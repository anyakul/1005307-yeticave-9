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
				
		// получаем из базы данных массив категорий
		$sql = "SELECT * FROM categories";
		$res_c = mysqli_query($con, $sql);
		$categories = mysqli_fetch_all($res_c, MYSQLI_ASSOC);  
            
		//  Добавляем мои функции
	
        require('my_function.php'); 
 	 
	    // добавляем функции из helper  

        require('helpers.php');
		if ( isset($_GET['user_id'])){
		    $user_id = $_GET['user_id']; 
		}
		else {
		    $user_id = $_SESSION['user_id'];  
		}
     // var_dump($user_id);		
        $sql = "SELECT   l.page_adress page_adress, l.image lot_image, l.name lot_name, l.date_finish lot_date_finish, l.category_id category_id, 
		                r.price, r.date_create 
		                FROM  rates r JOIN lots l ON r.lot_id = l.id 	WHERE r.user_id  = $user_id ORDER BY r.date_create DESC	   ";					 
		$res = mysqli_query($con, $sql);	     
		$my_bets = mysqli_fetch_all($res, MYSQLI_ASSOC);
	 // var_dump($my_bets);
	    $i=0;
		while ($i <  count($my_bets)) {
			$category_id = $my_bets[$i]['category_id'];
			foreach( $categories as $category) {
				if($category['id'] == $category_id ) {
				//	var_dump($category_id ,$category['name']);
					$my_bets[$i]['category_id'] = $category['name'];
					$i =$i+1;
				//	var_dump($val);
				}						 
			}
		}
	  //	var_dump($my_bets);
	     
 		$page_content = include_template('my_bets.php', [ 'categories' => $categories, 'my_bets' => $my_bets ]);		         
 	    $layout_content = include_template('layout.php',
               ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - мои ставки']);
        print($layout_content);			   
?> 		  			   