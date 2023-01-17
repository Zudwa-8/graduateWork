<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
if ($_SESSION['admin'] != "successfully") {
    header('Location: ../index.php');
}
if (isset($_GET['id_topic'])) {
    $query = "SELECT * FROM topics
        WHERE id_topic = " . $_GET['id_topic'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $result = sqlConvert($mysql_result);
    $query = "SELECT * FROM lessons
        WHERE topics_id_topic = " . $_GET['id_topic'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $lessons = sqlConvert($mysql_result);
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.6.0.js"></script>
    <script defer src="/administrator/js/topic.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/jquery.wysibb.min.js"></script>
    <script src="/js/lang/ru.js"></script>
    <link rel="stylesheet" href="/css/wbbtheme.css" />
    <title><? echo (isset($_GET['id_topic'])) ? "Изменение темы" : "Создание темы" ?></title>
</head>

<body>
    <a class="btn btn-danger m-3" href="../courses">Назад</a>
    <form class="m-5 container"  id="topic-form">
        <? if ($_GET['id_topic']) : ?>
            <input type="text" value="<? echo $_GET['id_topic'] ?>" id="id_topic" style="height: 0" class="invisible">
        <? endif; ?>
        <? if ($_GET['id_course']) : ?>
            <input type="text" value="<? echo $_GET['id_course'] ?>" id="id_course" style="height: 0" class="invisible">
        <? endif; ?>
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" <? if ($_GET['id_topic']) echo 'value="' . $result[0]['name'] . '"' ?>>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <script>
                $(document).ready(function() {
                    var wbbOpt = {
                        lang: "ru",
                        imgupload: false,
                        buttons: "bold,italic,underline"
                    }
                    $("#description").wysibb(wbbOpt);
                });
            </script>
            <textarea rows="10" class="form-control" maxlength="65000" id="description"><?if ($_GET['id_topic']) echo convertBBCode($result[0]['description'])?></textarea>
        </div>
        <div class="d-grid">
            <? if (isset($_GET['id_course'])) : ?>
                <button type="submit" class="btn btn-dark" id="submit-add">Добавить</button>
            <? else : ?>
                <button type="submit" class="btn btn-dark" id="submit-edit">Сохранить</button>
            <? endif ?>
        </div>
    </form>
    <? if (isset($_GET['id_topic'])) : ?>
        <a class="btn btn-dark m-3" href="editLesson.php?id_topic=<? echo $_GET['id_topic'] ?>">Добавить урок</a>
        <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Изменить</th>
                </tr>
            </thead>
            <tbody>
                <? for ($i = 0; $i < count($lessons); $i++) : ?>
                    <tr>
                        <td><? echo $lessons[$i]['name']; ?></td>
                        <td>
                            <div><? echo $lessons[$i]['text']; ?></div>
                        </td>
                        <td><a class="btn btn-dark" href="editLesson.php?id_lesson=<? echo $lessons[$i]['id_lesson'] ?>">Изменить</a></td>
                    </tr>
                <? endfor; ?>
            </tbody>
        </table>
    <? endif ?>
</body>

</html>