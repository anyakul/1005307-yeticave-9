<?php // Блок определения победителя аукциона и отправки сообщения победителю

       $lots = [];
       $sql = "SELECT  u.name user_name, l.id, l.user_winner_id, l.name, l.page_adress  FROM lots l  JOIN users u ON l.user_winner_id = u.id
               WHERE l.date_finish < NOW()and l.user_winner_id > 0 and l.user_id != l.user_winner_id";                      
       $res_l = mysqli_query($con, $sql);
       $lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
 
       if (count($lots) > 0) {
	    	// отправляем email победителю аукциона
		   foreach ($lots as $val) {   
		      $email = include_template('email.php',[ 'val' => $val] );
		      $winner_id = - $val['user_winner_id']; 			 
		      $lot_id = $val['id'];
              $res_c = mysqli_query($con, "UPDATE lots SET user_winner_id =  '$winner_id'  WHERE id = $lot_id");
			  //	print($email);
		    }
		
	    }
	
?>	 
   
			
 		