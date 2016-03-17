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

// updeate calender when month change
function updateCalendar(){
	var weeks = currentMonth.getWeeks();
    $("#calender_title").html(monthNames[currentMonth.month]+"&nbsp;"+currentMonth.year);
    $("#date_ind").html(" "); //print the title month
    
    var signin_flag = signin_check(); // check the if the user have checked in
    
    if (signin_flag() ) {
        jsonData = event_request(currentMonth.year, currentMonth.month,  "#show_event_tag".val() ); //request event data from server
    }
    
	for(var w in weeks){
		var days = weeks[w].getDates();
		for(var d in days){
            $("#date_ind").append('<li class="ui-state-default">'+days[d].getDate()+'<br>'); //add a day
            if (signin_flag) {
                for (e in jsonData.events){
                    if (jsonData.events[e].date == days[d].getDate() && jsonData.events[e].owner == "#show_event_user".val() ){ //if day and user match
                        $("#date_ind").append(jsonData.events[e].time + '<a id="' + jsonData.events[e].eid + '" class="event_brf">' + jsonData.events[e].title + '</a>');
                    }
                }
            }
            $("#date_ind").append('</li>');
		}
	}
    
    $(".event_brf").click(show_event_detail(event)); //add event listener for each event link
}

// ask events for certain month, tag and user from the server, return json type data
function event_request(year, month, tag){
    var dataString = "year=" + encodeURIComponent(year) + "&month=" + encodeURIComponent(month) + "&tag=" + encodeURIComponent(tag);
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
    jsonData = event_request_id(eid);
    $("#edit_event_title").val(jsonData.event.title);
    $("#edit_event_date").val(jsonData.event.date);
    $("#edit_event_tag").val(jsonData.event.tag);
    $("#edit_event_id").val(eid);
    $("#event_edit").dialog();
}

// event create uploader
function create_event_upload(){
    var title = $("#new_event_title").val();
    var date = $("#new_event_date").val();
    var tag = $("new_event_tag").val();
    var share = $("#new_event_share").val();
    
    var dataString = "title=" + encodeURIComponent(title) + "&date=" + encodeURIComponent(date) + "&tag=" + encodeURIComponent(tag) + "&share=" + encodeURIComponent(share);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_creater_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
    }, false);
    xmlHttp.send(dataString);
    if (Data.success) {
        alert("Event create success.");
    } else {
        alert("Event create fail.");
    }
}

// event edit uploader
function edit_event_upload(){
    var title = $("#edit_event_title").val();
    var date = $("#edit_event_date").val();
    var tag = $("edit_event_tag").val();
    var share = $("#edit_event_share").val();
    var eid = $("#edit_event_id").val();
    
    var dataString = "title=" + encodeURIComponent(title) + "&date=" + encodeURIComponent(date) + "&tag=" + encodeURIComponent(tag) + "&share=" + encodeURIComponent(share) + "&eid=" + encodeURIComponent(eid);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_editer_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
    }, false);
    xmlHttp.send(dataString);
    if (Data.success) {
        alert("Event edit success.");
    } else {
        alert("Event edit fail.");
    }
}

// event delet uploader
function delete_event_upload(){
    var eid = $("#edit_event_id").val();
    var dataString = "eid=" + encodeURIComponent(eid);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "events_deleter_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
    }, false);
    xmlHttp.send(dataString);
    if (Data.success) {
        alert("Event delete success.");
    } else {
        alert("Event delete fail.");
    }
}

// initial calender when page is ready
$(document).ready(updateCalendar);

// tag selection listener
$("#show_event_tag").change(updateCalendar);

// new calender share listener
$("#share_calender_btn").click(function(event){
    $("#calender_share").dialog();
});

// event create, edit, delete button listener
$("#new_event_btn").click(create_event_upload);
$("#edit_event_btn").click(edit_event_upload);
$("#delete_event_btn").click(delete_event_upload);



// update calender when tag selection change 
/*
function showtag(){
    var tag_value = $("#show_event_tag").val();
    var jsondata = event_request();
    for (d in jsondata){
        var event = jsondata.event[d];
        if(event.tag == tag_value){
            showevent(event);
        }
    }
}
*/