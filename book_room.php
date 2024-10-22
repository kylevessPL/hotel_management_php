<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (isset($customer_id))
{
    if (isset($_GET['id'], $_GET['start-date'], $_GET['end-date']))
    {
        [$id, $start_date, $end_date, $bed_number] = get_room_choice($alert_msg, $alert_type);
    }

    $address_result = get_customer_addresses($customer_id);
}

$beds_result = get_bed_variants();
$services_result = get_additional_services();

function get_room_choice(&$alert_msg, &$alert_type): array
{
    $id = escape_string($_GET['id']);
    $start_date = escape_string($_GET['start-date']);
    $end_date = escape_string($_GET['end-date']);
    $url = $_SERVER ["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] . "/process/check_room_availability?id=" . $id . "&start-date=" . rawurlencode($start_date) . "&end-date=" . rawurlencode($end_date);
    $result = file_get_contents($url);
    if ($result == 'false')
    {
        $alert_msg = "Room not available within $start_date - $end_date period";
        $alert_type = "warning";
    }
    else
    {
        $sql = "SELECT bed_amount FROM rooms where id = '$id'";
        $result = query($sql);
        $bed_number = mysqli_fetch_array($result);
    }
    return array($id, $start_date, $end_date, $bed_number);
}

function get_customer_addresses($customer_id)
{
    $sql = "SELECT id, street_name, house_number, zip_code, city FROM addresses where id IN (SELECT address_id FROM customers_addresses where customer_id = '$customer_id') ORDER BY 1";
    return query($sql);
}

function get_bed_variants()
{
    $sql = "SELECT DISTINCT bed_amount FROM rooms ORDER BY 1";
    return query($sql);
}

function get_additional_services()
{
    $sql = "SELECT id, name FROM additional_services ORDER BY 1";
    return query($sql);
}

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
            <p>Please fill in the form to book a room for the period you would like to stay</p>
            <div class="row mb-4 d-flex justify-content-between">
                <div class="col-12 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking form</h5>
                        </div>
                        <div class="card-body">
                        <?php if (!isset($customer_id))
                        { echo '
                            <p class="alert alert-'.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_msg).'</p>
                            <a class="btn btn-primary text-right" href="/account/my-details">Update my details</a>
                        ';}
                        else
                        {?>
                            <p class="alert alert-<?php echo isset($alert_type) ? htmlspecialchars($alert_type) : 'info'; ?>">
                                <?php echo isset($alert_msg) ? htmlspecialchars($alert_msg) : 'You can check available room list <a href="/dashboard/available-rooms">here</a>'; ?></p>
                            <form id="booking-form" name="booking-form">
                                <div id="booking-form-main">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="control-label" for="startDate">Start date<span style="color: red">*</span></label>
                                            <input class="form-control" id="startDate" type="text" name="startDate" value="<?php if (isset($bed_number)) { echo htmlspecialchars($start_date); } ?>" placeholder="dd/MM/yyyy" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label" for="endDate">End date<span style="color: red">*</span></label>
                                            <input class="form-control" id="endDate" type="text" name="endDate" value="<?php if (isset($bed_number)) { echo htmlspecialchars($end_date); } ?>" placeholder="dd/MM/yyyy" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="myAddress">My address<span style="color: red">*</span></label>
                                            <select class="selectpicker form-control" name="myAddress" id="myAddress">
                                                <?php if (mysqli_num_rows($address_result) == 0) { echo "<option value=''>You don't have any addresses</option>"; }
                                                $count = 0; while($row = mysqli_fetch_array($address_result)) { echo '
                                                <option value="'.htmlspecialchars($row[0]).'"'; if ($count == 0) { echo ' selected'; } echo '>'.htmlspecialchars($row[1]).' '.htmlspecialchars($row[2]).', '.htmlspecialchars($row[3]).' '.htmlspecialchars($row[4]).'</option>';
                                                $count++; } ?>

                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="bedAmount">Bed amount<span style="color: red">*</span></label>
                                            <select class="selectpicker form-control" name="bedAmount" id="bedAmount">
                                                <option value="">None selected</option>
                                                <?php while($row = mysqli_fetch_array($beds_result)) { echo '
                                                <option value="'.htmlspecialchars($row[0]).'"'; if (($bed_number[0] ?? null) == $row[0]) { echo ' selected'; } echo '>'.htmlspecialchars($row[0]).'</option>';
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="room">Room<span style="color: red">*</span></label>
                                            <select class="selectpicker form-control" name="room" id="room">
                                                <option value="">Choose dates and bed amount first</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="services">Additional services</label>
                                            <select class="selectpicker form-control" name="services" id="services" multiple data-selected-text-format="count > 2">
                                                <?php while($row = mysqli_fetch_array($services_result)) { echo '
                                                <option value="'.htmlspecialchars($row[0]).'">'.htmlspecialchars($row[1]).'</option>';
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4 mb-lg-0">
                    <div class="sticky-top" style="z-index: 200;">
                        <div style="padding: 0.75rem 0;">
                            <h4 class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Summary</span>
                                <span class="badge badge-secondary badge-pill" id="total-count">0</span>
                            </h4>
                        </div>
                        <ul class="list-group mb-3 items">
                            <li class="list-group-item d-flex justify-content-between total">
                                <span>Total</span>
                                <strong id="total">0 PLN</strong>
                            </li>
                        </ul>
                        <div class="card p-2 mb-3">
                            <form id="redeem-code-form" name="redeem-code-form">
                                <div class="d-inline-flex w-100 redeem-code">
                                    <input type="text" class="form-control mr-2" id="promo-code" name="promo-code" placeholder="Promo code">
                                    <button type="submit" class="btn btn-secondary redeemCode">Redeem</button>
                                </div>
                            </form>
                        </div>
                        <button class="btn btn-success btn-lg btn-block bookingSubmit" name="booking-submit" type="submit">Book</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="/assets/js/validation-additional-methods.js"></script>
<script src="/assets/js/book-room.js"></script>
<script src="/assets/js/payment-modal.js"></script>

<?php if (isset($bed_number[0])) { ?>
<script>
    const startDate = $('#startDate');
    const endDate = $('#endDate');
    const bedAmount = $('#bedAmount');
    fetchRooms(startDate, endDate, bedAmount);
    function setChoice() {
        if (bedAmount.val() === '<?php echo htmlspecialchars($bed_number[0]); ?>' &&
            startDate.val() === '<?php echo htmlspecialchars($start_date); ?>' &&
            endDate.val() === '<?php echo htmlspecialchars($end_date); ?>'
        ) {
            const room = $('#room');
            room.selectpicker('val', '<?php echo htmlspecialchars($id); ?>');
            room.selectpicker('setStyle', 'btn', 'remove');
            room.selectpicker('setStyle', 'form-control');
        }
    }
</script>
<?php } ?>

<script>
    function getCustomerId() {
        return '<?php echo htmlspecialchars($customer_id); ?>';
    }
</script>

</body>
</html>
