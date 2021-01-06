<?php

include 'helpers/get_view.php';

session_start();

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
    <link href="https://fonts.googleapis.com/css?family=Grand+Hotel" rel="stylesheet">
    <link href="/assets/css/index.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">

    <title>HoteLA Home</title>
</head>
<body class="min-vh-100 d-flex flex-column">
<nav class="navbar navbar-light bg-light p-2 flex-shrink-0">
    <div class="d-flex justify-content-between col-12 col-md-3 col-lg-2 mb-lg-0">
        <a class="navbar-brand d-flex justify-content-between align-middle" href="/dashboard">
            <img src="/assets/images/favicon.ico" alt="HoteLA logo" width="52" height="52" class="mr-3">
            HoteLA
        </a>
    </div>
    <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
        <ul class="nav">
            <?php if (isset($_SESSION['user_id'])) { echo '
            <li class="nav-item">
                <a class="btn btn-success nav-link" href="/dashboard"><i class="las la-external-link-alt la-lg mr-2"></i>Dashboard</a>
            </li>
            '; } else { echo '
            <li class="nav-item mr-3">
                <a class="btn btn-outline-success nav-link" href="/login">Log In</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-primary nav-link" href="/login?action=register">Sign Up</a>
            </li>
            '; }?>
        </ul>
    </div>
</nav>
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
                <div class="carousel-caption text-left d-flex flex-column justify-content-center">
                    <h2>Welcome to HoteLA</h2>
                    <p>HoteLA is one of the most luxurious hotels located in the centre of Łódź</p>
                    <p>The perfect place to relax and rest after a day full of adventures</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/lounge.png" alt="Lounge & Guest room">
                <div class="carousel-caption text-left d-flex flex-column justify-content-center">
                    <h2>Lounge & Guest room</h2>
                    <p>A large lounge & guest room with 100″ TV to ensure our gests feel comfortable and relax</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/cafe.png" alt="One of your 3 cafes">
                <div class="carousel-caption text-left d-flex flex-column justify-content-center">
                    <h2>Cafes</h2>
                    <p>Our guests can order a coffee or eat some sweet biscuit in one of our 3 hotel cafes</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/pool.png" alt="Swimming pool">
                <div class="carousel-caption text-left d-flex flex-column justify-content-center">
                    <h2>Swimming pool</h2>
                    <p>HoteLA features 67-foot swimming pool</p>
                    <p>A heated hydrotherapy hot tub complements the welcoming</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/restaurant.png" alt="One of our 5 restaurants">
                <div class="carousel-caption text-left d-flex flex-column justify-content-center">
                    <h2>Restaurants</h2>
                    <p>HoteLA offers 5 different restaurants, each of them differs with its unique cousine</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid footer-main justify-content-center flex-shrink-0">
    <footer>
        <div class="row justify-content-center text-center my-4">
            <div class="col-12 col-md mr-auto ml-auto">
                <span class="btn btn-circle btn-md mb-3">
                    <i class="las la-map-marker-alt pt-1 text-white" style="font-size: 26px;"></i>
                </span>
                <p>Piłsudskiego 75</p>
                <p class="mb-md-0">90-368 Łódź</p>
            </div>
            <div class="col-12 col-md pt-4 pt-md-0 mr-auto ml-auto">
                <span class="btn btn-circle animated-2 btn-md mb-3">
                    <i class="las la-phone pt-1 text-white" style="font-size: 26px;"></i>
                </span>
                <p>42 25 30 679</p>
                <p class="mb-md-0">Mon - Sat, 8:00 - 22:00</p>
            </div>
            <div class="col-12 col-md pt-4 pt-md-0 mr-auto ml-auto">
                <span class="btn btn-circle animated-2 btn-md mb-3">
                    <i class="las la-envelope pt-1 text-white" style="font-size: 26px;"></i>
                </span>
                <p>office@hotela.pl</p>
                <p class="mb-0">info@hotela.pl</p>
            </div>
        </div>
        <div class="row sub-footer px-3 py-3">
            <div class="col-12 d-flex justify-content-between">
                <span class="pt-2">Copyright © 2020-2021 HoteLA</span>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/login">Sign-In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/login?action=register">Register</a>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>