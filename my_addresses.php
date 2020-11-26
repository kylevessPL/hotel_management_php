<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/validate_address_fields.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId))
{
    $sql = "SELECT address_id FROM customers_addresses where customer_id = '$customerId'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            $address_list[] = $row[0];
        }
        $sql = "SELECT street_name, house_number, zip_code, city FROM addresses where id IN (".implode(',', $address_list).")";
        $result = query($sql);
    }
    else
    {
        $alertMsg = "You need to add at least one address to unlock all site features";
        $alertType = "info";
    }
}

if (isset($_POST["address-submit"]))
{
    validate_address_fields($_POST, $alertMsg, $alertType);
    if (!isset($alertMsg) || $alertType == 'info')
    {
        $address_num = escape_string($_POST["addressNum"]);
        $street_name = escape_string($_POST["streetName"]);
        $house_number = escape_string($_POST["houseNumber"]);
        $zip_code = escape_string($_POST["zipCode"]);
        $city = escape_string($_POST["city"]);
        if ($address_num == '') {
            $sql = "INSERT INTO addresses (street_name, house_number, zip_code, city) VALUES('$street_name', '$house_number', '$zip_code', '$city')";
        }
        else
        {
            $id = $address_list[$address_num - 1];
            $sql = "UPDATE addresses SET street_name = '$street_name', house_number = '$house_number', zip_code = '$zip_code', city = '$city' where id = '$id'";
        }
        if (query($sql))
        {
            if(empty($address_num))
            {
                $address_id = insert_id();
                $sql = "INSERT INTO customers_addresses (customer_id, address_id) VALUES('$customerId', '$address_id')";
                if (!query($sql))
                {
                    $alertMsg = 'Oops, something went wrong. Please try again later.';
                    $alertType = "danger";
                }
                else
                {
                    header("Refresh:0");
                }
            }
            else
            {
                header("Refresh:0");
            }
        }
        else
        {
            $alertMsg = 'Oops, something went wrong. Please try again later.';
            $alertType = "danger";
        }
    }
}

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
        <div class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <main>
                <?php view('breadcrumb.php'); ?>
                <p>View your home addresses and manage them</p>
                <div class="row">
                    <div class="col-10 col-xl-8 mb-lg-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Your Addresses</h4>
                                        <hr>
                                    </div>
                                </div>
                                <?php if (!isset($customerId))
                                { echo '
                                <p class="alert alert-'.$alertType.'">'.$alertMsg.'</p>
                                <a class="btn btn-primary text-right" href="/account/my-details">Update my details</a>
                                ';}
                                else
                                { echo isset($alertMsg) ? '<p class="alert alert-'.$alertType.'">'.$alertMsg.'</p>' : ''; ?>
                                <button class="btn btn-success text-right add-address-action"><i class="las la-plus-circle la-lg mr-2"></i>New address</button>
                                <?php if (isset($address_list)) {?>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Street name</th>
                                                    <th scope="col" class="text-center">House number</th>
                                                    <th scope="col" class="text-center">Zip code</th>
                                                    <th scope="col" class="text-center">City</th>
                                                    <th scope="col" class="text-center">Edit</th>
                                                    <th scope="col" class="text-center">Delete</th>
                                                </tr>
                                                </thead>
                                                <tbody><?php $count = 1; while($row = mysqli_fetch_array($result)) { echo "
                                                <tr>
                                                    <th class='address-num align-middle text-center' scope='row'>" . $count . "</th>
                                                    <td class='address-street-name align-middle'>" . $row[0] . "</td>
                                                    <td class='address-house-number align-middle text-center'>" . $row[1] . "</td>
                                                    <td class='address-zip-code align-middle text-center'>" . $row[2] . "</td>
                                                    <td class='address-city align-middle text-center'>" . $row[3] . "</td>
                                                    <td class='align-middle text-center'><button class='btn btn-info edit-address-action'><i class='las la-edit'></i></button></td>
                                                    <td class='align-middle text-center'><a class='btn btn-danger delete-address-action' href='../process/delete_address.php?id=".$address_list[$count - 1]."'><i class='las la-trash'></i></a></td>
                                                </tr>
                                                "; $count++;} ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php }} ?>

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

<?php if (isset($customerId)) { ?>
<div id="addressRequest" class="addressRequest">
    <form method="post" id="address-form" name="address-form" action="/account/my-addresses">
        <div aria-hidden="true" aria-labelledby="addressModalLabel" class="modal fade" id="addressModal" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 650px" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addressModalLabel"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <input class="form-control" id="addressNum" type="hidden" name="addressNum">
                            <div class="col-sm-8">
                                <label class="control-label" for="streetName">Street name:</label>
                                <input class="form-control" id="streetName" type="text" name="streetName" placeholder="Enter street name" autofocus>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label" for="houseNumber">House number:</label>
                                <input class="form-control" id="houseNumber" type="text" placeholder="Your house number" name="houseNumber">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label" for="zipCode">Zip code:</label>
                                <input class="form-control" id="zipCode" type="text" placeholder="Enter zip code" name="zipCode">
                            </div>
                            <div class="col-sm-8">
                                <label class="control-label" for="city">City:</label>
                                <input class="form-control" id="city" type="text" placeholder="Enter city" name="city">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                        <input class="btn btn-primary" name="address-submit" id="addressSubmitBtn" value="" type="submit">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php } ?>

<?php view('scripts.php'); ?>
<script src="../assets/js/address-modal.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="../assets/js/form-validation.js"></script>

</body>
</html>