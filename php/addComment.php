<?
    require_once($_SERVER['DOCUMENT_ROOT']."/php/cookieStart.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/php/connectDatabase.php");

    if (isset($_POST['id_post']) && isset($_POST['id_parent']) && isset($_POST['comment_text']) && isset($_POST['pageComment']))
    {
        $id_post = $_POST['id_post'];
        $id_parent = $_POST['id_parent'];
        $comment_text = mysqli_real_escape_string($connectDatabase, $_POST['comment_text']);
        $id_user = $_SESSION['user']['id_user'];
        $pageComment= $_POST['pageComment'];

        if ($id_post == "" || $id_parent == "" || $comment_text == "" || trim(strip_tags($comment_text)) == "") {
            $response['desc'] = 'Not all fields are filled';
            die(json_encode($response));
        }
        $dateNow = date('Y-m-d H:i:s');
        switch ($pageComment) {
            case 'new':
                if ($id_parent == 0) {
                    $query = "INSERT INTO `comments_news` (`text`, `news_id_new`, `users_id_user`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', '$dateNow')";
                }
                else{
                    $query = "INSERT INTO `comments_news` (`text`, `news_id_new`, `users_id_user`, `id_parent`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', $id_parent, '$dateNow')";
                }
                break;
            case 'courses':
                if ($id_parent == 0) {
                    $query = "INSERT INTO `comments_courses` (`text`, `courses_id_course`, `users_id_user`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', '$dateNow')";
                }
                else{
                    $query = "INSERT INTO `comments_courses` (`text`, `courses_id_course`, `users_id_user`, `id_parent`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', $id_parent, '$dateNow')";
                }
                break;
            case 'lesson':
                if ($id_parent == 0) {
                    $query = "INSERT INTO `comments_lessons` (`text`, `lessons_id_lesson`, `users_id_user`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', '$dateNow')";
                }
                else{
                    $query = "INSERT INTO `comments_lessons` (`text`, `lessons_id_lesson`, `users_id_user`, `id_parent`, `date`) 
                    VALUES ('$comment_text', '$id_post', '$id_user', $id_parent, '$dateNow')";
                }
                break;
            default:
                $response['desc'] = 'Failed to get data';
                die(json_encode($response));
                break;
        }
        
        $mysql_result = mysqli_query($connectDatabase, $query);
        
        if ($mysql_result === false){
            $response['desc'] = 'mysql_error';
            $response['desc_mysql_error'] = mysqli_error($connectDatabase);
            die(json_encode($response));
        }
        $response['desc'] = 'Comment added successfully';
        die(json_encode($response));
    }
    else{
        $response['desc'] = 'Failed to get data';
        die(json_encode($response));
    }
?>