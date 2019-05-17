<?php session_start();
    if( isset($_SESSION['username'] )) {	
	  $user_name =  $_SESSION['username'];
    } 
 
    //добавляем мои функции
	
	 require('my_function.php');

	// добавляем функции из helper  

     require('helpers.php');
	 
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
				
	$sql = "SELECT c.name category, l.image, l.name, l.page_adress, l.start_price, l.date_finish, l.current_price  FROM lots l JOIN categories c ON l.category_id = c.id
            WHERE l.date_finish > NOW()	"; 
	$res_l = mysqli_query($con, $sql);
	$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
 //  var_dump($lots);	
     $mark_rates = [];
	$time_to_end = [];
    if(count($lots) != 0) {			
		for( $i=0; $i < count($lots); $i++) {
			if ($lots[$i]['current_price'] == $lots[$i]['start_price']) { 
			     $mark_rates[$i] = 0;					
			}
			else {
				$mark_rates[$i] = 1;
			}
			$time_to_end[$i] = time_to_finish($lots[$i]['date_finish']);
		}
  //  var_dump($mark_rates, $time_to_end );			
	}	
	 
	
    
	// HTML код главной страницы	
   
    
	 $page_content = include_template('main.php', 
	 ['categories' => $categories, 'lots' => $lots, 'mark_rates' => $mark_rates, 'time_to_end' => $time_to_end] );
	 $layout_content = include_template('layout.php',
           ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
	 print($layout_content);    
	//	}	 
?>     
   