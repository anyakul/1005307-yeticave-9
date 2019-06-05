<?php session_start();

    /* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
    	 получение массива категорий, блока вывода ошибок на экран */
    include('common_block.php');
    
    $page_items = 3;  // количество лотов на одной странице
    
    if (isset($_GET['id'])) { // получен id запрошенной категории, который проверяется и сохраняется
        $_SESSION['category_id'] = addslashes($_GET['id']);
        $category_id = $_SESSION['category_id'];
         
        // получаем из базы данных количество открытых лотов в данной категории и вычисляем количество страниц для пагинации
        $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM lots l JOIN categories c ON l.category_id = c.id  
	  			                WHERE  (c.id = ' $category_id')  and (l.date_finish > NOW())");
        $items_count = mysqli_fetch_assoc($result)['cnt'];
        $pages_count = ceil($items_count/$page_items);
        $_SESSION['pages_count'] = $pages_count;  // сохраняем количество страниц
    } else {	// если сценарий вызывается повторно из данного сценария, то воостанавливаем текущие значения категории и количества страниц в пагинации
        $category_id = $_SESSION['category_id'];
        $pages_count = $_SESSION['pages_count'];
    }
    // формируем переменную часть ззаписи в заголовке страницы
    foreach ($categories as $category) {
        if ($category['id'] === $category_id) {
            $h2_text = $category['name'];
        }
    }
    
    $pages = range(1, $pages_count);
        
    if (isset($_GET['page'])) {
        // защита против SQL - инъекций
        $cur_page = addslashes($_GET['page']);
    } else { // при первом входе устанавливаем текущую страницу в 1
        $cur_page =1;
    }
    
    // определяем текущее смещение в таблице
    $offset = ($cur_page - 1) * $page_items;
    
    // выбираем из базы  данных данные для лота с требуемым id очередную порцию лотов
    $sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id, l.user_winner_id FROM lots l JOIN categories c ON l.category_id = c.id  
	  			WHERE  (c.id = ' $category_id')  and (l.date_finish > NOW()) LIMIT  $page_items   OFFSET  $offset  ";
    $res_l = mysqli_query($con, $sql);
    $lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
  
        
    //   записываем время до завершения торгов в массив
    $time_to_end = [];
    if (count($lots) > 0) {
        for ($i=0; $i < count($lots); $i++) {
            $time_to_end[$i] = time_to_finish($lots[$i]['date_finish']);
        }
    }
    
    $page_content = include_template('all-lots.php', [ 'lots' =>$lots,  'h2_text' => $h2_text,  'time_to_end' => $time_to_end,
                                        'pages_count' => $pages_count, 'cur_page' => $cur_page, 'pages' => $pages]);
     
    $layout_content = include_template('layout.php', ['content' => $page_content, 'categories'=> $categories,
                                       'title' => 'YetiCave - Результаты поиска','user_name' => $user_name]);
    print($layout_content);
        
?>      