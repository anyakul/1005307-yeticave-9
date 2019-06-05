/*В начале файла напишите запросы типа INSERT для добавления в БД всех необходимых данных.*/

INSERT INTO categories (name, symbol_code) VALUES ('Доски и лыжи', 'boards');
INSERT INTO categories (name, symbol_code) VALUES ('Крепления', 'attachment');
INSERT INTO categories (name, symbol_code) VALUES ('Ботинки', 'boots');
INSERT INTO categories (name, symbol_code) VALUES ('Одежда', 'clothing');
INSERT INTO categories (name, symbol_code) VALUES ('Инструменты', 'tools');
INSERT INTO categories (name, symbol_code) VALUES ('Разное', 'others');

 
INSERT INTO users set email = 'user1@gmail.com', date_registration = now(), name = 'user1', password = '4terg4', contacts = '+79435345653';
INSERT INTO users set email = 'user2@gmail.com', date_registration = now(), name = 'user2', password = '5tgjl6', contacts = '+73458654334';

INSERT INTO lots set user_id = 1, category_id = 1, date_create = now(), name = '2014 Rossignol District Snowboard', image = 'img/lot-1.jpg', start_price = 10999, 
                     step_rate = 1000, current_price = 12999, user_winner_id = 2, count_rates = 2, page_adress = 'lot.php?id=1', 
                     description = 'Тяжелый маневренный сноуборд, готовый дать жару в любом парке, растопив', date_finish = '2019.06.09';
INSERT INTO lots set user_id = 1, category_id = 1, date_create = now(), name = 'DC Ply Mens 2016/2017 Snowboard', image = 'img/lot-2.jpg', start_price = 159999, step_rate = 1000, 
                     current_price = 159999, user_winner_id = 0, count_rates = 0, page_adress = 'lot.php?id=2', 
					 description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и мощными дугами', date_finish = '2019.06.09' ;
INSERT INTO lots set user_id = 1, category_id = 2, date_create = now(), name = 'Крепления Union Contact Pro 2015 года размер L/XL', image = 'img/lot-3.jpg', start_price = 8000, 
                     step_rate = 1000, current_price = 8000, user_winner_id = 0, count_rates = 0, page_adress = 'lot.php?id=3', 
                     description = 'качественное крепление от ведущих производитетлей', date_finish = '2019.06.09';
INSERT INTO lots set user_id = 2, category_id = 3, date_create = now(), name = 'Ботинки для сноуборда DC Mutiny Charocal', image = 'img/lot-4.jpg', start_price = 10999, step_rate = 1000, current_price = 10999, user_winner_id = 0, count_rates = 0, page_adress = 'lot.php?id=4',
                     description = 'в хорошем состоянии', date_finish = '2019.06.09' ;
INSERT INTO lots set user_id = 2, category_id = 4, date_create = now(), name = 'Куртка для сноуборда DC Mutiny Charocal', image = 'img/lot-5.jpg', start_price = 7500, step_rate = 500,current_price = 7500, user_winner_id = 0, count_rates = 0, page_adress = 'lot.php?id=5',
                     description = 'размер 50', date_finish = '2019.06.09' ;
INSERT INTO lots set user_id = 2, category_id = 6, date_create = now(), name = 'Маска Oakley Canopy', image = 'img/lot-6.jpg', start_price = 398, step_rate = 100, current_price = 398, user_winner_id = 0, count_rates = 0, page_adress = 'lot.php?id=6',
                     description = 'желтая', date_finish = '2019.06.09' ;
 
INSERT INTO rates set user_id = 1, lot_id = 1, date_create = now(), price = 11999 ;
INSERT INTO rates set user_id = 2, lot_id = 1, date_create = now(), price = 12999 ;

 

 
 