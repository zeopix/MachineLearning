<?php

namespace MachineLearning\Application\Normalization;

use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Model\ValueInterface;


abstract class AbstractNormalization implements NormalizationInterface
{
    /**
     * @inheritdoc
     */
    public function normalizeDataset(Dataset $data, ValueInterface $coefficient)
    {
        $normalizedData = [];
        foreach ($data as $row) {
            $features = $row->getIndependentVariable();
            $features = $this->normalizeValue($features, $coefficient);
            $normalizedData[] = [
                $features->getValue(),
                $row->getDependentVariable()->getValue()
            ];
        }

        return new Dataset($normalizedData, $data->getHypothesis());
    }

    /**
     * @inheritdoc
     */
    abstract public function normalizeValue(ValueInterface $value, ValueInterface $coefficient);
}