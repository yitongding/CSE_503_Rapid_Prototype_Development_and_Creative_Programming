<?php
function user_validation($username,$password)
{
	require 'database.php';
	
	$stmt = $mysqli->prepare("select COUNT(*), user_id, user_slated_pw from users where user_name = ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->bind_result($cnt, $user_id $pwd_hash);
	$stmt->fetch();

	$pwd_guess = $password;
	
	// Compare the submitted password to the actual password hash
	if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
		// Login succeeded!
		$_SESSION['user_id'] = $user_id;
		// Redirect to your target page
	}else{
		// Login failed; redirect back to the login screen
	}


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