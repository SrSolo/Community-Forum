<!--This file is responsible for a registered user adding a News Story-->
<?php
//Connects to the database
  require 'connection.php';

  //starts the session
  session_start();

  //if the user isn't logged in and somehow is able to navigate to this page(should never loggically occur but just in case!) they are redirected to the main page as users that are not logged in are unable to add Posts
  if (!array_key_exists("user_id",$_SESSION)) {
    header('Location: main.php');
    exit();
  }
  
  //checks to make sure the submit button is pressed before grabbing POST request variables
  if (isset($_POST['submit'])) {
    //Grabs POST variables for inputting into the story table
    $title = $_POST['title'];
    $body = $_POST['body'];
    $link = $_POST['link'];
    $createdby_id = $_SESSION['user_id'];

    if(!hash_equals($_SESSION['token'], $_POST['token'])){
      die("Request forgery detected");
    }  

    $stmt = $mysqli->prepare("insert into story (title, body, link, createdby_id) values (?, ?, ?, ?)");  //sssi
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }

    $stmt->bind_param('sssi', $title, $body, $link, $createdby_id);

    $stmt->execute();

    $stmt->close();
    //I learned how to use the 'alert and redirect tag on this site: https://stackoverflow.com/questions/11869662/display-alert-message-and-redirect-after-click-on-accept
    echo "<script>
      alert('Post succesfully created! Redirecting you to the main page!');
      window.location.href='main.php';
        </script>";   

  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Post</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!--Navbar-->
  <div class="navBar">
        <a class="child" href="main.php">All Posts</a>
        <a class= "child" href="editStory.php">Edit/Delete Your Posts</a>
        <a class= "child" href="updateComment.php">Edit/Delete Your Comments</a>
        <a class= "child" href="updateUser.php">Edit Password</a>
        <a class= "child" href="logout.php">Logout</a>
    </div>
    <h1 class="postHeader">Add a Post</h1>
    <!--HTML form for adding to a POST, takes in the values title, body, and link from the user-->
    <form action="addPost.php" method="POST">
      <input name="title" type="text" placeholder="Story Title" required />
      <br /><br />
      <input name="body" type="text" placeholder="Body" required />
      <br /><br />
      <input name="link" type="text" placeholder="Story Link" required />
      <br /><br />
      <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
      <input type="submit" name="submit" value="Submit" />
    </form>
  </body>
</html>











