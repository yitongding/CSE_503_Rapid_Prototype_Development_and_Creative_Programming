var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function signinCheck(){
	var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "signin_check_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
		var Data = JSON.parse(event.target.responseText);
		if (Data.success) {
			$('.login_dis')
				.attr('id',Data.username)
				.append('<h3>Hello, '+Data.username+'</h3>');
		}
		
		}, false);
    xmlHttp.send(null);
}

function requestDetail(){
	var vote_id = getUrlParameter('id');
	var dataString = "id=" + encodeURIComponent(vote_id);
	var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "detail_provider_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        $('#optionList').html('<tr><th>Option Name:</th><th>Vote Count:</th><th>Percentage:</th><th>Vote for:</th></tr>');
		$('.commentList').html("");
		
		var Data = JSON.parse(event.target.responseText);
		$(".title_dis").text("Vote Name: "+Data.name);
		$(".host_name_dis").text("Host Name: "+Data.host);
		
		if (Data.username == Data.host) {
			$('.end_btn_div').show();
		} else {
			$('.end_btn_div').hide();
		}
		
		for (opt in Data.options) {
			var option = Data.options[opt];
			if (Data.votecount != 0) {
				var percent = option.count/Data.votecount;
			} else {var percent = 0;}
			$('#optionList tr:last').after('<tr><th>'+option.name+'</th><th>'+option.count+'</th><th>'+percent.toFixed(2)+'</th><th><button class="vote_btn" id="'+option.name+'">Vote for it</button></th></tr>');
		}
		
		
		for (com in Data.comments) {
			var comment = Data.comments[com];
			if (comment.name == Data.username && Data.username != "anonymous") {
				$('.commentList').append('<li class="commentBody" id=""><h4 class="commentUser">'+comment.name+': <button class="com_del_btn" id="'+com+'">Delete</button></h4><p class="commentMessage">'+comment.message+'</p><br></li>');
			} else {
				$('.commentList').append('<li class="commentBody" id=""><h4 class="commentUser">'+comment.name+': </h4><p class="commentMessage">'+comment.message+'</p><br></li>');
			}
		}
		
		if(Data.endflag == true) {
			$('button').prop("disabled",true);
		}
    }, false);
    xmlHttp.send(dataString);
}
	
$(document).ready(function(){
	signinCheck();
	requestDetail();
	$('.share_link').val(window.location.href);
});



$("#optionList").on('click','tr > th > button',function(e){
	addVote(e.target.id);
});

function addVote(opt_name) {
	var vote_id = getUrlParameter('id');
	if ($('.anonymousCheck').is(":checked")) {
		var anonymous="true";
	} else {
		var anonymous="false";
	}
    
    var dataString = "id=" + encodeURIComponent(vote_id) + "&opt_name=" + encodeURIComponent(opt_name) + "&anonymous=" + encodeURIComponent(anonymous);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "addvote_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
            requestDetail();
			alert("vote success.");  
		} else {
			alert("vote fail.");
		}
    }, false);
    xmlHttp.send(dataString);
	
}

$('.newCommentSubmit').click(function(){
	addComment();
});

function addComment() {
	var vote_id = getUrlParameter('id');
	var message = $('.newCommentMessage').val();
    
    var dataString = "id=" + encodeURIComponent(vote_id) + "&message=" + encodeURIComponent(message) ;
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "addComment_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
            requestDetail();
			alert("comment success.");  
		} else {
			alert("comment fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}

$('.commentList').on('click','li > h4 > .com_del_btn',function(e){
	delComment(e.target.id);
});

function delComment(com_id){
	var vote_id = getUrlParameter('id');
	
	var dataString = "id=" + encodeURIComponent(vote_id) + "&com_id=" + encodeURIComponent(com_id) ;
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "delComment_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
            requestDetail();
			alert("comment delete success.");  
		} else {
			alert("comment delete fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}

$('.end_btn').on('click',function(e){
	endVote();
});

function endVote() {
	var vote_id = getUrlParameter('id');
	var dataString = "id=" + encodeURIComponent(vote_id);
    var Data = null;
    
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("POST", "endVote_ajax.php", true);
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load",function(event){
        Data = JSON.parse(event.target.responseText);
		if (Data.success) {
            requestDetail();
			alert("vote end success.");  
		} else {
			alert("vote end fail.");
		}
    }, false);
    xmlHttp.send(dataString);
}