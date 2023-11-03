<!--This is the page displayed when a registered user wants to edit or delete their comments--> 
<?php
    //Starst the session
    session_start();
    //connects to the database
    require 'connection.php';
    require 'test.php';
    
    //if the user isn't logged in and somehow is able to navigate to this page(should never loggically occur) they are redirected to the main page
    if (!array_key_exists("user_id",$_SESSION)) {
        header('Location: main.php');
        exit();
    }
    $user_id = $_SESSION['user_id'];
    
    //if the submit button associated with editting comments is pressed, we update the database with the newly submitted values
    if (isset($_POST['editSubmit'])) {
        //grabs the $_POST variables submitted via the update Comment Form
        $comment_id = $_POST['comment_id'];
        $text = $_POST['text'];      
        //Make an update query checking for tokens first
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }        
        $stmt = $mysqli->prepare("UPDATE comment set `text`=? WHERE comment_id=?");  //sssi
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->bind_param('si', $text, $comment_id);

        $stmt->execute();

        $stmt->close();

        //Redirects to the editComment.html page
        header('Location: editComment.html');
        exit();

    }

    //if the submit button associated with deleting comments is pressed, we can delete the comment from the database
    if (isset($_POST['deleteSubmit'])) {
        //grabs the $_POST variables submitted via the delete Story Form
        $comment_id = $_POST['comment_id'];
        
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }        
        //Make a delte query
        $stmt = $mysqli->prepare("DELETE FROM comment WHERE comment_id=?");  
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->bind_param('i', $comment_id);

        $stmt->execute();

        $stmt->close();

        //Redirect to deleteComment.html
        header('Location: deleteComment.html');
        exit();

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Story News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navBar">
        <a class="child" href="main.php">All Posts</a>
        <a class= "child" href="editStory.php">Edit/Delete Your Story Posts</a>
        <a class= "child" href="addPost.php">Add Post</a>
        <a class= "child" href="updateUser.php">Edit Password</a>
        <a class= "child" href="logout.php">Logout</a>
    </div>
    <h1 class="updateComments">Edit or Delete Your Comments!</h1>
    <?php
        //When a user wants to edit or delete a comment they view the story information (title, body, link) associated with the story they commented on as well as the comment the posted (that is why the query joins the story and comment tables!)
        $stmt = $mysqli->prepare("select * from comment JOIN story ON (comment.comment_story_id=story_id) where comment_user_id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($comment_id, $text, $comment_user_id, $comment_story_id, $story_id, $title, $body, $link, $createdby_id);
        while($stmt->fetch()){
            //Concatenates the edit and delete form to the $output variable and then echos it out
            $output .= '
                        <p>You Commented on the Story Titled: '.$title.'</p>
                        <p>With a body of: '.$body.' </p>
                        <p>And a link of: '.$link.'</p>
                        <p>Edit Your Comment Here:</p>
                        <form action="updateComment.php" class="updateComment" method="POST">
                            <input type="hidden" name="comment_id" value="'.$comment_id.'">
                            <input type="text" name="text" value="'.htmlentities($text).'" required />
                                <br /><br />
                        <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
                            <input type="submit" name="editSubmit" value="Submit Edit" />
                        </form>

                        <form action="updateComment.php" class="deleteComment" method="POST">
                            <input type="hidden" name="comment_id" value="'.$comment_id.'">
                            <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
                            <input type="submit" name="deleteSubmit" value="Delete Comment" />
                        </form>


                    ';
        }
        $stmt->close();
        echo $output;
    ?>
</body>
</html>



