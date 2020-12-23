<?php
include 'helpers/include_all.php';

$sql = "SELECT id, name, price FROM additional_services";
$result = query($sql);
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
            <p>View available additional services you can order</p>
            <div class="row mb-4">
                <div class="col-12 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h5>Additional services list</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-title">All prices are in PLN</div>
                            <table class="table table-bordered">
                                <thead>
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Service name</th>
                                    <th scope="col">Price per night</th>
                                    <th scope="col">Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_array($result)) { echo "
                                <tr class='text-center'>
                                    <th class='service-id align-middle' scope='row'>".htmlspecialchars($row[0])."</th>
                                    <td class='service-name align-middle'>".htmlspecialchars($row[1])."</td>
                                    <td class='service-price align-middle'>".htmlspecialchars($row[2])."</td>
                                    <td class='align-middle'><button class='btn btn-primary py-1 px-2 viewServiceBtn'>View</button></td>
                                </tr>
                                ";}
                                ?>
                                </tbody>
                            </table>
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
<script src="/assets/js/additional-services.js"></script>
<script src="/assets/js/chat.js"></script>

</body>
</html>