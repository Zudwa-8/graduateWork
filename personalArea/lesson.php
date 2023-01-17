<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
if (!$_SESSION['user'])
    header('Location: ../index.php');
else{
    $query = "SELECT `courses`.`id_course`, `courses`.`name` AS `course_name`, `topics`.`name` AS `topic_name`, `lessons`.`name`AS `lesson_name`, `lessons`.`text` AS `lesson_text`, `id_lesson` FROM `purchased_courses`
        INNER JOIN `courses` ON `courses`.`id_course` = `purchased_courses`.`courses_id_course`
        INNER JOIN `topics` ON `topics`.`courses_id_course` = `courses`.`id_course`
        INNER JOIN `lessons` ON `lessons`.`topics_id_topic` = `topics`.`id_topic`
        WHERE `users_id_user` = ".$_SESSION['user']['id_user'].
        " AND `id_topic` = ".$_GET['id'];
    $mysql_result = mysqli_query($connectDatabase, $query);
    if ($mysql_result === false)
        die(mysqli_error($connectDatabase)); 
    $result = sqlConvert($mysql_result);
    if (!isset($result)){
        header('Location: /personalArea/index.php');
    }
}
$id = $result[0]['id_lesson'];
$title = "Мои курсы - ".$result[0]['course_name']." - ".$result[0]['topic_name'];
require_once($_SERVER['DOCUMENT_ROOT']."/php/comment.php");
require_once($_SERVER['DOCUMENT_ROOT']."/header.php");
$current_lesson = (!$_GET['idl'])?0:($_GET['idl'] - 1);
$serverPath = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"];
?>
<div class="container"> 
    <div class="row">
        <div class="col-2">
            <div class="d-flex justify-content-center mt-4">
                <div class="btn-group-vertical w-100">
                    <?
                        //$current_progress;
                        for ($i=0; $i < count($result); $i++) : 
                            $query = "SELECT * FROM `progress_learning`
                            WHERE `users_id_user` = ".$_SESSION['user']['id_user'].
                            " AND `lessons_id_lesson` = ".$result[$i]['id_lesson'];
                            $mysql_result = mysqli_query($connectDatabase, $query);
                            if ($mysql_result === false)
                                die(mysqli_error($connectDatabase)); 
                            $result2 = sqlConvert($mysql_result);
                            if ($result2>0) : 
                    ?>
                    <a class="btn btn-outline-success text-white text-start bg-success" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?id=".$_GET['id']."&idl=".($i+1)?>"><?echo $result[$i]['lesson_name']; ?></a>
                    <? 
                        elseif (!isset($current_progress)) : 
                            $current_progress = false;
                    ?>
                    <a class="btn btn-outline-warning text-start text-dark bg-warning" href="<?echo $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?id=".$_GET['id']."&idl=".($i+1)?>"><?echo $result[$i]['lesson_name']; ?></a>
                    <? else: ?>
                    <a class="btn btn-outline-danger text-start text-white bg-danger"><?echo $result[$i]['lesson_name']; ?></a>
                    <? endif; ?>
                    <? endfor; ?>
                </div>
            </div>
        </div>
        <div class="col-10">
            <h3 class="text-header text-center"><? echo $result[$current_lesson]['lesson_name'] ?></h3>
            <hr class="mt-0">
            <p><? echo $result[$current_lesson]['lesson_text'] ?></p>
            <?
                $query = "SELECT * FROM `questions`
                WHERE `lessons_id_lesson` = ".$result[$current_lesson]['id_lesson'];
                $mysql_result = mysqli_query($connectDatabase, $query);
                if ($mysql_result === false)
                    die(mysqli_error($connectDatabase)); 
                $result3 = sqlConvert($mysql_result);
                if ($result3 != null) :
            ?>
            <div id="test">
                <?
                    for ($i=0; $i < count($result3); $i++) :
                        $query = "SELECT * FROM `answers`
                        WHERE `questions_id_question` = ".$result3[$i]['id_question'];
                        $mysql_result = mysqli_query($connectDatabase, $query);
                        if ($mysql_result === false)
                            die(mysqli_error($connectDatabase)); 
                        $result2 = sqlConvert($mysql_result);
                ?>
                <div id="<?  echo $result3[$i]['id_question']?>" class="mt-3">
                    <h6><? echo $result3[$i]['text'] ?></h6>
                    <?  for ($j=0; $j < count($result2); $j++) : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" onclick="del_msg()" name="<? echo $result3[$i]['id_question']?>_radios" id="<? echo $result3[$i]['id_question']?>_radios_<?  echo $j ?>" value="<?  echo $result2[$j]['id_answer'] ?>">
                        <label class="form-check-label" for="<? echo $result3[$i]['id_question']?>_radios_<?  echo $j ?>"><? echo $result2[$j]['text'] ?></label>
                    </div>
                    <?  endfor; ?>
                </div>
                <?  endfor; ?>
            </div>
            <?  endif; ?>
            <div class="text-end"><a class="btn btn-dark bg-gradient" id="lessonСomplete">Завершить</a></div>
            <script>
            function del_msg(){
            $('#test-msg-alert').remove()
            $("#test").children().each(function( index ) {
                $(this).removeClass('text-danger')
            })
            }
                document.getElementById('lessonСomplete').addEventListener('click', (e) =>{
                    del_msg()
                    $(document.getElementById('lessonСomplete')).addClass('disabled');

                    var data = {};
                
                    data['id'] = <? echo $result[$current_lesson]['id_lesson'] ?>;

                    if ($("#test").length) {
                        var unselected = true
                        var questions = $("#test").children();
                        for (let index = 0; index < questions.length; index++) {
                            var inputCheck=$(questions[index]).find('input:checked')
                            if (inputCheck.length) {
                                data['test[' + $(questions[index]).prop('id') +']'] = inputCheck.prop('value')
                            }else{
                                unselected = false
                            }
                        }
                    }
                    else{
                        unselected = true
                    }
                    console.log('unselected: ' + unselected)
                    if (unselected) {
                        $.ajax({
                        type: 'POST',
                        url: '/php/PersonalArea/lessonСomplete.php',
                        dataType: 'html',
                        data: data,
                        beforeSend: function()
                        {
                            console.log(data)
                        },
                        success: function(response)
                        {
                            var jsonEncode = jQuery.parseJSON(response)
                            if(jsonEncode['desc'] == 'Data is not found'){
                                del_msg()
                                var divAlert = $(`<div class="alert alert-danger mt-3" id="test-msg-alert"><p>Ошибка при отправке запроса!</p></div>`);
                                $("#test").append(divAlert)
                            }
                            if(jsonEncode['desc'] == 'mysql_error'){
                                alert('mysql_error')
                            }
                            if(jsonEncode['desc'] == 'Lesson already passed'){
                                document.location.href = "<? echo $serverPath.(($current_lesson == count($result) - 1)?"/PersonalArea/topic.php?id=".$result[0]['id_course']:"/PersonalArea/lesson.php?id=".$_GET['id']."&idl=".($current_lesson + 2)); ?>";
                            }
                            if(jsonEncode['desc'] == 'Test failed'){
                                del_msg()
                                for (const [key, value] of Object.entries(jsonEncode['mistakes'])) {
                                    var id = '#'+key;
                                    console.log(id)
                                    console.log($("#test").find('#'+key).html())
                                    $("#test").find('#'+key).addClass('text-danger');
                                }
                                var divAlert = $(`<div class="alert alert-danger mt-3" id="test-msg-alert">
                                    <p>Вы ответили не правильно!</p>
                                    <p>Повторите предыдущие уроки и вернитесь к прохождению теста!</p>
                                </div>`);
                                $("#test").append(divAlert)
                            }
                            if(jsonEncode['desc'] == 'Test passed'){
                                del_msg()
                                var divAlert = $(`<div class="alert alert-success mt-3" id="test-msg-alert"><p>Тест успешно пройден! Еще раз нажмите на кнопку завершить, закончить урок.</p></div>`);
                                $("#test").append(divAlert)
                            }
                            if(jsonEncode['desc'] == 'Lesson passed'){
                                document.location.href = "<? echo $serverPath.(($current_lesson == count($result) - 1)?"/PersonalArea/topic.php?id=".$result[0]['id_course']:"/PersonalArea/lesson.php?id=".$_GET['id']."&idl=".($current_lesson + 2)); ?>";
                            }
                        }
                        });  
                    }
                    else{
                        del_msg()
                        var divAlert = $(`<div class="alert alert-danger mt-3" id="test-msg-alert"><p>Ответьте на все вопросы!</p></div>`);
                        $("#test").append(divAlert)
                    }
                    
                    $(document.getElementById('lessonСomplete')).removeClass('disabled');
                    e.preventDefault();
                })
            </script>
        </div>
    </div>
    <div class="my-5">
         <? output_comments('lesson', $id) ?>
    </div>    
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT']."/footer.php"); ?>