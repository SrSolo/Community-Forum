<!--This page is associated with profile viewing: displays all of the story posts and comments associated with a users account-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>WashU News Website</title>
</head>
<body>
    <?php
        //Starts the session
        session_start();
        if (array_key_exists("user_id", $_SESSION)) {
            //user is logged in
            echo '
                    <div class="navBar">
                        <a class ="child" href="main.php">All Posts</a>
                        <a class ="child" href="editStory.php">Edit/Delete Posts</a>
                        <a class= "child" href="updateComment">Edit/Delete Your Comments</a>
                        <a class= "child" href="addPost.php">Add Post</a>
                        <a class= "child" href="updateUser.php">Edit Password</a>
                        <a class= "child" href="logout.php">Logout</a>
                    </div>
                ';
        } else {
            //user is not logged in
            echo '
                <div class="navBar">
                    <a class="child" href="main.php">All Posts</a>
                    <a class ="child" href="login.php">Login</a>
                    <a class= "child" href="register.php">Create a New Account</a>
                </div>
                ';
        }
            require 'test.php';  
            //grabs the username from the url parameter
            $username = $_GET['user_id'];
            //The next three functions are located in test.php!
            $requested_id = getIdFromName($username); //gets user's id from based on their username. 
            $userPosts = getDBSpecific($requested_id); //gets all of the user's stories posted based on their user_id from the database, getDBSpecific() method is located in test.php
            $userComments = getCommentSpecific($requested_id); //gets user's comments based on their user_id  from the database, getCommentSpecific() method is located in test.php
            echo '<h1>'.htmlentities($username).' Posted Stories and Comments:</h1>';
            echo ($userPosts); //echos out the specific user's stories 
            echo ($userComments); //echos out the specific user's comments 
    ?>
<footer>Made by WashU Students, For WashU Students</footer>
</body>
</html>
