<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
            
    $query = "UPDATE `courses`
    SET `active` = IF(`active` = 1, 0, 1)
    WHERE `id_course` = ".$_POST['id_course'];
    $mysql_result = mysqli_query($connectDatabase, $query);
?>