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
    <script defer src="/administrator/js/question.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/js/jquery.wysibb.min.js"></script>
    <script src="/js/lang/ru.js"></script>
    <link rel="stylesheet" href="/css/wbbtheme.css" />
    <title>Добавление вопроса</title>
</head>

<body>
    <a class="btn btn-danger m-3" href="../courses">Назад</a>
    <form class="m-5 container" id="question-form">
        <input type="text" value="<? echo $_GET['id_lesson'] ?>" id="id_lesson" style="height: 0" class="invisible">
        <div class="mb-3">
            <label for="text" class="form-label">Вопрос</label>
            <input type="text" class="form-control" id="text">
        </div>
        <div class="mb-3">
            <label class="form-label">Варианты ответов</label>
            <div id="anwers">
                <div class="input-group my-3" id="answerDiv1">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" id="answerRadio1" name="answersRadio" value="1" checked>
                    </div>
                    <input type="text" class="form-control" id="answerText1">
                    <button class="btn btn-danger" type="button" onclick=deleteAnswer(1)>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                        </svg>
                    </button>
                </div>
                <div class="input-group my-3" id="answerDiv2">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" id="answerRadio2" name="answersRadio" value="2">
                    </div>
                    <input type="text" class="form-control" id="answerText2">
                    <button class="btn btn-danger" type="button" onclick=deleteAnswer(2)>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <button type="button" id="add_answer" class="btn btn-dark">Добавить вариант ответа</button>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-dark" id="submit-add">Добавить</button>
        </div>
    </form>
</body>

</html>