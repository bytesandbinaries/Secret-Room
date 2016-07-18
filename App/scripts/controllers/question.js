angular.module('secretRoom')
  .controller('QuestionCtrl', ['$scope','userData','$http', '$injector', function ($scope, userData, $http, $injector) {
    $scope.user=userData.data();
    $scope.qresponse=[];
    //check if the the user is using the app for the first time.
    $scope.currentlevel=$scope.user.currentlevel;
    $scope.get_category=function(){
      // $http({url:'http://localhost:8888/secret-room/app/server/get_c.php', method:'GET'}).
        //$http({url:'http://localhost/secret-room/app/server/get_c.php', method:'GET'}).
        $http({url:'../../server/get_c.php', method:'GET'}). //online
        success(function(responseData, status, headers, config) {
            $scope.que_data=responseData;
            if($scope.user.lastquestionId==0){
                $scope.question_status='startNewcategory';
            }
            //get the user back to the last saved question.
            else{
                $scope.saved=true;
                //get the total question in the saved question category
                var total_questionInLevel=$scope.que_data[$scope.user.currentlevel-1].all_questions
                console.log(total_questionInLevel);
                //find the position of the last question
                for($v=0; $v<total_questionInLevel.length; $v++){

                    if(total_questionInLevel[$v].q_no==$scope.user.lastquestionId){
                        //check if there are more questions in this level
                        if(($v+1)==total_questionInLevel.length){
                            $scope.user.currentlevel--;
                            $scope.moveToNewLevel();
                        }
                        else{
                            $scope.currQ=$v+1;
                            $scope.ques_data=total_questionInLevel;
                            $scope.question_status='getQuestions';
                            $scope.moveToNewQuestion()
                        }
                        break;
                    }
                }
            }
        }),
        function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
        }
    }
    $scope.moveattback= function(){  if($scope.attnum!=0) $scope.attnum-- }


    $scope.moveattforward= function(queAttr){
        if($scope.attnum != (queAttr.length)-1){ $scope.attnum++;}
        else{$scope.next();}

        // $scope.attnum !==(queAttr.length)-1 ? $scope.attnumm++ : $scope.next();
    }


    $scope.range_select=function(ques, ind){ques.value=ind;	$scope.next();  }

    $scope.getNumber = function(num) {    return new Array(num);   }

    $scope.next=function(){
    	var move=false;
    	var r=$scope.ques_data[$scope.currQ];
    	$scope.q={id:r.q_no, cat_id:r.q_cat, type:r.q_type}
    	$scope.errorM="Oops!! you need to select an answer to move on";
    	if(r.q_type=='s'){
    		if((typeof r.value!='undefined') || r.value!="" ){
    			move=true;
    			$scope.q.value=r.value;
    		}
    	}
    	else{
    		for($h=0; $h<r.resp.length; $h++){
    			if(r.q_type !='sm'){
                    if($h==0 && (typeof r.resp!='undefined')){$scope.q.value=[]; }
    				if(r.value!=''){move=true; $scope.q.value=r.value;}
    				else if(r.resp[$h].value!=''){
                        move=true;

                        $scope.q.value.push({item:r.resp[$h].item, value:r.resp[$h].value}) }
    				//else{move=true; $scope.q.value=r.resp;}
    			}
    			if(r.q_type=='sm'){
    				if($h==0){ $scope.q.value=[];}
    				if(r.resp[$h].respScale.value==''){move=false;}
    				else{
	    				move=true;
	    				var rr=r.resp[$h];
	    				$scope.q.value.push({item:rr.item, value:rr.respScale.value});
    				}
    			}
    		}
    	}
    	if(r.q_no==6){
    			if($scope.ques_data[1].value==$scope.ques_data[2].value){
    			move=false;
    			$scope.errorM='Sorry!! you need to select a different gender';
    		}
    	}
        if(r.q_no==7 || r.q_no==10){
    			if($scope.q.value[0].value!=='NG'){
    			$scope.currQ++;
    		}
    	}
    	if(r.q_no==19){

    		if($scope.ques_data[14].value.indexOf('kids')==-1){ $scope.currQ++;}
    	}
    	if(r.q_no==1){$scope.user.name=r.value	}
    	if(move==true){
    		$scope.error=false;
    		$scope.qresponse.push($scope.q);
	        if($scope.currQ<($scope.ques_data.length -1)) {
                $scope.moveToNewQuestion();
	        }
	        else{
	            $scope.moveToNewLevel();
	        //    $scope.get_questions($scope.user.currentlevel);
	        }
        }
        else{
        	$scope.error=true;
        }
    }
    $scope.moveToNewQuestion=function(){
        $scope.currQ++;
        console.log($scope.ques_data[$scope.currQ]);
        var qtype=$scope.ques_data[$scope.currQ].q_type
            if(qtype=='mp' || qtype=='s' || qtype=='sm' || qtype=='tb'){
                $scope.breakoptions($scope.ques_data[$scope.currQ])
            }
    }
    $scope.moveToNewLevel=function(){
        $scope.user.currentlevel++;
        if($scope.user.currentlevel<$scope.que_data.length){
            $scope.question_status='startNewcategory';
        }
        else{
            $scope.saving_response();
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
    $scope.get_age=function(){
        $scope.selection=[{'name':'Select Age', 'value':''},{'name':'18-25 years', 'value':'18-25'},{'name':'26-30 years', 'value':'26-30'},{'name':'31-35 years', 'value':'31-35'},{'name':'36-40 years', 'value':'36-40'},
        {'name':'41-45 years', 'value':'41-45'},{'name':'46-50 years', 'value':'46-50'}, {'name':'Above 50 years', 'value':'Above 50'}
    ]
    }

    $scope.get_height=function(){
        $scope.selection=[{'name':'Select Height', 'value':''},{'name':'3-4 Feet', 'value':'3-4 feet'},{'name':'4-5 Feet', 'value':'4-5 feet'},{'name':'5-6 Feet', 'value':'5-6 feet'},{'name':'6-7 Feet', 'value':'6-7 feet'}]
    }
    $scope.get_youfrom=function(){
        $scope.localgov= $scope.ques_data[$scope.currQ-1].resp[0].value.trim().split('|')
        $scope.selection=[{name:'Select your Local Government', value:''}]
        for($s=0; $s<$scope.localgov.length; $s++){
            eachloc={}
            eachloc.value=$scope.localgov[$s];
            eachloc.name=$scope.localgov[$s];
            $scope.selection.push(eachloc)
        }
    }
    $scope.breakoptions = function(que) {
        if(que.q_type=='mp'){      var splitopt=que.q_responseOpt.trim().split('|');}
        else if(que.q_type=='sm'){ var splitopt=que.q_responseOpt.trim().split('|');  var splitScale=que.q_scale.trim().split('|');}
        else if(que.q_type=='tb'){var splitopt=$scope.getNumber(que.q_responseMax); console.log('the split is'+splitopt)}
        else{var splitopt=que.q_scale.trim().split('|');}
        que.resp=[];
        que.value='';
        for($s=0; $s<splitopt.length; $s++){
            eachresp={}
            eachresp.item=splitopt[$s].trim();
            // if response is a function call
            if(!(eachresp.item.indexOf('()')==-1)){
                func=eachresp.item.slice(0, -2);
                angular.isFunction($scope[func])
                $scope[func]()
            }
            if(que.q_type=='sm'){
                $scope.attnum=0;
                eachresp.respScale=[]
                for($e=0; $e<splitScale.length; $e++){
                    eachscale={}
                    eachscale.item=splitScale[$e].trim();
                    eachscale.value='';
                    eachresp.respScale.push(eachscale);
                }
		//if($s==(splitopt.length-2)){return;}
            }
            else{eachresp.value='';}
            que.resp.push(eachresp)
        }
        console.log(que.resp);
    }
    $scope.get_questions=function(level){
        if(level<$scope.que_data.length){
            $scope.currQ=0;
            $scope.question_status='getQuestions';
            $scope.ques_data=$scope.que_data[level].all_questions;
            $scope.breakoptions($scope.ques_data[$scope.currQ])
        }
        else{
            $scope.saving_response();
        }
     }
    $scope.saving_response=function(){
    	$scope.question_status='save'
    	if($scope.saved==true){ $scope.save_response();}
    }
    $scope.save_response=function(){
    	var datap={user:$scope.user, response:$scope.qresponse}
        $scope.status="Please wait..."
        $http({method:'Post', url:'../../server/add_response.php', data:datap, //online
        //$http({method:'Post', url:'http://localhost:8888/secret-room/app/server/add_response.php', data:datap,
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
            }}).
        success(function(responseData, status, headers, config) {
            if($scope.user.status=='new' && $scope.user.id==0){
                $scope.user.id=responseData;
                $scope.saved=true;
                $scope.user.status='registered';
            }
            else{
                $scope.saved=true;
            }
            $scope.qresponse=[];
        }),
        function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
        }
    }
    $scope.changefromsave=function(){     $scope.question_status='getQuestions';  }
    $scope.acceptTerms=function(){
        $http({method:'Post', url:'../../server/accept_terms.php', data:$scope.user.id, //online
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
            }}).
        success(function(responseData, status, headers, config) {
                if(responseData=='updated'){
                    $scope.user.status='registeredandAccepted';
                }
        }),
        function(err) {
            $scope.message="An Error occured Please Check your internet connection and try again..."
        }
    }
}])
