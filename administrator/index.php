<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <title>Админ Панель</title>
</head>
<body>
    <? if ($_SESSION['admin'] == "successfully"): ?>
    <div class="position-absolute top-50 start-50 translate-middle">
        <ul class="nav nav-pills flex-column p-3">
            <li class="nav-item m-2">
                <a class="btn btn-dark w-100" href="courses/">Курсы</a>
            </li>
            <li class="nav-item m-2">
                <a class="btn btn-dark w-100" href="news.php">Новости</a>
            </li>
            <li class="nav-item m-2">
                <a class="btn btn-dark w-100" href="users.php">Пользователи</a>
            </li>
            <li class="nav-item m-2">
                <a class="btn btn-danger w-100" href="php/auth/logout.php">Выйти</a>
            </li>
        </ul>
    </div>
    <? else: ?>
    <div class="position-absolute top-50 start-50 translate-middle">
        <form method="POST" action="/administrator/php/auth/signin.php" style="margin: auto;">
            <div class="my-3">
                <label for="login" class="form-label mt-5">Логин</label>
                <input type="text" name="login" id="login" placeholder="Логин" class="form-control">
            </div>
            <div class="my-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" name="password" id="password" placeholder="Пароль" class="form-control">
            </div>
            <button type="submit" class="btn btn-dark w-100">Войти</button>
        </form>
    </div>
    <? endif ?>
</body>
</html>