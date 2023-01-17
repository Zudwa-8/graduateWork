<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    
    $login = $_POST["login"];
    $password = $_POST["password"];
    if ($login != "1" || $password != "1") {
        echo "<p>Не верный логин или пароль попробуйте еще раз.</p>";
        echo '<button onclick="window.location.href=`http://localhost/administrator`">Назад</button>';
    }
    else{
        $_SESSION['admin'] = 'successfully';
        header('Location: /administrator');
    }
?>