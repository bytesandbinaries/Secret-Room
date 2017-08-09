angular
  .module('secretRoom', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'appServices',
    'firebase',
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
		    access: { isFree: true },
        key: 'questions'
      })
      .when('/account', {
        templateUrl: 'views/account.html',
        controller: 'AccountCtrl',
        controllerAs: 'account',
		    access: { isFree: false },
        key: 'account',
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

  .run(['$rootScope',  '$location', '$window', 'userData', function(root, $location, $window, userData) {
      root.$on('$routeChangeSuccess', function(event, currRoute, prevRoute) {

          var user = userData.data();
          // $window.onbeforeunload = function(){
          //   // return 'about to exit';
          //   if( user.status == 'registered'){
          //     // return 'about to exit';
          //   }
          //
          // }

          try{
              if (currRoute.access? !currRoute.access.isFree && (user.status === "loggedOut"): null) {
                  $location.path('/');
              }
              if(currRoute.key == 'account' ){
                // Status.get_status();
                // root.userResponse = Status.userResponse();
                // console.log(root.userResponse);
              }

          }
          catch(e){
            console.log( 'error from app.js run callback ' + '\n' +e);
          }
      });
  }]);
