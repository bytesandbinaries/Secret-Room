angular.module('secretRoom')
.controller('AccountCtrl', [
  '$scope',
  '$rootScope',
  '$http',
  'userData',
  'AuthService',
  'LocalValue',
  '$location',
  '$window',
  function (  $scope,
              $rootScope,
              $http,
              userData,
              AuthService,
              LocalValue,
              $location,
              $document,
              $window) {



    $scope.user = userData.data();

    $scope.continue = true;

    $scope.loadquestions = function(){
      // console.log($rootScope.userResponse);
        // $scope.user.resumeQuestions = true;
        $location.path("/questions");
    };
}]);
