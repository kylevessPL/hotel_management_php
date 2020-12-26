<?php
include 'helpers/include_all.php';
include_once 'process/validate_contact_fields.php';

if (isset($_POST["contact-submit"]))
{
    validate_contact_fields($_POST, $alert_msg, $alert_type);
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response']))
    {
        $alert_msg = "ReCaptcha has to be verified";
        $alert_type = "danger";
    }
    else
    {
        verify_captcha_response($alert_msg, $alert_type);
    }
    if (!isset($alert_msg))
    {
        [$to, $subject, $body, $headers] = create_mail_body();
        send_mail($alert_msg, $alert_type, $to, $subject, $body, $headers);
    }
}

function verify_captcha_response(&$alert_msg, &$alert_type)
{
    try
    {
        $secret = '6LeIFREaAAAAALZi0YgONK77yTrQ5lheSQL5Txg7';
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . rawurlencode($_POST['g-recaptcha-response']);
        $content = file_get_contents($url);
        $response = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        if (!$response->success)
        {
            $alert_msg = "ReCaptcha validation error";
            $alert_type = "danger";
        }
    }
    catch (JsonException $e)
    {
        $alert_msg = 'Oops, something went wrong. Please try again later.';
        $alert_type = "danger";
    }
}

function create_mail_body(): array
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
    $body_paragraphs = ["Name: {$name}", "<br>Email: {$email}", "<br>Message:", $message];
    $body = implode(PHP_EOL, $body_paragraphs);
    return array($to, $subject, $body, $headers);
}

function send_mail(&$alert_msg, &$alert_type, $to, $subject, $body, $headers)
{
    if (mail($to, $subject, $body, $headers))
    {
        $alert_msg = "Message successfully sent. We'll get back to you as soon as possible.";
        $alert_type = "success";
    }
    else
    {
        $alert_msg = 'Oops, something went wrong. Please try again later.';
        $alert_type = "danger";
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
            <div class="row">
                <div class="col-12 col-xl-7 mb-lg-0">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><i class="las la-envelope la-lg mr-2"></i> Contact us</div>
                        <div class="card-body">
                            <?php echo isset($alert_msg) ? '<p class="alert alert-'.htmlspecialchars($alert_type).'">'.htmlspecialchars($alert_msg).'</p>' : ''; ?>
                            <form id="form-contact" name="form-contact" method="post" action="/support/contact">
                                <div class="form-group">
                                    <label for="name">Full name<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message<span style="color: red">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" placeholder="Enter message"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6LeIFREaAAAAAGqK6vf8uF1P-dk6QXBFyIgHY0J5" data-callback="handleReCaptchaChange"></div>
                                    <input class="form-control" type="hidden" name="gRecaptchaResponse" id="gRecaptchaResponse" value="">
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
                            <a class="btn btn-circle animated-2 btn-md mb-3" href="https://www.google.com/maps/place/al.+Marszałka+Józefa+Piłsudskiego+75,+90-368+Łódź" target="_blank" style="cursor: pointer;">
                                <i class="las la-map-marker-alt pt-1 text-white" style="font-size: 26px;"></i>
                            </a>
                            <p>Piłsudskiego 75</p>
                            <p class="mb-md-0">90-368 Łódź</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle animated-2 btn-md mb-3" href="tel:422530679" target="_blank" style="cursor: pointer;">
                                <i class="las la-phone pt-1 text-white" style="font-size: 26px;"></i>
                            </a>
                            <p>42 25 30 679</p>
                            <p class="mb-md-0">Mon - Sat, 8:00 - 22:00</p>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-circle animated-2 btn-md mb-3" href="mailto:office@hotela.pl" target="_blank" style="cursor: pointer;">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="/assets/js/contact.js"></script>

</body>
</html>