<?php

namespace MachineLearning\Application\Algorithm;

use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Hypothesis\Linear;

class GradientDescendentTest extends \PHPUnit_Framework_TestCase
{

    public function testTrainDataProvider()
    {
        return [
            'normal function' => [
                'trainData' => [
                    [[10, 2],34],
                    [[5,2], 19],
                    [[5,4],23],
                    [[0,0], 0],
                    //coef: [3, 2]
                ],
                'testData' => [
                    [[5, 2], 19]
                ],
                'relativeErrorMargin' => 0.01,
            ],
        ];
    }
    /**
     * @param $trainData
     * @param $testData
     * @param $relativeErrorMargin
     *
     * @dataProvider testTrainDataProvider
     */
    public function testTrain(
        $trainData,
        $testData,
        $relativeErrorMargin
    ) {
        $hypothesis = new Linear();
        $dataset = new Dataset(
            $trainData,
            $hypothesis
        );

        $algorithm = new GradientDescendent(
            $hypothesis
        );

        $coefficient = $algorithm->train($dataset);

        foreach ($testData as $testCase) {
            $idealResult = $hypothesis->createResultFromData($testCase);
            $idealResultValue = $idealResult->getDependentVariable()->getValue();
            $errorMargin = $relativeErrorMargin*$idealResultValue;
            $resultVariableValue = $hypothesis->calculate($coefficient, $idealResult->getIndependentVariable())->getValue();

            $this->assertGreaterThan($idealResultValue-$errorMargin, $resultVariableValue);
            $this->assertLessThan($idealResultValue+$errorMargin, $resultVariableValue);

        }
    }

    public function testNormalize()
    {

    }
}