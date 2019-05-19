   <h1> Поздравляем с победой </h1>
   <p> Здравстуйте, <?=$val['user_name'] ?> </p>
    
   <p> Ваша ставка для лота <a href=<?=$val['page_adress']?>><?=$val['name']?></a> победила. </p>
   <?php $winner_id =$val['user_vinner_id'];  $goto_my_bets = "my_bets.php?user_id =" . " $winner_id"; ?>
   <p> Перейдите по ссылке <a  href = <?=$goto_to_my_bets?>> мои ставки </a> чтобы связаться с автором объявления </p>
   <small> ""ИНТЕРНЕТ АУКЦИОН "YetiCave"</small>
   