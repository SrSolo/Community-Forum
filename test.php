<!--This file contains many Select statements wrapped in functions and other common functions to be easily called throughout portions in the code -->
<?php
	//Connects to the database
	require 'connection.php';

	//This function gets all the Story posts from the database (typically used in main.php) and wraps the values in h1 and anchor tags and returns it
	function getDB() {
		require 'connection.php';
		$stmt = $mysqli->prepare("select * from story order by story_id");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->execute();
		$stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
		$row= '';
		//Concatenate the values to the $row variable and return $row
		while($stmt->fetch()){
		$row .='
				<h1><a href="story.php?id='.$story_id.'">'.htmlentities($title).'</a></h1>
			';
		}
		$stmt->close();
		return $row;
	}

	//This function gets specific user Story posts based on their associated user_id, and concatenates the values to the $row variable and returns it (this is associated with profile viewing)
	function getDBSpecific($user_id) {
		$exist = false; 
		//Connects to the database
		require 'connection.php';
		$stmt = $mysqli->prepare("select * from story where createdby_id = ? order by story_id;");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
		$row= '<h2>Stories:</h2>';
		while($stmt->fetch()){
		$row .='
				<h1><a href="story.php?id='.$story_id.'">'.htmlentities($title).'</a></h1>
				';
			$exist = true; 
		}
		$stmt->close();
		if($exist){
			return $row;
		}
		
		//This is the case that a user has no stories so we return this message
		return '<div>
					<h1 class = "userComm">This User Has No Stories</h1>
					<hr>
			</div>';
	}

	//This function gets a specific users comments based on their user_id and displays them (this is associated with profile viewing)
	function getCommentSpecific($user_id) {
		$exist = false; 

		require 'connection.php';
		//Connects to the database 
		$stmt = $mysqli->prepare("select * from comment where comment_user_id = ? order by comment_story_id;");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('s', $user_id);
		$stmt->execute();
		$stmt->bind_result($comment_id, $text, $comment_user_id, $comment_story_id);
		$row= '<h2>Comments:</h2>';
		while($stmt->fetch()){
		$row .='
			<div>
			<h1 class = "userComm"><a href="story.php?id=' . htmlentities($comment_story_id) . '">' . htmlentities($text) . '</a></h1>
			<hr>
			</div>
			
			';
			$exist = true; 
		}
		$stmt->close();
		//If the users has comments we return them, if not we return the message that the user has no comments
		if($exist){
			return $row;
		}
		else{
			return 
				'<div>
					<h1 class = "userComm">This User Has No Comments</h1>
					<hr>
				</div>';
		}
	}


	//This function gets all of the users in the Database besides the Admin user (who has user_id=1) (This is associated with the feature of admin functionality to delete users) 
	function getUsers() {
		//Connects to the database
		require 'connection.php';
		// Ensure the admin cannot delete their own account as the admin user has a user_id of 1
		$stmt = $mysqli->prepare("select user_id, username from user where user_id > 1");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		
		$stmt->execute();
		$stmt->bind_result($user_id, $username);
		
		// Concatenate the list of users to the $row variable and return it
		$row .= '';
		while ($stmt->fetch()) {
			// List out the users within a form, having the username href to their story page, and the submit passes the token
			$row .= '<form method="post">
						<input type="hidden" name="username" value="' . htmlentities($username) . '" >
						<input type="hidden" name="token" value="' . $_SESSION['token'] . '" >
						<a href="userStory.php?user_id=' . htmlentities($username) . '">' . $username . '</a> 
						<input type="submit" name="submit" value="Delete" >
						<br>
						<br>
					</form>'
					;
		}
			
		$stmt->close();
		return $row;
	}


	//This function grabs the user_id from a username and returns that users user_id
	function getIdFromName($username){
		//Connects to the database
		require 'connection.php';
		// grab user id based on name
		$stmt = $mysqli->prepare("select user_id from user where username = ?");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($user_id);
		
		if ($stmt->fetch()) {
			$stmt->close();
			return $user_id;
		} else {
			$stmt->close();
			return null; // return null if the username doesn't exist
		}
	}


	//This function  a user_id, return that users username
	function getNameFromId($user_id){
		require 'connection.php'; 
		
		// grab username based on id
		$stmt = $mysqli->prepare("select username from user where user_id = ?");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		
		$stmt->bind_param("i", $user_id);
		$stmt->execute();
		$stmt->bind_result($username);
		
		if ($stmt->fetch()) {
			$stmt->close();
			return $username;
		} else {
			$stmt->close();
			return null; // return null if the user_id doesn't exist
		}
	}


	//This function grabs a story based on an inputted story_id
	//It concates the story contents to the $row variable and returns it
	function getStoryFromId($story_id) {
		require 'connection.php';
		$stmt = $mysqli->prepare("select * from story where story_id = ?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $story_id);
		$stmt->execute();
		$stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
		$row= '';
		while($stmt->fetch()){
		$row .='
			<div>
				<h1><a href="#">'.htmlentities($title).'</a></h1>
				<p>'.htmlentities($body).'</p>
				<p><a href='.htmlentities($link).'>'.htmlentities($link).'</a></p>
				<p> Created by '.htmlentities($createdby_id).'</p>
			</div>
			';	
			
		}
		$stmt->close();
		return $row;
	}

	//Gets all of the comments associated with a given story post (joins the user table in order to display the user associated with each comment!)
	function getCommentsFromStoryId($story_id) {
		//Connects to the database
		require 'connection.php';
		$stmt = $mysqli->prepare("select username, text from comment JOIN user ON (user.user_id=comment_user_id) where comment_story_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $story_id);
		$stmt->execute();
		$stmt->bind_result($username, $text);
		$allStoryComments= '';
		while($stmt->fetch()){
		$allStoryComments.='
			<li>
				<a href="userStory.php?user_id=' . htmlentities($username) . '">User: ' . htmlentities($username) . '</a>
				<p>Comment: ' . htmlentities($text) . '</p>
				<hr>
			</li>
			';
		}
		$stmt->close();
		//Returns all of the story comments
		return $allStoryComments;
	}

	//This function gets all of the stories created by a user (based on their user_id which corresponds to createdby_id in the story table)
	function getAllStoriesFromUserId($user_id) {
		require 'connection.php';
		$stmt = $mysqli->prepare("select * from story where createdby_id = ?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
		$row= '';
		while($stmt->fetch()){
		//Concatenates the stories to the $row variable and returns it
		$row .='
			<div>
				<h1><a href="#">'.htmlentities($title).'</a></h1>
				<p>'.htmlentities($body).'</p>
				<p><a href='.htmlentities($link).'>'.htmlentities($link).'</a></p>
				<p> Created by '.htmlentities($createdby_id).'</p>
			</div>
			';	
			
		}
		$stmt->close();
		return $row;
	}


	//This function takes in 'comment_story_id' which corresponds to the story_id that a comment was commented on
	//Returns the title, body, and link corresponding with the comment 
	function getStoryContentsFromStoryId($story_id) {
		//Connects to the database
		require 'connection.php';
		$stmt = $mysqli->prepare("select * from story where story_id = ?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $story_id);
		$stmt->execute();
		$stmt->bind_result($story_id, $title, $body, $link, $createdby_id);
		echo $story_id;
		echo $title;
		echo $body;
		echo $link;
		$stmt->close();
	}

	//This function deletes all comments associated with their user_id (used when an Admin user deltes a users entire account history)
	function delUserComment($user_id){
		require 'connection.php';
		$stmt = $mysqli->prepare("delete from comment where comment_user_id = ?");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$stmt->close();
	}

	//This function deletes all posts associated with their user_id(used when an Admin user deletes an entire account history)
	function delUserPost($user_id){
		require 'connection.php';
		$stmt = $mysqli->prepare("delete from story where createdby_id = ?");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$stmt->close();
	}

	//This function deletes a user from the database based on their username(used when an Admin user deletes an entire account hsitory)
	function delUser($username){
		require 'connection.php';

		$stmt = $mysqli->prepare("delete from user where username = ?");
		if (!$stmt) {
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->close();
	}

?>




