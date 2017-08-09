var appServices = angular.module('appServices', ['ngResource']);

appServices.service('LocalValue', ['$q', '$window', function($q, $window){

  var setLocalValue = function(stringKey, localValue){
    return $q( function(resolve, reject){
      try{
        $window.localStorage.setItem(stringKey, localValue);
      } catch (error){
        reject(error);
      }
      resolve(localValue);
    });
  }

  var getLocalValue = function( stringKey ){
    var value;
    return $q( function(resolve, reject){
      try{
        value = $window.localStorage.getItem(stringKey);
      } catch (error){
        reject(error);
      }
      if (value){
        resolve(value);
      }
    });
  }

  return {
    get: getLocalValue,
    set: setLocalValue
  };
}]);


appServices.service('userData', ['$rootScope', '$http', '$q', function($rootScope, $http, $q){

  var user = {
    name : '',
    id :  0,
    email : '',
    userID : '',
    currentlevel :  0,
    gender : '',
    lastquestionId :  0,
    status : 'new',
    prop_pict :  ''
  };

  var getDefaultUser = function(){
      user.name ='';
      user.id = 0;
      user.email ='';
      user.userID ='';
      user.currentlevel = 0;
      user.gender ='';
      user.lastquestionId = 0;
      user.status ='new';
      user.prop_pict = '';

      return user;
  };


  var update = function(key, value){
    user[key] = value;
  }



  var getUserProfile = function(email){

    return $http({
      // url: 'http://localhost/exclusiveserver/get_profile.php?email='+email,
      url: '../exclusiveserver/get_profile.php?email='+email,
      method: 'GET',
    });
    // userProfile is a promise with the folowing shape {details:{fname, email,userID,gender,joined,info}, errorM: ''}
	}

  function getUserStatus(userID){

      return $http({
        // url:'http://localhost/exclusiveserver/get_status.php?userid='+userID,
        url:'../exclusiveserver/get_status.php?userid='+userID,
        method:'GET'
      });

  }

  function getSavedUser(email){
    getUserProfile(email)
    .then(
      function(userResponse){
        var userDetails = userResponse.data.details;
        user.name = userDetails.fname;
        user.email = userDetails.email;
        user.userID = userDetails.userId;
        user.gender = userDetails.gender;
        user.joined = userDetails.joined;
        user.status = userDetails.status;
        var userID = userDetails.userId;
        user.id = userID || 0;
        // console.log(userDetails);
         return getUserStatus(userID);
       },
       function error(reject) {
           console.log(reject.data);
           message="An Error occured Please Check your internet connection and try again...";
       })
    .then(
      function(response){
          // console.log(response);
          if (response.data.length > 0){
            var responseData = response.data;
            user.all = responseData;
            user.total_que = responseData.length;
            // user.lastquestionId = parseInt(responseData[responseData.length-1].question_id);
            // user.currentlevel = parseInt(responseData[responseData.length-1].category_id);
            user.lastquestionId = parseInt(responseData[0].question_id);
            user.currentlevel = parseInt(responseData[0].category_id);

            if(user.lastquestionId > 55){
                user.continue = false;
            }
          }
        },
      function error(reject) {
          console.log(reject.data);
          message="An Error occured Please Check your internet connection and try again...";
      }
    );

    return $q(function(resolve, reject){
      if(user){
        resolve(user);
      } else {
        reject({message: 'unable to get user'});
      }
    });
  }

  return{
     data: function(){ return user},
     default: getDefaultUser,
     savedData: getSavedUser,
     update: update
  }
}]);

appServices.filter('posttime', function(){
  return function (input) {
    var n =moment.unix(input).fromNow();
    return n;
  }
});

appServices.service('AuthService', ['userData','$q','$http','USER_ROLES','$rootScope', '$firebaseAuth', function(userData, $q, $http, USER_ROLES, $rootScope, $firebaseAuth) {

  var authenticate = $firebaseAuth();

  var register = function( email, password ){

    if ( email != '' && password != '' ) {
      return authenticate.$createUserWithEmailAndPassword(email, password);
    }

  }

	var login = function( email, password ){

    if ( email != '' && password != '' ) {
      return authenticate.$signInWithEmailAndPassword(email, password);
    }

	}

  var logout = function (){
    return authenticate.$signOut();
	}

  return{
      login:    login,
      logout:   logout,
	    register: register,
  }
}]);
