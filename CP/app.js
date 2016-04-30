'use strict';

// Declare app level module which depends on views, and components
var voteApp = angular.module('voteApp', [
  'ngRoute',
  'voteControllers'
]);

voteApp.config(['$routeProvider', function($routeProvider) {
	$routeProvider.
		when('/list', {
			templateUrl: 'list.html',
			controller: 'voteListCtrl'
		}).
		when('/detail/:voteId', {
			templateUrl: 'detail.html',
			controller: 'voteDetailCtrl'
		}).
		otherwise({redirectTo: '/list'});
}]);


var voteControllers = angular.module('voteControllers',[]);

voteControllers.controller('voteListCtrl', function($scope) {

	$(".regesterSubmit").click(function(){
		userRegister();
	});
	
	function userRegister() {
		var username = $('.usernameInput').val();
		var password = $('.userPasswordInput').val();
       
		if (userRegister(username, password)){
			$('.loginDiv').hide();
			$('.logoutDiv').show();
			$('.usernameShow').text(username);
		}
    }
    
	$(".loginSubmit").click(function(){
		userLogin();
	});
	
	function userLogin() {
		var username = $('.usernameInput').val();
		var password = $('.userPasswordInput').val();
       
		if(userLogin(username, password)) {
			$('.loginDiv').hide();
			$('.logoutDiv').show();
			$('.usernameShow').text(username);	 
		}
    }
    
    function voteCreate(){
        var voteName = $('.nameCreate');
        var voteOptions = {
            option1 : $('.option1'),
            option2 : $('.option2'),
            option3 : $('.option3'),
            option4 : $('.option4'),
            option5 : $('.option5')
        };
    }
});

voteControllers.controller('voteDetailCtrl', function($scope, $routeParams) {
	var id = $routeParams.voteId;
	for (var coff in coffeesAry) {
		if (coffeesAry[coff].id == id) {
			$scope.coffee = coffeesAry[coff];
		}
	}
	$scope.max = 10;
	$scope.slides = slidesAry;
});




