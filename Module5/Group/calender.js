//calender.js

/********************/
/*****  library *****/
/********************/
(function(){Date.prototype.deltaDays=function(c){return new Date(this.getFullYear(),this.getMonth(),this.getDate()+c)};Date.prototype.getSunday=function(){return this.deltaDays(-1*this.getDay())}})();
function Week(c){this.sunday=c.getSunday();this.nextWeek=function(){return new Week(this.sunday.deltaDays(7))};this.prevWeek=function(){return new Week(this.sunday.deltaDays(-7))};this.contains=function(b){return this.sunday.valueOf()===b.getSunday().valueOf()};this.getDates=function(){for(var b=[],a=0;7>a;a++)b.push(this.sunday.deltaDays(a));return b}}
function Month(c,b){this.year=c;this.month=b;this.nextMonth=function(){return new Month(c+Math.floor((b+1)/12),(b+1)%12)};this.prevMonth=function(){return new Month(c+Math.floor((b-1)/12),(b+11)%12)};this.getDateObject=function(a){return new Date(this.year,this.month,a)};this.getWeeks=function(){var a=this.getDateObject(1),b=this.nextMonth().getDateObject(0),c=[],a=new Week(a);for(c.push(a);!a.contains(b);)a=a.nextWeek(),c.push(a);return c}};

var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

/********************/
/***** calender *****/
/********************/

//current month
var currentMonth = new Month(2016, 2); //March 2016
 
// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth();
	updateCalendar();
}, false);

$("#prev_month_btn").click(function(event){
    currentMonth = currentMonth.prevMonth();
    updateCalendar();
});


// update share user
function updateShare(){
	var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "share_update_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
		var jsonData = JSON.parse(event.target.responseText);
		var signin_flag = jsonData[0].signin;
		var share_name_array = jsonData[0].share_name_array;
		if (signin_flag) {
			$("#show_event_user").html("<option>MySelf</option>");
			for (n in share_name_array) {
				if (n != 0){
					$("#show_event_user").append("<option>"+share_name_array[n]+"</option>");
				}
			}
		}
	});
	xmlHttp.send(null);
}


// updeate calender when month change
function updateCalendar(){
	var weeks = currentMonth.getWeeks();
    $("#calender_title").html(monthNames[currentMonth.month]+"&nbsp;"+currentMonth.year);
    $("#date_ind").html(" "); //print the title month
	
	var share_user_selected = $( "#show_event_user option:selected" ).text();
	var year = currentMonth.year;
	var month = currentMonth.month;
	var dataString = "year=" + encodeURIComponent(year) + "&month=" + encodeURIComponent(month) + "&show_user_name=" + encodeURIComponent(share_user_selected);
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_provider_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        var jsonData = JSON.parse(event.target.responseText);
		var signin_flag = jsonData[0].signin;	//sign in flag, true if signed in
        var start_flag = false;
		var valid_share_flag = jsonData[0].valid_share;
		if (signin_flag & !valid_share_flag) {
			alert("You are not shared with this user.");
			signin_flag = false;
		}
		
		var tag_selected = $( "#show_event_tag option:selected" ).text();
		for(var w in weeks){
			var days = weeks[w].getDates();
			for(var d in days){
				if (days[d].getDate() == 1){
					start_flag = !start_flag;
				}
				if (start_flag){
					$("#date_ind").append('<li class="ui-state-default" id="day'+days[d].getDate()+'">'+days[d].getDate()+'<br>');
				} else {
					$("#date_ind").append('<li class="ui-state-default" id="non-day'+days[d].getDate()+'">'+days[d].getDate()+'<br>');
				}
							
				if (signin_flag & start_flag) {	//if signed in, show the events in the DB
					for (e in jsonData){
                        var day_getDate = days[d].toISOString().slice(0, 10);
						//if (jsonData[e].date == $day_getDate && jsonData[e].owner == $("#show_event_user option:selected").text()){ //if day and user match
                        if (jsonData[e].date == day_getDate & (jsonData[e].tag == tag_selected | tag_selected == "All")){ //if day match
							$("#day"+days[d].getDate()).append('<p id="' + jsonData[e].eid + '" class="event_brf">' + jsonData[e].title + '</p>');
							var event_id = "#" + jsonData[e].eid;	// get the id of the event tag
							$(event_id).on('click', function(ev){	// add event listener to the event
								show_event_detail(ev);
							});
						}
					}
				}
				$("#date_ind").append('</li>');
			}
		}
    }, false);
    xmlHttp.send(dataString);
}

// ask events for certain month, tag and user from the server, return json type data
function event_request(year, month){
    var dataString = "year=" + encodeURIComponent(year) + "&month=" + encodeURIComponent(month);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_provider_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
    }, false);
    xmlHttp.send(dataString);
    
    return Data;
} 


// request event data by event's id
function event_request_id(event_id){
    var dataString = "event_id=" + encodeURIComponent(event_id);
    var Data = null;
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_provider_id_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
    }, false);
    xmlHttp.send(dataString);
    return Data;
}


// show even detail dialog
function show_event_detail(event){
    var eid = event.target.id;
    var dataString = "event_id=" + encodeURIComponent(eid);
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_provider_id_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        var jsonData = JSON.parse(event.target.responseText);
        $("#edit_event_title").val(jsonData.title);
        $("#edit_event_date").val(jsonData.date);
        $("#edit_event_tag").val(jsonData.tag);
        $("#edit_event_id").val(eid);
        var edit_event_dialog, edit_event_form;
        edit_event_dialog = $("#event_edit").dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: false,
            buttons: {
                "Edit event": edit_event_upload,
                "Delete event": delete_event_upload,
                Cancel: function() {
                    edit_event_dialog.dialog( "close" );
                }
            },
            close: function() {
                edit_event_form[ 0 ].reset();
                //allFields.removeClass( "ui-state-error" );
            }
        });

        edit_event_form = edit_event_dialog.find("form").on("submit", function(event){
            event.preventDefault();
            edit_event_upload();
        });
        
        edit_event_dialog.dialog("open");
        // event create, edit, delete button listener
        //$("#edit_event_btn").click(edit_event_upload);
        //$("#delete_event_btn").click(delete_event_upload);
    }, false);
    xmlHttp.send(dataString);
}

// event create uploader
function create_event_upload(){
    var title = $("#new_event_title").val();
    var date = $("#new_event_date").val();
	var tag = $( "#new_event_tag option:selected" ).text();
    var share = $("#new_event_share").val();
    var token = $("#token").val();
    
    var dataString = "title=" + encodeURIComponent(title) + "&date=" + encodeURIComponent(date) + "&tag=" + encodeURIComponent(tag) + "&share=" + encodeURIComponent(share) + "&token=" + encodeURIComponent(token);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_creater_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
			$("#new_event").dialog("close");
            updateCalendar();
			alert("Event create success.");  
		} else {
			alert("Event create fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}

// event edit uploader
function edit_event_upload(){
    var title = $("#edit_event_title").val();
    var date = $("#edit_event_date").val();
    var tag =$( "#edit_event_tag option:selected" ).text();
    var share = $("#edit_event_share").val();
    var eid = $("#edit_event_id").val();
    var token = $("#token").val();
    
    var dataString = "title=" + encodeURIComponent(title) + "&date=" + encodeURIComponent(date) + "&tag=" + encodeURIComponent(tag) + "&share=" + encodeURIComponent(share) + "&eid=" + encodeURIComponent(eid)+"&token=" + encodeURIComponent(token);
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_editer_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(){
        var Data = JSON.parse(event.target.responseText);
        if (Data.success) {
            $("#event_edit").dialog("close");
            updateCalendar();
			alert("Event edit success.");
        } else {
            alert("Event edit fail.");
        }
    }, false);
    xmlHttp.send(dataString);
}

// event delete uploader
function delete_event_upload(){
    var eid = $("#edit_event_id").val();
    var token = $("#token").val();
    var dataString = "eid=" + encodeURIComponent(eid)+"&token=" + encodeURIComponent(token);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_deleter_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
			$("#event_edit").dialog('close');
			updateCalendar();
			alert("Event delete success.");
		} else {
			alert("Event delete fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}

// calender share uploader
function share_calender_upload(){
	var share_name = $("#new_calender_share").val();
    var token = $("#token").val();
	var dataString = "share=" + encodeURIComponent(share_name)+"&token=" + encodeURIComponent(token);
	var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "calender_share_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
			alert("Calender share success.");
			$("#calender_share").dialog('close');
			updateCalendar();
			updateShare();
		} else {
			alert("Calender share fail."+ Data.message);
		}
    }, false);
    xmlHttp.send(dataString);
}

// initial calender when page is ready
$(document).ready(function(){
	updateShare();
	updateCalendar();
});

// tag selection listener
$("#show_event_tag").change(updateCalendar);

// share calender show listener
$("#show_event_user").change(updateCalendar);

// new calender share listener
var share_calender_dialog, share_calender_form;
share_calender_dialog = $("#calender_share").dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: false,
    buttons: {
        "Share": share_calender_upload,
        Cancel: function() {
            share_calender_dialog.dialog( "close" );
        }
    },
    close: function() {
        share_calender_form[ 0 ].reset();
        //allFields.removeClass( "ui-state-error" );
    }
});

share_calender_form = share_calender_dialog.find("form").on("submit", function(event){
    event.preventDefault();
    share_calender_upload();
});

$("#share_calender_btn").button().click(function(){
	share_calender_dialog.dialog("open");
});
/*****************************/


// new event dialog
var new_event_dialog, new_event_form;
new_event_dialog = $("#new_event").dialog({
    autoOpen: false,
    height: 300,
    width: 350,
    modal: false,
    buttons: {
        "Create an event": create_event_upload,
        Cancel: function() {
            new_event_dialog.dialog( "close" );
        }
    },
    close: function() {
        new_event_form[ 0 ].reset();
        //allFields.removeClass( "ui-state-error" );
    }
});

new_event_form = new_event_dialog.find("form").on("submit", function(event){
    event.preventDefault();
    create_event_upload();
});

$("#new_event_create_btn").button().click(function(){
	new_event_dialog.dialog("open");
});
/*****************************/
