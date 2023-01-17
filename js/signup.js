function check(inputName, err) {
    var elem = document.getElementById(inputName)
    if (typeof err !== 'undefined') {
        if (inputName == 'register-inputPassword'){
            $(elem).parent().next().html(err);
            $(elem).removeClass("is-valid");
            $(elem).addClass("is-invalid");
            $(elem).parent().removeClass("is-valid");
            $(elem).parent().addClass("is-invalid");
        }
        else{
            $(elem).next().html(err);
            $(elem).removeClass("is-valid");
            $(elem).addClass("is-invalid");
        }
    }
    else {
        if (inputName == 'register-inputPassword'){
            $(elem).removeClass("is-invalid");
            $(elem).addClass("is-valid");
            $(elem).parent().removeClass("is-invalid");
            $(elem).parent().addClass("is-valid");
        }
        else{
            $(elem).removeClass("is-invalid");
            $(elem).addClass("is-valid");
        }
    }
}
function del(inputName) {
    var elem = document.getElementById(inputName);
    if (inputName == 'register-inputPassword'){
        $(elem).removeClass("is-invalid");
        $(elem).removeClass("is-valid");
        $(elem).parent().removeClass("is-invalid");
        $(elem).parent().removeClass("is-valid");
    }
    else{
        $(elem).removeClass("is-invalid");
        $(elem).removeClass("is-valid");
    }
}

var form = document.getElementById("register-form");

form.addEventListener('submit', (e) => {
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');
    var data = {};

    del('register-inputUsername');
    del('register-inputPassword');
    del('register-inputPasswordСonfirm');
    del('register-inputFullname');
    del('register-inputEmail');
    del('register-inputTelephone');
    // формируем массив для отправки на сервер
    data['validation[register-inputUsername]'] = document.getElementById('register-inputUsername').value;
    data['validation[register-inputPassword]'] = document.getElementById('register-inputPassword').value;
    data['validation[register-inputPasswordСonfirm]'] = document.getElementById('register-inputPasswordСonfirm').value;
    data['validation[register-inputFullname]'] = document.getElementById('register-inputFullname').value;
    data['validation[register-inputEmail]'] = document.getElementById('register-inputEmail').value;
    data['validation[register-inputTelephone]'] = document.getElementById('register-inputTelephone').value;

    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: 'php/authorization/signup.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
            if (typeof $('#ChangePassword-alert') !== 'undefined') {
                $('#ChangePassword-alert').remove();
            }
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (errors) {
            var jsonEncode = jQuery.parseJSON(errors);
            if (jsonEncode == 'Registration completed successfully') {
                console.log('Registration completed successfully')
                check('register-inputUsername', jsonEncode['register-inputUsername'])
                check('register-inputPassword', jsonEncode['register-inputPassword'])
                check('register-inputPasswordСonfirm', jsonEncode['register-inputPasswordСonfirm'])
                check('register-inputFullname', jsonEncode['register-inputFullname'])
                check('register-inputEmail', jsonEncode['register-inputEmail'])
                check('register-inputTelephone', jsonEncode['register-inputTelephone'])
                $(btn).before(`
          <div class="alert alert-success" role="alert" id="ChangePassword-alert">
              Регистрация прошла успешно.
          </div>
          `);
            }
            else if (jsonEncode == 'Failed to get data') {
                console.log('Failed to get data')
                btn.before(`
          <div class="alert alert-danger" role="alert" id="ChangePassword-alert">
              Ошибка при отправке данных на сервер.
          </div>
          `);
            }
            else if (jsonEncode == 'Error connecting to database') {
                console.log('Error connecting to database')
                btn.before(`
          <div class="alert alert-danger" role="alert" id="ChangePassword-alert">
              Ошибка при подключении к базе данных.
          </div>
          `);
            }
            else {
                console.log(jsonEncode)
                check('register-inputUsername', jsonEncode['register-inputUsername'])
                check('register-inputPassword', jsonEncode['register-inputPassword'])
                check('register-inputPasswordСonfirm', jsonEncode['register-inputPasswordСonfirm'])
                check('register-inputFullname', jsonEncode['register-inputFullname'])
                check('register-inputEmail', jsonEncode['register-inputEmail'])
                check('register-inputTelephone', jsonEncode['register-inputTelephone'])
            }
        }
    });
    btn.removeClass('disabled');
    e.preventDefault();
});