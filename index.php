<?php

include 'helpers/get_view.php';

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/images/favicon.ico" rel="shortcut icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/index.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">

    <title>HoteLA Home</title>
</head>
<body class="min-vh-100 d-flex flex-column">
<div class="container-fluid main-container flex-grow-1 px-0">
    <div id="carousel-overview" class="carousel slide carousel-fade" data-ride="carousel" data-interval="4000">
        <ul class="carousel-indicators">
            <li data-target="#carousel-overview" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-overview" data-slide-to="1"></li>
            <li data-target="#carousel-overview" data-slide-to="2"></li>
            <li data-target="#carousel-overview" data-slide-to="3"></li>
            <li data-target="#carousel-overview" data-slide-to="4"></li>
        </ul>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/reception.png" alt="Reception desk">
                <div class="carousel-caption text-left">
                    <h2>Welcome to HoteLA</h2>
                    <p>HoteLA is one of the most luxurious hotels located in the centre of Łódź.</p>
                    <p>The perfect place to relax and rest after a day full of adventures.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/lounge.png" alt="Lounge & Guest room">
                <div class="carousel-caption text-left">
                    <h2>Lounge & Guest room</h2>
                    <p>A large lounge & guest room with 100″ TV to ensure our gests feel comfortable and relax</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/cafe.png" alt="One of your 3 cafes">
                <div class="carousel-caption text-left">
                    <h2>Cafes</h2>
                    <p>Our guests can order a coffee or eat some sweet biscuit in one of our 3 hotel cafes</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/pool.png" alt="Swimming pool">
                <div class="carousel-caption text-left">
                    <h2>Swimming pool</h2>
                    <p>HoteLA features 67-foot swimming pool</p>
                    <p>A heated hydrotherapy hot tub complements the welcoming</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/restaurant.png" alt="One of our 5 restaurants">
                <div class="carousel-caption text-left">
                    <h2>Restaurants</h2>
                    <p>HoteLA offers 5 different restaurant, each of them differs with its unique cousine</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php view('footer_index.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>