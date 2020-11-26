<?php
$user_id = escape_string($_SESSION["user_id"]);
$sql = "SELECT username FROM users WHERE id = '$user_id'";
$query = query($sql);
$user_data = mysqli_fetch_assoc($query);
?>

<nav class="navbar navbar-light bg-light p-2 flex-shrink-0">
    <div class="d-flex col-12 col-md-3 col-lg-2 mb-2 mb-lg-0 flex-wrap flex-md-nowrap justify-content-between">
        <a class="navbar-brand" href="/dashboard">
            <img src="/assets/images/favicon.ico" alt="HoteLA logo" width="52" height="52" class="mr-2">
            HoteLA Client Dashboard
        </a>
        <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="col-12 col-md-5 col-lg-8 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                Hello, <?php echo $user_data['username']; ?>
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                <li>
                    <a class="dropdown-item pl-2" href="/account/my-details">
                        <i class="las la-address-book align-top mr-1" style="font-size: 28px;"></i>My details
                    </a>
                </li>
                <li>
                    <a class="dropdown-item pl-2" href="/account/my-addresses">
                        <i class="las la-map-marker-alt align-top mr-1" style="font-size: 28px;"></i>My addresses
                    </a>
                </li>
                <li>
                    <a class="dropdown-item logout-action pl-2" href="../process/logout.php">
                        <i class="las la-sign-out-alt align-top mr-1" style="font-size: 28px;"></i>Sign out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
