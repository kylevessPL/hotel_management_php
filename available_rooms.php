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
<div class="container-fluid main-container flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View available rooms and search by predefined criteria</p>
            <div class="row mb-4">
                <div class="col-12 col-xl-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Available rooms search</h5>
                        </div>
                        <div class="card-body">
                            <form name="rooms-search-form">
                                <div class="form-group">
                                    <label for="filter-start-date">Start date<span style="color: red">*</span></label>
                                    <input class="form-control" id="filter-start-date" type="text" placeholder="dd/MM/yyyy" name="filter-start-date">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="filter-end-date">End date<span style="color: red">*</span></label>
                                    <input class="form-control" id="filter-end-date" type="text" placeholder="dd/MM/yyyy" name="filter-end-date">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="filter-bed-amount">Bed amount</label>
                                    <select class="selectpicker form-control" name="filter-bed-amount" id="filter-bed-amount">
                                        <option value="">None selected</option>
                                        <?php while($row = mysqli_fetch_array($beds_result)) { echo '
                                            <option value="'.htmlspecialchars($row[0]).'">'.htmlspecialchars($row[0]).'</option>';
                                        } ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="filter-amenities">Amenities</label>
                                    <select class="selectpicker form-control" name="filter-amenities" id="filter-amenities" multiple>
                                        <?php while($row = mysqli_fetch_array($amenities_result)) { echo '
                                            <option value="'.htmlspecialchars($row[0]).'">'.htmlspecialchars($row[1]).'</option>';
                                        } ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <div id="filter-price-range">
                                        <label class="form-label pb-2" for="filter-price-slider">Price range [PLN]</label>
                                        <input type="text" id="filter-price-slider" name="filter-price-slider">
                                    </div>
                                    <div id="filter-price-value" class="row my-4 justify-content-center">
                                        <div class="col">
                                            <input class="form-control text-center" type="text" id="filter-min-price" name="filter-min-price" value="<?php echo htmlspecialchars($min_price); ?>">
                                        </div>
                                        <div class="input-group-addon">
                                            <div class="input-group-text">
                                                <span>-</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-center" type="text" id="filter-max-price" name="filter-max-price" value="<?php echo htmlspecialchars($max_price); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="rooms-search" id="rooms-search" class="btn btn-info"><i class="las la-search mr-2"></i>Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Available rooms list</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100" id="roomsTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none">Id</th>
                                    <th>Room number</th>
                                    <th>Bed amount</th>
                                    <th>Standard price [PLN]</th>
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

<?php view('scripts.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.23/fh-3.1.7/r-2.2.6/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="/assets/js/validation-additional-methods.js"></script>
<script src="/assets/js/available-rooms.js"></script>

</body>
</html>