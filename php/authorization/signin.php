<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');
$logs = [];
$msg = [];

if (isset($_POST['validation']))
{
    $Username = $_POST['validation']['login-inputUsername']['value'];
    $Password = $_POST['validation']['login-inputPassword']['value'];

    $query = "SELECT * FROM users WHERE username = '$Username'";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if (mysqli_num_rows($mysql_result) > 0) {
        session_start();
        if (session_status() != 2) {
            $logs[] = "Не удалось запустить сессию";
            echo json_encode(false);
        }
        else{
            $logs[] = "Пользователь найден";
            $user = sqlConvert($mysql_result)[0];
            if (password_verify($Password, $user['password'])) {
                $_SESSION['user'] = [
                    "id_user" => $user['id_user'],
                    "username" => $user['username']
                ];
                echo json_encode(true);
            }
            else {
                echo json_encode(false);
            }
        }
    }
    else{
        $logs[] = "Пользователь не найден";
        $logs[] = $query;
        echo json_encode(false);
    }
}

$arr = [
    'date' => date('Y-m-d H:i:s'),
    'POST' => $_POST,
    'msg' => $msg,
    'logs' => $logs,
];
?>