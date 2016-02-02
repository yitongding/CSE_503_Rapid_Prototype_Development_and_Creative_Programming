<?php
    session_start();
    
    include 'name_validation.php';
    $filename = $_POST['filename'];
    $sharename = $_POST['sharename'];
    $username = $_SESSION['username'];
    name_validation($filename, $username);
    name_validation($filename, $sharename);
    
    $_SESSION['share_error'] = "success";
    
    include 'user_validation.php';
    if( !user_validation($sharename) & $username == $sharename )
    {
        $_SESSION['share_error'] == "Share user name invalid.";
        header("Location: ./share_failure.php");
    }
    
    
    $source_path = sprintf("../module2/%s/%s", $username, htmlentities($filename));
    $dest_path = sprintf("../module2/%s/%s", $sharename, htmlentities($filename));
    if (copy($source_path, $dest_path))
    {
        header("Location: ./share_success.html");
        exit;
    }
    else
    {
        $_SESSION['share_error'] = "Copy failure.";
        header("Location: ./share_failure.php");
        exit;
    } 
    

?>