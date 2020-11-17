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
            <p>Please fill in the form to book a room for the period you would like to stay</p>
            <?php view('footer.php'); ?>
        </main>
    </div>
</div>
<?php view('sign_out_modal.php'); ?>

</body>
</html>