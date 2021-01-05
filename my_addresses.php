<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/validate_address_fields.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (isset($_POST["address-submit"], $customer_id))
{
    validate_address_fields($_POST, $alert_msg, $alert_type);
    if (!isset($alert_msg) || $alert_type == 'info')
    {
        $address_num = escape_string($_POST["addressNum"]);
        $street_name = escape_string($_POST["streetName"]);
        $house_number = escape_string($_POST["houseNumber"]);
        $zip_code = escape_string($_POST["zipCode"]);
        $city = escape_string($_POST["city"]);
        $address_list = get_customer_addresses($customer_id);
        autocommit(false);
        try
        {
            update_customer_addresses($street_name, $house_number, $zip_code, $city, $address_list, $address_num, $customer_id);
            commit_transaction();
            autocommit();
        }
        catch (Throwable $e)
        {
            $alert_msg = 'Oops, something went wrong. Please try again later.';
            $alert_type = "danger";
            rollback_transaction();
            autocommit();
        }
    }
}

if (isset($customer_id))
{
    $address_list = get_customer_addresses($customer_id);
    if (!isset($address_list) || empty($address_list))
    {
        $alert_msg = "You need to add at least one address to unlock all site features";
        $alert_type = "info";
    }
    else
    {
        $sql = "SELECT street_name, house_number, zip_code, city FROM addresses where id IN (".implode(',', $address_list).")";
        $address_result = query($sql);
    }
}

function get_customer_addresses($customer_id)
{
    $address_list = null;
    $sql = "SELECT address_id FROM customers_addresses where customer_id = '$customer_id'";
    $result = query($sql);
    while ($row = mysqli_fetch_array($result))
    {
        $address_list[] = $row[0];
    }
    return $address_list;
}

function update_customer_addresses(string $street_name, string $house_number, string $zip_code, string $city, $address_list, string $address_num, $customer_id): void
{
    if (!isset($address_num) || empty($address_num))
    {
        $sql = "INSERT INTO addresses (street_name, house_number, zip_code, city) VALUES('$street_name', '$house_number', '$zip_code', '$city')";
    }
    else
    {
        $id = $address_list[$address_num - 1];
        $sql = "UPDATE addresses SET street_name = '$street_name', house_number = '$house_number', zip_code = '$zip_code', city = '$city' where id = '$id'";
    }
    if (!query($sql))
    {
        throw new Exception(dbException());
    }
    if (!isset($address_num) || empty($address_num))
    {
        $address_id = insert_id();
        $sql = "INSERT INTO customers_addresses (customer_id, address_id) VALUES('$customer_id', '$address_id')";
        if (!query($sql))
        {
            throw new Exception(dbException());
        }
    }
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
        <div class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <main>
                <?php view('breadcrumb.php'); ?>
                <p>View your home addresses and manage them</p>
                <div class="row">
                    <div class="col-12 col-xl-8 mb-lg-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Your Addresses</h4>
                                        <hr>
                                    </div>
                                </div>
                                <?php if (!isset($customer_id))
                                { echo '
                                <p class="alert alert-'.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_msg).'</p>
                                <a class="btn btn-primary text-right" href="/account/my-details">Update my details</a>
                                ';}
                                else
                                { echo isset($alert_msg) ? '<p class="alert alert-'.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_msg).'</p>' : ''; ?>
                                <button class="btn btn-success text-right add-address-action"><i class="las la-plus-circle la-lg mr-2"></i>New address</button>
                                <?php if (isset($address_list)) {?>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col" class="text-center">Street name</th>
                                                    <th scope="col" class="text-center">House number</th>
                                                    <th scope="col" class="text-center">Zip code</th>
                                                    <th scope="col" class="text-center">City</th>
                                                    <th scope="col" class="text-center">Edit</th>
                                                    <th scope="col" class="text-center">Delete</th>
                                                </tr>
                                                </thead>
                                                <tbody><?php $count = 1; while($row = mysqli_fetch_array($address_result)) { echo "
                                                <tr>
                                                    <th class='address-num align-middle' scope='row'>".htmlspecialchars($count)."</th>
                                                    <td class='address-street-name align-middle text-center'>".htmlspecialchars($row[0])."</td>
                                                    <td class='address-house-number align-middle text-center'>".htmlspecialchars($row[1])."</td>
                                                    <td class='address-zip-code align-middle text-center'>".htmlspecialchars($row[2])."</td>
                                                    <td class='address-city align-middle text-center'>".htmlspecialchars($row[3])."</td>
                                                    <td class='align-middle text-center'><button class='btn btn-info edit-address-action'><i class='las la-edit'></i></button></td>
                                                    <td class='align-middle text-center'><a class='btn btn-danger delete-address-action' href='../process/delete_address.php?id=".htmlspecialchars($address_list[$count - 1])."'><i class='las la-trash'></i></a></td>
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
<?php view('footer_dashboard.php'); ?>

<?php view('scripts.php'); ?>
<script src="https://apps.elfsight.com/p/platform.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="/assets/js/validation-additional-methods.js"></script>
<script src="/assets/js/my-addresses.js"></script>
<script src="/assets/js/chat.js"></script>

</body>
</html>