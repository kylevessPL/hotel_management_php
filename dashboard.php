<?php

include_once('helpers/init.php');

$user_id = mysqli_real_escape_string($con, $_SESSION["user_id"]);
$sql = "SELECT username FROM users WHERE id = '$user_id'";
$query = query($sql);
$user_data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/images/favicon.ico" rel="shortcut icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/sign-out-modal.js"></script>

    <title>
        <?php
        $title = isset($_GET["action"]) ? htmlspecialchars($_GET["action"]) : 'HoteLA Client Dashboard';
        echo ucfirst(str_replace("-"," ", $title));
        ?>
    </title>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 90px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.1);
        }

        .sidebar .active
        {
            background-color: #eaecf0;
            border-radius: 3px;
            margin-left: 15px;
            margin-right: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                top: 11.5rem;
                padding: 0;
            }
        }

        .navbar {
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .1);
        }

        @media (min-width: 768px) {
            .navbar {
                top: 0;
                position: -webkit-sticky;
                position: sticky;
                z-index: 999;
            }
        }

        .sidebar .nav-link {
            color: #41516f;
            padding-left: 20px;
        }

        .sidebar .nav-link.active {
            color: #0c68f1;
            padding-left: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light p-2">
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
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item pl-2" href="/dashboard/my-details">
                            <i class="las la-address-book align-top mr-1" style="font-size: 28px;"></i>My details
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item pl-2" href="/dashboard/my-addresses">
                            <i class="las la-map-marker-alt align-top mr-1" style="font-size: 28px;"></i>My addresses
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item logout-action pl-2" href="/logout">
                            <i class="las la-sign-out-alt align-top mr-1" style="font-size: 28px;"></i>Sign out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light mt-2 sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link active" aria-current="page" href="/dashboard">
                                <i class="las la-home align-bottom" style="font-size: 32px;"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="/dashboard/available-rooms">
                                <i class="las la-bed align-bottom" style="font-size: 32px;"></i>
                                <span>Available rooms</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="/dashboard/additional-services">
                                <i class="las la-concierge-bell align-bottom" style="font-size: 32px;"></i>
                                <span>Additional services</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="/dashboard/book-room">
                                <i class="las la-user-clock align-bottom" style="font-size: 32px;"></i>
                                <span>Book a room</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="/dashboard/my-bookings">
                                <i class="las la-bookmark align-bottom" style="font-size: 32px;"></i>
                                <span>My bookings</span>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="/dashboard/payment-history">
                                <i class="las la-file-invoice-dollar align-bottom" style="font-size: 32px;"></i>
                                <span>Payment history</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Overview</li>
                    </ol>
                </nav>
                <h1 class="h2">Dashboard</h1>
                <p>This is the homepage of a simple admin interface which is part of a tutorial written on Themesberg</p>
                <div class="row my-4">
                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Customers</h5>
                            <div class="card-body">
                                <h5 class="card-title">345k</h5>
                                <p class="card-text">Feb 1 - Apr 1, United States</p>
                                <p class="card-text text-success">18.2% increase since last month</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Revenue</h5>
                            <div class="card-body">
                                <h5 class="card-title">$2.4k</h5>
                                <p class="card-text">Feb 1 - Apr 1, United States</p>
                                <p class="card-text text-success">4.6% increase since last month</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Purchases</h5>
                            <div class="card-body">
                                <h5 class="card-title">43</h5>
                                <p class="card-text">Feb 1 - Apr 1, United States</p>
                                <p class="card-text text-danger">2.6% decrease since last month</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Traffic</h5>
                            <div class="card-body">
                                <h5 class="card-title">64k</h5>
                                <p class="card-text">Feb 1 - Apr 1, United States</p>
                                <p class="card-text text-success">2.5% increase since last month</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">Latest transactions</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Order</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Date</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th scope="row">17371705</th>
                                            <td>Volt Premium Bootstrap 5 Dashboard</td>
                                            <td>johndoe@gmail.com</td>
                                            <td>€61.11</td>
                                            <td>Aug 31 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">17370540</th>
                                            <td>Pixel Pro Premium Bootstrap UI Kit</td>
                                            <td>jacob.monroe@company.com</td>
                                            <td>$153.11</td>
                                            <td>Aug 28 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">17371705</th>
                                            <td>Volt Premium Bootstrap 5 Dashboard</td>
                                            <td>johndoe@gmail.com</td>
                                            <td>€61.11</td>
                                            <td>Aug 31 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">17370540</th>
                                            <td>Pixel Pro Premium Bootstrap UI Kit</td>
                                            <td>jacob.monroe@company.com</td>
                                            <td>$153.11</td>
                                            <td>Aug 28 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">17371705</th>
                                            <td>Volt Premium Bootstrap 5 Dashboard</td>
                                            <td>johndoe@gmail.com</td>
                                            <td>€61.11</td>
                                            <td>Aug 31 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">17370540</th>
                                            <td>Pixel Pro Premium Bootstrap UI Kit</td>
                                            <td>jacob.monroe@company.com</td>
                                            <td>$153.11</td>
                                            <td>Aug 28 2020</td>
                                            <td><a href="#" class="btn btn-sm btn-primary">View</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="#" class="btn btn-block btn-light">View all</a>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="pt-5 d-flex justify-content-between">
                    <span>Copyright © 2020 HoteLA</span>
                    <ul class="nav m-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="/dashboard/faq">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="/dashboard/about-us">About us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="/dashboard/contact">Contact</a>
                        </li>
                    </ul>
                </footer>
            </main>
        </div>
    </div>

    <?php view('sign_out_modal.php'); ?>

</body>
</html>
