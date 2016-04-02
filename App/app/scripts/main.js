var main= angular.module('secretRoom');
  main.controller('AppCtrl',['$scope','userData', '$location', function($scope,userData, $location){
	  $scope.user=userData.data();
      /*
      $scope.logout = function() {
	  FB.logout(function(response) {
		 $scope.user.status="";
		 $scope.user.name="Resume Game"
		 $scope.user.totalscore=0;
		 $scope.user.line1=0;
		 $scope.user.line2=0;
		 $scope.user.line3=0;
		 $scope.user.facebook="";
		 $scope.user.email=0;
		 $scope.user.totalscore=0;
		 $scope.user.currentleve=1;
		 $scope.user.lastlevelscore=0;
		 $location.path('/');
		 $scope.$apply();
	  });
	}

	$scope.FBlogin = function() {
	 FB.login(function(response) { }, {scope: 'public_profile,email,user_friends'});
 }
 */
	}])
  main.controller('MainCtrl', ['$scope','userData','AuthService','$http', '$location', function ($scope, userData, AuthService, $http, $location) {
    $scope.user=userData.data();
	$scope.login_register=false;
	$scope.start=function(){
        console.log($scope.user)
		if($scope.user.status!=''){
            console.log('herhe')
			$location.path('/questions')
		}
		else{$scope.login_register=!$scope.login_register}
	}
    /*$scope.open = function (state) {
		 $scope.user.status=state;
          var modalInstance = $modal.open({
          templateUrl: 'template/register_user.html',
          controller: 'ModalInstanceCtrl',
		  windowClass:'tiny',
          resolve: {
            user: function () {
              return $scope.user;
            }
          }
        });

        modalInstance.result.then(function (registed_user) {
          $scope.selected = selectedItem;
        }, function () {
         $location.path('/exam');
        });
      };
	$scope.hscore=function(){
		 var modalInstance = $modal.open({
          templateUrl: 'template/topscore.html',
          controller: 'TopscoreInstanceCtrl',
          resolve: {
            users: function () {
              return $scope.user;
            }
          }
        });

	}*/
  }]);
/*
  main.controller('TopscoreInstanceCtrl', function ($scope, $modalInstance, users, $http) {
	   $scope.user = users;
		$http({url:'server/topscore.php', method:'GET'}).
          success(function(responseData, status, headers, config) {
            $scope.topScore=responseData
          }),
          function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
          }
	  $scope.ok = function () {
        $modalInstance.close($scope.selected.item);
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
	 })

    // Please note that $modalInstance represents a modal window (instance) dependency.
    // It is not the same as the $modal service used above.

    main.controller('ModalInstanceCtrl', function ($scope, $modalInstance, user, AuthService, $http) {

      $scope.user = user;
      $scope.account_action='register';
	  //when the register button is clicked
	  $scope.regData={}
      $scope.doRegister = function() {
      	$scope.loading=true;
		$reg={fname:$scope.regData.fname, lname:$scope.regData.lname, email:$scope.regData.email, phone:$scope.regData.phone, password:$scope.regData.password}
		AuthService.register($reg).then(
		  function(authenticated){
			console.log(authenticated);
			$scope.setCurrentUser(authenticated, 'newp');
		  },
		  function(err) {	alert('Registration failed! Please try again!');
		  	$scope.user.name="";
				$scope.user.id="";
				$scope.user.status="";
		  }
	  );

      };

      //when the login button is clicked
      $scope.login = function(data){
          AuthService.login(data.email, data.password).then(
              function(authenticated) {
                	console.log(authenticated);
                	$scope.setCurrentUser(authenticated, 'oldp');
                	//$scope.data.id=authenticated.id;
              },
              function(err) {
                  alert('Login failed! Please verify your username and password!');
				  $scope.user.name="";
				  $scope.user.id="";
				  $scope.user.status="";
              }
          );
      };
	  $scope.setCurrentUser=function(data, stat){ }
      $scope.ok = function () {
        $modalInstance.close($scope.selected.item);
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
    });
    */
