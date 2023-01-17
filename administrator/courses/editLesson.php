<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
if ($_SESSION['admin'] != "successfully") {
    header('Location: ../index.php');
}
if (isset($_GET['id_lesson'])) {
    $query = "SELECT * FROM lessons
        WHERE id_lesson = " . $_GET['id_lesson'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $result = sqlConvert($mysql_result);
    $query = "SELECT * FROM questions
        WHERE lessons_id_lesson = " . $_GET['id_lesson'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database2');
    }
    $questions = sqlConvert($mysql_result);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.6.0.js"></script>
    <script defer src="/administrator/js/lesson.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/jquery.wysibb.min.js"></script>
    <script src="/js/lang/ru.js"></script>
    <link rel="stylesheet" href="/css/wbbtheme.css" />
    <title><? echo (isset($_GET['id_lesson'])) ? "Изменение урока" : "Создание урока" ?></title>
</head>

<body>
    <a class="btn btn-danger m-3" href="../courses">Назад</a>
    <form class="m-5 container" id="lesson-form">
        <? if ($_GET['id_topic']) : ?>
            <input type="text" value="<? echo $_GET['id_topic'] ?>" id="id_topic" style="height: 0" class="invisible">
        <? endif; ?>
        <? if ($_GET['id_lesson']) : ?>
            <input type="text" value="<? echo $_GET['id_lesson'] ?>" id="id_lesson" style="height: 0" class="invisible">
        <? endif; ?>
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" <? if ($_GET['id_lesson']) echo 'value="' . $result[0]['name'] . '"' ?>>
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
            <textarea rows="10" class="form-control" maxlength="65000" id="description"><? if ($_GET['id_lesson']) echo convertBBCode($result[0]['text']) ?></textarea>
        </div>
        <div class="d-grid">
            <? if (isset($_GET['id_topic'])) : ?>
                <button type="submit" class="btn btn-dark" id="submit-add">Добавить</button>
            <? else : ?>
                <button type="submit" class="btn btn-dark" id="submit-edit">Сохранить</button>
            <? endif ?>
        </div>
    </form>
    <? if (isset($_GET['id_lesson'])) : ?>
        <a class="btn btn-dark m-3" href="editQuestion.php?id_lesson=<? echo $_GET['id_lesson'] ?>">Добавить вопрос</a>
        <table class="table mb-5">
            <thead class="table-white">
                <tr>
                    <th scope="col">Вопрос</th>
                    <th scope="col">Посмотреть ответы</th>
                    <th scope="col">Удалить вопрос</th>
                </tr>
            </thead>
            <tbody>
                <?
                if (count($questions) > 0) :
                    for ($i = 0; $i < count($questions); $i++) :
                        $query = "SELECT * FROM answers
                WHERE questions_id_question = " . $questions[$i]['id_question'];
                        $mysql_result = mysqli_query($connectDatabase, $query);
                        if ($mysql_result === false) {
                            die('Error connecting to database');
                        }
                        $answers = sqlConvert($mysql_result);
                ?>
                        <tr>
                            <td><? echo $questions[$i]['text']; ?></td>
                            <td>
                                <ul class="list-group  list-group-numbered">
                                    <?
                                    $query = "SELECT * FROM answers
                                WHERE questions_id_question = " . $questions[$i]['id_question'];
                                    $mysql_result = mysqli_query($connectDatabase, $query);
                                    if ($mysql_result === false) {
                                        die('Error connecting to database2');
                                    }
                                    $answers = sqlConvert($mysql_result);
                                    for ($j = 0; $j < Count($answers); $j++) :
                                    ?>

                                        <li class="list-group-item <? if ($answers[$j]['is_it_true']) echo "bg-success text-white" ?>" aria-current="true"><? echo $answers[$j]['text'] ?></li>
                                    <? endfor; ?>
                                </ul>
                            </td>
                            <td><button class="btn btn-danger" onclick="deleteField(<? echo $questions[$i]['id_question'] . ', \'' . $questions[$i]['text'] . '\'' ?>)">Удалить</button></td>
                        </tr>
                    <? endfor; ?>
                <? else : ?>
                    <tr><td colspan="3" class="text-center">Вопросов на данный урок нет.</td></tr>
                <? endif; ?>
            </tbody>
        </table>
        <div style="height: 1px;"> </div>
    <? endif; ?>
</body>

</html>