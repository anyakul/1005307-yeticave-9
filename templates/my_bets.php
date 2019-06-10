    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories as $category): ?> 
            <li class="nav__item">
			<?php $goto_category_id = "all-lots.php?id=" . $category['id']?>
            <a href=<?=$goto_category_id?>><?=$category['name']?></a>
            </li>
		<?php endforeach; ?>		       
      </ul>
    </nav> 
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
	    <?php
        foreach ($my_bets as $val) :
          $time_to_finish = time_to_finish($val['lot_date_finish']);
          $classname_end = ($time_to_finish['is_time_to_finish']) ? "" : "rates__item--end";  // определяем класс для ставок завершившегося лота
          $time_output =  ($time_to_finish['is_time_to_finish']) ? $time_to_finish['finish_time'] : "торги окончены";  // меняем значение поля времени для завершившихся лотов
          $classname_finishing = ($time_to_finish['feature_finish'])? "timer--finishing" : ""; // определяем класс для лотов с сроком жизни меньше 1 часа
          if ($val['mark_win']) : $classname_end = "rates__item--win";    endif;                    //  меняем класс  "rates__item--end" на класс "rates__item--win" для выигравшей ставки
          $classname_win_td = ($val['mark_win'])	? "timer--win" : "";                               //  определяем класс "timer--win" для поля с временем  для строки выигравшей ставки
          if ($val['mark_win']) : $time_output = "Ставка выиграла"; endif;                          //  меняем поле времени на "Ставка выиграла" для выигравшей ставки
        ?>
        <tr class="rates__item  <?=$classname_end?>  ">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$val['lot_image']?>" width="54" height="40" alt="Сноуборд">
            </div>
			<div>
               <h3 class="rates__title"><a href="<?=$val['page_adress']?>"><?=htmlspecialchars($val['lot_name'])?></a></h3>
			   <?php if ($val['mark_win']) :?><p><?=htmlspecialchars($val['user_contacts'])?> </p><?endif?>
			</div>
          </td>
          <td class="rates__category">
            <?=$val['category']?>
          </td>
		
          <td class="rates__timer">
            <div class="timer <?=$classname_finishing?> <?=$classname_win_td?>"> <?=$time_output?></div>
          </td>
          <td class="rates__price">
            <?=htmlspecialchars($val['price'])?>
          </td>          
          <td class="rates__time">		   
           <?=get_date_string($val['date_create'])?>
          </td>
        </tr>
		<?php endforeach?>   
      </table>
    </section>
  