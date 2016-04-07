// Require the packages we will use:
var http = require("http"),
	socketio = require("socket.io"),
	fs = require("fs");
	url = require('url'),
	path = require('path'),
	mime = require('mime');
	
var usernames = {};
var nameToId = {};
var idToName = {};
var roomList = [];
var roomHost = {};
var roomMember = {};
var banList = {};
var roomPassword = {};
	
var app = http.createServer(function(req, resp){
	fs.readFile("chat.html", function(err, data){
        // This callback runs when the client.html file has been read from the filesystem.
        if(err) return resp.writeHead(500);
        resp.writeHead(200);
        resp.end(data);
    });
	
});
app.listen(8000);

// Do the Socket.IO magic:
var io = socketio.listen(app);

io.sockets.on("connection", function(socket){
	// This callback runs when a new Socket.IO connection is established.
	
	socket.on('new message', function (data) {
		console.log("user: "+socket.username+ " message: "+data); // log it to the Node.JS output
        // We tell the client to execute 'new message'
        var room = socket.currentRoom;
        io.to(room).emit('new message', {
            username: socket.username,
            message: data
        });
    });
	
    // Private message handler
    socket.on('new private message', function (message, privateUsername) {
		console.log("user: "+socket.username+ " message: "+message+" to "+privateUsername);
        // We tell the client to execute 'new message'
        if (nameToId[privateUsername]){
            var socketid = nameToId[privateUsername];
			var fromUser = "From " + socket.username;
            socket.broadcast.to(socketid).emit('new message', {
                username: fromUser,
                message: message
            });
			var toUser = "To " + privateUsername;
			socket.emit('new message', {
                username: toUser,
                message: message
            });
        }
        else {
            message = " does not exist";
            socket.emit('new message', {
                username: privateUsername,
                message: message
            });
        }
    });
	
    
	// When the client emits 'add user', this listens and executes
    socket.on('add user', function (username) {
        // we store the username in the socket session for this client
        console.log("newUser: "+username);
        socket.username = username;
		usernames[username] = username;
		nameToId[username] = socket.id;
		idToName[socket.id] = username;
		socket.emit('room list', {room:roomList});
    });
	
    
    // "enter room" handler
	socket.on('enter room', function (roomName) {
		// if not on the ban list
		if (banList[roomName].indexOf(socket.username) == -1) {
			// If the room has password
			if (roomPassword[roomName] != null){
				socket.emit('need password', roomName);
			} else {
				socket.join(roomName);
				socket.currentRoom = roomName;
				console.log("user "+socket.username+" enter room "+roomName);
				// Push new member into member array
				roomMember[roomName].push(socket.username);
				
				// Tell all member the member list
				sendMemberList(roomName);
			}
        } else {
			// on the ban list
            socket.emit('banned room');
        }
	});
	
    // Send member list to room member
	function sendMemberList(roomName){
		var emitData = {
            host : {
                name: idToName[roomHost[roomName]],
                id : roomHost[roomName]
            },
			member : roomMember[roomName]
		};

		// Tell new member the list of member in the room
		io.to(socket.currentRoom).emit('member list', emitData);
	}
	
	
    // "Leave room" handler
	socket.on('leave room', function(data) {
		var roomName = socket.currentRoom;
        var username = socket.username;
        console.log("user "+username+" left room "+roomName);        
        
        // Leave the room
        socket.leave(socket.currentRoom);

        // Remove user from member list
        roomMember[roomName].remove(socket.username);
        
        emptyRoomCheck();
        sendMemberList(roomName);
        
        socket.currentRoom = '';
		// Get room list
		io.emit('room list', {room:roomList});
	});
	
	
    // "Create room" handler 
	socket.on('new room', function(roomName, password){
		socket.currentRoom = roomName;
        socket.join(roomName);
        console.log("user "+socket.username+" create room "+socket.currentRoom);
        // Add new room to the room list array
		roomList.push(roomName);
        // Add room host to the array
		roomHost[roomName] = socket.id;
        // Add creater to the member array
        roomMember[roomName] = [socket.username];
        // Add ban list of room
        banList[roomName] = [];
		// Add password to array
		roomPassword[roomName] = password;
        // Send member list to members
		sendMemberList(roomName);
        // refresh everyone's room list
        io.emit('room list', {room:roomList});
	});
	
    // Add remove by value function to array
    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };
    
    socket.on('disconnect', function () {
        var room = socket.currentRoom;
        var username = socket.username;
        console.log("user "+socket.username+" left room "+socket.currentRoom+ " and disconnect");
        io.to(socket.currentRoom).emit('member left', {
			name: socket.username
		});
        delete usernames[username];
		delete nameToId[username];
		delete idToName[socket.id];
        if ( roomMember[room] !== undefined) {
            roomMember[room].remove(username);
            emptyRoomCheck();
        }
        io.emit('room list', {room:roomList});
    });
	
    
    function emptyRoomCheck() {
        var room = socket.currentRoom;
        var memberArray = roomMember[room];
        if (memberArray.length < 1) {
            delete roomMember[room];
			delete roomPassword[room];
            roomList.remove(room);
            return true;
        }
        return false;
    }
    
    
    function forceLeaveRoom(roomName, username) {
        var userId = nameToId[username];
        console.log("user "+username+" get kicked from room "+roomName);        
        
        // Leave the room
        io.sockets.sockets[userId].leave(roomName);
        
        // Remove user from member list
        roomMember[roomName].remove(username);
        
        sendMemberList(roomName);
        
        io.sockets.sockets[userId].emit('get kicked');
        
        io.sockets.sockets[userId].currentRoom = '';
		// Get room list
		io.emit('room list', {room:roomList});
    }
    
    socket.on('kick member', function(username) {
        var roomName = socket.currentRoom
        if (roomMember[roomName].indexOf(username) != -1) {
            forceLeaveRoom(roomName, username);
        }
    });
    
    socket.on('ban member', function(username) {
        var roomName = socket.currentRoom
        if (roomMember[roomName].indexOf(username) != -1) {
            forceLeaveRoom(roomName, username);
            banList[roomName].push(username);
        }
    });
	
	socket.on('submit password', function(roomName, passwordInput) {
		if(roomPassword[roomName] == passwordInput) {
			socket.join(roomName);
			socket.currentRoom = roomName;
			console.log("user "+socket.username+" enter room "+roomName);
			// Push new member into member array
			roomMember[roomName].push(socket.username);
			socket.emit('password correct');
			// Tell all member the member list
			sendMemberList(roomName);
		} else {
			socket.emit('password wrong');
		}
	});
});