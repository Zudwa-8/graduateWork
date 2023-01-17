<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/cookieStart.php");
$title = "Новости";
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/connectDatabase.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/header.php");
?>
<div class="container">
    <div class="row">
        <div>
            <!--class="col-md-8" -->
            <?php
            $page = (isset($_GET['page'])) ? $_GET['page'] - 1 : 0;
            $query = "SELECT `id_new`, `title`, `text`, `date`, `image` FROM `news`
                    ORDER BY `date` DESC";
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false)
                die('Error connecting to server');

            $month_name = [
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря'
            ];
            $result = sqlConvert($mysql_result);
            for ($i = $page * 10; $i < $page * 10 + 10 && $i < count($result); $i++) :
                if (strlen($result[$i]['text']) > 512) {
                    $result[$i]['text'] = mb_strimwidth($result[$i]['text'], 0, 512, '...</p>');
                }
            ?>
                <article class="mb-5 mt-4">
                    <h2><a class="text-decoration-none" href=<? echo "new.php?id=" . $result[$i]['id_new'] ?>><? echo $result[$i]['title']; ?></a></h2>
                    <hr class="m-0">
                    <p class="text-secondary mt-0"><? echo date("d", strtotime($result[$i]['date'])) . " " . $month_name[date('n', strtotime($result[$i]['date'])) - 1] . " " . date("Y", strtotime($result[$i]['date'])) . ", " . date("h:i", strtotime($result[$i]['date'])) ?></p>

                    <div class="d-flex justify-content-center m-4">
                        <img src="/img/news/<? echo $result[$i]['image'] ?>" alt="<? echo $result[$i]['image'] ?>" style="max-width: 90%; max-height: 250px;">
                    </div>
                    <div class="d-flex justify-content-center m-4">
                        <div><? echo $result[$i]['text']; ?></div>
                    </div>
                    <hr class="mt-4">
                </article>
            <?php
            endfor;
            ?>
            <div class="d-flex justify-content-center mt-4 mb-5">
                <div class="btn-group">
                    <?php
                    $n_pages = ceil(count($result) / 10);

                    $page++;
                    if ($page <= 5) :
                        for ($i = 1; $i < $page; $i++) :
                    ?>
                            <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . $i ?>"><? echo $i ?></a>
                        <?php
                        endfor;
                    else :
                        ?>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=1" ?>">1</a>
                        <button class="btn btn-dark disabled">...</button>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . ($page - 2) ?>"><? echo $page - 2 ?></a>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . ($page - 1) ?>"><? echo $page - 1 ?></a>
                    <?php
                    endif;
                    ?>
                    <button class="btn btn-dark active"><? echo $page ?></button>
                    <?php
                    if ($page + 5 > $n_pages) :
                        for ($i = $page + 1; $i <= $n_pages; $i++) :
                    ?>
                            <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . $i ?>"><? echo $i ?></a>
                        <?php
                        endfor;
                    else :
                        ?>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . ($page + 1) ?>"><? echo $page + 1 ?></a>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . ($page + 2) ?>"><? echo $page + 2 ?></a>
                        <button class="btn btn-dark disabled">...</button>
                        <a class="btn btn-dark" href="<? echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["SCRIPT_NAME"] . "?page=" . $n_pages ?>"><? echo $n_pages ?></a>
                    <?php
                    endif;
                    ?>

                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">Фильтры</div> -->
    </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>