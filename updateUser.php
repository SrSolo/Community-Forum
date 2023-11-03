<!--This is the initial page that a user sees when wanting to edit their password-->
<!--On this page a user inputs their CURRENT username and password and if it is correct their are redirected to udpateDetails.php where they can input a new password-->
<?php
    //starts a session
    session_start();
    //if the user isn't logged in redirect them to the main.php page
    if (!array_key_exists("user_id", $_SESSION)) {
        header('Location: http:main.php');
        exit;
    }
    //Checks to see if the submit button has been pressed before checking to see if the inputted username and password is correct
    if (isset($_POST['submit'])) {
        //Conects to the database
        require 'connection.php';
        // Use a prepared statement
        //No token check because this is NOT udpating any database contents, simply verifying if the current username and password inputted is correct
        $stmt = $mysqli->prepare("SELECT COUNT(*), user_id, hashed_password FROM user WHERE username=?");
        // Bind the parameter
        $user = $_POST['username'];
        $stmt->bind_param('s', $user);
        $stmt->execute();
        // Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        $stmt->fetch();
        $pwd_guess = $_POST['password'];
        if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
            // Username and password is correct so redirect(+alert) user to updateDetails.php to edit their passwowrd
            //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
            echo "<script>
                    alert('Profile Details Correct! You will now be redirected to update your username and password!');
                    window.location.href='updateDetails.php';
                    </script>";
        } else{
            // Username or password incorrect so redirec(+alert) back to the same page to try again
            //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
            echo "<script>
            alert('Incorrect username and/or password! Please try again!');
            window.location.href='updateUser.php';
            </script>";    
            }
        }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update User</title>
</head>
<body>
    <div class="navBar">
        <a class="child" href="main.php">All Posts</a>
        <a class ="child" href="editStory.php">Edit/Delete Your Posts</a>
        <a class= "child" href="updateComment">Edit/Delete Your Comments</a>
        <a class= "child" href="addPost.php">Add Post</a>
        <a class= "child" href="logout.php">Logout</a>
    </div>

    <h1 class="updateUserHead">Welcome to the Edit Password Page!</h1>
    <h2 class="updateUserHead">Please Enter Your Username and Password.</h2>
    <h2 class="updateUserHead">If both are correct, you will have the opportunity to update these values!</h2>
    <div class="updateUserForm">
        <!--Form which takes in the current user's username and password and checks to see if the inputted values are correct before allowing a user to edit passwords-->
        <form class="formUpdate" action="updateUser.php" method="POST">
        <input name="username" type="text" placeholder="Username" required />
        <br /><br />
        <input name="password" type="password" placeholder="Password" required />
        <br /><br />
        <input type="submit" name="submit" value="Submit" />
        </form>
    </div>
</body>
</html>