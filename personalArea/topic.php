<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
if (!$_SESSION['user'])
    header('Location: ../index.php');
else{
    $query = "SELECT `courses`.`name` AS `course_name` FROM `purchased_courses`
        INNER JOIN `courses` ON `courses`.`id_course` = `purchased_courses`.`courses_id_course`
        WHERE `users_id_user` = ".$_SESSION['user']['id_user'].
        " AND `courses_id_course` = ".$_GET['id'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false)
        die(mysqli_error($connectDatabase)); 
    $result = sqlConvert($mysql_result);
    if (!isset($result)){
        header('Location: /personalArea/index.php');
    }
}
$title = "Мои курсы - ".$result[0]['course_name'];
require_once($_SERVER['DOCUMENT_ROOT']."/header.php"); 
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "Courses";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>
    <div class="col-md-10">
      <? //DISTINCT
        $query = "SELECT `id_topic`, `topics`.`name` AS `name`, `description`, `courses_id_course`, 
        (SELECT COUNT(`id_lesson`) FROM `lessons` WHERE `topics_id_topic` = `id_topic`) AS `count_lesson`,
        (SELECT COUNT(`id_progress_learning`) FROM `progress_learning` WHERE `users_id_user` = ".$_SESSION['user']['id_user']." AND `lessons_id_lesson` IN (SELECT `id_lesson` FROM `lessons` WHERE `topics_id_topic` = `id_topic`)) AS `count_progress`
        FROM `topics`
            WHERE `courses_id_course` = ".$_GET['id'];
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false)
            die(mysqli_error($connectDatabase)); 
        $result = sqlConvert($mysql_result);
       
      ?>
      <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Прогресс</th>
                    <th scope="col">Выбрать тему</th>
                </tr>
            </thead>
            <tbody>
                <? 
                    for ($i=0; $i < count($result); $i++) :  
                    $progress = ($result[$i]['count_lesson'] == 0)?100:round($result[$i]['count_progress'] * 100 / $result[$i]['count_lesson'], 2);
                ?>
                <tr>
                    <th scope="row"><? echo $i+1; ?></th>
                    <td><? echo $result[$i]['name']; ?></td>
                    <td><? echo $result[$i]['description']; ?></td>
                    <td>
                        <div class="progress position-relative" style="height: 30px;">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <? echo $progress ?>%;" aria-valuenow="<? echo $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: <? echo 100-$progress ?>%;" aria-valuenow="<? echo 100-$progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            <p class="position-absolute start-50 top-50 translate-middle text-center text-light"><? echo $progress ?>%</p>
                        </div>
                    </td>
                    <td><a class="btn btn-dark" href="/PersonalArea/lesson.php?id=<? echo $result[$i]['id_topic'] ?>">Выбрать тему</a></td>
                </tr>
                <? endfor; ?>
            </tbody>
        </table>
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>