<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];
    $id_topic = $_POST['validation']['id_topic'];
    $name = $_POST['validation']['name'];
    $description = $_POST['validation']['description'];

    $name = mysqli_real_escape_string($connectDatabase, $name);
    $description = mysqli_real_escape_string($connectDatabase, $description);

    if ($name == "" || $description == "" || $id_topic == "") {
        die(json_encode(array('msg' => "Fill in all the fields")));
    }

    $query = "UPDATE `topics`
            SET
            `name` = '$name',
            `description` = '$description'
            WHERE `id_topic` = $id_topic
    ";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die(json_encode(array('msg' => "Error connecting to database")));
    }
    die(json_encode(array(
        'msg' => "Added successfully"
    )));
} else {
    die(json_encode(array('msg' => "Failed to get data", 'POST' => $_POST)));
}
