<?php
session_start();
         
/* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
   получение массива категорий, блока вывода ошибок на экран */
include('common_block.php');
        
if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($con, $_GET['user_id']);
} else {
    $user_id = $_SESSION['user_id'];
}
// получаем данные по моим ставкам из базы данных и записываем в массив $my_bets
$sql = "SELECT   l.page_adress page_adress, l.image lot_image, l.name lot_name, l.date_finish lot_date_finish, l.user_id user_id, l.category_id category,
		        l.user_winner_id user_winner_id, l.current_price current_price, r.price, r.date_create FROM  rates r JOIN lots l ON r.lot_id = l.id 
				WHERE r.user_id  = $user_id ORDER BY r.date_create DESC";
$res = mysqli_query($con, $sql);
$my_bets = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
// заменяем id категории на название категории и добавляем в массив $my_bets данные о наличии выигрыша по ставке и контатные данные владельца лота с выигравшей ставкой
for ($i=0; $i < count($my_bets); $i++) {
    $category_id = $my_bets[$i]['category'];
    foreach ($categories as $category) {
        if ($category['id'] === $category_id) {
            $my_bets[$i]['category'] = $category['name'];
        }
    }
            
    $my_bets[$i]['mark_win'] = false;
    $my_bets[$i]['user_contacts'] = '';
    if ($_SESSION['user_id'] === -$my_bets[$i]['user_winner_id'] and $my_bets[$i]['current_price'] === $my_bets[$i]['price']) {
        $my_bets[$i]['mark_win'] = true;
        $user_id = $my_bets[$i]['user_id'];
        $res = mysqli_query($con, "SELECT * FROM users WHERE id = $user_id");
        $user_lot = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $my_bets[$i]['user_contacts'] = $user_lot[0]['contacts'];
    }
}
         
$page_content = include_template('my_bets.php', [ 'categories' => $categories, 'my_bets' => $my_bets ]);
$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - мои ставки', 'user_name' => $user_name]
);
print($layout_content);
