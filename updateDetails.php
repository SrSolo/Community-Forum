<!--This page allows a user to udpate their password details-->
<?php
    //Starts session
    session_start();
    //if the user isn't logged in redirect them to the main.php page
    if (!array_key_exists("user_id", $_SESSION)) {
        header('Location: http:main.php');
        exit;
    }
    //Checks to see if the form has been submitted
    if (isset($_POST['submitNewDetails'])) {
        require 'connection.php';
        //grabs the $_POST variables submitted via the update Username,Password Form
        $user_id = $_SESSION['user_id'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }    
        // Make an update which replaces the old hashed password with the new hashed password
        $stmt = $mysqli->prepare("UPDATE user SET hashed_password=? WHERE user_id=?");  //sssi
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('si',$hashed_password,$user_id);

        $stmt->execute();

        $stmt->close();
        //Redirects to main.php with an alert
        // I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
        echo "<script>
                alert('User Password Updated Succesfuly!');
                window.location.href='main.php';
            </script>";
        exit();
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
    <h1 class="updateUserHead">Please Enter Your New Password.</h1>
    <h2 class="updateUserHead">Once you Hit Submit this value is now your new Password so be careful!</h2>
    <!--Form where users input their new password, be careful as this new password is associated with their new account going forward! -->
    <div class="updateUserForm">
        <form class="formUpdate" action="updateDetails.php" method="POST">
        <input name="password" type="password" placeholder="Password" required />
        <br /><br />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        <input type="submit" name="submitNewDetails" value="Submit" />
        </form>
    </div>
</body>
</html>