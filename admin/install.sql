DROP TABLE IF EXISTS `#__snk`;

CREATE TABLE `#__snk` (
  `id` int(11) NOT NULL auto_increment,
  `greeting` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__snk` (`greeting`) VALUES ('Hello, World!'), ('Bonjour, Monde!'), ('Ciao, Mondo!');
