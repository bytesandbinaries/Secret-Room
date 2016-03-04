angular
  .module('Admin', [
    'ngResource',
    'ngRoute',
    'appServices'
  ])

  .config(function ($routeProvider) {
    $routeProvider
    .when('/', {
      templateUrl: 'templates/home.html',
      controller: 'MainCtrl',
      //controllerAs: 'main',
    access: { isFree: true  }
    })
    .when('/viewallQ', {
      templateUrl: 'templates/viewallQ.html',
      controller: 'viewallQCtrl',
      controllerAs: 'viewallQ',
      access: { isFree: true  }
    })
    .when('/editQ/:id', {
      templateUrl: 'templates/editq.html',
      controller: 'editQCtrl',
      controllerAs: 'editQ',
      access: { isFree: true  }
    })
    .when('/addnewq', {
      templateUrl: 'templates/addQ.html',
      controller: 'addnewqCtrl',
      controllerAs: 'addnewQ',
      access: { isFree: true  }
    })
      .when('/viewQ/:id', {
        templateUrl: 'templates/viewQ.html',
        controller: 'viewQCtrl',
        controllerAs: 'viewQ',
		      access: { isFree: true  }
      })
      .when('/match', {
        templateUrl: 'templates/match.html',
        controller: 'matchCtrl',
        controllerAs: 'match',
		      access: { isFree: true  }
      })
      .when('/vmatch', {
        templateUrl: 'templates/viewmatch.html',
        controller: 'viewmatchCtrl',
        controllerAs: 'vmatch',
		      access: { isFree: true  }
      })
      .when('/viewallc', {
        templateUrl: 'templates/viewC.html',
        controller: 'categoryCtrl',
        controllerAs: 'categoryView',
		    access: { isFree: true }
      })
      .when('/AddC', {
        templateUrl: 'templates/addC.html',
        controller: 'addcatCtrl',
        controllerAs: 'addCC',
		    access: { isFree: true }
      })
      .when('/editc/:cat', {
          templateUrl: 'templates/editc.html',
          controller: 'editCCtrl',
          controllerAs: 'editC',
  		      access: { isFree: true  }
        })
      .otherwise({
        redirectTo: '/',
		access: { isFree: true}
      });
  })
  .run(['$rootScope',  '$location', function(root, $location) {

      root.$on('$routeChangeSuccess', function(scope, currView, prevView) {
          try{if (!currView.access.isFree && (user.status=="")) { $location.path('/');  }}
          catch(e){}
      });
  }])
