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


function deleteAnswer(i) {
    if ($('#anwers').children().length > 2){
        $('#answerDiv' + i).remove();
    }
}

var i = 2;
    $('#add_answer').on('click', function(e) {
        i++;
        $('#anwers').append(`<div class="input-group my-3" id="answerDiv`+i+`">
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="radio" id="answerRadio`+i+`" name="answersRadio"  value="`+i+`">
        </div>
        <input type="text" class="form-control" id="answerText`+i+`">
        <button class="btn btn-danger" type="button" onclick=deleteAnswer(`+i+`)>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
            </svg>
        </button>
    </div>
    `);
    });


var form = document.getElementById("question-form")

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
    var j = 0;
    // формируем массив для отправки на сервер 
    data['validation[id_lesson]'] = document.getElementById('id_lesson').value;
    data['validation[text]'] = document.getElementById('text').value;
    $('#anwers').children().each(function() {
        data['validation[answers][' + j + '][text]'] = $(this).children('input[type="text"]').val();
        data['validation[answers][' + j + '][value]'] = $(this).find('input[type="radio"]').val();
        j++;
    });
    data['validation[radio]'] = $('input[name="answersRadio"]:checked').val();

    // делаем ajax-запрос методом POST на текущий адрес, в ответ ждем данные HTML
    $.ajax({
        type: 'POST',
        url: '/administrator/php/addQuestion.php',
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
                alert('Вопрос успешно добавлен.');
                window.location.href = "http://cppk/administrator/courses/editLesson.php?id_lesson=" + jsonEncode.id;
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