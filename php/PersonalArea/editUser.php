<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");

//$_POST

if (isset($_POST['validation']))
{
    $error = [];
    $inputTitle = $_POST['validation']['AddNew-inputTitle'];
    $inputText = $_POST['validation']['AddNew-inputText'];
	$inputText = mysqli_real_escape_string($connectDatabase, $inputText);
    
    if ($inputText == "" || $inputTitle == "") {
        echo json_encode('Fill in all the fields');
        die();
    }
    $dateNow = date('Y-m-d H:i:s');
    $id_user = $_SESSION['user']['id_user'];
    $query = "INSERT INTO `news` (`title`, `text`, `date`, `users_id_user`) VALUES ('$inputTitle', '$inputText', '$dateNow', $id_user);";
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