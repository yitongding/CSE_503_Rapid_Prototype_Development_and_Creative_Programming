<!DOCTYPE html>
<html>
    
<?php 
    session_start();
    $message = $_SESSION['message_return'];
    
    printf("<head><title>%s</title></head>", $message);
    printf("<body><p>%s.</p>", $message);
?>
	<form action="./user_file.php" method=POST>
		<input type="submit" value="Return">
	</form>

</body>

</html>