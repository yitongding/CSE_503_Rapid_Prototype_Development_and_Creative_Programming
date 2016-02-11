<?php 
    session_start();
    
    include 'name_validation.php';
    $filename = $_GET['filename'];
    $username = $_SESSION['username'];
    name_validation($filename, $username);
    
    $full_path = sprintf("../module2/%s/%s", $username, htmlentities($filename));
    
    if (unlink($full_path))
    {
        $_SESSION['message_return'] = "Delete success";
        header("Location: ./message_return.php");
        exit;
    }
    else
    {
        $_SESSION['message_return'] = "Delete failure";
        header("Location: ./message_return.php");
        exit;
    } 
    
    
?>