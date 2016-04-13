<?php
namespace MachineLearning\Domain\Model;

interface ValueInterface
{

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * Return scalar product between values
     * @param ValueInterface $value
     * @return double
     * @throws \DomainException
     */
    public function scalar(ValueInterface $value);
}
