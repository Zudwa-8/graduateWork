<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
if (!$_SESSION['user']){
    header('Location: ../index.php');
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
    $id_user = $_SESSION['user']['id_user'];
    $query = "SELECT `roles`.`role_name` FROM users
    INNER JOIN `roles` ON `roles`.`id_role` = `users`.`role_id_role`
    WHERE `id_user` = $id_user";
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false){
        echo json_encode('Error connecting to database');
        die();
    }
    $result = sqlConvert($mysql_result);                 
    if ($result[0]['role_name'] != "Администратор") {
        header('Location: ../personalArea/');
    }
}
$title = "Личный кабинет";
$headers .= '<script defer src="/js/personalArea/news.js"></script>';

// // Подключение TinyMCE
// $headers .= '<script src="https://cdn.tiny.cloud/1/hycluf58dk96twbl16c0ig9c214jlpw5lk4opbkbigtfhx8h/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>';
// // Подключение TinyMCE

// Подключение wysibb
$headers .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';
$headers .= '<script src="/js/jquery.wysibb.min.js"></script>';
$headers .= '<script src="/js/lang/ru.js"></script>';
$headers .= '<link rel="stylesheet" href="/css/wbbtheme.css"/>';
// Подключение wysibb
require_once($_SERVER['DOCUMENT_ROOT']."/header.php"); 
?>
<div class="container">
  <div class="row">
    <div class="col-md-2">
      <?php 
        $liActive = "news";
        require_once($_SERVER['DOCUMENT_ROOT']."/personalArea/menu.php"); 
      ?>
    </div>

    <div class="col-md-10">
        <div class="mb-5">
            <h3>Добавить новость</h3>   
            <hr>
            <form class="needs-validation" id="AddNew-form" novalidate>
                <div class="mb-3">
                    <label for="AddNew-inputTitle" class="form-label">Заголовок</label>
                    <input type="text" class="form-control" id="AddNew-inputTitle" maxlength="256" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="AddNew-inputText" class="form-label">Текст</label>
                    <!-- // Подключение wysibb -->
                    <script>
                        $(document).ready(function() {
                        var wbbOpt = {
                            lang: "ru",
                            imgupload:			false,
                            img_uploadurl:		"/iupload.php",
                            img_maxwidth:		<?echo 400?>
                        }
                        $("#AddNew-inputText").wysibb(wbbOpt);
                        });
                    </script>
                    <!-- // Подключение wysibb -->
                    <!-- // Подключение TinyMCE -->
                    <!-- <script>
                    tinymce.init({
                        selector: '#AddNew-inputText',
                        toolbar: 'image',
                        plugins: 'image imagetools',
                        a11y_advanced_options: true
                    });
                    </script> -->
                    <!-- // Подключение TinyMCE -->
                    <textarea class="form-control" id="AddNew-inputText" rows="10" maxlength="65000"></textarea>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Добавить новость</button>
                </div>
            </form>
        </div>
        <div class="mb-5">
            <h3>Все новости</h3>   
            <hr>
            <table class="table">
                <thead class="table-white">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Автор</th>
                    <th scope="col">Изменить</th>
                </tr>
                </thead>
                <tbody>
                    <?
                        $query = "SELECT `title`, `text`, `date`, `users`.`username` FROM news
                        INNER JOIN `users` ON `users`.`id_user` = `news`.`users_id_user`";
                        $mysql_result = mysqli_query($connectDatabase, $query);
                        if ($mysql_result === false){
                            echo json_encode('Error connecting to database');
                            die();
                        }
                        $result = sqlConvert($mysql_result);
                        for ($i=0; $i < count($result); $i++) :
                    ?>
                    <tr>
                        <th scope="row"><? echo $i+1; ?></th>
                        <td><? echo $result[$i]['title']; ?></td>
                        <td><? echo $result[$i]['text']; ?></td>
                        <td><? echo $result[$i]['date']; ?></td>
                        <td><? echo $result[$i]['username']; ?></td>
                        <td><button class="btn btn-dark" onclick="alert('<? echo $result[$i]['username']; ?>')">Изменить</button></td>
                    </tr>
                    <? endfor; ?>
                </tbody>
            </table>
        </div>
        
    </div>
  </div>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>