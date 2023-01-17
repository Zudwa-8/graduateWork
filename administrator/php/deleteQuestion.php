<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
            
    $query = "DELETE FROM `answers` 
    WHERE `questions_id_question` = ".$_POST['id_question'];
    $mysql_result = mysqli_query($connectDatabase, $query);

    $query = "DELETE FROM `questions` 
    WHERE `id_question` = ".$_POST['id_question'];
    $mysql_result = mysqli_query($connectDatabase, $query);
?>