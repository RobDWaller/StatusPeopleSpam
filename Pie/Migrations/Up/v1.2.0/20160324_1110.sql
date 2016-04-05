CREATE TABLE IF NOT EXISTS spsp_admin_logins
(id INT AUTO_INCREMENT PRIMARY KEY,
ip_address VARCHAR(20) NOT NULL,
success TINYINT DEFAULT '0' NOT NULL,
created INT DEFAULT '0' NOT NULL);
ALTER TABLE spsp_admin_logins ADD INDEX (ip_address),
ADD INDEX (created);