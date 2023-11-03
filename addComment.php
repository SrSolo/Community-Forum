<!--The code in this file essentially adds a comment to the database from a story page-->
<?php
	//checks to ensure the submit button was pressed
    if(isset($_POST['submit'])) {
		//starts session()
        session_start();
		//connects to database
        require 'connection.php';
		//grabs text, comment_story_id, and comment_user_id from POST parameters
        $text = $_POST['commentText'];
        $comment_story_id = $_POST['story_id'];
        $comment_user_id = $_POST['commentor_id'];
		
		//Query statement for inserting comments
		$stmt = $mysqli->prepare("insert into comment (text, comment_user_id, comment_story_id) values (?, ?, ?)");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}

		$stmt->bind_param('sii', $text, $comment_user_id, $comment_story_id);

		$stmt->execute();

		$stmt->close();

		//Sends user back to the news story page after inserting a comment
        header("Location: story.php");
        
    }
?>