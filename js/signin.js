var form = document.getElementById("login-form")

form.addEventListener('submit', (e) => {
    console.log('запуск signin')
    $('#login-msg-alert').removeAttr('hidden');
    $(form).closest('form').find(':submit').addClass('disabled');
    // elements содержит количество элементов для валидации
    var elements = form.querySelectorAll('div>input');

    // has содержит количество элементов успешно прощедших валидацию
    var data = {};

    // формируем массив для отправки на сервер
    //на сервере массив будет доступен в виде $_POST['validation']['name']['value'] и т.п. 
    elements.forEach((element) => {
        $('#login-msg-alert').html('');
        var name = $(element).attr('id');
        data['validation[' + name + '][value]'] = $(element).val();
        data['validation[' + name + '][name]'] = name;
        switch (name) {
            case 'login-inputUsername':
                data['validation[' + name + '][username-signin]'] = true;
                break;
            case 'login-inputPassword':
                data['validation[' + name + '][password-signin]'] = true;
                break;
            default:
                break;
        }
    });

    $.ajax({
        type: 'POST',
        url: 'php/authorization/signin.php',
        dataType: 'html',
        data: data,
        beforeSend: function () {
        },
        success: function (msg) {
            var jsonEncode = jQuery.parseJSON(msg);
            $(form).closest('form').find(':submit').removeClass('disabled');
            if (jsonEncode) {
                $('#login-msg-alert').remove()
                location.reload();
            } else {
                $('#login-msg-alert').html(`
        Не удаётся войти.<br>
        Пожалуйста, проверьте правильность написания логина и пароля.
        `)
                $('#login-msg-alert').removeAttr('hidden')
            }
        }
    });
    e.preventDefault();
});

function onchange_login_input() {
    $('#login-msg-alert').attr('hidden');
    $('#login-msg-alert').html('');
}