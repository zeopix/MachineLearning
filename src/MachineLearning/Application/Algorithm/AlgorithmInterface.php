<?php

namespace MachineLearning\Application\Algorithm;

use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Model\ValueInterface;

interface AlgorithmInterface
{
    /**
     * @param Dataset $dataset
     * @return ValueInterface
     */
    public function train(Dataset $dataset);

}