<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
            
    $query = "DELETE FROM `news` 
    WHERE `news`.`id_new` = ".$_POST['id_new'];
    $mysql_result = mysqli_query($connectDatabase, $query);
?>