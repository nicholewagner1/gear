CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `notes` varchar(555) DEFAULT NULL,
  `cost` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;