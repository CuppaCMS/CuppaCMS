CREATE TABLE `ex_tiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `target` varchar(10) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `enabled` int(11) DEFAULT '1',
  `background` varchar(255) DEFAULT NULL,
  `margin` varchar(255) DEFAULT NULL,
  `classes` varchar(255) DEFAULT NULL,
  `css` text,
  `content` text,
  `content_over` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;