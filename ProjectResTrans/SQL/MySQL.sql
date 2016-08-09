DROP TABLE IF EXISTS `user`;
CREATE TABLE `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_fname` varchar(45) NOT NULL,
  `user_lname` varchar(45) NOT NULL,
  `user_cell` varchar(45) NOT NULL,
  `user_number` varchar(45) NOT NULL,
  `user_type` varchar(45) NOT NULL,
  `user_addrs` varchar(45) NOT NULL,
  `user_pass` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `transport`;
CREATE TABLE `transport` (
  `transport_id` int(10) NOT NULL AUTO_INCREMENT,
  `transport_no` varchar(45) DEFAULT NULL,
  `transport_type` varchar(45) DEFAULT NULL,
  `transport_email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`transport_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `booking`;
CREATE TABLE `booking` (
  `booking_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `transport_id` int(10) NOT NULL,
  `booking_from` varchar(45) DEFAULT NULL,
  `booking_to` varchar(45) DEFAULT NULL,
  `booking_start_time` int(11) DEFAULT NULL,
  `booking_end_time` int(11) DEFAULT NULL,
  `booking_confirmation` varchar(45) DEFAULT NULL,
  `booking_message` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `transport_id` (`transport_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`transport_id`) REFERENCES `transport` (`transport_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `transport_date`;
CREATE TABLE `transport_date` (
  `transport_date_id` int(10) NOT NULL AUTO_INCREMENT,
  `start_time` varchar(45) DEFAULT NULL,
  `end_time` varchar(45) DEFAULT NULL,
  `transport_id` int(10) NOT NULL,
  PRIMARY KEY (`transport_date_id`),
  KEY `transport_id` (`transport_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
