vagrant   up
vagrant   ssh
mysql -u root -p
CREATE DATABASE quiz; 
GRANT ALL ON quiz.* TO 'creator'@'localhost' IDENTIFIED BY 'password';
USE quiz;

CREATE TABLE questions (question_number_id INT(20), theme VARCHAR(25), question VARCHAR(150), answer VARCHAR(20), points INT(3)) ENGINE InnoDB;
DESCRIBE questions;
ALTER TABLE questions ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY;
CREATE INDEX user_id ON questions(questionNumber_id(20));

CREATE TABLE users (user_id INT(20), name VARCHAR(15), surname VARCHAR(20), gender VARCHAR(15), date_of_birth VARCHAR(15), login VARCHAR(20), password VARCHAR(15), email VARCHAR(40), salt VARCHAR(30),cookie VARCHAR(30)) ENGINE InnoDB;
ALTER TABLE users ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY;
CREATE INDEX user_id ON users(user_id(20));


CREATE TABLE usersAnswers (user_id INT(11), question_number_id INT(20), userAnswer VARCHAR(15), user_points INT(3)) ENGINE InnoDB;
ALTER TABLE usersAnswers ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY;

CREATE TABLE results (user_id INT(11), theme VARCHAR(15), best_points INT(3)) ENGINE InnoDB;
ALTER TABLE results ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY;


INSERT INTO questions (question_number_id, theme, question, answer, points)
VALUES('1', 'литература','Кто написал комедию "Горе от ума"?','грибоедов','10');
INSERT INTO questions (question_number_id, theme, question, answer, points)
VALUES('2', 'литература','Кто написал комедию "Преступление и наказание"?','достоевский','10');
INSERT INTO questions (question_number_id, theme, question, answer, points)
VALUES('3', 'литература','В каком году родился Марк Твен','1835','10');
INSERT INTO questions (question_number_id, theme, question, answer, points)
VALUES('4', 'литература','В каком году родился Ф.М. Достоевский','1821','10');
INSERT INTO questions (question_number_id, theme, question, answer, points)
VALUES('5', 'литература','В каком году написан роман "Мастер и Маргарита"?','1928','10');

SELECT * FROM classics;


ALTER TABLE questions CONVERT TO CHARACTER SET  utf8;
ALTER TABLE tbl_name CONVERT TO CHARACTER SET  charset _name;


utf8_general_ci


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(18) NOT NULL,
  `surname` varchar(18) NOT NULL,
  `gender` varchar(18) NOT NULL,
  `date_of_birth` date NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `role` varchar(17) DEFAULT NULL,
  `salt` varchar(8) DEFAULT NULL,
  `cookie` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;