var main= angular.module('secretRoom');
  main.controller('AppCtrl',['$scope','userData', '$location', function($scope,userData, $location){
	  $scope.user=userData.data();
	}])
  main.controller('MainCtrl', ['$scope','userData','AuthService','$http', '$location', function ($scope, userData, AuthService, $http, $location) {
    $scope.user=userData.data();
	$scope.login_register=false;
	$scope.start=function(){
        console.log($scope.user)
		if($scope.user.status!=''){
			$location.path('/questions')
		}
		else{$scope.login_register=!$scope.login_register}
	}

  }]);
