//calender.js

// updeate calender when month change
function updateList(){
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "votes_provider_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        $('.listtable').html('<tr><th>Details:</th><th>Vote Name:</th><th>Initiator:</th><th>Vote Counts:</th><th>Active:</th></tr>');
		var Data = JSON.parse(event.target.responseText);
		for (v in Data) {
			var vote_id = Data[v].id.$id;
			var vote_name = Data[v].name;
			var vote_host = Data[v].host;
			var vote_count = Data[v].votecount;
			if (Data[v].endflag) {
				var endflag = "Vote End";
			} else {
				var endflag = "Vote On Going";
			}
			$('.listtable tr:last').after('<tr><th><a href="./detail_bs.html?id='+vote_id+'" class="btn btn-default">Detail</a></th><th>'+vote_name+'</th><th>'+vote_host+'</th><th>'+vote_count+'</th><th>'+endflag+'</th></tr>');
		}
    }, false);
    xmlHttp.send(null);
}

// event create uploader
function create_event_upload(){
    var name = $("#nameCreate").val();
    var option1 = $("#option1").val();
	var option2 = $("#option2").val();
    var option3 = $("#option3").val();
    var option4 = $("#option4").val();
	var option5 = $("#option5").val();
    
    var dataString = "name=" + encodeURIComponent(name) + "&option1=" + encodeURIComponent(option1) + "&option2=" + encodeURIComponent(option2) + "&option3=" + encodeURIComponent(option3) + "&option4=" + encodeURIComponent(option4) + "&option5=" + encodeURIComponent(option5);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "vote_creater_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
            updateList();
			alert("vote create success.");  
		} else {
			alert("vote create fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}

// initial calender when page is ready
$(document).ready(function(){
	updateList();
	signinCheck();
});

/*****************************
// new vote dialog
var new_vote_dialog, new_vote_form;
new_vote_dialog = $("#voteCreateDialog").dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: false,
    buttons: {
        "Create vote": create_event_upload,
        Cancel: function() {
            new_vote_dialog.dialog( "close" );
        }
    },
    close: function() {
        new_vote_form[ 0 ].reset();
        //allFields.removeClass( "ui-state-error" );
    }
});

new_vote_form = new_vote_dialog.find("form").on("submit", function(event){
    event.preventDefault();
    create_event_upload();
});

$("#new_vote_create_btn").button().click(function(){
	new_vote_dialog.dialog("open");
});
/*****************************/


$(document).on("click", ".submitCreate", function(event){
    create_event_upload();
});
