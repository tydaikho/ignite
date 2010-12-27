DROP TABLE IF EXISTS `#__hello`;

CREATE TABLE `#__hello` (
  `id` int(11) NOT NULL auto_increment,
  `greeting` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__hello` (`greeting`) VALUES ('Hello, World!'), ('Bonjour, Monde!'), ('Ciao, Mondo!');
