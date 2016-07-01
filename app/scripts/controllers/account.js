angular.module('secretRoom')
.controller('AccountCtrl', ['$scope','$rootScope','$http','userData','AuthService', '$location', function ($scope, $rootScope, $http, userData, AuthService, $location) {
    $scope.user=userData.data();
    $scope.get_status=function(){
        $http({url:'../../server/get_status.php?userid='+$scope.user.userID, method:'GET'}). //online
        success(function(responseData, status, headers, config) {
            console.log(responseData);
            $scope.all_responses=responseData;
            $scope.total_que=responseData.length;
            $scope.user.lastquestionId=responseData[$scope.total_que-1].question_id;
            $scope.user.currentlevel=responseData[$scope.total_que-1].category_id
        }),
        function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
        }
    }
    $scope.loadquestions=function(){
        $location.path("/questions")
    }
}]);
