# MachineLearning
Simple stupid machine learning library for php

[![Build Status](https://scrutinizer-ci.com/g/zeopix/MachineLearning/badges/build.png?b=master)](https://scrutinizer-ci.com/g/zeopix/MachineLearning/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zeopix/MachineLearning/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zeopix/MachineLearning/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/zeopix/MachineLearning/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/zeopix/MachineLearning/?branch=master)

###### Warning
This library is not designed for production or high performance requirements, it's more a proof of concept and framework to play with Machine Learning Algorithms.

## Features
- Gradient Descent Algorithm
- Mean Scale Normalization
- Linear Hypothesis for multiple unlimited variables

## Usage
Include with composer
```
composer require zeopix/machine-learning
```

#### Train your dataset
```
use Zeopix\MachineLearning\Application\Service\LinearRegressionService

$linearRegressionService = new LinearRegressionService();
$data = [
 [[2,3], 1],
 [[4,6], 2]
];
$training = $linearRegressionService->train($data);

$prediction = $training->predict([8,12]);
```

