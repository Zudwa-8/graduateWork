<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
if ($_SESSION['admin'] != "successfully") {
    header('Location: ../index.php');
}
if (isset($_GET['id_course'])) {
    $query = "SELECT * FROM courses
        WHERE id_course = " . $_GET['id_course'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $result = sqlConvert($mysql_result);
    $query = "SELECT * FROM topics
        WHERE courses_id_course = " . $_GET['id_course'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $topics = sqlConvert($mysql_result);
}
$query = "SELECT *  FROM level_of_knowledge";
$mysql_result = mysqli_query($connectDatabase, $query);
if ($mysql_result === false) {
    die('Error connecting to database');
}
$level_of_knowledge = sqlConvert($mysql_result);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script defer src="/administrator/js/course.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/jquery.wysibb.min.js"></script>
    <script src="/js/lang/ru.js"></script>
    <link rel="stylesheet" href="/css/wbbtheme.css" />
    <title>Курсы</title>
</head>

<body class="pb-5"> 
    <a class="btn btn-danger m-3" href="../courses">Назад</a>
    <form class="m-5 mt-3 container" id="course-form">
        <?if ($_GET['id_course']) :?>
            <input type="text" value="<?echo $_GET['id_course']?>" id="id_course" style="height: 0" class="invisible">
        <?endif;?>
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" <? if ($_GET['id_course']) echo 'value="' . $result[0]['name'] . '"' ?>>
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
            <textarea rows="10" class="form-control" maxlength="65000" id="description"><?if ($_GET['id_course']) echo convertBBCode($result[0]['description'])?></textarea>
        </div>
        <!-- <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="number" class="form-control" id="price" <? if ($_GET['id_course']) echo 'value="' . $result[0]['price'] . '"' ?>>
        </div> -->
        <div class="mb-3">
            <label for="level_of_knowledge" class="form-label">Категория</label>
            <select class="form-select" id="level_of_knowledge">
                <? foreach ($level_of_knowledge as $key => $value) : ?>
                    <option value="<? echo $value['id_level_of_knowledge'] ?>" <? if ($_GET['id_course']) echo ($value['id_level_of_knowledge'] == $result[0]['level_of_knowledge_id_level_of_knowledge'] ? " selected" : "") ?>><? echo $value['name'] ?></option>
                <? endforeach ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Картинка</label>
            <? if ($_GET['id_course']) : ?>
                <div class="m-3">
                    <img src="/img/courses/<? echo $result[0]['image'] ?>" alt="<? echo $result[0]['image'] ?>" style="height: 100px;" id="img-preview" class="border border-dark border-3 rounded-3">
                </div>
            <? else : ?>
                <div class="m-3">
                    <img src="/img/courses/default.jpg" alt="default.jpg" style="height: 100px;" id="img-preview" class="border border-dark border-3 rounded-3">
                </div>
            <? endif ?>
            <input type="file" class="form-control" id="image" accept="image/*,image/jpeg">
            <input type="text" class="form-control invisible h-0" id="image_name" <? if ($_GET['id_course']) echo 'value="' . $result[0]['image'] . '"'; else echo 'value="default.jpg"' ?>>
        </div>
        <div class="d-grid">
            <? if (isset($_GET['id_course'])) : ?>
                <button type="submit" class="btn btn-dark" id="submit-edit">Сохранить</button>
            <? else : ?>
                <button type="submit" class="btn btn-dark" id="submit-add">Добавить</button>
            <? endif ?>
        </div>
    </form>
    <? if (isset($_GET['id_course'])) : ?>
        <a class="btn btn-dark m-3" href="editTopic.php?id_course=<? echo $_GET['id_course'] ?>">Добавить тему</a>
        <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Изменить</th>
                </tr>
            </thead>
            <tbody>
                <? for ($i = 0; $i < count($topics); $i++) : ?>
                    <tr>
                        <td><? echo $topics[$i]['name']; ?></td>
                        <td><? echo $topics[$i]['description']; ?></td>
                        <td><a class="btn btn-dark" href="editTopic.php?id_topic=<? echo $topics[$i]['id_topic'] ?>">Изменить</a></td>
                    </tr>
                <? endfor; ?>
            </tbody>
        </table>
    <? endif ?>
</body>

</html>