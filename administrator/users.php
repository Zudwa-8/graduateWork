<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
if ($_SESSION['admin'] != "successfully") {
    header('Location: ../index.php');
}

$query = "SELECT * FROM users  ";
if (isset($_POST['Course']) || isset($_POST['Search'])) {
    if (isset($_POST['Course']) && $_POST['Course'] != 0) {
        $query .= "INNER JOIN `purchased_courses` ON `purchased_courses`.`users_id_user` = `users`.`id_user` WHERE ";
        $query .= "`courses_id_course` = " . $_POST['Course'] . " AND ";
    } else {
        $query .= " WHERE ";
    }
    if (isset($_POST['Search'])) {
        $query .= "`users`.`fullname` LIKE '%" . mysqli_real_escape_string($connectDatabase, $_POST['Search']) . "%' AND ";
    }
    $query = substr($query, 0, -5);
}
$mysql_result = mysqli_query($connectDatabase, $query);
if ($mysql_result === false) {
    die('Error connecting to database');
}
$result = sqlConvert($mysql_result);
$countComplete = 0;
for ($i = 0; $i < count($result); $i++) {

    $query = "SELECT `courses`.`id_course`, `courses`.`name` AS `course_name`,
                (SELECT COUNT(`id_lesson`) FROM `lessons` 
                INNER JOIN `topics` ON `topics`.`id_topic` = `lessons`.`topics_id_topic`
                WHERE `courses_id_course` = `id_course`) AS `count_lesson`,
                (SELECT COUNT(`id_progress_learning`) FROM `progress_learning` 
                WHERE `users_id_user` = " . $result[$i]['id_user'] .
        " AND `lessons_id_lesson` IN (SELECT `id_lesson` FROM `lessons` WHERE `topics_id_topic` IN (SELECT `id_topic` FROM `topics` WHERE `topics`.`courses_id_course`  = `id_course`))) AS `count_progress`
                  FROM `users`
                    INNER JOIN `purchased_courses` ON `purchased_courses`.`users_id_user` = `users`.`id_user`
                    INNER JOIN `courses` ON `purchased_courses`.`courses_id_course` = `courses`.`id_course`
                    WHERE `users_id_user` = " . $result[$i]['id_user'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false)
        die(mysqli_error($connectDatabase));
    $result[$i]['course'] = sqlConvert($mysql_result);
    if (count($result[$i]['course']) > 0){
        for ($j=0; $j < count($result[$i]['course']); $j++) { 
            if ($result[$i]['course'][$j]['count_lesson'] == $result[$i]['course'][$j]['count_progress'] && ($result[$i]['course'][$j]['id_course'] == $_POST['Course'] || !isset($_POST['Course']))) {
                $countComplete++;
            }
        }
    }
}
// echo '<pre>';
// var_dump($result);
// echo '</pre>';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script defer src="js/news.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/jquery.wysibb.min.js"></script>
    <script src="/js/lang/ru.js"></script>
    <link rel="stylesheet" href="/css/wbbtheme.css" />
    <title>Пользователи</title>
</head>

<body>
    <a class="btn btn-danger m-3" href="index.php">Назад</a>
    <form class="m-5" method="POST" action="users.php">
        <h3 class="text-center fw-bold">Фильтры</h3>
        <hr class="mt-0">
        <div class="row g-3">
            <div class="mb-3 col-auto">
                <label for="Search" class="form-label">Поиск по ФИО</label>
                <input type="text" class="form-control" id="Search" name="Search" placeholder="Поиск по ФИО" <? if (isset($_POST['Search'])) echo 'value="' . $_POST['Search'] . '"' ?>>
            </div>
            <div class="mb-3 col-auto">
                <label for="Course" class="form-label">Курс</label>
                <select class="form-select" id="Course" name="Course">
                    <option value="0">Все курсы</option>
                    <?
                    $query = "SELECT `id_course`, `name`, COUNT(`courses_id_course`) AS 'count' FROM `courses` 
                    INNER JOIN `purchased_courses` ON `purchased_courses`.`courses_id_course` = `courses`.`id_course`
                    GROUP BY  `id_course`";
                    $mysql_result = mysqli_query($connectDatabase, $query);
                    if ($mysql_result === false)
                        die('Error connecting to database: ' . mysqli_error($connectDatabase));
                    $resultCourse = sqlConvert($mysql_result);
                    for ($i = 0; $i < count($resultCourse); $i++) :
                    ?>
                        <option value="<? echo $resultCourse[$i]['id_course'] ?>" <? if (isset($_POST['Course']) && $_POST['Course'] == $resultCourse[$i]['id_course']) echo 'selected' ?>><? echo $resultCourse[$i]['name'] . " (" . $resultCourse[$i]['count'] . ")" ?></option>
                    <? endfor; ?>
                </select>
            </div>
            <? if (isset($_POST['Course']) || isset($_POST['Search'])) : ?>
                <a type="submit" class="btn btn-danger ms-2 me-2 my-5 col-auto" href="users.php">Сбросить фильтры</a>
            <? endif; ?>
            <button type="submit" class="btn btn-dark ms-2 my-5 col-auto">Применить фильтры</button>
        </div>
    </form>
    <div class="m-5">
        <h3>Пользователи</h3>
        <hr>
        <div>
            <p>Количество пользователей: <? echo count($result) ?></p>
            <p>Всего закончено курсов: <? echo $countComplete ?></p>
        </div>
        <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя пользователя</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Дата регистрации</th>
                    <th scope="col">Курсы и прогресс прохождения</th>
                </tr>
            </thead>
            <tbody>
                <?
                for ($i = 0; $i < count($result); $i++) :
                ?>
                    <tr>
                        <th scope="row"><? echo $i + 1; ?></th>
                        <td><? echo $result[$i]['username']; ?></td>
                        <td><? echo $result[$i]['fullname']; ?></td>
                        <td><? echo $result[$i]['date_register']; ?></td>
                        <td>

                            <a class="btn btn-dark" data-bs-toggle="collapse" href="#collapseExample<? echo $i ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                Показать прогресс
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div class="collapse" id="collapseExample<? echo $i ?>">
                                <div class="card card-body">
                                    <?

                                    if (count($result[$i]['course']) > 0) :
                                        for ($j = 0; $j < count($result[$i]['course']); $j++) :
                                            $progress = ($result[$i]['course'][$j]['count_lesson'] == 0) ? 100 : round($result[$i]['course'][$j]['count_progress'] * 100 / $result[$i]['course'][$j]['count_lesson'], 2);
                                    ?>
                                            <p><? echo $result[$i]['course'][$j]['course_name']; ?>
                                            <div class="progress position-relative" style="height: 30px; width: 200px">
                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <? echo $progress ?>%;" aria-valuenow="<? echo $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: <? echo 100 - $progress ?>%;" aria-valuenow="<? echo 100 - $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                <p class="position-absolute start-50 top-50 translate-middle text-center text-light"><? echo $progress ?>%</p>

                                            </div>
                                            </p>
                                        <? endfor; ?>
                                    <? else : ?>
                                        <p>Пользователь не записан ни на один курс.</p>
                                    <? endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <? endfor; ?>
            </tbody>
        </table>
    </div>
</body>

</html>