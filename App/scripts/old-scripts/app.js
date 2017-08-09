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
      templateUrl: 'views/home.html',
      controller: 'AppCtrl',
      controllerAs: 'app',
      access: { isFree: true  }
    })
      .when('/intro', {
        templateUrl: 'views/intro.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
		access: { isFree: true  }
      })
      .when('/questions', {
        templateUrl: 'views/questions.html',
        controller: 'QuestionCtrl',
        controllerAs: 'question',
		access: { isFree: true }
      })
      .when('/account', {
        templateUrl: 'views/account.html',
        controller: 'AccountCtrl',
        controllerAs: 'account',
		access: { isFree: false }
      })
      .when('/terms', {
        templateUrl: 'views/terms.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
		access: { isFree: true }
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
		access: { isFree: true }
      })
      .when('/privacy', {
        templateUrl: 'views/privacy.html',
        controller: 'MainCtrl',
        controllerAs: 'main',
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
          var user = userData.data();
          try{
              if (!currView.access.isFree && (user.status === "new")) {
                  $location.path('/');
              }
          }
          catch(e){}
      });
  }]);
