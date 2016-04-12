<?php

namespace MachineLearning\Domain\Hypothesis;

use MachineLearning\Domain\Model\Result;
use MachineLearning\Domain\Model\ValueInterface;

interface HypothesisInterface
{
    /**
     * @param ValueInterface $coefficient
     * @param ValueInterface $variable
     * @return ValueInterface
     */
    public function calculate(ValueInterface $coefficient, ValueInterface $variable);

    /**
     * @param ValueInterface $coefficient
     * @param ValueInterface $variable
     * @param int $partialVariable
     * @return ValueInterface
     */
    public function derivative(ValueInterface $coefficient, ValueInterface $variable, $partialVariable);

    /**
     * @param array $data
     * @return Result
     */
    public function createResultFromData(array $data);
}
