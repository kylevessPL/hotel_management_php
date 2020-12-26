# HoteLA - Hotel Client Dashboard
Hotel client dashboard web GUI implementation using PHP 7.4, JavaScript (incl. jQuery library) and MySQL database.

## Features
- client login/register functionality (incl. database password encryption - bcrypt algorithm)
- homepage with client bookings/payments summary & basic statistics plus hotel infrastructure, rooms and additional services information with gallery
- available rooms search with advanced query parameters plus room overview and room-to-book selection
- additional services overview with detailed description
- booking form with booking shopping cart & promo codes availability
- payment form with 4 payment variants available incl. PayPal sandbox implementation (personal API key required) 
- payment form credit card type detector
- my bookings overview with 4 latest bookings shown as cards plus selected booking overview
- my payment history overview with filter panes
- bookings retry-payment & cancel options available
- my details update page
- my addresses update page with CRUD operations
- FAQ - frequently asked questions (accordion style)
- contact form with email sending simulation (php.ini configuration necessary) & Google Maps iframe
- facebook floating chatbox (using Elfsight Apps plugin, personal account connection necessary)
- all in-page forms client-side validation using jQuery Validation plugin
- responsiveness & mobile-friendly.

## How to run

If you plan to test it in local environment only, install & configure XAMPP/WAMP/MAMP/LAMP with Apache server and MySQL database.

To make the contact form work properly, install & configure [sendmail](https://www.glob.com.au/sendmail) with your preferred SMTP server.

Put all project files in htdocs folder.

Then navigate to:
`http://localhost`
and that's it.

Enjoy :)
