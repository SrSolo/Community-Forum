<!--This is the page displayed when a user logs into their account -->
<?php
//Starts the session
session_start();
//Waits for the submit button to be pressed before grabbing POST variables to check if a user can login
if(isset($_POST['submit'])) {
    // This is a *good* example of how you can implement password-based user authentication in your web application.
    //Connects to the database
    require 'connection.php';
    // Use a prepared statement
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
        // Login succeeded!
        //Updates SESSION Variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['token'] = bin2hex(random_bytes(32));
        //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
        echo "<script>
                alert('User Succesfully Logged In!');
                window.location.href='main.php';
                </script>";
    } else{
        // Login failed; redirect back to the login screen
        //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
        echo "<script>
         alert('Login Uncessful! Try again!');
         window.location.href='login.php';
         </script>";    
        }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <div class="navBar">
                <a class="child" href="main.php">All Posts</a>
                <a class= "child" href="register.php">Create a New Account</a>
    </div>
    <h1 class="loginHeader">Login to your Account!</h1>
    <!--HTML Form whers user inputs: username and password, once submit button is pressed the variables are passed via POST-->
    <form action="login.php" method="POST">
      <input name="username" type="text" placeholder="Username" required />
      <br /><br />
      <input name="password" type="password" placeholder="Password" required />
      <br /><br />
      <input type="submit" name="submit" value="Submit" />
    </form>
  </body>
</html>