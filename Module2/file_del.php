<?php 
    session_start();
    
    include 'name_validation.php';
    $filename = $_POST['filename'];
    $username = $_SESSION['username'];
    name_validation($filename, $username);
    
    $full_path = sprintf("../module2/%s/%s", $username, htmlentities($filename));
    
    if (unlink($full_path))
    {
        header("Location: ./del_success.html");
        exit;
    }
    else
    {
        header("Location: ./del_failure.html");
        exit;
    }
    
    
?>