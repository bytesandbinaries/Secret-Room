angular.module('Admin')
  .controller('MainCtrl', ['$scope','appService', function ($scope, appService) {
    appService.request_data('','').then(function(response){
      $scope.init_data=response;
    },
    function(error){
      console.log('error this '.error)
    });
  }])
  .controller('categoryCtrl', ['$scope','appService', function ($scope, appService) {
    appService.request_data('getallc','').then(function(response){
      $scope.categories=response;
      $scope.categories.shift();
    },
    function(error){
      console.log('error this '.error)
    });
    $scope.delete=function(id){
      appService.request_data('deletec',id).then(function(response){

      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('addcatCtrl', ['$scope','appService', function ($scope, appService) {
    $scope.cat={};
    $scope.addc=function(){
      appService.request_data('addc',$scope.cat).then(function(response){
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('editCCtrl', ['$scope','appService', '$routeParams', '$location', function ($scope, appService, $routeParams, $location) {
    $scope.cat=JSON.parse($routeParams.cat);
    $scope.editc=function(){
      appService.request_data('updatec',$scope.cat).then(function(response){
        $location.path('/viewallc')
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('viewQCtrl', ['$scope', '$routeParams', 'appService','$location',  function ($scope, $routeParams, appService, $location) {
    var id=$routeParams.id;
    appService.request_data('viewQ', id).then(function(response){
      $scope.question=response;
      if( $scope.question.question_data[4] == 'tb'){$scope.qtype= 'Textbox Fill-in';}
      else if( $scope.question.question_data[4] == 's'){$scope.qtype= 'Scale';}
      else if( $scope.question.question_data[4] == 'mp'){$scope.qtype= 'Multiple Response';}
      else if( $scope.question.question_data[4] == 'sm'){$scope.qtype ='Scale and Multiple Response';}
    },
    function(error){
      console.log('error this '.error);
    });
    $scope.delete=function(id){
      appService.request_data('deleteq',id).then(function(response){
        $location.path('/viewallQ');
      })
    }
  }])
  .controller('viewallQCtrl', ['$scope','appService', '$location', function ($scope, appService, $location) {
    appService.request_data('viewallQ','').then(function(response){
      $scope.categories=response;
    },
    function(error){
      console.log('error this '.error)
    });

  }])
  .controller('editQCtrl', ['$scope','appService', '$location','$routeParams', function ($scope, appService, $location, $routeParams) {
    var id=$routeParams.id;
    appService.request_data('viewQ', id).then(function(response){
      $scope.question=response;
      console.log($scope.question.question_data[2]);
      $scope.qtype=[{value:'', name:'Select A Question Type'},
        {value:'tb', name:'TextBox'},
        {value:'mp', name:'Multiple Response'},
        {value:'s', name:'Scale'},
        {value:'sm', name:'Scale And Multiple Response'}];
    },
    function(error){
      console.log('error this '.error);
    });
    appService.request_data('getallc','').then(function(response){
      $scope.categories=response;
    },
    function(error){
      console.log('error this '.error)
    });
    $scope.edit=function(question){
      appService.request_data('editQ',question.question_data).then(function(response){
        $location.path('/viewQ/'+id)
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('addnewqCtrl', ['$scope','appService', '$location', function ($scope, appService, $location ) {
    $scope.qdata={cat:'', qtype:''};
    $scope.qtype=[{value:'', name:'Select A Question Type'},
      {value:'tb', name:'TextBox'},
      {value:'mp', name:'Multiple Response'},
      {value:'s', name:'Scale'},
      {value:'sm', name:'Scale And Multiple Response'}];
    appService.request_data('getallc','').then(function(response){
      $scope.categories=response;
    },
    function(error){
      console.log('error this '.error)
    });
    $scope.addq=function(data){
      appService.request_data('addQ', data).then(function(response){
       $location.path('/viewQ/'+response)
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('matchCtrl', ['$scope','appService', '$location', function ($scope, appService, $location ) {
    $scope.f={cat:''}; $scope.s={cat:''}; $scope.m={cat:''};
    $scope.range=['0','1','2','3','4','5','6'];
    //$scope.mdata={fcat:'', fq_id:'',scat:'', sq_id:'', scale_id:'', scale_range:'', mtype:''};
    $scope.mtypev='';
    $scope.mtype=[{value:'', name:'Select A Matching Type'},{value:'p', name:'Matching Pair'},{value:'sp', name:'Scale Matching Pair'}];
    appService.request_data('getallc','').then(function(response){
      $scope.categories=response;
    },
    function(error){
      console.log('error this '.error)
    });
    $scope.getq=function(m){
      m.loading='waiting';
      m.q_id='';
      appService.request_data('getallqinc',m.cat).then(function(response){
        m.loading='return';
        m.questions=response;
      },
      function(error){
        console.log('error this '.error)
      });
    }
    $scope.matcher=function(){
      var mquery={fcat:$scope.f.cat, fq_id:$scope.f.q_id,scat:$scope.s.cat,
        sq_id:$scope.s.q_id, scale_cat:$scope.m.cat, scale_id:$scope.m.q_id, scale_range:$scope.m.range, mtype:$scope.mtypev};
      console.log(mquery);
      appService.request_data('maddQ', mquery).then(function(response){
        console.log(response);
      // $location.path('/viewQ/'+response)
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
  .controller('viewmatchCtrl', ['$scope','appService', '$location', function ($scope, appService, $location ) {
    appService.request_data('getallm','').then(function(response){
      $scope.matches=response;
    },
    function(error){
      console.log('error this '.error)
    });

    $scope.matcher=function(){
      var mquery={fcat:$scope.f.cat, fq_id:$scope.f.q_id,scat:$scope.s.cat,
        sq_id:$scope.s.q_id, scale_cat:$scope.m.cat, scale_id:$scope.m.q_id, scale_range:$scope.m.range, mtype:$scope.mtypev};
      console.log(mquery);
      appService.request_data('maddQ', mquery).then(function(response){
        console.log(response);
      // $location.path('/viewQ/'+response)
      },
      function(error){
        console.log('error this '.error)
      });
    }
  }])
