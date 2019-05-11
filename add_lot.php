<?php 
session_start();
			 if (!isset($_SESSION['is_auth'])) {
				 http_response_code(403);
				 exit();
		    }
        // устанавливаем соединение с базой данных и Создаем  массив категорий 
        $con = mysqli_connect("localhost", "root", "", "yeticave");
     	if ($con == false) {
// 			print("Ошибка подключения: " . mysqli_connect_error());
	 	}
		else {
//			print("Соединение установлено");
		}				
	    mysqli_set_charset($con, "utf8");
				
		// получаем из базы данных массив категорий
		$sql = "SELECT * FROM categories";
		$res_c = mysqli_query($con, $sql);
		$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);  
            
		//  Добавляем мои функции
	
        require('my_function.php'); 
 	 
	    // добавляем функции из helper  
        require('helpers.php');
		   
        // подключаем страницу с добавлением  лота
		
				 
	
	    //  проверка   получения формы
		$errors = [];
		$lot = []; 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$lot = $_POST; 
			// поля, обязательные для заполнения
			$required = ['lot-name', 'category', 'message', 'image',  'lot-rate', 'lot-step', 'lot-date']; 
			
			//  валидация всех текстовых полей формы
			$error = 'это поле обязательно для заполнения';
			if (empty($lot['lot-name'])) { $errors['lot-name'] = $error; }
			if ($lot['category'] == 'Выберите категорию') {$errors['category'] = 'надо выбрать категорию';}
			if (empty($lot['message'])) {$errors['message'] = $error;}
			if ($lot['lot-rate'] <= 0) {$errors['lot-rate'] = 'цена должна быть положительным числом';}
			if ($lot['lot-step'] <= 0 or (int)($lot['lot-step']) != $lot['lot-step'] ) {$errors['lot-step'] = 'цена должна быть целым положительным числом';}
			if (!is_date_valid($lot['lot-date'])) {
				$errors['lot-date'] = 'должен соблюдаться формат вводимой даты';
				if ($lot['lot-date'] <= date("Y-m-d")) {
					$errors['lot-date'] = 'Дата должна быть больше текущей';
				}
			}
			 			
			// Если все текстовые поля прошли валидацию, то проводим валидацию файла изображения
			if (count($errors) == 0) {
			     //  проверка наличия загрузки и формата файла  
			    if ( empty($_FILES['lot-img']['tmp_name'])) {
				    $errors['image'] = 'Файл не загружен.';				   
			    }                                                                 
			    else {
			       $finfo = finfo_open(FILEINFO_MIME_TYPE);
                   $file_name = $_FILES['lot-img']['tmp_name'];
			       $file_type = finfo_file($finfo, $file_name);				 
			       if ($file_type != 'image/jpeg') {
				       $errors['image'] = 'загрузите картинку в формате jpeg или jpg';
			       }
                   else { // Проверка файла изображения прошла успешно и записываем этот файл в папку uploads
				      $file_path = __DIR__ . '/uploads/';				     
                      move_uploaded_file( $_FILES['lot-img']['tmp_name'], $file_path . $_FILES['lot-img']['name']);
				      $lot['image'] = 'uploads/' . $_FILES['lot-img']['name'];
					  
					   // записываем введенные данные в базу данных и переходим на страницу показа введенного лота 
					   foreach ($required as $key) {
						   $lot[$key] = addslashes($lot[$key]);
					   }
					   $lots['user_id'] = 2;		    	  
					   $sql = "INSERT INTO lots (  date_create, name,  category_id, user_id, start_price, description, step_rate, date_finish, image) 
					          VALUES  (NOW(),?,?,2,?,?,?,?,?)";
					   $stmt = db_get_prepare_stmt($con, $sql, [$lot['lot-name'], $lot['category'], $lot['lot-rate'], $lot['message'], $lot['lot-step'],
                                $lot['lot-date'], $lot['image']]);  				    
 			           $res  = mysqli_stmt_execute($stmt);		        	 
					   if($res) {
						   $lot_id = mysqli_insert_id($con);
					   }					 
					   $con = mysqli_connect("localhost", "root", "", "yeticave"); 
					   
			 		   header("location: lot.php?id=" . "$lot_id"  );
		          					 
			        }
				}   
            }					
		}
				
		// если пользователь авторизован - открыть содержимое страницы. если нет - открыть ошибку 403.

			
				$page_content = include_template('add_lot.php', [ 'categories' => $categories, 'lot' =>$lot, 'errors' => $errors]);	
				$layout_content = include_template('layout.php',
				['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Добавление лота', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
				print($layout_content);			   
			
?> 		  
			   
			 
			    	 
			 
				   
		   
          
		  
		   
               