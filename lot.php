<?php session_start();
    if( isset($_SESSION['username'] )) {	
	  $user_name =  $_SESSION['username'];
    } 
 
     // добавляем функции из helper  
    require('helpers.php');
	
	//  Добавляем мои функции	
    require('my_function.php'); 	
	
	 // устанавливаем соединение с базой данных и Создаем  массив категорий 
    $con = mysqli_connect("localhost", "root", "", "yeticave");
	mysqli_set_charset($con, "utf8");
	
	// получаем из базы данных массив категорий	
	$categories = get_categories($con);
    
	$add_ruble='<b class="rub">₽</b>';	
	
    if (isset($_GET['id'])) {
        $lot_id = addslashes($_GET['id']);
		$_SESSION['id'] = $lot_id;
	}
    else {
	    $lot_id = addslashes($_SESSION['id']);	
	}

    // получаем данные по лоту из базы данных	
	$sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id, l.count_rates FROM lots l JOIN categories c ON l.category_id = c.id
				WHERE l.id = $lot_id"; 
	$res_l = mysqli_query($con, $sql);
	$lot = mysqli_fetch_all($res_l, MYSQLI_ASSOC);	 	
	
	if (count($lot) > 0) {
		$val=$lot[0]; 
		$current_price = $val['current_price'];
        $min_price = $current_price;
		
		// из rates выбираем всю историю по данному лоту для записи истории и подсчета минимальной цены при введении ставки		
        $sql = "SELECT  u.name user_name, r.price, r.date_create FROM  rates r JOIN users u ON r.user_id = u.id 
          			WHERE r.lot_id = $lot_id";		 			 
        $res = mysqli_query($con, $sql);		 
		$lot_rates = mysqli_fetch_all($res, MYSQLI_ASSOC);		 
          		
	    if(count($lot_rates)> 0) { 			 
           $min_price = $current_price + $val['step_rate'];			 
		}		
	  	// определяем  оставшееся  время жизни лота	
		$time_to_finish  = time_to_finish($val['date_finish']);    
	}
	else {
 		http_response_code(404);
	    header("location: /pages/404.html");
    }				 
		// выбираем из базы  данных данные для лота с требуемым id 
	    
	 
	
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	    $errors = [];  
	    $lot_rate_new = $_POST;		  
		if (empty($lot_rate_new['cost'])) { 
		    $errors['cost'] = 'Это поле нужно заполнить'; 
		}
		else {								
		    if ($lot_rate_new['cost'] <= 0 or (int)($lot_rate_new['cost']) != $lot_rate_new['cost']) {
				$errors['cost'] = 'цена должна быть целым положительным числом'; 
		    }
		    else {
			    if ($lot_rate_new['cost'] <  $min_price) {
					    $errors['cost'] = 'цена должна быть не меньше стартовой цены или не меньше текущей цены + шаг ставки';
				} 	
			}
		}
	 
		if (count($errors) == 0) { 
		
		    //  записываем новую ставку в таблицу rates  и меняем текущую цену и текущего выигрывающего пользователя в таблице lots
			$current_price = $lot_rate_new['cost'];		
			$lot_rate_user_id = $_SESSION['user_id'];		 			 
			$sql = "INSERT INTO rates (date_create, user_id, lot_id, price) VALUES  (NOW(),?,?,?)";
			$stmt = db_get_prepare_stmt($con, $sql, [ $lot_rate_user_id, $lot_id, $current_price]);
		    $res  = mysqli_stmt_execute($stmt);
            $lot_count_rates = $val['count_rates'] + 1;	 		
		    $res_c = mysqli_query($con, "UPDATE lots SET current_price = $current_price, user_winner_id = $lot_rate_user_id, count_rates = $lot_count_rates    
                                  WHERE id = $lot_id");				 
            unset($_SESSION['lot_id']);                 
			header("location: index.php");     		 		
		}		 
	}
 
   
    $page_content = include_template('lot.php', [ 'val' => $val , 'errors' => $errors, 
	         'time_to_finish' => $time_to_finish, 'lot_rates' => $lot_rates,
                                   	'add_ruble' => $add_ruble, 'current_price' => $current_price, 'min_price' => $min_price] );
	 
    $layout_content = include_template('layout.php',['content' => $page_content, 'categories'=> $categories,  
	                                   'title' => 'YetiCave - Просмотр лота', 'user_name' => $user_name]);									   
	print($layout_content);
 	
?>      