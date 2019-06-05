<?php
    session_start();
    
    /* подключаем общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
       получение массива категорий, блока вывода ошибок на экран */
    include('common_block.php');
    
    $add_ruble='<b class="rub">₽</b>';
    
    if (isset($_GET['id'])) {
        $lot_id = addslashes($_GET['id']);
        $_SESSION['id'] = $lot_id;
    } else {
        $lot_id = $_SESSION['id'] ;
    }

    // получаем данные по лоту из базы данных
    $sql = "SELECT c.name category, l.id, l.image, l.description, l.date_create, l.current_price, l.date_finish,
		        l.name, l.page_adress, l.start_price, l.step_rate, l.user_id, l.count_rates FROM lots l JOIN categories c ON l.category_id = c.id
				WHERE l.id = $lot_id";
    $res_l = mysqli_query($con, $sql);
    $lot = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
    
    if (count($lot) > 0) {
        $val=$lot[0];
        $current_price = $val['current_price'];
        $min_price = $current_price;
        
        // из rates выбираем всю историю по данному лоту для записи истории и подсчета минимальной цены при введении ставки
        $sql = "SELECT  u.name user_name, r.price, r.date_create FROM  rates r JOIN users u ON r.user_id = u.id 
          			WHERE r.lot_id = $lot_id";
        $res = mysqli_query($con, $sql);
        $lot_rates = mysqli_fetch_all($res, MYSQLI_ASSOC);
                   
        if (count($lot_rates)> 0) {
            $min_price = $current_price + $val['step_rate'];
        } 
        // определяем  оставшееся  время жизни лота
        $time_to_finish  = time_to_finish($val['date_finish']);
    } else {
        http_response_code(404);
        header("location: /pages/404.html");
    }
    /* Блок ввода ставки. Появляется в шаблоне только для авторизированных пользователей     */
    /* и пользователей, котрые не являются владельцами данного лота                           */
        
    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        // защита от SQL-инъекций
        $lot_rate_new  = addslashes($_POST['cost']);
        
        // валидация введенного значения
        if (empty($lot_rate_new)) {
            $errors['cost'] = 'Это поле нужно заполнить';
        } elseif (!is_numeric($lot_rate_new)  or (floor($lot_rate_new) != $lot_rate_new)  or ($lot_rate_new  <  $min_price)) {
            $errors['cost'] = 'цена должна быть целым положительным числом, больше или равным минимальной ставке';
        }
        
     
        if (count($errors) === 0) {
        
            //  записываем новую ставку в таблицу rates  и меняем текущую цену, текущего выигрывающего пользователя количества ставок для лота в таблице lots
            $current_price = $lot_rate_new;
            $lot_rate_user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO rates (date_create, user_id, lot_id, price) VALUES  (NOW(),?,?,?)";
            $stmt = db_get_prepare_stmt($con, $sql, [ $lot_rate_user_id, $lot_id, $current_price]);
            $res  = mysqli_stmt_execute($stmt);
            $lot_count_rates = $val['count_rates'] + 1;
            $res_c = mysqli_query($con, "UPDATE lots SET current_price = $current_price, user_winner_id = $lot_rate_user_id, count_rates = $lot_count_rates    
                                  WHERE id = $lot_id");
            unset($_SESSION['lot_id']);

            // переходим на главную страницу
            header("location: index.php");
        }
    }
 
   
    $page_content = include_template('lot.php', [ 'val' => $val , 'errors' => $errors, 'time_to_finish' => $time_to_finish, 'lot_rates' => $lot_rates,
                                       'add_ruble' => $add_ruble, 'current_price' => $current_price, 'min_price' => $min_price]);
     
    $layout_content = include_template('layout.php', ['content' => $page_content, 'categories'=> $categories,
                                       'title' => 'YetiCave - Просмотр лота', 'user_name' => $user_name]);
    print($layout_content);
    
?>      