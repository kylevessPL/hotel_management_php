<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId))
{
    $sql = "SELECT COUNT(id) AS count from customers_bookings WHERE customer_id = '$customerId'";
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
            <div class="row">
                <?php if (isset($customerId)) { ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="card animated-2 bg-success text-white overflow-hidden">
                        <div class="card-body bg-success">
                            <div class="rotate rotate-custom float-right" style="z-index: 5;">
                                <i class="fas fa-tags fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Bookings</h6>
                            <h1 class="display-4"><?php echo $bookings_count; ?></h1 class="display-4">
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
                            <h1 class="display-4"><?php echo $payments_count; ?></h1>
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
                            <h1 class="display-4"><?php echo $rooms_count; ?></h1>
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
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>
