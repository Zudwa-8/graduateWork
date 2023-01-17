function check(inputName, err) {
    var elem = document.getElementById(inputName)
    if (typeof err !== 'undefined') {
        $(elem).addClass("is-invalid")
        $(elem).next().html(err)
    }
    else {
        $(elem).addClass("is-valid")
    }
}

function deleteField(id, name) {
    if (confirm("Вы уверены, что хотите удалить вопрос: \"" + name + "\"?")) {
        var formData = new FormData();
        formData.append("id_question", id);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/administrator/php/deleteQuestion.php", false);
        xhr.send(formData);
        location.reload();
    }
}
var form = document.getElementById("lesson-form")

$('#submit-add').on('click', function(e) {
    e.preventDefault();
    if (typeof $("#Messeage-alert") !== 'undefined') {
        $("#Messeage-alert").remove();
    }
    $(form).find("div input").removeClass('is-invalid')
    $(form).find("div input").removeClass('is-valid')
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');

    var data = {};

    // формируем массив для отправки на сервер 
    data['validation[name]'] = document.getElementById('name').value;
    data['validation[id_topic]'] = document.getElementById('id_topic').value;
    $("#description").sync();
    data['validation[description]'] = $("#description").htmlcode();
    
    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/addLesson.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (msg) 
        {
            var jsonEncode = jQuery.parseJSON(msg);
            if (jsonEncode.msg == 'Added successfully') {
                alert('Урок успешно добавлен.');
                window.location.href = location.href.substring(0, location.href.indexOf('?')) + "?id_lesson=" + jsonEncode.id;
            }
            else if (jsonEncode.msg == 'Failed to get data') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при отправке данных на сервер.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Error connecting to database') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при подключении к базе данных.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Fill in all the fields') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Заполните все поля.
            </div>
            `);
            }
            btn.removeClass('disabled');
        }
    });
    e.preventDefault();
});

$('#submit-edit').on('click', function(e) {
    e.preventDefault();
    if (typeof $("#Messeage-alert") !== 'undefined') {
        $("#Messeage-alert").remove();
    }
    $(form).find("div input").removeClass('is-invalid')
    $(form).find("div input").removeClass('is-valid')
    var btn = $(form).closest('form').find(':submit');
    btn.closest('form').find(':submit').addClass('disabled');

    var data = {};

    // формируем массив для отправки на сервер 
    data['validation[name]'] = document.getElementById('name').value;
    $("#description").sync();
    data['validation[description]'] = $("#description").htmlcode();
    data['validation[id_lesson]'] = document.getElementById('id_lesson').value;

    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/editLesson.php',
        dataType: 'html',
        data: data,
        // до выполнения запроса
        beforeSend: function () {
        },
        // в случае удачного выполнения добавляем блок с сообщением
        success: function (msg) 
        {
            var jsonEncode = jQuery.parseJSON(msg);
            if (jsonEncode.msg == 'Added successfully') {
                alert('Урок успешно изменен.');
            }
            else if (jsonEncode.msg == 'Failed to get data') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при отправке данных на сервер.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Error connecting to database') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Ошибка при подключении к базе данных.
            </div>
            `);
            }
            else if (jsonEncode.msg == 'Fill in all the fields') {
                btn.before(`
            <div class="alert alert-danger" role="alert" id="Messeage-alert">
                Заполните все поля.
            </div>
            `);
            }
            btn.removeClass('disabled');
        }
    });
    e.preventDefault();
});