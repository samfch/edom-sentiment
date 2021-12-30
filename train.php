<?php
require_once 'vendor/autoload.php';

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\HTMLStripper;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\Other\Tokenizers\NGram;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\ZScaleStandardizer;
use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\Optimizers\AdaMax;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Other\Loggers\Screen;
use League\Csv\Writer;
use Rubix\ML\CrossValidation\Metrics\MCC;
use Rubix\ML\Extractors\CSV;
use Rubix\ML\NeuralNet\CostFunctions\CrossEntropy;
use Rubix\ML\NeuralNet\Layers\Dropout;
use function Rubix\ML\array_transpose;

ini_set('memory_limit', '-1');

echo 'Loading data ...' . PHP_EOL;

$dataset = Labeled::fromIterator(new CSV('dataset/data-edom-label-fix.csv',true,';'));

// print_r($dataset->samples());
// print_r($dataset->labels());

$estimator = new PersistentModel(
    new Pipeline([
        new HTMLStripper(),
        new TextNormalizer(),
        new WordCountVectorizer(10000, 1, 10000, new NGram(1,3)),
        new TfIdfTransformer(),
        // new ZScaleStandardizer(),
        // $token_transformer = new WordCountVectorizer(10000, 1, 10000, new NGram(1,3))
    ], new MultilayerPerceptron([
        // new Dense(200),
        new Dense(200),
        new Activation(new LeakyReLU()),
        new Dropout(0.3),

        new Dense(20),
        new Activation(new LeakyReLU()),
        new Dropout(0.3),

        new Dense(3),
        new Activation(new LeakyReLU()),

        new PReLU(),
    ], 100, new AdaMax(0.001), 1e-4, 100, 1e-3, 3, 0.1, new CrossEntropy(), new MCC())
),
    new Filesystem('sentiment.model.3', true)
);

$estimator->setLogger(new Screen('sentiment'));

echo 'Training ...' . PHP_EOL;

$estimator->train($dataset);

$scores = $estimator->scores();
$losses = $estimator->steps();

$writer = Writer::createFromPath('progress-simple-3.csv', 'w+');
$writer->insertOne(['score', 'loss']);
$writer->insertAll(array_transpose([$scores, $losses]));

echo 'Progress saved to progress.csv' . PHP_EOL;

// if (strtolower(trim(readline('Save this model? (y|[n]): '))) === 'y') {
    $estimator->save();
// }

