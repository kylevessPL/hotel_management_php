<?php
include 'helpers/page_info.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php
            if($path == '/dashboard' || $path == '\\')
            {
                ?>
                <a href="/dashboard">Dashboard</a>
                <?php
            }
            else if($path == '/account')
            {
                ?>
                <a href="/account/my-details">Account</a>
            <?php
            }
            else if($path == '/support')
            {
                ?>
                <a href="/support/contact">Support</a>
                <?php
            }
            ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $title != 'Dashboard' ? $title : 'Overview'; ?></li>
    </ol>
</nav>
<h1 class="h2"><?php echo $title; ?></h1>
