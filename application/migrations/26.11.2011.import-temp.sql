CREATE TABLE `osp`.`temp_imports` (
`import_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`import_data` TEXT NOT NULL 
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE `osp`.`temp_sheets` (
`import_id` INT( 11 ) NOT NULL ,
`sheet_id` TINYINT( 2 ) NOT NULL ,
`sheet_data` TEXT NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE `osp`.`temp_sheets_data` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sheet_id` INT( 11 ) NOT NULL ,
`row_id` INT( 11 ) NOT NULL ,
`row_data` TEXT NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;