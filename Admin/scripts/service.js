var appServices=angular.module('appServices', ['ngResource']);
appServices.filter('posttime', function(){
  return function (input) {
    var n =moment.unix(input).fromNow();
    return n;
  }
});
appServices.service('appService', ['$q','$http','$location','$rootScope', function( $q, $http, $location, $rootScope) {
	var request_data=function(action, data){
		return $q(function(resolve, reject) {
      var url='server/get_allq.php'
      if(action!=''){url=url+'?action='+action}
      if(data!=''){url=url+'&data='+JSON.stringify(data)}
      $http.get(url).
        success(function(response) {
          console.log(response);
          resolve(response);
        },
        function(err) {reject('Login Failed.');}
        );
    });
	}
  return{
    request_data :function(action, data) {
      return request_data(action, data);
    },
  	register:function(regprams){
  		 register(regprams);
  	}
  }
}]);
