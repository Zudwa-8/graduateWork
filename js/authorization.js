$(document).ready(function () {
    $.getScript("/js/signin.js", function () { });
    $("#btn-log").click(function () {
        $("#btn-reg").removeClass('active');
        $("#btn-log").addClass('active');
        $("#auth-body").html(`
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
        `);
        $.getScript("/js/signin.js", function () { });
    });
    $("#btn-reg").click(function () {
        $("#btn-log").removeClass('active');
        $("#btn-reg").addClass('active');
        $("#auth-body").html(`
                <form class="needs-validation" id="register-form">
                    <div class="mb-3">
                    <label for="register-inputUsername" class="form-label">Имя пользователя</label>    
                    <input type="text" class="form-control" id="register-inputUsername" maxlength="25" placeholder="Имя пользователя">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                    <label for="register-inputPassword" class="form-label">Пароль</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="register-inputPassword" placeholder="Пароль">
                            <button type="button" class="input-group-text" onclick="register_show_password(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                    <label for="register-inputPasswordСonfirm" class="form-label">Повторите пароль</label>
                        <input type="password" class="form-control" id="register-inputPasswordСonfirm" placeholder="Повторите пароль">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                    <label for="register-inputFullname" class="form-label">ФИО</label>
                        <input type="text" class="form-control" id="register-inputFullname" placeholder="ФИО">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                    <label for="register-inputEmail" class="form-label">Электронная почта</label>
                        <input type="email" class="form-control" id="register-inputEmail" placeholder="Электронная почта">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                    <label for="register-inputTelephone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" id="register-inputTelephone" placeholder="Телефон">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                        <label class="form-check-label" for="invalidCheck">
                            Согласие на обработку персональных данных
                        </label>
                        <div class="invalid-feedback">
                            Вы должны принять перед отправкой.
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-dark">Зарегистрироваться</button>
                    </div>
                </form>
        `);
        $.getScript("/js/signup.js", function () { });
        $("[type = tel]").mask("+7 (999) 999-99-99");
    });


});
function register_show_password(obj) {
    if ($('#register-inputPassword').attr('type') == 'password') {
        $(obj).html(`
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
        </svg>
        `);
        $('#register-inputPassword').attr('type', 'text');
    } else {
        $(obj).html(`
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
        </svg>
        `);
        $('#register-inputPassword').attr('type', 'password');
    }
}
function login_show_password(obj) {
    if ($('#login-inputPassword').attr('type') == 'password') {
        $(obj).html(`
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
        </svg>
        `);
        $('#login-inputPassword').attr('type', 'text');
    } else {
        $(obj).html(`
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
        </svg>
        `);
        $('#login-inputPassword').attr('type', 'password');
    }
}