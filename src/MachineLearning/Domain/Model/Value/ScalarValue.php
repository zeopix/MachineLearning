<?php
namespace MachineLearning\Domain\Model\Value;
use MachineLearning\Domain\Model\ValueInterface;

class ScalarValue implements ValueInterface
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @param float $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function scalar(ValueInterface $value)
    {
        return $value->getValue()*$this->getValue();
    }


}