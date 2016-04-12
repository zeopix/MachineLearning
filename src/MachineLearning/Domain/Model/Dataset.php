<?php
namespace MachineLearning\Domain\Model;

use MachineLearning\Domain\Hypothesis\HypothesisInterface;

class Dataset implements \Iterator, \Countable
{
    /**
     * @var array[]
     */
    protected $data;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var HypothesisInterface
     */
    protected $hypothesis;

    /**
     * @param array[] $data
     */
    public function __construct(
        array $data,
        HypothesisInterface $hypothesis
    ) {
        $this->position = 0;
        $this->hypothesis = $hypothesis;
        $this->data = $data;
    }

    public function first()
    {
        return $this->hypothesis->createResultFromData(
            $this->data[0]
        );
    }

    /**
     * @return Result
     */
    public function current()
    {
        return $this->hypothesis->createResultFromData(
            $this->data[$this->position]
        );
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->data);
    }

    /**
     * @return HypothesisInterface
     */
    public function getHypothesis()
    {
        return $this->hypothesis;
    }

}