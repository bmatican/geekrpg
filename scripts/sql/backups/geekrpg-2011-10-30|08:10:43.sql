DROP TABLE Problem;

CREATE TABLE `Problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `body` mediumtext NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `userid` (`userid`),
  KEY `dateAdded` (`dateAdded`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

INSERT INTO Problem VALUES("1","1","aaa","asdasdasda","1317492958","0");
INSERT INTO Problem VALUES("2","2","xxx","kaushfdpoa","1317492000","1");



DROP TABLE Problem_comment;

CREATE TABLE `Problem_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `body` mediumtext NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `postid` (`postid`),
  KEY `parentid` (`parentid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO Problem_comment VALUES("1","2","0","1","This has a \" and a \'","1317494000","0");



DROP TABLE Problem_tagmap;

CREATE TABLE `Problem_tagmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objectid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`objectid`,`tagid`),
  KEY `objectid` (`objectid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO Problem_tagmap VALUES("1","1","1");
INSERT INTO Problem_tagmap VALUES("2","1","2");
INSERT INTO Problem_tagmap VALUES("3","2","2");



DROP TABLE Problem_tags;

CREATE TABLE `Problem_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO Problem_tags VALUES("1","java","java");
INSERT INTO Problem_tags VALUES("2","c++","c++");



DROP TABLE Solution;

CREATE TABLE `Solution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `body` mediumtext NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  `problemid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `uq_solution` (`problemid`,`userid`),
  KEY `userid` (`userid`),
  KEY `dateAdded` (`dateAdded`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO Solution VALUES("1","1","","","0","0","1");
INSERT INTO Solution VALUES("2","0","aaa","xxxx","1317490000","0","1");
INSERT INTO Solution VALUES("3","0","bb","bbbb","1317491000","0","2");



DROP TABLE Solution_comment;

CREATE TABLE `Solution_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `body` mediumtext NOT NULL,
  `dateAdded` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `postid` (`postid`),
  KEY `parentid` (`parentid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




DROP TABLE Users;

CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(20) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `email` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO Users VALUES("1","1234567","9","aaaaaa@gmail.com");
INSERT INTO Users VALUES("2","matican","7ac66c0f148de9519b8bd264312c4d64","mati@mati.com");
INSERT INTO Users VALUES("9","maticanbogdan","7ac66c0f148de9519b8bd264312c4d64","mati@mati.com");
INSERT INTO Users VALUES("3","xxx","xxxxx","xxx@gmail.com");



DROP TABLE Users_tagmap;

CREATE TABLE `Users_tagmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objectid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`objectid`,`tagid`),
  KEY `objectid` (`objectid`),
  KEY `tagid` (`tagid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO Users_tagmap VALUES("1","1","3");
INSERT INTO Users_tagmap VALUES("2","1","2");
INSERT INTO Users_tagmap VALUES("3","1","4");
INSERT INTO Users_tagmap VALUES("4","3","2");
INSERT INTO Users_tagmap VALUES("5","2","2");
INSERT INTO Users_tagmap VALUES("6","2","3");



DROP TABLE Users_tags;

CREATE TABLE `Users_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_key` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO Users_tags VALUES("1","c++","c++ language");
INSERT INTO Users_tags VALUES("2","xxx","xxxx xxxx");
INSERT INTO Users_tags VALUES("3","bash","bash language");
INSERT INTO Users_tags VALUES("4","aaa","aaa is aaaa");



