<?php

namespace MachineLearning\Application\Normalization;

use MachineLearning\Domain\Model\Value\VectorValue;
use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Model\ValueInterface;

/**
 * Normalize substracting dataset mean and divide by value range
 * So value ranges goes to -1,1
 */
class MeanScaleNormalization extends AbstractNormalization
{
    const COEFFICIENT_AVERAGE = 0;
    const COEFFICIENT_RANGE = 1;

    /**
     * @inheritdoc
     */
    public function normalizeValue(ValueInterface $value, ValueInterface $coefficient)
    {
        $rawCoefficient = $coefficient->getValue();
        $rawValue = $value->getValue();

        $numberColumns = count($rawValue);
        for ($i=0; $i<$numberColumns; $i++) {
            $rawValue[$i] =
                (
                    $rawValue[$i] - $rawCoefficient[static::COEFFICIENT_AVERAGE][$i]
                )
                / $rawCoefficient[static::COEFFICIENT_RANGE][$i]
            ;
        }

        return new VectorValue($rawValue);

    }

    /**
     * @inheritdoc
     */
    public function calculateCoefficient(Dataset $data)
    {
        $numberFeatures = count($data->first()->getIndependentVariable()->getValue());
        $numberRows = count($data);

        $featuresMinimumValue = [];
        $featuresMaximumValue = [];
        $featuresSum = array_fill(0, $numberFeatures, 0);
        $featuresAverage = array_fill(0, $numberFeatures, 0);
        $featuresRange = array_fill(0, $numberFeatures, 0);

        //@todo solve this in another way...tremendous
        list($featuresMaximumValue, $featuresMinimumValue, $featuresSum) = $this->prepareNormalizationData(
            $data,
            $numberFeatures,
            $featuresMaximumValue,
            $featuresMinimumValue,
            $featuresSum
        );

        foreach ($featuresSum as $i => $featureSum) {
            $featuresAverage[$i] = $featureSum / $numberRows;
            $featuresRange[$i] = ($featuresMaximumValue[$i] - $featuresMinimumValue[$i]) > 0 ?
                ($featuresMaximumValue[$i] - $featuresMinimumValue[$i])
                : 1;
        }

        return new VectorValue([
            static::COEFFICIENT_AVERAGE => $featuresAverage,
            static::COEFFICIENT_RANGE => $featuresRange
        ]);
    }

    /**
     * @param Dataset $data
     * @param $numberFeatures
     * @param $featuresMaximumValue
     * @param $featuresMinimumValue
     * @param $featuresAverage
     * @return array
     */
    protected function prepareNormalizationData(Dataset $data, $numberFeatures, $featuresMaximumValue, $featuresMinimumValue, $featuresSum)
    {
        foreach ($data as $row) {
            $features = $row->getIndependentVariable()->getValue();
            for ($i = 0; $i < $numberFeatures; $i++) {
                if (!isset($featuresMaximumValue[$i]) || $featuresMaximumValue[$i] < $features[$i]) {
                    $featuresMaximumValue[$i] = $features[$i];
                }
                if (!isset($featuresMinimumValue[$i]) || $featuresMinimumValue[$i] > $features[$i]) {
                    $featuresMinimumValue[$i] = $features[$i];
                }
                $featuresSum[$i] = $featuresSum[$i] + $features[$i];
            }
        }
        return array($featuresMaximumValue, $featuresMinimumValue, $featuresSum);
    }

}
