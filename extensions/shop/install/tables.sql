DROP TABLE IF EXISTS `ex_shop_brands`;
CREATE TABLE `ex_shop_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_brands` VALUES (1,'Brand1','');
INSERT INTO `ex_shop_brands` VALUES (2,'Brand2','');
DROP TABLE IF EXISTS `ex_shop_categories`;
CREATE TABLE `ex_shop_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `description` text,
  `parent` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `enabled` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_categories` VALUES (1,'Uncategory','uncategory','','0','[\"0\"]','',1);
DROP TABLE IF EXISTS `ex_shop_countries`;
CREATE TABLE `ex_shop_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_countries` VALUES (1,'US','United States');
INSERT INTO `ex_shop_countries` VALUES (2,'CA','Canada');
INSERT INTO `ex_shop_countries` VALUES (3,'AF','Afghanistan');
INSERT INTO `ex_shop_countries` VALUES (4,'AL','Albania');
INSERT INTO `ex_shop_countries` VALUES (5,'DZ','Algeria');
INSERT INTO `ex_shop_countries` VALUES (6,'DS','American Samoa');
INSERT INTO `ex_shop_countries` VALUES (7,'AD','Andorra');
INSERT INTO `ex_shop_countries` VALUES (8,'AO','Angola');
INSERT INTO `ex_shop_countries` VALUES (9,'AI','Anguilla');
INSERT INTO `ex_shop_countries` VALUES (10,'AQ','Antarctica');
INSERT INTO `ex_shop_countries` VALUES (11,'AG','Antigua and/or Barbuda');
INSERT INTO `ex_shop_countries` VALUES (12,'AR','Argentina');
INSERT INTO `ex_shop_countries` VALUES (13,'AM','Armenia');
INSERT INTO `ex_shop_countries` VALUES (14,'AW','Aruba');
INSERT INTO `ex_shop_countries` VALUES (15,'AU','Australia');
INSERT INTO `ex_shop_countries` VALUES (16,'AT','Austria');
INSERT INTO `ex_shop_countries` VALUES (17,'AZ','Azerbaijan');
INSERT INTO `ex_shop_countries` VALUES (18,'BS','Bahamas');
INSERT INTO `ex_shop_countries` VALUES (19,'BH','Bahrain');
INSERT INTO `ex_shop_countries` VALUES (20,'BD','Bangladesh');
INSERT INTO `ex_shop_countries` VALUES (21,'BB','Barbados');
INSERT INTO `ex_shop_countries` VALUES (22,'BY','Belarus');
INSERT INTO `ex_shop_countries` VALUES (23,'BE','Belgium');
INSERT INTO `ex_shop_countries` VALUES (24,'BZ','Belize');
INSERT INTO `ex_shop_countries` VALUES (25,'BJ','Benin');
INSERT INTO `ex_shop_countries` VALUES (26,'BM','Bermuda');
INSERT INTO `ex_shop_countries` VALUES (27,'BT','Bhutan');
INSERT INTO `ex_shop_countries` VALUES (28,'BO','Bolivia');
INSERT INTO `ex_shop_countries` VALUES (29,'BA','Bosnia and Herzegovina');
INSERT INTO `ex_shop_countries` VALUES (30,'BW','Botswana');
INSERT INTO `ex_shop_countries` VALUES (31,'BV','Bouvet Island');
INSERT INTO `ex_shop_countries` VALUES (32,'BR','Brazil');
INSERT INTO `ex_shop_countries` VALUES (33,'IO','British lndian Ocean Territory');
INSERT INTO `ex_shop_countries` VALUES (34,'BN','Brunei Darussalam');
INSERT INTO `ex_shop_countries` VALUES (35,'BG','Bulgaria');
INSERT INTO `ex_shop_countries` VALUES (36,'BF','Burkina Faso');
INSERT INTO `ex_shop_countries` VALUES (37,'BI','Burundi');
INSERT INTO `ex_shop_countries` VALUES (38,'KH','Cambodia');
INSERT INTO `ex_shop_countries` VALUES (39,'CM','Cameroon');
INSERT INTO `ex_shop_countries` VALUES (40,'CV','Cape Verde');
INSERT INTO `ex_shop_countries` VALUES (41,'KY','Cayman Islands');
INSERT INTO `ex_shop_countries` VALUES (42,'CF','Central African Republic');
INSERT INTO `ex_shop_countries` VALUES (43,'TD','Chad');
INSERT INTO `ex_shop_countries` VALUES (44,'CL','Chile');
INSERT INTO `ex_shop_countries` VALUES (45,'CN','China');
INSERT INTO `ex_shop_countries` VALUES (46,'CX','Christmas Island');
INSERT INTO `ex_shop_countries` VALUES (47,'CC','Cocos (Keeling) Islands');
INSERT INTO `ex_shop_countries` VALUES (48,'CO','Colombia');
INSERT INTO `ex_shop_countries` VALUES (49,'KM','Comoros');
INSERT INTO `ex_shop_countries` VALUES (50,'CG','Congo');
INSERT INTO `ex_shop_countries` VALUES (51,'CK','Cook Islands');
INSERT INTO `ex_shop_countries` VALUES (52,'CR','Costa Rica');
INSERT INTO `ex_shop_countries` VALUES (53,'HR','Croatia (Hrvatska)');
INSERT INTO `ex_shop_countries` VALUES (54,'CU','Cuba');
INSERT INTO `ex_shop_countries` VALUES (55,'CY','Cyprus');
INSERT INTO `ex_shop_countries` VALUES (56,'CZ','Czech Republic');
INSERT INTO `ex_shop_countries` VALUES (57,'DK','Denmark');
INSERT INTO `ex_shop_countries` VALUES (58,'DJ','Djibouti');
INSERT INTO `ex_shop_countries` VALUES (59,'DM','Dominica');
INSERT INTO `ex_shop_countries` VALUES (60,'DO','Dominican Republic');
INSERT INTO `ex_shop_countries` VALUES (61,'TP','East Timor');
INSERT INTO `ex_shop_countries` VALUES (62,'EC','Ecuador');
INSERT INTO `ex_shop_countries` VALUES (63,'EG','Egypt');
INSERT INTO `ex_shop_countries` VALUES (64,'SV','El Salvador');
INSERT INTO `ex_shop_countries` VALUES (65,'GQ','Equatorial Guinea');
INSERT INTO `ex_shop_countries` VALUES (66,'ER','Eritrea');
INSERT INTO `ex_shop_countries` VALUES (67,'EE','Estonia');
INSERT INTO `ex_shop_countries` VALUES (68,'ET','Ethiopia');
INSERT INTO `ex_shop_countries` VALUES (69,'FK','Falkland Islands (Malvinas)');
INSERT INTO `ex_shop_countries` VALUES (70,'FO','Faroe Islands');
INSERT INTO `ex_shop_countries` VALUES (71,'FJ','Fiji');
INSERT INTO `ex_shop_countries` VALUES (72,'FI','Finland');
INSERT INTO `ex_shop_countries` VALUES (73,'FR','France');
INSERT INTO `ex_shop_countries` VALUES (74,'FX','France, Metropolitan');
INSERT INTO `ex_shop_countries` VALUES (75,'GF','French Guiana');
INSERT INTO `ex_shop_countries` VALUES (76,'PF','French Polynesia');
INSERT INTO `ex_shop_countries` VALUES (77,'TF','French Southern Territories');
INSERT INTO `ex_shop_countries` VALUES (78,'GA','Gabon');
INSERT INTO `ex_shop_countries` VALUES (79,'GM','Gambia');
INSERT INTO `ex_shop_countries` VALUES (80,'GE','Georgia');
INSERT INTO `ex_shop_countries` VALUES (81,'DE','Germany');
INSERT INTO `ex_shop_countries` VALUES (82,'GH','Ghana');
INSERT INTO `ex_shop_countries` VALUES (83,'GI','Gibraltar');
INSERT INTO `ex_shop_countries` VALUES (84,'GR','Greece');
INSERT INTO `ex_shop_countries` VALUES (85,'GL','Greenland');
INSERT INTO `ex_shop_countries` VALUES (86,'GD','Grenada');
INSERT INTO `ex_shop_countries` VALUES (87,'GP','Guadeloupe');
INSERT INTO `ex_shop_countries` VALUES (88,'GU','Guam');
INSERT INTO `ex_shop_countries` VALUES (89,'GT','Guatemala');
INSERT INTO `ex_shop_countries` VALUES (90,'GN','Guinea');
INSERT INTO `ex_shop_countries` VALUES (91,'GW','Guinea-Bissau');
INSERT INTO `ex_shop_countries` VALUES (92,'GY','Guyana');
INSERT INTO `ex_shop_countries` VALUES (93,'HT','Haiti');
INSERT INTO `ex_shop_countries` VALUES (94,'HM','Heard and Mc Donald Islands');
INSERT INTO `ex_shop_countries` VALUES (95,'HN','Honduras');
INSERT INTO `ex_shop_countries` VALUES (96,'HK','Hong Kong');
INSERT INTO `ex_shop_countries` VALUES (97,'HU','Hungary');
INSERT INTO `ex_shop_countries` VALUES (98,'IS','Iceland');
INSERT INTO `ex_shop_countries` VALUES (99,'IN','India');
INSERT INTO `ex_shop_countries` VALUES (100,'ID','Indonesia');
INSERT INTO `ex_shop_countries` VALUES (101,'IR','Iran (Islamic Republic of)');
INSERT INTO `ex_shop_countries` VALUES (102,'IQ','Iraq');
INSERT INTO `ex_shop_countries` VALUES (103,'IE','Ireland');
INSERT INTO `ex_shop_countries` VALUES (104,'IL','Israel');
INSERT INTO `ex_shop_countries` VALUES (105,'IT','Italy');
INSERT INTO `ex_shop_countries` VALUES (106,'CI','Ivory Coast');
INSERT INTO `ex_shop_countries` VALUES (107,'JM','Jamaica');
INSERT INTO `ex_shop_countries` VALUES (108,'JP','Japan');
INSERT INTO `ex_shop_countries` VALUES (109,'JO','Jordan');
INSERT INTO `ex_shop_countries` VALUES (110,'KZ','Kazakhstan');
INSERT INTO `ex_shop_countries` VALUES (111,'KE','Kenya');
INSERT INTO `ex_shop_countries` VALUES (112,'KI','Kiribati');
INSERT INTO `ex_shop_countries` VALUES (113,'KP','Korea, Democratic People\'s Republic of');
INSERT INTO `ex_shop_countries` VALUES (114,'KR','Korea, Republic of');
INSERT INTO `ex_shop_countries` VALUES (115,'KW','Kuwait');
INSERT INTO `ex_shop_countries` VALUES (116,'KG','Kyrgyzstan');
INSERT INTO `ex_shop_countries` VALUES (117,'LA','Lao People\'s Democratic Republic');
INSERT INTO `ex_shop_countries` VALUES (118,'LV','Latvia');
INSERT INTO `ex_shop_countries` VALUES (119,'LB','Lebanon');
INSERT INTO `ex_shop_countries` VALUES (120,'LS','Lesotho');
INSERT INTO `ex_shop_countries` VALUES (121,'LR','Liberia');
INSERT INTO `ex_shop_countries` VALUES (122,'LY','Libyan Arab Jamahiriya');
INSERT INTO `ex_shop_countries` VALUES (123,'LI','Liechtenstein');
INSERT INTO `ex_shop_countries` VALUES (124,'LT','Lithuania');
INSERT INTO `ex_shop_countries` VALUES (125,'LU','Luxembourg');
INSERT INTO `ex_shop_countries` VALUES (126,'MO','Macau');
INSERT INTO `ex_shop_countries` VALUES (127,'MK','Macedonia');
INSERT INTO `ex_shop_countries` VALUES (128,'MG','Madagascar');
INSERT INTO `ex_shop_countries` VALUES (129,'MW','Malawi');
INSERT INTO `ex_shop_countries` VALUES (130,'MY','Malaysia');
INSERT INTO `ex_shop_countries` VALUES (131,'MV','Maldives');
INSERT INTO `ex_shop_countries` VALUES (132,'ML','Mali');
INSERT INTO `ex_shop_countries` VALUES (133,'MT','Malta');
INSERT INTO `ex_shop_countries` VALUES (134,'MH','Marshall Islands');
INSERT INTO `ex_shop_countries` VALUES (135,'MQ','Martinique');
INSERT INTO `ex_shop_countries` VALUES (136,'MR','Mauritania');
INSERT INTO `ex_shop_countries` VALUES (137,'MU','Mauritius');
INSERT INTO `ex_shop_countries` VALUES (138,'TY','Mayotte');
INSERT INTO `ex_shop_countries` VALUES (139,'MX','Mexico');
INSERT INTO `ex_shop_countries` VALUES (140,'FM','Micronesia, Federated States of');
INSERT INTO `ex_shop_countries` VALUES (141,'MD','Moldova, Republic of');
INSERT INTO `ex_shop_countries` VALUES (142,'MC','Monaco');
INSERT INTO `ex_shop_countries` VALUES (143,'MN','Mongolia');
INSERT INTO `ex_shop_countries` VALUES (144,'MS','Montserrat');
INSERT INTO `ex_shop_countries` VALUES (145,'MA','Morocco');
INSERT INTO `ex_shop_countries` VALUES (146,'MZ','Mozambique');
INSERT INTO `ex_shop_countries` VALUES (147,'MM','Myanmar');
INSERT INTO `ex_shop_countries` VALUES (148,'NA','Namibia');
INSERT INTO `ex_shop_countries` VALUES (149,'NR','Nauru');
INSERT INTO `ex_shop_countries` VALUES (150,'NP','Nepal');
INSERT INTO `ex_shop_countries` VALUES (151,'NL','Netherlands');
INSERT INTO `ex_shop_countries` VALUES (152,'AN','Netherlands Antilles');
INSERT INTO `ex_shop_countries` VALUES (153,'NC','New Caledonia');
INSERT INTO `ex_shop_countries` VALUES (154,'NZ','New Zealand');
INSERT INTO `ex_shop_countries` VALUES (155,'NI','Nicaragua');
INSERT INTO `ex_shop_countries` VALUES (156,'NE','Niger');
INSERT INTO `ex_shop_countries` VALUES (157,'NG','Nigeria');
INSERT INTO `ex_shop_countries` VALUES (158,'NU','Niue');
INSERT INTO `ex_shop_countries` VALUES (159,'NF','Norfork Island');
INSERT INTO `ex_shop_countries` VALUES (160,'MP','Northern Mariana Islands');
INSERT INTO `ex_shop_countries` VALUES (161,'NO','Norway');
INSERT INTO `ex_shop_countries` VALUES (162,'OM','Oman');
INSERT INTO `ex_shop_countries` VALUES (163,'PK','Pakistan');
INSERT INTO `ex_shop_countries` VALUES (164,'PW','Palau');
INSERT INTO `ex_shop_countries` VALUES (165,'PA','Panama');
INSERT INTO `ex_shop_countries` VALUES (166,'PG','Papua New Guinea');
INSERT INTO `ex_shop_countries` VALUES (167,'PY','Paraguay');
INSERT INTO `ex_shop_countries` VALUES (168,'PE','Peru');
INSERT INTO `ex_shop_countries` VALUES (169,'PH','Philippines');
INSERT INTO `ex_shop_countries` VALUES (170,'PN','Pitcairn');
INSERT INTO `ex_shop_countries` VALUES (171,'PL','Poland');
INSERT INTO `ex_shop_countries` VALUES (172,'PT','Portugal');
INSERT INTO `ex_shop_countries` VALUES (173,'PR','Puerto Rico');
INSERT INTO `ex_shop_countries` VALUES (174,'QA','Qatar');
INSERT INTO `ex_shop_countries` VALUES (175,'RE','Reunion');
INSERT INTO `ex_shop_countries` VALUES (176,'RO','Romania');
INSERT INTO `ex_shop_countries` VALUES (177,'RU','Russian Federation');
INSERT INTO `ex_shop_countries` VALUES (178,'RW','Rwanda');
INSERT INTO `ex_shop_countries` VALUES (179,'KN','Saint Kitts and Nevis');
INSERT INTO `ex_shop_countries` VALUES (180,'LC','Saint Lucia');
INSERT INTO `ex_shop_countries` VALUES (181,'VC','Saint Vincent and the Grenadines');
INSERT INTO `ex_shop_countries` VALUES (182,'WS','Samoa');
INSERT INTO `ex_shop_countries` VALUES (183,'SM','San Marino');
INSERT INTO `ex_shop_countries` VALUES (184,'ST','Sao Tome and Principe');
INSERT INTO `ex_shop_countries` VALUES (185,'SA','Saudi Arabia');
INSERT INTO `ex_shop_countries` VALUES (186,'SN','Senegal');
INSERT INTO `ex_shop_countries` VALUES (187,'SC','Seychelles');
INSERT INTO `ex_shop_countries` VALUES (188,'SL','Sierra Leone');
INSERT INTO `ex_shop_countries` VALUES (189,'SG','Singapore');
INSERT INTO `ex_shop_countries` VALUES (190,'SK','Slovakia');
INSERT INTO `ex_shop_countries` VALUES (191,'SI','Slovenia');
INSERT INTO `ex_shop_countries` VALUES (192,'SB','Solomon Islands');
INSERT INTO `ex_shop_countries` VALUES (193,'SO','Somalia');
INSERT INTO `ex_shop_countries` VALUES (194,'ZA','South Africa');
INSERT INTO `ex_shop_countries` VALUES (195,'GS','South Georgia South Sandwich Islands');
INSERT INTO `ex_shop_countries` VALUES (196,'ES','Spain');
INSERT INTO `ex_shop_countries` VALUES (197,'LK','Sri Lanka');
INSERT INTO `ex_shop_countries` VALUES (198,'SH','St. Helena');
INSERT INTO `ex_shop_countries` VALUES (199,'PM','St. Pierre and Miquelon');
INSERT INTO `ex_shop_countries` VALUES (200,'SD','Sudan');
INSERT INTO `ex_shop_countries` VALUES (201,'SR','Suriname');
INSERT INTO `ex_shop_countries` VALUES (202,'SJ','Svalbarn and Jan Mayen Islands');
INSERT INTO `ex_shop_countries` VALUES (203,'SZ','Swaziland');
INSERT INTO `ex_shop_countries` VALUES (204,'SE','Sweden');
INSERT INTO `ex_shop_countries` VALUES (205,'CH','Switzerland');
INSERT INTO `ex_shop_countries` VALUES (206,'SY','Syrian Arab Republic');
INSERT INTO `ex_shop_countries` VALUES (207,'TW','Taiwan');
INSERT INTO `ex_shop_countries` VALUES (208,'TJ','Tajikistan');
INSERT INTO `ex_shop_countries` VALUES (209,'TZ','Tanzania, United Republic of');
INSERT INTO `ex_shop_countries` VALUES (210,'TH','Thailand');
INSERT INTO `ex_shop_countries` VALUES (211,'TG','Togo');
INSERT INTO `ex_shop_countries` VALUES (212,'TK','Tokelau');
INSERT INTO `ex_shop_countries` VALUES (213,'TO','Tonga');
INSERT INTO `ex_shop_countries` VALUES (214,'TT','Trinidad and Tobago');
INSERT INTO `ex_shop_countries` VALUES (215,'TN','Tunisia');
INSERT INTO `ex_shop_countries` VALUES (216,'TR','Turkey');
INSERT INTO `ex_shop_countries` VALUES (217,'TM','Turkmenistan');
INSERT INTO `ex_shop_countries` VALUES (218,'TC','Turks and Caicos Islands');
INSERT INTO `ex_shop_countries` VALUES (219,'TV','Tuvalu');
INSERT INTO `ex_shop_countries` VALUES (220,'UG','Uganda');
INSERT INTO `ex_shop_countries` VALUES (221,'UA','Ukraine');
INSERT INTO `ex_shop_countries` VALUES (222,'AE','United Arab Emirates');
INSERT INTO `ex_shop_countries` VALUES (223,'GB','United Kingdom');
INSERT INTO `ex_shop_countries` VALUES (224,'UM','United States minor outlying islands');
INSERT INTO `ex_shop_countries` VALUES (225,'UY','Uruguay');
INSERT INTO `ex_shop_countries` VALUES (226,'UZ','Uzbekistan');
INSERT INTO `ex_shop_countries` VALUES (227,'VU','Vanuatu');
INSERT INTO `ex_shop_countries` VALUES (228,'VA','Vatican City State');
INSERT INTO `ex_shop_countries` VALUES (229,'VE','Venezuela');
INSERT INTO `ex_shop_countries` VALUES (230,'VN','Vietnam');
INSERT INTO `ex_shop_countries` VALUES (231,'VG','Virigan Islands (British)');
INSERT INTO `ex_shop_countries` VALUES (232,'VI','Virgin Islands (U.S.)');
INSERT INTO `ex_shop_countries` VALUES (233,'WF','Wallis and Futuna Islands');
INSERT INTO `ex_shop_countries` VALUES (234,'EH','Western Sahara');
INSERT INTO `ex_shop_countries` VALUES (235,'YE','Yemen');
INSERT INTO `ex_shop_countries` VALUES (236,'YU','Yugoslavia');
INSERT INTO `ex_shop_countries` VALUES (237,'ZR','Zaire');
INSERT INTO `ex_shop_countries` VALUES (238,'ZM','Zambia');
INSERT INTO `ex_shop_countries` VALUES (239,'ZW','Zimbabwe');
DROP TABLE IF EXISTS `ex_shop_features`;
CREATE TABLE `ex_shop_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text,
  `enabled` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_features` VALUES (1,'Clothes size','Size','',1);
INSERT INTO `ex_shop_features` VALUES (2,'Gender','Gender','',1);
DROP TABLE IF EXISTS `ex_shop_features_values`;
CREATE TABLE `ex_shop_features_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  `feature` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_features_values` VALUES (1,'m','M',NULL,0,2);
INSERT INTO `ex_shop_features_values` VALUES (2,'f','F',NULL,0,2);
INSERT INTO `ex_shop_features_values` VALUES (3,'s','S',NULL,1,1);
INSERT INTO `ex_shop_features_values` VALUES (4,'m','M',NULL,2,1);
INSERT INTO `ex_shop_features_values` VALUES (5,'l','L',NULL,3,1);
INSERT INTO `ex_shop_features_values` VALUES (6,'xs','XS',NULL,0,1);
INSERT INTO `ex_shop_features_values` VALUES (7,'xl','XL',NULL,4,1);
DROP TABLE IF EXISTS `ex_shop_form`;
CREATE TABLE `ex_shop_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `message` text,
  `product_data` text,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_form` VALUES (1,'Tufik Chediak','tufik2@hotmail.com','316','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ligula nisl, interdum vel orci vel, malesuada dictum enim. Cras ac eleifend ante, et congue nunc. Ut blandit mauris in nunc consectetur porta. Sed vel velit non arcu interdum rhoncus id sit amet risus. Duis consectetur neque eu quam interdum blandit. Mauris tempus leo eu ipsum sodales imperdiet. Integer tempus urna lectus, non malesuada urna posuere non. Suspendisse erat leo, dignissim et commodo sit amet, tincidunt sed felis. Aliquam erat volutpat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed hendrerit hendrerit metus eu congue. Cras egestas suscipit felis, ut laoreet nulla.','amount: 4\ntax: 2\nprice: 50000.00\ncode: VM7326\nname: Vase\n','2014-01-22');
INSERT INTO `ex_shop_form` VALUES (2,'dds','sds@dssd.com','323233','dssdsd','gender: F\nsize: L\namount: 1\ntax: 1\nprice: 75\ncode: JS382\nname: Shirt\n','2014-02-03');
INSERT INTO `ex_shop_form` VALUES (3,'D','Rudi@gmaol.com','333','Dxx','gender: F\nsize: L\namount: 1\ntax: 1\nprice: 75\ncode: JS382\nname: Shirt\n','2014-02-27');
DROP TABLE IF EXISTS `ex_shop_order_states`;
CREATE TABLE `ex_shop_order_states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_order_states` VALUES (1,'Pending');
INSERT INTO `ex_shop_order_states` VALUES (2,'Canceled');
INSERT INTO `ex_shop_order_states` VALUES (3,'Shipped');
INSERT INTO `ex_shop_order_states` VALUES (4,'Delivered');
INSERT INTO `ex_shop_order_states` VALUES (5,'Awaiting payment');
INSERT INTO `ex_shop_order_states` VALUES (6,'Refund');
DROP TABLE IF EXISTS `ex_shop_orders`;
CREATE TABLE `ex_shop_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `characteristics` text,
  `state` int(11) DEFAULT NULL,
  `date_registered` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ex_shop_product_images`;
CREATE TABLE `ex_shop_product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumbnail` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `principal` int(11) DEFAULT '0',
  `order` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '1',
  `product` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_product_images` VALUES (1,NULL,NULL,1,0,1,1);
INSERT INTO `ex_shop_product_images` VALUES (2,NULL,NULL,0,0,1,1);
INSERT INTO `ex_shop_product_images` VALUES (3,NULL,NULL,0,0,1,1);
INSERT INTO `ex_shop_product_images` VALUES (4,NULL,NULL,1,0,1,2);
INSERT INTO `ex_shop_product_images` VALUES (5,NULL,NULL,0,0,1,2);
INSERT INTO `ex_shop_product_images` VALUES (6,NULL,NULL,1,0,1,3);
INSERT INTO `ex_shop_product_images` VALUES (7,NULL,NULL,0,0,1,3);
INSERT INTO `ex_shop_product_images` VALUES (8,NULL,NULL,0,0,1,3);
DROP TABLE IF EXISTS `ex_shop_product_prices`;
CREATE TABLE `ex_shop_product_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `country` varchar(255) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `enabled` int(11) DEFAULT '1',
  `specific_prices_available_only` int(11) DEFAULT '0',
  `specific_prices` text,
  `product` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_product_prices` VALUES (1,10,2,0,'[\"0\"]','',1,1,'[{\"price\":\"20\",\"stock\":\"2\",\"available\":\"1\",\"default\":\"0\",\"size\":\"4\",\"gender\":\"1\"},{\"price\":\"15\",\"stock\":\"2\",\"available\":\"1\",\"default\":\"1\",\"size\":\"4\",\"gender\":\"2\"},{\"price\":\"30\",\"stock\":\"4\",\"available\":\"1\",\"default\":\"0\",\"size\":\"3\",\"gender\":\"1\"},{\"price\":\"25\",\"stock\":\"3\",\"available\":\"1\",\"default\":\"0\",\"size\":\"3\",\"gender\":\"2\"},{\"price\":\"25\",\"stock\":\"4\",\"available\":\"1\",\"default\":\"0\",\"size\":\"5\",\"gender\":\"1\"}]',1);
INSERT INTO `ex_shop_product_prices` VALUES (2,15.67,1,20,'[\"0\"]','',1,0,'[{\"price\":\"20.44\",\"stock\":\"3\",\"available\":\"1\",\"default\":\"0\",\"gender\":\"1\"},{\"price\":\"14.54\",\"stock\":\"5\",\"available\":\"1\",\"default\":\"0\",\"gender\":\"2\"}]',2);
INSERT INTO `ex_shop_product_prices` VALUES (3,29.99,2,2,'[\"0\"]','',1,0,'',3);
INSERT INTO `ex_shop_product_prices` VALUES (4,25,1,2,'[\"1\"]','',1,0,'',3);
INSERT INTO `ex_shop_product_prices` VALUES (5,20,2,20,'[\"2\",\"1\"]','',1,0,'[{\"price\":\"40\",\"stock\":\"10\",\"available\":\"1\",\"default\":\"0\",\"size\":\"S\",\"gender\":\"M\"},{\"price\":\"30\",\"stock\":\"10\",\"available\":\"1\",\"default\":\"0\",\"size\":\"S\",\"gender\":\"F\"},{\"price\":\"50\",\"stock\":\"20\",\"available\":\"1\",\"default\":\"0\",\"size\":\"L\",\"gender\":\"M\"},{\"price\":\"60\",\"stock\":\"20\",\"available\":\"1\",\"default\":\"0\",\"size\":\"L\",\"gender\":\"F\"}]',1);
DROP TABLE IF EXISTS `ex_shop_products`;
CREATE TABLE `ex_shop_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text,
  `features` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `enabled` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_products` VALUES (1,'Shirt','shirt','JS382','Sed pretium, lectus sit amet ultricies dapibus, justo dui consequat turpis, nec rhoncus velit ipsum vel nulla. Sed sodales, felis vitae ullamcorper dapibus, justo neque consequat nunc, sed euismod tellus metus vitae nunc. Suspendisse nec nisi quis nisl al','[\"1\",\"2\"]','[\"1\"]',1,'',1);
INSERT INTO `ex_shop_products` VALUES (2,'Kappe','kappe','PT864','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras vestibulum laoreet magna vel luctus. Nam ipsum odio, commodo id mauris non, pharetra ornare dui. Integer odio ligula, iaculis ut massa vitae, luctus vestibulum nisl. Lorem ipsum dolor sit amet,','[\"2\"]','[\"1\"]',2,'',1);
INSERT INTO `ex_shop_products` VALUES (3,'Vase','vase','VM7326','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in suscipit neque. Proin elit orci, iaculis quis nunc id, accumsan sodales augue. Duis id nibh orci. Aliquam egestas quam sit amet erat porta, at semper quam eleifend. Morbi consequat ultricies nulla sed lacinia. Ut non fermentum dolor. Ut eu semper odio, non gravida mauris. Sed dictum enim tortor, ac gravida dui pharetra quis. Fusce bibendum eleifend eros at vehicula. Nullam commodo lectus eu metus porta varius. Nullam ac arcu ultricies, dapibus dui vel, dictum elit. Praesent adipiscing pellentesque neque, a aliquet magna scelerisque et. Quisque convallis mattis eros vitae vestibulum. Sed posuere a metus sed dignissim. Vestibulum blandit odio eget nibh mollis, in volutpat enim scelerisque. Curabitur mattis, felis sed tempus luctus, nisl quam tristique nunc, ut lacinia nisl augue ac odio.','[\"0\"]','[\"1\"]',1,'',1);
DROP TABLE IF EXISTS `ex_shop_shipping`;
CREATE TABLE `ex_shop_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_shipping` VALUES (1,'Shipping 10.00',10,'[\"0\"]');
INSERT INTO `ex_shop_shipping` VALUES (2,'No shipping',0,'[\"2\",\"1\"]');
DROP TABLE IF EXISTS `ex_shop_taxes`;
CREATE TABLE `ex_shop_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `tax` decimal(10,2) DEFAULT '0.00',
  `enabled` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `ex_shop_taxes` VALUES (1,'No tax','',0,1);
INSERT INTO `ex_shop_taxes` VALUES (2,'Tax 11%','',11,1);