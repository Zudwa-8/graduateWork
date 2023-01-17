<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
if (!$_SESSION['user'])
    header('Location: ../index.php');
$headers .= '<script defer src="/js/getDoc.js"></script>';
$title = "Мои курсы";
require_once($_SERVER['DOCUMENT_ROOT'] . "/header.php");
?>
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <?php
            $liActive = "Courses";
            require_once($_SERVER['DOCUMENT_ROOT'] . "/personalArea/menu.php");
            ?>
        </div>
        <div class="col-md-10">
            <?
            $query = "SELECT `id_course`, `courses`.`name` AS `course_name`, `description`, `level_of_knowledge`.`name` AS `level_name`,
        (SELECT COUNT(`id_lesson`) FROM `lessons` 
        INNER JOIN `topics` ON `topics`.`id_topic` = `lessons`.`topics_id_topic`
        WHERE `courses_id_course` = `id_course`) AS `count_lesson`,
        (SELECT COUNT(`id_progress_learning`) FROM `progress_learning` 
        WHERE `users_id_user` = " . $_SESSION['user']['id_user'] .
                " AND `lessons_id_lesson` IN (SELECT `id_lesson` FROM `lessons` WHERE `topics_id_topic` IN (SELECT `id_topic` FROM `topics` WHERE `topics`.`courses_id_course`  = `id_course`))) AS `count_progress`
          FROM `courses`
            INNER JOIN `purchased_courses` ON `purchased_courses`.`courses_id_course` = `courses`.`id_course`
            INNER JOIN `level_of_knowledge` ON `level_of_knowledge`.`id_level_of_knowledge` = `courses`.`level_of_knowledge_id_level_of_knowledge`
            WHERE `users_id_user` = " . $_SESSION['user']['id_user'];
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
                        <th scope="col">Cложность</th>
                        <th scope="col">Прогресс</th>
                        <th scope="col">Начать курс</th>
                        <th scope="col">Получить сертификат</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    for ($i = 0; $i < count($result); $i++) :
                        $progress = ($result[$i]['count_lesson'] == 0) ? 100 : round($result[$i]['count_progress'] * 100 / $result[$i]['count_lesson'], 2);



                    ?>
                        <tr>
                            <th scope="row"><? echo $i + 1; ?></th>
                            <td><? echo $result[$i]['course_name']; ?></td>
                            <td><? echo $result[$i]['description']; ?></td>
                            <td><? echo $result[$i]['level_name']; ?></td>
                            <td>
                                <div class="progress position-relative" style="height: 30px;">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <? echo $progress ?>%;" aria-valuenow="<? echo $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: <? echo 100 - $progress ?>%;" aria-valuenow="<? echo 100 - $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    <p class="position-absolute start-50 top-50 translate-middle text-center text-light"><? echo $progress ?>%</p>
                                </div>
                            </td>
                            <td><a class="btn btn-dark" href="/PersonalArea/topic.php?id=<? echo $result[$i]['id_course'] ?>">Начать курс</a></td>
                            <td>
                                <?if (($result[$i]['count_progress'] == $result[$i]['count_lesson'])) :?>
                                <a class="btn btn-dark" href="http://cppk/php/GetDoc.php?id=<?echo $result[$i]['id_course']?>">Получить сертификат</a>
                                    <?else:?>
                                <button class="btn btn-dark" onclick="alert('Сначала завершите курс')">Получить сертификат</button>
                                    <?endif;?>
                            </td>
                        </tr>
                    <? endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>