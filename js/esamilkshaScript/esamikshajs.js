
var selectMailer={};

$(document).ready(function(){
	


 //====================== write down the project ===================================================


//======================== submit click =========================
$("#submitmail").click(function(){

  //---------------- data box ---------------------------------   
           val1=$("#cat1").val();
            val1= val1.trim();
            refChecker=checkValue(val1);
            if(refChecker!="Ok")
            {
            
                mess="Category is Not Selected !!!";
                errorShw(mess);
                return false;
              }
   
//---------------- data box --------------------------------- 
      
 //---------------- data box ---------------------------------   
           val2=$("#cat2").val();
            val2= val2.trim();
            refChecker=checkValue(val2);
            if(refChecker!="Ok")
             {
            
                mess="Document is Not Selected !!!";
                errorShw(mess);
                return false;
               }
   
//---------------- data box --------------------------------- 

 //---------------- data box ---------------------------------   
           val3=$("#date1").val();
            val3= val3.trim();
            refChecker=checkValue(val3);
            if(refChecker!="Ok")
             {
            
                mess="Document/Letter Date is Not Selected !!!";
                 errorShw(mess);
                return false;
               }
   
//---------------- data box --------------------------------- 

 //---------------- data box ---------------------------------   
           val4=$("#date2").val();
           val4= val4.trim();
            refChecker=checkValue(val4);
            if(refChecker!="Ok")
             {
            
                mess="Document Expiry Date is Not Selected !!!";
                errorShw(mess);
                return false;
               }
   
//---------------- data box --------------------------------- 


 //---------------- data box ---------------------------------   
           val5=$("#emailSub").val();
           val5= val5.trim();
           

//---------------- data box --------------------------------- 


 //---------------- data box ---------------------------------   
           val6=$("#docSub").val();
            val6=val6.trim();
           
            //alert(val6);
//---------------- data box --------------------------------- 

  //---------------- data box ---------------------------------   
           val7=$("#emailBody").val();
            val7= val7.trim();
           

          //  alert(val7);
 //---------------- data box --------------------------------- 
 //---------------- data box ---------------------------------   
           val8=$("#importfile").val();
           val8= val8.trim();
            refChecker=checkValue(val8);
            fileStatus="No";
            if(refChecker=="Ok")
            	{

                fileStatus="Yes";

            	}

         getUserString=JSON.stringify(selectMailer);

           if(getUserString.length<5){

                
                mess="Please Select the Listed Users !!!!";
                errorShw(mess);
                return false;
              

           }

//================== hide the Users ==============================
        // errorShwHide();

   urlVal="http://180.151.3.101/dbtliveserver/esamiksha/mailSender";

    //alert("data Flow");
 //---------------- data box ---------------------------------   
//================== count the  selected user Value =============================
      dataMaker={cat:val1,doctyp:val2,ldate:val3,ExDate:val4,docSub:val6,emailSub:val5,emailBdy:val7,fileStatus:fileStatus,usr:selectMailer,url:urlVal}

   //alert("data Flow");
     //getStrinbgData=JSON.stringify(dataMaker);

     updateData(dataMaker);
     
 
//================== count the  selected user Value =============================




         });// submit button .....








//=================== clicking on the  mail Selectio =============
$(".umailSelet").click(function (){

       getid=$(this).attr("idval");
   if($(this).prop("checked"))
   	    {

          
           
       selectMailer[getid]="Yes";
 

   	    }else
   	    {

         delete selectMailer[getid];


   	   }

  console.log(JSON.stringify(selectMailer))
 

});// select tejh umailSelet










});// end of the document .....

//=================== ----------------- error Message ------------------ ============


//=================== checkValue(cat1) ===============================
checkValue=function(paramData){

   reVal="No";
   getData=paramData;
   getData=getData.trim();
    if(getData.length>2)
      {

         reVal="Ok";

      }
  //alert(paramData);
    return reVal;

}// checkValue(cat1) 









errorShw=function(mess){

       var refId=$(document).find(".error-msg");
        refId.html(mess);

       refId.css("display","block");


}


errorShwHide=function(){

       var refId=$(document).find(".error-msg");
        refId.html("Error Display !!!");

       refId.css("display","none");


}


//=================== upload the file ==========================

 updateData =function(getData){



          // alert("user Data !!");



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

                                         alert("Soon This Mailing Functionality is Activated !!!");


                               
               }// end of he UPlad ...