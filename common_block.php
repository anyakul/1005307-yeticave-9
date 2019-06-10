<?php
// вывод ошибок на экран
ini_set('display_errors', 1);
error_reporting(E_ALL);
         
// сохраняем имя пользователя
if (isset($_SESSION['username'])) {
    $user_name = $_SESSION['username'];
} else {
    $user_name = "";
}
//  добавляем мои функции
require('my_function.php');
     
// добавляем функции из helper
require('helpers.php');

// устанавливаем соединение с базой данных
$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
         
// получаем из базы данных массив категорий
$categories = get_table($con, 'categories');
