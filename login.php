<?php
require_once 'helpers/conn.php';
include_once 'process/validate_reg_fields.php';

session_start();

if (isset($_SESSION["user_id"]))
{
    header("location:/dashboard");
    return;
}

if (isset($_POST["register-submit"]))
{
    validate_reg_fields($_POST, $alert_msg, $alert_type);
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
        $username = escape_string($_POST["username"]);
        $email = escape_string($_POST["email"]);
        $result1 = check_username_availability($username);
        $result2 = check_email_availability($email);
        if ($result1 == 'true' && $result2 == 'true')
        {
            register_user($alert_msg, $alert_type, $username, $email);
        }
    }
}

if (isset($_POST["login-submit"]))
{
    validate_login_fields($alert_msg, $alert_type);
    if (!isset($alert_msg))
    {
        $id = check_user_existence($alert_msg, $alert_type);
        if (!isset($alert_msg))
        {
            set_username_cookie();
            $_SESSION['user_id'] = $id;
            header("location:/dashboard");
        }
    }
}

function validate_login_fields(&$alert_msg, &$alert_type)
{
    if (count($_POST) != count(array_filter($_POST)))
    {
        $alert_msg = "All fields are required";
        $alert_type = "danger";
    }
}

function verify_captcha_response(&$alert_msg, &$alert_type)
{
    try
    {
        $secret = '6LeIFREaAAAAALZi0YgONK77yTrQ5lheSQL5Txg7';
        $response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . rawurlencode($_POST['g-recaptcha-response'])), false, 512, JSON_THROW_ON_ERROR);
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

function check_user_existence(&$alert_msg, &$alert_type)
{
    $login = escape_string($_POST["login"]);
    $password = escape_string($_POST["password"]);
    $sql = "SELECT * FROM users WHERE username = '$login' OR email = '$login'";
    $result = query($sql);
    if (mysqli_num_rows($result) == 0)
    {
        $alert_msg = "Invalid username or password";
        $alert_type = "danger";
        return null;
    }

    $row = mysqli_fetch_assoc($result);
    if (!password_verify($password, $row['password']))
    {
        $alert_msg = "Invalid username or password";
        $alert_type = "danger";
        return null;
    }

    return $row['id'];
}

function set_username_cookie(): void
{
    if (isset($_POST["remember-me"]))
    {
        setcookie("login_remember", $_POST["login"], time() + 3600 * 24 * 30);
    }
    else if (isset($_COOKIE["login_remember"]))
    {
        unset($_COOKIE['login_remember']);
        setcookie('login_remember', null, time() - 3600);
    }
}

function check_username_availability(string $username)
{
    $url = $_SERVER ["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] . "/process/check_username_availability?username=" . rawurlencode($username);
    return file_get_contents($url);
}

function check_email_availability(string $email)
{
    $url = $_SERVER ["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] . "/process/check_email_availability?email=" . rawurlencode($email);
    return file_get_contents($url);
}

function register_user(&$alert_msg, &$alert_type, string $username, string $email)
{
    $password = escape_string($_POST["password"]);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, email) VALUES('$username', '$password', '$email')";
    if (query($sql))
    {
        $alert_msg = "You have successfully registered";
        $alert_type = "success";
    }
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/images/favicon.ico" rel="shortcut icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <title><?php echo (isset($_GET["action"]) == "register" ? 'Register' : 'Log In') ?></title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card my-5">
                <div class="card-body">
                    <?php
                    if (isset($_GET["action"]) == "register" ||
                    (isset($_POST["register-submit"]) && (isset($alert_type) ? ($alert_type != "success") : true)))
                    {
                        ?>
                        <h5 class="card-title text-center mb-4">Register</h5>
                        <p class="alert alert-<?php echo isset($alert_type) ? htmlspecialchars($alert_type) : 'info'; ?>">
                            <?php echo isset($alert_msg) ? htmlspecialchars($alert_msg) : 'Fill in your data'; ?></p>
                        <form id="form-register" name="form-register" method="post" action="/login">
                            <div class="form-group">
                                <label for="username">Username<span style="color: red">*</span></label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" autocomplete="off" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password<span style="color: red">*</span></label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password2">Repeat password<span style="color: red">*</span></label>
                                <input type="password" id="password2" name="password2" class="form-control" placeholder="Repeat password" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="email">E-mail<span style="color: red">*</span></label>
                                <input type="text" id="email" name="email" class="form-control" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LeIFREaAAAAAGqK6vf8uF1P-dk6QXBFyIgHY0J5" data-callback="handleReCaptchaChange"></div>
                                <input class="form-control" type="hidden" name="gRecaptchaResponse" id="gRecaptchaResponse" value="">
                            </div>
                            <div class="text-right mt-3">
                                <a href="/login">Sign In</a>
                            </div>
                            <input class="btn btn-lg btn-primary btn-block mt-2" name="register-submit" value="Sign Up" type="submit">
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <h5 class="card-title text-center mb-4">Login</h5>
                        <p class="alert alert-<?php echo $alert_type ?? 'success'; ?>"
                           style="display: <?php echo isset($alert_msg) ? 'block' : 'none'; ?>;">
                            <?php echo $alert_msg ?? ''; ?></p>
                        <form id="form-login" name="form-login" method="post" action="/login">
                            <div class="form-group">
                                <label for="login">Username or e-mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="login" name="login" class="form-control" placeholder="Enter username or email"
                                           value=
                                           <?php
                                           if (isset($_POST["login"], $alert_msg))
                                           {
                                               echo '"', htmlspecialchars($_POST["login"]), '"';
                                           }
                                           else if (isset($_COOKIE["login_remember"]))
                                           {
                                               echo '"', htmlspecialchars($_COOKIE["login_remember"]), '"';
                                           }
                                           else
                                           {
                                               echo '"" autofocus';
                                           }
                                           ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-key"></i>
                                        </div>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                                        <?php echo isset($_COOKIE["login_remember"]) ? 'autofocus' : ''; ?>>
                                </div>
                            </div>
                            <div class="form-group custom-control custom-checkbox">
                                <input type="checkbox" id="remember-me" name="remember-me" class="custom-control-input"
                                    <?php echo isset($_COOKIE["login_remember"]) ? 'checked' : 'unchecked'; ?>>
                                <label class="custom-control-label" for="remember-me">Remember username</label>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="/"><i class="fas fa-long-arrow-alt-left mr-2"></i>Back to HoteLA Home</a>
                                <a href="?action=register">Register</a>
                            </div>
                            <input class="btn btn-lg btn-primary btn-block mt-2" name="login-submit" value="Sign In" type="submit">
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="assets/js/validation-additional-methods.js"></script>
<script src="assets/js/login.js"></script>

</body>
</html>