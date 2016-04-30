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

<br>
<h1>Vote for <?php echo $vote_name?></h1>
<p>Initiator: <?php echo $vote_host?></p>
<table st-table="rowCollection" class="table table-striped">
	<tr>
		<th>Option:</th>
		<th>Vote Count:</th>
		<th>Percentage:</th>
		<th>Vote for:</th>
	</tr>
	<?php while(false) : ?>
	<tr>
		<td><?php echo $option_name?></td>
		<td><?php echo $option_count?></td>
		<td><?php echo $option_percen?></td>
		<td>
			<form action="./votefor.php">
				<button class="voteButton" id="{{option.id}}">Vote for it!</button>
			</form>
		</td>
	</tr>
	<?php endwhile ?>
</table>

<div class="newComment">
	<input type="text" maxlength="140" placeholder="new comment">
	<button class="newCommentSubmit">Submit</button>
</div>

<div class="commentList" ng-repeat="comment in vote.comment">
	<div class="commentBody" id="{{comment.id}}">
		<p class="commentUser">{{comment.user}}: </p>
		<p class="commentMessage">{{comment.message}}</p>
		<br>
	</div>
</div>


</body>
</html>

