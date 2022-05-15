<meta charset="UTF-8">   /*  <-  НЕ УДАЛЯТЬ !!! чтобы БД поняла какая кодировка */
SHOW VARIABLES;

CREATE TABLE `score_list` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `date` DATETIME ,
    `name` VARCHAR(30) CHARACTER SET utf8mb4 ,
    `score` VARCHAR(20) CHARACTER SET utf8mb4 ,
    PRIMARY KEY (`id`)
) default charset utf8mb4 ;

/* создание таблицы для приложения */
CREATE TABLE `mismatch_user` (
  `user_id` INT AUTO_INCREMENT,
  `join_date` DATETIME,
  `first_name` VARCHAR(32) CHARACTER SET utf8mb4 ,
  `last_name` VARCHAR(32) CHARACTER SET utf8mb4 ,
  `gender` VARCHAR(1) CHARACTER SET utf8mb4 ,
  `birthdate` DATE,
  `city` VARCHAR(32) CHARACTER SET utf8mb4 ,
  `state` VARCHAR(2) CHARACTER SET utf8mb4 ,
  `picture` VARCHAR(32) CHARACTER SET utf8mb4 ,
  PRIMARY KEY (`user_id`)
) default charset utf8mb4 ;

/* добавление полей для Логина и Пароля */
ALTER TABLE `mismatch_user`
    ADD COLUMN username VARCHAR(32) CHARACTER SET utf8mb4 NOT NULL 
        AFTER user_id ;
ALTER TABLE `mismatch_user`
    ADD COLUMN password VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL 
        AFTER username ;

INSERT INTO `mismatch_user` (username, password, join_date) VALUES ('sasha', SHA('123'), NOW());

SELECT username FROM mismatch_user WHERE password = SHA('123');

/* заносим данные */
INSERT INTO `mismatch_user` VALUES (1, '2008-06-03 14:51:46', 'Sidney', 'Kelsow', 'F', '1984-07-19', 'Tempe', 'AZ', 'sidneypic.jpg');
INSERT INTO `mismatch_user` VALUES (2, '2008-06-03 14:52:09', 'Nevil', 'Johansson', 'M', '1973-05-13', 'Reno', 'NV', 'nevilpic.jpg');
INSERT INTO `mismatch_user` VALUES (3, '2008-06-03 14:53:05', 'Alex', 'Cooper', 'M', '1974-09-13', 'Boise', 'ID', 'alexpic.jpg');
INSERT INTO `mismatch_user` VALUES (4, '2008-06-03 14:58:40', 'Susannah', 'Daniels', 'F', '1977-02-23', 'Pasadena', 'CA', 'susannahpic.jpg');
INSERT INTO `mismatch_user` VALUES (5, '2008-06-03 15:00:37', 'Ethel', 'Heckel', 'F', '1943-03-27', 'Wichita', 'KS', 'ethelpic.jpg');
INSERT INTO `mismatch_user` VALUES (6, '2008-06-03 15:00:48', 'Oscar', 'Klugman', 'M', '1968-06-04', 'Providence', 'RI', 'oscarpic.jpg');
INSERT INTO `mismatch_user` VALUES (7, '2008-06-03 15:01:08', 'Belita', 'Chevy', 'F', '1975-07-08', 'El Paso', 'TX', 'belitapic.jpg');
INSERT INTO `mismatch_user` VALUES (8, '2008-06-03 15:01:19', 'Jason', 'Filmington', 'M', '1969-09-24', 'Hollywood', 'CA', 'jasonpic.jpg');
INSERT INTO `mismatch_user` VALUES (9, '2008-06-03 15:01:51', 'Dierdre', 'Pennington', 'F', '1970-04-26', 'Cambridge', 'MA', 'dierdrepic.jpg');
INSERT INTO `mismatch_user` VALUES (10, '2008-06-03 15:02:02', 'Paul', 'Hillsman', 'M', '1964-12-18', 'Charleston', 'SC', 'paulpic.jpg');
INSERT INTO `mismatch_user` VALUES (11, '2008-06-03 15:02:13', 'Johan', 'Nettles', 'M', '1981-11-03', 'Athens', 'GA', 'johanpic.jpg');