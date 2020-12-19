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
            <p>View your payment history for hotel bookings</p>
            <div class="row mb-4">
                <div class="col-12 col-xl-9 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h5>Your payment history</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100" id="paymentsTable">
                                <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Payment date</th>
                                    <th>Payment id</th>
                                    <th>Payment form</th>
                                    <th>Booking id</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.22/fh-3.1.7/r-2.2.6/datatables.min.js"></script>
<script src="/assets/js/payment-history.js"></script>

</body>
</html>