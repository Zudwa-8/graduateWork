<?
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");

if (!$_SESSION['user']) {
    die('User is not found');
}
else {
    $query = "SELECT * FROM `purchased_courses`
        WHERE `users_id_user` = ".$_SESSION['user']['id_user'].
        " AND `courses_id_course` = ".$_POST['id'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false)
        die(mysqli_error($connectDatabase)); 
    $result = sqlConvert($mysql_result);
    if (count($result) > 0) {
        die('Course purchased'); 
    }
    else{
        $document_number = 'A1234567';
        $query = "INSERT INTO `purchased_courses` (`users_id_user`, `courses_id_course`, `document_number`, `payment_verification`)
        VALUES (".$_SESSION['user']['id_user'].", ".$_POST['id'].", '$document_number', 0)";
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false)
            die(mysqli_error($connectDatabase)); 
        die('Course added');
    }
}
?>