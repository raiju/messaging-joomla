DROP TABLE IF EXISTS `#__messaging`;
DROP TABLE IF EXISTS `#__messaging_groups`;

CREATE TABLE `#__messaging` (
  `n` int(11) NOT NULL auto_increment,
  `idFrom` int(11) NOT NULL,
  `idTo` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `seen` bool NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`n`)
) DEFAULT CHARACTER SET utf8;

CREATE TABLE `#__messaging_groups` (
  `n` int(11) NOT NULL,
  `groupName` varchar(75) NOT NULL,
  `messageLimit` int(11) NOT NULL
) DEFAULT CHARACTER SET utf8;

INSERT INTO `#__messaging_groups` VALUES (0,'Super Administrator',0);
INSERT INTO `#__messaging_groups` VALUES (1,'Administrator',0);
INSERT INTO `#__messaging_groups` VALUES (2,'Manager',0);
INSERT INTO `#__messaging_groups` VALUES (3,'Publisher',0);
INSERT INTO `#__messaging_groups` VALUES (4,'Editor',0);
INSERT INTO `#__messaging_groups` VALUES (5,'Author',0);
INSERT INTO `#__messaging_groups` VALUES (6,'Registered',0);
INSERT INTO `#__messaging_groups` VALUES (7,'nameSuggestion',0);
INSERT INTO `#__messaging_groups` VALUES (8,'sendNotify',1);
INSERT INTO `#__messaging_groups` VALUES (9,'limitAddress',1);
