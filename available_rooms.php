<?php
include 'helpers/include_all.php';

$sql = "SELECT MIN(standard_price) AS 'min_price', MAX(standard_price) AS 'max_price' FROM rooms";
$price_result = query($sql);
$row = mysqli_fetch_array($price_result);
$min_price = floor($row['min_price']);
$max_price = ceil($row['max_price']);

$sql = "SELECT DISTINCT bed_amount FROM rooms ORDER BY 1";
$beds_result = query($sql);
$sql = "SELECT id, name FROM amenities ORDER BY 1";
$amenities_result = query($sql);

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
            <p>View available rooms and search by predefined criteria</p>
            <div class="col-10 col-xl-8 mb-4 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <h5>Available rooms search</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <form name="rooms-search-form">
                                    <div class="form-group">
                                        <label for="filter-start-date">Start date*:</label>
                                        <input class="form-control" id="filter-start-date" type="text" placeholder="dd/MM/yyyy" name="filter-start-date">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="filter-end-date">End date*:</label>
                                        <input class="form-control" id="filter-end-date" type="text" placeholder="dd/MM/yyyy" name="filter-end-date">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="filter-bed-amount">Bed amount (optional):</label>
                                        <select class="form-control" name="filter-bed-amount" id="filter-bed-amount" class="form-control">
                                            <option value="">None selected</option>
                                            <?php while($row = mysqli_fetch_array($beds_result)) { echo '
                                            <option value="'.$row[0].'">'.$row[0].'</option>';
                                            } ?>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="filter-amenities">Amenities (optional):</label>
                                        <select class="form-control" name="filter-amenities" id="filter-amenities" class="form-control" multiple="multiple">
                                            <?php while($row = mysqli_fetch_array($amenities_result)) { echo '
                                            <option value="'.$row[0].'">'.$row[1].'</option>';
                                            } ?>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div id="filter-price-range">
                                            <label class="form-label pb-2" for="filter-price-slider">Price range [USD]:</label>
                                            <input type="text" id="filter-price-slider" name="filter-price-slider">
                                        </div>
                                        <div id="filter-price-value" class="row my-4 justify-content-center">
                                            <div class="col-sm-2">
                                                <input class="form-control text-center" type="text" id="filter-min-price" name="filter-min-price" value="<?php echo $min_price; ?>" disabled>
                                            </div>
                                            <div class="input-group-addon">
                                                <div class="input-group-text">
                                                    <span>-</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <input class="form-control text-center" type="text" id="filter-max-price" name="filter-max-price" value="<?php echo $max_price; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" name="rooms-search" id="rooms-search" class="btn btn-info"><i class="las la-search mr-2"></i>Search</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>
                <?php view('chat.php'); ?>
            </div>
            <div class="col-10 col-xl-8 my-4 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <h5>Available rooms list</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered w-100" id="roomsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none">Id</th>
                                    <th>Room number</th>
                                    <th>Bed amount</th>
                                    <th>Standard price [USD]</th>
                                    <th class="text-center">Amenities</th>
                                    <th class="text-center">Book</th>
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

<div aria-hidden="true" aria-labelledby="viewRoomDescModalTitle" class="modal fade" id="viewRoomDescModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRoomDescModalTitle"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="viewRoomDescRoomNumber"></div>
                <div id="viewRoomDescBedAmount"></div>
                <div id="viewRoomDescStandardPrice"></div>
                <div id="viewRoomDescAmenities"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
            </div>
        </div>
    </div>
</div>

<?php view('scripts.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.22/fh-3.1.7/r-2.2.6/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="/assets/js/form-validation.js"></script>
<script src="/assets/js/available-rooms.js"></script>

</body>
</html>