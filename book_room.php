<?php
include 'helpers/include_all.php';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body class="min-vh-100 d-flex flex-column">
<?php view('navbar.php'); ?>
<?php view('confirmation_modal.php'); ?>
<div class="container-fluid flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>Please fill in the form to book a room for the period you would like to stay</p>
            <div class="row mb-4">
                <div class="col-10 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking form</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-title">All prices are in PLN</div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Service name</th>
                                        <th scope="col" class="text-center">Price per night</th>
                                        <th scope="col" class="text-center">Description</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php view('chat.php'); ?>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>