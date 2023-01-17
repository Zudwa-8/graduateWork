<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    if ($_SESSION['admin'] != "successfully") {
        header('Location: ../index.php');
    }
    $query = "SELECT level_of_knowledge.name AS level, courses.name, description, id_course, active  FROM courses
    INNER JOIN level_of_knowledge ON level_of_knowledge.id_level_of_knowledge = courses.level_of_knowledge_id_level_of_knowledge
    ORDER BY id_course";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false){
        die('Error connecting to database');
    }
    $result = sqlConvert($mysql_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <title>Курсы</title>
</head>
<script>
    function activeCourse(id) {
        var formData = new FormData();
        formData.append("id_course", id);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/administrator/php/activeCourse.php", false);
        xhr.send(formData);
        location.reload();
    }
</script>
<body>
    <a class="btn btn-danger m-3" href="../index.php">Назад</a>
    <a class="btn btn-dark m-3" href="editCourse.php">Добавить курс</a>
    <table class="table">
        <thead class="table-white">
            <tr>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Категория</th>
                <th scope="col">Статус</th>
                <th scope="col">Изменить</th>
            </tr>
            </thead>
            <tbody>
                <? for ($i=0; $i < count($result); $i++) : ?>
                <tr>
                    <td><? echo $result[$i]['name']; ?></td>
                    <td><? echo $result[$i]['description']; ?></td>
                    <td><? echo $result[$i]['level']; ?></td>
                    <td><button class="btn <? echo ($result[$i]['active'] == 1)?"btn-danger":"btn-success"; ?>" style="width: 100px;" onclick="activeCourse(<? echo $result[$i]['id_course']; ?>)"><? echo ($result[$i]['active'] == 1)?"Скрыть":"Открыть"; ?></button></td>
                    <td><a class="btn btn-dark" href="editCourse.php?id_course=<?echo $result[$i]['id_course']?>">Изменить</a></td>
                </tr>
                <? endfor; ?>
        </tbody>        
    </table>
</body>
</html>