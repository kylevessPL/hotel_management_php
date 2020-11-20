<?php
include 'helpers/include_all.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body>
<?php view('navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>Feel free to contact us if you have any any questions regarding our service</p>
            <div class="row">
                <div class="col-10 col-xl-7 mb-4 mb-lg-0">
                    <div class="card">
                        <img class="card-img-top h-50" src="/assets/images/logo_full.PNG" alt="HoteLA logo">
                        <div class="card-body">
                            <p class="card-text">Some example text.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-5">
                    <div class="container-fluid">
                        <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDZhKZ5AjqR2uMcr9ujCJQVUoox8_SHNiA&q=al.+Marszałka+Józefa+Piłsudskiego+75,+90-368+Łódź" width="100%" height="450" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="row text-center mt-4">
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-map-marker-alt pt-1" style="font-size: 26px; color: white;"></i>
                            </a>
                            <p>Piłsudskiego 75</p>
                            <p class="mb-md-0">90-368 Łódź</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-phone pt-1" style="font-size: 26px; color: white;"></i>
                            </a>
                            <p>42 25 30 679</p>
                            <p class="mb-md-0">Mon - Sat, 8:00 - 22:00</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-envelope pt-1" style="font-size: 26px; color: white;"></i>
                            </a>
                            <p>office@hotela.pl</p>
                            <p class="mb-0">info@hotela.pl</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php view('footer.php'); ?>
        </main>
    </div>
</div>
<?php view('sign_out_modal.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>