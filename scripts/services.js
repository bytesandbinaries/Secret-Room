var appServices=angular.module('appServices', ['ngResource']);
appServices.service('levelData', function(){
  var savedData =  {levelname:'', levelinstruction:'',  levetime:'',  lpassmark:'', linstruction:''}
  return{
     data:function() {   return savedData;	 },
  }
})
appServices.service('userData', ['$rootScope','$location', function($rootScope,$location){
  var savedData =  {name:'', id:0, email:'', currentlevel:0, lastquestionId:0, status:'new', prop_pict:'', password:''}

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
appServices.service('AuthService', ['userData','$q','$http','USER_ROLES','$location','$rootScope', function(userData, $q, $http, USER_ROLES, $location, $rootScope) {
  var LOCAL_TOKEN_KEY = 'myAskToken';
  var user=userData.data();
  var username = '';
  var isAuthenticated = false;
  var role = '';
  var authToken={'token':'', 'id':''};
    var loadUserCredentials= function() {
    //    var token = window.localStorage.getItem(LOCAL_TOKEN_KEY);
        if (token) useCredentials(token);

    }

    var useCredentials=function(token, stat) {
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

    var destroyUserCredentials = function() {
       authToken = undefined;
       username = '';
       isAuthenticated = false;
       $http.defaults.headers.common['X-Auth-Token'] = undefined;
       window.localStorage.removeItem(LOCAL_TOKEN_KEY);
   }
   var userCred=function(token, stat){
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
	var friendsList=function(flist){
		$http({url:'server/get_friends.php?', method:'GET', params:flist}).
              success(function(responseData, status, headers, config) {user.friends=responseData; console.log(user.friends)},
              function(err) {}
          );
	}
	var login=function(name, pw){
		return $q(function(resolve, reject) {
          if (name != '' && pw != '') {
            $http.get('server/login.php?email='+name+'&pass='+pw).
            success(function(response) {
              var logResult=response
              if(logResult.errorM) {reject(logResult.errorM);}
              else{
				  console.log(response)
                  userCred(response[0], 'oldp');
                  resolve(response[0]);
              }
            },
            function(err) {reject('Login Failed.');}
            );

          } else  reject('Login Failed.');

        });
	}
  return{
      login :function(name, pw) {
        login(name,pw)
    },

      logout : function() {
        this.destroyUserCredentials();
    },
	storeUserCredentials:function(token) {
      userCred(token)
    },

	register:function(regprams){
		 register(regprams)
	}
    /*,
    watchLoginChange: function() {
  		FB.Event.subscribe('auth.authResponseChange', function(res) {
    		if (res.status === 'connected') {
      			FB.api('/me?fields=name,email, friends', function(res) {
					user.name=res.name;
					console.log(res);
					user.status='FBPlayer';
					user.prop_pict='http://graph.facebook.com/'+ res.id+'/picture';
					$reg={fname:res.name.split(' ')[0], lname:res.name.split(' ')[1], email:res.email, phone:'', password:'fblogin', facebook:res.id}
					var email =(res.email)? res.email:res.id;
					login(email, 'fblogin').then(  function(authenticated) {},
					  function(err) { console.log();  register($reg).then(  function(authenticated){}, function(err) {} );
					  }
					);
					if(res.friends.data.length>0){
						friends = [];
						for($j=0; $j<res.friends.data.length; $j++)	{
							friends.push(res.friends.data[$j]);
						}
						friendsList(friends).then( function(authenticated){
							console.log(authenticated)
						}, function(err){console.log('Error getting friends, try again')})
					}
					$location.path('/exam');
					$rootScope.$apply(function() {
					$rootScope.user = res;

					});
				});
			}
			else {}
  		});
	}*/
}
}])
