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

  .config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
    $routeProvider
    .when('/', {
      templateUrl: 'templates/home.html',
     // controller: 'MainCtrl',
      //controllerAs: 'main',
    access: { isFree: true  }
    })
      .when('/intro', {
        templateUrl: 'templates/intro.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
		access: { isFree: true  }
      })
      .when('/questions', {
        templateUrl: 'templates/questions.html',
        controller: 'QuestionCtrl',
        controllerAs: 'question',
		access: { isFree: true }
      })
      .otherwise({
        redirectTo: '/',
		access: { isFree: true}
      });

      $httpProvider.defaults.useXDomain = true;
      delete $httpProvider.defaults.headers.common['X-Requested-With'];
  }])

  .run(['$rootScope',  '$location', 'userData', function(root, $location, userData) {
      root.$on('$routeChangeSuccess', function(scope, currView, prevView) {
          var user= userData.data()
          try{if (!currView.access.isFree && (user.status=="")) { $location.path('/');  }}
          catch(e){}
      });
  }])
