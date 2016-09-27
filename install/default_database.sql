-- Default template tool data set

--
-- Table structure for table `colours`
--

DROP TABLE IF EXISTS `colours`;

CREATE TABLE `colours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `RGBA` char(8) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Default data for table `colours`
--

LOCK TABLES `colours` WRITE;
INSERT INTO `colours` VALUES
	(NULL,'Black','000000FF'),
	(NULL,'Red','AA0000FF'),
	(NULL,'Grey','56524EFF'),
	(NULL,'Light Grey','A7A7A7FF'),
	(NULL,'White','FFFFFFFF'),
	(NULL,'Yellow','F8BF00FF'),
	(NULL,'Blue','00A3ADFF'),
	(NULL,'Purple','771AA1FF'),
	(NULL,'Green','74BC09FF'),
	(NULL,'Pink','DF2B5FFF');
UNLOCK TABLES;

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `type` enum('normal','data','wrapper') DEFAULT 'normal',
  `name` varchar(32) NOT NULL,
  `default_text` text NOT NULL,
  `force_uppercase` tinyint(4) NOT NULL DEFAULT '0',
  `character_limit` int(11) DEFAULT NULL,
  `font_id` int(11) NOT NULL,
  `font_size` double DEFAULT NULL,
  `colour_id` int(11) NOT NULL DEFAULT '1',
  `x_position` double DEFAULT NULL,
  `y_position` double DEFAULT NULL,
  `wrap_width` double DEFAULT NULL,
  `leading` double NOT NULL DEFAULT '0',
  `parent` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT '1',
  `tracking` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`),
  KEY `font_id` (`font_id`)
);

--
-- Default data for table `fields`
--

LOCK TABLES `fields` WRITE;
INSERT INTO `fields` VALUES
(NULL,1,'normal','Title','Template title',1,50,6,18,8,50,70,110,20,0,1,0),
(NULL,1,'normal','Main text','This is where your text goes.',0,2048,6,12,1,50,80,110,15,0,1,0);
UNLOCK TABLES;

--
-- Table structure for table `fonts`
--

DROP TABLE IF EXISTS `fonts`;
CREATE TABLE `fonts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `font_file` varchar(64) NOT NULL,
  `original_file` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Default data for table `fonts`
--

LOCK TABLES `fonts` WRITE;
INSERT INTO `fonts` VALUES
	(NULL,'Roboto Light','roboto_light','roboto_light.ttf'),
	(NULL,'Roboto Black Italic','roboto_blackitalic','roboto_blackitalic.ttf'),
	(NULL,'Roboto Black','roboto_black','roboto_black.ttf'),
	(NULL,'Roboto Bold Italic','roboto_bolditalic','roboto_bolditalic.ttf'),
	(NULL,'Roboto Bold','roboto_bold','roboto_bold.ttf'),
	(NULL,'Roboto Italic','roboto_italic','roboto_italic.ttf'),
	(NULL,'Roboto Light Italic','roboto_lightitalic','roboto_lightitalic.ttf'),
	(NULL,'Roboto Medium Italic','roboto_mediumitalic','roboto_mediumitalic.ttf'),
	(NULL,'Roboto Medium','roboto_medium','roboto_medium.ttf'),
	(NULL,'Roboto Regular','roboto_regular','roboto_regular.ttf'),
	(NULL,'Roboto Thin Italic','roboto_thinitalic','roboto_thinitalic.ttf'),
	(NULL,'Roboto Thin','roboto_thin','roboto_thin.ttf');
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `x_position` double NOT NULL,
  `y_position` double NOT NULL,
  `width` double NOT NULL,
  `height` double NOT NULL,
  `alignment` varchar(12) NOT NULL,
  `page` int(11) DEFAULT '1',
  `hide` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`)
);

--
-- Default data for table `images`
--

LOCK TABLES `images` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `saved_templates`
--

DROP TABLE IF EXISTS `saved_templates`;
CREATE TABLE `saved_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(64) DEFAULT 'default',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `template_id` int(11) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Default data for table `saved_templates`
--

LOCK TABLES `saved_templates` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `tags` varchar(256) DEFAULT NULL,
  `pdf_file` varchar(128) NOT NULL,
  `permissions` varchar(32) DEFAULT NULL,
  `bleed` double DEFAULT NULL,
  `owner` varchar(128) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `pagecount` int(11) DEFAULT '1',
  `height` double DEFAULT '297',
  `width` double DEFAULT '210',
  PRIMARY KEY (`id`)
);

--
-- Default data for table `templates`
--

LOCK TABLES `templates` WRITE;
INSERT INTO `templates` VALUES
	(1,'Sample template','sample','000000_test_template.pdf','',0,'',1,1,297,210);
UNLOCK TABLES;

--
-- Table structure for table `working_templates`
--

DROP TABLE IF EXISTS `working_templates`;
CREATE TABLE `working_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `template_id` int(11) NOT NULL,
  `data` text,
  PRIMARY KEY (`id`)
);

--
-- Default data for table `working_templates`
--

LOCK TABLES `working_templates` WRITE;
UNLOCK TABLES;

