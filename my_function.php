<?php
     //  функция форматирования цены
	
    function format_price($var) {               
		      return number_format(ceil($var), 0, ' ', ' ') ;
	}
		 
	// функция определения времени c текущего момента  до полуночи
	// кроме определения времени сохраняет признакБ что время до полуночи осталось меньше или равно 1 часа
	
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
	 
	// вычисляет время, оставшееся до определенного времении (finish_time)
	// возвращает два признака: 1) осталось время или вышло (is_time_to_finish)
    // 2) осталось менее или равно одного часа (feature_finish)
	
 	function time_to_finish($date_finish) {			
		   $time_to_finish = [
		     'finish_time' => '',
			 'feature_finish' => false,
			 'is_time_to_finish' => true
			];			
            $secs_to_finish = strtotime($date_finish) - time();		 
            if ( $secs_to_finish < 0) {
				$time_to_finish['is_time_to_finish'] = false;
				return $time_to_finish;
			}			
			
	        // определяем количество часов и переводим в строку	
            $hours = floor($secs_to_finish / 3600);
			$hours_str=(string)$hours ;
			
	        // вычисляем количество минут и переволим в строку 	
            $minutes = floor(($secs_to_finish % 3600) / 60); 
			$minutes_str=(string)$minutes ;		
			$time_to_finish['finish_time'] = str_pad($hours_str, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes_str, 2, "0", STR_PAD_LEFT );
			
		    // записываем признак, что осталось меньше или равно одного часа			
		    if ($hours  <  1 or $hours === 1 and $minutes === 0) {
				$time_to_finish['feature_finish'] = true;  
			}			
		    return $time_to_finish;
	} 
	
	//  возвращает ассоциативный массив, считанный из базы данных с меткой $con	 
	
	function get_categories($con) {
        $sql = "SELECT * FROM categories";
        $res_c = mysqli_query($con, $sql);
    	return mysqli_fetch_all($res_c, MYSQLI_ASSOC);	
	}
			
			
			
	/* Функция возвращает время в 'человеческом' формате  до одного часа возвращает количество минут назад, например, "5 минут назад" 
    	больше одного час до 5 минут возвращает "час назад", далее дату в формате вида "28.05.19 в 10:29"  */
		
	function get_date_string($date) {
		$date_string = '';
		
	    $time_to_second = time() - strtotime($date);		
		if ( $time_to_second < 60) {
			$date_string = 'только что'; 
		}
		else {
			if ( $time_to_second <  3600 ) {
				$minutes = floor($time_to_second/60); 
				 $date_string = (string)$minutes .' ' .   get_noun_plural_form ($minutes, 'минута', 'минуты ','минут');
			}
			else { 
			    if ($time_to_second <  3840 ) {
					$date_string = '1' . ' ' . 'час назад';
				}
				else {
					$date = date_create($date);
				    $date_string = date_format($date, "d.m.y в h:i");
				}			    
			}	 
	    } 
		
		return $date_string;
    }		
?>