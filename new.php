<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/comment.php");
$id = ((isset($_GET['id'])) ? $_GET['id'] : 0);
$query = "SELECT `title`, `text`, `date`, `image` FROM `news`
            WHERE `id_new` = " . $id;
$mysql_result = mysqli_query($connectDatabase, $query);
if ($mysql_result === false)
    die('Error connecting to server');
$new =  sqlConvert($mysql_result)[0];
$title = $new['title'];
require_once($_SERVER['DOCUMENT_ROOT'] . "/header.php");
?>
<div class="container mt-5">
    <div>
        <h3><? echo $new['title'] ?></h3>
        <hr class="m-0">
        <p class="text-secondary mt-0"><? echo date("d", strtotime($new['date'])) . " " . $month_name[date('n', strtotime($new['date'])) - 1] . " " . date("Y", strtotime($new['date'])) . ", " . date("h:i", strtotime($new['date'])) ?></p>

        <div class="d-flex justify-content-center m-4">
            <img src="/img/news/<? echo $new['image'] ?>" alt="<? echo $new['image'] ?>" style="width: 90%;">
        </div>
    </div>
    <div class="d-flex justify-content-center m-4">
        <div style="width: 90%;"><? echo $new['text']; ?></div>
    </div>
</div>
<div class="mb-5">
    <? output_comments('new', $id) ?>
</div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>