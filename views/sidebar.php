<?php
include 'helpers/page_info.php';
?>

<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light mt-2 sidebar collapse">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="/dashboard"
                   class=<?php echo $title == 'Dashboard' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-home align-bottom" style="font-size: 32px;"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/dashboard/available-rooms"
                   class=<?php echo $title == 'Available rooms' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-bed align-bottom" style="font-size: 32px;"></i>
                    <span>Available rooms</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/dashboard/additional-services"
                   class=<?php echo $title == 'Additional services' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-concierge-bell align-bottom" style="font-size: 32px;"></i>
                    <span>Additional services</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/dashboard/book-room"
                   class=<?php echo $title == 'Book room' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-user-clock align-bottom" style="font-size: 32px;"></i>
                    <span>Book room</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/dashboard/my-bookings"
                   class=<?php echo $title == 'My bookings' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-bookmark align-bottom" style="font-size: 32px;"></i>
                    <span>My bookings</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/dashboard/payment-history"
                   class=<?php echo $title == 'Payment history' ? '"nav-link active" aria-current="page"' : '"nav-link"'; ?>>
                    <i class="las la-file-invoice-dollar align-bottom" style="font-size: 32px;"></i>
                    <span>Payment history</span>
                </a>
            </li>
        </ul>
    </div>
</nav>