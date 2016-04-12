# MachineLearning
Simple stupid machine learning library for php
![Scrutinizer Rating](https://scrutinizer-ci.com/g/zeopix/MachineLearning/badges/quality-score.png?b=master "Scrutinizer Rating")

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
$data = [
 [[2,3], 1],
];
$training = 


