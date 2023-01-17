<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
if ($_SESSION['admin'] != "successfully") {
    header('Location: ../index.php');
}
if (isset($_GET['id_new'])) {
    $query = "SELECT * FROM news
    WHERE id_new = " . $_GET['id_new'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false) {
        die('Error connecting to database');
    }
    $result = sqlConvert($mysql_result);
}

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
    <title>Курсы</title>
</head>

<body>
    <a class="btn btn-danger m-3" href="index.php">Назад</a>
    <form class="needs-validation m-5 mt-3 container" id="AddNew-form" novalidate>
        <div class="mb-3">
            <label for="AddNew-inputTitle" class="form-label">Заголовок</label>
            <input type="text" class="form-control" id="AddNew-inputTitle" maxlength="256" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Картинка</label>
            <div class="m-3">
                <img src="/img/courses/default.jpg" alt="default.jpg" style="height: 100px;" id="img-preview" class="border border-dark border-3 rounded-3">
            </div>
            <input type="file" class="form-control" id="image" accept="image/*,image/jpeg">
            <input type="text" class="form-control invisible h-0" id="image_name" value="default.jpg">
        </div>
        <div class="mb-3">
            <label for="AddNew-inputText" class="form-label">Текст</label>
            <script>
                $(document).ready(function() {
                    var wbbOpt = {
                        lang: "ru",
                        imgupload: false,
                        buttons: "bold,italic,underline"
                    }
                    $("#AddNew-inputText").wysibb(wbbOpt);
                    $("#AddNew-inputText").sync();

                });
            </script>
            <textarea class="form-control" id="AddNew-inputText" rows="10" maxlength="65000"></textarea>
            <div class="invalid-feedback"></div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-dark">Добавить</button>
        </div>
    </form>
    <div class="m-5">
        <h3>Все новости</h3>
        <hr>
        <table class="table">
            <thead class="table-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?
                $query = "SELECT `id_new`, `title`, `text`, `date` FROM news";
                $mysql_result = mysqli_query($connectDatabase, $query);
                if ($mysql_result === false) {
                    echo json_encode('Error connecting to database');
                    die();
                }
                $result = sqlConvert($mysql_result);
                for ($i = 0; $i < count($result); $i++) :
                ?>
                    <tr>
                        <th scope="row"><? echo $i + 1; ?></th>
                        <td><? echo $result[$i]['title']; ?></td>
                        <td><? echo $result[$i]['text']; ?></td>
                        <td><? echo $result[$i]['date']; ?></td>
                        <td><button class="btn btn-danger" onclick="deleteField(<? echo $result[$i]['id_new'] . ', \'' . $result[$i]['title'] . '\'' ?>)">Удалить</button></td>
                    </tr>
                <? endfor; ?>
            </tbody>
        </table>
    </div>
</body>

</html>