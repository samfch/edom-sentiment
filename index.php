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
        <div class="row mt-5">
            <div class="col-sm">
                <h5>Sentiment Analysis on EDOM</h5>
                Sentiment analysis dengan Deep Learning untuk klasifikasi komentar
                mahasiswa pada sistem EDOM Unissula
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-6">
                <h5>Write/paste sentence</h5>
                <form action="" method="post">
                    <div class="form-group">
                        <label for=""></label>
                        <textarea class="form-control" required="required" name="sentence" id="sentence" rows="7"></textarea>
                    </div>
                    <button type="button" class="btn-predict btn btn-primary">Predict</button>
                </form>
            </div>
            <div class="spinner col-sm text-center" style="display: none;">
                <div class="cube1"></div>
                <div class="cube2"></div>
                <div>Predicting...</div>
            </div>
            <div class="col result-div" style="display: none;">
                <h5>Prediction : <span id="predict" class=""></span></h5>
                <div>
                    <small>Result for sentence:</small>
                    <div class="text-info">"here is the sentence to predict by this app"</div>
                </div>
                <table class="table table-sm mt-4">
                    <tbody>
                        <tr>
                            <td scope="row"><span class="text-success positif-percent">-</span></td>
                        </tr>
                        <tr>
                            <td scope="row"><span class="text-danger negatif-percent">-</span></td>
                        </tr>
                        <tr>
                            <td scope="row"><span class="netral-percent">-</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/simpleAjax.min.js"></script>

    <script>
        $('.btn-predict').on('click', function() {

            if ($.trim($('#sentence').val()) == '') {
                alert('Input can not blank');
                return false;
            } 
            const simpleajax = new simpleAJAX;
            if ($('.result-div').length != null) {
                $('.result-div').hide();
            }

            $('.spinner').show();

            const data = {
                "sentence": $('#sentence').val()
            }
            predictions = simpleajax.post('predict-single.php',

                data, (err, predictions) => {
                    if (err) {
                        console.log(err)
                    } else {
                        $('.result-div').show();
                        var json_obj = $.parseJSON(predictions)
                        $('.text-info').html("<i>'" + json_obj.sentence + "'</i>");
                        $('.positif-percent').html("positif : " + parseFloat(json_obj.scores[0].positif * 100).toFixed(2) + "%");
                        $('.negatif-percent').html("negatif : " + parseFloat(json_obj.scores[0].negatif * 100).toFixed(2) + "%");
                        $('.netral-percent').html("netral : " + parseFloat(json_obj.scores[0].netral * 100).toFixed(2) + "%");
                        $('#predict').html(json_obj.prediction);
                        $('#predict').removeClass().addClass(json_obj.prediction);
                        $('.spinner').hide();
                        console.log(json_obj.sentence);
                    }
                });
        });
    </script>

</body>

</html>