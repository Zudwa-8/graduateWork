<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
$title = "Курсы";
$headers .= '<script src="/js/jquery.dotdotdot.min.js"></script>';
$headers .= '<script>$(function(){$(".card-text").dotdotdot();});</script>';
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/header.php");
?>
<div class="container mb-5">
    <div class="mb-5 mt-5 text-center bd-masthead">
        <h1 class="display-5 fw-bold">Курсы</h1>
        <hr class="mt-0">
        <div class="mx-auto fs-4">
            <p>
                На платформе можнополучить знания по актуальным темам и востребованные навыки. Мы следим за актуальностью материала.
            </p>
            <p>
                Формат обучения - самостоятельный. Вы будете взаимодействовать один на один с интерактивным интерфейсом, однако без обратной связи вы не останетесь.
            </p>
            <p>
                Срок обучения - индивидуальный. Он зависит от вашего начального уровня и того времени, которое вы готовы уделять в день для изучения.

            </p>
        </div>
    </div>
    <form class="mb-5" method="POST" action="courses.php">
        <h3 class="text-center fw-bold">Фильтры</h3>
        <hr class="mt-0">
        <div class="row g-3">
            <div class="mb-3 col-auto">
                <label for="Search" class="form-label">Поиск</label>
                <input type="text" class="form-control" id="Search" name="Search" placeholder="Поиск" <? if (isset($_POST['Search'])) echo 'value="' . $_POST['Search'] . '"' ?>>
            </div>
            <div class="mb-3 col-auto">
                <label for="Category" class="form-label">Категория</label>
                <select class="form-select" id="Category" name="Category">
                    <option value="0">Все категории</option>
                    <?
                    $query = "SELECT `id_level_of_knowledge`, `name` FROM `level_of_knowledge`";
                    $mysql_result = mysqli_query($connectDatabase, $query);
                    if ($mysql_result === false)
                        die('Error connecting to database: ' . mysqli_error($connectDatabase));
                    $result = sqlConvert($mysql_result);
                    for ($i = 0; $i < count($result); $i++) :
                    ?>
                        <option value="<? echo $result[$i]['id_level_of_knowledge'] ?>" <? if (isset($_POST['Category']) && $_POST['Category'] == $result[$i]['id_level_of_knowledge']) echo 'selected' ?>><? echo $result[$i]['name'] ?></option>
                    <? endfor; ?>
                </select>
            </div>
                <? if (isset($_POST['Category']) || isset($_POST['Search'])) : ?>
                    <a type="submit" class="btn btn-danger ms-2 me-2 my-5 col-auto" href="courses.php">Сбросить фильтры</a>
                <? endif; ?>
                <button type="submit" class="btn btn-dark ms-2 my-5 col-auto">Применить фильтры</button>
        </div>
    </form>
        <h3 class="text-center fw-bold">Список курсов</h3>
        <hr class="mt-0">
    <?php
    $query = "SELECT `id_course`, `courses`.`name` AS `course_name`, `description`, `price`, `image`, `level_of_knowledge`.`name` AS `level_name` FROM `courses`
        INNER JOIN `level_of_knowledge` ON `level_of_knowledge`.`id_level_of_knowledge` = `courses`.`level_of_knowledge_id_level_of_knowledge`
        WHERE `active` = 1 AND ";
    if (isset($_POST['Category']) || isset($_POST['Search'])) {
        if (isset($_POST['Category']) && $_POST['Category'] != 0) {
            $query .= "`level_of_knowledge`.`id_level_of_knowledge` = " . $_POST['Category'] . " AND ";
        }
        if (isset($_POST['Search'])) {
            $query .= "`courses`.`name` LIKE '%" . $_POST['Search'] . "%' AND ";
        }
    }
    $query = substr($query, 0, -5);
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false)
        die('Error connecting to database: ' . mysqli_error($connectDatabase));
    $result = sqlConvert($mysql_result);
    if (count($result) != 0) :
    ?>
        <div class="row row-cols-2 row-cols-md-3 g-3">
            <?
            for ($i = 0; $i < count($result); $i++) :
            ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header text-center h-5">
                            <h5><? echo $result[$i]['course_name'] ?></h5>
                        </div>
                        <img src="/img/courses/<? echo $result[$i]['image'] ?>" class="card-img-center h-35" alt="<? echo $result[$i]['image'] ?>">
                        <div class="card-body pb-0">
                            <p class="card-text mb-3" style="height: 170px;"><? echo $result[$i]['description'] ?></p>
                        </div>
                        <div class="card-footer h-30">
                            <p class="mb-3"><b>Категория:</b> <? echo $result[$i]['level_name'] ?></p>
                            <!-- <p class="mb-3"><b>Цена:</b> <? echo number_format($result[$i]['price'], 0, "", " ") ?> руб.</p> -->
                            <a class="btn btn-dark w-100" href="/course.php?id=<? echo $result[$i]['id_course']; ?>">Подробнее</a>
                        </div>
                    </div>
                </div>
            <?
            endfor; ?>
        </div>
    <?
    else :
    ?>
        <div class="mb-5 text-center">
            <p>По вашему запросу ничего не найдено.</p>
        </div>
    <? endif; ?>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>