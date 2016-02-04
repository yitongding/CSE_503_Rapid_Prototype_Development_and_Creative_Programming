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
		printf("<p>Hello, <strong>%s</strong>!</p> <p>Here are your files:</p>",htmlentities($_SESSION['username']));
		$dir = sprintf("../module2/%s",$_SESSION['username']);
		$file_list = scandir($dir);
		$file_num = count($file_list);
        printf("<table border=\"1\" style=\"width:100%%\">");
		while ($file_num > 2)
		{
			$file_name = $file_list[$file_num - 1];
            printf("<tr><td><a href='./file_read.php?file=%s'>%s</a></td>",htmlentities($file_name),htmlentities($file_name));
            printf("<td><a href='./file_download.php?file=%s'>Download</a> </td>",htmlentities($file_name));
            printf("<td><a href='./file_del.php?filename=%s'>Delete</a> </td>",htmlentities($file_name));
            printf("<td><a href='./share_page.php?filename=%s'>Share</a> </td>",htmlentities($file_name));
            printf("</tr>");
            
			$file_num = $file_num - 1;
		}
        printf("</table>");
	?>
	
	<p>If you want to <strong>upload</strong> more files:</p>
	<p>
		<form enctype="multipart/form-data" action="uploader.php" method="POST">
			<p>
				<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
				<label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input" />
				<input type="submit" value="Upload File" />
			</p>
		</form>
	</p>
    
    <!--
    <p>If you want to <strong>share</strong> a file with someone:</p>
    <p><form action="./file_share.php" method="POST">
		<p>
			<label for="sharenameinput" >Username:</label>
			<input type="text" name="sharename" id="sharenameinput">
            <label for="filenameinput" >Filename:</label>
			<input type="text" name="filename" id="filenameinput">            
			<input type="submit" value="Share">
		</p>
	</form></p>
    -->
    
	<p>
	<form action="./logout.php" method=POST>
		<input type="submit" value="Logout">
	</form>
	</p>
	
</body>

</html>