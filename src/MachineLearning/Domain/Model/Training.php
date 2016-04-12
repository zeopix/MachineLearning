<?php
namespace MachineLearning\Domain\Model;

use MachineLearning\Application\Algorithm\AlgorithmInterface;
use MachineLearning\Application\Normalization\NormalizationInterface;
use MachineLearning\Domain\Hypothesis\HypothesisInterface;

class Training
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var ValueInterface
     */
    private $algorithmCoefficient;

    /**
     * @var AlgorithmInterface
     */
    private $algorithm;

    /**
     * @var ValueInterface
     */
    private $normalizationCoefficient;

    /**
     * @var NormalizationInterface
     */
    private $normalization;

    /**
     * @var HypothesisInterface
     */
    private $hypothesis;

    /**
     * Training constructor.
     * @param array $data
     * @param ValueInterface $algorithmCoefficient
     * @param AlgorithmInterface $algorithm
     * @param ValueInterface $normalizationCoefficient
     * @param NormalizationInterface $normalization
     * @param HypothesisInterface $hypothesis
     */
    public function __construct(
        array $data,
        AlgorithmInterface $algorithm,
        ValueInterface $algorithmCoefficient,
        NormalizationInterface $normalization,
        ValueInterface $normalizationCoefficient,
        HypothesisInterface $hypothesis
    ) {
        $this->data = $data;
        $this->algorithmCoefficient = $algorithmCoefficient;
        $this->algorithm = $algorithm;
        $this->normalizationCoefficient = $normalizationCoefficient;
        $this->normalization = $normalization;
        $this->hypothesis = $hypothesis;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return ValueInterface
     */
    public function getAlgorithmCoefficient()
    {
        return $this->algorithmCoefficient;
    }

    /**
     * @return AlgorithmInterface
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @return ValueInterface
     */
    public function getNormalizationCoefficient()
    {
        return $this->normalizationCoefficient;
    }

    /**
     * @return NormalizationInterface
     */
    public function getNormalization()
    {
        return $this->normalization;
    }

    /**
     * @return HypothesisInterface
     */
    public function getHypothesis()
    {
        return $this->hypothesis;
    }

    /**
     * @param ValueInterface
     * @return ValueInterface
     */
    public function predict(ValueInterface $value)
    {
        $normalizedValue = $this->normalization->normalizeValue($value, $this->normalizationCoefficient);
        return $this->hypothesis->calculate($this->algorithmCoefficient, $normalizedValue);
    }

}
