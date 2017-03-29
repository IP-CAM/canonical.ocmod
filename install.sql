CREATE TABLE IF NOT EXISTS `canonical_pages`(
 	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`canonical_url` VARCHAR(400) NOT NULL,
	`url` VARCHAR(400) NULL DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `canonical_pages_params` (
	`id` INT NOT NULL,
	`param` VARCHAR(30) NOT NULL,
	`value` VARCHAR(200) NOT NULL,
	FOREIGN KEY (`id`)
  		REFERENCES `canonical_pages`(`id`)
		ON DELETE CASCADE
);