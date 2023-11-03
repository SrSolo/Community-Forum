<!--This page is displayed when a user wants to create a new account-->
<!--Note: After registering an account user's will NOT be automatically loged in -->
<!--Note: After registering, users will be redirected to main.php and then they can proceed to login.php to login to their newly created user-->

<?php
  //Connects to the database
  require 'connection.php';
  //Starts the Session
  session_start();

  //Checks to make sure the submit button is pressed before grabbing POST variables sent from the registration form
  if (isset($_POST['submit'])) {
    //Grabs the post varaibles from the registration form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_passowrd = password_hash($password, PASSWORD_BCRYPT);
    //Inserts the username and hasehd password into the database
    $stmt = $mysqli->prepare("insert into user (username, hashed_password) values (?, ?)");  
          if(!$stmt){
          printf("Query Prep Failed: %s\n", $mysqli->error);
          exit;
          }

          $stmt->bind_param('ss', $username, $hashed_passowrd);

          $stmt->execute();

          $stmt->close();
          //Redirects user to main.php
          //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
          echo "<script>
                  alert('User Succesfully Created! Login to use the User!');
                  window.location.href='main.php';
                </script>";
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
      <a class ="child" href="login.php">Login</a>
    </div>
    <h1 class="registerHeader">Register A New User</h1>
    <!--Registration form: takes in the input username and password which are sent via POST method to be inserted into the database-->
    <form action="register.php" method="POST">
      <input name="username" type="text" placeholder="Username" required />
      <br /><br />
      <input name="password" type="password" placeholder="Password" required />
      <br /><br />
      <input type="submit" name="submit" value="Submit" />
    </form>
  </body>
</html>











