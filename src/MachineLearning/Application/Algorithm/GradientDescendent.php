<?php

namespace MachineLearning\Application\Algorithm;

use MachineLearning\Domain\Exception\DivergenceException;
use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Hypothesis\HypothesisInterface;
use MachineLearning\Domain\Model\Result;
use MachineLearning\Domain\Model\ValueInterface;
use MachineLearning\Domain\Model\Value\VectorValue;

class GradientDescendent implements AlgorithmInterface
{
    const DEFAULT_LEARNING_RATE = 0.01;

    const DEFAULT_CONVERGENCE_CRITERIA = 0.0000001;

    const DEFAULT_DIVERGENCE_CRITERIA = 100;

    /**
     * @var HypothesisInterface
     */
    protected $hypothesis;

    /**
     * @var double
     */
    protected $learningRate;

    /**
     * @var double
     */
    protected $convergenceCriteria;

    /**
     * @var double
     */
    protected $divergenceCriteria;

    /**
     * @param HypothesisInterface $hypothesis
     * @param double $learningRate
     * @param double $convergenceCriteria
     * @param double $divergenceCriteria
     */
    public function __construct(
        HypothesisInterface $hypothesis,
        $learningRate = self::DEFAULT_LEARNING_RATE,
        $convergenceCriteria = self::DEFAULT_CONVERGENCE_CRITERIA,
        $divergenceCriteria = self::DEFAULT_DIVERGENCE_CRITERIA
    ) {
        $this->hypothesis = $hypothesis;
        $this->learningRate = (double) $learningRate;
        $this->convergenceCriteria = (double) $convergenceCriteria;
        $this->divergenceCriteria = (double) $divergenceCriteria;
    }

    /**
     * @param Dataset $dataset
     * @return ValueInterface
     * @throws DivergenceException
     */
    public function train(Dataset $dataset) {
        $convergence = false;
        $divergence = false;

        $firstResult = $dataset->current();
        $features = count($firstResult->getIndependentVariable()->getValue());
        $total = count($dataset);

        $coefficientVector = array_fill(0, $features+1, 1);
        $coefficientVector[0] = 0;
        $incrementVector = array_fill(0, $features+1, 0);

        while(!$convergence && !$divergence) {
            list($convergence, $divergence, $coefficientVector) = $this->doStep(
                $dataset,
                $coefficientVector,
                $features,
                $total,
                $incrementVector
            );
        }

        if ($divergence) {
            throw new DivergenceException();
        }

        return new VectorValue($coefficientVector);
    }

    /**
     * @param Dataset $dataset
     * @param $coefficientVector
     * @param $features
     * @param $total
     * @param $incrementVector
     * @return array
     */
    protected function doStep(Dataset $dataset, $coefficientVector, $features, $total, $incrementVector)
    {
        $coefficient = new VectorValue($coefficientVector);
        $costVector = array_fill(0, $features + 1, 0);

        foreach ($dataset as $result) {
            $costVector = $this->calculateStepCost($features, $coefficient, $result, $costVector);
        }

        for ($j = 0; $j < $features + 1; $j++) {
            $incrementVector[$j] = $this->learningRate * -(1 / ($total)) * $costVector[$j];
        }

        $convergence = (bool)(abs(array_sum($incrementVector)) < $this->convergenceCriteria);
        $divergence = (bool)(abs(array_sum($incrementVector)) > $this->divergenceCriteria);

        for ($j = 0; $j < $features + 1; $j++) {
            $coefficientVector[$j] += $incrementVector[$j];
        }
        return array($convergence, $divergence, $coefficientVector);
    }

    /**
     * @param int $features
     * @param ValueInterface $coefficient
     * @param Result $result
     * @param array $costVector
     */
    protected function calculateStepCost($features, ValueInterface $coefficient, Result $result, array $costVector)
    {
        $firstOrderIncrement = (
            (float)$this->hypothesis->calculate(
                $coefficient,
                $result->getIndependentVariable()
            )->getValue()
            - (float)$result->getDependentVariable()->getValue()
        );
        $costVector[0] += $firstOrderIncrement;

        for ($j = 1; $j < $features + 1; $j++) {
            $costVector[$j] += $firstOrderIncrement
                * (float)$this->hypothesis->derivative(
                    $coefficient,
                    $result->getIndependentVariable(),
                    $j - 1
                )->getValue();
        }
        return $costVector;
    }

}
