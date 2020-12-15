CREATE EVENT `set_booking_completed` ON SCHEDULE EVERY 1 MINUTE ON COMPLETION PRESERVE ENABLE
    DO UPDATE bookings SET status = 'Completed' WHERE end_date = CURDATE() AND status = 'Paid';

CREATE EVENT `set_booking_cancelled` ON SCHEDULE EVERY 1 MINUTE ON COMPLETION PRESERVE ENABLE
    DO UPDATE bookings SET status = 'Cancelled' WHERE ((TIMESTAMPDIFF(DAY, book_date, CURRENT_TIMESTAMP) > 7
    AND TIMESTAMPDIFF(DAY, book_date, start_date) > 7) OR (TIMESTAMPDIFF(DAY, start_date, CURRENT_TIMESTAMP) >= 0
    AND TIMESTAMPDIFF(DAY, book_date, start_date) <= 7)) AND status = 'Unpaid';
