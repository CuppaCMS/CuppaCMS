DROP TABLE IF EXISTS `ex_shop_categories`;
DROP TABLE IF EXISTS `ex_shop_countries`;
DROP TABLE IF EXISTS `ex_shop_features`;
DROP TABLE IF EXISTS `ex_shop_features_values`;
DROP TABLE IF EXISTS `ex_shop_form`;
DROP TABLE IF EXISTS `ex_shop_order_states`;
DROP TABLE IF EXISTS `ex_shop_orders`;
DROP TABLE IF EXISTS `ex_shop_product_images`;
DROP TABLE IF EXISTS `ex_shop_product_prices`;
DROP TABLE IF EXISTS `ex_shop_products`;
DROP TABLE IF EXISTS `ex_shop_shipping`;
DROP TABLE IF EXISTS `ex_shop_taxes`;

DELETE FROM cu_tables WHERE table_name LIKE 'ex_shop%';
DELETE FROM cu_permissions_data WHERE reference LIKE 'ex_shop%';
DELETE FROM cu_menu_items WHERE menus_id = 1 AND alias LIKE 'shop%';

