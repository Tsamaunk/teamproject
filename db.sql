CREATE TABLE IF NOT EXISTS `users` (
userId BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
firstName VARCHAR(45),
lastName VARCHAR(45),
password VARCHAR(60),
email VARCHAR (100),
role INT, 				# 1 - user, 2 - admin
created BIGINT NOT NULL,
isDeleted BOOL DEFAULT 0,
approvedBy INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `days` (
id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
userId BIGINT,
assignedDate DATE,
fromTime INT, 			# - in seconds from the midnight  
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
status INT, 		# 0 - initiated, 1 - confirmed, 2 - declined, 3 - approved by RD
reason VARCHAR (255), 	# why it was declined or just a comment
created BIGINT NOT NULL,
FOREIGN KEY(userId1) REFERENCES users(userId),
FOREIGN KEY(userId2) REFERENCES users(userId)
);

