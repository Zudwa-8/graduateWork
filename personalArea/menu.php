<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    $id_user = $_SESSION['user']['id_user'];
    $query = "SELECT `username` FROM users
    WHERE `id_user` = $id_user";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false){
        echo json_encode('Error connecting to database');
        die();
    }
    $result = sqlConvert($mysql_result);
    $role_name = $result[0]['role_name'];
    $username = $result[0]['username'];
?>
<nav class="nav nav-pills flex-column pt-3">
    <p class="text-secondary text-center m-0"><? echo $username; ?></p>
    <hr class="mt-0">
    <!-- <div class="mb-3">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-1 text-muted">
            <span>Личный кабинет</span>
        </h6> -->
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link <? echo (($liActive == "Courses") ? "text-white bg-dark" : "text-dark bg-light"); ?>" href="index.php">Мои курсы</a></li>
        </ul>
    <!-- </div>
    <div class="mb-3">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-1 text-muted">
            <span>Настройки аккаунта</span>
        </h6> -->
        <ul class="nav flex-column mb-2">
            <!-- <li class="nav-item"><a class="nav-link link-dark <? echo (($liActive == "PersonalInformation") ? "text-white bg-dark" : "text-dark bg-light"); ?>" href="PersonalInformation.php">Персональные данные</a></li> -->
            <li class="nav-item"><a class="nav-link <? echo (($liActive == "AccountProtection") ? "text-white bg-dark" : "text-dark bg-light"); ?>" href="AccountProtection.php">Защита аккаунта</a></li>
        </ul>
    <!-- </div> -->
</nav>