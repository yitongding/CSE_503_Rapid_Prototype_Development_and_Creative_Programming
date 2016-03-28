// ajax.js
 
function loginAjax(event){
	var username = document.getElementById("username").value; // Get the username from the form
	var password = document.getElementById("password").value; // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
 
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "login_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){
			$("#login").hide();
            $("#logout").show();
			$("#icons").show();
            $("#token").val(jsonData.token);
            updateCalendar();
            updateShare();
            alert("You've been Logged In!");
		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}

function logoutAjax(event){
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "logout_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(){
        $("#login").show();
        updateCalendar();
        updateShare();
        $("#token").val("");
        $("#logout").hide();
		$("#icons").hide();
    },false);
    xmlHttp.send(null); // Send the data
}

function register_dialog(){
    $("#register").dialog();
	$("#new_register_btn").click(registerAjax); //register comfrim button listener
}

function registerAjax(event){
    var username = $("#new_username").val(); // Get the username from the form
	var password = $("#new_password").val(); // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "register_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){
			$("#login").hide();
            $("#logout").show();
			$("#icons").show();
            updateCalendar();
            updateShare();
            $("#token").val(jsonData.token);
            alert("You've registered!");
			$("#register").dialog('close');
		}else{
			alert("Register error."+jsonData.message);
		}
	}, false); // Bind the callback to the load event
    xmlHttp.send(dataString);
    
}

//triger when page is ready
$(document).ready(function()    
{
	// check the statue of log in
    var Data = null;
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "signin_check_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success){
			$("#login").hide();
			$("#logout").show();
			$("#icons").show();
            $("#token").val(Data.token);
		} else {
			$("#login").show();
			$("#logout").hide();
			$("#icons").hide();
		}
    }, false);
    xmlHttp.send(null);
});

document.getElementById("login_btn").addEventListener("click", loginAjax, false); // login_btn listener

$("#register_btn").click(register_dialog); //register_btn listener

$("#logout_btn").click(logoutAjax); // logout_btn listener