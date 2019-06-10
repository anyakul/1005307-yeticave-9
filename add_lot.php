<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: /");
    exit();
}
/* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
получение массива категорий, блока вывода ошибок на экран */
include('common_block.php');
        
//  работа с  формой
$errors = [];
$lot = [];
$required = ['lot-name', 'category', 'message',  'lot-rate', 'lot-step', 'lot-date'];  // поля, необходимые для заполнения. не включая изображение
        
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($required as $val) {
        $lot[$val] = $_POST[$val];
    }
    //  валидация всех текстовых полей формы
    $error = 'это поле обязательно для заполнения';
    if (!isset($lot['lot-name']) or mb_strlen(str_replace(array(" "), '', $lot['lot-name']), 'utf-8') === 0) {
        $errors['lot-name'] = $error;
    }
 
    if ($lot['category'] === 'Выберите категорию') {
        $errors['category'] = 'надо выбрать категорию';
    }
    if (empty($lot['message'])) {
        $errors['message'] = $error;
    }
    if (!is_numeric($lot['lot-rate']) or $lot['lot-rate'] <= 0) {
        $errors['lot-rate'] = 'цена должна быть положительным числом';
        ;
    }
    if (!is_numeric($lot['lot-step']) or $lot['lot-step'] <= 0 or  !filter_var($lot['lot-step'], FILTER_VALIDATE_INT)) {
        $errors['lot-step'] = 'шаг ставки должна быть целым положительным числом';
        ;
    }
    if (!is_date_valid($lot['lot-date'])) {
        $errors['lot-date'] = 'должен соблюдаться формат вводимой даты';
    } else {
        if (strtotime($lot['lot-date']) < time() + 24*3600) {
            $errors['lot-date'] = 'время завершения должно быть не менее чем на сутки больше текущей даты';
        }
    }
                      
    //  проводим валидацию файла изображения
    //  проверка наличия загрузки и формата файла
    if (empty($_FILES['lot-img']['tmp_name'])) {
        $errors['lot-img'] = 'Файл не загружен.';
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES['lot-img']['tmp_name']; 
        $file_type = finfo_file($finfo, $file_name);
        if ($file_type !== 'image/jpeg') {
            $errors['lot-img'] = 'загрузите картинку в формате jpeg или jpg';
        }
    }
                
    if (count($errors) === 0) {
                
        // блок записи введенных данных в базу данных и переход на страницу лота
        // данные вводятся с помощью функции db_get_prepare_stmt(), а значит защита от SQL-инъекций есть
        $file_input =  $_FILES['lot-img']['name']; 
        $file_path = __DIR__ . '/uploads/'; 
        move_uploaded_file($file_name, $file_path . $file_input);
        $lot['lot-img'] = 'uploads/' . $file_input;
                      
        // записываем введенные данные, а также id категории лота и адрес страницы просмотра лота в базу данных и переходим на страницу показа введенного лота
        foreach ($categories as $category) {
            if ($lot['category'] === $category['name']) {
                $category_id = $category['id'];
            }
        }
                   
        $sql = "INSERT INTO lots (  date_create, name,  category_id, user_id, start_price, current_price, description, step_rate, date_finish, image,
      		       	   user_winner_id, count_rates)  VALUES  (NOW(),?,?,?,?,?,?,?,?,?,0,0)";
                      
        $stmt = db_get_prepare_stmt($con, $sql, [$lot['lot-name'], $category_id, $_SESSION['user_id'], $lot['lot-rate'], $lot['lot-rate'],  $lot['message'],
                                    $lot['lot-step'], $lot['lot-date'], $lot['lot-img']]);
        $res  = mysqli_stmt_execute($stmt);
        $lot_id = mysqli_insert_id($con);
        $con = mysqli_connect("localhost", "root", "", "yeticave");
        $page_adress = "lot.php?id=" . "$lot_id";
        $sql = "UPDATE lots SET page_adress =  '$page_adress'  WHERE id = $lot_id";
        $res_c = mysqli_query($con, $sql);
            
        //  переходим на страницу введенного лота
        header("location: lot.php?id=" . "$lot_id");
    }
}
 
$page_content = include_template('add_lot.php', [ 'categories' => $categories, 'lot' =>$lot, 'errors' => $errors]);
$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - добавление лота',  'user_name' => $user_name]
);
print($layout_content);
