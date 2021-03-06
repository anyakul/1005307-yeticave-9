    <nav class="nav">
       <ul class="nav__list container">
	    <?php foreach ($categories as $val):
            $classname =($h2_text === $val['name']) ? "nav__item--current" : ""; ?>	 
            <li class="nav__item <?=$classname ?>" >
			<?php $goto_category_id = "all-lots.php?id=" . $val['id']?>
            <a href=<?=$goto_category_id?>><?=$val['name']?></a>
            </li>
		<?php endforeach; ?> 
       </ul>
    </nav>   
    <div class="container">
      <section class="lots">
	    <?php
        $h2_output_text = (count($lots) >0) ? "Все лоты в категории:   " :"нет лотов в категории:  ";
        ?>
        <h2><?=$h2_output_text?><span><?=$h2_text?></span></h2> 
        <ul class="lots__list">
		<?php for ($i=0; $i < count($lots); $i++) : ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=$lots[$i]['image']?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=$lots[$i]['category']?></span>
			  <?php
              $lot_id = $lots[$i]['id']; $go_to_lot = "lot.php?id=" . "$lot_id";
              ?>
              <h3 class="lot__title"><a class="text-link" href=<?=$go_to_lot ?>><?=htmlspecialchars($lots[$i]['name'])?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                 <?php
                  $output_text = ((int)$lots[$i]['user_winner_id'] !==  0) ? (string)$lots[$i]['count_rates'] . ' ' . get_noun_plural_form(
                                  $lots[$i]['count_rates'], 'ставка', 'ставки', 'ставок') : "стартовая цена";
                  $output_price = ((int)$lots[$i]['user_winner_id'] ===  0) ? $lots[$i]['start_price'] : $lots[$i]['current_price'];
                  ?>
                  <span class="lot__amount"><?=$output_text?></span>
                  <span class="lot__cost"><?=htmlspecialchars($output_price)?><b class="rub">р</b></span>
                </div>
				<?php
                $classname = ($time_to_end[$i]['feature_finish']) ? "timer--finishing" : "";
                ?>
                <div class="lot__timer timer <?=$classname?>">
                    <?=$time_to_end[$i]['finish_time']?>
                </div>
              </div>
            </div>
          </li>
		 <?php endfor; ?>  
        </ul>      
      </section>
	  <?php if ($pages_count > 1) :?>
      <ul class="pagination-list">	   
        <li class="pagination-item pagination-item-prev">
		    <a <?php $page = $cur_page -1; $goto_to_page = "all-lots.php?page=" . "$page"; if ($cur_page>1) :?> href=<?=$goto_to_page?><?endif;?>>Назад</a>
		</li>
		<?php foreach ($pages as $page):
        $goto_to_page = "all-lots.php?page=" . "$page";	?>
        <li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif;?>">
		    <a href=<?=$goto_to_page?>><?=$page?></a>
		</li>
        <?php endforeach;?> 
        <li class="pagination-item pagination-item-next">
		<?php $page = $cur_page +1; $goto_to_page = "all-lots.php?page=" . "$page"; if ($cur_page < $pages_count) :?>
		    <a  href=<?=$goto_to_page?>><?endif;?>Вперед</a>
		</li>
      </ul>
	  <?php endif; ?>
    </div>
  