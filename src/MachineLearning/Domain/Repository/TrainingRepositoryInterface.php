<?php

namespace MachineLearning\Domain\Repository;

use MachineLearning\Domain\Model\Training;

interface TrainingRepositoryInterface
{
    public function save(Training $training);
}