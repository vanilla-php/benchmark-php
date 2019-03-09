CREATE DATABASE IF NOT EXISTS `php_bench` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `php_bench`;

CREATE TABLE IF NOT EXISTS `benchmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` decimal(10,1) DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `php_version` varchar(255) DEFAULT NULL,
  `strings` decimal(10,3) DEFAULT NULL,
  `loops` decimal(10,3) DEFAULT NULL,
  `if_else` decimal(10,3) DEFAULT NULL,
  `calc_total` decimal(10,3) DEFAULT NULL,
  `mysql_version` varchar(255) DEFAULT NULL,
  `mysql_con` decimal(10,3) DEFAULT NULL,
  `mysql_sel` decimal(10,3) DEFAULT NULL,
  `mysql_query` decimal(10,3) DEFAULT NULL,
  `mysql_bench` decimal(10,3) DEFAULT NULL,
  `mysql_total` decimal(10,3) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `total_time` decimal(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;