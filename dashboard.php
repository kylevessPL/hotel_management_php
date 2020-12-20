<?php
include 'helpers/include_all.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body class="min-vh-100 d-flex flex-column">
<?php view('navbar.php'); ?>
<div class="container-fluid main-container flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View your recent bookings and latest transactions</p>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card card-animated-2 bg-success text-white overflow-hidden">
                        <div class="card-body bg-success">
                            <div class="rotate rotate-custom float-right" style="z-index: 5;">
                                <i class="fas fa-tags fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Bookings</h6>
                            <h1 class="display-4">134</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card card-animated-2 text-white bg-danger overflow-hidden">
                        <div class="card-body bg-danger">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-money-check-alt fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Payments</h6>
                            <h1 class="display-4">87</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card card-animated-2 text-white bg-info overflow-hidden">
                        <div class="card-body bg-info">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-bed fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Available rooms</h6>
                            <h1 class="display-4">125</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card card-animated-2 text-white bg-warning overflow-hidden">
                        <div class="card-body">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-hand-holding-usd fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Cheapest room</h6>
                            <h1 class="display-4">36</h1>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>
