<?php
namespace MachineLearning\Application\Normalization;

use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Model\ValueInterface;

interface NormalizationInterface
{
    /**
     * @param Dataset $data
     * @return ValueInterface $coefficient
     */
    public function calculateCoefficient(Dataset $data);

    /**
     * @param ValueInterface $value
     * @param ValueInterface $coefficient
     * @return ValueInterface
     */
    public function normalizeValue(ValueInterface $value, ValueInterface $coefficient);

    /**
     * @param Dataset $data
     * @param ValueInterface $coefficient
     * @return Dataset
     */
    public function normalizeDataset(Dataset $data, ValueInterface $coefficient);

}