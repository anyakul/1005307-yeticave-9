 
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
	    <?php 	   
	    foreach($my_bets as $val) :
		  $time_to_finish = time_to_finish($val['lot_date_finish']);		 
	 	  $classname_is = ($time_to_finish['is_time_to_finish']) ? "" : "rates__item--end";
		  $time_output =  ($time_to_finish['is_time_to_finish']) ? $time_to_finish['finish_time'] : "торги завершены";          
          $classname = ($time_to_finish['feature_finish'])? "timer--finishing" : "";       
		?>
        <tr class="rates__item <?=$classname?> <?=$classname_is?> ">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$val['lot_image']?>" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="<?=$val['page_adress']?>"><?=$val['lot_name']?></a></h3>
          </td>
          <td class="rates__category">
            <?=$val['category_id']?>
          </td>
		
          <td class="rates__timer">
            <div class="timer "> <?=$time_output?></div>
          </td>
          <td class="rates__price">
            <?=$val['price']?>
          </td>
          <?php $date_create = $val['date_create'];
		    $time_to_second = time() - strtotime($date_create);
		//	var_dump($time_to_second);
		    $day = floor($time_to_second / 3600/24);
		//	var_dump($day);			
			if($day > 1) { $date_string =(string)$day .' ' .   get_noun_plural_form ($day, 'день назад', 'дня назад', 'дней назад');
			}
			else {
				$hours = floor($time_to_second /3600);
		//	    var_dump($hours);
				if($hours >=1){$date_string = (string)$hours .' ' . get_noun_plural_form ($hours, 'час назад', 'часа назад', 'часов назад');
				} 		                  
				else {$minutes = floor($time_to_second /60);
		//	        var_dump($minutes); 
					
					if($minutes >=1){$date_string =(string)$minutes .' ' .   get_noun_plural_form ($minutes, 'минута назад', 'минуты назад',
  						  'минут назад');
				    }
					else {$date_string = 'только что'; var_dump($date_string);}
				}
			}	
           ?>			
          <td class="rates__time">		   
           <?=$date_string?>
          </td>
        </tr>
		<?php endforeach?>   
      </table>
    </section>
  