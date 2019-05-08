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
				
		// получаем из базы данных массив категорий
		$sql = "SELECT * FROM categories";
		$res_c = mysqli_query($con, $sql);
		$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);  
            
		//  Добавляем мои функции
	
        require('my_function.php'); 
 	 
	    // добавляем функции из helper  

        require('helpers.php');
		   
        // подключаем страницу с добавлением  лота
		
				 
        
	$errors=[];
	    //  проверка   получения формы
        if($_SERVER['REQUEST_METHOD'] == 'POST') {	
			$lot  = $_POST ; 
			// поля, обязательные для заполнения
			$required = ['lot-name', 'category', 'message',   'lot-rate', 'lot-step', 'lot-date'];
			
			
			 // загрузка изображения и проверка формата файла
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
				  $file_path = __DIR__ . '/uploads/';				     
                  move_uploaded_file( $_FILES['lot-img']['tmp_name'], $file_path . $_FILES['lot-img']['name']);
				  $lot['image'] = 'uploads/' . $_FILES['lot-img']['name'];				  
			   }
            }			   
					
		    // проверка правильности заполнения всех текстовых полей формы
			foreach ($required as $key ) {
			   if(empty($lot[$key])) {
				   $errors[$key] = 'поле должно быть  заполнено';
			   } 
               else {
				  if(($key == 'lot-rate' or $key == 'lot-step')  
					       and (!is_numeric($lot[$key]) or  $lot[$key] <= 0 or  (int)($lot[$key]) != $lot[$key])) {
				   	$errors[$key] = 'должно вводиться только целое положительное число';
			      }
			      if(($key == 'lot-date') and !is_date_valid($lot[$key]))  {
					   $errors[$key] = 'должен соблюдаться формат вводимой даты';
					   if(diff($lot[$key],date(now)) <1 ) {
					       $errors[$key] = 'время завершения торгов по лоту должно отличаться от текущей не менее, чем на 1 час';
					   }
				  }
			   }			  
			} 
				
		   
		  
			   
			if(count($errors) == 0){
			    	 
			        // добавляем страницу с новым лотом
			    	$sql = "INSERT INTO lots SET name = '{$_POST['lot-name']}' , start_price = '{$_POST['lot-rate']}', category_id = '1',
			                user_id ='2',  description ='{$_POST['message']}',  step_rate = '{$_POST['lot-step']}', date_finish = '{$_POST['lot-date']}'" ;  
 			        $res_i = mysqli_query($con, $sql);	
					
	 				// определяем  время жизни лота до полуночи b вводим переменную с символом рубля	
	                $time_to_finish  = time_lot();
		            $add_ruble='<b class="rub">₽</b>';
					
					// подключаем lot.php 
                    $val=[];
					$val['name'] = $lot['lot-name'];
                    $val['image'] = $lot['image'];					
                    $val['category'] = $lot['category'];
                    $val['start_price']	= $lot['lot-rate'];	
                    $val['descrition']	= $lot['message'];					
                    $page_content = include_template('lot.php', [ 'val' => $val , 'time_to_finish' => $time_to_finish, 'add_ruble' => $add_ruble] );
					
		            // подключаем layout.php
 	                $layout_content = include_template('layout.php',
                         ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная',
						 'user_name' => $user_name, 'is_auth' => $is_auth ]);
	                print($layout_content);					 
			} 	
		    else {
				 // должны остаться на той же странице с изменнненными классами и сохраненными данными
  			 
 				  $page_content = include_template('add_lot.php', [ 'categories' => $categories, 'lot' =>$lot, 'errors' => $errors]);		         
 	              $layout_content = include_template('layout.php',
                         ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
                  print($layout_content);			  
			}
        }  
        else {  
		    $page_content = include_template('add_lot.php', [ 'categories' => $categories, 'lot' =>$lot, 'errors' => $errors]);			   
		    // подключаем layout.php
 	        $layout_content = include_template('layout.php',
                 ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Главная', 'user_name' => $user_name, 'is_auth' => $is_auth ]);
            print($layout_content);		  
		}  
?>                