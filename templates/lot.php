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
 <section class="lot-item container">
      <h2><?=htmlspecialchars($val['name'])?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$val['image']?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?=$val['category']?></span></p>
          <p class="lot-item__description"><?=htmlspecialchars($val['description'])?></p>
        </div>		
		<div class="lot-item__right">
          <div class="lot-item__state"> 
            <?php
               $classname = ($time_to_finish['feature_finish'])  ? "timer--finishing" : "";
               $type_of_price = (count($lot_rates) > 0) ? "текущая цена" : "стартовая цена"	;
               $time_output =  ($time_to_finish['is_time_to_finish']) ? $time_to_finish['finish_time'] : "Аукцион завершен";
            ?>		  
            <div class="lot-item__timer timer <?=$classname;?>"> 
			   <?=$time_output;?>	
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount"><?=$type_of_price?></span>
                <span class="lot-item__cost"><?=htmlspecialchars($current_price)?><?=$add_ruble?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=htmlspecialchars($min_price)?><?=$add_ruble?></span>
              </div>
            </div>
		    <?php
             if (isset($_SESSION['username']) and  $time_to_finish['is_time_to_finish'] 
			    and (int)$val['user_id'] !== (int)$_SESSION['user_id'] and (int)$val['user_winner_id'] !== (int)$_SESSION['user_id']) :
                $classname = (isset($errors['cost'])) ? "form__item--invalid" : "";
                $value_input = (isset($lot_rate['cost']))? $lot_rates['cost'] : "Введите вашу ставку ";
                $value_error = (isset($errors['cost']))? $errors['cost'] : "Введите вашу ставку";
             ?>           
		        <form class="lot-item__form <?= $classname ?>" action="lot.php" method="post" autocomplete="off">
                  <p class="lot-item__form-item form__item >
                    <label for="cost">Ваша ставка</label>
                    <input id="cost" type="text" name="cost" placeholder="введите ставку">
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
				<td class="history__name"><?= htmlspecialchars($val['price'])?></td>
                <td class="history__time"> <?=get_date_string($val['date_create'])?></td>              		   
			 </tr>
			 <?php endforeach; ?>	 
             </table>
          </div>
        </div>		
      </div>
    </section>