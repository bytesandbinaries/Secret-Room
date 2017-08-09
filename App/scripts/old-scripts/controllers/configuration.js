"use strict";

var cons= angular.module('secretRoom')

cons.constant('ENV', {
    name:'development',
    apiEndpoint:'http://dev.yoursite.com:10000/'
});
cons.constant('AUTH_EVENTS', {
  notAuthenticated: 'auth-not-authenticated',
  notAuthorized: 'auth-not-authorized',
  wrongPass:'wrong-password-username'
})

cons.constant('USER_ROLES', {
  admin: 'admin_role',
  public: 'public_role'
});
