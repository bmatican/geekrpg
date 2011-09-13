/* user for doing user account specific operations (sign up, login, etc.) */

CREATE USER 'noob'@'localhost' IDENTIFIED BY 'noob';
CREATE DATABASE IF NOT EXISTS geekrpg;
GRANT INSERT ON geekrpg.* TO 'noob'@'localhost';


/* setup user table */
CREATE TABLE IF NOT EXISTS geekrpg.Users (id INTEGER AUTO_INCREMENT PRIMARY KEY, username CHAR(20), password CHAR(32), email CHAR(30));
