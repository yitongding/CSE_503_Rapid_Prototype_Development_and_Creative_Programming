<?php
    function user_validation($username)
    {
        $username_list = fopen("../module2/users.txt","r");
        $user_found = 0;
        while( !feof($username_list) & $user_found == 0)
        {
            $user_temp = fgets($username_list);
            $user_trim = trim($user_temp);
            if ($user_trim == $username)
            {
                $user_found = 1;
            }
        }
        fclose($username_list);
        return $user_found;
    }
    
    
?>