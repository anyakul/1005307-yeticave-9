<?php 
        session_start();
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
		
       //блок валидации формы
		$errors = [];	
	    
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$form = $_POST; 
			
			 
			// Проверка email			
			if ( empty($_POST['email']) or !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$errors['email'] = 'Поле не заполнено или неверный Email';	
			}		
			else { 
			    $em = $_POST['email']; 			 
			    $sql = "SELECT * FROM users WHERE email = '$em'";
		        $res_c = mysqli_query($con, $sql);
		        $user = mysqli_fetch_all($res_c, MYSQLI_ASSOC); 		
				if( empty($user)) {
					$errors['email'] = 'пользователя с таким email не существует';		 
			    } 			
				else {			 
					if( password_verify( $form['password'], $user[0]['password'])) { 				 
			 		 $_SESSION ['username'] = $user[0]['name'];
                     $_SESSION ['user_id'] = $user[0]['id'];                     
					 
					 header("Location: index.php");
					} 
					else {
					   $errors['password'] = 'Неверный пароль';			 
                    }
				}
			}
			
		}
			
	    $page_content = include_template('login.php',  ['form' => $form, 'errors' => $errors]);	 
	
        $layout_content = include_template('layout.php',
         ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Вход на сайт']);
        print($layout_content);		
		   
?>