<?php
include_once('helpers/conn.php');
session_start();
if(isset($_SESSION["user_id"]))
{
    header("location:dashboard.php");
}
if(isset($_POST["register-submit"]))
{
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
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
if(isset($_POST["login-submit"]))
{
    $login = mysqli_real_escape_string($con, $_POST["login"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $sql = "SELECT * FROM users WHERE username = '$login' OR email = '$login'";
    $result = query($sql);
    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row['password']))
        {
            $_SESSION['user_id'] = $row['id'];
            header("location:dashboard");
        }
        else
        {
            $alertMsg = "Wrong password!";
            $alertType = "danger";
        }
    }
    else
    {
        $alertMsg = "User not found!";
        $alertType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="/assets/images/favicon.ico" rel="shortcut icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="assets/css/index.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script src="assets/js/form-validation.js"></script>

    <title>Sign In</title>
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
                        <p class="alert alert-<?php echo isset($alertType) ? $alertType : 'info'; ?>">
                            <?php echo isset($alertMsg) ? $alertMsg : 'Fill in your data'; ?></p>
                        <form id="form-register" name="form-register" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="username" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="password">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="password2">Repeat password</label>
                                <input type="password" id="password2" name="password2" class="form-control" placeholder="repeat password">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="email">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="email">
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
                        <p class="alert alert-<?php echo isset($alertType) ? $alertType : 'success'; ?>"
                           style="display: <?php echo isset($alertMsg) ? 'block' : 'none'; ?>;">
                            <?php echo isset($alertMsg) ? $alertMsg : ''; ?></p>
                        <form id="form-login" name="form-login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="form-group">
                                <label for="login">Username or e-mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="login" name="login" class="form-control" placeholder="username or email" autofocus>
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
                                    <input type="password" id="password" name="password" class="form-control" placeholder="password">
                                </div>
                            </div>
                            <div class="text-right mt-3">
                                <a href="/?action=register">Register</a>
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
</body>
</html>