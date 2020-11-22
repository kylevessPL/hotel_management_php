--
-- Database: `hotel`
--


CREATE TABLE `additional_services` (
	`id` int(11) AUTO_INCREMENT,
	`name` varchar(40) NOT NULL,
	`price` numeric(10,2) DEFAULT 0,
    `desc` varchar(300),
	CONSTRAINT `additional_services_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `amenities` (
	`id` int(11) AUTO_INCREMENT,
	`name` varchar(40) NOT NULL,
	CONSTRAINT `amenities_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `bookings` (
	`id` int(11) AUTO_INCREMENT,
	`book_date` DATETIME NOT NULL,
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`offer_id` int(11),
	`final_price` numeric(10,2),
	`status` varchar(15) NOT NULL,
	CONSTRAINT `bookings_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `bookings_services` (
	`id` int(11) AUTO_INCREMENT,
	`booking_id` int(11) NOT NULL,
	`service_id` int(11) NOT NULL,
	CONSTRAINT `bookings_services_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `customers` (
	`id` int(11) AUTO_INCREMENT,
	`first_name` varchar(30) NOT NULL,
	`last_name` varchar(30) NOT NULL,
	`document_type` varchar(10) NOT NULL,
	`document_id` varchar(14) NOT NULL,
	CONSTRAINT `customers_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `customers_addresses` (
	`id` int(11) AUTO_INCREMENT,
	`customer_id` int(11) NOT NULL,
	`address_id` int(11) NOT NULL,
	CONSTRAINT `customers_addresses_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `addresses` (
	`id` int(11) AUTO_INCREMENT,
	`street_name` varchar(30) NOT NULL,
	`house_number` varchar(10) NOT NULL,
	`zip_code` varchar(10) NOT NULL,
	`city` varchar(30) NOT NULL,
	CONSTRAINT `addresses_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `payment_forms` (
	`id` int(11) AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	CONSTRAINT `payment_forms_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `payments` (
	`id` int(11) AUTO_INCREMENT,
	`booking_id` int(11) NOT NULL,
	`payment_date` DATETIME NOT NULL,
	`payment_form_id` int(11) NOT NULL,
	`transaction_id` character varying(36) NOT NULL,
	CONSTRAINT `payments_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `rooms_amenities` (
	`id` int(11) AUTO_INCREMENT,
	`room_id` int(11) NOT NULL,
	`amenity_id` int(11) NOT NULL,
	CONSTRAINT `rooms_amenities_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `rooms` (
	`id` int(11) AUTO_INCREMENT,
	`room_number` varchar(10) NOT NULL,
	`bed_amount` int(11) NOT NULL DEFAULT 1,
	`standard_price` numeric(10,2) NOT NULL DEFAULT 0,
	CONSTRAINT `rooms_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `special_offers` (
	`id` int(11) AUTO_INCREMENT,
	`discount` int(11) NOT NULL DEFAULT 0,
	`bookings_amount` int(11) NOT NULL DEFAULT 0,
	`description` varchar(100),
	CONSTRAINT `special_offers_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `customers_bookings` (
	`id` int(11) AUTO_INCREMENT,
	`customer_id` int(11) NOT NULL,
	`booking_id` int(11) NOT NULL,
	CONSTRAINT `customers_bookings_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `bookings_rooms` (
	`id` int(11) AUTO_INCREMENT,
	`booking_id` int(11) NOT NULL,
	`room_id` int(11) NOT NULL,
	CONSTRAINT `bookings_rooms_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;



CREATE TABLE `users` (
	`id` int(11) AUTO_INCREMENT,
	`username` varchar(30) NOT NULL,
	`password` varchar(255) NOT NULL,
	`email` varchar(50) NOT NULL,
	`customer_id` int(11),
	CONSTRAINT `users_pk` PRIMARY KEY (`id`)
) ENGINE=InnoDB;




ALTER TABLE `bookings_services` ADD CONSTRAINT `bookings_services_fk0` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);

ALTER TABLE `bookings_services` ADD CONSTRAINT `bookings_services_fk1` FOREIGN KEY (`service_id`) REFERENCES `additional_services`(`id`);

ALTER TABLE `payments` ADD CONSTRAINT `payments_fk0` FOREIGN KEY (`payment_form_id`) REFERENCES `payment_forms`(`id`);

ALTER TABLE `bookings` ADD CONSTRAINT `bookings_fk0` FOREIGN KEY (`offer_id`) REFERENCES `special_offers`(`id`);

ALTER TABLE `customers_bookings` ADD CONSTRAINT `customers_bookings_fk0` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);

ALTER TABLE `customers_bookings` ADD CONSTRAINT `customers_bookings_fk1` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);

ALTER TABLE `bookings_rooms` ADD CONSTRAINT `bookings_rooms_fk0` FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);

ALTER TABLE `bookings_rooms` ADD CONSTRAINT `bookings_rooms_fk1` FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`);

ALTER TABLE `rooms_amenities` ADD CONSTRAINT `rooms_amenities_fk0` FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`);

ALTER TABLE `rooms_amenities` ADD CONSTRAINT `rooms_amenities_fk1` FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`);

ALTER TABLE `customers_addresses` ADD CONSTRAINT `customers_addresses_fk0` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);

ALTER TABLE `customers_addresses` ADD CONSTRAINT `customers_addresses_fk1` FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`);

ALTER TABLE `users` ADD CONSTRAINT `users_fk0` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);
