<!DOCTYPE html>
<html>
    <head>
        <title>Share page.</title>
    </head>
        
	<body>
    <?php
        session_start();
        $username = $_SESSION['username'];
        $filename = $_GET['filename'];
        $_SESSION['filename'] = $filename;
        
        include 'name_validation.php';
        include 'user_validation.php';
        if(!name_validation($filename, $username))
        {
            $_SESSION['message_return'] = "Username or filename invalid";
            header("Location: ./message_return.php");
            exit;
        }
        if(!user_validation($username))
        {
            $_SESSION['message_return'] = "User not exeist";
            header("Location: ./message_return.php");
            exit;
        }
    ?>
    <p>Whom do you want to share with?</p>
    <p><form action="./file_share.php" method="POST">
		<p>
			<label for="sharenameinput" >Username:</label>
			<input type="text" name="sharename" id="sharenameinput"> 
			<input type="submit" value="Share">
		</p>
	</form></p>
    </body>
</html>