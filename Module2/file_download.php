<?php

session_start();
    
    include 'name_validation.php';
    $filename = $_GET['filename'];
    $username = $_SESSION['username'];
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
    
    $full_path = sprintf("../module2/%s/%s", $username, htmlentities($filename));
    
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

?>