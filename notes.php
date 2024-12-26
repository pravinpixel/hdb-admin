ALTER TABLE `approve_requests` ADD `email_status` BOOLEAN NULL DEFAULT FALSE AFTER `date_of_return`;

25-11-2021
ALTER TABLE `configs` ADD `enable_email` BOOLEAN NOT NULL DEFAULT FALSE AFTER `item_number`, ADD `last_cron_updated` DATETIME NULL AFTER `enable_email`;

10-01-2021
CREATE TABLE `email_configurations` (  `id` INT NOT NULL AUTO_INCREMENT, `driver` VARCHAR(191) NULL , `host` VARCHAR(191) NULL , `port` VARCHAR(191) NULL , `encryption` VARCHAR(191) NULL , `user_name` VARCHAR(191) NULL , `password` LONGTEXT NULL , `sender_name` VARCHAR(191) NULL , `sender_email` VARCHAR(191) NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL ,PRIMARY KEY (`id`) ) ENGINE = InnoDB;