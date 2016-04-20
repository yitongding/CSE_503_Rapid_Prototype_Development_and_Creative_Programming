'use strict';

// Declare app level module which depends on views, and components
var coffeesApp = angular.module('coffeesApp', [
  'ngRoute',
  'coffeesControllers'
]);

coffeesApp.config(['$routeProvider', function($routeProvider) {
	$routeProvider.
		when('/coffees', {
			templateUrl: 'coffees.html',
			controller: 'coffeesListCtrl'
		}).
		when('/reviews/:coffeeId', {
			templateUrl: 'reviews.html',
			controller: 'coffeesDetailCtrl'
		}).
		otherwise({redirectTo: '/coffees'});
}]);

var coffeesAry = [
       {'id': 1,
       'brand': "Average Andy's Coffee",
       'name': 'Regular Coffee',
       'country': 'Denmark',
       'reviews': [
               {'rating': 3,
               'comment': "Could've been crispier",
               'reviewer': "Chris P. Bacon"
               }
       ]
       },
       {'id': 2,
       'brand': "Jimmy's Coffee",
       'name': 'Mocha',
       'country': 'America',
       'reviews': [
       {'rating': 10,
       'comment': 'What everyone should drink in the morning!',
       'reviewer': 'Earl Lee Riser'
       },
       {'rating': 10,
       'comment': 'A genius of everything coffee',
       'reviewer': 'Bob'
       }
       ]
       },
       {'id': 3,
       'brand': "We Did Our Best",
       'name': 'Latte',
       'country': 'France',
       'reviews': [
       {'rating': 1,
       'comment': " a 'latte' yuckiness.",
       'reviewer': 'Tim Burr'
       },
       {'rating': 1,
       'comment': 'Is this even coffee?',
       'reviewer': 'Sue Flay'
       },
        {'rating': 1,
       'comment': 'The grossest thing I have ever had.',
       'reviewer': 'Myles Long'
       },
        {'rating': 5,
       'comment': 'I dont know what the fuss is about, i dont think its too bad!',
       'reviewer': 'Sara Bellum'
       }
       ]
       },
       {'id': 4,
       'brand': "Jimmy's Special Coffee",
       'name': 'Americano',
       'country': 'America',
       'reviews': [
       {'rating': 10,
       'comment': 'If I could rate it higher, I would!',
       'reviewer': 'Justin Case'
       },
       {'rating': 10,
       'comment': 'He does it again!',
       'reviewer': 'Eileen Dover'
       }
       ]
       }
];

var slidesAry = [
		{
			image:"https://pixabay.com/static/uploads/photo/2013/08/11/19/46/coffee-171653_960_720.jpg",
			text:['nice coffees'],
			id: 0
		},
		{
			image:"https://pixabay.com/static/uploads/photo/2014/12/11/02/56/coffee-563797_960_720.jpg",
			text:['nice coffees'],
			id: 1
		}
	];

var coffeesControllers = angular.module('coffeesControllers',['ui.bootstrap', 'smart-table']);

coffeesControllers.controller('coffeesListCtrl', function($scope) {
	$scope.coffees = coffeesAry;
	$scope.active= 1;
	$scope.slides = slidesAry;
});

coffeesControllers.controller('coffeesDetailCtrl', function($scope, $routeParams) {
	var id = $routeParams.coffeeId;
	for (var coff in coffeesAry) {
		if (coffeesAry[coff].id == id) {
			$scope.coffee = coffeesAry[coff];
		}
	}
    $scope.max = 10;
	$scope.slides = slidesAry;
});
