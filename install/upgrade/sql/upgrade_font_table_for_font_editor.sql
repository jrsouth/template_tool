ALTER TABLE `fonts` CHANGE `display_name` `name` varchar(64) NOT NULL;
ALTER TABLE `fonts` ADD `original_file` varchar(64) NOT NULL DEFAULT "Built in";
UPDATE `fonts` SET `original_file` = REPLACE(`font_file`,".php",".ttf");
ALTER TABLE `fonts` MODIFY `original_file` varchar(64) NOT NULL;
UPDATE `fonts` SET `font_file` = REPLACE(`font_file`,".php","");
