<?php

namespace MachineLearning\Domain\Hypothesis;

use MachineLearning\Domain\Model\Value\VectorValue;

class LinearTest extends \PHPUnit_Framework_TestCase
{
    public function testCalculate()
    {
        $coefficient = new VectorValue([2,4,3]);
        $value = new VectorValue([2,5]);
        $hypothesis = new Linear();
        $this->assertEquals((2+4*2+3*5), $hypothesis->calculate($coefficient, $value)->getValue());
    }

    public function testDerivate()
    {
        $coefficient = new VectorValue([2,4]);
        $value = new VectorValue([2,5]);
        $hypothesis = new Linear();
        $this->assertEquals(5, $hypothesis->derivative($coefficient, $value, 1)->getValue());
    }
}