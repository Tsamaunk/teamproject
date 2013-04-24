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

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `password`, `email`, `role`, `created`, `isDeleted`, `approvedBy`) VALUES
(1, 'Russell', 'Green', '5f4dcc3b5aa765d61d8327deb882cf99', 'admin@admin.admin', 2, 1234567890, 0, 1),
(2, 'John', 'Smith', '5f4dcc3b5aa765d61d8327deb882cf99', 'himor.cre@gmail.com', 2, 1364175034, 0, 1),
(3, 'Default', 'Admin', 'd41d8cd98f00b204e9800998ecf8427e', 'admin2@admin.admin', 2, 1364315185, 0, 1),
(4, 'Jake', 'Craig', '5f4dcc3b5aa765d61d8327deb882cf99', 'himor.cre@gmail.com', 2, 1364315265, 0, 1),
(5, 'Mike', 'Gordo', '5f4dcc3b5aa765d61d8327deb882cf99', 'mgordo@live.com', 2, 1364525760, 0, 1),
(6, 'Matt', 'Grunert', '5f4dcc3b5aa765d61d8327deb882cf99', 'himor.cre2@gmail.com', 2, 1365030897, 0, 1),
(7, 'Alex', 'Brown', '5f4dcc3b5aa765d61d8327deb882cf99', 'abrown@none.com', 2, 1366765769, 0, 1),
(8, 'Anthony', 'Hopkins', '5f4dcc3b5aa765d61d8327deb882cf99', 'ah@none.com', 2, 1366765789, 0, 1);

CREATE TABLE IF NOT EXISTS `days` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`type` INT, # 1 - regular, 2 - admin on duty 
userId BIGINT,
assignedDate DATE,
month INT,
fromTime INT, # - in seconds from the midnight  
toTime INT,
created BIGINT NOT NULL,
FOREIGN KEY(userId) REFERENCES users(userId)
);

INSERT INTO `days` (`id`, `type`, `userId`, `assignedDate`, `month`, `fromTime`, `toTime`, `created`) VALUES
(1, 2, 6, '2013-04-21', 4, 0, 0, 1366766190),
(2, 2, 7, '2013-04-22', 4, 0, 0, 1366766194),
(3, 2, 2, '2013-04-23', 4, 0, 0, 1366766197),
(4, 2, 6, '2013-04-24', 4, 0, 0, 1366766202),
(5, 2, 7, '2013-04-25', 4, 0, 0, 1366766205),
(6, 2, 2, '2013-04-26', 4, 0, 0, 1366766208),
(7, 2, 6, '2013-04-27', 4, 0, 0, 1366766212),
(8, 1, 1, '2013-04-21', 4, 0, 0, 1366766219),
(9, 1, 2, '2013-04-21', 4, 0, 0, 1366766224),
(10, 1, 1, '2013-04-22', 4, 0, 0, 1366766227),
(11, 1, 4, '2013-04-22', 4, 0, 0, 1366766231),
(12, 1, 4, '2013-04-23', 4, 0, 0, 1366766236),
(13, 1, 7, '2013-04-23', 4, 0, 0, 1366766239),
(14, 1, 4, '2013-04-24', 4, 0, 0, 1366766246),
(15, 1, 7, '2013-04-24', 4, 0, 0, 1366766249),
(16, 1, 8, '2013-04-24', 4, 0, 0, 1366766252),
(17, 1, 2, '2013-04-25', 4, 0, 0, 1366766256),
(18, 1, 1, '2013-04-25', 4, 0, 0, 1366766258),
(19, 1, 8, '2013-04-25', 4, 0, 0, 1366766261),
(20, 1, 5, '2013-04-26', 4, 0, 0, 1366766263),
(21, 1, 8, '2013-04-26', 4, 0, 0, 1366766267),
(22, 1, 5, '2013-04-27', 4, 0, 0, 1366766270),
(23, 1, 7, '2013-04-27', 4, 0, 0, 1366766272),
(24, 1, 1, '2013-04-27', 4, 0, 0, 1366766276);

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
status INT, # 0 - initiated, 1 - confirmed, 2 - declined, 3 - approved by RD, 4 - denied by RD
reason VARCHAR (255), # why it was declined or just a comment
created BIGINT NOT NULL,
FOREIGN KEY(userId1) REFERENCES users(userId),
FOREIGN KEY(userId2) REFERENCES users(userId)
);

INSERT INTO `switch` (`id`, `userId1`, `userId2`, `date1`, `date2`, `fromTime1`, `fromTime2`, `toTime1`, `toTime2`, `status`, `reason`, `created`) VALUES
(2, 2, 4, '2013-04-21', '2013-04-22', 0, 0, 0, 0, 0, NULL, 15253546),
(3, 1, 7, '2013-04-21', '2013-04-23', 0, 0, 0, 0, 3, NULL, 15253546),
(4, 4, 5, '2013-04-24', '2013-04-26', 0, 0, 0, 0, 1, NULL, 15253546);

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

INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('numberOfRd', 8, ''); 	# admin settings
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_1', 1, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_2', 2, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_3', 3, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_4', 4, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_5', 5, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_6', 6, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_7', 7, '');			# very important!
INSERT INTO `setting` (`setting`, `intVal`, `charVal`) VALUES ('rd_8', 8, '');			# very important!

CREATE TABLE if not exists `log` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
`event` INT NOT NULL,
`description` VARCHAR(255),
`created` BIGINT,
`userId1` BIGINT,
`userId2` BIGINT
);

INSERT INTO `log` (`id`, `event`, `description`, `created`, `userId1`, `userId2`) VALUES
(1, 14, 'User role updated', 1366766115, 1, 2),
(2, 14, 'User role updated', 1366766116, 1, 4),
(3, 14, 'User role updated', 1366766117, 1, 7),
(4, 14, 'User role updated', 1366766118, 1, 8),
(5, 15, 'User undeleted', 1366766118, 1, 6),
(6, 20, 'Approved user', 1366766120, 1, 5),
(7, 20, 'Approved user', 1366766120, 1, 6),
(8, 20, 'Approved user', 1366766121, 1, 3),
(9, 14, 'User role updated', 1366766164, 1, 3);

CREATE TABLE IF NOT EXISTS `token` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
created BIGINT,
expired BIGINT,
`type` INT,
token VARCHAR(70),
raId1 BIGINT,
raId2 BIGINT,
rdId BIGINT,
switchId BIGINT
);

CREATE TABLE IF NOT EXISTS `security` (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
userToken VARCHAR(120) NOT NULL,
userId BIGINT NOT NULL,
timer BIGINT #expiration timestamp
);

CREATE TABLE IF NOT EXISTS `notif` (
id BIGINT AUTO_INCREMENT PRIMARY KEY,
userId BIGINT NOT NULL,
event INT NOT NULL,
another BIGINT,		# can be another user for example
description VARCHAR(70),
created BIGINT,
`read` BOOL DEFAULT 0,
FOREIGN KEY(userId) REFERENCES users(userId)
);