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

        //@todo we could validate if value and coefficient have same same dimension
        //should be done in a upper layer, in order not to execute it every row

        for ($i=0; $i<count($rawValue); $i++) {
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

        $featuresMinimumValue = [];
        $featuresMaximumValue = [];
        $featuresAverage = array_fill(0, $numberFeatures, 0);
        $featuresRange = array_fill(0, $numberFeatures, 0);

        //@todo solve this in another way...tremendous
        foreach ($data as $row) {
            $features = $row->getIndependentVariable()->getValue();
            for ($i = 0; $i < count($features); $i++) {
                if (!isset($featuresMaximumValue[$i]) || $featuresMaximumValue[$i] < $features[$i]) {
                    $featuresMaximumValue[$i] = $features[$i];
                }
                if (!isset($featuresMinimumValue[$i]) || $featuresMinimumValue[$i] > $features[$i]) {
                    $featuresMinimumValue[$i] = $features[$i];
                }
                $featuresAverage[$i] = $featuresAverage[$i]+$features[$i];
            }
        }

        foreach ($featuresAverage as $i => $featureAverage) {
            $featuresAverage[$i] = $featureAverage / count($data);
            $featuresRange[$i] = ($featuresMaximumValue[$i] - $featuresMinimumValue[$i]) > 0 ?
                ($featuresMaximumValue[$i] - $featuresMinimumValue[$i])
                : 1;
        }

        return new VectorValue([
            static::COEFFICIENT_AVERAGE => $featuresAverage,
            static::COEFFICIENT_RANGE => $featuresRange
        ]);
    }

}