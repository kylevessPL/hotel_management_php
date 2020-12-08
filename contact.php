<?php
include 'helpers/include_all.php';
include_once 'process/validate_contact_fields.php';

if(isset($_POST["contact-submit"]))
{
    validate_contact_fields($_POST, $alertMsg, $alertType);
    if(!isset($alertMsg))
    {
        $to = 'kacperpiasta@gmail.com';
        $subject = 'HoteLA: New client contact form message';
        $name = escape_string($_POST["name"]);
        $email = escape_string($_POST["email"]);
        $message = escape_string($_POST["message"]);
        $headers = [
            'From' => $name,
            'Reply-To' => $email,
            'Content-type' => 'text/html; charset=iso-8859-1'
        ];
        $bodyParagraphs = ["Name: {$name}", "<br>Email: {$email}", "<br>Message:", $message];
        $body = implode(PHP_EOL, $bodyParagraphs);
        if (mail($to, $subject, $body, $headers))
        {
            $alertMsg = "Message successfully sent. We'll get back to you as soon as possible.";
            $alertType = "success";
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
<div class="container-fluid main-container flex-grow-1">
    <div class="row">
        <?php view('sidebar.php'); ?>
        <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
            <?php view('breadcrumb.php'); ?>
            <p>Feel free to contact us if you have any any questions regarding our service</p>
            <div class="row mb-4">
                <div class="col-10 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><i class="las la-envelope la-lg mr-2"></i> Contact us</div>
                        <div class="card-body">
                            <?php echo isset($alertMsg) ? '<p class="alert alert-'.htmlspecialchars($alertType).'">'.htmlspecialchars($alertMsg).'</p>' : ''; ?>
                            <form id="form-contact" name="form-contact" method="post" action="/support/contact">
                                <div class="form-group">
                                    <label for="name">Full name<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Enter full name" minlength="2" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address<span style="color: red">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                                    <small id="emailHelp" class="form-text text-muted">We never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message<span style="color: red">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" minlength="10" required></textarea>
                                </div>
                                <input class="btn btn-primary text-right" name="contact-submit" value="Submit" type="submit">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-5">
                    <div class="container-fluid">
                        <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDZhKZ5AjqR2uMcr9ujCJQVUoox8_SHNiA&q=al.+Marszałka+Józefa+Piłsudskiego+75,+90-368+Łódź" width="100%" height="450" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="row text-center mt-4">
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-map-marker-alt pt-1 text-white" style="font-size: 26px;"></i>
                            </a>
                            <p>Piłsudskiego 75</p>
                            <p class="mb-md-0">90-368 Łódź</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-phone pt-1 text-white" style="font-size: 26px;"></i>
                            </a>
                            <p>42 25 30 679</p>
                            <p class="mb-md-0">Mon - Sat, 8:00 - 22:00</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle btn-md mb-3">
                                <i class="las la-envelope pt-1 text-white" style="font-size: 26px;"></i>
                            </a>
                            <p>office@hotela.pl</p>
                            <p class="mb-0">info@hotela.pl</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php view('footer.php'); ?>

<?php view('scripts.php'); ?>

</body>
</html>