var appServices=angular.module('appServices', ['ngResource']);
appServices.service('levelData', function(){
  var savedData =  {levelname:'', levelinstruction:'',  levetime:'',  lpassmark:'', linstruction:''}
  return{
     data:function() {   return savedData;	 },
  }
})
appServices.service('userData', ['$rootScope', function($rootScope){
  var savedData =  {name:'', id:0, email:'', userID:'', currentlevel:0, gender:'', lastquestionId:0, status:'new', prop_pict:'', password:''}

  return{
     data:function() {   return savedData; }
  }
}])

appServices.filter('posttime', function(){
  return function (input) {
    var n =moment.unix(input).fromNow();
    return n;
  }
});
appServices.service('AuthService', ['userData','$q','$http','USER_ROLES','$rootScope', function(userData, $q, $http, USER_ROLES, $rootScope) {
  var LOCAL_TOKEN_KEY = 'myAskToken';
  var user=userData.data();
  var username = '';
  var isAuthenticated = false;
  var role = '';
  var authToken={'token':'', 'id':''};
  function loadUserCredentials() {
    //    var token = window.localStorage.getItem(LOCAL_TOKEN_KEY);
        if (token) useCredentials(token);

    }

    function useCredentials(token, stat) {
       username = token.token.split('.')[0];
       var user_ro= token.token.split('.')[1];
       var user_id= token.id;
       isAuthenticated = true;
       authToken = token;
       // if (user_ro == 'admin') {
       //   role = USER_ROLES.admin
       // }
       // else{
       //   role = USER_ROLES.public
       // }
       // Set the token as header for your requests!
	   if(stat=='newp'){
		  user.name=token.token.split('.')[0];
		  user.id=token.id;
		  user.status='NewPlayer';

	  }
	  else{
		  user.name=token.token.split('.')[0];
		  user.id=token.userId;
		  user.status='ReturnPlayer';
		  user.totalscore=token.totalscore;
		  user.email=token.email;
		  user.currentlevel=token.level;

		 // user.lastLscore=token.lastlevelscore;
		  user.line1=token.line1;
		  user.line2=token.line2;
		  user.line3=token.line3;
	  }

       $http.defaults.headers.common['X-Auth-Token'] = token;
   }

    function destroyUserCredentials() {
       authToken = undefined;
       username = '';
       isAuthenticated = false;
       $http.defaults.headers.common['X-Auth-Token'] = undefined;
       window.localStorage.removeItem(LOCAL_TOKEN_KEY);
   }
   function userCred(token, stat){
	   window.localStorage.setItem(LOCAL_TOKEN_KEY, token);
      useCredentials(token, stat);
	  }
	var register= function(regprams){
		$http({url:'server/register_user.php?', method:'GET', params:regprams}).
              success(function(responseData, status, headers, config) {
                  data=responseData;
    			  loading=false;
                  userCred(data, 'newp')
              },
              function(err) {

              }
          );
	}
	var login=function(name, pw){
		return $q(function(resolve, reject) {
          if (name != '' && pw != '') {
            $http.get('../server/login.php?email='+name+'&pass='+pw).
            success(function(response) {
              var logResult=response
              if(logResult.errorM) {
                  reject(logResult.errorM);}
              else{
                  var details= response.details;
                  user.name=details.fname;
                  user.email=details.email;
                  user.userID=details.userId;
                  user.gender=details.gender;
                  user.joined=details.joined;
                  user.password="";
                  user.status=details.info
                //  userCred(response[0], 'oldp');
                  resolve(response.details);
              }
            },
            function(err) {reject('Login Failed.');}
            );

          } else  reject('Login Failed.');

        });
	}
    return{
        login:login
    }
//   return{
//       login :function(name, pw) {
//         login(name,pw)
//     },
//
//       logout : function() {
//         this.destroyUserCredentials();
//     },
// 	storeUserCredentials:function(token) {
//       userCred(token)
//     },
//
// 	register:function(regprams){
// 		 register(regprams)
// 	}
// }
}])
