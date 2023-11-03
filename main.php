<!--This is the main page of the website that displays all of the Stories -->
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
    //Starts the Session
    session_start();
    //Checks to see if a user is logged in
    if (array_key_exists("user_id", $_SESSION)) {
        // If the user_id =1, this means that this is the Admin user in our DB, so we display to them the navbar which includes User Management
        if($_SESSION['user_id'] == '1'){
            echo '
            <div class="navBar">
                <a class= "child" href="userManagement.php">Manage Users</a>
                <a class= "child" href="addPost.php">Add Post</a>
                <a class= "child" href="updateUser.php">Edit Password</a>
                <a class= "child" href="logout.php">Logout</a>
            </div>
         ';
        }
        // Case that the user is logged in but their user_id != 1, thus they are not the admin user, so display the typical user navbar
        else{
            echo '
            <div class="navBar">
                <a class ="child" href="editStory.php">Edit/Delete Your Posts</a>
                <a class= "child" href="updateComment">Edit/Delete Your Comments</a>
                <a class= "child" href="addPost.php">Add Post</a>
                <a class= "child" href="updateUser.php">Edit Password</a>
                <a class= "child" href="logout.php">Logout</a>
            </div>
         ';
        }
    } else {
        //Case that the user is not logged in at all so we display the "guest" nav bar 
        echo '
            <div class="navBar">
                <a class="child" href="main.php">All Posts</a>
                <a class ="child" href="login.php">Login</a>
                <a class= "child" href="register.php">Create a New Account</a>
            </div>
             ';
    }
          require 'test.php'; 
          echo '<h1>All Stories Posted (click on a story to view it!):</h1>';
          $allPosts = getDB(); //gets all the stories posted from the database, getDB() method is located in test.php which Selects all of the stories in the database
          echo ($allPosts); //echos out all of the stories posted
    ?>

<footer>Made by WashU Students, For WashU Students</footer>
</body>
</html>
