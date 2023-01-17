<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation']))
{
    $error = [];
    $inputTitle = $_POST['validation']['AddNew-inputTitle'];
    $inputText = $_POST['validation']['AddNew-inputText'];
    $image_name = $_POST['validation']['image_name'];
	$inputText = mysqli_real_escape_string($connectDatabase, $inputText);
    
    if ($inputText == "" || $inputTitle == "") {
        echo json_encode('Fill in all the fields');
        die();
    }
    $dateNow = date('Y-m-d H:i:s');
    $id_user = $_SESSION['user']['id_user'];
    $query = "INSERT INTO `news` (`title`, `text`, `date`, `image`) VALUES ('$inputTitle', '$inputText', '$dateNow', '$image_name');";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false){
        echo json_encode('Error connecting to database');
        die();
    }
    echo json_encode('News added successfully');
}
else{
    echo json_encode('Failed to get data');
}
?>