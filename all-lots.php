<?php session_start(); 

    // устанавливаем соединение с базой данных и Создаем  массив категорий 
    $con = mysqli_connect("localhost", "root", "", "yeticave");
				//if ($con == false) {
				//	print("Ошибка подключения: " . mysqli_connect_error());
				//}
				//else {
				//	print("Соединение установлено");
				//}				
				mysqli_set_charset($con, "utf8");
				
				// получаем из базы данных массив категорий
				$sql = "SELECT * FROM categories";
				$res_c = mysqli_query($con, $sql);
				$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);
	
	
        //  Добавляем мои функции
	
    require('my_function.php'); 
 	 
	// добавляем функции из helper  
    require('helpers.php'); 
    $page_items = 3;
//	var_dump($_GET);	
    if(isset($_GET['id'])) {
	     $_SESSION['category_id'] = $_GET['id']; 
	     $category_id = addslashes($_SESSION['category_id']);
	     $result = mysqli_query($con,"SELECT COUNT(*) as cnt FROM lots l JOIN categories c ON l.category_id = c.id  
	  			WHERE  (c.id = ' $category_id')  and (l.date_finish > NOW())");
	     $items_count = mysqli_fetch_assoc($result)['cnt'];		 
	     $pages_count = ceil($items_count/$page_items); 
         $_SESSION['pages_count'] = $pages_count;  
	}
	else {	
	   $category_id = addslashes($_SESSION['category_id']);	
	   $pages_count = $_SESSION['pages_count'];
	}	
 //   var_dump($category_id);    
	foreach ($categories as $val) {
	    if($val['id'] == $category_id) {
		      $h2_text = $val['name'];	
	    }        
	}
	$pages = range(1, $pages_count);	 
		
  	if(   isset($_GET['page'])) {		    
		    $cur_page = $_GET['page'];	
	}
    else {
		 $cur_page =1;
	}	
	$offset = ($cur_page - 1) * $page_items;
	// выбираем из базы  данных данные для лота с требуемым id 
		 
    $sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id FROM lots l JOIN categories c ON l.category_id = c.id  
	  			WHERE  (c.id = ' $category_id')  and (l.date_finish > NOW()) LIMIT  $page_items   OFFSET  $offset  ";  
	$res_l = mysqli_query($con, $sql);	 
	$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
  
	    
	// вводим метки наличия аукционной цены  и записываем время до завершения торгов в массивы
	$mark_rates = [];
	$time_to_end = [];
    if(count($lots) != 0)  {          		
	  for( $i=0; $i < count($lots); $i++) {
		 if ($lots[$i]['current_price'] == $lots[$i]['start_price']) { 
		     $mark_rates[$i] = 0;					
		 }
		 else {
		 	$mark_rates[$i] = 1;
		 }
		 $time_to_end[$i] = time_to_finish($lots[$i]['date_finish']);
	  }
    }	
		
		 // вычисляем количествотребуемых страниц		
		 
    
    $page_content = include_template('all-lots.php', [ 'lots' =>$lots,  'h2_text' => $h2_text, 'mark_rates' => $mark_rates , 'time_to_end' => $time_to_end, 
		                                'pages_count' => $pages_count, 'cur_page' => $cur_page, 'pages' => $pages] );
	 
    $layout_content = include_template('layout.php',['content' => $page_content, 'categories'=> $categories,  
	                                   'title' => 'YetiCave - Результаты поиска']);									   
    print($layout_content); 
    	
?>      