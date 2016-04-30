<!DOCTYPE html>
<html lang="en" ng-app="voteApp">
<head>
    <meta charset="utf-8">
    <title>My AngularJS App</title>
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-route/angular-route.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.js"></script>
	<!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">       
	<script src= "bower_components/angular-bootstrap/ui-bootstrap.js"></script>
	<script src= "bower_components/angular-bootstrap/ui-bootstrap.min.js" type="text/javascript"></script>
	<script src= "bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js" type="text/javascript"></script>
	<script src= "bower_components/angular-smart-table/dist/smart-table.js"></script>-->
	<script src="dbControl.js"></script>
	<script src="app.js"></script>
</head>
<body>
	
<?php
	session_start();
	require 'database.php';
?>

<br>

<?php if (isset($_SESSION['user_id'])): ?>
	<h2>Hello, <? echo $_SESSION['user_id']) ?> </h2>
<?php else: ?>
<?php if (isset($_SESSION['login_error'])): ?>
	<h2>Wrong name or password </h2>
<?php endif ?>
	<div class="loginDiv">
		<form action="./login_redir.php" method="POST">
			<input type="text" name="username" placeholder="Username" maxlength="20">
			<input type="password" placeholder="Password" 
			name="password" maxlength="20">
			<input type="submit" name="loginsub" value="Login">
			<input type="submit" name="loginsub" value="Regester">
		</form>
	</div>
<?php endif ?>

<br>

<table st-table="rowCollection" class="table table-striped">
	<tr>
		<th>Details:</th>
		<th>Vote Name:</th>
		<th>Initiator:</th>
		<th>Vote Counts:</th>
	</tr>
	<?php while ($stmt->fetch()): ?>
		<tr>
			<td><a href="#/detail/?id=<?php echo $vote_id?>">Review</a></td>
			<td><?php echo $vote_name?></td>
			<td><?php echo $vote_host?></td>
			<td><?php echo $vote_cnt?></td>
		</tr>
	<?php endwhile ?>
	
</table>

<div class="voteCreateDialog">	
	<form action="./createvote.php" method="POST">
		<input type="text" class="nameCreate" name="newName">
		<input type="text" class="optionCreate" id="option1" name="option1">
		<input type="text" class="optionCreate" id="option2" name="option2">
		<input type="text" class="optionCreate" id="option3" name="option3">
		<input type="text" class="optionCreate" id="option4" name="option4">
		<input type="text" class="optionCreate" id="option5" name="option5">
		<input type="submit" value="Submit" class="submitCreate">
	</form>
</div>


</body>
</html>