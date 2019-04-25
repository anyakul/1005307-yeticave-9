<?php
$is_auth = rand(0, 1);
$user_name = "Аня Куликова"; // укажите здесь ваше имя
 
 
 // Создаем  массивы    
     
	    $categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"]; 
		  
 		$goods = [ 
		   [
		       'name' => '2014 Rossignol District Snowboard',
		       'category' => $categories[0],
		       'price' => 10999,
		       'url' => 'img/lot-1.jpg'
		    ],
 		    [
		       'name' => 'DC Ply Mens 2016/2017 Snowboard',
		       'category' => $categories[0],
		       'price' => 159999,
		       'url' => 'img/lot-2.jpg'
		    ],
		    [
		       'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
		       'category' => $categories[1],
		       'price' => 8000,
		       'url' => 'img/lot-3.jpg'
		    ],
		    [
		       'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
		       'category' => $categories[2],
		       'price' => 10999,
		       'url' => 'img/lot-4.jpg'
		    ],
		    [
		       'name' => 'Куртка для сноуборда DC Mutiny Charocal',
		       'category' => $categories[3],
		       'price' => 7500,
		       'url' => 'img/lot-5.jpg'
		    ],
		    [
		       'name' => 'Маска Oakley Canopy',
		       'category' => $categories[5],
		       'price' => 397.5,
		       'url' => 'img/lot-6.jpg'
		    ] 
		];
  		
   
	//  Добавляем функцию форматирования цены
	
     function format_price($var) {               
		      return number_format(ceil($var), 0, ' ', ' ') ;
	     }
	// функция определения времени жизни лота до полуночи
	
	 function time_lot() {			
		    $time_to_midnight = ['finish_time' => '',
			'feature_finish' => false
			];			
            $secs_to_midnight = strtotime('tomorrow') - time();	
			
	        // определяем количество часов и переводим в строку	
            $hours = floor($secs_to_midnight / 3600);
			$hours_str=(string)$hours ;
			
	        // вычисляем количество минут и переволим в строку 	
            $minutes = floor(($secs_to_midnight % 3600) / 60); 
			$minutes_str=(string)$minutes ;
			
	        //	добавляем  0, если количество минут или часов меньше 10			
			if ($minutes < 10): $minutes_str='0' .$minutes_str; endif;			    
			if ($hours < 10) : $hours_str='0' .$hours_str; endif;
			
	        // записываем время в формате ЧЧ:ММ				
		    $time_to_midnight['finish_time'] =  $hours_str .':' .$minutes_str;

            // записываем признак, что осталось меньше или равно одного часа			
		    if ($hours  <  1 or $hours === 1 and $minutes === 0) : $time_to_midnight['feature_finish'] = true; endif;
			
		    return $time_to_midnight;
	 }
	 
	// добавляем функции из helper  

     require('helpers.php');

    // форматируем оставшееся время до завершения
	
	 $time_to_finish  = time_lot();
	 
	// HTML код главной страницы
	
     $page_content = include_template('main.php', ['categories' => $categories, 'goods' => $goods, 'time_to_finish' => $time_to_finish] );
     
    
	// окончательный HTML код
	
	 $layout_content = include_template('layout.php',
            ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
	  print($layout_content);
?>
   