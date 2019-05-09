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
			$errors = [];
			$user = [];
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$user = $_POST; 
				// поля, обязательные для заполнения
				$required = ['email', 'password', 'name', 'message']; 
				//  валидация всех текстовых полей формы
				$error = 'это поле обязательно для заполнения';
				foreach ($required as $field) {
					if (empty($_POST[$field])) {
						$errors[$field] = $error; 
					} 
				}
				if ($key == "email" and !filter_var($value, FILTER_VALIDATE_EMAIL)) {
					$errors[$key] = 'Email должен быть корректным';
					if (isset($user['email'])) {
						$errors[$key] = 'Данный email уже использовался. попробуйте войти в аккаунт';
					}
				}
                // Если ошибок нет, то сохранить данные формы в таблице пользователей
			    if (count($errors) == 0) {
					foreach ($required as $key) {
						$user[$key] = addslashes($user[$key]);
					}
					$passwordHash = password_hash('secret-password', PASSWORD_DEFAULT);
					$sql = "INSERT INTO users (date_registration, email, password, name, contacts) 
					       VALUES (NOW(),?,?,?,?)";
					$stmt = db_get_prepare_stmt($con, $sql, [$user['email'], $passwordHash, $user['name'], $user['message']]);  				    
 			        $res  = mysqli_stmt_execute($stmt);		        	 
					if($res) {
					   $user_id = mysqli_insert_id($con);
					}					 
					$con = mysqli_connect("localhost", "root", "", "yeticave"); 
					header("location: login.php");
				}
		    }
		
		// должны остаться на той же странице с изменнненными классами и сохраненными данными
  			 
 		$page_content = include_template('sign-up.php', [ 'categories' => $categories, 'user' =>$user, 'errors' => $errors]);		         
 	    $layout_content = include_template('layout.php',
               ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
        print($layout_content);			   
?>