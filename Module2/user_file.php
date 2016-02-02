<!DOCTYPE html>
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>User's Files</title>
<head>

<body>
	<?php
		session_start();
		printf("Hello, <strong>%s</strong>! Here are your files:",$_SESSION['username']);
		$dir = sprintf("../module2/%s",$_SESSION['username']);
		$file_list = scandir($dir);
		$file_num = count($file_list);
		while ($file_num > 2)
		{
			$file_name = $file_list[$file_num - 1];
			//$flink = 
			printf("<p><a href='./file_read.php?file=%s'>%s</a> \t",$file_name,$file_name);
            /*printf("<form action='./file_del.php?file=%s' method='GET'><input type='submit' value='Delete File'></form></p>", $file_name);
            printf('
                <form action="./login_redir.php?file_name=%s" method="POST">
                    <label for="shareusernameinput" >Share with User:</label>
                    <input type="text" name="shareusername" id="shareusernameinput">
                    <input type="submit" value="Share">
                </form>
            ',$file_name);
            */
			$file_num = $file_num - 1;
		}
	?>
	
	<p>If you want to upload more files:</p>
	<p>
		<form enctype="multipart/form-data" action="uploader.php" method="POST">
			<p>
				<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
				<label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
				<input type="submit" value="Upload File" />
			</p>
		</form>
	</p>
    
    
	<p>If you want to delete a file:</p>
	<p><form action="./file_del.php" method="POST">
		<p>
			<label for="filenameinput" >Filename:</label>
			<input type="text" name="filename" id="filenameinput">
			<input type="submit" value="Delete">
		</p>
	</form><p>
    
    <p>If you want to share a file with someone:</p>
    <p><form action="./file_share.php" method="POST">
		<p>
			<label for="sharenameinput" >Username:</label>
			<input type="text" name="sharename" id="sharenameinput">
            <label for="filenameinput" >Filename:</label>
			<input type="text" name="filename" id="filenameinput">            
			<input type="submit" value="Share">
		</p>
	</form></p>
    
    
	<p>
	<form action="./logout.php" method=POST>
		<input type="submit" value="Logout">
	</form>
	</p>
	
</body>

</html>