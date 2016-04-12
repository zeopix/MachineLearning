<?php

namespace MachineLearning\Application\Algorithm;

use MachineLearning\Domain\Exception\DivergenceException;
use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Hypothesis\HypothesisInterface;
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
     * @var float
     */
    protected $learningRate;

    /**
     * @var float
     */
    protected $convergenceCriteria;

    /**
     * @var float
     */
    protected $divergenceCriteria;

    /**
     * @param HypothesisInterface $hypothesis
     * @param float $learningRate
     * @param float $convergenceCriteria
     * @param int $divergenceCriteria
     */
    public function __construct(
        HypothesisInterface $hypothesis,
        $learningRate = self::DEFAULT_LEARNING_RATE,
        $convergenceCriteria = self::DEFAULT_CONVERGENCE_CRITERIA,
        $divergenceCriteria = self::DEFAULT_DIVERGENCE_CRITERIA
    ) {
        $this->hypothesis = $hypothesis;
        $this->learningRate = $learningRate;
        $this->convergenceCriteria = $convergenceCriteria;
        $this->divergenceCriteria = $divergenceCriteria;
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
            $coefficient = new VectorValue($coefficientVector);

            $costVector = array_fill(0, $features+1, 0);

            foreach ($dataset as $result) {
                $firstOrderIncrement = (
                        (float) $this->hypothesis->calculate(
                            $coefficient,
                            $result->getIndependentVariable()
                        )->getValue()
                        - (float) $result->getDependentVariable()->getValue()
                    );
                $costVector[0] += $firstOrderIncrement;

                for ($j = 1; $j < $features+1; $j++) {
                    $costVector[$j] += $firstOrderIncrement
                        * (float) $this->hypothesis->derivative(
                            $coefficient,
                            $result->getIndependentVariable(),
                            $j-1
                        )->getValue();
                }
            }

            for ($j = 0; $j < $features+1; $j++) {
                $incrementVector[$j] = $this->learningRate * -(1 / ($total)) * $costVector[$j];
            }


            $convergence = (bool) (abs(array_sum($incrementVector)) < $this->convergenceCriteria);
            $divergence = (bool) (abs(array_sum($incrementVector)) > $this->divergenceCriteria);

            for ($j = 0; $j < $features+1; $j++) {
                $coefficientVector[$j] += $incrementVector[$j];
            }
        }

        if ($divergence) {
            throw new DivergenceException();
        }

        return new VectorValue($coefficientVector);
    }

}