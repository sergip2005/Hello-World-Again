--
-- Table structure for table `parts`
--

CREATE TABLE IF NOT EXISTS `parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'original name in partlist',
  `vendor_id` int(5) NOT NULL,
  `type` enum('s','c') COLLATE utf8_unicode_ci NOT NULL COMMENT 's = solder, c = cabinet',
  `ptype` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'part type field in price list (usual stands for solder parts)',
  `name_rus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'url of this part on mktel site',
  `mktel_has` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if this part is available on mktel (show link to it)',
  `price` float(7,2) NOT NULL DEFAULT '0.00',
  `show` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'show this part on phone page or not',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `parts`
--


-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(5) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `model` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `phones`
--


-- --------------------------------------------------------

--
-- Table structure for table `phones_parts`
--

CREATE TABLE IF NOT EXISTS `phones_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `cct_ref` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'identificator of this part on phone picture',
  `num` smallint(2) NOT NULL DEFAULT '0' COMMENT 'number of parts in use',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `phones_parts`
--


-- --------------------------------------------------------

--
-- Table structure for table `phones_parts_regions_rel`
--

CREATE TABLE IF NOT EXISTS `phones_parts_regions_rel` (
  `part_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  KEY `part_region` (`part_id`,`region_id`),
  KEY `part_id` (`part_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='only active ones are stored';

--
-- Dumping data for table `phones_parts_regions_rel`
--

