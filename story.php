<!--When a User clicks on a story in main.php, they are directed to this page to view the story-->
<?php
  //Starts the session
  session_start();
  //Checks to see if the submit button (for inserting comments) into the comments table has been pressed
  if(isset($_POST['submit'])) {
      //Connects to the database
      require 'connection.php';
      //Grabs the POST varaibles associated with inserting a comment
      $text = $_POST['commentText'];
      $comment_story_id = $_POST['story_id'];
      $comment_user_id = $_POST['commentor_id'];
      if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
      }
      //Inserts comment into the database
      $stmt = $mysqli->prepare("insert into comment (text, comment_user_id, comment_story_id) values (?, ?, ?)");
      if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }

      $stmt->bind_param('sii', $text, $comment_user_id, $comment_story_id);

      $stmt->execute();

      $stmt->close();
        
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
        //Checks to see if a user is logged in or not depending on what navbar to dispaly them
        if (array_key_exists("user_id", $_SESSION)) {
            //user is logged in
            echo '
                    <div class="navBar">
                      <a class="child" href="main.php">All Posts</a>
                      <a class ="child" href="editStory.php">Edit/Delete Your Posts</a>
                      <a class= "child" href="updateComment">Edit/Delete Your Comments</a>
                      <a class= "child" href="addPost.php">Add Post</a>
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
        //Connects to the database
        require 'connection.php';
        //Includes test.php which contains many SELECT queries to be accessed from anywhere in the code
        require 'test.php';
        $story_id = '';
        //Checks to see if a STORY_ID has been passed through the url parameters (which should always occur when a user clicks on a story from main.php, just checking to be safe!)
        if (array_key_exists("id",$_REQUEST)) {
            $story_id = $_REQUEST['id'];  //grabs the story_id from the url parameters
        } else {
            //case that the user goes to story.php (with no id) this should never loggically occur but just being sure!
            header('Location: http:main.php');
            exit;
        }

        //fetches the title, body, and link from the story table
        $stmt = $mysqli->prepare("select * from story where story_id = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $story_id);
        $stmt->execute();

        $stmt->bind_result($story_id, $title, $body, $link, $createdby_id);

        $stmt->fetch();
        $name = getNameFromId($createdby_id); //Grabs the author of the story (name) based on the stories createdby_id (one of the story columns), this method is located in test.php 
        echo '<h1>Story Title: '.$title.' <a href="userStory.php?user_id='.$name.' "> Posted By: ' . htmlentities($name) . '</a>  (Click on name to view author profile!)</h1>'; 
        echo "<p class='story_body'>Story Body: $body</p>";
        echo "<h2>Story Link:  <a href='$link'>$link</a></h2>";  
        $stmt->close();
    ?>  
    <!--Comment Section-->
    <h2>Comment Section- Click on Commentor Name to View their Profile!</h2>
      <div class="comments">
      <hr>
        <?php
        //Submit Comment Functionality: first ensures a user is logged in and then logged in user can submit comments to the associated story 
        if (array_key_exists("user_id",$_SESSION)) {
            $user_id = $_SESSION['user_id'];
            echo '
                    <form action="story.php" method="POST">
                    <input type="hidden" name="id" value="'.$story_id.'"/>  
                    <input type="hidden" name="story_id" value="'.$story_id.'"/> 
                    <input type="hidden" name="commentor_id" value="'.$user_id.'"/> 
                    <h2 class="addCommentHeader">Add Comment</h2>
                    <input type="text" name="commentText" placeholder="Comment Text" required><br>
                    <input type="hidden" name="token" value="'.$_SESSION["token"].'" />
                    <input type="submit" name="submit" value="Submit">
                    </form> 
                ';
        }
        //the getCommentsFromStoryId() method is located in test.php
        //fetches all the comments from the comment table based on the storyId
        $comments = getCommentsFromStoryId($story_id); 
        //echo's out all of the comment's associated with a single story
        echo $comments;
        ?>
      </div>
  </body>
</html>


