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
var currentMonth = new Month(2016, 2);
 
// Change the month when the "next" button is pressed
document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth();
	updateCalendar();
}, false);

$("#prev_month_btn").click(function(event){
    currentMonth = currentMonth.prevMonth();
    updateCalendar();
});


function updateCalendar(){
	var weeks = currentMonth.getWeeks();
    $("#calender_title").html(monthNames[currentMonth.month]+"&nbsp;"+currentMonth.year);
    $("#date_ind").html(" ");
	for(var w in weeks){
		var days = weeks[w].getDates();
		for(var d in days){
            $("#date_ind").append('<li class="ui-state-default">'+days[d].getDate()+'</li>');
		}
	}
}

function showtag(){
    var tag_value = $("#show_event_tag").val();
    var jsondata = getdata();
    for (d in jsondata){
        var event = jsondata.event[d];
        if(event.tag == tag_value){
            showevent(event);
        }
    }
}

$(document).ready(updateCalendar);

$("#show_event_tag").change(showtag);

$("#share_calender_btn").click(function(event){
    $("#calender_share").dialog();
});