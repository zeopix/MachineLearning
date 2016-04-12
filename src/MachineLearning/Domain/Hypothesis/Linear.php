<?php
namespace MachineLearning\Domain\Hypothesis;

use MachineLearning\Domain\Exception\WrongVariableForHypothesisException;
use MachineLearning\Domain\Model\Result;
use MachineLearning\Domain\Model\Value\VectorValue;
use MachineLearning\Domain\Model\Value\ScalarValue;
use MachineLearning\Domain\Model\ValueInterface;

class Linear implements HypothesisInterface
{

    /**
     * @inheritdoc
     */
    public function calculate(ValueInterface $coefficient, ValueInterface $variable)
    {
        if (! $variable instanceof VectorValue) {
            throw new WrongVariableForHypothesisException();
        }

        list($offset, $firstOrderCoefficients) = $this->getParameters($coefficient, $variable);

        $value = $offset+$variable->scalar($firstOrderCoefficients);
        return new ScalarValue($value);
    }

    /**
     * @inheritdoc
     */
    public function derivative(ValueInterface $coefficient, ValueInterface $variable, $partialVariable)
    {
        if (! $variable instanceof VectorValue) {
            throw new WrongVariableForHypothesisException();
        }

        $x = $variable->getValue();

        return new ScalarValue($x[$partialVariable]);
    }

    /**
     * @inheritdoc
     */
    public function createResultFromData(array $data)
    {
        return new Result(
            new VectorValue($data[0]),
            new ScalarValue($data[1])
        );
    }


    /**
     * @param ValueInterface $coefficient
     * @param ValueInterface $variable
     * @return array
     */
    protected function getParameters(ValueInterface $coefficient, ValueInterface $variable)
    {
        $coefficientValues = $coefficient->getValue(); //e.g. [1,1,3]

        $offset = $coefficientValues[0];
        array_shift($coefficientValues);

        $firstOrderCoefficients = new VectorValue($coefficientValues);

        return array($offset, $firstOrderCoefficients);
    }
}
