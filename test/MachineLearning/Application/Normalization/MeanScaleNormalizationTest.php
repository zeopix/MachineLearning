<?php

namespace MachineLearning\Application\Normalization;

use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Hypothesis\Linear;

class MeanScaleNormalizationTest extends \PHPUnit_Framework_TestCase
{

    public function testTrainDataProvider()
    {
        return [
            'normalize 2 features dataset' => [
                'data' => [
                    [[10, 2],34],
                    [[5,2], 19],
                    [[5,4],23],
                    [[0,0], 0],
                ],
                'expectedNormalizedData' => [
                    [[0.5, 0], 34],
                    [[0, 0], 19],
                    [[0, 0.5], 23],
                    [[-0.5, -0.5], 0],
                ],
                'expectedCoefficientValue' => [
                    [5,2],
                    [10,4]
                ]
            ],
        ];
    }
    /**
     * @param array $data
     * @param array $expectedNormalizedData
     *
     * @dataProvider testTrainDataProvider
     */
    public function testNormalize(
        $data,
        $expectedNormalizedData,
        $expectedCoefficientValue
    ) {
        $hypothesis = new Linear();
        $dataset = new Dataset(
            $data,
            $hypothesis
        );
        $expectedNormalizedDataset = new Dataset($expectedNormalizedData, $hypothesis);

        $normalization = new MeanScaleNormalization();
        $coefficient = $normalization->calculateCoefficient($dataset);
        $normalizedDataset = $normalization->normalizeDataset($dataset, $coefficient);

        $this->assertEquals($expectedCoefficientValue, $coefficient->getValue());
        $this->assertEquals($expectedNormalizedDataset, $normalizedDataset);
    }
}