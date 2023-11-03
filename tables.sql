CREATE TABLE `comment` (
 `comment_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
 `text` varchar(255) NOT NULL,
 `comment_user_id` int(255) unsigned NOT NULL,
 `comment_story_id` int(255) unsigned NOT NULL,
 PRIMARY KEY (`comment_id`),
 KEY `comment_FK_2` (`comment_user_id`),
 KEY `comment_FK_1` (`comment_story_id`),
 CONSTRAINT `comment_FK_1` FOREIGN KEY (`comment_story_id`) REFERENCES `story` (`story_id`),
 CONSTRAINT `comment_FK_2` FOREIGN KEY (`comment_user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1

CREATE TABLE `story` (
 `story_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `body` varchar(255) NOT NULL,
 `link` varchar(255) NOT NULL,
 `createdby_id` int(255) unsigned NOT NULL,
 PRIMARY KEY (`story_id`),
 KEY `story_FK_1` (`createdby_id`),
 CONSTRAINT `story_FK_1` FOREIGN KEY (`createdby_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1

CREATE TABLE `user` (
 `user_id` int(255) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(255) NOT NULL,
 `hashed_password` char(255) NOT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1