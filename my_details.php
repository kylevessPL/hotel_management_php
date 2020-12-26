<?php
include 'helpers/include_all.php';
include 'process/get_customer_id.php';
include 'process/validate_customer_details_fields.php';

get_customer_id($alert_msg, $alert_type, $customer_id);

if (isset($customer_id))
{
    $sql = "SELECT first_name, last_name, document_type, document_id FROM customers WHERE id = '$customer_id'";
    $result = query($sql);
    $customer_data = mysqli_fetch_assoc($result);
}

function update_customer_details($customer_id, string $first_name, string $last_name, string $document_type, string $document_id): array
{
    if (!isset($customer_id))
    {
        $sql = "INSERT INTO customers (first_name, last_name, document_type, document_id) VALUES('$first_name', '$last_name', '$document_type', '$document_id')";
    }
    else
    {
        $sql = "UPDATE customers SET first_name = '$first_name', last_name = '$last_name', document_type = '$document_type', document_id = '$document_id' where id = '$customer_id'";
    }
    if (!query($sql))
    {
        throw new Exception(dbException());
    }
    if (!isset($customer_id))
    {
        $sql = "UPDATE users SET customer_id = '" . insert_id() . "' where id = '" . $_SESSION['user_id'] . "'";
        if (!query($sql))
        {
            throw new Exception(dbException());
        }
        $alert_msg = "Thank you. You have now full access to all features on the site.";
    }
    else
    {
        $alert_msg = "Personal data modified successfully";
    }
    $alert_type = "success";
    return array($alert_msg, $alert_type);
}

if (isset($_POST["customer-details-submit"]))
{
    validate_customer_details_fields($_POST, $alert_msg, $alert_type);
    if (!isset($alert_msg) || $alert_type == 'info')
    {
        $first_name = escape_string($_POST["first-name"]);
        $last_name = escape_string($_POST["last-name"]);
        $document_type = escape_string($_POST["document-type"]);
        $document_id = escape_string($_POST["document-id"]);
        autocommit(false);
        try
        {
            [$alert_msg, $alert_type] = update_customer_details($customer_id, $first_name, $last_name, $document_type, $document_id);
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
            <p>View your account details and edit</p>
            <div class="row mb-4">
                <div class="col-12 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Your Profile</h4>
                                    <hr>
                                </div>
                            </div>
                            <?php echo isset($alert_msg) ? '<p class="alert alert-'.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_msg).'</p>' : ''; ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="form-customer-details" name="form-customer-details" method="post" action="/account/my-details">
                                        <div class="form-group row">
                                            <label for="first-name" class="col-4 col-form-label">First name<span style="color: red">*</span></label>
                                            <div class="col-8">
                                                <input type="text" id="first-name" name="first-name" placeholder="Enter first name" class="form-control"
                                                    <?php if (isset($alert_msg) && $alert_type == 'success')
                                                    {
                                                        echo 'value="'.htmlspecialchars($_POST['first-name']).'"';
                                                    }
                                                    else if (isset($customer_data))
                                                    {
                                                        echo 'value="'.htmlspecialchars($customer_data['first_name']).'"';
                                                    }
                                                    ?>>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="last-name" class="col-4 col-form-label">Last name<span style="color: red">*</span></label>
                                            <div class="col-8">
                                                <input type="text" id="last-name" name="last-name" placeholder="Enter last name" class="form-control"
                                                    <?php if (isset($alert_msg) && $alert_type == 'success')
                                                    {
                                                        echo 'value="'.htmlspecialchars($_POST['last-name']).'"';
                                                    }
                                                    else if (isset($customer_data))
                                                    {
                                                        echo 'value="'.htmlspecialchars($customer_data['last_name']).'"';
                                                    }
                                                    ?>>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-type" class="col-4 col-form-label">Document type<span style="color: red">*</span></label>
                                            <div class="col-4">
                                                <select id="document-type" name="document-type" class="selectpicker form-control">
                                                    <option value="ID card"
                                                        <?php if ((isset($alert_msg) && $alert_type == 'success' && $_POST['document-type'] == 'ID card') || (isset($customer_data) && $customer_data['document_type'] == 'ID card'))
                                                        {
                                                            echo 'selected';
                                                        }
                                                        ?>>ID card</option>
                                                    <option value="Passport"
                                                        <?php if ((isset($alert_msg) && $alert_type == 'success' && $_POST['document-type'] == 'Passport') || (isset($customer_data) && $customer_data['document_type'] == 'Passport'))
                                                        {
                                                            echo 'selected';
                                                        }
                                                        ?>>Passport</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-id" class="col-4 col-form-label">Document ID<span style="color: red">*</span></label>
                                            <div class="col-8">
                                                <input type="text" id="document-id" name="document-id" placeholder="Enter document ID" class="form-control"
                                                    <?php if (isset($alert_msg) && $alert_type == 'success')
                                                    {
                                                        echo 'value="'.htmlspecialchars($_POST['document-id']).'"';
                                                    }
                                                    else if (isset($customer_data))
                                                    {
                                                        echo 'value="'.htmlspecialchars($customer_data['document_id']).'"';
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="/assets/js/validation-additional-methods.js"></script>
<script src="/assets/js/my-details.js"></script>
<script src="/assets/js/chat.js"></script>

</body>
</html>