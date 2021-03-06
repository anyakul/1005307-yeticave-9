<?php
session_start();
/* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
    	 получение массива категорий, блока вывода ошибок на экран */
include('common_block.php');
    
// подключаем сценарий определения победителя по лотам с истекшими датами и отправки сообщения на почту победителю
include('getwinner.php');
    
// выбираем из базы данных все открытые лоты
$sql = "SELECT c.name category, l.image, l.name, l.user_id, l.user_winner_id, l.page_adress, l.start_price, l.date_finish, l.current_price, l.count_rates  FROM lots l JOIN categories c ON l.category_id = c.id
            WHERE l.date_finish > NOW()	ORDER BY l.date_finish";
$res_l = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
    
// создаем   массив  $time_to_end  показывает время до окончания торгов по лоту
$time_to_end = [];
if (count($lots) > 0) {
    for ($i=0; $i < count($lots); $i++) {
        $time_to_end[$i] = time_to_finish($lots[$i]['date_finish']);  // записано оставшееся время торгов по лоту
    }
}
       
// подключаем шаблон главной страницы
$page_content = include_template('main.php', ['categories' => $categories, 'lots' => $lots,  'time_to_end' => $time_to_end]);
    
// подключаем шаблон layout для показа основной страницы сайта
$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name]
);
print($layout_content);
