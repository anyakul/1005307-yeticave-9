<?php
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
 	 function time_to_finish($date_finish) {			
		   $time_to_finish = [
		   'finish_time' => '',
			'feature_finish' => false,
			 'is_time_to_finish' => true
			];			
            $secs_to_finish = strtotime($date_finish) - time();
		// 	var_dump(strtotime($date_finish),time(),$secs_to_finish );
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
		    if ($hours  <  1 or $hours === 1 and $minutes === 0) : $time_to_finish['feature_finish'] = true; endif;
			
		    return $time_to_finish;
	 } 
	 
?>