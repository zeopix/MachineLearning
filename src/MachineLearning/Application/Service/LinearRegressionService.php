<?php

namespace MachineLearning\Application\Service;

use MachineLearning\Application\Algorithm\GradientDescendent;
use MachineLearning\Application\Normalization\MeanScaleNormalization;
use MachineLearning\Domain\Hypothesis\Linear;
use MachineLearning\Domain\Model\Dataset;
use MachineLearning\Domain\Model\Training;

class LinearRegressionService
{

    /**
     * @param array $data
     * @return Training
     * @throws \MachineLearning\Domain\Exception\DivergenceException
     */
    public function train($data)
    {

        $hypothesis = new Linear();
        $normalization = new MeanScaleNormalization();
        $algorithm = new GradientDescendent($hypothesis);
        $dataset = new Dataset($data, $hypothesis);

        $normalizationCoefficient = $normalization->calculateCoefficient($dataset);
        $dataset = $normalization->normalizeDataset($dataset, $normalizationCoefficient);

        $algorithmCoefficient = $algorithm->train($dataset);

        return new Training(
            $data,
            $algorithm,
            $algorithmCoefficient,
            $normalization,
            $normalizationCoefficient,
            $hypothesis
        );

    }
}