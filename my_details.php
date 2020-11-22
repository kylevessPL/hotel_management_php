<?php
include 'helpers/include_all.php';

include 'process/check_customer_details_existence.php';



?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<?php view('head.php'); ?>

<body>
<?php view('navbar.php'); ?>
<div class="container-fluid">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>View your account details and edit</p>
            <div class="row mb-4">
                <div class="col-10 col-xl-7 mb-4 mb-lg-0">
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
                                            <label for="first-name" class="col-4 col-form-label">First name*</label>
                                            <div class="col-8">
                                                <input type="text" id="first-name" name="first-name" placeholder="Enter first name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="last-name" class="col-4 col-form-label">Last name*</label>
                                            <div class="col-8">
                                                <input type="text" id="last-name" name="last-name" placeholder="Enter last name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-type" class="col-4 col-form-label">Document type*</label>
                                            <div class="col-8">
                                                <select id="document-type" name="document-type" class="custom-select">
                                                    <option value="id-card">ID card</option>
                                                    <option value="passport">Passport</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="document-id" class="col-4 col-form-label">Document ID*</label>
                                            <div class="col-8">
                                                <input type="text" id="document-id" name="document-id" placeholder="Enter document ID" class="form-control">
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
            <?php view('footer.php'); ?>
        </main>
    </div>
</div>

<?php view('sign_out_modal.php'); ?>

<?php view('scripts.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="../assets/js/form-validation.js"></script>

</body>
</html>