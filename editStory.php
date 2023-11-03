<!--Page Shown when a User wants to edit or delete their story-->
<?php
    //starts the session
    session_start();
    //connects to the database
    require 'connection.php';

    //if the user isn't logged in and somehow is able to navigate to this page(should never loggically occur) they are redirected to the main page
    if (!array_key_exists("user_id",$_SESSION)) {
        header('Location: main.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    
    //if the submit button for editting a Story post is pressed, we update the database with the newly submitted values
    if (isset($_POST['editSubmit'])) {
        //grabs the $_POST variables submitted via the update Story Form
        $story_id = $_POST['story_id'];
        $title = $_POST['title'];
        $body = $_POST['body'];
        $link = $_POST['link'];

        //Make an update query but check for forgery before
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }        
        $stmt = $mysqli->prepare("UPDATE  story SET title=?, body=?, link=?, createdby_id=? WHERE story_id=?");  //sssi
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    


        $stmt->bind_param('sssii', $title, $body, $link, $user_id, $story_id);

        $stmt->execute();

        $stmt->close();

        //Redirect user to editStory.html
        header('Location: editStory.html');
        exit();

    }

    //if the submit button for deleting a Story post is pressed, we update the database with the newly submitted values
    if (isset($_POST['deleteSubmit'])) {
        //grabs the $_POST variables submitted via the delete Story Form
        $story_id = $_POST['story_id'];
    
        //Make a delte query but check for forgery before
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        $stmt = $mysqli->prepare("DELETE FROM story WHERE story_id=?");  
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->bind_param('i', $story_id);

        $stmt->execute();

        $stmt->close();

        //Redirect to deleteStory.html
        header('Location: deleteStory.html');
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
        <a class= "child" href="updateComment.php">Edit/Delete Your Comments</a>
        <a class= "child" href="addPost.php">Add Post</a>
        <a class= "child" href="updateUser.php">Edit Password</a>
        <a class= "child" href="logout.php">Logout</a>
    </div>
    <h1 class="updatePosts">Edit or Delete Your Posts!</h1>
<?php
    //Selects all of the stories created by the logged in user
    $stmt = $mysqli->prepare("select * from story where createdby_id = ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
    //There are 2 different PHP forms created for each Story grabbed from the database: one for updatting/editing the post and one for deleting the post
    //We concatanate the forms to the variable $output and then echo it out at the bottom of the page
    while($stmt->fetch()){
        $output .= '
                    <form action="editStory.php" class="editStory" method="POST">
                        <input type="hidden" name="story_id" value="'.$story_id.'">
                        <p>Title:</p>
                        <input name="title" type="text" value="'.htmlentities($title).'" required />
                            <br /><br />
                        <p>Body:</p>
                        <textarea name="body" required >'.htmlentities($body).'</textarea>
                            <br /><br />
                        <p>Link:</p>
                        <input name="link" type="text" value="'.htmlentities($link).'" required />
                            <br /><br />
                        <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
                        <input type="submit" name="editSubmit" value="Edit Post" />
                    </form>

                    <form action="editStory.php" class="deleteStory" method="POST">
                        <input type="hidden" name="story_id" value="'.$story_id.'">
                        <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
                        <input type="submit" name="deleteSubmit" value="Delete Story" />
                    </form>


                ';
    }
    $stmt->close();
    echo $output;
?>
</body>
</html>


