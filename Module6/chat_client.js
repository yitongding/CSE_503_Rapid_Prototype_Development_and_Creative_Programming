$(function() {

    //Initialize variables
    var $window = $(window);
    var $usernameInput = $('.usernameInput'); // Input for username
    var $messages = $('.messages'); // Messages area
    var $inputMessage = $('.inputMessage'); // Input message input box

    var $loginPage = $('.login.page'); // The login page
    var $chatPage = $('.chat.page'); // The chatroom page
	var $roomPage = $('.room.page'); // The room list page 
	var $passwordPage = $('.password.page'); // The enter room passwprd page
	
    // Prompt for setting a username
    var username;
    var connected = false;
    var typing = false;
    ///////var lastTypingTime;
    var $currentInput = $usernameInput.focus();

    var socket = io();
    
	// Listen the ENTER key
	/*$window.keydown(function (event) {
        // Auto-focus the current input when a key is typed
        //if (!(event.ctrlKey || event.metaKey || event.altKey)) {
        //    $currentInput.focus();
        //}
        // When the client hits ENTER on their keyboard
        if (event.which === 13) {
            if (username) {
                sendMessage();
            } else {
                setUsername();
            }
        }
    });*/

    function cleanInput (input) {
        return $('<div/>').text(input).text();
    }
	
    // Sets the client's username
    function setUsername () {
        username = cleanInput($usernameInput.val().trim());

        // If the username is valid
        if (username) {
            $loginPage.hide();
            $chatPage.hide();
            $roomPage.show();
            //////$loginPage.off('click');
            $currentInput = $inputMessage.focus();

            // Tell the server your username
            socket.emit('add user', username);
        }
    }
    
    // Sends a chat message
    function sendMessage () {
        var message = $inputMessage.val();
        // Prevent markup from being injected into the message
        message = cleanInput(message);
        var privateUsername = $('.privateUsername').val();
        if (privateUsername) {
			if (message) {
				$inputMessage.val('');
				socket.emit('new private message', message, privateUsername)
			}
        }
        // if there is a non-empty message
        else if (message) {
            $inputMessage.val('');
            // tell server to execute 'new message' and send along one parameter
            socket.emit('new message',message);
        }
    }
	
	
	// Add chat massage to the screen 
	function addChatMessage (data, options) {
        var $usernameDiv = $('<span class="username"/>')
            .text(data.username);
        var $messageBodyDiv = $('<span class="messageBody">')
            .text(data.message);
        var $messageDiv = $('<li class="message"/>')
            .data('username', data.username)
            .append($usernameDiv, $messageBodyDiv);

        addMessageElement($messageDiv, options);
    }
	
	// Add any element to the Message window
	function addMessageElement (el, options) {
        var $el = $(el);
        $messages.append($el);
        $messages[0].scrollTop = $messages[0].scrollHeight;
    }
    
	// Show room list
	function showRoomList(data) {
		var $roomList = $('.roomList');
        $roomList.html('');
		for (roomIdx in data.room) {
			var $roomBodyDiv = $('<span class="roomBody">')
				.text(data.room[roomIdx])
                .attr("id",data.room[roomIdx]);
			var $roomDiv = $('<li class="room"/>')
				.append($roomBodyDiv);
			$roomList.append($roomDiv);
		}
	}
	

	// Send enter room message to server
	function enterRoom(roomName) {
		$roomPage.hide();
		$chatPage.show();
        $('.messages').html('');
        $('.memberList').html('');
		socket.emit('enter room', roomName);
	}
	
    
	// Receive whole room member list from server
    function receiveMemberList(data) {
        var $memberList = $('.memberList');
        $memberList.html('');
        
        var $hostBodyDiv = $('<span class="hostBody">')
				.text("Host of the room: "+data.host.name);
        var $hostDiv = $('<li class="host"/>')
				.attr("id",data.host.id)
				.append($hostBodyDiv);
        $('.memberList').append($hostDiv);
        
        for (memberIdx in data.member) {
			var $memberBodyDiv = $('<span class="memberBody">')
				.text(data.member[memberIdx]);
			var $memberDiv = $('<li class="member"/>')
				.attr("id",data.member[memberIdx])
				.append($memberBodyDiv);
			$('.memberList').append($memberDiv);
		}
        
        refreshHost(data);
    }
    
    function refreshHost(data) {
        if (data.host.id == "/#"+socket.id){
            $('.memberList').html('');
            
            var $hostBodyDiv = $('<span class="hostBody">')
				.text("You are the Host");
            var $hostDiv = $('<li class="host"/>')
                    .attr("id",data.host.id)
                    .append($hostBodyDiv);
            $('.memberList').append($hostDiv);
        
            for (memberIdx in data.member) {
                var $memberBodyDiv = $('<span class="memberBody">')
                    .text(data.member[memberIdx]);
                var $muteButton = $('<button class="muteButton">')
                    .attr("id",data.member[memberIdx])
                    .text("Mute");
                var $kickButton = $('<button class="kickButton">')
                    .attr("id",data.member[memberIdx])
                    .text("Kick");
                var $banButton = $('<button class="banButton">')
                    .attr("id",data.member[memberIdx])
                    .text("Ban");
                var $memberDiv = $('<li class="member"/>')
                    .append($memberBodyDiv)
                    .append($muteButton)
                    .append($kickButton)
                    .append($banButton);
                $('.memberList').append($memberDiv);
            }
            var $closeButton = $('<button class="closeButton">')
            .text("Close the room");
            $('.memberList').append($closeButton);
        }
    }
    

	// Add new room member to the member list
	function newRoomMember(data) {
		var $memberList = $('.memberList');
		var $memberBodyDiv = $('<span class="memberBody">')
			.text(data.name)
            .attr("id",data.name);
		var $memberDiv = $('<li class="member"/>')
			.append($memberBodyDiv);
		$('.memberList').append($memberDiv);
	}

	
	// Leave room function
	function returnToRoomList(){
		// Clean the message page 
		$('.messages').html('');
		// Clean room member list
		$('.memberList').html('');
		$chatPage.hide();
		$roomPage.show();
		// Tell the server the you have left the room 
		socket.emit('leave room', 'leave room');
	}
	

	// Remove left member from the list
	function leftRoomMember(data) {
		leftMemberId = "#" + data.name;
		$(leftMemberId).remove();
	}
		
	function createNewRoom(){
		var roomName = $('.newRoomNameInput').val();
		var password = $('.newRoomPasswordInput').val();
		socket.emit('new room', roomName, password);
		$roomPage.hide();
		$chatPage.show();
	}
	
    function kickMember(userId){
        socket.emit('kick member',userId);
    }
    
    function banMember(userId){
        socket.emit('ban member', userId);
    }
    
    function muteMember(userId){
        socket.emit('mute member', userId);
    }
    
    function closeRoom(){
        socket.emit('close room');
    }
    
	function passwordSubmit(){
		var passwordInput = $('.roomPassword').val();
		$('.roomPassword').val('');
		var roomName = $('.roomPassword').attr('id');
		socket.emit('submit password', roomName, passwordInput);
	}
	
	
    
    
    $(document).ready(function(){
        $roomPage.hide();
        $chatPage.hide();
		$passwordPage.hide();
    });
    
    $('.usernameSubmit').click(function(){
        setUsername();
    });
    
    $('.messageSubmit').click(function(){
        sendMessage();
    });
    
    $('.roomListRefresh').click(function(){
        socket.emit('request room list');
    });
    
    // Create event listener for enter room click
    $('.roomList').on('click','.room',function(e){
        enterRoom(e.target.id);
    });
    
    // Listener to leave room button
	$('.returnToRoomList').click(function (){
		returnToRoomList();
	});
    
    // Listen to the new room create button
	$('.createNewRoom').click(function(){
		createNewRoom();
	});
	
	// Listrn to the password submit button
    $('.passwordSubmit').click(function(){
		passwordSubmit();
	});
	
	$('.passwordCancel').click(function(){
		$('.roomPassword').val('');
		$passwordPage.hide();
		$roomPage.show();
	});
	
	$('.memberList').on('click','li > .kickButton',function(e){
         kickMember(e.target.id);
    });
    
    $('.memberList').on('click','li > .banButton',function(e){
         banMember(e.target.id);
    });
    
    $('.memberList').on('click','li > .muteButton',function(e){
         muteMember(e.target.id);
    });
    
    $('.memberList').on('click','.closeButton',function(e){
         closeRoom();
    });
    
    // Listen to the new message from server
	socket.on('new message', function (data) {
        addChatMessage(data);
    });
    
    // Listen the room list from server
	socket.on('room list', function (data) {
		showRoomList(data);
	});
    
    socket.on('member list', function(data) {
        receiveMemberList(data);
	});
    
    // Receive new room member from server
	socket.on('member join', function(data){
		newRoomMember(data);
	});
    
    // Receive member left room from server
	socket.on('member left', function(data){
		leftRoomMember(data);
	});
    
    socket.on('get muted', function(){
        alert("You are muted by the host.");
    });
    
    socket.on('get kicked',function() {
        $roomPage.show();
		$chatPage.hide();
        alert("You are kicked from the room.");
    });
    
    socket.on('banned room', function() {
        $roomPage.show();
		$chatPage.hide();
        alert("You are banned from the room.");
    });
    
	socket.on('need password', function(roomName) {
		$chatPage.hide();
		$passwordPage.show();
		$('.roomPassword').attr("id",roomName);
	});
	
	socket.on('password correct', function() {
		$chatPage.show();
		$passwordPage.hide();
	});
	
	socket.on('password wrong', function() {
		alert("Password incorrect.");
	});
    
    socket.on('close room', function(){
        alert("Host closed the room.");
        $chatPage.hide();
        $roomPage.show();
    });
	
});

