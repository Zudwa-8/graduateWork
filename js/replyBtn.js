function openForm(btn, id){
    closeForm()
    var form = `<div id="dynamic_comment-form" class="mt-2 mb-4">
                    <div class="mb-3">
                        <label for="dynamic_comment-inputText" class="form-label"><h5 class="mb-0">Оставить комментарий</h5></label>
                        <textarea class="form-control" id="dynamic_comment-inputText" rows="2" maxlength="65000"></textarea>
                        <div class="invalid-feedback" id="dynamic_comment-form-msg-alert"></div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-outline" onclick="closeForm()">Закрыть</button>
                        <button type="submit" class="btn btn-dark" onclick="addComment(this,`+id+`)">Оставить комментарий</button>
                    </div> 
                </div>`;
    $(btn).parent().after(form);
    var wbbOpt = {
        lang:   "ru",
        imgupload:  false,
        buttons:    "bold,italic,underline"
    }
    $("#dynamic_comment-inputText").wysibb(wbbOpt);   
}

function closeForm(){
    $('#dynamic_comment-form').remove()
}

function addComment(btn, id = 0){
    $(btn).addClass('disabled')
    var data = {};
    data['id_parent'] = id
    data['id_post'] = getUrlParameter('id')
    
    if (id == 0) {
        var inputId  = "#comment-inputText";
        
    } else {
        var inputId  = "#dynamic_comment-inputText";
    }
    $("#comment-inputText").sync();
    data['comment_text'] = $(inputId).htmlcode();
    if (window.location.pathname == '/new.php') {
        data['pageComment'] = 'new';
    } 
    else if (window.location.pathname == '/course.php') {
        data['pageComment'] = 'courses';
    } 
    else if (window.location.pathname == '/PersonalArea/lesson.php') {
        data['pageComment'] = 'lesson';
    }
    $.ajax({
        type: 'POST',
        url: '/php/addComment.php', 
        dataType: 'html',
        data: data,
        beforeSend: function()
        {
            console.log('beforeSend')
        },
        success: function(response)
        {
            var jsonEncode = jQuery.parseJSON(response)
            console.log(jsonEncode)
            if(jsonEncode['desc'] == 'Not all fields are filled'){
                var divAlert = $(`<div class="alert alert-danger mt-3" id="test-msg-alert"><p>Заполните все необходимые поля.</p></div>`);
                if (inputId  == "#comment-inputText") {
                    $('#comment-form').append(divAlert)
                }
                if (inputId  == "#dynamic_comment-inputText") {
                    $('#dynamic_comment-form').append(divAlert)
                }
            }
            if(jsonEncode['desc'] == 'Failed to get data'){
                var divAlert = $(`<div class="alert alert-danger mt-3" id="test-msg-alert"><p>Ошибка при отправке запроса!</p></div>`);
                if (inputId  == "#comment-inputText") {
                    $('#comment-form').append(divAlert)
                }
                if (inputId  == "#dynamic_comment-inputText") {
                    $('#dynamic_comment-form').append(divAlert)
                }
            }

            if(jsonEncode['desc'] == 'Comment added successfully'){
                location.reload();
            }
        }
    });
    $(btn).removeClass('disabled')
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};