<?php
    function name_validation($filename, $username)
    {
        if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
            echo "Invalid filename";
            exit;
        }
            
        if( !preg_match('/^[\w_\-]+$/', $username) ){
            echo "Invalid username";
            exit;
        }
        return 0;
    }
    
?>