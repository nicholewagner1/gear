CREATE TABLE `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `thumbnail` tinyint(1) DEFAULT '0',
  `serial` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `UNIQUE` (`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8mb4;