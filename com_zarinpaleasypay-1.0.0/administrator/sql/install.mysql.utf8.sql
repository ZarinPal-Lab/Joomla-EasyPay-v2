CREATE TABLE IF NOT EXISTS `#__zarinpaleasypay_forms` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`alias` VARCHAR(50)  NOT NULL UNIQUE ,
`description` TEXT NOT NULL ,
`amount` INT(10)  NOT NULL ,
`currency` VARCHAR(255)  NOT NULL ,
`callback_url` VARCHAR(300)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__zarinpaleasypay_faktors` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`form_id` INT(11) UNSIGNED NOT NULL ,
`first_name` VARCHAR(255)  NOT NULL ,
`last_name` VARCHAR(255)  NOT NULL ,
`email` CHAR(255)  NOT NULL ,
`mobile` CHAR(100)  NOT NULL ,
`description` VARCHAR(500)  NOT NULL ,
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__zarinpaleasypay_payments` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`faktor_id` INT(11)  NOT NULL ,
`status` VARCHAR(255) DEFAULT 'pending',
`status_id` CHAR(5) DEFAULT '0',
`ref_id` CHAR(255) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;