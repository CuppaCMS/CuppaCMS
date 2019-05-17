-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.21 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table cuppa.cu_api_keys
CREATE TABLE IF NOT EXISTS `cu_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `limit_access` text,
  `enabled` int(11) DEFAULT '1',
  `ssl` int(11) DEFAULT '1',
  `sql_queries` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_api_keys: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_api_keys` DISABLE KEYS */;
INSERT INTO `cu_api_keys` (`id`, `name`, `key`, `limit_access`, `enabled`, `ssl`, `sql_queries`) VALUES
	(1, 'Default', 'gbmZ48tzyLfx8PqapQB3el8nGFPqQldS', '', 1, 0, 0);
/*!40000 ALTER TABLE `cu_api_keys` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_banners
CREATE TABLE IF NOT EXISTS `cu_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `background` varchar(255) DEFAULT NULL,
  `css` text,
  `content` text,
  `language` varchar(10) NOT NULL DEFAULT '',
  `countries` varchar(500) NOT NULL DEFAULT '',
  `countries_not` varchar(500) NOT NULL DEFAULT '',
  `show_from` date NOT NULL DEFAULT '0000-00-00',
  `show_to` date NOT NULL DEFAULT '0000-00-00',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_banners: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `cu_banners` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_banners_by_sections
CREATE TABLE IF NOT EXISTS `cu_banners_by_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` int(11) DEFAULT NULL,
  `banners` varchar(255) DEFAULT NULL,
  `show_in_subsection` int(11) NOT NULL DEFAULT '0',
  `duration` decimal(10,2) NOT NULL DEFAULT '8.00',
  `classes` varchar(255) DEFAULT NULL,
  `code` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_banners_by_sections: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_banners_by_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `cu_banners_by_sections` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_content
CREATE TABLE IF NOT EXISTS `cu_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `classes` varchar(255) DEFAULT '',
  `css` text,
  `content` text,
  `anchor` varchar(100) DEFAULT NULL,
  `region` varchar(100) NOT NULL DEFAULT '',
  `language` varchar(10) NOT NULL DEFAULT '',
  `countries` varchar(500) NOT NULL DEFAULT '',
  `countries_not` varchar(500) NOT NULL DEFAULT '',
  `show_from` date NOT NULL DEFAULT '0000-00-00',
  `show_to` date NOT NULL DEFAULT '0000-00-00',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_content: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `cu_content` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_content_by_sections
CREATE TABLE IF NOT EXISTS `cu_content_by_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` int(11) DEFAULT NULL,
  `contents` varchar(255) DEFAULT NULL,
  `show_in_subsection` int(11) NOT NULL DEFAULT '0',
  `code` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_content_by_sections: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_content_by_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `cu_content_by_sections` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_countries
CREATE TABLE IF NOT EXISTS `cu_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `default_language` varchar(10) NOT NULL DEFAULT '',
  `available_languages` varchar(255) NOT NULL DEFAULT '',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_countries: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_countries` DISABLE KEYS */;
INSERT INTO `cu_countries` (`id`, `code`, `name`, `default_language`, `available_languages`, `enabled`) VALUES
	(3, 'us', 'Unites States', '', '', 1);
/*!40000 ALTER TABLE `cu_countries` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_language_rich_content
CREATE TABLE IF NOT EXISTS `cu_language_rich_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `content` text,
  `code` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`label`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_language_rich_content: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_language_rich_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `cu_language_rich_content` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_menus
CREATE TABLE IF NOT EXISTS `cu_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`,`language`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_menus: ~3 rows (approximately)
/*!40000 ALTER TABLE `cu_menus` DISABLE KEYS */;
INSERT INTO `cu_menus` (`id`, `name`, `description`, `language`) VALUES
	(1, 'admin_menu', 'Administrator sections/menu', ''),
	(2, 'admin_settings', 'Configuration panel', ''),
	(3, 'web', 'main menu', '');
/*!40000 ALTER TABLE `cu_menus` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_menu_items
CREATE TABLE IF NOT EXISTS `cu_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title_tab` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `view` int(11) DEFAULT '1',
  `description` text,
  `menu_item_type_id` int(11) NOT NULL,
  `menu_item_params` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `menus_id` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `language` varchar(10) DEFAULT '',
  `countries` varchar(255) NOT NULL DEFAULT '',
  `countries_not` varchar(255) NOT NULL DEFAULT '',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `tracking_codes` text,
  `default_page` int(11) NOT NULL DEFAULT '0',
  `error_page` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`alias`,`menus_id`),
  KEY `fk_cu_menu_item_type` (`menu_item_type_id`),
  KEY `fk_cu_menu` (`menus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_menu_items: ~32 rows (approximately)
/*!40000 ALTER TABLE `cu_menu_items` DISABLE KEYS */;
INSERT INTO `cu_menu_items` (`id`, `title`, `alias`, `title_tab`, `image`, `view`, `description`, `menu_item_type_id`, `menu_item_params`, `parent_id`, `menus_id`, `order`, `language`, `countries`, `countries_not`, `enabled`, `tracking_codes`, `default_page`, `error_page`) VALUES
	(1, 'users', 'users', '', '', 1, '', 1, '{}', 0, 1, 3, '', '', '', 1, NULL, 0, 0),
	(2, 'user_groups', 'user-groups', '', NULL, 1, NULL, 2, '{"table_name":"cu_user_groups","defined_task":""}', 1, 1, 2, '', '', '', 1, NULL, 0, 0),
	(19, 'user_accounts', 'user-accounts', '', NULL, 1, NULL, 2, '{"table_name":"cu_users","defined_task":""}', 1, 1, 1, '', '', '', 1, NULL, 0, 0),
	(41, 'profile', 'profile', '', '', 1, '', 2, '{"table_name":"cu_users","defined_task":"&task=edit&id=profile&cancel=false&save=false"}', 0, 1, 1, '', '', '', 1, '', 0, 0),
	(52, 'add_new_user', 'add-new-user', '', '', 1, '', 2, '{"table_name":"cu_users","defined_task":"&task=new"}', 19, 1, 1, '', '', '', 1, '', 0, 0),
	(53, 'add_new_group', 'add-new-group', '', '', 1, '', 2, '{"table_name":"cu_user_groups","defined_task":"&task=new"}', 2, 1, 1, '', '', '', 1, '', 0, 0),
	(82, 'Table manager', 'table-manager', '', 'media/upload_files/menu_database.png', 1, '', 3, '{"component_name":"table_manager"}', 0, 2, 2, '', '', '', 1, NULL, 0, 0),
	(83, 'section_manager', 'section-manager', '', 'media/upload_files/menu_menu.png', 1, '', 1, '{}', 0, 2, 4, '', '', '', 1, '', 0, 0),
	(84, 'section_groups', 'section-groups', '', '', 1, '', 2, '{"table_name":"cu_menus","defined_task":""}', 83, 2, 1, '', '', '', 1, '', 0, 0),
	(85, 'section_items', 'section-items', '', '', 1, '', 3, '{"component_name":"menu"}', 83, 2, 2, '', '', '', 1, '', 0, 0),
	(86, 'section_items_type', 'section-items-type', '', '', 1, '', 2, '{"table_name":"cu_menu_item_type","defined_task":""}', 83, 2, 3, '', '', '', 1, '', 0, 0),
	(87, 'Permissions', 'permissions', '', 'media/upload_files/menu_permissions.png', 1, '', 1, '{}', 0, 2, 5, '', '', '', 1, NULL, 0, 0),
	(88, 'Permissions', 'permission-types', '', '', 1, '', 2, '{"table_name":"cu_permissions","defined_task":""}', 87, 2, 2, '', '', '', 1, NULL, 0, 0),
	(89, 'Groups', 'permission-groups', '', '', 1, '', 2, '{"table_name":"cu_permissions_group","defined_task":""}', 87, 2, 1, '', '', '', 1, NULL, 0, 0),
	(90, 'Language manager', 'language-manager', '', 'media/upload_files/menu_language.png', 1, '', 3, '{"component_name":"language_manager"}', 0, 2, 7, '', '', '', 1, NULL, 0, 0),
	(91, 'File manager', 'file-manager', '', 'media/upload_files/menu_file.png', 1, '', 5, '{"js_function":"stage.loadFileManager()"}', 0, 2, 9, '', '', '', 1, NULL, 0, 0),
	(92, 'Settings', 'settings', '', 'media/upload_files/menu_settings.png', 1, '', 3, '{"component_name":"configuration"}', 0, 2, 10, '', '', '', 1, NULL, 0, 0),
	(94, 'Logout', 'logout', '', 'media/upload_files/menu_logout.png', 1, '', 4, '{"url":"?task=logout","target":"_self"}', 0, 2, 14, '', '', '', 1, NULL, 0, 0),
	(95, 'Permission values', 'permission-values', '', '', 1, '', 2, '{"table_name":"cu_permissions_values","defined_task":""}', 87, 2, 3, '', '', '', 0, NULL, 0, 0),
	(96, 'Views', 'views', '', 'media/upload_files/menu_views.png', 1, '', 2, '{"table_name":"cu_views","defined_task":""}', 0, 2, 6, '', '', '', 1, NULL, 0, 0),
	(97, 'Permission data', 'permission-data', '', '', 1, '', 2, '{"table_name":"cu_permissions_data","defined_task":""}', 87, 2, 4, '', '', '', 1, NULL, 0, 0),
	(116, 'Support', 'support', '', 'media/upload_files/menu_support.png', 1, '', 1, '{}', 0, 2, 12, '', '', '', 1, NULL, 0, 0),
	(121, 'Developer page', 'developer-page', '', '', 1, '', 4, '{"url":"http://www.cuppacms.com/","target":"_blank"}', 116, 2, 1, '', '', '', 1, NULL, 0, 0),
	(130, 'Order by sections', 'order-by-sections-contents', '', '', 1, '', 2, '{"table_name":"cu_content_by_sections","defined_task":""}', 189, 1, 1, '', '', '', 1, '', 0, 0),
	(188, 'Countries', 'countries', '', 'media/upload_files/menu_countries_1454693151.png', 1, '', 2, '{"table_name":"cu_countries","defined_task":""}', 0, 2, 8, '', '', '', 1, '', 0, 0),
	(189, 'Contents', 'contents-2', '', '', 1, '', 2, '{"table_name":"cu_content","defined_task":""}', 199, 1, 3, '', '', '', 1, '', 0, 0),
	(190, 'Banners', 'banners', '', '', 1, '', 2, '{"table_name":"cu_banners","defined_task":""}', 199, 1, 2, '', '', '', 1, '', 0, 0),
	(192, 'Order by sections', 'order-by-sections-banners', '', '', 1, '', 2, '{"table_name":"cu_banners_by_sections","defined_task":""}', 190, 1, 1, '', '', '', 1, '', 0, 0),
	(199, 'Web', 'web', '', '', 1, '', 1, '{}', 0, 1, 13, '', '', '', 1, '', 0, 0),
	(211, 'Home', 'home', '', '', 1, '', 4, '{"url":"","target":"_self"}', 0, 3, 15, '', '', '', 1, '', 1, 0),
	(226, 'Sections', 'sections', '', '', 1, '', 4, '{"url":"component/menu/&menu_filter=3","target":"_self"}', 199, 1, 1, '', '', '', 1, '', 0, 0),
	(253, 'Api keys', 'api-keys', '', 'media/upload_files/menu_api_1481038931.png', 1, '', 2, '{"table_name":"cu_api_keys","defined_task":""}', 0, 2, 11, '', '', '', 1, '', 0, 0);
/*!40000 ALTER TABLE `cu_menu_items` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_menu_item_type
CREATE TABLE IF NOT EXISTS `cu_menu_item_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_menu_item_type: ~7 rows (approximately)
/*!40000 ALTER TABLE `cu_menu_item_type` DISABLE KEYS */;
INSERT INTO `cu_menu_item_type` (`id`, `name`) VALUES
	(6, 'Auto'),
	(2, 'Auto administrate'),
	(5, 'JS function'),
	(1, 'None'),
	(7, 'Other menu item'),
	(3, 'Personalized component'),
	(4, 'URL');
/*!40000 ALTER TABLE `cu_menu_item_type` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_permissions
CREATE TABLE IF NOT EXISTS `cu_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `group` int(11) DEFAULT '1',
  `accept_default_value` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_permissions: ~14 rows (approximately)
/*!40000 ALTER TABLE `cu_permissions` DISABLE KEYS */;
INSERT INTO `cu_permissions` (`id`, `name`, `group`, `accept_default_value`) VALUES
	(1, 'show item menu', 3, 0),
	(2, 'consult', 2, 0),
	(3, 'insert', 2, 0),
	(4, 'edit', 2, 0),
	(5, 'delete', 2, 0),
	(7, 'edit field', 5, 1),
	(8, 'download', 2, 0),
	(10, 'filter state', 4, 1),
	(11, 'list field', 5, 0),
	(12, 'duplicate', 2, 0),
	(13, 'insert', 6, 0),
	(16, 'consult', 6, 0),
	(17, 'edit', 6, 0),
	(18, 'delete', 6, 0);
/*!40000 ALTER TABLE `cu_permissions` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_permissions_data
CREATE TABLE IF NOT EXISTS `cu_permissions_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) DEFAULT NULL COMMENT 'group_permission_id',
  `reference` varchar(100) DEFAULT NULL,
  `data` text,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`reference`,`group`)
) ENGINE=InnoDB AUTO_INCREMENT=1090 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_permissions_data: ~62 rows (approximately)
/*!40000 ALTER TABLE `cu_permissions_data` DISABLE KEYS */;
INSERT INTO `cu_permissions_data` (`id`, `group`, `reference`, `data`, `order`) VALUES
	(21, 3, '93', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(30, 4, 'cu_users,user_group_id', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJ2YWx1ZV81XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0),
	(31, 4, 'cu_users,date_registered', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJ2YWx1ZV81XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0),
	(32, 4, 'cu_users,enabled', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJ2YWx1ZV81XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0),
	(33, 2, 'cu_users', 'eyJ2YWx1ZV8xXzIiOiI1IiwidmFsdWVfMV8zIjoiOCIsInZhbHVlXzFfNCI6IjEwIiwidmFsdWVfMV81IjoiMTMiLCJ2YWx1ZV8xXzgiOiIxNiIsInZhbHVlXzFfMTIiOiIyOCIsInZhbHVlXzJfMiI6IjYiLCJ2YWx1ZV8yXzMiOiI5IiwidmFsdWVfMl80IjoiMTAiLCJ2YWx1ZV8yXzUiOiIxNCIsInZhbHVlXzJfOCI6IjE3IiwidmFsdWVfMl8xMiI6IjI4IiwidmFsdWVfNF8yIjoiNiIsInZhbHVlXzRfMyI6IjkiLCJ2YWx1ZV80XzQiOiIxMSIsInZhbHVlXzRfNSI6IjE0IiwidmFsdWVfNF84IjoiMTciLCJ2YWx1ZV80XzEyIjoiMjgiLCJkZWZhdWx0XzFfMiI6IiIsImRlZmF1bHRfMV8zIjoiIiwiZGVmYXVsdF8xXzQiOiIiLCJkZWZhdWx0XzFfNSI6IiIsImRlZmF1bHRfMV84IjoiIiwiZGVmYXVsdF8xXzEyIjoiIiwiZGVmYXVsdF8yXzIiOiIiLCJkZWZhdWx0XzJfMyI6IiIsImRlZmF1bHRfMl80IjoiIiwiZGVmYXVsdF8yXzUiOiIiLCJkZWZhdWx0XzJfOCI6IiIsImRlZmF1bHRfMl8xMiI6IiIsImRlZmF1bHRfNF8yIjoiIiwiZGVmYXVsdF80XzMiOiIiLCJkZWZhdWx0XzRfNCI6IiIsImRlZmF1bHRfNF81IjoiIiwiZGVmYXVsdF80XzgiOiIiLCJkZWZhdWx0XzRfMTIiOiIifQ==', 0),
	(50, 3, '41', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiMyIsInZhbHVlXzNfMSI6IjMiLCJ2YWx1ZV80XzEiOiIzIiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(51, 5, 'cu_users,user_group_id', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjIwIiwidmFsdWVfMl8xMSI6IjIyIiwidmFsdWVfM183IjoiMjAiLCJ2YWx1ZV8zXzExIjoiMjIiLCJ2YWx1ZV80XzciOiIyMCIsInZhbHVlXzRfMTEiOiIyMiIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(60, 5, 'cu_users,enabled', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjIwIiwidmFsdWVfMl8xMSI6IjIyIiwidmFsdWVfM183IjoiMjAiLCJ2YWx1ZV8zXzExIjoiMjIiLCJ2YWx1ZV80XzciOiIyMCIsInZhbHVlXzRfMTEiOiIyMiIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(67, 5, 'cu_users,date_registered', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjIzIiwidmFsdWVfMl8xMSI6IjIyIiwidmFsdWVfM183IjoiMjAiLCJ2YWx1ZV8zXzExIjoiMjIiLCJ2YWx1ZV80XzciOiIyMCIsInZhbHVlXzRfMTEiOiIyMiIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(77, 5, 'cu_users,image', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjE5IiwidmFsdWVfMl8xMSI6IjIxIiwidmFsdWVfNF83IjoiMTkiLCJ2YWx1ZV80XzExIjoiMjEiLCJkZWZhdWx0XzFfNyI6IidtZWRpYS91c2VyX2ltYWdlcy9kZWZhdWx0LmpwZyciLCJkZWZhdWx0XzFfMTEiOiIiLCJkZWZhdWx0XzJfNyI6IidtZWRpYS91c2VyX2ltYWdlcy9kZWZhdWx0LmpwZyciLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzRfNyI6IidtZWRpYS91c2VyX2ltYWdlcy9kZWZhdWx0LmpwZyciLCJkZWZhdWx0XzRfMTEiOiIifQ==', 0),
	(225, 4, 'cu_permissions,group', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(226, 4, 'cu_permissions,accept_default_value', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(227, 4, 'cu_permissions_values,permission', 'eyJ2YWx1ZV8xXzEwIjoiMjQiLCJ2YWx1ZV8yXzEwIjoiMjQiLCJ2YWx1ZV8zXzEwIjoiMjQiLCJ2YWx1ZV80XzEwIjoiMjQiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzNfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(245, 4, 'cu_permissions_data,group', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(322, 5, 'cu_permissions_values,permission', 'eyJ2YWx1ZV8xXzciOiIyMyIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjIzIiwidmFsdWVfMl8xMSI6IjIxIiwidmFsdWVfM183IjoiMTkiLCJ2YWx1ZV8zXzExIjoiMjEiLCJ2YWx1ZV80XzciOiIxOSIsInZhbHVlXzRfMTEiOiIyMSIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(426, 5, 'cu_permissions,group', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjE5IiwidmFsdWVfMl8xMSI6IjIxIiwidmFsdWVfM183IjoiMTkiLCJ2YWx1ZV8zXzExIjoiMjEiLCJ2YWx1ZV80XzciOiIxOSIsInZhbHVlXzRfMTEiOiIyMSIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(452, 4, 'cu_language_rich_content,language', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(473, 2, 'cu_menu_items', 'eyJ2YWx1ZV8xXzIiOiI1IiwidmFsdWVfMV8zIjoiOCIsInZhbHVlXzFfNCI6IjEwIiwidmFsdWVfMV81IjoiMTMiLCJ2YWx1ZV8xXzgiOiIxNiIsInZhbHVlXzJfMiI6IjUiLCJ2YWx1ZV8yXzMiOiI4IiwidmFsdWVfMl80IjoiMTAiLCJ2YWx1ZV8yXzUiOiIxMyIsInZhbHVlXzJfOCI6IjE2IiwidmFsdWVfM18yIjoiNSIsInZhbHVlXzNfMyI6IjgiLCJ2YWx1ZV8zXzQiOiIxMCIsInZhbHVlXzNfNSI6IjEzIiwidmFsdWVfM184IjoiMTYiLCJ2YWx1ZV80XzIiOiI1IiwidmFsdWVfNF8zIjoiOCIsInZhbHVlXzRfNCI6IjEwIiwidmFsdWVfNF81IjoiMTMiLCJ2YWx1ZV80XzgiOiIxNiIsImRlZmF1bHRfMV8yIjoiIiwiZGVmYXVsdF8xXzMiOiIiLCJkZWZhdWx0XzFfNCI6IiIsImRlZmF1bHRfMV81IjoiIiwiZGVmYXVsdF8xXzgiOiIiLCJkZWZhdWx0XzJfMiI6IiIsImRlZmF1bHRfMl8zIjoiIiwiZGVmYXVsdF8yXzQiOiIiLCJkZWZhdWx0XzJfNSI6IiIsImRlZmF1bHRfMl84IjoiIiwiZGVmYXVsdF8zXzIiOiIiLCJkZWZhdWx0XzNfMyI6IiIsImRlZmF1bHRfM180IjoiIiwiZGVmYXVsdF8zXzUiOiIiLCJkZWZhdWx0XzNfOCI6IiIsImRlZmF1bHRfNF8yIjoiIiwiZGVmYXVsdF80XzMiOiIiLCJkZWZhdWx0XzRfNCI6IiIsImRlZmF1bHRfNF81IjoiIiwiZGVmYXVsdF80XzgiOiIifQ==', 0),
	(481, 2, 'cu_language_rich_content', 'eyJ2YWx1ZV8xXzIiOiI1IiwidmFsdWVfMV8zIjoiOCIsInZhbHVlXzFfNCI6IjEwIiwidmFsdWVfMV81IjoiMTMiLCJ2YWx1ZV8xXzgiOiIxNiIsInZhbHVlXzJfMiI6IjUiLCJ2YWx1ZV8yXzMiOiI4IiwidmFsdWVfMl80IjoiMTAiLCJ2YWx1ZV8yXzUiOiIxMyIsInZhbHVlXzJfOCI6IjE2IiwidmFsdWVfM18yIjoiNSIsInZhbHVlXzNfMyI6IjgiLCJ2YWx1ZV8zXzQiOiIxMCIsInZhbHVlXzNfNSI6IjEzIiwidmFsdWVfM184IjoiMTYiLCJ2YWx1ZV80XzIiOiI1IiwidmFsdWVfNF8zIjoiOCIsInZhbHVlXzRfNCI6IjEwIiwidmFsdWVfNF81IjoiMTMiLCJ2YWx1ZV80XzgiOiIxNiIsImRlZmF1bHRfMV8yIjoiIiwiZGVmYXVsdF8xXzMiOiIiLCJkZWZhdWx0XzFfNCI6IiIsImRlZmF1bHRfMV81IjoiIiwiZGVmYXVsdF8xXzgiOiIiLCJkZWZhdWx0XzJfMiI6IiIsImRlZmF1bHRfMl8zIjoiIiwiZGVmYXVsdF8yXzQiOiIiLCJkZWZhdWx0XzJfNSI6IiIsImRlZmF1bHRfMl84IjoiIiwiZGVmYXVsdF8zXzIiOiIiLCJkZWZhdWx0XzNfMyI6IiIsImRlZmF1bHRfM180IjoiIiwiZGVmYXVsdF8zXzUiOiIiLCJkZWZhdWx0XzNfOCI6IiIsImRlZmF1bHRfNF8yIjoiIiwiZGVmYXVsdF80XzMiOiIiLCJkZWZhdWx0XzRfNCI6IiIsImRlZmF1bHRfNF81IjoiIiwiZGVmYXVsdF80XzgiOiIifQ==', 0),
	(527, 4, 'cu_permissions', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(529, 4, 'cu_permissions_group', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(530, 4, 'cu_permissions_values', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(642, 4, 'cu_menus,language', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(655, 5, 'cu_users,name', 'eyJ2YWx1ZV8xXzciOiIxOSIsInZhbHVlXzFfMTEiOiIyMSIsInZhbHVlXzJfNyI6IjE5IiwidmFsdWVfMl8xMSI6IjIxIiwidmFsdWVfM183IjoiMTkiLCJ2YWx1ZV8zXzExIjoiMjEiLCJ2YWx1ZV80XzciOiIxOSIsInZhbHVlXzRfMTEiOiIyMSIsImRlZmF1bHRfMV83IjoiIiwiZGVmYXVsdF8xXzExIjoiIiwiZGVmYXVsdF8yXzciOiIiLCJkZWZhdWx0XzJfMTEiOiIiLCJkZWZhdWx0XzNfNyI6IiIsImRlZmF1bHRfM18xMSI6IiIsImRlZmF1bHRfNF83IjoiIiwiZGVmYXVsdF80XzExIjoiIn0=', 0),
	(669, 3, '79', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiMyIsInZhbHVlXzNfMSI6IjMiLCJ2YWx1ZV80XzEiOiIzIiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(670, 3, '91', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(671, 3, '82', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(675, 3, '83', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(679, 3, '87', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(685, 3, '96', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(689, 3, '90', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(698, 3, '92', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(703, 3, '119', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiMyIsInZhbHVlXzNfMSI6IjMiLCJ2YWx1ZV80XzEiOiIzIiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(705, 3, '116', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzNfMSI6IjQiLCJ2YWx1ZV80XzEiOiI0IiwiZGVmYXVsdF8xXzEiOiIiLCJkZWZhdWx0XzJfMSI6IiIsImRlZmF1bHRfM18xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(781, 3, '1', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzRfMSI6IjQiLCJkZWZhdWx0XzFfMSI6IiIsImRlZmF1bHRfMl8xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(795, 4, 'ex_content_by_section,sections', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(796, 4, 'ex_content_by_section,contents', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(803, 4, 'ex_content_by_sections,section', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(804, 4, 'ex_content_by_sections,contents', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(827, 2, 'ex_content_by_sections', 'eyJ2YWx1ZV8xXzIiOiI1IiwidmFsdWVfMV8zIjoiOCIsInZhbHVlXzFfNCI6IjEwIiwidmFsdWVfMV81IjoiMTMiLCJ2YWx1ZV8xXzgiOiIxNiIsInZhbHVlXzFfMTIiOiIyOCIsInZhbHVlXzJfMiI6IjUiLCJ2YWx1ZV8yXzMiOiI4IiwidmFsdWVfMl80IjoiMTAiLCJ2YWx1ZV8yXzUiOiIxMyIsInZhbHVlXzJfOCI6IjE2IiwidmFsdWVfMl8xMiI6IjI4IiwidmFsdWVfM18yIjoiNSIsInZhbHVlXzNfMyI6IjgiLCJ2YWx1ZV8zXzQiOiIxMCIsInZhbHVlXzNfNSI6IjEzIiwidmFsdWVfM184IjoiMTYiLCJ2YWx1ZV8zXzEyIjoiMjgiLCJ2YWx1ZV80XzIiOiI1IiwidmFsdWVfNF8zIjoiOCIsInZhbHVlXzRfNCI6IjEwIiwidmFsdWVfNF81IjoiMTMiLCJ2YWx1ZV80XzgiOiIxNiIsInZhbHVlXzRfMTIiOiIyOCIsImRlZmF1bHRfMV8yIjoiIiwiZGVmYXVsdF8xXzMiOiIiLCJkZWZhdWx0XzFfNCI6IiIsImRlZmF1bHRfMV81IjoiIiwiZGVmYXVsdF8xXzgiOiIiLCJkZWZhdWx0XzFfMTIiOiIiLCJkZWZhdWx0XzJfMiI6IiIsImRlZmF1bHRfMl8zIjoiIiwiZGVmYXVsdF8yXzQiOiIiLCJkZWZhdWx0XzJfNSI6IiIsImRlZmF1bHRfMl84IjoiIiwiZGVmYXVsdF8yXzEyIjoiIiwiZGVmYXVsdF8zXzIiOiIiLCJkZWZhdWx0XzNfMyI6IiIsImRlZmF1bHRfM180IjoiIiwiZGVmYXVsdF8zXzUiOiIiLCJkZWZhdWx0XzNfOCI6IiIsImRlZmF1bHRfM18xMiI6IiIsImRlZmF1bHRfNF8yIjoiIiwiZGVmYXVsdF80XzMiOiIiLCJkZWZhdWx0XzRfNCI6IiIsImRlZmF1bHRfNF81IjoiIiwiZGVmYXVsdF80XzgiOiIiLCJkZWZhdWx0XzRfMTIiOiIifQ==', 0),
	(939, 4, 'cu_user_groups,admin_login', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(940, 4, 'cu_user_groups,enabled', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(941, 4, 'cu_user_groups,site_login', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfM18xMCI6IjEiLCJ2YWx1ZV80XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfM18xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiJ9', 0),
	(944, 4, 'ex_banners_by_sections,section', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(945, 4, 'ex_banners_by_sections,banners', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(946, 4, 'ex_banners_by_sections,show_in_subsection', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(949, 4, 'ex_content_by_sections,show_in_subsection', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(954, 3, '188', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiNCIsInZhbHVlXzRfMSI6IjQiLCJkZWZhdWx0XzFfMSI6IiIsImRlZmF1bHRfMl8xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(1028, 4, 'ex_banners,language', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1029, 4, 'ex_banners,countries', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1030, 4, 'ex_banners,countries_not', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1031, 4, 'ex_banners,enabled', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1032, 4, 'ex_content,language', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1033, 4, 'ex_content,countries', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1034, 4, 'ex_content,countries_not', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1035, 4, 'ex_content,enabled', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJkZWZhdWx0XzFfMTAiOiIiLCJkZWZhdWx0XzJfMTAiOiIiLCJkZWZhdWx0XzRfMTAiOiIifQ==', 0),
	(1036, 3, '200', 'eyJ2YWx1ZV8xXzEiOiIzIiwidmFsdWVfMl8xIjoiMyIsInZhbHVlXzRfMSI6IjMiLCJkZWZhdWx0XzFfMSI6IiIsImRlZmF1bHRfMl8xIjoiIiwiZGVmYXVsdF80XzEiOiIifQ==', 0),
	(1040, 5, 'cu_users,restore_password', 'eyJ2YWx1ZV8xXzciOiIyMCIsInZhbHVlXzFfMTEiOiIyMiIsInZhbHVlXzJfNyI6IjIwIiwidmFsdWVfMl8xMSI6IjIyIiwidmFsdWVfM183IjoiMjAiLCJ2YWx1ZV8zXzExIjoiMjIiLCJkZWZhdWx0XzFfNyI6IiIsImRlZmF1bHRfMV8xMSI6IiIsImRlZmF1bHRfMl83IjoiIiwiZGVmYXVsdF8yXzExIjoiIiwiZGVmYXVsdF8zXzciOiIiLCJkZWZhdWx0XzNfMTEiOiIifQ==', 0),
	(1083, 4, 'cu_api_keys,enabled', 'eyJ2YWx1ZV8xXzEwIjoiMSIsInZhbHVlXzJfMTAiOiIxIiwidmFsdWVfNF8xMCI6IjEiLCJ2YWx1ZV81XzEwIjoiMSIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0),
	(1084, 2, 'cu_api_keys', 'eyJ2YWx1ZV8xXzIiOiI1IiwidmFsdWVfMV8zIjoiOCIsInZhbHVlXzFfNCI6IjEwIiwidmFsdWVfMV81IjoiMTMiLCJ2YWx1ZV8xXzgiOiIxNiIsInZhbHVlXzFfMTIiOiIyOCIsInZhbHVlXzJfMiI6IjUiLCJ2YWx1ZV8yXzMiOiI4IiwidmFsdWVfMl80IjoiMTAiLCJ2YWx1ZV8yXzUiOiIxMyIsInZhbHVlXzJfOCI6IjE2IiwidmFsdWVfMl8xMiI6IjI4IiwidmFsdWVfNF8yIjoiNSIsInZhbHVlXzRfMyI6IjgiLCJ2YWx1ZV80XzQiOiIxMCIsInZhbHVlXzRfNSI6IjEzIiwidmFsdWVfNF84IjoiMTYiLCJ2YWx1ZV80XzEyIjoiMjgiLCJ2YWx1ZV81XzIiOiI1IiwidmFsdWVfNV8zIjoiOCIsInZhbHVlXzVfNCI6IjEwIiwidmFsdWVfNV81IjoiMTMiLCJ2YWx1ZV81XzgiOiIxNiIsInZhbHVlXzVfMTIiOiIyOCIsImRlZmF1bHRfMV8yIjoiIiwiZGVmYXVsdF8xXzMiOiIiLCJkZWZhdWx0XzFfNCI6IiIsImRlZmF1bHRfMV81IjoiIiwiZGVmYXVsdF8xXzgiOiIiLCJkZWZhdWx0XzFfMTIiOiIiLCJkZWZhdWx0XzJfMiI6IiIsImRlZmF1bHRfMl8zIjoiIiwiZGVmYXVsdF8yXzQiOiIiLCJkZWZhdWx0XzJfNSI6IiIsImRlZmF1bHRfMl84IjoiIiwiZGVmYXVsdF8yXzEyIjoiIiwiZGVmYXVsdF80XzIiOiIiLCJkZWZhdWx0XzRfMyI6IiIsImRlZmF1bHRfNF80IjoiIiwiZGVmYXVsdF80XzUiOiIiLCJkZWZhdWx0XzRfOCI6IiIsImRlZmF1bHRfNF8xMiI6IiIsImRlZmF1bHRfNV8yIjoiIiwiZGVmYXVsdF81XzMiOiIiLCJkZWZhdWx0XzVfNCI6IiIsImRlZmF1bHRfNV81IjoiIiwiZGVmYXVsdF81XzgiOiIiLCJkZWZhdWx0XzVfMTIiOiIifQ==', 0),
	(1086, 4, 'cu_api_keys,sql_queries', 'eyJ2YWx1ZV8xXzEwIjoiMiIsInZhbHVlXzJfMTAiOiIyIiwidmFsdWVfNF8xMCI6IjIiLCJ2YWx1ZV81XzEwIjoiMiIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0),
	(1089, 4, 'cu_api_keys,ssl', 'eyJ2YWx1ZV8xXzEwIjoiMiIsInZhbHVlXzJfMTAiOiIyIiwidmFsdWVfNF8xMCI6IjIiLCJ2YWx1ZV81XzEwIjoiMiIsImRlZmF1bHRfMV8xMCI6IiIsImRlZmF1bHRfMl8xMCI6IiIsImRlZmF1bHRfNF8xMCI6IiIsImRlZmF1bHRfNV8xMCI6IiJ9', 0);
/*!40000 ALTER TABLE `cu_permissions_data` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_permissions_group
CREATE TABLE IF NOT EXISTS `cu_permissions_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_permissions_group: ~6 rows (approximately)
/*!40000 ALTER TABLE `cu_permissions_group` DISABLE KEYS */;
INSERT INTO `cu_permissions_group` (`id`, `name`) VALUES
	(1, 'ungroup'),
	(2, 'table'),
	(3, 'menu'),
	(4, 'filters'),
	(5, 'table field'),
	(6, 'api key');
/*!40000 ALTER TABLE `cu_permissions_group` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_permissions_values
CREATE TABLE IF NOT EXISTS `cu_permissions_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) DEFAULT NULL,
  `permission` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_permissions_values: ~36 rows (approximately)
/*!40000 ALTER TABLE `cu_permissions_values` DISABLE KEYS */;
INSERT INTO `cu_permissions_values` (`id`, `value`, `permission`) VALUES
	(1, 'enable', '10'),
	(2, 'disable', '10'),
	(3, 'true', '1'),
	(4, 'false', '1'),
	(5, 'true', '2'),
	(6, 'false', '2'),
	(7, 'only own info', '2'),
	(8, 'true', '3'),
	(9, 'false', '3'),
	(10, 'true', '4'),
	(11, 'false', '4'),
	(12, 'only own info', '4'),
	(13, 'true', '5'),
	(14, 'false', '5'),
	(15, 'only own info', '5'),
	(16, 'true', '8'),
	(17, 'false', '8'),
	(18, 'only own info', '8'),
	(19, 'true', '7'),
	(20, 'false', '7'),
	(21, 'true', '11'),
	(22, 'false', '11'),
	(23, 'blocked', '7'),
	(24, 'hidden', '10'),
	(25, 'blocked', '10'),
	(26, 'hidden', '7'),
	(27, 'true', '12'),
	(28, 'false', '12'),
	(29, 'true', '13'),
	(30, 'false', '13'),
	(31, 'true', '16'),
	(32, 'false', '16'),
	(33, 'true', '17'),
	(34, 'false', '17'),
	(35, 'true', '18'),
	(36, 'false', '18');
/*!40000 ALTER TABLE `cu_permissions_values` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_tables
CREATE TABLE IF NOT EXISTS `cu_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(45) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_tables: ~16 rows (approximately)
/*!40000 ALTER TABLE `cu_tables` DISABLE KEYS */;
INSERT INTO `cu_tables` (`id`, `table_name`, `params`) VALUES
	(20, 'cu_permissions', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": "",\n            "default": ""\n        }\n    },\n    "group": {\n        "type": "Select",\n        "label": "Group",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_permissions_group",\n                "data_column": "id",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": ""\n            },\n            "extraParams": {\n                "language_reference": false,\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "width": ""\n            },\n            "tooltip": "",\n            "tooltip_lang_reference": false\n        }\n    },\n    "accept_default_value": {\n        "type": "Select",\n        "label": "Accept default value",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "0",\n                    "false"\n                ],\n                [\n                    "1",\n                    "true"\n                ]\n            ],\n            "extraParams": {\n                "language_reference": false,\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "width": ""\n            },\n            "tooltip": "",\n            "tooltip_lang_reference": false\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Permissions",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": [\n        {\n            "image_src": "media/upload_files/default.jpg",\n            "url": "component/table_manager/view/cu_permissions_values/",\n            "var_name": "permission=id&filter_permission=id",\n            "tooltip": "Add value"\n        }\n    ],\n    "include_file": "",\n    "tabs": ""\n}'),
	(21, 'cu_menus', '{\n    "id": {\n        "type": "Id",\n        "label": "id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "description": {\n        "type": "Text",\n        "label": "description",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "language": {\n        "type": "Language_Selector",\n        "label": "Language",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Sections / Menus",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": [\n        {\n            "image_src": "media/upload_files/default.jpg",\n            "url": "component/menu/",\n            "var_name": "menu_filter=id",\n            "tooltip": "edit_section_items"\n        }\n    ],\n    "include_file": "",\n    "tabs": ""\n}'),
	(22, 'cu_menu_item_type', '{\n    "id": {\n        "type": "Id",\n        "label": "id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Menu item types",\n    "custom_table_name_language_reference": 0,\n    "order_by": null,\n    "order_by_order": null,\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(25, 'cu_user_groups', '{\n    "id": {\n        "type": "Id",\n        "label": "id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "admin_login": {\n        "type": "Select",\n        "label": "admin_login",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": "0",\n                "custom_data": "",\n                "custom_label": "",\n                "language_reference": "1"\n            }\n        }\n    },\n    "site_login": {\n        "type": "Select",\n        "label": "site_login",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": "0",\n                "custom_data": "",\n                "custom_label": "",\n                "language_reference": "1"\n            }\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": "0",\n                "custom_data": "",\n                "custom_label": "",\n                "language_reference": "1"\n            }\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "User groups",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(26, 'cu_users', '{\n    "id": {\n        "type": "Id",\n        "label": "id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "lastname": {\n        "type": "Text",\n        "label": "Lastname",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "email": {\n        "type": "Text",\n        "label": "email",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "email"\n        }\n    },\n    "phone": {\n        "type": "Text",\n        "label": "Phone",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "username": {\n        "type": "Text",\n        "label": "username",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text"\n        }\n    },\n    "password": {\n        "type": "Text",\n        "label": "password",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "password",\n            "encode": "global_encode",\n            "maxlength": ""\n        }\n    },\n    "image": {\n        "type": "File",\n        "label": "Image",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "folder": "user_images",\n            "unique_name": 1,\n            "show_image": 1,\n            "dimention_priority": "height",\n            "dimention_image": "50",\n            "download_enabled": 1,\n            "tooltip": "",\n            "width": "",\n            "resize": false,\n            "max_width": "",\n            "max_height": "",\n            "crop": false\n        }\n    },\n    "user_group_id": {\n        "type": "Select",\n        "label": "user_group_id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_user_groups",\n                "data_column": "id",\n                "label_column": "name"\n            },\n            "extraParams": {\n                "add_custom_item": "0",\n                "custom_data": "",\n                "custom_label": "",\n                "language_reference": "0"\n            }\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": "0",\n                "custom_data": "",\n                "custom_label": "",\n                "language_reference": "1"\n            }\n        }\n    },\n    "date_registered": {\n        "type": "Date",\n        "label": "Date registered",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "datePicker",\n            "config": "auto_today_selected",\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": "",\n            "disable": false\n        }\n    },\n    "restore_password": {\n        "type": "Select",\n        "label": "Restore password",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "0",\n                    "false"\n                ],\n                [\n                    "1",\n                    "true"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Users",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(32, 'cu_permissions_group', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Permission groups",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": [\n        {\n            "image_src": "media/upload_files/default.jpg",\n            "url": "component/table_manager/view/cu_permissions/",\n            "var_name": "filter_group=id&group=id",\n            "tooltip": "Add permission"\n        }\n    ],\n    "include_file": "",\n    "tabs": ""\n}'),
	(33, 'cu_permissions_values', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "value": {\n        "type": "Text",\n        "label": "Value",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": ""\n        }\n    },\n    "permission": {\n        "type": "Select",\n        "label": "Permission",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_permissions",\n                "data_column": "id",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Permission values",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "value",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(34, 'cu_views', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": ""\n        }\n    },\n    "description": {\n        "type": "TextArea",\n        "label": "Description",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "none",\n            "width": "400px",\n            "height": "100px",\n            "styles_css": "",\n            "template_list": ""\n        }\n    },\n    "screen": {\n        "type": "File",\n        "label": "Screen",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "folder": "media/upload_files",\n            "unique_name": 0,\n            "show_image": 1,\n            "dimention_priority": "height",\n            "dimention_image": "100",\n            "download_enabled": 1,\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": ""\n        }\n    },\n    "file_path": {\n        "type": "Text",\n        "label": "File path",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "Path to the view",\n            "width": "100%",\n            "default": ""\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": "Html code to the view."\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Views",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwibmFtZSIsImRlc2NyaXB0aW9uIiwic2NyZWVuIl19LHsidGFiX25hbWUiOiJDb2RlIiwidGFiX2ZpZWxkcyI6WyJmaWxlX3BhdGgiLCJjb2RlIl19XSwiZmllbGRzX3RhYiI6eyJpZCI6IkdlbmVyYWwiLCJuYW1lIjoiR2VuZXJhbCIsImRlc2NyaXB0aW9uIjoiR2VuZXJhbCIsInNjcmVlbiI6IkdlbmVyYWwiLCJ1bmRlZmluZWQiOiJDb2RlIiwiZmlsZV9wYXRoIjoiQ29kZSIsImNvZGUiOiJDb2RlIn19"\n}'),
	(35, 'cu_permissions_data', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "group": {\n        "type": "Select",\n        "label": "Group",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_permissions_group",\n                "data_column": "id",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": ""\n            },\n            "extraParams": {\n                "language_reference": false,\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "width": ""\n            },\n            "tooltip": "",\n            "tooltip_lang_reference": false\n        }\n    },\n    "reference": {\n        "type": "Text",\n        "label": "Reference",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "disable": false,\n            "tooltip": "",\n            "tooltip_lang_reference": false,\n            "width": "",\n            "default": ""\n        }\n    },\n    "data": {\n        "type": "TextArea",\n        "label": "Data",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "jsoneditor",\n            "width": "821px",\n            "height": "217px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "media/upload_files",\n            "base_64_encode": true\n        }\n    },\n    "order": {\n        "type": "Text",\n        "label": "Order",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "number",\n            "number_fortmat": "normal",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Permission data",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "reference",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(36, 'cu_language_rich_content', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "label": {\n        "type": "Text",\n        "label": "Label",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "language": {\n        "type": "Language_Selector",\n        "label": "Language",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0\n    },\n    "content": {\n        "type": "TextArea",\n        "label": "Content",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "tinymce",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "../css/styles.css",\n            "template_list": "js/tiny_mce/templates/templates.js",\n            "folder": "upload_files",\n            "base_64_encode": false,\n            "editor_mode": "html",\n            "maxlength": "",\n            "tooltip": ""\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "600px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Rich content",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "label",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwibGFiZWwiLCJsYW5ndWFnZSIsImNvbnRlbnQiXX0seyJ0YWJfbmFtZSI6IkNvZGUiLCJ0YWJfZmllbGRzIjpbImNvZGUiXX1dLCJmaWVsZHNfdGFiIjp7ImlkIjoiR2VuZXJhbCIsImxhYmVsIjoiR2VuZXJhbCIsImxhbmd1YWdlIjoiR2VuZXJhbCIsImNvbnRlbnQiOiJHZW5lcmFsIiwidW5kZWZpbmVkIjoiQ29kZSIsImNvZGUiOiJDb2RlIn19"\n}'),
	(37, 'cu_countries', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "code": {\n        "type": "Text",\n        "label": "Code",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "default_language": {\n        "type": "Language_Selector",\n        "label": "Default language",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "multiSelect": false,\n            "height": "",\n            "width": "",\n            "tooltip": ""\n        }\n    },\n    "available_languages": {\n        "type": "Language_Selector",\n        "label": "Available languages",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "multiSelect": true,\n            "height": "5",\n            "width": "",\n            "tooltip": ""\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "Enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Countries",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": ""\n}'),
	(38, 'cu_content', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "classes": {\n        "type": "Text",\n        "label": "Classes",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "tags",\n            "maxlength": "",\n            "tooltip": "Container classes",\n            "width": "100%",\n            "default": "\'class_\'.time().mt_rand(1,999).,.\'inner_\'.time().mt_rand(1,999)"\n        }\n    },\n    "css": {\n        "type": "TextArea",\n        "label": "Css",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "90px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "upload_files",\n            "base_64_encode": false,\n            "editor_mode": "css",\n            "maxlength": "",\n            "tooltip": "container_css_styles"\n        }\n    },\n    "content": {\n        "type": "TextArea",\n        "label": "Content",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "tinymce",\n            "width": "100%",\n            "height": "550px",\n            "styles_css": "../css/styles.css",\n            "template_list": "js/tiny_mce/templates/templates.js",\n            "folder": "content",\n            "base_64_encode": false,\n            "editor_mode": "html",\n            "maxlength": "",\n            "tooltip": ""\n        }\n    },\n    "anchor": {\n        "type": "Text",\n        "label": "Anchor",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "region": {\n        "type": "Text",\n        "label": "Region",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "language": {\n        "type": "Language_Selector",\n        "label": "Language",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "multiSelect": false,\n            "height": "1",\n            "width": "",\n            "tooltip": ""\n        }\n    },\n    "countries": {\n        "type": "Select_List",\n        "label": "Countries",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_countries",\n                "data_column": "code",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 0,\n                "listHeight": "7",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "countries_not": {\n        "type": "Select_List",\n        "label": "Hidden in",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_countries",\n                "data_column": "code",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 0,\n                "listHeight": "7",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "show_from": {\n        "type": "Date",\n        "label": "Show from",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "datePicker",\n            "config": "simple",\n            "tooltip": "",\n            "width": ""\n        }\n    },\n    "show_to": {\n        "type": "Date",\n        "label": "Show to",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "datePicker",\n            "config": "simple",\n            "tooltip": "",\n            "width": ""\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "Enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "../css/tempalte.css",\n            "template_list": "",\n            "folder": "content",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": "code_info"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Contents",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": [\n        {\n            "image_src": "media/upload_files/default.jpg",\n            "url": "component/table_manager/view/cu_content_by_sections",\n            "var_name": "",\n            "tooltip": "add_to_section"\n        }\n    ],\n    "include_file": "W3sicGF0aCI6ImNvbXBvbmVudHMvY29udGVudC9hZG1pbi9saXN0LnBocCIsImFkZF90byI6Imxpc3QiLCJwb3NpdGlvbiI6ImVuZCJ9XQ==",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwibmFtZSIsImVuYWJsZWQiXX0seyJ0YWJfbmFtZSI6IkNvbnRlbnQiLCJ0YWJfZmllbGRzIjpbImNsYXNzZXMiLCJjc3MiLCJjb250ZW50Il19LHsidGFiX25hbWUiOiJDb2RlIiwidGFiX2ZpZWxkcyI6WyJjb2RlIl19LHsidGFiX25hbWUiOiJDb25maWd1cmF0aW9uIiwidGFiX2ZpZWxkcyI6WyJhbmNob3IiLCJyZWdpb24iLCJsYW5ndWFnZSIsImNvdW50cmllcyIsImNvdW50cmllc19ub3QiLCJzaG93X2Zyb20iLCJzaG93X3RvIl19XSwiZmllbGRzX3RhYiI6eyJpZCI6IkdlbmVyYWwiLCJuYW1lIjoiR2VuZXJhbCIsImVuYWJsZWQiOiJHZW5lcmFsIiwidW5kZWZpbmVkIjoiQ29uZmlndXJhdGlvbiIsImNsYXNzZXMiOiJDb250ZW50IiwiY3NzIjoiQ29udGVudCIsImNvbnRlbnQiOiJDb250ZW50IiwiY29kZSI6IkNvZGUiLCJhbmNob3IiOiJDb25maWd1cmF0aW9uIiwicmVnaW9uIjoiQ29uZmlndXJhdGlvbiIsImxhbmd1YWdlIjoiQ29uZmlndXJhdGlvbiIsImNvdW50cmllcyI6IkNvbmZpZ3VyYXRpb24iLCJjb3VudHJpZXNfbm90IjoiQ29uZmlndXJhdGlvbiIsInNob3dfZnJvbSI6IkNvbmZpZ3VyYXRpb24iLCJzaG93X3RvIjoiQ29uZmlndXJhdGlvbiJ9fQ=="\n}'),
	(39, 'cu_content_by_sections', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "section": {\n        "type": "Select",\n        "label": "Section",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_menu_items",\n                "data_column": "id",\n                "label_column": "title",\n                "where_column": "menus_id  NOT IN (1,2)",\n                "nested_column": "id",\n                "parent_column": "parent_id",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "contents": {\n        "type": "Select_List",\n        "label": "Contents",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_content",\n                "data_column": "id",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 1,\n                "listHeight": "15",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": true,\n                "width": "300px"\n            },\n            "tooltip": ""\n        }\n    },\n    "show_in_subsection": {\n        "type": "Select",\n        "label": "Show in subsection",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "0",\n                    "false"\n                ],\n                [\n                    "1",\n                    "true"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "../css/tempalte.css",\n            "template_list": "",\n            "folder": "content",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": "code_info"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": null,\n    "show_list_like_tree_validate": null,\n    "show_list_like_tree_indicator": null,\n    "language_file_reference": "",\n    "custom_table_name": "Contents by sections",\n    "custom_table_name_language_reference": 0,\n    "order_by": null,\n    "order_by_order": null,\n    "link_indicator": "section",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwic2VjdGlvbiIsImNvbnRlbnRzIiwic2hvd19pbl9zdWJzZWN0aW9uIl19LHsidGFiX25hbWUiOiJDb2RlIiwidGFiX2ZpZWxkcyI6WyJjb2RlIl19XSwiZmllbGRzX3RhYiI6eyJpZCI6IkdlbmVyYWwiLCJzZWN0aW9uIjoiR2VuZXJhbCIsImNvbnRlbnRzIjoiR2VuZXJhbCIsInNob3dfaW5fc3Vic2VjdGlvbiI6IkdlbmVyYWwiLCJ1bmRlZmluZWQiOiJDb2RlIiwiY29kZSI6IkNvZGUifX0="\n}'),
	(41, 'cu_banners', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "background": {\n        "type": "File",\n        "label": "Background",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "folder": "banners",\n            "unique_name": 1,\n            "show_image": 1,\n            "dimention_priority": "height",\n            "dimention_image": "50",\n            "download_enabled": 1,\n            "tooltip": "",\n            "width": "",\n            "resize": false,\n            "max_width": "",\n            "max_height": "",\n            "crop": false\n        }\n    },\n    "css": {\n        "type": "TextArea",\n        "label": "Css",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "90px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "upload_files",\n            "base_64_encode": false,\n            "editor_mode": "css",\n            "maxlength": "",\n            "tooltip": "container_css_styles"\n        }\n    },\n    "content": {\n        "type": "TextArea",\n        "label": "Content",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "tinymce",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "../css/styles.css",\n            "template_list": "js/tiny_mce/templates/templates.js",\n            "folder": "banners",\n            "base_64_encode": false,\n            "editor_mode": "html",\n            "maxlength": "",\n            "tooltip": ""\n        }\n    },\n    "language": {\n        "type": "Language_Selector",\n        "label": "Language",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "multiSelect": false,\n            "height": "1",\n            "width": "",\n            "tooltip": ""\n        }\n    },\n    "countries": {\n        "type": "Select_List",\n        "label": "Countries",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_countries",\n                "data_column": "code",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 0,\n                "listHeight": "7",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "countries_not": {\n        "type": "Select_List",\n        "label": "Hidden in",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_countries",\n                "data_column": "code",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 0,\n                "listHeight": "7",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "show_from": {\n        "type": "Date",\n        "label": "Show from",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "datePicker",\n            "config": "simple",\n            "tooltip": "",\n            "width": ""\n        }\n    },\n    "show_to": {\n        "type": "Date",\n        "label": "Show to",\n        "showList": 0,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "datePicker",\n            "config": "simple",\n            "tooltip": "",\n            "width": ""\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "Enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "",\n            "template_list": "",\n            "folder": "upload_files",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": "code_info"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Banners",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": [\n        {\n            "image_src": "media/upload_files/default.jpg",\n            "url": "component/table_manager/view/cu_banners_by_sections",\n            "var_name": "",\n            "tooltip": "add_to_section"\n        }\n    ],\n    "include_file": "W3sicGF0aCI6ImNvbXBvbmVudHMvYmFubmVycy9hZG1pbi9saXN0LnBocCIsImFkZF90byI6Imxpc3QiLCJwb3NpdGlvbiI6ImVuZCJ9XQ==",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwibmFtZSIsImVuYWJsZWQiXX0seyJ0YWJfbmFtZSI6IkNvbnRlbnQiLCJ0YWJfZmllbGRzIjpbImJhY2tncm91bmQiLCJjc3MiLCJjb250ZW50Il19LHsidGFiX25hbWUiOiJDb2RlIiwidGFiX2ZpZWxkcyI6WyJjb2RlIl19LHsidGFiX25hbWUiOiJDb25maWd1cmF0aW9uIiwidGFiX2ZpZWxkcyI6WyJsYW5ndWFnZSIsImNvdW50cmllcyIsImNvdW50cmllc19ub3QiLCJzaG93X2Zyb20iLCJzaG93X3RvIl19XSwiZmllbGRzX3RhYiI6eyJpZCI6IkdlbmVyYWwiLCJuYW1lIjoiR2VuZXJhbCIsImVuYWJsZWQiOiJHZW5lcmFsIiwidW5kZWZpbmVkIjoiQ29uZmlndXJhdGlvbiIsImJhY2tncm91bmQiOiJDb250ZW50IiwiY3NzIjoiQ29udGVudCIsImNvbnRlbnQiOiJDb250ZW50IiwiY29kZSI6IkNvZGUiLCJsYW5ndWFnZSI6IkNvbmZpZ3VyYXRpb24iLCJjb3VudHJpZXMiOiJDb25maWd1cmF0aW9uIiwiY291bnRyaWVzX25vdCI6IkNvbmZpZ3VyYXRpb24iLCJzaG93X2Zyb20iOiJDb25maWd1cmF0aW9uIiwic2hvd190byI6IkNvbmZpZ3VyYXRpb24ifX0="\n}'),
	(43, 'cu_banners_by_sections', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "section": {\n        "type": "Select",\n        "label": "Section",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_menu_items",\n                "data_column": "id",\n                "label_column": "title",\n                "where_column": "menus_id  NOT IN (1,2)",\n                "nested_column": "id",\n                "parent_column": "parent_id",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": true,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "banners": {\n        "type": "Select_List",\n        "label": "Banners",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": {\n                "table_name": "cu_banners",\n                "data_column": "id",\n                "label_column": "name",\n                "where_column": "",\n                "nested_column": "",\n                "parent_column": "",\n                "init_id": "",\n                "dinamic_update_field": "",\n                "dinamic_update_column": ""\n            },\n            "extraParams": {\n                "multiSelect": 1,\n                "mirrorList": 1,\n                "listHeight": "7",\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": true,\n                "width": "200px"\n            },\n            "tooltip": ""\n        }\n    },\n    "show_in_subsection": {\n        "type": "Select",\n        "label": "Show in subsection",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "0",\n                    "false"\n                ],\n                [\n                    "1",\n                    "true"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "duration": {\n        "type": "Text",\n        "label": "Duration",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "number",\n            "number_fortmat": "normal",\n            "maxlength": "",\n            "tooltip": "E.g. 8 seg",\n            "width": "",\n            "default": "8"\n        }\n    },\n    "classes": {\n        "type": "Text",\n        "label": "Classes",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "tags",\n            "maxlength": "",\n            "tooltip": "Container classes",\n            "width": "100%",\n            "default": "\'banner_\'.time().mt_rand(1,999)"\n        }\n    },\n    "code": {\n        "type": "TextArea",\n        "label": "Code",\n        "showList": 0,\n        "includeDownload": 0,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "editor": "ace",\n            "width": "100%",\n            "height": "500px",\n            "styles_css": "../css/tempalte.css",\n            "template_list": "",\n            "folder": "upload_files",\n            "base_64_encode": false,\n            "editor_mode": "php",\n            "maxlength": "",\n            "tooltip": "code_info"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": "id",\n    "show_list_like_tree_validate": "id",\n    "show_list_like_tree_indicator": "id",\n    "language_file_reference": "",\n    "custom_table_name": "Banners by sections",\n    "custom_table_name_language_reference": 0,\n    "order_by": "id",\n    "order_by_order": "ASC",\n    "link_indicator": "section",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "",\n    "tabs": "eyJpbmZvIjpbeyJ0YWJfbmFtZSI6IkdlbmVyYWwiLCJ0YWJfZmllbGRzIjpbImlkIiwic2VjdGlvbiIsImJhbm5lcnMiLCJzaG93X2luX3N1YnNlY3Rpb24iXX0seyJ0YWJfbmFtZSI6IkNvbmZpZ3VyYXRpb24iLCJ0YWJfZmllbGRzIjpbImR1cmF0aW9uIiwiY2xhc3NlcyJdfSx7InRhYl9uYW1lIjoiQ29kZSIsInRhYl9maWVsZHMiOlsiY29kZSJdfV0sImZpZWxkc190YWIiOnsiaWQiOiJHZW5lcmFsIiwic2VjdGlvbiI6IkdlbmVyYWwiLCJiYW5uZXJzIjoiR2VuZXJhbCIsInNob3dfaW5fc3Vic2VjdGlvbiI6IkdlbmVyYWwiLCJ1bmRlZmluZWQiOiJDb2RlIiwiZHVyYXRpb24iOiJDb25maWd1cmF0aW9uIiwiY2xhc3NlcyI6IkNvbmZpZ3VyYXRpb24iLCJjb2RlIjoiQ29kZSJ9fQ=="\n}'),
	(45, 'cu_api_keys', '{\n    "id": {\n        "type": "Id",\n        "label": "Id",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0\n    },\n    "name": {\n        "type": "Text",\n        "label": "Name",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "",\n            "default": ""\n        }\n    },\n    "key": {\n        "type": "Text",\n        "label": "Key",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 1,\n        "language_button": 0,\n        "config": {\n            "type": "text",\n            "maxlength": "",\n            "tooltip": "",\n            "width": "300px",\n            "default": ""\n        }\n    },\n    "limit_access": {\n        "type": "Text",\n        "label": "Limit access",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "type": "tags",\n            "maxlength": "",\n            "tooltip": "limit_access_tooltip",\n            "width": "",\n            "default": ""\n        }\n    },\n    "enabled": {\n        "type": "Select",\n        "label": "Enabled",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": ""\n        }\n    },\n    "ssl": {\n        "type": "Select",\n        "label": "SSL",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "1",\n                    "true"\n                ],\n                [\n                    "0",\n                    "false"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": "ssl_tooltip"\n        }\n    },\n    "sql_queries": {\n        "type": "Select",\n        "label": "SQL queries",\n        "showList": 1,\n        "includeDownload": 1,\n        "required": 0,\n        "language_button": 0,\n        "config": {\n            "data": [\n                [\n                    "0",\n                    "false"\n                ],\n                [\n                    "1",\n                    "true"\n                ]\n            ],\n            "extraParams": {\n                "add_custom_item": 0,\n                "custom_data": "",\n                "custom_label": "",\n                "no_translate": false,\n                "width": ""\n            },\n            "tooltip": "sql_queries_tooltip"\n        }\n    },\n    "show_list_like_tree": 0,\n    "show_list_like_tree_column": null,\n    "show_list_like_tree_validate": null,\n    "show_list_like_tree_indicator": null,\n    "language_file_reference": "",\n    "custom_table_name": "Api keys",\n    "custom_table_name_language_reference": 0,\n    "order_by": null,\n    "order_by_order": null,\n    "link_indicator": "name",\n    "list_limit": "",\n    "primary_key": "id",\n    "option_panel": null,\n    "include_file": "W3sicGF0aCI6ImFwaS9hZG1pbmlzdHJhdG9yL2N1X2FwaV9rZXlzX2VkaXQucGhwIiwiYWRkX3RvIjoiZm9ybSIsInBvc2l0aW9uIjoiZW5kIn1d",\n    "tabs": ""\n}');
/*!40000 ALTER TABLE `cu_tables` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_tables_log
CREATE TABLE IF NOT EXISTS `cu_tables_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_creator` int(11) DEFAULT NULL,
  `user_id_update` int(11) DEFAULT NULL,
  `table_name` varchar(100) NOT NULL DEFAULT '',
  `reference_id` int(11) DEFAULT NULL,
  `date_registered` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `user_id_updating` int(11) DEFAULT NULL,
  `date_updating` datetime DEFAULT NULL,
  `deleted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name_UNIQUE` (`table_name`,`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_tables_log: ~1 rows (approximately)
/*!40000 ALTER TABLE `cu_tables_log` DISABLE KEYS */;
INSERT INTO `cu_tables_log` (`id`, `user_id_creator`, `user_id_update`, `table_name`, `reference_id`, `date_registered`, `date_update`, `user_id_updating`, `date_updating`, `deleted`) VALUES
	(1, NULL, NULL, 'cu_api_keys', 1, NULL, NULL, 1, '2019-04-05 00:51:00', NULL);
/*!40000 ALTER TABLE `cu_tables_log` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_users
CREATE TABLE IF NOT EXISTS `cu_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(500) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `user_group_id` int(11) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `date_registered` date DEFAULT NULL,
  `restore_password` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping structure for table cuppa.cu_user_groups
CREATE TABLE IF NOT EXISTS `cu_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `admin_login` int(11) NOT NULL DEFAULT '0',
  `site_login` int(11) NOT NULL DEFAULT '1',
  `enabled` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_user_groups: ~3 rows (approximately)
/*!40000 ALTER TABLE `cu_user_groups` DISABLE KEYS */;
INSERT INTO `cu_user_groups` (`id`, `name`, `admin_login`, `site_login`, `enabled`) VALUES
	(1, 'Super admin', 1, 1, 1),
	(2, 'Admin', 1, 1, 1),
	(3, 'Users', 0, 1, 1);
/*!40000 ALTER TABLE `cu_user_groups` ENABLE KEYS */;

-- Dumping structure for table cuppa.cu_views
CREATE TABLE IF NOT EXISTS `cu_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `screen` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table cuppa.cu_views: ~0 rows (approximately)
/*!40000 ALTER TABLE `cu_views` DISABLE KEYS */;
INSERT INTO `cu_views` (`id`, `name`, `description`, `screen`, `file_path`, `code`) VALUES
	(1, 'Default', 'This is the default view', '', '', '<?php \n    include \'html/menu.php\';\n    include \'administrator/components/banners/banners.php\';\n    include \'administrator/components/content/content.php\';\n?>');
/*!40000 ALTER TABLE `cu_views` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
