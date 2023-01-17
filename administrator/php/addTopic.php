<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];
    $name = $_POST['validation']['name'];
    $description = $_POST['validation']['description'];
    $id_course = $_POST['validation']['id_course'];

    $name = mysqli_real_escape_string($connectDatabase, $name);
    $description = mysqli_real_escape_string($connectDatabase, $description);

    if ($name == "" || $description == "" || $id_course == "") {
        die(json_encode(array('msg' => "Fill in all the fields")));
    }

    $query = "INSERT INTO `topics` (`name`, `description`, `courses_id_course`) 
    VALUES ('$name', '$description', $id_course);";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die(json_encode(array('msg' => "Error connecting to database")));
    }
    $query = "SELECT id_topic FROM `topics` 
    WHERE `name` = '" . $name . "'
    AND `description` = '" . $description."'
    AND `courses_id_course` = " . $id_course;
    $mysql_result = sqlConvert(mysqli_query($connectDatabase, $query))[0]['id_topic'];
    $id = ($mysql_result);
    die(json_encode(array(
            'msg' => "Added successfully",
            'id' => $mysql_result
        )));
} else {
    die(json_encode(array('msg' => "Failed to get data", 'POST' => $_POST)));
}
