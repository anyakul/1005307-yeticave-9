<?php
$is_auth = rand(0, 1);
$user_name = "Аня Куликова"; // укажите здесь ваше имя
 
 
 // Создаем  массивы 

   $con = mysqli_connect("localhost", "root", "", "yeticave");
				if ($con == false) {
					print("Ошибка подключения: " . mysqli_connect_error());
				}
				else {
					print("Соединение установлено");
					// выполнение запросов
				}
				mysqli_set_charset($con, "utf8");
				
				$sql = "SELECT * FROM categories";
				$res_c = mysqli_query($con, $sql);
				$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);	
				
				$sql = 'SELECT c.NAME category, l.image, l.name, l.start_price FROM lots l JOIN categories c ON c.id = l.category_id WHERE DATE(date_create) = CURRENT_DATE()';
				$res_l = mysqli_query($con, $sql);
				$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);	
 	 		foreach($lots as $val): print ($val['l.name']);    endforeach;  
			 		    

   
	//  Добавляем функцию форматирования цены
	
     function format_price($var) {               
		      return number_format(ceil($var), 0, ' ', ' ') ;
	     }
	// функция определения времени жизни лота до полуночи
	
	 function time_lot() {			
		   $time_to_midnight = [
		   'finish_time' => '',
			'feature_finish' => false
			];			
            $secs_to_midnight = strtotime('tomorrow') - time();	
			
	        // определяем количество часов и переводим в строку	
            $hours = floor($secs_to_midnight / 3600);
			$hours_str=(string)$hours ;
			
	        // вычисляем количество минут и переволим в строку 	
            $minutes = floor(($secs_to_midnight % 3600) / 60); 
			$minutes_str=(string)$minutes ;		
			$time_to_midnight['finish_time'] = str_pad($hours_str, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes_str, 2, "0", STR_PAD_LEFT );
			
		    // записываем признак, что осталось меньше или равно одного часа			
		    if ($hours  <  1 or $hours === 1 and $minutes === 0) : $time_to_midnight['feature_finish'] = true; endif;
			
		    return $time_to_midnight;
	 }
	 
	// добавляем функции из helper  

     require('helpers.php');

    // форматируем оставшееся время до завершения
	
	 $time_to_finish  = time_lot();
	 
	// HTML код главной страницы
	
     $page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots, 'time_to_finish' => $time_to_finish] );
     
    
	// окончательный HTML код
	
	 $layout_content = include_template('layout.php',
            ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
	  print($layout_content);
?>
   