<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/validate_customer_details_fields.php';

get_customer_id($alertMsg, $alertType, $customerId);
if (isset($customerId))
{
    $sql = "SELECT first_name, last_name, document_type, document_id FROM customers WHERE id = '$customerId'";
    $result = query($sql);
    $customerData = mysqli_fetch_assoc($result);
}

if (isset($_POST["customer-details-submit"]))
{
    validate_customer_details_fields($_POST, $alertMsg, $alertType);
    if (!isset($alertMsg) || $alertType == 'info')
    {
        $first_name = escape_string($_POST["first-name"]);
        $last_name = escape_string($_POST["last-name"]);
        $document_type = escape_string($_POST["document-type"]);
        $document_id = escape_string($_POST["document-id"]);
        if (!isset($customerId)) {
            $sql = "INSERT INTO customers (first_name, last_name, document_type, document_id) VALUES('$first_name', '$last_name', '$document_type', '$document_id')";
        }
        else
        {
            $sql = "UPDATE customers SET first_name = '$first_name', last_name = '$last_name', document_type = '$document_type', document_id = '$document_id' where id = '$customerId'";
        }
        if (query($sql))
        {
            if (isset($customerId))
            {
                $alertMsg = "Personal data modified successfully";
                $alertType = "success";
            }
            else
            {
                $customer_id = insert_id();
                $sql = "UPDATE users SET customer_id = '$customer_id' where id = '".$_SESSION['user_id']."'";
                if (query($sql))
                {
                    $alertMsg = "Thank you. You have now full access to all features on the site.";
                    $alertType = "success";
                }
                else
                {
                    $alertMsg = 'Oops, something went wrong. Please try again later.';
                    $alertType = "danger";
                }
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
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View your account details and edit</p>
            <div class="row mb-4">
                <div class="col-10 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Your Profile</h4>
                                    <hr>
                                </div>
                            </div>
                            <?php echo isset($alertMsg) ? '<p class="alert alert-'.$alertType.'">'.$alertMsg.'</p>' : ''; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="form-customer-details" name="form-customer-details" method="post" action="/account/my-details">
                                        <div class="form-group row">
                                            <label for="first-name" class="col-4 col-form-label">First name<span style="color: red">*</span>:</label>
                                            <div class="col-8">
                                                <input type="text" id="first-name" name="first-name" placeholder="Enter first name" class="form-control"
                                                    <?php if (isset($_POST["customer-details-submit"]))
                                                    {
                                                        echo 'value="'.$_POST['first-name'].'"';
                                                    }
                                                    else if (isset($customerData))
                                                    {
                                                        echo 'value="'.$customerData['first_name'].'"';
                                                    }
                                                    ?>>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="last-name" class="col-4 col-form-label">Last name<span style="color: red">*</span>:</label>
                                            <div class="col-8">
                                                <input type="text" id="last-name" name="last-name" placeholder="Enter last name" class="form-control"
                                                    <?php if (isset($_POST["customer-details-submit"]))
                                                    {
                                                        echo 'value="'.$_POST['last-name'].'"';
                                                    }
                                                    else if (isset($customerData))
                                                    {
                                                        echo 'value="'.$customerData['last_name'].'"';
                                                    }
                                                    ?>>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-type" class="col-4 col-form-label">Document type<span style="color: red">*</span>:</label>
                                            <div class="col-8">
                                                <select id="document-type" name="document-type" class="custom-select">
                                                    <option value="ID card"
                                                        <?php if ((isset($_POST["customer-details-submit"]) && $_POST['document-type'] == 'ID card') || (isset($customerData) && $customerData['document_type'] == 'ID card'))
                                                        {
                                                            echo 'selected';
                                                        }
                                                        ?>>ID card</option>
                                                    <option value="Passport"
                                                        <?php if ((isset($_POST["customer-details-submit"]) && $_POST['document-type'] == 'Passport') || (isset($customerData) && $customerData['document_type'] == 'Passport'))
                                                        {
                                                            echo 'selected';
                                                        }
                                                        ?>>Passport</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-id" class="col-4 col-form-label">Document ID<span style="color: red">*</span>:</label>
                                            <div class="col-8">
                                                <input type="text" id="document-id" name="document-id" placeholder="Enter document ID" class="form-control"
                                                    <?php if (isset($_POST["customer-details-submit"]))
                                                    {
                                                        echo 'value="'.$_POST['document-id'].'"';
                                                    }
                                                    else if (isset($customerData))
                                                    {
                                                        echo 'value="'.$customerData['document_id'].'"';
                                                    }
                                                    ?>>
                                            </div>
                                        </div>
                                        <input class="btn btn-primary text-right" name="customer-details-submit" value="Update my details" type="submit">
                                    </form>
                                </div>
                            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="../assets/js/form-validation.js"></script>

</body>
</html>