<?php
namespace MachineLearning\Domain\Model\Value;
use MachineLearning\Domain\Model\ValueInterface;

class VectorValue implements ValueInterface
{
    /**
     * @var float[]
     */
    protected $value;

    /**
     * @param float[] $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return \float[]
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
        $result = 0;
        for ($i=0; $i<count($this->value); $i++) {
            $result += $this->value[$i]*$value->getValue()[$i];
        }
        return $result;
    }

}