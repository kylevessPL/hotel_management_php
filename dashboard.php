<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId))
{
    $sql = "SELECT COUNT(cb.id) AS count from customers_bookings cb " .
    "INNER JOIN bookings b ON b.id = cb.booking_id " .
    "WHERE customer_id = '$customerId' AND book_date >= '".date('Y-m-d', strtotime('first day of january this year'))."'";
    $result = query($sql);
    $bookings_count = mysqli_fetch_assoc($result)['count'];

    $sql = "SELECT COUNT(id) AS count from payments WHERE booking_id IN (SELECT id from customers_bookings WHERE customer_id = '$customerId')";
    $result = query($sql);
    $payments_count = mysqli_fetch_assoc($result)['count'];
}

$sql = "SELECT MIN(standard_price) AS min from rooms WHERE id NOT IN (SELECT room_id from bookings_rooms WHERE booking_id IN (SELECT id from bookings WHERE status IN ('Paid', 'Unpaid')))";
$result = query($sql);
$min_price = (int) mysqli_fetch_assoc($result)['min'];

$start_date = date('Y-m-d', strtotime('next monday'));
$end_date = date('Y-m-d', strtotime($start_date . 'next sunday'));
$sql = "SELECT COUNT(id) AS count from rooms WHERE id NOT IN (SELECT room_id from bookings_rooms WHERE booking_id IN (SELECT id from bookings WHERE status != 'Cancelled' AND '$start_date' <= end_date AND '$end_date' >= start_date))";
$result = query($sql);
$rooms_count = (int) mysqli_fetch_assoc($result)['count'];

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
            <p>Check the statistics & get familiar with our offer</p>
            <div class="row mb-5">
                <?php if (isset($customerId)) { ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 bg-success text-white overflow-hidden">
                        <div class="card-body bg-success">
                            <div class="rotate rotate-custom float-right" style="z-index: 5;">
                                <i class="fas fa-tags fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Bookings</h6>
                            <div>
                                <h1 class="display-4"><?php echo $bookings_count; ?>
                                    <lead class="d-inline-block" style="font-size: 20px;">this year</lead>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 text-white bg-danger overflow-hidden">
                        <div class="card-body bg-danger">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-money-check-alt fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Payments</h6>
                            <div>
                                <h1 class="display-4"><?php echo $payments_count; ?>
                                    <lead class="d-inline-block" style="font-size: 20px;">in total</lead>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 text-white bg-info overflow-hidden">
                        <div class="card-body bg-info">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-bed fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Available rooms</h6>
                            <div>
                                <h1 class="display-4"><?php echo $rooms_count; ?>
                                    <lead class="d-inline-block" style="font-size: 20px;">for next week</lead>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 text-white bg-warning overflow-hidden">
                        <div class="card-body">
                            <div class="rotate float-right" style="z-index: 5;">
                                <i class="fas fa-hand-holding-usd fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Cheapest room</h6>
                            <h1 class="display-4"><?php echo $min_price; ?> PLN</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-deck">
                        <div class="card">
                            <div class="meal-packs-img text-white overflow-hidden position-relative">
                                <img class="card-img-top position-absolute" src="/assets/images/breakfast-1.png" alt="Sample breakfast">
                                <div class="meal-packs-img-hover position-absolute w-100 h-100"></div>
                                <div class="meal-packs-img-hover-content px-5 py-4 text-center position-absolute w-100">
                                    <h3 class="meal-packs-img-hover-title text-uppercase font-weight-bold mb-1"><i class="far fa-images"></i></h3>
                                    <p class="meal-packs-img-hover-description small text-uppercase mb-0">Show gallery</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Our meal packs</h5>
                                <p class="card-text">We offer two kinds of meal packs: Breakfast Pack and Lunch &amp; Dinner Pack.<br>Both packs are offered in affordable price and available to be purchased as an additional service paid per single day.<br>Breakfast is provided by HealthyMeal Restaurant and all meals are served by our waiters in bed.<br>Purchasing Lunch &amp; Dinner Pack entitles You to order in one of our 5 in-hotel restaurants.</p>
                            </div>
                        </div>
                        <div class="card">
                            <img class="card-img-top" src="..." alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                        <div class="card">
                            <img class="card-img-top" src="..." alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<a class="meal-packs-gallery-toggle" href="assets/images/breakfast-1.png" data-lightbox="meal-packs-gallery" data-title="Sample breakfast 1"></a>
<a href="assets/images/breakfast-2.png" data-lightbox="meal-packs-gallery" data-title="Sample breakfast 2"></a>
<a href="assets/images/dinner-1.png" data-lightbox="meal-packs-gallery" data-title="Sample dinner 1"></a>
<a href="assets/images/dinner-2.png" data-lightbox="meal-packs-gallery" data-title="Sample dinner 2"></a>

<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="/assets/js/dashboard.js"></script>
<script src="https://rawcdn.githack.com/lokesh/lightbox2/56b06ac5914179d5ffd27d133333230e9b002317/dist/js/lightbox.min.js"></script>

</body>
</html>
