<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';

get_customer_id($alertMsg, $alertType, $customerId);

if (isset($customerId))
{
    $sql = "SELECT address_id FROM customers_addresses where customer_id = '$customerId'";
    $result = query($sql);
    if (mysqli_num_rows($result) > 0)
    {
        $address_list = mysqli_fetch_all($result);
        $sql = "SELECT street_name, house_number, zip_code, city FROM addresses where id IN (".implode(',', $address_list).")";
        $result = query($sql);
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
                                { if (mysqli_num_rows($result) == 0) { echo '
                                <p class="alert alert-info">You need to add at least one address to unlock all site features</p>';} ?>
                                <button class="btn btn-success text-right">New address</button>
                                <?php if (isset($address_list)) {?>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Street name</th>
                                                    <th scope="col">House number</th>
                                                    <th scope="col">Zip code</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col" class="text-center">Edit</th>
                                                    <th scope="col" class="text-center">Delete</th>
                                                </tr>
                                                </thead>
                                                <tbody><?php $count = 0; while($row = mysqli_fetch_array($result)) { echo "
                                                <tr>
                                                    <td class='service-price align-middle text-center'>" . $count . "</td>
                                                    <th class='service-id align-middle' scope='row'>" . $row[0] . "</th>
                                                    <td class='service-name align-middle'>" . $row[1] . "</td>
                                                    <td class='service-price align-middle text-center'>" . $row[2] . "</td>
                                                    <td class='service-price align-middle text-center'>" . $row[3] . "</td>
                                                    <td class='align-middle text-center'><button class='btn btn-sm btn-info edit-address-action'><i class='las la-edit'></i></button></td>
                                                    <td class='align-middle text-center'><a class='btn btn-sm btn-danger delete-address-action' href='../process/delete_address.php'><i class='las la-trash'></i></a></td>
                                                </tr>
                                                "; $count++;}
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
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