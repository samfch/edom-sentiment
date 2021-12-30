<?php
require_once 'vendor/autoload.php';

use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Extractors\CSV;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');

$dataset = Labeled::fromIterator(new CSV('dataset/data-edom-label-fix.csv', true, ';'));
$num_dataset = count($dataset->labels());
$distributions = array_count_values($dataset->labels());
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
        <div class="row mt-5 mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dataset</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td scope="row">Numbers of data</td>
                                    <td><?= $num_dataset; ?></td>
                                </tr>
                                <tr>
                                    <td scope="row"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Data distribution (by label)</h5>
                        <div id="chart"></div>
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
            series: [<?= $distributions['positif']; ?>, <?= $distributions['negatif']; ?>, <?= $distributions['netral']; ?>],
            colors: ['#52cdb5','#e74c3c','#24282c'],
            chart: {
                width: '100%',
                type: "pie",
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                    },
                },
            },

            legend: {
                position: "top",
            },
            labels: ["Positif", "Negatif", "Netral"],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200,
                    },
                    legend: {
                        position: "bottom",
                    },
                },
            }, ],
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
</body>

</html>