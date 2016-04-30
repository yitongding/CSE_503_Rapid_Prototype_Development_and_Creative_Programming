var MongoClient = require('mongodb').MongoClient
var assert = require('assert');
var url = 'mongodb://54.200.82.237:27017/voting';
var usersCollection = null;
var votesCollection = null;

var user = null;

var db = MongoClient.connect(url, function(err, db) {
    if(err)
        throw err;
    console.log("connected to the mongoDB !");
    usersCollection = db.collection('users');
    votesCollection = db.collection('votes');
});

function userRegister(username, password) {
    /* user regester */
    var passwordHash = require('password-hash');
    var hashedPassword = passwordHash.generate(password);
    var newUser = {
        name: username,
        password: hashedPassword
    }
    // before insert, check if username been used
    var nameUsedFlag = false;
    var cursor = usersCollection.find({ "name" : username});
    cursor.each(function(err, doc) {
        if(err) throw err;
        if(doc==null) return;
        nameUsedFlag = true;
    });
    if (nameUsedFlag) {
        // username been used
        alert("User name used.");
		return false;
    } else {
        // insert new user
        usersCollection.insert(newUser, function(err, result) {
            if(err) throw err;
            console.log("entry saved");
        });
		return true;
    }
}






/* user login */
function userLogin(username, password) {
	var hashedPassword = "";

	var cursor = usersCollection.find({ "name" : username});
	cursor.each(function(err, doc) {
		if(err) throw err;
		if(doc==null) return;
		hashedPassword = doc.password;
	});

	if (passwordHash.verify(password, hashedPassword)) {
		user = username;
		return true;
	} else {
		alert("Password wrong");
		return false;
	}
}


function voteCreate(){
	/* Vote list insert */
	var vote_host = user;
	var voteName = $('.nameCreate');
	var voteOptions = {
		option1 : $('.option1'),
		option2 : $('.option2'),
		option3 : $('.option3'),
		option4 : $('.option4'),
		option5 : $('.option5')
	};
	var vote_comment = [];
	var new_vote = {
		name: vote_name,
		options: vote_options,
		host: vote_host,
		comment: vote_comment,
		active: true
	};

	votesCollection.insert(new_vote, function(err, result) {
		if(err) throw err;
		console.log("entry saved");
	});
}



function findVoteList() {
	/* Vote list find */
	var votesList = votesCollection.find().toArray();
	return votesList;
}


/* update certain vote */
function incCount(voteName, voteOption) {
	//var voteName = "";
	//var voteOption = "";
	votesCollection.update(
		{'name':voteName, 'options.optionName':voteOption},
		{$inc:{ 'options.$.optionCount': 1}}
	);
}




/* terminate the vote */
function endVote(voteName){
	//var voteName = "";
	votesCollection.update(
		{'name':voteName},
		{$set:{ 'active': false}}
	);
}



function insertComment(voteName, voteCommentUser, voteCommentMessage) {
	/* Add comment to the vote*/
	var voteName = "";
	var voteCommentUser = "";
	var voteCommentMessage = "";
	var commentObj = {
		commentUser: voteCommentUser,
		commentMessage: voteCommentMessage
	};
	votesCollection.update(
		{'name': voteName},
		{$addToSet: {'comment': commentObj} }
	);
}



function findCommentList(){
	/* find the comment list */
	var voteName = "";
	var commentList = null;

	var cursor = usersCollection.find({ "name" : voteName});
	cursor.each(function(err, doc) {
		if(err) throw err;
		if(doc==null) return;
		commentList = doc.comment;
	});
}



