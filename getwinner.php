<?php
// Блок определения победителя аукциона и отправки сообщения победителю
require_once "vendor/autoload.php";   // подключение библиотеки
       
//  выбираем все лоты, торги по которым состоялись, но победители по которым еще не определены
$lots = [];
$sql = "SELECT  u.name user_name, u.email user_email, l.id, l.user_winner_id, l.name, l.page_adress  FROM lots l  JOIN users u ON l.user_winner_id = u.id
               WHERE l.date_finish < NOW()and l.user_winner_id > 0 and l.user_id != l.user_winner_id";
$res_l = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($res_l, MYSQLI_ASSOC);
 
if (count($lots) > 0) {
           
    // отправляем email победителям аукциона
    foreach ($lots as $val) {
        $email = include_template('email.php', [ 'val' => $val]);
        $user_email = $val['user_email'];
        $user_name =  $val['user_name'];
         
        // Конфигурация траспорта
        $transport = new Swift_SmtpTransport('phpdemo.ru', 25, null);
        $transport->setUsername('keks@phpdemo.ru');
        $transport->setPassword('htmlacademy');
               
        // Формирование сообщения
        $message = new Swift_Message("Ваша ставка победила");
        $message->setTo(["$user_email" => "$user_name"]);
        $message->addPart("$email", "text/html");
        $message->setFrom("keks@phpdemo.ru", "YetyCave");
              
        // Отправка сообщения
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
              
        // отмечаем, что по данному лоту победитель выбран ( меняем знак  у поля user_winner_id на противоположный)
        $winner_id = - $val['user_winner_id'];
        $lot_id = $val['id'];
        $res_c = mysqli_query($con, "UPDATE lots SET user_winner_id =  '$winner_id'  WHERE id = $lot_id");
    }
}
