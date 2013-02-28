USE ProjectSwitch; # database name

CREATE TABLE if not exists `users` (
userId BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
firstName VARCHAR(45),
lastName VARCHAR(45),
password VARCHAR(60),
email VARCHAR (100),
role INT, # 1 - user, 2 - admin
created BIGINT NOT NULL,
isDeleted BOOL DEFAULT 0,
approvedBy INT DEFAULT 0 
);

INSERT INTO `users` (`firstName`, `lastName`, `password`, `email`, `role`, `created`) VALUES ('Default', 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@albany.edu', 2, 123456789); #default admin with password admin

CREATE TABLE IF NOT EXISTS `days` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
userId BIGINT,
assignedDate DATE,
month INT,
fromTime INT, # - in seconds from the midnight  
toTime INT,
created BIGINT NOT NULL,
FOREIGN KEY(userId) REFERENCES users(userId)
);

CREATE TABLE IF NOT EXISTS `switch` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
userId1 BIGINT,
userId2 BIGINT,
date1 DATE,
date2 DATE, 
fromTime1 INT,
fromTime2 INT,
toTime1 INT,
toTime2 INT,
status INT, # 0 - initiated, 1 - confirmed, 2 - declined, 3 - approved by RD
reason VARCHAR (255), # why it was declined or just a comment
created BIGINT NOT NULL,
FOREIGN KEY(userId1) REFERENCES users(userId),
FOREIGN KEY(userId2) REFERENCES users(userId)
);

create table if not exists `message`(
`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
`fromId` BIGINT not null,
`toId` BIGINT not null,
`read` BOOL DEFAULT 0,
`subject` varchar(255),
`text` TEXT,
`created` bigint,
`isDeleted` BOOL DEFAULT 0,
FOREIGN KEY(fromId) REFERENCES users(userId),
FOREIGN KEY(toId) REFERENCES users(userId)
);

create table if not exists `setting`(
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`setting` varchar(255) NOT NULL,
`intVal` INT,
`charVal` varchar(255)
);

INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('numberOfRd', 1, ''); # admin settings
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_1', 1, '');	# very important!

CREATE TABLE if not exists `log` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`event` INT NOT NULL,
`description` VARCHAR(255),
`created` BIGINT,
`userId1` BIGINT,
`userId2` BIGINT
);

CREATE TABLE IF NOT EXISTS `token` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
created BIGINT,
expired BIGINT,
type INT,
token VARCHAR(70),
raId1 BIGINT,
raId2 BIGINT,
rdId BIGINT,
switchId BIGINT
);



