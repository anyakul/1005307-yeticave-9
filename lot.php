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
                                               //?
		
    if (isset($_GET['id'])) {
        $lot_id = addslashes($_GET['id']);                            // проверяем запрос на наличие ID лота
	//     var_dump($id);                    		                              // получаем id лота из запроса, если он там есть
		 
		// выбираем из базы  данных данные для лота с требуемым id 
	    $sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id FROM lots l JOIN categories c ON l.category_id = c.id
				WHERE l.id = $lot_id"; 
		$res_l = mysqli_query($con, $sql);
		$lot = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
		if (count($lot) != 0) {
		    $val=$lot[0];
			//выбираем все данные по лоту из разных таблиц для истории ставок и т.п.
		//    $sql = "SELECT u.name user_name, l.name lot_name, l.start_price cost, l.step_rate step, date_finish, 
		//	       r.date_create date_create, r.user_id user_id, r.lot.id FROM users u JOIN lots l, rates r ON u.id = l.user_id
		//			WHERE user_name = r.user_id and r.lot_id = l.name";
		//	$res = mysqli_query($con, $sql);
		
		// из rates выбираем всю историю по данному лоту для записи истории и подсчета минимальной цены при введении ставки, 
		//пока вместо имени будет id
		
		    $sql = "SELECT  u.name user_name, r.price, r.date_create FROM  rates r JOIN users u ON r.user_id = u.id 
          			WHERE r.lot_id = $lot_id";		 			 
		 	$res = mysqli_query($con, $sql);		 
			if ($res !=  false) {
				$lot_rates = mysqli_fetch_all($res, MYSQLI_ASSOC);	
 	 //		var_dump($lot_rates);
			} 
            $count_lot_rates = count($lot_rates);
			
		    if($count_lot_rates > 0) { 
			    $current_price = $lot_rates[$count_lot_rates - 1]['price'];				 
		//		var_dump($val['step_rate']);
			}
			else {
				$current_price = $val['start_price'];
				  // поправить if я занесу старт прайс в рэйтс
			}
			$min_price = $current_price+ $val['step_rate'] ;
	  		// определяем  время жизни лота до полуночи b вводим переменную с символом рубля	
	
			$time_to_finish  = time_to_finish($val['date_finish']);   //  'date_finish'
			
			$_SESSION['lot_id'] = $lot_id;
            $_SESSION['min_price'] = $min_price;
            $_SESSION['lot_rates'] = $lot_rates;
            $_SESSION['time_to_finish'] = $time_to_finish;		
			$_SESSION['val'] = $val;

		}
	 	else {
 			http_response_code(404);
		    header("location: /pages/404.html");
	    }			
	}	  
		 
	// форма добавления ставки
    //if (!empty($_SESSION['is_auth'])) {
  
	
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {	
	    $errors = [];
        $current_price = $_SESSION['val']['current_price'];		
	    $lot_id = $_SESSION['lot_id'];
		$min_price =  $_SESSION['min_price'];
	  //    var_dump($lot_id, $min_price );
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
	//var_dump($errors);
		if (count($errors) == 0) { 
			$current_price = $lot_rate_new['cost'];		
			$lot_rate_user_id = $_SESSION['user_id'];  
			// $_SESSION[$user_name]['id'];		    
		//	var_dump($lot_rate_price,$lot_rate_user_id,$id );
			// определить по имени юзера его id или записать его в $_SESSION
			// lot_id взять из предыдущего запроса в GET
			// ввести поле в lots current_price, где хранит
			 			 
			$sql = "INSERT INTO rates (date_create, user_id, lot_id, price) VALUES  (NOW(),?,?,?)";
			$stmt = db_get_prepare_stmt($con, $sql, [ $lot_rate_user_id, $lot_id, $current_price]);
		    $res  = mysqli_stmt_execute($stmt);
			$sql = "UPDATE lots SET current_price = $current_price WHERE id = $lot_id";
		    $res_c = mysqli_query($con, $sql);				 
            if($res) {
				 
				unset($_SESSION['lot_id']);
                unset($_SESSION['min_price']);
                unset($_SESSION['lot_rates']);
                unset($_SESSION['time_to_finish']);	
			    unset($_SESSION['val']);
				header("location: index.php"); 
			}			
		}		 
	}
		//	   else {
	//			   http_response_code(404);
			//	   header("location: /pages/404.html");
			//   }			
		     

		 //  в случае когда правильно сработал запрос на извлечение данных переходим на страницу лота
			  
		 
        
		 // страница с таким ID  не существует или в id указано не число
    //    else {	
	//	       http_response_code(404);
	//	       header("location: /pages/404.html");      }				
	 
	// в запросе отсутсвует ID
  //  else {	
//		  http_response_code(404);
//		  header("location: /pages/404.html");
  //  }
  //   var_dump($lot_rates);  
   
    $page_content = include_template('lot.php', [ 'val' => $_SESSION['val'] , 'errors' => $errors, 
	         'time_to_finish' => $_SESSION['time_to_finish'],'lot_rates' => $_SESSION['lot_rates'],
                                   	'add_ruble' => $add_ruble, 'current_price' => $current_price, 'min_price' => $min_price] );
	 
    $layout_content = include_template('layout.php',['content' => $page_content, 'categories'=> $categories,  
	                                   'title' => 'YetiCave - Просмотр лота']);									   
	print($layout_content);
 	
?>      