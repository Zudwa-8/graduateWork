<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

$document = new \PhpOffice\PhpWord\TemplateProcessor($_SERVER['DOCUMENT_ROOT'] .'/template.docx');


$query = "SELECT `courses`.`name` AS `course`, `users`.`fullname` AS `name`, 
(SELECT COUNT(`id_lesson`) FROM `lessons` 
	INNER JOIN `topics` ON `topics`.`id_topic` = `lessons`.`topics_id_topic`
	WHERE `courses_id_course` = `id_course`) AS `count_lesson`,
(SELECT COUNT(`id_progress_learning`) FROM `progress_learning` 
	WHERE `users_id_user` = ".$_SESSION['user']['id_user']." AND `lessons_id_lesson` IN (SELECT `id_lesson` FROM `lessons` WHERE `topics_id_topic` IN (SELECT `id_topic` FROM `topics` WHERE `topics`.`courses_id_course`  = `id_course`))) AS `count_progress`
FROM `courses`
INNER JOIN `purchased_courses` ON `purchased_courses`.`courses_id_course` = `courses`.`id_course`
INNER JOIN `users` ON `purchased_courses`.`users_id_user` = `users`.`id_user`
WHERE `users`.`id_user` = ".$_SESSION['user']['id_user']." AND `courses`.`id_course` = ".$_GET['id'];

$mysql_result = mysqli_query($connectDatabase, $query);
    
if ($mysql_result === false) {
    die("Error connecting to database");
    die(json_encode(array('msg' => "Error connecting to database", 'query' => $query)));
}

$result = sqlConvert(mysqli_query($connectDatabase, $query))[0];

if ($result['count_lesson'] > $result['count_progress']){
    die("Incomplete course");
    //die(json_encode(array('msg' => "Incomplete course")));
}

$uploadDir =  __DIR__;
$outputFile = 'certificate.docx';

$document->setValue('name', $result['name']);
$document->setValue('course', $result['course']);
$document->setValue('date', date('Y-m-d'));

$document->saveAs($outputFile);


// Имя скачиваемого файла
$downloadFile = $outputFile;

// Контент-тип означающий скачивание
header("Content-Type: application/octet-stream");

// Размер в байтах
header("Accept-Ranges: bytes");

// Размер файла
header("Content-Length: ".filesize($downloadFile));

// Расположение скачиваемого файла
header("Content-Disposition: attachment; filename=".$downloadFile);  

// Прочитать файл
readfile($downloadFile);

unlink($outputFile);
?>