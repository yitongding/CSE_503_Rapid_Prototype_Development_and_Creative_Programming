$(function() {
    var socket = io();
    var username;
    
    function userRegister() {
       var username = cleanInput($('.usernameInput').val().trim());
       var password = cleanInput($('.userPasswordInput').val().trim());
       
       socket.emit('user register', username, password);
    }
    
    function userLogin() {
       var username = cleanInput($('.usernameInput').val().trim());
       var password = cleanInput($('.userPasswordInput').val().trim());
       
       socket.emit('user login', username, password);
    }
    
    socket.on('login success', function(un) {
        $('.loginDiv').hide();
        $('.logoutDiv').show();
        $('.usernameShow').text(un);
        username = un;
    });
    
    socket.on('login fail', function() {
        alert('username or password mismatch!');
    });
    
    
    
    function voteCreate(){
        var voteName = $('.nameCreate');
        var voteOptions = {
            option1 = $('.option1'),
            option2 = $('.option2'),
            option3 = $('.option3'),
            option4 = $('.option4'),
            option5 = $('.option5')
        };
        socket.emit('create vote', voteName, voteOptions);
    }
    
    
    
});