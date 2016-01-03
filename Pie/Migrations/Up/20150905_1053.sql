CREATE TABLE IF NOT EXISTS spsp_admin
(id INT AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(64) NOT NULL,
password VARCHAR(64) NOT NULL,
live TINYINT DEFAULT '1' NOT NULL,
created INT DEFAULT '0' NOT NULL);

INSERT INTO spsp_admin (email, password, created)
VALUES ('rob@statuspeople.com', '$2y$10$7Bg7jfNePiBETF7BVhEcpu.80yJ0uEhTbSzaCKW1PHhs324hBi7yy', UNIX_TIMESTAMP());