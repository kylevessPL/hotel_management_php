<?php
include 'helpers/include_all.php';

$sql = "SELECT id, name, price FROM additional_services";
$result = query($sql);

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('header.php'); ?>
<body>
<?php view('navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View available additional services you can order</p>
            <div class="row">
                <div class="col-10 col-xl-7 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h5>Additional services list</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-title">All prices are in PLN (Polish Zloty)</div>
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
                                    <?php while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>
                                        <th class='service-id' scope='row'>" . $row[0] . "</th>
                                        <td class='service-name'>" . $row[1] . "</td>
                                        <td class='text-center service-price'>" . $row[2] . "</td>
                                        <td class='text-center'><button class='btn btn-sm btn-primary viewServiceBtn'>View</button></td>
                                    </tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php view('chat.php'); ?>
            </div>
            <?php view('footer.php'); ?>
        </main>
    </div>
</div>

<script src="/assets/js/view-service-desc-modal.js"></script>

<div aria-hidden="true" aria-labelledby="viewServiceDescModalTitle" class="modal fade" id="viewServiceDescModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewServiceDescName"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="viewServiceDescPrice"></div>
                <div id="viewServiceDescDesc"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
            </div>
        </div>
    </div>
</div>

<?php view('sign_out_modal.php'); ?>

</body>
</html>