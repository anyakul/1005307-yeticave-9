<?php 
 session_start();
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
			
			$user = [];
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				$form = $_POST; 
				// поля, обязательные для заполнения
				$required = ['email', 'password']; 
				$errors = [];
				//  валидация всех текстовых полей формы
				foreach ($required as $key) {
					if (empty($form[$key])) {
						$errors[$key] = 'это поле обязательно для заполнения'; 
					} 
				}
				// Проверка email
					 $email = mysqli_real_escape_string($con, $form['email']);

					 $sql = "SELECT * FROM users WHERE email = '$email'";
					 $res = mysqli_query($con, $sql);
					 $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

					 if (!count($errors) and $user) {
						 if (password_verify($form['password'], $user['password'])) {
							 $_SESSION['user'] = $user;
							 $_SESSION['is_auth'] = true;
							 header("Location: /index.php");
							 } else {
								 $errors['password'] = 'Неверный пароль';
							 }
					 }

					 else {
						$errors['email'] = 'Такой пользователь не найден';
  					 }
					 if (count($errors)) { 
					     $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors]);
					}
			}
			else {
				if (isset($_SESSION['user'])) {
					header("Location: /index.php");
				}
				else {
					$page_content = include_template('login.php', []);
				}
		}
 	    $layout_content = include_template('layout.php',
               ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Вход на сайт']);
        print($layout_content);		
		   
?>