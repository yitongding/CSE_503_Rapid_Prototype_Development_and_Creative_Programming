var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");
	url = require('url'),
	path = require('path'),
	mime = require('mime');
var passwordHash = require('password-hash');


/*database setup */
var MongoClient = require('mongodb').MongoClient
var assert = require('assert');
var url = 'mongodb://localhost:27017/voting';
var usersCollection = null;
var votesCollection = null;

var db = MongoClient.connect(url, function(err, db) {
    if(err)
        throw err;
    console.log("connected to the mongoDB !");
    usersCollection = db.collection('users');
    votesCollection = db.collection('votes');
});


/*app setup */    
var app = http.createServer(function(req, resp){
	fs.readFile("list.html", function(err, data){
        // This callback runs when the client.html file has been read from the filesystem.
        if(err) return resp.writeHead(500);
        resp.writeHead(200);
        resp.end(data);
    });
	
});
app.listen(8000);

var io = socketio.listen(app);


/* function start */
io.socket.on('user register', function(un, pw) {
    userRegister(un,pw);
});

function userRegister(username, password) {
    /* user regester */
    
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
        socket.emit('login fail');
    } else {
        // insert new user
        usersCollection.insert(newUser, function(err, result) {
            if(err) throw err;
            console.log("entry saved");
        });
        socket.username = username;
        socket.eimt('login success',username);
    }
}


io.socket.on('user login', function(un, pw) {
    userlogin(un,pw);
});

function userlogin(username, password) {
    var hashedPassword = null;

    var cursor = usersCollection.find({ "name" : username});
    cursor.each(function(err, doc) {
        if(err) throw err;
        if(doc==null) return;
        hashedPassword = doc.password;
    });

    if (passwordHash.verify(password, hashedPassword)) {
        // password right
        socket.username = username;
        socket.emit('login success',username);
    } else {
        // password wrong
        socket.emit('login fail');
    }
}