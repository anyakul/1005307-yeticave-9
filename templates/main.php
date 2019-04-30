 <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
			
			
            <!--заполняем этот список из массива категорий-->
			
				<?php foreach ($categories as  $val): ?>
               <li class="promo__item promo__item--<?=($val['symbol_code'])?>">
                  <a class="promo__link" href="pages/all-lots.html"><?=htmlspecialchars($val['name'])?></a>
               </li>
			  <?php endforeach; ?>
			 
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
		<ul class="lots__list">	
        
            <!--заполняем этот список из массива с товарами-->	

		<?php	foreach ($lots as $val): ?>		 
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$val['image']?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($val['category'])?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($val['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=format_price(htmlspecialchars($val['start_price']))?><b class="rub">₽</b>  </span>
                        </div>						 
                        <div class="lot__timer timer <?php if ($time_to_finish['feature_finish'] === true):?>timer--finishing<?php endif; ?>">
                          <?=$time_to_finish['finish_time'];?> 
						</div>
                    </div>
                </div>
            </li>
			<?php endforeach; ?>
        </ul>
    </section>