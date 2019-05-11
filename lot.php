<?php 

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
 	   
     if (isset($_GET['id'])) {                                  // проверяем запрос на наличие ID лота
	     $id = addslashes($_GET['id']);                     		// получаем id лота из запроса, если он там есть
		 
		 // выбираем из базы  данных данные для лота с требуемым id 
	     $sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create,
		        l.name, l.page_adress, l.start_price FROM lots l JOIN categories c ON l.category_id = c.id
				WHERE l.id = $id"; 
		 $res_l = mysqli_query($con, $sql);
		 
		 // проверяем правильность выполнения запроса
		 if ($res_l !=  false) {
             $lot = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
		     
			  if (count($lot) != 0) {
			  $val=$lot[0];	 
				   // определяем  время жизни лота до полуночи b вводим переменную с символом рубля	
				   $time_to_finish  = time_lot();
				   $add_ruble='<b class="rub">₽</b>';
			  
				   // подключаем страницу с лотом 	     
				   $page_content = include_template('lot.php', [ 'val' => $val , 'time_to_finish' => $time_to_finish, 'add_ruble' => $add_ruble] );
				   
				   // подключаем layout.php
				   $layout_content = include_template('layout.php',
				   ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Просмотр лота', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
				   print($layout_content);
			 }
			   else {
				   http_response_code(404);
				   header("location: /pages/404.html");
			   }			
		}
		 //  в случае когда правильно сработал запрос на извлечение данных переходим на страницу лота
		 
        
		 // страница с таким ID  не существует или в id указано не число
         else {	
		       http_response_code(404);
		       header("location: /pages/404.html");
         }				
	 }
	// в запросе отсутсвует ID
    else {	
		  http_response_code(404);
		  header("location: /pages/404.html");
    }	
?>     
   