<!DOCTYPE html>
<html>
<head>
	<title>Share Failure</title>
</head>
    
<body>
    <?php
        printf("<p>Share Failure. %s</p>",$_SESSION['share_error']);
    ?>

	<form action="./user_file.php" method=POST>
		<input type="submit" value="Return">
	</form>

</body>

</hetml>