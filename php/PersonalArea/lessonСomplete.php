<?
require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");
function key_compare_func($a, $b)
{
    if ($a === $b) {
        return 0;
    }
    return ($a > $b)? 1:-1;
}
if (!$_SESSION['user']) {
    $response['desc'] = 'Data is not found';
    die(json_encode($response));
}
else {
    if (!$_POST['id']) {
        $response['desc'] = 'Data is not found';
        die(json_encode($response));
    }
    else{
        $query = "SELECT * FROM `progress_learning`
                WHERE `users_id_user` = ".$_SESSION['user']['id_user'].
                " AND `lessons_id_lesson` = ".$_POST['id'];
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false){
            $response['desc'] = 'mysql_error';
            $response['desc_mysql_error'] = mysqli_error($connectDatabase);
            die(json_encode($response));
        }
        $result = sqlConvert($mysql_result);
        if (count($result) > 0) {
            $response['desc'] = 'Lesson already passed';
            die(json_encode($response));
        }
        $query = "SELECT * FROM `questions`
            WHERE `lessons_id_lesson` = ".$_POST['id'];
        $mysql_result = mysqli_query($connectDatabase, $query);
        if ($mysql_result === false){
            $response['desc'] = 'mysql_error';
            $response['desc_mysql_error'] = mysqli_error($connectDatabase);
            die(json_encode($response));
        }
        $result = sqlConvert($mysql_result);
        if (count($result) > 0) {
            if (!$_POST['test']){
                $response['desc'] = 'Data is not found';
                die(json_encode($response));
            }
            $query = "SELECT `id_question`, `id_answer` FROM `questions`
                INNER JOIN `answers` ON `answers`.`questions_id_question` = `questions`.`id_question`
                WHERE `lessons_id_lesson` = ".$_POST['id']." AND `is_it_true` = true";
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false){
                $response['desc'] = 'mysql_error';
                $response['desc_mysql_error'] = mysqli_error($connectDatabase);
                die(json_encode($response));
            }
            $result = sqlConvert($mysql_result);

            foreach ($result as $key => $value) {
                $answers[$value['id_question']] = $value['id_answer'];
            }
            $mistakes = array_diff_uassoc($answers, $_POST['test'], "key_compare_func");
            
            if (count($mistakes) >= 1) {
                $response['desc'] = 'Test failed';
                $response['mistakes'] = $mistakes;
                die(json_encode($response));
            }
            else{
                $query = "INSERT INTO `progress_learning` (`users_id_user`, `lessons_id_lesson`)
                VALUES (".$_SESSION['user']['id_user'].", ".$_POST['id'].")";
                $mysql_result = mysqli_query($connectDatabase, $query);
                if ($mysql_result === false){
                    $response['desc'] = 'mysql_error';
                    $response['desc_mysql_error'] = mysqli_error($connectDatabase);
                    die(json_encode($response));
                }
                $response['desc'] = 'Test passed';
                die(json_encode($response));
            }
        }
        else{
            $query = "INSERT INTO `progress_learning` (`users_id_user`, `lessons_id_lesson`)
            VALUES (".$_SESSION['user']['id_user'].", ".$_POST['id'].")";
            $mysql_result = mysqli_query($connectDatabase, $query);
            if ($mysql_result === false){
                $response['desc'] = 'mysql_error';
                $response['desc_mysql_error'] = mysqli_error($connectDatabase);
                die(json_encode($response));
            }
            $response['desc'] = 'Lesson passed';
            die(json_encode($response));
        }
    }
    
}
?>