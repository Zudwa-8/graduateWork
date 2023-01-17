<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];
    $name = $_POST['validation']['name'];
    $description = $_POST['validation']['description'];
    $price = $_POST['validation']['price'];
    $image_name = $_POST['validation']['image_name'];
    $level_of_knowledge = $_POST['validation']['level_of_knowledge'];

    $name = mysqli_real_escape_string($connectDatabase, $name);
    $description = mysqli_real_escape_string($connectDatabase, $description);
    $image_name = mysqli_real_escape_string($connectDatabase, $image_name);

    if ($name == "" || $description == "" || $price == "" || $image_name == "" || $level_of_knowledge == "") {
        die(json_encode(array('msg' => "Fill in all the fields")));
    }

    $query = "INSERT INTO `courses` (`name`, `description`, `price`, `level_of_knowledge_id_level_of_knowledge`, `image`) 
    VALUES ('$name', '$description', $price, $level_of_knowledge, '$image_name');";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die(json_encode(array('msg' => "Error connecting to database")));
    }
    $query = "SELECT id_course FROM `courses` 
    WHERE `name` = '" . $name . "'
    AND `description` = '" . $description . "'
    AND `price` = " . $price . "
    AND `image` = '" . $image_name . "'
    AND `level_of_knowledge_id_level_of_knowledge` = " . $level_of_knowledge;
    $mysql_result = sqlConvert(mysqli_query($connectDatabase, $query))[0]['id_course'];
    $id = ($mysql_result);
    die(json_encode(array(
            'msg' => "Added successfully",
            'id' => $mysql_result
        )));
} else {
    die(json_encode(array('msg' => "Failed to get data", 'POST' => $_POST)));
}
