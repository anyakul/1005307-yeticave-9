<?php    
    /**
	* Возвращает время в формате 'ЧЧ:ММ' оставшееся от текущего момента до некоторого времени $date_finish, а также два признака: 
	* 1) осталось менее или равно одного часа (feature_finish)
    * 2) осталось время или вышло (is_time_to_finish)
	*
    * Примеры использования: 
	* Пусть текущее время 2019-06-04 11:15:52
    * time_to_finish(2019-06-04 12:10:52) // $time_to_finish[ 'finish_time' => '00:55','feature_finish' => true,'is_time_to_finish' => true]
    * time_to_finish(2019-06-04 19:15:52) // $time_to_finish[ 'finish_time' => '08:00','feature_finish' => false,'is_time_to_finish' => true]
    * time_to_finish(2019-06-03 11:10:00) // $time_to_finish[ 'finish_time' => '','feature_finish' => false,'is_time_to_finish' => false] 
    * time_to_finish(2019-06-10 00:00:00) // $time_to_finish[ 'finish_time' => '132:44','feature_finish' => false,'is_time_to_finish' => true] 	
    *
    * @date_finish string  -  Дата в формате 'ГГГГ-ММ-ДД'
    *
    * @return ассоциативный массив $time_to_finish  
    */
    function time_to_finish($date_finish)
    {
        $time_to_finish = [
             'finish_time' => '',
             'feature_finish' => false,
             'is_time_to_finish' => true
            ];
        $secs_to_finish = strtotime($date_finish) - time();
        if ($secs_to_finish < 0) {
            $time_to_finish['is_time_to_finish'] = false;
            return $time_to_finish;
        }
            
        // определяем количество часов и переводим в строку
        $hours = floor($secs_to_finish / 3600);
        $hours_str=(string)$hours ;
            
        // вычисляем количество минут и переволим в строку
        $minutes = floor(($secs_to_finish % 3600) / 60);
        $minutes_str=(string)$minutes ;
        $time_to_finish['finish_time'] = str_pad($hours_str, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes_str, 2, "0", STR_PAD_LEFT);
            
        // записываем признак, что осталось меньше или равно одного часа
        if ($hours  <  1 or $hours === 1 and $minutes === 0) {
            $time_to_finish['feature_finish'] = true;
        }
        return $time_to_finish;
    }
    
        
    /**
    *  Возвращает ассоциативный массив данных всех полей из таблицы с именем $name_table   базы данных с меткой $con
    *
    * Примеры использования:
    * $categories = get_table($con, 'categories'); // ассоциативный массив $categories     
    *
    * @con array - ассоцативный мвссив установления связи с БД
    *
    * @return array - ассоциативный массив всех данных из таблицы БД 
    */
    function get_table($con, $name_table)
	{  	
        $sql = "SELECT * FROM $name_table";
        $res_c = mysqli_query($con, $sql);
        return mysqli_fetch_all($res_c, MYSQLI_ASSOC);
    }
            
            
            
     
    /**
    * Функция возвращает время, прошедшее от текущего времени  до некоторой даты  в 'человеческом' формате:
    * до одной минуты возвращает "только что",
    * от одной минутыБ но меньше часа возвращает количество минут назад, например, "5 минут назад",
    * от часа и больше часа до 5 минут возвращает "час назад", 
	* больше часа и пяти минут в формате вида "28.05.19 в 10:29".
	* В случае когда дата из будущего, возвращает пустую строку.	 
    *
	* Ограничения:
	* Функция предполагает, что входные данные заданы   в формате "ГГГГ-ММ-ДД" или с помощью функции NOW() при записи в БД. Другие форматы не проверены.
	*
    * Примеры использования:
	* Пусть текущее время: 2019-06-04 11:15:52.   
    * get_date_string('2019-06-04 11:15:58'); //"только что"
    * get_date_string('2019-06-04 11:17:55'); // "2 минуты назад"
    * get_date_string('2019-06-04 12:17:53'); // "час назад"
    * get_date_string('2019-06-04 14:17:53'); // "04.06.19 в 14:17"	 
	* get_date_string('2019-03-19');          //"19.03.19 в 00:00"
	* get_date_string('2019-07-15');          //"".
    *
    * @$date  - date в формате 'ГГГГ-ММ-ДД' или создано функцией NOW() при записи в БД
    *
    * @return  - string 
    */    
    function get_date_string($date)
    {
        $date_string = '';
        
        $time_to_second = time() - strtotime($date);
        if ($time_to_second >=  0 and $time_to_second < 60) {
            $date_string = 'только что';            		
        }  
        if ($time_to_second >=  60 and $time_to_second <  3600) {
            $minutes = floor($time_to_second/60);
            $date_string = (string)$minutes .' ' .   get_noun_plural_form($minutes, 'минута', 'минуты ', 'минут') . ' ' . 'назад';
	    }  
        if ($time_to_second >=  3600 and $time_to_second <  3900) {
            $date_string = '1' . ' ' . 'час назад';		    
        }  
            
        if ($time_to_second >= 3900) {
			$date = date_create($date);
            $date_string = date_format($date, "d.m.y в H:i");			 
		} 
        return $date_string;
    }
	
	/**
    * возвращает  цену товара в виде целого количества рублей 
    *     
    */
    
    function format_price($var)
    {
        return number_format(ceil($var), 0, ' ', ' ') ;
    }
