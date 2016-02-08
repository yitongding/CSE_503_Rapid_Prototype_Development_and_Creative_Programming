<!DOCTYPE html>
<html>

<head>
	<!--
    <link rel="stylsheet" type="text/css" href="./cal_stylesheet.css"/>
    -->
	<title>Uploader</title>
<head>

<body>
<p>
<?php
    session_start();
    
    // Get the filename and make sure it is valid
    $filename = basename($_FILES['uploadedfile']['name']);
    if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
        echo "Invalid filename";
        exit;
    }
    
    // Get the username and make sure it is valid
    $username = $_SESSION['username'];
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username";
        exit;
    }
    
    $full_path = sprintf("../module2/%s/%s", $username, $filename);
    
    if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
        if (chmod ($full_path, 777))
        {
            $_SESSION['message_return'] = "Upload success";
            header("Location: ./message_return.php");
            exit;
        }
        else
        {
            $_SESSION['message_return'] = "Upload failure";
            header("Location: ./message_return.php");
            exit;
        } 
    }
    else
    {
        $_SESSION['message_return'] = "Upload failure";
        header("Location: ./message_return.php");
        exit;
    } 
?>
</p>
</body>
</html>