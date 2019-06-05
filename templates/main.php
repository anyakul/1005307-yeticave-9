    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">			
        <!--заполняем этот список из массива категорий-->			
		<?php foreach ($categories as  $val): ?>
            <li class="promo__item promo__item--<?=($val['symbol_code'])?>">
		    <?php $category_id = "all-lots.php?id=" . $val['id']?>             
                <a class="promo__link" href=<?=$category_id?>><?=htmlspecialchars($val['name'])?></a>
            </li>
		<?php endforeach; ?>			  
        </ul>
    </section>
    <section class="lots">
	     <?php $value_input = (count($lots) > 0)? "открытые лоты" : "открытых лотов нет"; ?>
        <div class="lots__header">
            <h2><?=$value_input?></h2>
        </div>
		<ul class="lots__list">        
            <!--заполняем этот список из массива с товарами-->
		<?php 
		for ($i=0; $i < count($lots); $i++) : ?>	 
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$lots[$i]['image']?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($lots[$i]['category'])?></span>
                    <h3 class="lot__title"><a class="text-link" href = "<?=$lots[$i]['page_adress']?>" ><?=htmlspecialchars($lots[$i]['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate"> 
						    <?php 							
							$output_text = ($lots[$i]['user_winner_id'] != 0) ? (string)$lots[$i]['count_rates'] . ' ' . get_noun_plural_form( $lots[$i]['count_rates'], 
							                'ставка', 'ставки', 'ставок') : "стартовая цена";
				            $output_price = ($lots[$i]['user_winner_id'] === 0) ? $lots[$i]['start_price'] : $lots[$i]['current_price'];						 
				            ?>
                            <span class="lot__amount"><?=$output_text?></span>
                            <span class="lot__cost"><?=format_price(htmlspecialchars( $output_price ))?><b class="rub">₽</b>  </span>
                        </div>						 
                        <div class="lot__timer timer <?php if ($time_to_end[$i]['feature_finish'] === true):?>timer--finishing<?php endif; ?>">
                           <?=$time_to_end[$i]['finish_time'];?> 
						</div>
                    </div>
                </div>
            </li>
	    <? endfor; ?> 
        </ul>
    </section>