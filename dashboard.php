<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (isset($customer_id))
{
    $bookings_count = get_customer_bookings_count($customer_id);
    $payments_count = get_customer_payments_count($customer_id);
}

$min_price = get_room_min_price();
$rooms_count = get_available_rooms_next_week();

function get_customer_payments_count($customerId)
{
    $sql = "SELECT COUNT(id) AS count from payments WHERE booking_id IN (SELECT booking_id from customers_bookings WHERE customer_id = '$customerId')";
    $result = query($sql);
    return mysqli_fetch_assoc($result)['count'];
}

function get_customer_bookings_count($customerId)
{
    $sql = "SELECT COUNT(cb.id) AS count from customers_bookings cb " .
        "INNER JOIN bookings b ON b.id = cb.booking_id " .
        "WHERE customer_id = '$customerId' AND book_date >= '" . date('Y-m-d', strtotime('first day of january this year')) . "'";
    $result = query($sql);
    return mysqli_fetch_assoc($result)['count'];
}

function get_room_min_price(): int
{
    $sql = "SELECT MIN(standard_price) AS min from rooms WHERE id NOT IN (SELECT room_id from bookings_rooms WHERE booking_id IN (SELECT id from bookings WHERE status IN ('Paid', 'Unpaid')))";
    $result = query($sql);
    return (int) mysqli_fetch_assoc($result)['min'];
}

function get_available_rooms_next_week(): int
{
    $start_date = date('Y-m-d', strtotime('next monday'));
    $end_date = date('Y-m-d', strtotime($start_date . 'next sunday'));
    $sql = "SELECT COUNT(id) AS count from rooms WHERE id NOT IN (SELECT room_id from bookings_rooms WHERE booking_id IN (SELECT id from bookings WHERE status != 'Cancelled' AND '$start_date' <= end_date AND '$end_date' >= start_date))";
    $result = query($sql);
    return (int) mysqli_fetch_assoc($result)['count'];
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
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
                <?php if (isset($customer_id)) { ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 bg-success text-white overflow-hidden">
                        <div class="card-body bg-success">
                            <div class="rotate rotate-custom float-right" style="z-index: 5;">
                                <i class="fas fa-tags fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Bookings</h6>
                            <div>
                                <h1 class="display-4"><?php echo $bookings_count; ?>
                                    <span class="d-inline-block" style="font-size: 20px;">this year</span>
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
                                    <span class="d-inline-block" style="font-size: 20px;">in total</span>
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
                                    <span class="d-inline-block" style="font-size: 20px;">for next week</span>
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
                            <div class="card-img card-meals text-white overflow-hidden position-relative">
                                <img class="card-img-top position-absolute" src="/assets/images/breakfast-1.png" alt="Sample breakfast">
                                <div class="card-img-hover position-absolute w-100 h-100"></div>
                                <div class="card-img-hover-content px-5 py-4 text-center position-absolute w-100">
                                    <h3 class="card-img-hover-icon mb-1"><i class="far fa-images"></i></h3>
                                    <p class="card-img-hover-info small text-uppercase">Show gallery</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Our meal packs</h5>
                                <p class="card-text">We offer two kinds of meal packs: Breakfast Pack and Lunch &amp; Dinner Pack.<br>Both packs are offered in affordable price and available to be purchased as an additional service paid per single day.<br>Breakfast is provided by HealthyMeal Restaurant and all meals are served in bed by our waiters.<br>Purchasing Lunch &amp; Dinner Pack entitles You to order in one of our 5 in-hotel restaurants.</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-img card-rooms text-white overflow-hidden position-relative">
                                <img class="card-img-top position-absolute" src="/assets/images/room-1.png" alt="Sample room 1">
                                <div class="card-img-hover position-absolute w-100 h-100"></div>
                                <div class="card-img-hover-content px-5 py-4 text-center position-absolute w-100">
                                    <h3 class="card-img-hover-icon mb-1"><i class="far fa-images"></i></h3>
                                    <p class="card-img-hover-info small text-uppercase">Show gallery</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Our rooms</h5>
                                <p class="card-text">HoteLA has overall 50 rooms available for our guests. We have 4 variants of rooms: 1-bed, 2-bed, 3-bed and 4-bed types.<br>Most of the rooms come en-suited, however cheaper room variants have shared bathrooms.<br>Each room has different amenities: TV, phone, balcony, radio or A/C.<br>We strongly encourage You to check our full room offer <a href="../dashboard/available-rooms">here</a>.</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-img card-infrastructure text-white overflow-hidden position-relative">
                                <img class="card-img-top position-absolute" src="/assets/images/reception.png" alt="Reception desk">
                                <div class="card-img-hover position-absolute w-100 h-100"></div>
                                <div class="card-img-hover-content px-5 py-4 text-center position-absolute w-100">
                                    <h3 class="card-img-hover-icon mb-1"><i class="far fa-images"></i></h3>
                                    <p class="card-img-hover-info small text-uppercase">Show gallery</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Hotel infrastructure</h5>
                                <p class="card-text">HoteLA stands out not only by its convenient location but also by the infrastructure as a whole.<br>Our guests can take advantage of our 5 restaurants, 3 cafes and an underground bar to unwind a little in the evenings.<br>A large swimming pool and one of the biggest gym in the city are completely free for our guests.</p>
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

<a class="rooms-gallery-toggle" href="assets/images/room-1.png" data-lightbox="rooms-gallery" data-title="Sample room 1"></a>
<a href="assets/images/room-2.png" data-lightbox="rooms-gallery" data-title="Sample room 2"></a>
<a href="assets/images/room-3.png" data-lightbox="rooms-gallery" data-title="Sample room 3"></a>
<a href="assets/images/bathroom.png" data-lightbox="rooms-gallery" data-title="En-suite bathroom"></a>
<a href="assets/images/corridor.png" data-lightbox="rooms-gallery" data-title="Corridor"></a>

<a class="infrastructure-gallery-toggle" href="assets/images/reception.png" data-lightbox="infrastructure-gallery" data-title="Reception desk"></a>
<a href="assets/images/lounge.png" data-lightbox="infrastructure-gallery" data-title="Lounge & Guest room"></a>
<a href="assets/images/cafe.png" data-lightbox="infrastructure-gallery" data-title="One of your 3 cafes"></a>
<a href="assets/images/bar.png" data-lightbox="infrastructure-gallery" data-title="Our underground bar"></a>
<a href="assets/images/restaurant.png" data-lightbox="infrastructure-gallery" data-title="One of our 5 restaurants"></a>
<a href="assets/images/pool.png" data-lightbox="infrastructure-gallery" data-title="Swimming pool"></a>
<a href="assets/images/gym.png" data-lightbox="infrastructure-gallery" data-title="Gym"></a>
<a href="assets/images/valet_parking.png" data-lightbox="infrastructure-gallery" data-title="Secured valet parking"></a>

<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="/assets/js/dashboard.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</body>
</html>
