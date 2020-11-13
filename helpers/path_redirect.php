<?php

if(isset($_SERVER['PATH_INFO']))
{
    switch($_SERVER['PATH_INFO'])
    {
        case '/personal-details':
            header('location:personal_details');
            break;
        case '/personal-addresses':
            header('location:personal_addresses');
            break;
        case '/available-rooms':
            header('location:available_rooms');
            break;
        case '/additional-services':
            header('location:additional_services');
            break;
        case '/book-room':
            header('location:book_room');
            break;
        case '/my-bookings':
            header('location:my_bookings');
            break;
        case '/payment-history':
            header('location:payment_history');
            break;
        case '/faq':
            header('location:faq');
            break;
        case '/contact':
            header('location:contact');
            break;
        case '/about-us':
            header('location:about-us');
            break;
        default:
            header('location:404');
            break;
    }
}

?>