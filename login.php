<?php
        session_start();
        
        /* общий   блок начальных дейтсвий, включая подключение общих функций, подключение базы данных,
    	 получение массива категорий, блока вывода ошибок на экран */
         include('common_block.php');
        
        // определяем используемые массивы
        $errors = [];
        $user = [];
        $form =	[];
        
        // получаем и проверяем полученные данные
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // защита от некоторых SQL-инъекций
            $form['email'] = addslashes($_POST['email']);
            $form['password'] = addslashes($_POST['password']);
            
            // Проверка email
            if (empty($form['email']) or !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Поле не заполнено или неверный Email';
            } else {
                $em = $form['email'];
                $sql = "SELECT * FROM users WHERE email = '$em'";
                $res_c = mysqli_query($con, $sql);
                $user = mysqli_fetch_all($res_c, MYSQLI_ASSOC);
            }
            if (empty($errors['email']) and empty($user)) {
                $errors['email'] = 'пользователя с таким email не существует';
            }
            // проверка пароля при правильном e-mail
            if (empty($errors['email']) and password_verify($form['password'], $user[0]['password'])) {
                $_SESSION ['username'] = $user[0]['name'];
                $_SESSION ['user_id'] = $user[0]['id'];
                 
                header("Location: index.php");
            }
            //случай правильного email и неверного пароля
            if (empty($errors['email'])) {
                $errors['password'] = 'Неверный пароль';
            }
        }
            
        $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors]);
        $layout_content = include_template('layout.php', ['content' => $page_content, 'categories'=> $categories, 'title' => 'YetiCave - Вход на сайт']);
        print($layout_content);
