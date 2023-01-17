<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');
$logs = [];
$msg = [];

if (isset($_POST['validation']))
{
    $logs[] = $_SESSION;
    $error = [];
    $inputPassword = $_POST['validation']['ChangePassword-inputPassword'];
    $inputNewPassword = $_POST['validation']['ChangePassword-inputNewPassword'];
    $inputNewPasswordСonfirm = $_POST['validation']['ChangePassword-inputNewPasswordСonfirm'];
	if (empty($inputNewPassword)) {
        $error['ChangePassword-inputNewPassword'] = 'Поле не может быть пустым.<br>';
    }
    else{
        if (strlen($inputNewPassword) < 5 || strlen($inputNewPassword) > 25) {
            $error['ChangePassword-inputNewPassword'] = 'Пароль должен содержать от 5 до 25 символов.<br>';
        }
        elseif (!preg_match('/[a-z]+/', $inputNewPassword) || !preg_match('/[A-Z]+/', $inputNewPassword) || !preg_match('/\d+/', $inputNewPassword)) {
            $logs['preg_match1'] = !preg_match('/[a-z]+/', $inputNewPassword);
            $logs['preg_match2'] = !preg_match('/[A-Z]+/', $inputNewPassword);
            $logs['preg_match3'] = !preg_match('/\d+/', $inputNewPassword);
            $error['ChangePassword-inputNewPassword'] = 'Пароль должен содержать хотя бы одну букву нижнего и верхнего регистров и цифру.<br>';
        }
        elseif (empty($inputNewPasswordСonfirm)) {
            $error['ChangePassword-inputNewPasswordСonfirm'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if ($inputNewPassword != $inputNewPasswordСonfirm) {
                $error['ChangePassword-inputNewPasswordСonfirm'] = 'Пароли должны совпадать.<br>';
            }
            else{
                if (empty($inputPassword)) {
                    $error['ChangePassword-inputPassword'] = 'Поле не может быть пустым.<br>';
                }
                else{
                    $id_user = $_SESSION['user']['id_user'];
                    $logs[] = $_SESSION;
                    $query = "SELECT `password` FROM `users` WHERE `id_user` = $id_user";
                    $logs[] = $query;
                    $mysql_result = mysqli_query($connectDatabase, $query);
                    if ($mysql_result === false){
                        echo json_encode('Error connecting to database');
                        die();
                    }else{
                        $user_password = sqlConvert($mysql_result)[0]['password'];
                        if (!password_verify($inputPassword, $user_password)) {
                            $error['ChangePassword-inputPassword'] = 'Старый пароль недействителен.<br>';
                        }elseif ($inputPassword == $inputNewPassword) {
                            $error['ChangePassword-inputNewPassword'] = 'Новый пароль должен отличаться.<br>';
                        }
                    }
                }
            }
        }
    }
    if (!empty($error)) {
        // поля не прошли валидацию
        echo json_encode($error);
    }else{$newPassword = password_hash($inputNewPassword, PASSWORD_DEFAULT);
        $query = "UPDATE `users` SET `password` = '$newPassword' WHERE `id_user` = $id_user";
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false){
            echo json_encode('Error connecting to database');
            die();
        }
        echo json_encode('Password changed successfully');
    }
}
else{
    echo json_encode('Failed to get data');
}

$arr = [
    'date' => date('Y-m-d H:i:s'),
    'POST' => $_POST['validation'],
    '$_SESSION' => $_SESSION,
    'logs' => $logs
];
?>