<?php 
        session_start();
		if (!isset($_SESSION['username'])) {
			header("location: /");
			exit();
		}
		else {
			$user_name = $_SESSION['username'];
		}
        // устанавливаем соединение с базой данных  

        $con = mysqli_connect("localhost", "root", "", "yeticave");  
	    mysqli_set_charset($con, "utf8");		
            
		//  добавляем мои функции
	
        require('my_function.php'); 
 	 
	    // добавляем функции из helper  

        require('helpers.php');	        
		 
		// получаем из базы данных массив категорий	
	    $categories = get_categories($con);        
	
	    //  работа с  формой
		$errors = []; 
		$lot = [];	        
		$required = ['lot-name', 'category', 'message', 'image',  'lot-rate', 'lot-step', 'lot-date']; 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			// записываем в $lots старые значения формы и новые в случае изменений, пересланных через POST
			foreach( $required as $val) {				 
				if(empty($_POST[$val])) {
					if($val == 'lot-date' and empty($_SESSION[$val])) {
						$lot[$val] = "";
					}
					else {
					   $lot[$val] = $_SESSION[$val]; 	
					}					
				}
			    else {
					$lot[$val] = $_POST[$val];
				}		 
			}			
			//  валидация всех текстовых полей формы
			$error = 'это поле обязательно для заполнения';
			if (empty($lot['lot-name'])) { 
			    $errors['lot-name'] = $error; 
			}
			if ($lot['category'] == 'Выберите категорию') {
				$errors['category'] = 'надо выбрать категорию';
			}
			if (empty($lot['message'])) {
				$errors['message'] = $error;
			}
			if ($lot['lot-rate'] <= 0 or (int)($lot['lot-rate']) != $lot['lot-rate'] ) {
				$errors['lot-rate'] = 'цена должна быть целым положительным числом';;
			}
			if ($lot['lot-step'] <= 0 or (int)($lot['lot-step']) != $lot['lot-step'] ) {
				$errors['lot-step'] = 'цена должна быть целым положительным числом';;
			}
			if (!is_date_valid($lot['lot-date'])) {
				$errors['lot-date'] = 'должен соблюдаться формат вводимой даты';
			}
			else {
				 if ( strtotime($lot['lot-date']) < time() + 24*3600 ) {
					 $errors['lot-date'] = 'время завершения должно быть не менее чем на сутки больше текущей даты';
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
                   else { 
				      // Проверка файла изображения прошла успешно и записываем этот файл в папку uploads
				      $file_path = __DIR__ . '/uploads/';				     
                      move_uploaded_file( $_FILES['lot-img']['tmp_name'], $file_path . $_FILES['lot-img']['name']);
				      $lot['image'] = 'uploads/' . $_FILES['lot-img']['name'];					  
					  
					  // записываем введенные данные, а также id категории лота и адрес страницы просмотра лота в базу данных и переходим на страницу показа введенного лота 
					  foreach ($required as $key) {
						  $lot[$key] =addslashes($lot[$key]);  
					  }					  
					  foreach ($categories as $category) {
						  if ($lot['category'] == $category['name']) {
						     $category_id = $category['id'];
						  }
					  }	
 		 		   
                      $sql = "INSERT INTO lots (  date_create, name,  category_id, user_id, start_price, current_price, description, step_rate, date_finish, image,
        			   	   user_winner_id, count_rates) 
					       VALUES  (NOW(),?,?,?,?,?,?,?,?,?,0,0)";
					  
                      $stmt = db_get_prepare_stmt($con, $sql, [$lot['lot-name'], $category_id, $_SESSION['user_id'], $lot['lot-rate'], $lot['lot-rate'],  $lot['message'],
					                               $lot['lot-step'], $lot['lot-date'], $lot['image']]);  				    
 			          $res  = mysqli_stmt_execute($stmt);
 					   
					  if($res) {
					    $lot_id = mysqli_insert_id($con);
					  }					 
					  $con = mysqli_connect("localhost", "root", "", "yeticave"); 					   
					  $page_adress = "lot.php?id=" . "$lot_id";					 
					  $sql = "UPDATE lots SET page_adress =  '$page_adress'  WHERE id = $lot_id";
		              $res_c = mysqli_query($con, $sql);

                      // очищаем часть полей массива $SESSION, использованных для сохранения введенных значений формы					   
					   foreach( $required as $val) {
			             unset($_SESSION[ $val]);
		              }
 
                      //  переходим на страницу введенного лота					   
			 		  header("location: lot.php?id=" . "$lot_id"  );		          					 
			        }
				}   
            }          		
		}
				
		// должны остаться на той же странице с изменнненными классами и сохраненными данными
  		foreach( $required as $val) {
			$_SESSION[$val]	= $lot[$val]; 
		}     	
 		$page_content = include_template('add_lot.php', [ 'categories' => $categories, 'lot' =>$lot, 'errors' => $errors]);		         
 	    $layout_content = include_template('layout.php',
               ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - добавление лота', 'user_name' => $user_name]);
        print($layout_content);			   
?> 		  
			   
			 
			    	 
			 
				   
		   
          
		  
		   
               