<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
if (!$_SESSION['user']){
    header('Location: ../index.php');
}
else{
    $id_user = $_SESSION['user']['id_user'];
    $query = "SELECT `roles`.`role_name` FROM users
    INNER JOIN `roles` ON `roles`.`id_role` = `users`.`role_id_role`
    WHERE `id_user` = $id_user";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false){
        echo json_encode('Error connecting to database');
        die();
    }
    $result = sqlConvert($mysql_result);                    
    if ($result['role_name'] != "Администратор") {
        header('Location: ../personalArea/');
    }
}
$title = "Редактирование пользователя";
require_once($_SERVER['DOCUMENT_ROOT']."/header.php");
if (!isset($_GET['id_user'])){
    header('Location: users.php');
}
$query = "SELECT * FROM `users` WHERE `id_user` = ".$_GET['id_user'];
$mysql_result = mysqli_query($connectDatabase, $query);
if ($mysql_result === false){
    echo json_encode('Error connecting to database');
    die();
}
$result = sqlConvert($mysql_result);
$date = date("Y-m-d", strtotime($result[0]['date_register']));
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "users";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>
    <div class="col-md-10">
        <h3>Редактирование пользователя</h3>   
        <hr>
        <form id="editUser-form" action="/php/PersonalArea/editUser.php" method="POST">
        <div class="row">
            <div class="mb-3 col">
                <label for="editUser-inputUsername" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" readonly id="editUser-inputUsername" <? echo "value=\"".$result[0]['username']."\""; ?>>
            </div>
            <div class="mb-3 col">
                <label for="editUser-inputDate" class="form-label">Дата регистрации</label>
                <input type="date" class="form-control" readonly id="editUser-inputDate" value="<? echo $date; ?>">
            </div> 
        </div>
            <div class="mb-3 col">
                <label for="editUser-inputRole" class="form-label">Роль</label>
                <select class="form-select" id="editUser-inputRole" name="editUser-inputRole">
                    <?
                        $query = "SELECT `id_role`, `role_name`  FROM `roles` WHERE `priority` > 1";
                        $mysql_result = mysqli_query($connectDatabase, $query);
                        if ($mysql_result === false){
                            echo json_encode('Error connecting to database');
                            die();
                        }
                        $result2 = sqlConvert($mysql_result);
                        for ($i=0; $i < count($result2); $i++) :
                    ?>
                    <option value="<? echo $result2[$i]['id_role']; ?>" <? if ($result2[$i]['id_role'] == $result[$i]['id_role']) echo "selected"; ?>><? echo $result2[$i]['role_name']; ?></option>
                    <? endfor; ?>
                </select>
            </div>  
            <div class="d-grid col-12">
                <button type="submit" class="btn btn-dark">Изменить</button>
            </div>
        </form>
        
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>