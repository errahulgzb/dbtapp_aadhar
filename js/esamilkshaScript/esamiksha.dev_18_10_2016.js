
var ALL_BS_URL="http://180.151.3.101/dbtliveserver/dbtsamiksha";
//================= local Action ===========================
//var ALL_BS_URL="http://localhost/dbt_live/dbtsamiksha";
var formAct="Yes";
//===================== variable
'use strict';
var getUdetailVal ,selectAttendee,selectAttendeeAll,assignWork,selectAssignWorkAll ,lastKey;
var  totlUserObj=0; // total Object Count
var   allUserDaHolder="Please Wait";
var  maiLerAttend={};
var  actOwnerAttend={};
var  dIdHolder={};
var  closedDHolder={};
var  decisionHolder={};
var  storeDecision={};
var rowCPoint=0;
var attendeCount=1;
//---------------------- view Attendee List Zone ------------------------------
var viewMinsList={};
var viewStateList={};
var viewCentralList={};
var viewAdditionalList={};
var viewNwAttendeeList={};



//---------------------- view Attendee List Zone ------------------------------
//========================== new Attendee List ===================================
var getSelectNewAct={curId:"ministrYlsItems",selectype:"Ministry"};


//========================== new Attendee List ===================================

 var   app=angular.module('myApp',['ngSanitize','ngMessages']).controller("esamikshaControler",['$compile','$scope', '$sce' ,'$http','giveServiceToAll',function($compile,$scope,$sce,$http,giveServiceToAll){
	

//============================= ########================ action Block =======================================	                     

	                     $scope.getName=giveServiceToAll.getTextName();
	                       var allFrmIds="No";
	                        $scope.getUdetailVal=[];

	                       $scope.getActVal=[];



	                        selectAttendee=[];
	                        selectAttendeeAll="No";
	                        assignWork=[];
	                        selectAssignWorkAll="No";
//=========================== initialise userData ============================
                        $scope.getUDetail=function(){


                                                  //$scope.getUdetailVal=giveServiceToAll.makerUserData();
                                                   //alert($scope.getUdetailVal);

                                                   //giveServiceToAll.makerUserData();



                                                   }
//============================  call the attendee to add attendee =============================
                   $scope.showTheAttendee=function(){
                                                         giveServiceToAll.modalHeaderTitle("Add Attendees ");
                                                          giveServiceToAll.modalBdyWriter("Please Wait..., Preparing the related Contents...");

                                                         $scope.getUdetailVal=giveServiceToAll.makerUserData();
                                                          //  alert(totlUserObj);

                                                           //console.log(JSON.stringify($scope.getUdetailVal['ud0']));


                   	                                    getDynicContent=giveServiceToAll.getUdetailForShow($scope.getUdetailVal,"mattendee",selectAttendee,selectAttendeeAll);
                   	                                   
                                                       //alert(allUserDaHolder);


                                                          getCompileCode= $compile(allUserDaHolder)($scope);

                                                        //alert( getCompileCode);
                                                          giveServiceToAll.modalHeaderTitle("Add Attendees  ");
                                                        // giveServiceToAll.modalBdyWriter(getCompileCode);

                                                      //var element1= angular.element(allUserDaHolder);

                                                       // alert(element1);

                                                       // var getRef=document.getElementById("modlBody");
                                                   
                                                        var getRef=$("#modlBody");

                                                            getRef.html(getCompileCode);

                                                        

                                                        

                                                         


                                                         // mdlHeader.html(getCompileCode);




                   	                                   //=================------- writer the content ------------------ ===============
                                             
                                                         // giveServiceToAll.modalBdyWriter(getDynicContent);


                                                  // console.log(JSON.stringify(getUdetail['ud0']));

                                             }//  show attendee for Add

//=================================== action data value ========================================
$scope.actionTheAttendee=function(){

                                giveServiceToAll.modalHeaderTitle("Add Attendees For Action");
                                 giveServiceToAll.modalBdyWriter("Please Wait..., Preparing the related Contents...");

                                $scope.getActVal=giveServiceToAll.makerUserData();;

                                  getDynicContent=giveServiceToAll.getActForShow($scope.getActVal);



                                                     


                                                          getCompileCode= $compile(allUserDaHolder)($scope);

                                                       
                                                        // giveServiceToAll.modalBdyWriter(getCompileCode);

                                                      //var element1= angular.element(allUserDaHolder);

                                                       // alert(element1);

                                                       // var getRef=document.getElementById("modlBody");
                                                   
                                                        var getRef=$("#modlBody");

                                                            getRef.html(getCompileCode);

                                                        

                                                        

                                                         


                                                         // mdlHeader.html(getCompileCode);











 }// end  alert.....










//========================================= action Data Value ===============================================================











//=========================== initialise user Data ================================



	                       $scope.idMaker=function(idData){

                                                          allFrmIds=giveServiceToAll.allFrmIds(idData);
  
                                                               
	                                                      };// end of the idMaker
                           $scope.callMeetinSub=function(formId){
                                          
                          	                        
                          	                              //================== meeting creator form Submit ======================
                          	                            if(formAct=="Yes")
                          	                            {
                                                              giveServiceToAll.submitCreatedFrmData(allFrmIds);


                          	                             }else
                          	                             {

                                                           giveServiceToAll.formAlertMess()


                          	                               }

                                                     
//
                                                };//========= callMeetinSub =================
                           $scope.callClear=function(formId){
                        	   
                        	                        cnfr=window.confirm("Do You Want To Clear All Fields ?");
                        	             if(cnfr){
                        	            	 
      
                        	                                  mkId=".form-control";
                        	                                mkRef=$(document).find(mkId);
                        	                                mkRef.val('');
                        	  //============================================== creal the ck editor ======================                              
                        	                                for( instance in CKEDITOR.instances ){
                        	                                       CKEDITOR.instances[instance].updateElement();
                        	                                       CKEDITOR.instances[instance].setData('');
                        	                                       }
                        	                             
                        	                                
                        	                                fName=document.getElementById("filename");
                        	                                fName.innerHTML="No File Chosen"
                        	                            
                        	                                
                        	                  	            	 
                                           	            	 
                        	             }
                 	                                
                        	//============================================== creal the ck editor ====================== 
                        	                               
                           	                                  giveServiceToAll.errorMessHide();


                                                  };// callForClear....   

//==================================== close the modal ================================================================
                  $scope.closeModal=function(){

                                             giveServiceToAll.closeModal();

                                         }// end of the closeModal()
//========================================= getchk(this) ===================================
$scope.getchk=function(refValId,value){

	                   //======================== all reference data will come here =============================

                            getDetailValue= $scope.getUdetailVal;



	                         idMker="#"+refValId;


                           var getChkRef=$(document).find(idMker);

                           if(getChkRef.prop("checked"))
                           	 {

                               giveServiceToAll.setAttendeeValue(getDetailValue,value,refValId);

     
                           	     }else
                           	    {

                               giveServiceToAll.unSetAttendeeValue(getDetailValue,value,refValId);

                           	  }
                           
                           var getAttRef=$(document).find("#attendShw");
                           var attendeeHld=$(document).find("#attendeeHld");
                         

                     getJsonVal=JSON.stringify( maiLerAttend);
                     if( getJsonVal.length>8)
                    	  {
                    	 attendeeHld.css("display","block");
                    	     getAttRef.html('');
                    	     attendeCount=1;
                              angular.forEach(maiLerAttend, function(value, key) {
                        	   
                        	    giveServiceToAll.shwAttendeeList(getDetailValue,key,attendeCount)
                        	   
                        	   
                                });
                    	 }//=========== remove the action Point ..
                       else
                    	   {
                    	     getAttRef.html('');
                    	     attendeeHld.css("display","none");
                    	   
                    	   
                    	   }



                  }// make check or uncheck ....................

//=========================== getActchk(==========================================================
$scope.getActchk=function(refValId,value){






                            getDetailValue= $scope.getActVal;



	                         idMker="#"+refValId;


                           var getChkRef=$(document).find(idMker);

                           if(getChkRef.prop("checked"))
                           	 {

                                 giveServiceToAll.setAttendeeValueAct(getDetailValue,value,refValId);

     
                           	     }else
                           	    {

                               giveServiceToAll.unSetAttendeeValueAct(getDetailValue,value,refValId);

                           	  }


	                        









}// end of th egetActchk( 
//=============================== date change ==============================
   $scope.dChange=function(){


                alert('some chage occurs');


      }// end of dateChange

//================= get the meeting  details ====================
$scope.viewCrItem=function(mId){
	
	
//=================== maker url and get Data =============================================
	$("#modalDSho").html("Please Wait ,Data is Processing !!");
	     var usrUrl=ALL_BS_URL+"?pos=getmdetail&hdata=hide&mid="+mId+"&bkclk=90";
	      // alert(usrUrl);
     
           giveServiceToAll.getMViewDetail(mId,usrUrl).then(function(resolve){
   //-----------==============  alert  fucntion Value ==================
        	  
        	   
        	   
  //-----------==============  alert  fucntion Value ==================---
        	   
        	   
        	   getCompileCode= $compile(resolve)($scope);
        	   $("#modalDSho").html(getCompileCode);
        	   
           },function(reject){
        	   
        	   alert(reject);
        	   
        	   
           });

        


    }// end of the viewCrItem(
//======================== add new Decision body =======================================================

$scope.addDecisionMker=function(){
	
	
	
	 getDecisionBdy=giveServiceToAll.addWorkDecisionBody();
	 getCompile=$compile(getDecisionBdy)($scope)
	
	 $("#hlderDecisionMkerPr").append(getCompile);
	 $("#decisionTtle").css("display","block");
	 
	// alert("all this is done SuccessFullY");
   }// add the decision bosy ...
//============================= remove the added body ===========================
$scope.removeDesBody=function(remId){
	      delete decisionHolder[remId];
	      mksId="#decisionInput"+remId;
	      getRef=$(document).find(mksId);
	      
	    
	      var sndId="#snd"+remId;
	      sndIdRef=$(document).find(sndId);
	      cntVal=sndIdRef.attr("cnt");
	      getRef.remove();
	      
	        if(rowCPoint> cntVal )
	        	{
	        
	        	 getInc=cntVal;	
	        	 getInc=parseInt(getInc);
	        	 valPut=cntVal;
	        	 valPut=parseInt(valPut);
	        	 while(valPut<=rowCPoint)
	        		 {
	        		
		        	 getInc++;
		        	 mkClss=".clsed"+getInc;
		        	 
		        	 rmvClass="clsed"+getInc;
		        	 newCls="clsed"+valPut;
		        	 clssRef=$(document).find(mkClss);
		        	 clssRef.html( valPut);
		        	 clssRef.attr("cnt",valPut);
		        	 clssRef.addClass(newCls);
		        	 
		        	 clssRef.removeClass(rmvClass);
		        	 
		        	 
	        		 
		        	 valPut++;
	        		 }
	        	
	        	
	        	
	        	 // alert("Lower Data Remove");
	        	
	        	
	        	}
	    	  
	    	 
	      
	      
	      
	      
	      if(rowCPoint>=1)
	    	  {
	    	     rowCPoint--;
	    	  }
	      
	 
/*/=============================================================	      
	  getObJectlength=JSON.stringify(decisionHolder);  
	  
	  
	   if(getObJectlength.length<10)
		   {
		   
		     //$("#decisionTtle").css("display","none");
		   
		   }
//======================================/*/     
	
	
	
	
    }// end of removeDesBody(
//=============================== action pointer ==================================
$scope.actionOnCrItem=function(mId){
	
	
	
	   var usrUrl=ALL_BS_URL+"?pos=getActionForm&hdata=hide&mid="+mId+"&bkclk=90";
	     
	   giveServiceToAll.getMViewDetail(mId,usrUrl).then(function(resolve){
    	   
		   getCompileCode= $compile(resolve)($scope);
    	   $("#modalDSho").html(getCompileCode);
    	   
       },function(reject){
    	    
    	    alert("Error In Data Processing ");
    	   
    	   
       });
	
	
	
 }// actionOnCrItem(end of th4 
//======================== closeMAct ===================================
$scope.closeMAct=function(mbdId){
	    var mkMId="#"+mbdId;
	  var bdyRef=$(document).find(mkMId);
	  bdyRef.html("Please Wait data is processed for further action !!! ")
	
	
}// closeMAct
//========================== close the action Button ===============================
$scope.callDecisionComment=function(refId){
	
	 giveServiceToAll.UpdateCommentOnDecision(refId);
	
	
}// end of the callDecisionComment(



//================== -------------------- new Attendee Action List ----------- ===================
$scope.mkNwSlectActive=function(selectAdd,selType){

                         getSelectNewAct={curId:selectAdd,selectype:selType};

                          giveServiceToAll.mkActNewAttendeeFrAdd();

                      //alert(JSON.stringify(getSelectNewAct));


 } // end of the 
//--------------------- submit the Form Action -----------------------------
$scope.attendeeAdCall=function(formId){

    giveServiceToAll.addNWAttendeeList(formId).then(function(resolve){
    	
    	console.log(JSON.stringify(resolve));
    	
    	if(resolve["status"]=="Success")
    	  {
  //================= compile and append the data =========================  		
    		
    		//alert(resolve["data"])
    		
        getCompileCode= $compile(resolve["data"])($scope);
        $("#newAttendeeL").append(getCompileCode);
        $('#addNwAttende')[0].reset();
        
        alert("New attendee is added in the list");
    		
    		
    		
    		
    	  }else if(resolve["status"]=="exist")
    	  {
    		  
    		  alert(resolve['mess']); 
    		  
    		  
    	  }else
    	  {
    		  
    		alert(resolve["mess"]);  
    		  
    		  
    	  }
    	
    	
    	
    },function(reject){
    	
    	alert(reject);
    	
    	
    });


}// end of the attendeeAdCall(

//================== new Attendee add Action =======================================
$scope.getAttendeechk=function(utype,userId,rowId){

  //------------------------ block Section ----------------------------------------------------

              mkChkedId="#"+rowId+"chked";
            chedRef=$(document).find(mkChkedId);
//================== ---------- row reference ------------------------
           mkRowId="#"+rowId+"tr";
           rowRef=$(document).find(mkRowId);
//================== ---------- row reference ------------------------




            if(chedRef.prop("checked"))
               {

 //===================== select Action  in New Attendee====================

                     giveServiceToAll.addNwAttendInList(utype,userId,rowId);
                  rowRef.css("background-color","rgba(220, 188, 73, 0.22)");
                
                 // background-color:rgba(220, 188, 73, 0.22)


                 }else
                 {

//===================== select Action  in New Attendee====================
                   giveServiceToAll.removeNwAttendInList(utype,userId,rowId);
                   rowRef.css("background-color","#fff");

                 }
	//------------------------ block Section ----------------------------------------------------
	  
	
	
        }//======== getAttendeechk=========================
//============= ----------------- getMinchk ------------------------- =========
$scope.getMinchk=function(utype,userId,rowId){


 //------------------------ block Section ----------------------------------------------------

              mkChkedId="#"+rowId+"chked";
            chedRef=$(document).find(mkChkedId);
//================== ---------- row reference ------------------------
           mkRowId="#"+rowId+"tr";
           rowRef=$(document).find(mkRowId);
//================== ---------- row reference ------------------------




            if(chedRef.prop("checked"))
               {

 //===================== select Action  in New Attendee====================
                  giveServiceToAll.addMinsInList(utype,userId,rowId);

                  rowRef.css("background-color","rgba(220, 188, 73, 0.22)");
                
                 // background-color:rgba(220, 188, 73, 0.22)


                 }else
                 {

//===================== select Action  in New Attendee====================
                    giveServiceToAll.removeMinsInList(utype,userId,rowId);
                   rowRef.css("background-color","#fff");

                 }
  //------------------------ block Section ----------------------------------------------------





 }//end value.. ministry...
 //====== ======== ----------- getStatechk----------- ====
 $scope.getStatechk=function(utype,userId,rowId){


//------------------------ block Section ----------------------------------------------------

              mkChkedId="#"+rowId+"chked";
            chedRef=$(document).find(mkChkedId);
//================== ---------- row reference ------------------------
           mkRowId="#"+rowId+"tr";
           rowRef=$(document).find(mkRowId);
//================== ---------- row reference ------------------------




            if(chedRef.prop("checked"))
               {

 //===================== select Action  in New Attendee====================
 
                  giveServiceToAll.addStateInList(utype,userId,rowId);

                  rowRef.css("background-color","rgba(220, 188, 73, 0.22)");
                
                 // background-color:rgba(220, 188, 73, 0.22)


                 }else
                 {

//===================== select Action  in New Attendee====================

                  giveServiceToAll.removeStateInList(utype,userId,rowId);
                   rowRef.css("background-color","#fff");

                 }
  //------------------------ block Section ----------------------------------------------------





 }// end of the getStatechk
//============ ------------------- getCentralchk -----------------------
$scope.getCentralchk=function(utype,userId,rowId){



//------------------------ block Section ----------------------------------------------------

              mkChkedId="#"+rowId+"chked";
            chedRef=$(document).find(mkChkedId);
//================== ---------- row reference ------------------------
           mkRowId="#"+rowId+"tr";
           rowRef=$(document).find(mkRowId);
//================== ---------- row reference ------------------------




            if(chedRef.prop("checked"))
               {

 //===================== select Action  in New Attendee====================
               giveServiceToAll.addCentralInList(utype,userId,rowId);

                  rowRef.css("background-color","rgba(220, 188, 73, 0.22)");
                
                 // background-color:rgba(220, 188, 73, 0.22)


                 }else
                 {

//===================== select Action  in New Attendee====================
                giveServiceToAll.removeCentralInList(utype,userId,rowId);
                   rowRef.css("background-color","#fff");

                 }
  //------------------------ block Section ----------------------------------------------------






}// end of the getCentralchk
//==================== additional Action Zone ===============================
$scope.getAdditionchk=function(utype,userId,rowId){


//------------------------ block Section ----------------------------------------------------

              mkChkedId="#"+rowId+"chked";
            chedRef=$(document).find(mkChkedId);
//================== ---------- row reference ------------------------
           mkRowId="#"+rowId+"tr";
           rowRef=$(document).find(mkRowId);
//================== ---------- row reference ------------------------




            if(chedRef.prop("checked"))
               {

 //===================== select Action  in New Attendee====================

               giveServiceToAll.addAdditionalInList(utype,userId,rowId);
                  rowRef.css("background-color","rgba(220, 188, 73, 0.22)");
                
                 // background-color:rgba(220, 188, 73, 0.22)


                 }else
                 {

//===================== select Action  in New Attendee====================
                   giveServiceToAll.removeAdditionalInList(utype,userId,rowId);
                   rowRef.css("background-color","#fff");

                 }
  //------------------------ block Section ----------------------------------------------------




}// end of the getAdditionchk








//================== -------------------- new Attendee Action List ----------- ===================

//============================ add new decision body ==================================================



//============================= ########================ action Block =======================================

                               }]);// controller Registration...  with ng..








 
//===================  MAKE THE FACTORY METHOD TO SERVER THE NEW sERVICE ====================== 
 app.factory("allFMethods",['$http','$q',function($http,$q){


	 
	            var factory={};


	              factory.allSubFrm=function(getData){
	            	        

	            	                  var target_url=getData.url;
	            	                   getData.filestatus="No";

	            	                       

	            	                      	


                              
                                  var fUploader=document.getElementById("importfile");

                                    
                                  var formData = new FormData();

                                //  console.log(fUploader.files[0].type);
                                     var fileValue=fUploader.value;
                                       fileValue=fileValue.trim();
                                      // alert( fileValue);// file coming url alert...

                                      if(fileValue.length>4)
                                      {

                                               formData.append('file', fUploader.files[0]);
                                                getData.filestatus="Yes";// Upload action Yes

                                       }// end of the file check


                                      var user_data=JSON.stringify(getData);

                                      formData.append("otherD",user_data);


                                    /*========================== */
	            	                           return  $http(
	            	                        		  
	            	                          
	            	                        		          {
	            	                        			       method: 'POST',
	            	                        			       url: target_url,
	            	                        			       data: formData

	            	                        			       ,
	            	                        			          headers: { 'Content-Type': undefined}// this for image Uploading...
	            	                        			        });
	                      
	                       		                    //================================= /*/

                              

	            	                 
	            	                  // /*========================== */
	            	                  //          return  $http(
	            	                        		  
	            	                          
	            	                  //       		          {
	            	                  //       			       method: 'POST',
	            	                  //       			       url: target_url,
	            	                  //       			       data: "getAllData=" + user_data,
	            	                  //       			         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	            	                  //       			        });
	                      
	                       		      //               //================================= /*/
	                       		   
	            	               
	            	
	            	        
	            	
	                                 };// end of the course form....


//===================================== initialse the value of the catched value =================================
	factory.onlyContentVal=function(getData){
		
		   var target_url=getData.url;
		   var user_data=JSON.stringify(getData);
		
		    return  $http(
          		     
  		          {
  			       method: 'GET',
  			       url: target_url,
  			        data: "allData="+user_data

  			       ,
  			          headers: { 'Content-Type': undefined}// this for image Uploading...
  			        });

		
		
	  }// onlyContent
	
	factory.onlyContentValpOST=function(getData){
		
		   var target_url=getData.url;
		   var user_data=JSON.stringify(getData);
		   var formData = new FormData();
		   formData.append("allData",user_data);
		    return  $http(
       		     
		          {
			       method: 'POST',
			       url: target_url,
			        data:  formData

			       ,
			          headers: { 'Content-Type': undefined}// this for image Uploading...
			        });

		
		
	  }// onlyContent

/////////////////////*/

//===================================== initialse the value of the catched value =================================
	 
	 
	 
	 
	 return factory;
   }]) ;// end of the factory methos..
 
 

 
 
//===================  MAKE THE FACTORY METHOD TO SERVER THE NEW sERVICE ====================== 
 
 //=================== creating the service for the use =======================
  app.service("giveServiceToAll",['allFMethods','$q',function(allFMethods,$q){

  	         // var deferred = $q.defer(); //----deferred action ... 

//==================== created form data  is submitted here ============================================
          this.submitCreatedFrmData=function(allFrmIds){
                                               var fValid="Ok";
          	                           var getFNumber=document.getElementById('fnumber');
          	                               getFNumberVal=getFNumber.value;
//================================================ validation box =====================================
                                              fValid=this.fileValidate(getFNumberVal,3);
                                              if(fValid!="Ok")
                                                 {
                                                   mess="Please Fill Required Fields, File No. should be more than 2 characters";
                                                   this.errorMess(mess);
                                                   alert( mess);
                                                    return false;
                                                 
                                                  }
                                               
                                            
//================================================ validation box =====================================
          	                           var getMDate=document.getElementById('dPicker');
          	                               getMDateVal=getMDate.value;
//================================================ validation box =====================================
                                              fValid=this.fileDvalid(getMDateVal);
                                              if(getMDateVal.length<4)
                                                 {

                                                   mess="Please Fill Required Fields, Meeting Date is required.";
                                                   this.errorMess(mess);
                                                   alert( mess);
                                                    //this.errorMess();
                                                    return false;
                                                 
                                                  }
                                               
                                            
//================================================ validation box =====================================

          	                            var getchair=document.getElementById('selectCr');
          	                                getchairVal=getchair.value;
                                            getchairVal=getchairVal.trim();
                                            if(getchairVal=="No")
                                            {

                                              mess="Please Select Chair Person.";
                                                   this.errorMess(mess);
                                                    alert( mess);
                                                    //this.errorMess();
                                                    return false;


                                             }
   //////==================== ----------------- get the chair person  if ----------
                                        optionId="#selectCr option:selected";
                                        selectChairRef=$(document).find(optionId);
                                        getChairId=selectChairRef.attr('chairid');


                                   // alert(getChairId);
///========================== meeting Type ============================================
                            meetingTypeRef=$(document).find("#meetingType");
                            meetingTypeVal=meetingTypeRef.val();
                            meetingTypeVal=meetingTypeVal.trim();

                               if(meetingTypeVal=="No")
                               {


                                   mess="Please select meeting type";
                                                   this.errorMess(mess);
                                                    alert( mess);
                                                    //this.errorMess();
                                                    return false;


                               }






///========================== meeting Type ============================================




          	             //----------------- CKEDITOR VALUE  FOR  MESSAGE -----------------------------
          	                         //  alert(CKEDITOR.instances.editor1.getData())

                                          getMeetDescript=CKEDITOR.instances.editor1.getData();

          	                          //============= NEED credit JS ..ADD THE EDITOR HERE ============================================

          	                         // getMeetDescript=document.getElementById("editor1").innerHTML;//CKEDITOR.instances.editor1.getData();

//====================================== get the added attendee list ====================================
                                        getAttendeeList=maiLerAttend;
                                        //console.log(JSON.stringify(getAttendeeList));
                                        getActAttendee=actOwnerAttend;

//===================================== valid the action date========================================
                                       fValid=this.filedCheckCloseD();
                                              if(fValid!="Ok")
                                                 {

                                                    this.errorMess("Error");
                                                     return false;
                                                 
                                                    }

 //================================================= hide the message ======================================
                                                 this.errorMessHide();
//================================================= hide the message ======================================                                             

//============================= Add Ministry Decision Portion ====================================

                             //storeDecision={};
                             fValid=this.validateDecisionPoint();
                      if(fValid!="Ok")  
                           {
                                          mess="Please Fill Action Point ,Action Point should be more than 5 characters .";
                                          mess+=" and Please Select the Ministry."
                                                   this.errorMess(mess);
                                                   alert( mess);

                                       // this.errorMess("Decision Description and Ministry are required Fields !!! ");
                                        return false;



                           }      

   /*/=================================================                     
     alert("we are going further action !!!");
    console.log(JSON.stringify(decisionHolder)) ;
    console.log(JSON.stringify(storeDecision)) 
    return false ;
  //================= BOX FOR ACTION aDD ++++++++++++/*/

//============================= Add Ministry Decision Portion ====================================



//----------------- CKEDITOR VALUE  FOR  MESSAGE -----------------------------

                                            var getmom=document.getElementById('momcr');
          	                                  getmomVal=getmom.value;
          	                                  var urlVal=ALL_BS_URL+"/mcreator";
                        /*=========== attendee List ===============

var viewMinsList={};
var viewStateList={};
var viewCentralList={};
var viewAdditionalList={};
var viewNwAttendeeList={};



                        ================attende List ================*/
                         newAttendeeLst={"ministry":viewMinsList,"state":viewStateList,"central":viewCentralList,"additional":viewAdditionalList,"newAttendee":viewNwAttendeeList};



          	                 // allDataValue=getFNumberVal+"==="+ getMDateVal+"==="+getchairVal+"===="+ getMeetDescript +"===="+getmomVal;
          	                     var  allDataValues={"url":urlVal,"fNumber":getFNumberVal,"mData":getMDateVal,"chair":getchairVal,"chairId":getChairId,"mType":meetingTypeVal,"mess":getMeetDescript,"mom":getmomVal,"getAttendee":getAttendeeList,"getActList":actOwnerAttend,"assingWorkList":closedDHolder,"decisionPoints":storeDecision,"nwAttendee":newAttendeeLst};
          	                
          	                    // console.log(JSON.stringify(allDataValues));
                               // return false;
          	                var getCall=this;
                           //alert("make the form action !!");
              //alert(allDataValues);
                             this.formWaiterLoad();
          	                //==============        
                                    allFMethods.allSubFrm(allDataValues).success(function(reData){
/*/======================================================================================

                                     console.log(reData);
                                    	 alert(reData);// alert all return value..

//====================================================/*/

                                    	          if(reData['status']!=undefined)
                                    	        	   {
                                    	        	  
                                    	        	      if(reData['status']=="Success")
                                    	        	    	  {
                                    	        	    	  alert(" Meeting is created Successfully");
                                    	        	    	   getReUrl=reData['data']['url'];
                                    	        	    	    
                                    	        	    	  
                                    	        	    	   location.assign(getReUrl);
                                    	        	    	  
                                    	        	    	  
                                    	        	    	  
                                    	        	    	  }else
                                    	        	    	  {
                                    	        	    		  
                                    	        	    		  
                                    	        	    		  getCall.errorMess(reData['mess']);
                             	                	               getCall.reopenFrmAct();   
                                    	        	    		  
                                    	        	    	  }
                                    	        	  
                                    	        	  
                                    	        	  
                                    	        	   }else
                                    	        	   {
                                    	        		   
                                    	        		   
                                    	        		   getCall.errorMess(" Error 505!");
                      	                	               getCall.reopenFrmAct(); 
                                    	        		   
                                    	        		   
                                    	        	   }

                                                     

                                    	             
        	        	                   


        	        	                      //  getCall.errorMess("DAta is Submetted For Form Creation !!!");
        	    	     
        	        	                     // deferred.resolve(data);
        	    	   
        	                            }).error(function(error){
        	    	   
        	                	

        	                	            getCall.errorMess("Error 505!");
        	                	             getCall.reopenFrmAct();
        	    	   
        	    	   
        	    	   
        	                         });;

                               // alert(allDataValues)//===============/*/;


           };// created Form Data 

//==================== validateDecisionPoint() =======================================
this.validateDecisionPoint=function(){
//===================== this function Validate the decision Point and full the data====
 re_data="Ok";
 chkPoint="no";
 var keepGoing = true;

                    storeDecision={};
                   
              decisionPointId=decisionHolder;
             countPOint=JSON.stringify(decisionPointId);
           if(countPOint.length>10)
                {

//======================== fill the data in storeDecision====================
            angular.forEach(decisionPointId,function(value,keyId)
                 {

                  if(keepGoing)
                   {
                    getId=keyId;

                    
                     desp="#desp"+getId;// required
                    
 //===================== action Point ===========================                   
                      despRef=$(document).find(desp);
                      despRefVal=despRef.val();
                      despRefVal=despRefVal.trim();
                      //alert(despRefVal.length);
                     if(despRefVal.length<5)
                          {
                              //keepGoing=false 
                               re_data="Not Action Des";




                     
                             
                            }

 //===================== action Point =========================== 

                    SelId="#sel"+getId;// required

                    //===================== action Point ===========================                   
                       SelIdRef=$(document).find(SelId);
                       SelIdRefVal=SelIdRef.val();
                      SelIdRefVal=SelIdRefVal.trim();
                     
                     if(SelIdRefVal=="No")
                          {
                              // keepGoing=false 
                               re_data="Not Action Mini";




                     
                             
                             }

 //===================== action Point =========================== 




                  
                       dDate="#ddate"+getId;// need Data Value
//===================== action Point ===========================                   
                         dDateRef=$(document).find(dDate);
                         dDateRefVal=dDateRef.val();
                          dDateRefVal=dDateRefVal.trim();
                           if(SelIdRefVal!="No")
                           {

                                storeDecision[keyId]="mins$$"+SelIdRefVal+"@$-$@des$$"+despRefVal+"@$-$@cdate$$"+dDateRefVal;

                           }

                          
                    
                    
                          
 //===================== action Point =========================== 








                          }// keep going False
                  
                     


                 })// end of the angular angular Forea






             
  //alert(JSON.stringify(storeDecision));








//=============== make decision Valu e=========================

             


                    chkPoint="Yes";// enable final data check



                   }// end of if 



//================== unable to write data in StoreHolder ==================
             if(chkPoint=="Yes")
              {

                     //alert(chkPoint);
                  storeDecisionStr=JSON.stringify(storeDecision);

                  storeDecisionCount=storeDecisionStr.length;
               

               if( storeDecisionCount<10)
                       {


                          re_data="Unable Add The Data ";
 
                       }// inner If 

            }// check Action

//================== unable to write data in StoreHolder ==================
// console.log(JSON.stringify(storeDecision));
//console.log(JSON.stringify(decisionHolder));

  //alert(JSON.stringify(storeDecision));

 return re_data;

}// e




//============================= validateDecisionPoint() =====================================

//==================== created form data  is submitted here ============================================







  	            this.getTextName=function(){




                      return "Name From the service Action ...";

  	              };// get Name value ..

  	              this.allFrmIds=function(idData){

                                 getIdArr=idData.split(":");

                                    return getIdArr;

  	                          };// get the Name of the 

	//------------------------------- submit the meeting creator Form ---------------------------------

	                  this.meetingCreator=function(allFrmIds){


                                              // this.stringDchker(allFrmIds[2],4);

                                              this.errorMess("Hello Value ..");


	                                   };// end of the meetingCreator(allFrmIds)

  //###################------------- string checker ------------------- #####################

                    this.stringDchker=function(vlu,len){


                                     // alert(len);


                               }// end of the    

         this.errorMess=function(message){


                             var getErrorBox=document.getElementsByClassName("default-samiksha-mess");
                              var errorBox1=getErrorBox[0];
                                var errorBox2=getErrorBox[1];

                                   errorBox1.style.display="block";
                             
                                   errorBox1.innerHTML=message;
                                 //---------------- error box bottom------------------------
                                    errorBox2.style.display="block";
                             
                                   errorBox2.innerHTML=message;


           };// end of th e


         this.errorMessHide=function(){
                            var getErrorBox=document.getElementsByClassName("default-samiksha-mess");
                              var errorBox1=getErrorBox[0];
                                var errorBox2=getErrorBox[1];

                                   errorBox1.style.display="none";
                             
                                  
                                 //---------------- error box bottom------------------------
                                    errorBox2.style.display="none";
                             
                                  



           };// end of th e
  this.formWaiterLoad=function(){


                   formAct="No";
                    var getSubButtOnId=document.getElementById("SubmitCr");
                         getSubButtOnId.innerHTML="<img src='/dbt_live/images/loaderImg/btn.gif' width='20'/>Please Wait..";

                      //alert("block the Form Submit");





            };

       this.reopenFrmAct=function(){

       	                     formAct="Yes";

                          var getSubButtOnId=document.getElementById("SubmitCr");
                            getSubButtOnId.innerHTML="Submit";







             };
       this.formAlertMess=function(){



                  alert("Please Wait Process is On !!!");






          };


 //###################------------- string checker ------------------- ##################### 
 //----------------------------------- user Data will be made Here -------------------------
   this.makerUserData=function(){



   	                 var getArrCount=0;


                   var UholderRef=document.getElementById("udeltHolder");
                   Uholder=UholderRef.value;
                   UJsonObj=JSON.parse(Uholder);
                  // uDetail=UJsonObj.uData;
                  // uDetail=JSON.stringify(uDetail);
                     var angVal=[];
                     
                      angular.forEach(UJsonObj,function(obj,key){

                             if(obj['uData'] != "No")
                             {
                                 lastKey+="_"+key;
                             	   var mkKey="ud"+key;
                             	  // angVal["'"+mkKey+"'"]=obj['uData'];
                             	 //  angVal.push(mkKey+":"+obj['uData']);

                             	 angVal[mkKey]=obj['uData'];

                                // console.log(lastKey);

                            //  angVal.push("ud"+key+":"+obj['uData']);

                               // console.log(JSON.stringify( angVal[mkKey]));
                               }

                               getArrCount++;


                             })// 

                       totlUserObj= getArrCount;

                       // alert(JSON.stringify(angVal['ud0']));

                       //console.log(JSON.stringify(angVal['ud0']));


                       return angVal;

   }// end of the makerUserData()



//------------------------------  submit the meeting creator --------------------------------------- 


//======================= -------------------- attendee maker ---------------------------- =============
    this.getUdetailForShow=function(getUdetail,mattendee,selectAttendee,SelectAttendeeAll) {

    	               //alert(getUdetailVal);

    	               // console.log(JSON.stringify(getUdetail['ud0'][0]));

 //============================= make the form of data ========================================
                         this.modalHeaderTitle("Add Attendees");
                         this.modalBdyWriter("Please Wait..., Preparing the related Contents....");
                         var makFunName=mattendee+"chker";
                         var tableData="<table class='table table-order'>";
                         //alert(getUdetail.length);
                         var getTotalUser=totlUserObj;
                         var stCount=0;
                         var coubntDwn=1;
                         var condition=Object.keys(maiLerAttend).length;
                         console.log(JSON.stringify(maiLerAttend));
                         	var rowColor="";
                         	 	var checkValue=""
                               
              
                         tableData+="<tr><th>SNo.</th><th>Ministry</th><th>Complete Name</th><th>Email Id </th><th>Contact Number </th><th>Select</th></tr>";
                          var dtaTdHlder="";
                         while(stCount<=getTotalUser)
                         	 {

                         	 
                         	 	 mkKey="ud"+stCount;

                         	 	 getCurrObject=getUdetail[mkKey];

                         	 	 if(condition>0)
                         	 	 	 {
                                         if(maiLerAttend[mkKey]!=undefined)
                                         	  {
                                                

                                                     rowColor="style='background-color:rgba(220, 188, 73, 0.22);'";
                                                     checkValue="checked='checked'";
 

                                                       //console.log(rowColor);


                                         	   }else
                                         	   {


                                                rowColor="";
                                                  checkValue="";


                                         	   }



                         	 	 	 }

                         	 	 if(getCurrObject!=undefined)
                         	 	 {

                                  	 indiObj= getCurrObject[0];

                         	 	      //console.log(JSON.stringify( indiObj));

                                          chkId="chk"+Math.random();
                                           chkId= chkId.replace(".","");
                                           rwId="rw"+chkId;



                         	 	
                         	 	   	 

                                    
                                          trMker="<tr id='"+rwId+"' "+rowColor+">";
                                          sn=coubntDwn;
                                           trMker+="<td>"+sn+"</td>";
                                           trMker+="<td>"+ indiObj.ministry+"</td>";
                                             trMker+="<td>"+ indiObj.firstname+"</td>";
                                             trMker+="<td>"+ indiObj.email+"</td>";
                                             trMker+="<td>"+ indiObj.mobile+"</td>";
                                             trMker+="<td><input type='checkbox'  ng-click='getchk(\""+chkId+"\",\""+mkKey+"\")' id=\""+chkId+"\"  "+checkValue+"/></td>";








                                  

                                          trMker+="</tr>";

                                           dtaTdHlder+=trMker;
                                             coubntDwn++;


                         	     	}// end if ///

                              
                           


                                 

                                  stCount++;

                         	    } // end of the while ////////=*/

                               tableData+=dtaTdHlder;

                       


                            tableData+="</table>";

                            allUserDaHolder= tableData;

                            // this.modalHeaderTitle("Add Attendees For Mail");
                            // this.modalBdyWriter(tableData);




                   
                        return tableData;
//=============================================================================================


                   } // end of the  show   

//================================== getActForShow( ======================================
this.getActForShow=function(actValue){

	           getUdetail=actValue;



	     var tableData="<table class='table table-order'>";
                         //alert(getUdetail.length);
                         var getTotalUser=totlUserObj;
                         var stCount=0;
                         var coubntDwn=1;
                         var condition=Object.keys(actOwnerAttend).length;
                          //console.log(JSON.stringify(actOwnerAttend));
                         	var rowColor="";
                         	var checkValue="";
                         	var dvalD="";
                         	 	
 var disabledP="disabled";
              
                         tableData+="<tr><th>SNo.</th><th>Name</th><th>email</th><th>Cont.No</th><th>Select</th><th>Closed Date</th></tr>";
                          var dtaTdHlder="";
                         while(stCount<=getTotalUser)
                         	 {

                         	 
                         	 	 mkKey="ud"+stCount;

                         	 	 getCurrObject=getUdetail[mkKey];

                         	 	 if(condition>0)
                         	 	 	 {
                                         if(actOwnerAttend[mkKey]!=undefined)
                                         	  {
                                                

                                                     rowColor="style='background-color:rgba(220, 188, 73, 0.22);'";
                                                     checkValue="checked='checked'";
                                                     disabledP="";
                                                     if(closedDHolder[mkKey]!=undefined)
                                                    	 {
                                                    	 
                                                    	 dvalD=closedDHolder[mkKey];
                                                    	 
                                                    	 }

                                                       


                                         	   }else
                                         	   {


                                                rowColor="";
                                                 checkValue="";
                                                 disabledP="disabled";
                                                 dvalD="";


                                         	   }



                         	 	 	 }

                         	 	 if(getCurrObject!=undefined)
                         	 	 {

                                  	 indiObj= getCurrObject[0];

                         	 	     //console.log(JSON.stringify( indiObj));

                                          chkId="chk"+Math.random();
                                           chkId= chkId.replace(".","");
                                           rwId="rw"+chkId;



                         	 	
                         	 	   	 

                                    
                                          trMker="<tr id='"+rwId+"' "+rowColor+">";
                                          sn=coubntDwn;
                                           trMker+="<td>"+sn+"</td>";
                                          
                                             trMker+="<td>"+ indiObj.firstname+"</td>";
                                             trMker+="<td>"+ indiObj.email+"</td>";
                                             trMker+="<td>"+ indiObj.mobile+"</td>";
                                             trMker+="<td><input type='checkbox'  ng-click='getActchk(\""+chkId+"\",\""+mkKey+"\")' id=\""+chkId+"\"  "+checkValue+"/></td>";

                             trMker+="<td><input type='date' id=\"d"+chkId+"\"  "+disabledP+"  onchange='getDOnChange(\"d"+chkId+"\",\""+mkKey+"\" )' value=\""+dvalD+"\"   /></td>";






                                  

                                          trMker+="</tr>";

                                           dtaTdHlder+=trMker;
                                             coubntDwn++;


                         	     	}// end if ///

                              
                           


                                 

                                  stCount++;

                         	    } // end of the while ////////=*/

                               tableData+=dtaTdHlder;

                       


                            tableData+="</table>";

                            allUserDaHolder= tableData;

                            // this.modalHeaderTitle("Add Attendees For Mail");
                            // this.modalBdyWriter(tableData);

   //alert("action ateendee will come here !!");


                   
                        return tableData;
//=============================================================================================












  }// end of the getActForShow(
//======================================= validation section =================================
  this.fileValidate=function(getFNumberVal,minlenth){
                      reData="No";
                     getFile=getFNumberVal;
                     getFile=getFile.trim();
                     if(getFile.length>=minlenth)
                      {

                        reData="Ok";

                     }


          return reData;


       }// end of theufileValidate(getFNumberVal,3)

this.fileDvalid=function(getMDateVal){
              reData="No";
  var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/; 

            if(getMDateVal.match(dateformat))
                {

                 
                  reData="Ok";
                }

         return reData;
    

  }// end of the fileDvalid(getMDateVal)
this.filedCheckCloseD=function(){
          redata="Ok";
        getAttendee=actOwnerAttend;
        getCloseD=dIdHolder;
         angular.forEach(getAttendee, function(value, key) {
        	 // console.log(JSON.stringify(getAttendee));

             if(getCloseD[key]!=undefined)                    
                 {
            	     if(closedDHolder[key]!=undefined)
            	    	   {
            	    	     //getdVal=closedDHolder[key];
                 	         //console.log(getdVal);
                 	         
            	    	 
            	    	 
            	    	   }else
            	    	   {
            	    		   
            	    		 
            	    		   redata="No";
            	    		  
            	    		   //break;
            	    		   
            	    		   
            	    	       }
            	   
            	    
            	       
            	 
                    

                   }
//=========================== break the loop here ================
             if(redata=="No")
            	 {
            	 
            	  return  redata;
            	 }
             
             
             
           

         });// end of the foreach..



  return  redata;





}// end of the filedCheckCloseD()












//======================= --- close the modal ------------=================================
this.closeModal=function(){

               this.modalHeaderTitle("Add Attendees For Mail");
               this.modalBdyWriter("Please Wait..., Preparing the related Contents...");



}// end of the closeModal()


//======================== ----- modal Header Writter ----- ===========================
    this.modalHeaderTitle=function(ttle){



                               var mdlHeader=document.getElementById("modlHeader");
                                mdlHeader.innerHTML=ttle;


                }// end of the mdlHeader



   this.modalBdyWriter=function(contentVal){


                                  var mdlHeader=document.getElementById("modlBody");
                                  mdlHeader.innerHTML=contentVal;


                }// end of the mdlHeader

//============================= setAttendeeValue(getDetailValue,value); ==============================
this.setAttendeeValue=function(getDetailValue,value,refValId){


                              getVal=getDetailValue[value][0]["userId"];
                              if( getVal!=undefined)

                              {

                              	// dataValue=value+":"+getVal+",refId:"+refValId;
                               //     mk_arr={dataValue};

                                   maiLerAttend[value]=getVal;
                                // this.shwAttendeeList(getDetailValue,value);
                             

                                 //  alert(value);









                                   }




                

                     

                 	// console.log("set the Value ...");

                       //  console.log(JSON.stringify(maiLerAttend));
                //=======================   selected row Value ====================== 

                        rwId="#rw"+refValId;
                        
     	                getRef=$(document).find(rwId);
     	                getRef.css("background-color","rgba(220, 188, 73, 0.22)");   
                         







    };// set the attende value .....................
//===================== shwAttendeeList(value)======================
    this.shwAttendeeList=function(getDetailValue,value,attenC){
    	
    	 indiObj=getDetailValue[value][0];
    	 coubntDwn=attenC;
         trMker="<tr >";
           sn=coubntDwn;
           trMker+="<td>"+sn+"</td>";
           trMker+="<td>"+ indiObj.ministry+"</td>";
           trMker+="<td>"+ indiObj.firstname+"</td>";
           trMker+="<td>"+ indiObj.email+"</td>";
           trMker+="<td>"+ indiObj.mobile+"</td>";
           trMker+="</tr" ;
         		
          var getRef=$(document).find("#attendShw");
          getRef.append(trMker);
          attendeCount++;
    	
    };
    
    
    
    
    
    
    
    
    
    
    

     this.unSetAttendeeValue=function(getDetailValue,value,refValId){
     	              


     	                   //console.log("Unset the Value ...");


                       /// console.log(JSON.stringify(getDetailValue[value][0]));
                       if(maiLerAttend[value]!=undefined)
                       {

                             delete maiLerAttend[value];
                              // console.log(JSON.stringify(maiLerAttend));
                              // console.log(Object.keys(maiLerAttend).length);



//============================= Un select row value ===================================

                          rwId="#rw"+refValId;
     	                  getRef=$(document).find(rwId);
     	                  getRef.css("background-color","#fff");

                      


                        }






    };// Unset the attende value .....................




//============================= setAttendeeValue(getDetailValue,value); ==============================
this.setAttendeeValueAct=function(getDetailValue,value,refValId){


                              getVal=getDetailValue[value][0]["userId"];
                              if( getVal!=undefined)

                              {

                              	// dataValue=value+":"+getVal;
                               //     mk_arr={dataValue};

                                     actOwnerAttend[value]=getVal;
                                     dIdHolder[value]=refValId;

                                 // console.log(JSON.stringify(dIdHolder))

                                   //alert(mk_arr);









                                   }




                

                     

                 	// console.log("set the Value ...");

                       //  console.log(JSON.stringify(maiLerAttend));
                //=======================   selected row Value ====================== 

                        rwId="#rw"+refValId;
                        dtId="#d"+refValId;
     	                getRef=$(document).find(rwId);
     	                getRef.css("background-color","rgba(220, 188, 73, 0.22)"); 
                        dtIdRef= $(document).find(dtId);  

                       dtIdRef.removeAttr("disabled");            







    };// set the attende value .....................


     this.unSetAttendeeValueAct=function(getDetailValue,value,refValId){
     	              


     	                   //console.log("Unset the Value ...");


                       /// console.log(JSON.stringify(getDetailValue[value][0]));
                       if(actOwnerAttend[value]!=undefined)
                       {

                             delete actOwnerAttend[value];             
                             delete  dIdHolder[value];
  
                              // console.log(JSON.stringify(dIdHolder));
                              // console.log(Object.keys(maiLerAttend).length);



//============================= Un select row value ===================================

                          rwId="#rw"+refValId;
                           dtId="#d"+refValId;
     	                  getRef=$(document).find(rwId);
     	                   getRef.css("background-color","#fff");
                          dtIdRef= $(document).find(dtId);  

                         dtIdRef.attr("disabled","disabled");   

                        }






      };// Unset the attende value .....................
      
//================================ add the action of the Decision making body =========================      
   this.addWorkDecisionBody=function(){
	         randNo="P"+Math.random();
	         randNo=randNo.replace(".",""); 
	         
	         //alert(randNo);
	         
	          rowDiv="decisionInput"+randNo;
	          SelId="sel"+randNo;
	          desp="desp"+randNo;
	          dDate="ddate"+randNo;
	          clsId="snd"+randNo;
            getSelectRef=$(document).find("#ministryNameHolder");

            getSelectRefVal=getSelectRef.html();
            
           // alert(getSelectRefVal);
            rowCPoint++;
            closedCl="clsed"+rowCPoint;
	          
	          
	        rowHolder='<div class="row" id="'+ rowDiv+'"style="padding-bottom:2px;margin-bottom:2px;border-bottom: 1px dashed #DDD;">';
	       
	        rowHolder+='<div class ="'+closedCl+' col-sm-1" id="'+clsId+'" cnt="'+rowCPoint+'">'+rowCPoint+'</div>';
	        
	        rowHolder+='<div class ="col-sm-4"><input type="text" id="'+desp+'"class="form-control" placeholder="Action Point"/></div>';
        
          rowHolder+='<div class ="col-sm-4"><select class="selectpicker" id="'+SelId+'">'+getSelectRefVal+'</select></div>';
	      

           rowHolder+='<div class ="col-sm-2"><input type="text" id="'+ dDate+'"  class="picdate form-control " placeholder="Expected Date Of Closure"/></div> ';
	       rowHolder+='<div class ="col-sm-1"><span style="cursor:pointer;color:#F17D7D;" ng-click="removeDesBody(\''+randNo+'\')">Remove</span></div>';
	       rowHolder+='</div>';

	       decisionHolder[randNo]="Yes";
	       //$("#hlderDecisionMker").append(rowHolder);
	   
	       return rowHolder ;
	   
    } //
      
      
      
      
      
      
      
//================================== add the decision making body ================================      
      
      
      
      
      
      
//========================== get the value item for Show ====================
 this.getMViewDetail=function(mId,usrUrl){
	 
	         //  alert(usrUrl);
               
            var re_url=usrUrl;
          
            
            var deferred = $q.defer();
            
               var mk_data={url:re_url,usr:mId};
              // console.log(re_url);
               
               allFMethods.onlyContentValpOST(mk_data).success(function(reData){
            	     //console.log(reData);
            	     // alert(reData);//view action Will come here ......
            	    //redata=redata.trim();
            	
            	    
            	    deferred.resolve(reData);
                  //  $("#modalDSho").html(redata);
            	   
               }).error(function(error){
    	    	   
               	
            	   deferred.reject(' Data Processing Error !!!')



               });;
              return deferred.promise;       
               
/*///========================== end of the ajax ===============
	 var re_url=usrUrl;
               $.ajax({

                      type:"GET",
                       url:re_url,
                       success:function(redata){
                    	   // alert(redata);
                            redata=redata.trim();
                            $("#modalDSho").html(redata);

                       },
                        error:function(){

                            alert("Error In Url");

                           }








               })// end of the ajax
               
      //=================== end of the ajax ==========================  //*/       
               
               


   }// end of the getMViewDetail(mId)

//====================================== update columen ==================
 this.UpdateCommentOnDecision=function(refId){
	 
	 comId="#comment"+refId;
	 comRef=$(document).find(comId);
	 comRefVal=comRef.val();
	 comRefVal=comRefVal.trim();
	  if(comRefVal.length<5)
		  {
		    alert("Response Should be more than 5 characters. ");
		    return false
		   }
	 
	 
	  status="#status"+refId;
	  statusRef=$(document).find(status);
	  statusRefVal=statusRef.val();
	  statusRefVal= statusRefVal.trim();
	  
	  bntId="#descomm"+refId;
	  bntIdRef=$(document).find(bntId);
	  getMRef= bntIdRef.attr('cref');
//====================================== ajax request ===================================	  
	  //  alert(usrUrl);
      
      var re_url=ALL_BS_URL+"/commentact";
    
      
    
      
         var mk_data={url:re_url,comment:comRefVal,status:statusRefVal,idref:getMRef};
        
         
         allFMethods.onlyContentValpOST(mk_data).success(function(reData){
      	   
      	   
        	//console.log(JSON.stringify(reData)); 
        	    if(reData['status']=="Success")
        	    	{
        	    	
        	    	  alert("Response Sumitted");
        	    	
        	      	$("#myModal").modal('toggle');
        	    	
        	    	}
        	    	 
        	    	
      	  
      	   
                   }).error(function(error){
	    	   
         	
      	  alert("Please Try After Some Time");



         });;
        
	 
	 
	 
  }// end of th e

//=================== Add New attendee Action =====================================

this.mkActNewAttendeeFrAdd=function(){
          getSetObj=getSelectNewAct;

          getStrVal=JSON.stringify(getSetObj);

          if(getStrVal.length>5)
          {


 //------------- ======= add the Over All  Property ================== ---------             
              curId=getSetObj.curId;
              SlctType=getSetObj.selectype;
              newAttenSelct=$(document).find(".newuSEl");
               newAttenSelct.attr("disabled","disabled");
                newAttenSelct.css("cursor","no-drop");
               newAttenSelct.css("border-color","rgb(169, 169, 169)");
//======================== added minitry zone ========
                getNRef=$(document).find("#shwSlectMin");

                getNRef.text(SlctType);



 //------------- ======= add the new Property ================== ---------              
               mkId="#"+curId;
               curRefId=$(document).find(mkId);
               curRefId.css("border-color","green");
               curRefId.removeAttr("disabled");
                curRefId.css("cursor","pointer");

           



          }else
          {


              alert("Please Select type of attendee");
           }





}// end of the mkActNewAttendeeFrAdd

//-------- ========= add Attendee List In Action ============= --
this.addNWAttendeeList=function(formId){

               getSetObj=getSelectNewAct;

          getStrVal=JSON.stringify(getSetObj);

          if(getStrVal.length>5)
          {
//============== submit the Attendee Details==================================
              curId=getSetObj.curId;
              SlctType=getSetObj.selectype;
              mkId="#"+curId;
              curRefId=$(document).find(mkId);
              getSectValue=curRefId.val();
               if( getSectValue=="No")
                 {

                   alert("Please Select the Option In "+SlctType);
                   return false;

                 }
//===================== get name Value ============================

//------------------------------- value Check -----------------------
               nameId=$(document).find("#attName1");
                nameIdVal=nameId.val();
                nameIdVal=nameIdVal.trim();
                if(nameIdVal.length<4)
                   {

                     alert("Full Name should be more than 3 characters");
                      return false;

                     }

//------------------------------- value Check -----------------------
            otherId=$(document).find("#attoter2");
                otherIdVal=otherId.val();
                otherIdVal=otherIdVal.trim();
                if(otherIdVal.length<3)
                   {

                      alert("Destination should be more than 2 characters");
                      return false;

                     }

//------------------------------- value Check -----------------------

             emailId=$(document).find("#attEmail3");
                emailIdVal=emailId.val();
                emailIdVal=emailIdVal.trim();
                 var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if((emailReg.test(emailIdVal)==false) || (emailIdVal.length<8))
                     {

                       alert("Email Id is not valid ");
                       return false;

                     }
//------------------------------- value Check -----------------------


//------------------------------- value Check -----------------------
            numberId=$(document).find("#attN04");
                numberIdVal=numberId.val();
                numberIdVal=numberIdVal.trim();
                if((numberIdVal.length!=10)||($.isNumeric(numberIdVal)==false))
                   {

                      alert("Mobile is not Valid");
                      return false;

                     }
//======================== Save the  New Attendee Data ============================


var re_url=ALL_BS_URL+"/newattendee";
    
      
    
      
         var mk_data={url:re_url,utype:SlctType,ministry:getSectValue,name:nameIdVal,other:otherIdVal,email:emailIdVal,number:numberIdVal};
        
         //alert(JSON.stringify(mk_data));
         //return false;
         var deferred = $q.defer();

         allFMethods.onlyContentValpOST(mk_data).success(function(reData){
           
          // alert(reData);
         // console.log(JSON.stringify(reData)); 
          
                 
        	   
     	         deferred.resolve(reData);
          
           
                   }).error(function(error){
           
          
                	   deferred.reject(' Data Processing Error !!!')



         });;


         return deferred.promise;  

                


//======================== Save the  New Attendee Data ============================

//============== submit the Attendee Details==================================
          }else
          {


              alert("Please Select type of attendee");
           }


     }// end of the addNWAttendeeList(formId)



//====================  Add new Attendee Action ===========================================
//-------------------- list of ministry Show ----------------------
this.addMinsInList=function(utype,userId,rowId){

//============= add in the list ===================
//------------------- get Value from action --------------
              minId="#"+rowId+"min";
              minRef=$(document).find(minId);
              minVal=minRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              nameId="#"+rowId+"name";
              nameRef=$(document).find(nameId);
              nameVal=nameRef.text();

//------------------- get Value from action --------------
//------------------- get Value from action --------------
              emailId="#"+rowId+"email";
              emailRef=$(document).find(emailId);
              emailVal=emailRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              contactId="#"+rowId+"contact";
              contactRef=$(document).find(contactId);
              contactVal=contactRef.text();

//------------------- get Value from action --------------

         viewMinsList[rowId]={ministr:minVal,name:nameVal,email:emailVal,contact:contactVal,uId:userId,utype:utype};
    
        this.showMinViewList();


         // alert(JSON.stringify( viewMinsList));
         }// end of the addMinsInList(utype,userId,rowId)
//------------- ============ remove the ministry ===================
this.removeMinsInList=function(utype,userId,rowId){


    delete viewMinsList[rowId];
    
this.showMinViewList();
    // alert(JSON.stringify( viewMinsList));



}// end of the removeMinsInList(utype,userId,rowId)
//---------------- showMinViewList() --------------------
this.showMinViewList=function(){
      getMinistryList=viewMinsList;
      ministryCount=JSON.stringify(getMinistryList);
      minHolderRef=$(document).find("#topMinsListHolder");
      minshwRef=$(document).find("#attendMinsShw");
      if( ministryCount.length>10)
       {
         mkTD="";
         minHolderRef.css("display","block");
         rowCount=1;
       angular.forEach(getMinistryList, function(value, key) {
                   getMinstryObj=value;
                    minstry=getMinstryObj.ministr;
                    name=getMinstryObj.name;
                    email=getMinstryObj.email;
                    contact=getMinstryObj.contact;
 
                       mkTD+="<tr>";
                        mkTD+="<td>"+rowCount+"</td>";
                       mkTD+="<td>"+minstry+"</td>";
                       mkTD+="<td>"+name+"</td>";
                       mkTD+="<td>"+email+"</td>";
                        mkTD+="<td>"+contact+"</td>";
                         mkTD+="</tr>";

                    // console.log(JSON.stringify(getMinstryObj))


                rowCount++;


                 });

    
     //============= add in the list 
     minshwRef.html(mkTD);

         }else
        {
         minHolderRef.css("display","none");


         }



}// end of the showMinViewList()

//================= get State List ==================
this.addStateInList=function(utype,userId,rowId){

 //============= add in the list ===================
//------------------- get Value from action --------------
              minId="#"+rowId+"min";
              minRef=$(document).find(minId);
              minVal=minRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              nameId="#"+rowId+"name";
              nameRef=$(document).find(nameId);
              nameVal=nameRef.text();

//------------------- get Value from action --------------
//------------------- get Value from action --------------
              emailId="#"+rowId+"email";
              emailRef=$(document).find(emailId);
              emailVal=emailRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              contactId="#"+rowId+"contact";
              contactRef=$(document).find(contactId);
              contactVal=contactRef.text();

//------------------- get Value from action --------------

         viewStateList[rowId]={ministr:minVal,name:nameVal,email:emailVal,contact:contactVal,uId:userId,utype:utype};
     this.showStateViewList();

  //alert(JSON.stringify(viewStateList));

     }// end of the addStateInList(utype,userId,rowId)
//========== ------------------ remove the action -------------- ===============
this.removeStateInList=function(utype,userId,rowId){


   delete viewStateList[rowId];
    
      
    // alert(JSON.stringify( viewMinsList));
    this.showStateViewList();


        };
//==================== end of the this.showStateViewList(); ======
this.showStateViewList=function(){

 getMinistryList=viewStateList;
      ministryCount=JSON.stringify(getMinistryList);
      minHolderRef=$(document).find("#topStateListHolder");
      minshwRef=$(document).find("#attendStateShw");
      if( ministryCount.length>10)
       {
         mkTD="";
         minHolderRef.css("display","block");
         rowCount=1;
       angular.forEach(getMinistryList, function(value, key) {
                   getMinstryObj=value;
                    minstry=getMinstryObj.ministr;
                    name=getMinstryObj.name;
                    email=getMinstryObj.email;
                    contact=getMinstryObj.contact;
 
                       mkTD+="<tr>";
                        mkTD+="<td>"+rowCount+"</td>";
                       mkTD+="<td>"+minstry+"</td>";
                       mkTD+="<td>"+name+"</td>";
                       mkTD+="<td>"+email+"</td>";
                        mkTD+="<td>"+contact+"</td>";
                         mkTD+="</tr>";

                    // console.log(JSON.stringify(getMinstryObj))


                rowCount++;


                 });

    
     //============= add in the list 
     minshwRef.html(mkTD);

         }else
        {
         minHolderRef.css("display","none");


         }




 };// end of the this.showStateViewList();
//=========================== central Action List =============================
this.addCentralInList=function(utype,userId,rowId){


//============= add in the list ===================
//------------------- get Value from action --------------
              minId="#"+rowId+"min";
              minRef=$(document).find(minId);
              minVal=minRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              nameId="#"+rowId+"name";
              nameRef=$(document).find(nameId);
              nameVal=nameRef.text();

//------------------- get Value from action --------------
//------------------- get Value from action --------------
              emailId="#"+rowId+"email";
              emailRef=$(document).find(emailId);
              emailVal=emailRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              contactId="#"+rowId+"contact";
              contactRef=$(document).find(contactId);
              contactVal=contactRef.text();

//------------------- get Value from action --------------

         viewCentralList[rowId]={ministr:minVal,name:nameVal,email:emailVal,contact:contactVal,uId:userId,utype:utype};
     
     //alert(JSON.stringify(viewCentralList));

     this.showCentralViewList();

 }// end of the addCentralInList(utype,userId,rowId)
this.removeCentralInList=function(utype,userId,rowId){
        delete viewCentralList[rowId];
    
      
    // alert(JSON.stringify( viewMinsList));
     this.showCentralViewList();

//alert(JSON.stringify( viewCentralList));

     }// end of the addCentralInList

//============= ------------- central view List ---------- =====================
this.showCentralViewList=function(){


 getMinistryList=viewCentralList;
      ministryCount=JSON.stringify(getMinistryList);
      minHolderRef=$(document).find("#topCentralListHolder");
      minshwRef=$(document).find("#attendCentralShw");
      if( ministryCount.length>10)
       {
         mkTD="";
         minHolderRef.css("display","block");
         rowCount=1;
       angular.forEach(getMinistryList, function(value, key) {
                   getMinstryObj=value;
                    minstry=getMinstryObj.ministr;
                    name=getMinstryObj.name;
                    email=getMinstryObj.email;
                    contact=getMinstryObj.contact;
 
                       mkTD+="<tr>";
                        mkTD+="<td>"+rowCount+"</td>";
                       mkTD+="<td>"+minstry+"</td>";
                       mkTD+="<td>"+name+"</td>";
                       mkTD+="<td>"+email+"</td>";
                        mkTD+="<td>"+contact+"</td>";
                         mkTD+="</tr>";

                    // console.log(JSON.stringify(getMinstryObj))


                rowCount++;


                 });

    
     //============= add in the list 
     minshwRef.html(mkTD);

         }else
        {
         minHolderRef.css("display","none");


         }






     }// endof the showCentralViewList()
//========== --------------- get the  addAdditionalInList(utype,userId,rowId) --------------
this.addAdditionalInList=function(utype,userId,rowId){

//============= add in the list ===================
//------------------- get Value from action --------------
              minId="#"+rowId+"min";
              minRef=$(document).find(minId);
              minVal=minRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              nameId="#"+rowId+"name";
              nameRef=$(document).find(nameId);
              nameVal=nameRef.text();

//------------------- get Value from action --------------
//------------------- get Value from action --------------
              emailId="#"+rowId+"email";
              emailRef=$(document).find(emailId);
              emailVal=emailRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              contactId="#"+rowId+"contact";
              contactRef=$(document).find(contactId);
              contactVal=contactRef.text();

//------------------- get Value from action --------------

         viewAdditionalList[rowId]={ministr:minVal,name:nameVal,email:emailVal,contact:contactVal,uId:userId,utype:utype};
     
    // alert(JSON.stringify(viewAdditionalList));

     this.showAdditionViewList();


 }// end of the addAdditionalInList(utype,userId,rowId)

//--------------- remove the section ------------------
this.removeAdditionalInList=function(utype,userId,rowId){

    delete viewAdditionalList[rowId];

 this.showAdditionViewList();

    }// end of the this.addAdditionalInList=function(utype,userId,rowId){
//============== ---- show --- ==============================
this.showAdditionViewList=function(){



 getMinistryList=viewAdditionalList;
      ministryCount=JSON.stringify(getMinistryList);
      minHolderRef=$(document).find("#topAdditionalListHolder");
      minshwRef=$(document).find("#attendAdditionalShw");
      if( ministryCount.length>10)
       {
         mkTD="";
         minHolderRef.css("display","block");
         rowCount=1;
       angular.forEach(getMinistryList, function(value, key) {
                   getMinstryObj=value;
                    minstry=getMinstryObj.ministr;
                    name=getMinstryObj.name;
                    email=getMinstryObj.email;
                    contact=getMinstryObj.contact;
 
                       mkTD+="<tr>";
                        mkTD+="<td>"+rowCount+"</td>";
                       mkTD+="<td>"+minstry+"</td>";
                       mkTD+="<td>"+name+"</td>";
                       mkTD+="<td>"+email+"</td>";
                        mkTD+="<td>"+contact+"</td>";
                         mkTD+="</tr>";

                    // console.log(JSON.stringify(getMinstryObj))


                rowCount++;


                 });

    
     //============= add in the list 
     minshwRef.html(mkTD);

         }else
        {
         minHolderRef.css("display","none");


         }








     }// end of the showAdditionViewList

//================ -=------------ addNwAttendInList(utype,userId,rowId) -----------
this.addNwAttendInList=function(utype,userId,rowId){



//============= add in the list ===================
//------------------- get Value from action --------------
              minId="#"+rowId+"min";
              minRef=$(document).find(minId);
              minVal=minRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              nameId="#"+rowId+"name";
              nameRef=$(document).find(nameId);
              nameVal=nameRef.text();

//------------------- get Value from action --------------
//------------------- get Value from action --------------
              emailId="#"+rowId+"email";
              emailRef=$(document).find(emailId);
              emailVal=emailRef.text();

//------------------- get Value from action --------------

//------------------- get Value from action --------------
              contactId="#"+rowId+"contact";
              contactRef=$(document).find(contactId);
              contactVal=contactRef.text();

//------------------- get Value from action --------------

         viewNwAttendeeList[rowId]={ministr:minVal,name:nameVal,email:emailVal,contact:contactVal,uId:userId,utype:utype};
     
       // alert(JSON.stringify( viewNwAttendeeList));

     this.showNwAttendeeViewList();






}// end of the addNwAttendInList(utype,userId,rowId)

//---------------- remove the this.addNwAttendInList=function(utype,userId,rowId){--
  this.removeNwAttendInList=function(utype,userId,rowId){

  delete viewNwAttendeeList[rowId];
   this.showNwAttendeeViewList();

  }// end of the 
//============ -------------- show  this.showNwAttendeeViewList() --------
 this.showNwAttendeeViewList=function(){

getMinistryList=viewNwAttendeeList;
      ministryCount=JSON.stringify(getMinistryList);
      minHolderRef=$(document).find("#topNwAttendListHolder");
      minshwRef=$(document).find("#attendNwAttendShw");
      if( ministryCount.length>10)
       {
         mkTD="";
         minHolderRef.css("display","block");
         rowCount=1;
       angular.forEach(getMinistryList, function(value, key) {
                   getMinstryObj=value;
                    minstry=getMinstryObj.ministr;
                    name=getMinstryObj.name;
                    email=getMinstryObj.email;
                    contact=getMinstryObj.contact;
 
                       mkTD+="<tr>";
                        mkTD+="<td>"+rowCount+"</td>";
                       mkTD+="<td>"+minstry+"</td>";
                       mkTD+="<td>"+name+"</td>";
                       mkTD+="<td>"+email+"</td>";
                        mkTD+="<td>"+contact+"</td>";
                         mkTD+="</tr>";

                    // console.log(JSON.stringify(getMinstryObj))


                rowCount++;


                 });

    
     //============= add in the list 
     minshwRef.html(mkTD);

         }else
        {
         minHolderRef.css("display","none");


         }




 }//  this.showNwAttendeeViewList()








            
	 //end of ahamd code in couserLoadter service...
//========############### end of the  service couserLoadter ##############======================	 
	 
       }]);;
 
 

 //========############### end of the  service couserLoadter ##############======================

//===================================== document action ========================================

function getDOnChange(gId,uId)
          {
	        
             getRef=document.getElementById(gId);
             getD=getRef.value;
             
             
             
             getD=getD.trim();
             if(getD.length>2)
            	 {
            	 
            	  closedDHolder[uId]=getD;
            	 
            	 }else
            	 {
            	  	delete closedDHolder[uId]; 
            		 
            		 
            		 
            		 
            	   }
             
           
             
          
            //console.log(JSON.stringify( dIdHolder));

            



            }// end of the getD



























	




