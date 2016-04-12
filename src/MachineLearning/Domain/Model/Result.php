<?php

namespace MachineLearning\Domain\Model;


class Result
{

    /**
     * @var ValueInterface
     */
    protected $independentVariable;

    /**
     * @var ValueInterface
     */
    protected $dependentVariable;

    /**
     * @param ValueInterface $independentVariable
     * @param ValueInterface $dependentVariable
     */
    public function __construct(ValueInterface $independentVariable, ValueInterface $dependentVariable)
    {
        $this->independentVariable = $independentVariable;
        $this->dependentVariable = $dependentVariable;
    }

    /**
     * @return ValueInterface
     */
    public function getIndependentVariable()
    {
        return $this->independentVariable;
    }

    /**
     * @return ValueInterface
     */
    public function getDependentVariable()
    {
        return $this->dependentVariable;
    }
}