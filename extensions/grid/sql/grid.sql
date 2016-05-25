DROP TABLE IF EXISTS `ex_grid`;
CREATE TABLE `ex_grid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  `section` int(11) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `enabled` int(11) DEFAULT '1',
  `background` varchar(255) DEFAULT NULL,
  `margin` varchar(255) DEFAULT NULL,
  `classes` varchar(255) DEFAULT NULL,
  `css` text,
  `content` text,
  `content_over` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ex_grid_by_sections`;
CREATE TABLE `ex_grid_by_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` int(11) DEFAULT NULL,
  `grids` varchar(255) DEFAULT NULL,
  `show_in_subsection` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`section`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
