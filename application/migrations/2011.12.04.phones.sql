ALTER TABLE `phones`
					ADD `image` VARCHAR( 255 ) NOT NULL AFTER `model`,
					ADD `solder_image` VARCHAR( 255 ) NOT NULL AFTER `image`,
					ADD `cabinet_image` VARCHAR( 255 ) NOT NULL AFTER `solder_image` ;