<?php session_start();
    if( isset($_SESSION['username'] )) {	
	  $user_name =  $_SESSION['username'];
    } 
 // подключаем базу данных и создаем массивы

    $con = mysqli_connect("localhost", "root", "", "yeticave");
	if ($con == false) {
//		print("Ошибка подключения: " . mysqli_connect_error());
	}
	else {
//		print("Соединение установлено");
	}
	mysqli_set_charset($con, "utf8");				
    $sql = "SELECT * FROM categories";
	$res_c = mysqli_query($con, $sql);
	$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);	
				
	$sql = "SELECT c.name category, l.image, l.name, l.page_adress, l.current_price  FROM lots l JOIN categories c ON l.category_id = c.id "; 
	$res_l = mysqli_query($con, $sql);
	$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);	
  
	 
	//добавляем мои функции
	
	 require('my_function.php');

	// добавляем функции из helper  

     require('helpers.php');

    // форматируем оставшееся время до завершения
	
	 $time_to_finish  = time_lot();
	 
	// HTML код главной страницы	
   
    
	 $page_content = include_template('main.php', 
	 ['categories' => $categories, 'lots' => $lots, 'time_to_finish' => $time_to_finish] );
	 $layout_content = include_template('layout.php',
           ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
	 print($layout_content);    
	//	}	 
?>     
   