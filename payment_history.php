<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

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
                <div class="col-12 col-xl-3 mb-lg-0">
                    <div class="sticky-top" id="searchPaneContainer" style="cursor:pointer; z-index: 200;">
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.23/fh-3.1.7/r-2.2.6/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="/assets/js/payment-history.js"></script>

<script>
    function isCustomerIdSet() {
        const value = <?php echo isset($customer_id) ? 'true' : 'false'; ?>;
        return value;
    }
</script>

</body>
</html>