/*В начале файла напишите запросы типа INSERT для добавления в БД всех необходимых данных.*/

INSERT INTO categories (name, symbol_code) VALUES ('Доски и лыжи', 'boards');
INSERT INTO categories (name, symbol_code) VALUES ('Крепления', 'attachment');
INSERT INTO categories (name, symbol_code) VALUES ('Ботинки', 'boots');
INSERT INTO categories (name, symbol_code) VALUES ('Одежда', 'clothing');
INSERT INTO categories (name, symbol_code) VALUES ('Инструменты', 'tools');
INSERT INTO categories (name, symbol_code) VALUES ('Разное', 'others');

INSERT INTO users email = 'user1@gmail.com', date_registration = now(), name = 'user1', password = '4terg4', contacts = '+79435345653';
INSERT INTO users email = 'user2@gmail.com', date_registration = now(), name = 'user2', password = '5tgjl6', contacts = '+73458654334';

INSERT INTO lots set user_id = 1, category_id = 1, date_create = now(), name = '2014 Rossignol District Snowboard', image = 'img/lot-1', start_price = 10999, step_rate = 1000;
INSERT INTO lots set user_id = 1, category_id = 1, date_create = now(), name = 'DC Ply Mens 2016/2017 Snowboard', image = 'img/lot-2', start_price = 159999, step_rate = 1000;
INSERT INTO lots set user_id = 1, category_id = 2, date_create = now(), name = 'Крепления Union Contact Pro 2015 года размер L/XL', image = 'img/lot-3', start_price = 8000, step_rate = 1000;
INSERT INTO lots set user_id = 2, category_id = 3, date_create = now(), name = 'Ботинки для сноуборда DC Mutiny Charocal', image = 'img/lot-4', start_price = 10999, step_rate = 1000;
INSERT INTO lots set user_id = 2, category_id = 4, date_create = now(), name = 'Куртка для сноуборда DC Mutiny Charocal', image = 'img/lot-5', start_price = 7500, step_rate = 500;
INSERT INTO lots set user_id = 2, category_id = 6, date_create = now(), name = 'Маска Oakley Canopy', image = 'img/lot-5', start_price = 398, step_rate = 100;

INSERT INTO rates set user_id = 1, lot_id = 1, date_create = now(), price = 11999 ;
INSERT INTO rates set user_id = 2, lot_id = 1, date_create = now(), price = 12999 ;



/*Ниже этом файле напишите SQL-код всех запросов на выборку данных, каждый с новой строчки.*/

/*получить все категории;*/
SELECT * FROM categories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;*/
SELECT id, NAME, start_price, image, category_id FROM lots l WHERE DATE(date_create) = CURRENT_DATE()  ;

/*показать лот по его id. Получите также название категории, к которой принадлежит лот;*/
SELECT l.id, l.NAME, c.name  FROM lots l 
JOIN categories c ON l.category_id = c.id  
WHERE l.id = 6; 

/*обновить название лота по его идентификатору;*/
UPDATE lots SET name = '2015 Rossignol District Snowboard' WHERE id = 1

/*получить список самых свежих ставок для лота по его идентификатору.*/
 SELECT l.id, l.name, r.price, u.NAME, r.date_create   FROM users u 
 INNER JOIN rates r ON u.id = r.user_id 
 INNER JOIN lots l ON l.id = r.lot_id   
 WHERE DATEDIFF(now(), r.date_create) < 10 AND l.id = 1; 