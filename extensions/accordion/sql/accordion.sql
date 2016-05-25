/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `ex_accordion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `group` int(11) NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '',
  `countries` varchar(255) NOT NULL DEFAULT '',
  `countries_not` varchar(255) NOT NULL DEFAULT '',
  `order` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `ex_accordion` VALUES (1,'Las2 venas varicosas NO son sólo un problema cosmético que no necesita ser tratado','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',1,'','','',0,1);
INSERT INTO `ex_accordion` VALUES (2,'Las venas varicosas NO son lo mismo que arañas vasculares (telangiectasias)','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',1,'','','',0,1);
INSERT INTO `ex_accordion` VALUES (3,'Las venas varicosas NO son lo mismo que arañas vasculares (telangiectasias)','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',2,'','','',0,1);
INSERT INTO `ex_accordion` VALUES (4,'Las venas varicosas NO son lo mismo que arañas vasculares (telangiectasias)','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',3,'','','',0,1);
INSERT INTO `ex_accordion` VALUES (5,'Las venas varicosas NO son lo mismo que arañas vasculares (telangiectasias)','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',4,'','','',0,1);
INSERT INTO `ex_accordion` VALUES (6,'Las venas varicosas NO son lo mismo que arañas vasculares (telangiectasias)','Aunque a menudo considerado como un problema cosm&eacute;tico, las venas varicosas pueden progresar a una forma m&aacute;s grave de la enfermedad venosa cr&oacute;nica llamada insuficiencia venosa cr&oacute;nica (IVC). IVC es una enfermedad progresiva que puede dar lugar a signos y s&iacute;ntomas cada vez m&aacute;s graves si no se tratan, incluyendo dolor, hinchaz&oacute;n, da&ntilde;os en la piel y &uacute;lceras. (1) Hoy en d&iacute;a, los pacientes tienen acceso a las opciones de tratamiento m&iacute;nimamente invasivos, que permiten una recuperaci&oacute;n corta y un r&aacute;pido retorno a las actividades. (3,4).',5,'','','',0,1);
CREATE TABLE `ex_accordion_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `ex_accordion_groups` VALUES (1,'quien-esta-en-riesgo');
INSERT INTO `ex_accordion_groups` VALUES (2,'prevencion');
INSERT INTO `ex_accordion_groups` VALUES (3,'disipando-mitos');
INSERT INTO `ex_accordion_groups` VALUES (4,'Procedimiento ClosureFast');
INSERT INTO `ex_accordion_groups` VALUES (5,'Procedimiento venaseal');

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
