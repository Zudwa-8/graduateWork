<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="private">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/jquery-3.6.0.js"></script>
    <?php if (!$_SESSION['user']) : ?>
        <script src="/js/jquery.maskedinput.js"></script>
        <script defer src="/js/authorization.js"></script>
    <?php endif; ?>
    <title><? echo $title ?></title>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <? echo $headers ?>
</head>

<body style="min-height: 100vh; display: flex; flex-direction: column; background-color: #edeef0;">
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark fixed-top" aria-label="Sixth navbar example">
        <div class="container">
            <a class="navbar-brand" href="/">ЦППК</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample06" aria-controls="navbarsExample06" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample06">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/courses.php">Курсы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/news.php">Новости</a>
                    </li>
                </ul>
                <div>
                    <?php if ($_SESSION['user']) : ?>
                        <a class="btn btn-outline-light me-2 " id="btn-personalArea" href="/personalArea/"><?echo $_SESSION['user']['username']?></a>
                        <button class="btn btn-warning bg-gradient" id="btn-exit" onClick="document.location='/php/authorization/logout.php'">Выход</button>
                        <!-- <script>
                            $(document).ready(function(){
                                
                                $('#btn-exit').click(function(){
                                    var url = "/php/authorization/logout.php";
                                    $(location).attr('href', url);
                                });
                            });
                        </script> -->
                    <?php else : ?>
                        <!-- <button type="button" class="btn btn-outline-light me-2  disabled">Личный кабинет</button> -->
                        <button type="button" class="btn btn-warning bg-gradient" data-bs-toggle="modal" data-bs-target="#staticmodalRegisterLogin">Вход</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div style="height: 56px;"></div>
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5" aria-label="Ninth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">ЦППК</a>
            <div class="dropdown navbar-toggler">
                <button class="navbar-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="collapse" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton2">
                    <li><a class="dropdown-item active" href="#">Действие</a></li>
                    <li><a class="dropdown-item" href="#">Другое действие</a></li>
                    <li><a class="dropdown-item" href="#">Что-то еще здесь</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Отделенная ссылка</a></li>
                </ul>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                </ul>
                
            </div>
        </div>
    </nav> -->
    <?php if (!$_SESSION['user']) : ?>
        <div class="modal fade" id="staticmodalRegisterLogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticmodalRegisterLoginLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center;" id="staticmodalRegisterLoginLabel">Авторизация</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-4 border-bottom border-4 border-primary">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 active" id="btn-log">Вход</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0" id="btn-reg">Регистрация</button>
                            </li>
                        </ul>
                        <div id="auth-body">
                            <form class="needs-validation" id="login-form">
                                <div class="mb-3">
                                    <label for="login-inputUsername" class="form-label">Имя пользователя</label>
                                    <input type="text" class="form-control" id="login-inputUsername" placeholder="Имя пользователя" onchange="onchange_login_input()">
                                </div>
                                <div class="mb-3">
                                    <label for="login-inputPassword" class="form-label">Пароль</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="login-inputPassword" placeholder="Пароль" onchange="onchange_login_input()">
                                        <button type="button" class="input-group-text" onclick="login_show_password(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="alert alert-danger" role="alert" id="login-msg-alert" hidden>
                                    Не удаётся войти.<br>
                                    Пожалуйста, проверьте правильность написания логина и пароля.
                                </div>
                                <div class="d-grid mt-5">
                                    <button type="submit" class="btn btn-dark">Войти</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="container bg-white">