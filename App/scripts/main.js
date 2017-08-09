var main = angular.module('secretRoom');

main.controller('AppCtrl', ['$scope','userData', '$location', function($scope, userData, $location){
  $scope.user = userData.data();
}]);

main.controller('MainCtrl', [
  '$scope',
  '$rootScope',
  '$state',
  'userData',
  'AuthService',
  '$location',
  'LocalValue',
  '$window',
  '$q',
  function (  $scope,
              $rootScope,
              $state,
              userData,
              AuthService,
              $location,
              LocalValue,
              $window,
              $q ) {

    $scope.user = userData.data();
    $scope.user.status = 'new';
    $scope.login_register = false;

    $scope.start = function(){
        // console.log($scope.user);
        $location.path('/questions');
        if($scope.user.status !== ''){
            $location.path('/questions');
        }
        else{

            $scope.login_register = !$scope.login_register;
        }
    };

    $scope.login = function(){

        $scope.errorL = false;
        $scope.loading = true;

        AuthService.login($scope.user.email, $scope.user.password)
        .then(
          function(user) {
            return LocalValue.set('email', user.email);
            $scope.loading = false;
        })
        .then(
          function(email){
            return userData.savedData(email);
            $scope.loading = false;
        })
        .then(
          function(user){
            $scope.loading = false;
            // if(user.status = 'new'){
            //   userData.update('status', 'registered');
            // }
            console.log(user);
            $location.path('/account');
        })
        .catch( function(err) {
          $scope.loading = false;
          $scope.errorL = true;
          switch ( err.code ){
            case 'auth/invalid-email':
            case 'auth/wrong-password':
              $scope.errorM = 'Oops! Wrong username or password !';
              break;
            case 'auth/user-disabled':
              $scope.errorM = 'Yikes! Your account is inactive email us at support@the-exclusives.com !';
              break;
            case 'auth/user-not-found':
              $scope.errorM = 'Uhm! Invalid login details ';
              break;
            default:
              $scope.errorM = 'Unable to create account:' + '\n' + '\n' + err.message;
          }
        });

    };

    $scope.logout = function(){
    	AuthService.logout()
      .then(
        function(){
          return LocalValue.set('email', '');
        }
      )
      .then(
        function(){
          // console.log($scope.user);
          $rootScope.user = null;
          userData.default();
        	$location.path("/home");
        }
      )
    }

    $scope.signup = function(){
      Authservice.register($scope.user.email, $scope.user.password)
      .then(function(user){
        $scope.start();
      })
      .catch( function(Err){
        $scope.erroL = true;
        $scope.erroM = err;
      });
    }
}]);
