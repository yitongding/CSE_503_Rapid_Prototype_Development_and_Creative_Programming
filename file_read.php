
	<?php
		session_start();
        include 'name_validation.php';
		 
		$filename = $_GET['file'];
        $username = $_SESSION['username'];
        name_validation($filename, $username);
		/*if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
			echo "Invalid filename";
			exit;
		}
		 
		if( !preg_match('/^[\w_\-]+$/', $username) ){
			echo "Invalid username";
			exit;
		}
		*/
		$full_path = sprintf("../module2/%s/%s", $username, htmlentities($filename));
		 
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($full_path);
		 
		header("Content-Type: ".$mime);
		readfile($full_path);
		 
	?>
