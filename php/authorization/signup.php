<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    date_default_timezone_set('Europe/Moscow');
    $logs = [];
    $msg = [];
    // валидация
    if (isset($_POST['validation']))
    {
        $error = [];
        $inputUsername = $_POST['validation']['register-inputUsername'];
        $inputPassword = $_POST['validation']['register-inputPassword'];
        $inputPasswordСonfirm = $_POST['validation']['register-inputPasswordСonfirm'];
        $inputFullname = $_POST['validation']['register-inputFullname'];
        $inputEmail = $_POST['validation']['register-inputEmail'];
        $inputTelephone = $_POST['validation']['register-inputTelephone'];
        if (empty($inputUsername)) {
            $error['register-inputUsername'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if (strlen($inputUsername) < 3 || strlen($inputUsername) > 25) {
                $error['register-inputUsername'] = 'Имя пользователя должно содержать от 3 до 25 символов.<br>';
            }
            elseif (!preg_match('/^[a-zA-Z\d]+[a-zA-Z\d_.-]+$/', $inputUsername)) {
                $error['register-inputUsername'] = 'Имя пользователя должно начинаться с буквы или цифры и содержать только буквы, цифры, знак подчеркивания, точку и минус.<br>';
            }
            elseif (!preg_match('/[a-zA-Z]+/', $inputUsername)) {
                $error['register-inputUsername'] = 'Имя пользователя должно содержать хотя бы одну букву.<br>';
            }else{
                $query = "SELECT `username` FROM `users` WHERE `username` = '$inputUsername'";
                $mysql_result = mysqli_query($connectDatabase, $query);
                if ($mysql_result === false){
                    echo json_encode('Error connecting to database');
                    die();
                }
                if (sqlConvert($mysql_result) > 0) {
                    $error['register-inputUsername'] = 'Имя пользователя уже занято.<br>';
                }
            }
        }

        if (empty($inputPassword)) {
            $error['register-inputPassword'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if (strlen($inputPassword) < 5 || strlen($inputPassword) > 25) {
                $error['register-inputPassword'] = 'Пароль должен содержать от 5 до 25 символов.<br>';
            }
            elseif (!preg_match('/[a-z]+/', $inputPassword) || !preg_match('/[A-Z]+/', $inputPassword) || !preg_match('/\d+/', $inputPassword)) {
                $error['register-inputPassword'] = 'Пароль должен содержать хотя бы одну букву нижнего и верхнего регистров и цифру.<br>';
            }
        }

        if (empty($inputPasswordСonfirm)) {
            $error['register-inputPasswordСonfirm'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if (empty($inputPassword)) {
                $error['register-inputPasswordСonfirm'] = 'Сначала заполните поле с паролем.<br>';
            }
            elseif ($inputPassword != $inputPasswordСonfirm) {
                $error['register-inputPasswordСonfirm'] = 'Пароли должны совпадать.<br>';
            }
        }
        
        if (empty($inputFullname)) {
            $error['register-inputFullname'] = 'Поле не может быть пустым.<br>';
        }else{
            if (strlen($inputFullname) > 64) {
                $error['register-inputFullname'] = 'ФИО не может содержать более 64 символов.<br>';
            }
            elseif (preg_match('~[^а-яёА-ЯЁ ]~u', $inputFullname)) {
                $error['register-inputFullname'] = 'ФИО должно содержать только кириллицу.<br>';
            }
        }
        
        if (empty($inputEmail)) {
            $error['register-inputEmail'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
                $error['register-inputEmail'] = 'Электронная почта имеет не верный формат<br>';
            }else{
                $mysqli_result = mysqli_query($connectDatabase, "SELECT `email` FROM `contacts`");
                if ($mysqli_result === false) {
                    $error['register-inputEmail'] = 'Неудалось проверить электронную почту<br>';
                }else{
                    $emails = sqlConvert($mysqli_result);
                    if(in_array ($inputEmail, array_column($emails, 'email')) !== false) {
                        $error['register-inputEmail'] = 'Эта электронная почта уже используется<br>';
                    }
                }
            }
        }
        
        if (empty($inputTelephone)) {
            $error['register-inputTelephone'] = 'Поле не может быть пустым.<br>';
        }
        else{
            if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/', $inputTelephone)) {
                $error['register-inputTelephone'] = 'Телефон имеет не верный формат<br>';
            }else{
                $mysqli_result = mysqli_query($connectDatabase, "SELECT `telephone` FROM `contacts`");
                if ($mysqli_result === false) {
                    $error['register-inputTelephone'] = 'Неудалось проверить номер телефона<br>';
                }else{
                    $telephones = sqlConvert($mysqli_result);
                    if(in_array ($inputTelephone, array_column($telephones, 'telephone')) !== false) {
                        $error['register-inputTelephone'] = 'Этот номер телефона уже используется<br>';
                    }
                }
            }
        }
        
    
        if (!empty($error)) {
            echo json_encode($error);
        }else{
            $Username = $inputUsername;
            $Password = password_hash($inputPassword, PASSWORD_DEFAULT);
            $Fullname = preg_replace('/^ +| +$|( ) +/m', '$1', $inputFullname);
            $Email = preg_replace('/^ +| +$|( ) +/m', '$1', $inputEmail);
            $Telephone = $inputTelephone;
            $date_register = date('Y-m-d H:i:s');

            $query = "INSERT INTO `users` (`username`, `password`, `fullname`, `date_register`) VALUES ('$Username', '$Password', '$Fullname', '$date_register')";
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false){
                echo json_encode('Error connecting to database');
                die();
            }
            
            $query = "SELECT `id_user` FROM `users` WHERE `username` = '$Username'";
            $users_id_user = sqlConvert(mysqli_query($connectDatabase, $query))[0]['id_user'];
            if ($mysql_result === false){
                echo json_encode('Error connecting to database');
                die();
            }

            $query = "INSERT INTO `contacts` (`users_id_user`, `email`, `email_confirm`, `telephone`, `date_edit`) VALUES ('$users_id_user', '$Email', '0', '$Telephone', '$date_register')";
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false){
                echo json_encode('Error connecting to database');
                die();
            }
            echo json_encode('Registration completed successfully');    
        }
    }
    else{
        echo json_encode('Failed to get data');
    }

    $arr = [
        'date' => date('Y-m-d H:i:s'),
        'POST' => $_POST['validation'],
        'logs' => $logs
    ];

?>