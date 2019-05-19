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
	$add_ruble='<b class="rub">₽</b>';	
	
    if (isset($_GET['id'])) {
        $lot_id = addslashes($_GET['id']);
		$_SESSION['id'] =$lot_id;
	}
    else {
	    $lot_id = addslashes($_SESSION['id']);	
	}	
	$sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id FROM lots l JOIN categories c ON l.category_id = c.id
				WHERE l.id = $lot_id"; 
	$res_l = mysqli_query($con, $sql);
	$lot = mysqli_fetch_all($res_l, MYSQLI_ASSOC);		
	if (count($lot) != 0) {
		$val=$lot[0];		 
		
		// из rates выбираем всю историю по данному лоту для записи истории и подсчета минимальной цены при введении ставки, 
		//пока вместо имени будет id
		
        $sql = "SELECT  u.name user_name, r.price, r.date_create FROM  rates r JOIN users u ON r.user_id = u.id 
          			WHERE r.lot_id = $lot_id";		 			 
        $res = mysqli_query($con, $sql);		 
		if ($res !=  false) {
			$lot_rates = mysqli_fetch_all($res, MYSQLI_ASSOC);	 	  
		} 
        $count_lot_rates = count($lot_rates);			
	    if($count_lot_rates > 0) { 
			 $current_price = $lot_rates[$count_lot_rates - 1]['price'];		 
		}
		else {
			$current_price = $val['start_price'];				  
		}
		$min_price = $current_price+ $val['step_rate'] ;
	  	// определяем  время жизни лота до полуночи b вводим переменную с символом рубля	
	
		$time_to_finish  = time_to_finish($val['date_finish']);    
	}
	else {
 		http_response_code(404);
	    header("location: /pages/404.html");
    }				 
		// выбираем из базы  данных данные для лота с требуемым id 
	    
	 
	
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	    $errors = [];
        $current_price = $val['current_price'];		
	    
		$min_price =   $min_price;;
	   
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
					    $errors['cost'] = 'цена должна быть не меньше текущей цены + шаг ставки';
				} 	
			}
		}
	 
		if (count($errors) == 0) { 
			$current_price = $lot_rate_new['cost'];		
			$lot_rate_user_id = $_SESSION['user_id'];		 			 
			$sql = "INSERT INTO rates (date_create, user_id, lot_id, price) VALUES  (NOW(),?,?,?)";
			$stmt = db_get_prepare_stmt($con, $sql, [ $lot_rate_user_id, $lot_id, $current_price]);
		    $res  = mysqli_stmt_execute($stmt);			 
		    $res_c = mysqli_query($con, "UPDATE lots SET current_price = $current_price, user_winner_id =$lot_rate_user_id   WHERE id = $lot_id");				 
            if($res) {				 
				unset($_SESSION['lot_id']);                 
				header("location: index.php"); 
    		}			
		}		 
	}
 
   
    $page_content = include_template('lot.php', [ 'val' => $val , 'errors' => $errors, 
	         'time_to_finish' => $time_to_finish, 'lot_rates' => $lot_rates,
                                   	'add_ruble' => $add_ruble, 'current_price' => $current_price, 'min_price' => $min_price] );
	 
    $layout_content = include_template('layout.php',['content' => $page_content, 'categories'=> $categories,  
	                                   'title' => 'YetiCave - Просмотр лота']);									   
	print($layout_content);
 	
?>      