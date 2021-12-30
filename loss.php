<?php
require_once 'vendor/autoload.php';

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');

$dataset = Labeled::fromIterator(new CSV('progress-simple-3.csv', true, ','));
$mcc = $dataset->samples();
$loss = $dataset->labels();

// print_r($mcc);
// print_r($loss);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sentiment Analysis</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/flatty.bootstrap.min.css" />
    <link rel="stylesheet" href="css/main.css" />
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">EDOM Sentiment Analysis</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sentiment</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownId">
                        <a class="dropdown-item" href="dataset.php">Dataset</a>
                        <a class="dropdown-item" href="validation.php">Validation</a>
                        <a class="dropdown-item" href="loss.php">MCC & Loss</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h4 class="mt-5">MCC & Loss</h4>
        <div class="row mt-5 mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div id="chart-mcc"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div id="chart-loss"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/apexchart/apexcharts.min.js"></script>

    <script>
        var options = {
            series: [{
                name: "Loss",
                data: [
                    <?php foreach ($loss as $l) {
                        echo round($l, 3) . ',';
                    }
                    ?>
                ]
            }],
            colors: ['#e74c3c'],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -10,
                offsetX: -8,
                background: {
                    enabled: false,
                },
                style: {
                    fontSize: '10px',
                    colors: ['#000000'],
                    fontWeight: 400
                },
            },
            stroke: {
                curve: 'straight',
                width: 1
            },
            markers: {
                size: 2,
                shape: "square",
            },
            title: {
                text: 'Cross Entropy (Log Loss)',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: [
                    <?php foreach (array_keys($loss) as $l) {
                        echo '"' . intval($l + 1) . '",';
                    }
                    ?>
                ],
                title: {
                    text: "Epoch",
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-loss"), options);
        chart.render();

        var options_mcc = {
            series: [{
                name: "MCC",
                data: [
                    <?php foreach ($mcc as $l) {
                        echo round($l[0], 3) . ',';
                    }
                    ?>
                ]
            }],

            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -8,
                offsetY: -4,
                background: {
                    enabled: false,
                },
                style: {
                    fontSize: '10px',
                    colors: ['#000000'],
                    fontWeight: 400
                },
            },
            stroke: {
                curve: 'straight',
                width: 1
            },
            markers: {
                size: 2,
                shape: "square",
            },
            title: {
                text: 'MCC Score',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: [
                    <?php foreach (array_keys($mcc) as $l) {
                        echo '"' . intval($l + 1) . '",';
                    }
                    ?>
                ],
                title: {
                    text: "Epoch",
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-mcc"), options_mcc);
        chart.render();
    </script>
</body>

</html>