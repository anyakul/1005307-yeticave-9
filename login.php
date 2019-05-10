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
				$required = ['email', 'password']; 
				//  валидация всех текстовых полей формы
				$error = 'это поле обязательно для заполнения';
				foreach ($required as $key) {
					if (empty($_POST[$key])) {
						$errors[$key] = $error; 
					} 
				}
				// Проверка email
				if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					 $email = mysqli_real_escape_string($con, $_POST['email']);
					 $sql = "SELECT * FROM users WHERE email = $email";
					 $res = mysqli_query($con, $sql);
					 $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
					 if (!$user) {
						 $errors[$key] = 'Пользователь с таким email не найден';
					 }
					 if (!count($errors) and $user) {
						 if (password_verify($_POST['password'], $user['password'])) {
							 $_SESSION['user'] = $user;
							 } else {
								 $errors['password'] = 'Неверный пароль';
							 }
					 }
				}

				//выполнить процесс аутентификации и переадресовать пользователя на главную страницу.
				if (!count($errors)) {
					session_start('user');
					$user_name = $user[name];
					header("Location: index.php");
				}
			}
				
		// должны остаться на той же странице с изменнненными классами и сохраненными данными
  			 
 		$page_content = include_template('login.php', [ 'categories' => $categories, 'user' =>$user, 'errors' => $errors]);		         
 	    $layout_content = include_template('layout.php',
               ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Вход на сайт', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
        print($layout_content);			   
?>