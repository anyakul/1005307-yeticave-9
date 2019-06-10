<?php
session_start();
/* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
       получение массива категорий, блока вывода ошибок на экран */
include('common_block.php');
     
$page_items = 3;  // количество лотов на одной странице
        
if (isset($_GET['search'])) {
  
    // некоторая защита от SQL-инъекций
    $_SESSION['search'] =  trim(mysqli_real_escape_string($con, $_GET['search']));
    $word_search =  $_SESSION['search'];
       
    // получаем из базы данных количество открытых лотов для данного поиска и вычисляем количество страниц для пагинации
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM lots l JOIN categories c ON l.category_id = c.id  
	  			WHERE   (MATCH(l.name, l.description)  AGAINST( '$word_search' ))  and  (l.date_finish > NOW())");
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count/$page_items);
    $_SESSION['pages_count'] = $pages_count;  // сохрраняем количество страниц
} else {	// если сценарий вызывается повторно, то воостанавливаем текущее значения поиска и количества страниц в пагинации
        $word_search = $_SESSION['search'] ;
    $pages_count = $_SESSION['pages_count'];
}
    
$pages = range(1, $pages_count);
        
if (isset($_GET['page'])) {	// вызвана страница с номером page
    $cur_page = mysqli_real_escape_string($con, $_GET['page']);
} else {
    $cur_page =1;  // при первом входе в сценарий устанавливаем текущую страницу в 1
}
    
// определяем текущее смещение в таблице
$offset = ($cur_page - 1) * $page_items;
    
// выбираем  из таблицы lots  записи, удовлетворяющих условию поиска
$sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
 	        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id, l.user_winner_id, l.count_rates  FROM lots l JOIN categories c ON l.category_id = c.id  
   			WHERE  ( MATCH(l.name, l.description)  AGAINST( '$word_search')) and (l.date_finish > NOW()) LIMIT  $page_items   OFFSET  $offset";
$res_l = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
    
//   записываем время до завершения торгов по лотам в массив
$time_to_end = [];
if (count($lots) > 0) {
    for ($i=0; $i < count($lots); $i++) {
        $time_to_end[$i] = time_to_finish($lots[$i]['date_finish']);
    }
}
    
$page_content = include_template('search.php', [ 'lots' =>$lots,  'h2_text' => $word_search, 'categories'=> $categories, 'time_to_end' => $time_to_end,
                                              'pages_count' => $pages_count, 'cur_page' => $cur_page, 'pages' => $pages]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'categories'=> $categories,
                                       'title' => 'YetiCave - Результаты поиска', 'user_name' => $user_name]);
print($layout_content);
