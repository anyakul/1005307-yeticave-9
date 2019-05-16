 <section class="lot-item container">
      <h2><?=$val['name']?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$val['image']?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?=$val['category']?></span></p>
          <p class="lot-item__description"><?=$val['description']?></p>
        </div>		
		<div class="lot-item__right">
          <div class="lot-item__state"> 
            <?php $classname = ($time_to_finish['feature_finish'])  ? "timer--finishing" : "";
			      $type_price = (count($lot_rates) > 0) ? "текущая цена" : "стартовая цена"	;			   	 	            
		          $time_output =  ($time_to_finish['is_time_to_finish']) ? $time_to_finish['finish_time'] : "Нет торгов"; 
				  ?>		  
            <div class="lot-item__timer timer <?=$classname;?>"> 
			   <?=$time_output;?>	
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount"><?=$type_price?></span>
                <span class="lot-item__cost"><?=$current_price?><?=$add_ruble?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=$min_price?><?=$add_ruble?></span>
              </div>
            </div>
		 <?php session_start();		       
	  	       if (isset($_SESSION['username']) and  $time_to_finish['is_time_to_finish']  ) :    
 	           $classname = (isset($errors['cost'])) ? "form__item--invalid" : "";		 
               $value_input = (isset($lot_rate['cost']))? $lot_rates['cost'] : "Введите вашу ставку ";
               $value_error = (isset($errors['cost']))? $errors['cost'] : "Введите вашу ставку";   ?>           
		   <form class="lot-item__form <?= $classname ?>" action="lot.php" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item >
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?=$value_input?>">
                <span class="form__error"><?=$value_error?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
           <?php endif; ?>			
          </div>
          <div class="history">
            <h3>История ставок  (<span><?=count($lot_rates) ?></span>) </h3>
             <table class="history__list">	     
  			  <?php  foreach ($lot_rates as $val): ?>
			  <tr class="history__item">
                <td class="history__price"><?=htmlspecialchars($val['user_name'])?></td>               
				<td class="history__name"><?=htmlspecialchars( $val['price'])?></td>
                <td class="history__time"><?=htmlspecialchars($val['date_create'])?></td>              		   
			 </tr>
			 <?php endforeach; ?>	 
             </table>
          </div>
        </div>
		
      </div>
    </section>