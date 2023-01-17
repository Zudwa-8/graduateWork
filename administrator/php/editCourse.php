<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];
    $id_course = $_POST['validation']['id_course'];
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

    $query = "UPDATE `courses`
            SET
            `name` = '$name',
            `description` = '$description',
            `level_of_knowledge_id_level_of_knowledge` = $level_of_knowledge,
            `image` = '$image_name'
            WHERE `id_course` = $id_course
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
