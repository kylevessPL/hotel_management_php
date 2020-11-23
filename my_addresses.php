<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body class="min-vh-100 d-flex flex-column">
<?php view('navbar.php'); ?>
<?php view('sign_out_modal.php'); ?>
<div class="container-fluid flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <div class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <main>
                <?php view('breadcrumb.php'); ?>
                <p>View your home addresses and manage them</p>
                <div class="row">
                    <div class="col-10 col-xl-7 mb-lg-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Your Addresses</h4>
                                        <hr>
                                    </div>
                                </div>
                                <?php if (!isset($customerId))
                                {
                                    echo '<p class="alert alert-'.$alertType.'">'.$alertMsg.'</p>
                                        <a class="btn btn-primary text-right" href="/account/my-details">Update my details</a>';
                                }
                                else
                                {?>
                                <div class="row">
                                    <div class="col-md-12">
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
                                <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                    <?php view('chat.php'); ?>
                </div>
            </main>
        </div>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>