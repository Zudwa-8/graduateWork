<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
date_default_timezone_set('Europe/Moscow');

if (isset($_POST['validation'])) {
    $error = [];

    $id_lesson = $_POST['validation']['id_lesson'];
    $text = $_POST['validation']['text'];
    $radio = $_POST['validation']['radio'];
    $answers = $_POST['validation']['answers'];

    $text = mysqli_real_escape_string($connectDatabase, $text);
    foreach ($answers as $key => $value) {
        $value['text'] = mysqli_real_escape_string($connectDatabase, $value['text']);
    }

    if ($text == "" || $radio == "" || in_array("", $answers)) {
        die(json_encode(array('msg' => "Fill in all the fields")));
    }

    $query = "INSERT INTO `questions` (`text`, `lessons_id_lesson`) 
    VALUES ('$text', $id_lesson);";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die(json_encode(array('msg' => "Error connecting to database")));
    }
    $query = "SELECT id_question FROM `questions` 
    WHERE `text` = '" . $text . "'
    AND `lessons_id_lesson` = " . $id_lesson . "
    ORDER BY `id_question` DESC";
    $id = sqlConvert(mysqli_query($connectDatabase, $query))[0]['id_question'];

    foreach ($answers as $key => $value) {
        $query = "INSERT INTO `answers` (`text`, `questions_id_question`, `is_it_true`) 
        VALUES ('".$value['text']."', $id, ".(($radio == $value['value'])?1:0).");";
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false) {
            die(json_encode(array('msg' => "Error connecting to database")));
        }
    }

    die(json_encode(array(
        'msg' => "Added successfully",
        'id' => $id_lesson
    )));
} else {
    die(json_encode(array('msg' => "Failed to get data", 'POST' => $_POST)));
}
