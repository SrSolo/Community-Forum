<!--This page is associated with Admin functionality (deleting users), the admin has a user_id=1 -->
<?php
  require 'connection.php';
  session_start();
  if ($_SESSION['user_id'] > '1') {
    //if the user isn't the admin, they should get immediately redirected to the main page. (only the admin has a user_id=1)
    header('Location: main.php');
    exit();
  }
  //Checks to see if the delete button has been pressed
  if (isset($_POST['submit'])) {
    $usernameToDelete = $_POST['username'];
    //token check
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Request forgery detected");
    }
    require 'test.php';
    //get the id for story and comment deletion
    $user_idToDelete = getIdFromName($usernameToDelete);
    // delete user from DB. Because of foreign key constraints we go comment -> story -> user
     
    //delete the users comments
    delUserComment($user_idToDelete);
    
    //delete the user's stories
    delUserPost($user_idToDelete);
    
    //delete the user
    delUser($usernameToDelete);
    //note success
    echo "<script>
         alert('User Deleted! Redirecting you back to Management Page');
         window.location.href='userManagement.php';
           </script>";   
    // Redirect back to management page after deletion
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Story</title>
</head>
  <body>
    <?php
        //Header
            echo '
                    <div class="navBar">
                      <a class="child" href="main.php">All Posts</a>
                      <a class= "child" href="addPost.php">Add Post</a>
                      <a class= "child" href="logout.php">Logout</a>
                    </div>
                 ';

        require 'test.php';  
        $users = getUsers(); //gets all of the users from the DB (getUsers() function is located in test.php)
        echo '<h1> All Users: Beware! Deleting a user deletes the account and all existing posts and comments associated with the account.</h1>';
        echo ($users); //echos out the users
   ?>
  </body>
</html>


