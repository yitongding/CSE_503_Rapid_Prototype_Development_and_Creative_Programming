<?php
    function name_validation($filename, $username)
    {
        if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
            echo "Invalid filename";
            return 0;
        }
            
        if( !preg_match('/^[\w_\-]+$/', $username) ){
            echo "Invalid username";
            return 0;
        }
        return 1;
    }
    
?>