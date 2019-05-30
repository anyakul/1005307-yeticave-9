 

CREATE DATABASE yeticave;
DEFAULT CHARACTER SET UTF8;
DEFAULT COLLATE UTF8_GENERAL_CI; 
  
CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name char(128),
symbol_code char(128)
); 

CREATE TABLE lots (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED,
user_winner_id INT,
category_id INT UNSIGNED,
date_create DATETIME,
name char(128),
description text,
image char(128) ,
start_price int UNSIGNED,
current_price int UNSIGNED,
count_rates int UNSIGNED,
date_finish DATETIME,
step_rate INT UNSIGNED
);

CREATE TABLE rates (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT UNSIGNED,
lot_id INT UNSIGNED,
date_create DATETIME,
price int
);

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY, 
date_registration DATETIME,
email char(128),
name char(128),
password char(64),
avatar char(128) ,
contacts char(255)
);

CREATE INDEX c_category ON lots(category_id);
CREATE INDEX c_user ON lots(user_id);
CREATE UNIQUE INDEX c_user_name ON users(name);
CREATE UNIQUE INDEX c_email  ON users(email);
CREATE FULLTEXT INDEX lot_ft_searchlotslots ON lots(name, description)   /* добавлено 16.05.2019 /*