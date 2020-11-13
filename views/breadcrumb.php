<?php
include 'helpers/page_info.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $title != 'Dashboard' ? $title : 'Overview'; ?></li>
    </ol>
</nav>
<h1 class="h2"><?php echo $title; ?></h1>
