<?php
session_start();
         
/* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
   получение массива категорий, блока вывода ошибок на экран */
include('common_block.php');
           
// блок получения и проверки данных
$required = ['email', 'password', 'name', 'message'];
$errors = [];
$user = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = 'это поле обязательно для заполнения';
   
    foreach ($required as $key) {
        $user[$key] = mysqli_real_escape_string($con, $_POST[$key]);
        if (empty($user[$key])) {
            $errors[$key] = $error;
        }
    }
    
    // проверка email на корректность и уникальность
    if (empty($errors['email']) and !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email должен быть корректным';
    }
    if (empty($errors['email'])) {
        $em = $user['email'];
        $sql = "SELECT id FROM users WHERE email = '$em'";
        $res_c = mysqli_query($con, $sql);
        $us = mysqli_fetch_all($res_c, MYSQLI_ASSOC);
        if (!empty($us)) {
            $errors['email'] = 'Данный email уже используется';
        }
    }
    
    // проверка уникальности имени
    if (empty($errors['name'])) {
        $em = $user['name'];
        $sql = "SELECT id FROM users WHERE name = '$em'";
        $res_c = mysqli_query($con, $sql);
        $us = mysqli_fetch_all($res_c, MYSQLI_ASSOC);
        if (!empty($us)) {
            $errors['name'] = 'Данное имя уже используется';
        }
    }
   
    // Если ошибок нет, то сохранить данные формы в таблице пользователей
    if (count($errors) === 0) {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (date_registration, email, password, name, contacts) 
				       VALUES (NOW(),?,?,?,?)";
        $stmt = db_get_prepare_stmt($con, $sql, [$user['email'], $passwordHash, $user['name'], $user['message']]);
        $res  = mysqli_stmt_execute($stmt);
        
        // переход на страницу авторизации
        header("location:  login.php");
    }
}
        
$page_content = include_template('sign-up.php', [ 'categories' => $categories, 'user' =>$user, 'errors' => $errors]);
$layout_content = include_template(
    'layout.php',
    ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Регистрация', 'user_name' => $user_name]
);
print($layout_content);
