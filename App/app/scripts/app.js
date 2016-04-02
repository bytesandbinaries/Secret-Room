angular
  .module('secretRoom', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'appServices',
    'foundation',
    'foundation.dynamicRouting',
    'foundation.dynamicRouting.animations'
  ])

  .config(function ($routeProvider) {
    $routeProvider
    .when('/', {
      templateUrl: './App/app/templates/home.html',
     // controller: 'MainCtrl',
      //controllerAs: 'main',
    access: { isFree: true  }
    })
      .when('/intro', {
        templateUrl: './App/app/templates/intro.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
		access: { isFree: true  }
      })
      .when('/questions', {
        templateUrl: './App/app/templates/questions.html',
        controller: 'QuestionCtrl',
        controllerAs: 'question',
		access: { isFree: true }
      })
      .otherwise({
        redirectTo: '/',
		access: { isFree: true}
      });
  })
  .run(['$rootScope',  '$location', 'userData', function(root, $location, userData) {
      root.$on('$routeChangeSuccess', function(scope, currView, prevView) {
          var user= userData.data()
          try{if (!currView.access.isFree && (user.status=="")) { $location.path('/');  }}
          catch(e){}
      });
  }])
