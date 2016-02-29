angular.module('secretRoom')
  .controller('QuestionCtrl', ['$scope','userData','$http', '$injector', function ($scope, userData, $http, $injector) {
    $scope.user=userData.data();
    if($scope.user.lastquestionId==0){
        $scope.question_status='startNewcategory';
    }
    else{$scope.question_status='getQuestions';}
    $scope.currentlevel=$scope.user.currentlevel;
    $scope.get_category=function(){
        $http({url:'server/get_c.php?category='+$scope.currentlevel, method:'GET'}).
          success(function(responseData, status, headers, config) {
              $scope.cat_data=responseData;
              console.log( responseData);

              //etManager(true);
          }),
          function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
          }
    }
    $scope.getNumber = function(num) {    return new Array(num);   }
    $scope.next=function(){
        if($scope.currQ<($scope.ques_data.length -1)) {   $scope.currQ++;
            var qtype=$scope.ques_data[$scope.currQ].q_type
                if(qtype=='mp' || qtype=='s' || qtype=='sm'){
                    $scope.breakoptions($scope.ques_data[$scope.currQ])
                }
        }
        else{
            $scope.currentlevel++;
            $scope.question_status='startNewcategory';
            $scope.get_category();
        }
    }
    $scope.goback=function(){
        if($scope.currQ>0){
            $scope.currQ--;
        }
    }
    $scope.get_country=function(){
        $http.get("scripts/country.json").success(function(response) {$scope.selection = response;});
    }
    $scope.get_state=function(){
        $http.get("scripts/state_local.json").success(function(response) {$scope.selection = response;});
    }
    $scope.get_height=function(){
        $scope.selection=[{'name':'3-4 Feet', 'value':'3-4 feet'},{'name':'4-5 Feet', 'value':'4-5 feet'},{'name':'5-6 Feet', 'value':'5-6 feet'},{'name':'6-7 Feet', 'value':'6-7 feet'}]
    }
    $scope.get_youfrom=function(){
        console.log($scope.ques_data[$scope.currQ-1].resp)
        $scope.localgov= $scope.ques_data[$scope.currQ-1].resp[0].value.trim().split('|')
        $scope.selection=[]
        for($s=0; $s<$scope.localgov.length; $s++){
            eachloc={}
            eachloc.value=$scope.localgov[$s];
            eachloc.name=$scope.localgov[$s];
            $scope.selection.push(eachloc)
        }
        //$http.get("scripts/state_local.json").success(function(response) {$scope.selection = response;});
    }
    $scope.breakoptions = function(que) {
        if(que.q_type=='mp'){      var splitopt=que.q_responseOpt.trim().split('|');}
        else if(que.q_type=='sm'){ var splitopt=que.q_responseOpt.trim().split('|');  var splitScale=que.q_scale.trim().split('|');}
        else{var splitopt=que.q_scale.trim().split('|');}
        que.resp=[];
        for($s=0; $s<splitopt.length; $s++){
            eachresp={}
            eachresp.item=splitopt[$s];
            if(!(eachresp.item.indexOf('()')==-1)){
                console.log('here1');
                func=eachresp.item.slice(0, -2);
                console.log(func)
                angular.isFunction($scope[func])
                $scope[func]()
                //$scope.$eval(func);
                //if (typeof fn === "function") fn();

            }
            if(que.q_type=='sm'){
                eachresp.respScale=[]
                for($e=0; $e<splitScale.length; $e++){
                    eachscale={}
                    eachscale.item=splitScale[$e];
                    eachscale.value='';
                    eachresp.respScale.push(eachscale);
                }

            }
            else{eachresp.value='';}
            que.resp.push(eachresp)


        }
        console.log(que.resp);
    }
    $scope.get_questions=function(){
        $scope.currQ=0;
        $scope.question_status='getQuestions';
        $http({url:'server/get_q.php?category='+$scope.currentlevel, method:'GET'}).
          success(function(responseData, status, headers, config) {
              $scope.ques_data=responseData;
              console.log( $scope.ques_data);
              $scope.breakoptions($scope.ques_data[$scope.currQ])
              //etManager(true);
          }),
          function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
          }
     }
}])
