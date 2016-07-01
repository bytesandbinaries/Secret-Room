var main= angular.module('secretRoom');
  main.controller('AppCtrl',['$scope','userData', '$location', function($scope,userData, $location){
	  $scope.user=userData.data();
	}])
  main.controller('MainCtrl', ['$scope','$rootScope','$state','userData','AuthService', '$location', function ($scope, $rootScope, $state, userData, AuthService, $location) {
    $scope.user=userData.data();
	$scope.login_register=false;
	$scope.start=function(){
        console.log($scope.user)
		if($scope.user.status!=''){
			$location.path('/questions')
		}
		else{$scope.login_register=!$scope.login_register}
	}
    $scope.login=function(){
        $scope.errorL=false;
        AuthService.login($scope.user.email, $scope.user.password).then(function(authenticated) {
                console.log($scope.user);
        	    $location.path("/account");
            },
            function(err) {
                $scope.errorL=true;
                $scope.errorM=err;
            }
        )
    }
    }]);
