<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];
    $name = $_POST['validation']['name'];
    $description = $_POST['validation']['description'];
    $id_topic = $_POST['validation']['id_topic'];

    $name = mysqli_real_escape_string($connectDatabase, $name);
    $description = mysqli_real_escape_string($connectDatabase, $description);

    if ($name == "" || $description == "" || $id_topic == "") {
        die(json_encode(array('msg' => "Fill in all the fields")));
    }

    $query = "INSERT INTO `lessons` (`name`, `text`, `topics_id_topic`) 
    VALUES ('$name', '$description', $id_topic);";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die(json_encode(array('msg' => "Error connecting to database")));
    }
    $query = "SELECT id_lesson FROM `lessons` 
    WHERE `name` = '" . $name . "'
    AND `text` = '" . $description."'
    AND `topics_id_topic` = " . $id_topic;
    $mysql_result = sqlConvert(mysqli_query($connectDatabase, $query))[0]['id_lesson'];
    $id = ($mysql_result);
    die(json_encode(array(
            'msg' => "Added successfully",
            'id' => $mysql_result
        )));
} else {
    die(json_encode(array('msg' => "Failed to get data", 'POST' => $_POST)));
}
