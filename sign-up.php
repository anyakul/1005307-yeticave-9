<?php 
 
        // устанавливаем соединение с базой данных и Создаем  массив категорий 
        $con = mysqli_connect("localhost", "root", "", "yeticave");
     	if ($con == false) {
// 			print("Ошибка подключения: " . mysqli_connect_error());
	 	}
		else {
//			print("Соединение установлено");
		}				
	    mysqli_set_charset($con, "utf8");
				
		//  Добавляем мои функции
	
        require('my_function.php'); 
 	 
	    // добавляем функции из helper  
        require('helpers.php');
		
		// получаем из базы данных массив категорий
		$sql = "SELECT * FROM categories";
		$res_c = mysqli_query($con, $sql);
		$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);   
		   
       //Написать код валидации формы и показа ошибок.
	    $required = ['email', 'password', 'name', 'message']; 
     $errors = []; 
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
		//	foreach ($required as $key){
		//		if (!empty($_POST[$key])) {
		//			$user[$key] = $_POST[$key];
		//		}
		//	}   
			
			$user = $_POST; 
			// поля, обязательные для заполнения
			
			//  валидация всех текстовых полей формы
			$error = 'это поле обязательно для заполнения';
			foreach ($required as $key) {
				if (empty($_POST[$key])) {
					$errors[$key] = $error; 
				} 
			}
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = 'Email должен быть корректным';
			}		
			else { 
			    $em = $_POST['email']; 			 
			    $sql = "SELECT id FROM users WHERE email = '$em'";
		        $res_c = mysqli_query($con, $sql);
		        $us = mysqli_fetch_all($res_c, MYSQLI_ASSOC); 		         	        	 
				if( !empty($us)) {
					$errors['email'] = 'Данный email уже используется';
			    } 			
			}			 
				
                // Если ошибок нет, то сохранить данные формы в таблице пользователей
		    if (count($errors) == 0) {
				foreach ($required as $key) {
				$user[$key] = addslashes($user[$key]);
		    	}
				$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$sql = "INSERT INTO users (date_registration, email, password, name, contacts) 
				       VALUES (NOW(),?,?,?,?)";
				$stmt = db_get_prepare_stmt($con, $sql, [$user['email'], $passwordHash, $user['name'], $user['message']]);  
				$res  = mysqli_stmt_execute($stmt);				    
				header("location:  login.php");
				}
		    
				// должны остаться на той же странице с изменнненными классами и сохраненными данными
  		}	 
 	    $page_content = include_template('sign-up.php', [ 'categories' => $categories, 'user' =>$user, 'errors' => $errors]);		         
 	    $layout_content = include_template('layout.php',
              ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Регистрация', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
        print($layout_content);
	    
?>