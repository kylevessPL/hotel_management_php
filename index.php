<?php
require_once 'helpers/conn.php';
include_once 'process/validate_reg_fields.php';

session_start();
if(isset($_SESSION["user_id"]))
{
    header("location:dashboard");
}

if(isset($_POST["register-submit"]))
{
    validate_reg_fields($_POST, $alertMsg, $alertType);
    if(!isset($alertMsg))
    {
        $username = escape_string($_POST["username"]);
        $password = escape_string($_POST["password"]);
        $email = escape_string($_POST["email"]);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql1 = "SELECT id FROM users WHERE username = '$username'";
        $sql2 = "SELECT id FROM users WHERE email = '$email'";
        $result1 = query($sql1);
        $result2 = query($sql2);
        if(mysqli_num_rows($result1) > 0)
        {
            $alertMsg = "Username not available";
            $alertType = "danger";
        }
        else if(mysqli_num_rows($result2) > 0)
        {
            $alertMsg = "There is already a user with this email";
            $alertType = "danger";
        }
        else
        {
            $sql = "INSERT INTO users (username, password, email) VALUES('$username', '$password', '$email')";
            if(query($sql))
            {
                $alertMsg = "You have successfully registered";
                $alertType = "success";
            }
        }
    }
}
if(isset($_POST["login-submit"]))
{
    if(count($_POST) != count(array_filter($_POST)))
    {
        $alertMsg = "All fields are required";
        $alertType = "danger";
    }
    else
    {
        $login = escape_string($_POST["login"]);
        $password = escape_string($_POST["password"]);
        $sql = "SELECT * FROM users WHERE username = '$login' OR email = '$login'";
        $result = query($sql);
        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password']))
            {
                if(isset($_POST["remember-me"]))
                {
                    setcookie("login_remember", $_POST["login"], time() + 3600 * 24 * 30);
                }
                else if(isset($_COOKIE["login_remember"]))
                {
                    unset($_COOKIE['login_remember']);
                    setcookie('login_remember', null, time() - 3600);
                }
                $_SESSION['user_id'] = $row['id'];
                header("location:dashboard");
            }
            else
            {
                $alertMsg = "Invalid username or password";
                $alertType = "danger";
            }
        }
        else
        {
            $alertMsg = "Invalid username or password";
            $alertType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/images/favicon.ico" rel="shortcut icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="assets/css/index.css" rel="stylesheet">
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
                    if(isset($_GET["action"]) == "register" ||
                    (isset($_POST["register-submit"]) && (isset($alertType) ? ($alertType != "success") : true)))
                    {
                        ?>
                        <h5 class="card-title text-center mb-4">Register</h5>
                        <p class="alert alert-<?php echo isset($alertType) ? htmlspecialchars($alertType) : 'info'; ?>">
                            <?php echo isset($alertMsg) ? htmlspecialchars($alertMsg) : 'Fill in your data'; ?></p>
                        <form id="form-register" name="form-register" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="form-group">
                                <label for="username">Username<span style="color: red">*</span></label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password<span style="color: red">*</span></label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password2">Repeat password<span style="color: red">*</span></label>
                                <input type="password" id="password2" name="password2" class="form-control" placeholder="Repeat password">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="email">E-mail<span style="color: red">*</span></label>
                                <input type="text" id="email" name="email" class="form-control" placeholder="Enter email">
                            </div>
                            <div class="text-right mt-3">
                                <a href="/">Sign In</a>
                            </div>
                            <input class="btn btn-lg btn-primary btn-block mt-2" name="register-submit" value="Sign Up" type="submit">
                        </form>
                        <?php
                    }
                    else
                    {
                        ?>
                        <h5 class="card-title text-center mb-4">Login</h5>
                        <p class="alert alert-<?php echo $alertType ?? 'success'; ?>"
                           style="display: <?php echo isset($alertMsg) ? 'block' : 'none'; ?>;">
                            <?php echo $alertMsg ?? ''; ?></p>
                        <form id="form-login" name="form-login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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
                                           if(isset($_COOKIE["login_remember"]))
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
                            <div class="text-right mt-3">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="assets/js/validation-additional-methods.js"></script>
<script src="assets/js/index.js"></script>

</body>
</html>