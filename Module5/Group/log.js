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
            updateCalendar();
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
        $("#logout").hide();
    },false);
    xmlHttp.send(null); // Send the data
}

function register_dialog(){
    $("#register").dialog();
}

function registerAjax(event){
    var username = document.getElementById("new_username").value; // Get the username from the form
	var password = document.getElementById("new_password").value; // Get the password from the form
 
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
            updateCalendar();
            alert("You've registered!");
		}else{
			alert("Register error."+jsonData.message);
		}
	}, false); // Bind the callback to the load event
    xmlHttp.send(dataString);
    
}

//triger when page is ready
$(document).ready(function()    
{
    $("#login").show();
    $("#logout").hide();
});

document.getElementById("login_btn").addEventListener("click", loginAjax, false); // login_btn listener

$("#register_btn").click(register_dialog); //register_btn listener

$("#new_register_btn").click(registerAjax); //register comfrim button listener

$("#logout_btn").click(logoutAjax); // logout_btn listener