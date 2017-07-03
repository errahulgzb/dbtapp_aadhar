
<?php
require_once("common.php");
//
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype(){
		/*************code for translation **********/
		 $vareng  = APPLICATION_PATH.'/languages/english.php';
	    $varhindi  = APPLICATION_PATH.'/languages/hindi.php';
	     $translate = new Zend_Translate(
        array(
            'adapter' => 'array',
            'content' => $vareng,
            'locale'  => 'en'
        )
    );
	
	$translate->addTranslation(
    array(
        'content' =>  $vareng,
        'locale'  => 'en'
    )
);


$translate->addTranslation(
    array(
        'content' => $varhindi,
        'locale'  => 'hi'
    )
);
	$i=1;
			 //$_SESSION['LANGUAGE_ID_TEXT']=$_POST['language_id'];
			 
			   $defaultLanguage = "en";
			   
			   
			    $slang = new Zend_Session_Namespace('languageold');
				$langid =   $slang ->language;
			   if($langid ==1){
				   $translate->setLocale("hi");
			   }
			   else{
				 $translate->setLocale("en");
			   }
		$pressrelease =  $translate->_("Press_release");	
		$dashboard =  $translate->_("Dashboard");	
		$mprreport =  $translate->_("Mpr_report");	
	   $dbtonboardedschemes =  $translate->_("DBT_ONBOARDED_SCHEMES");
	    $dbtapplicableschemes =  $translate->_("DBT_Applicable_Schemes");			   
		$central =  $translate->_("Central");
        $dbtcell  =  $translate->_("DBT_Cells"); 
        $stateuts =  $translate->_("stateuts"); 		
		$circular =  $translate->_("Circulars");
		$event =  $translate->_("Events");
		$document =  $translate->_("Documents");
		$uts =  $translate->_("UTs"); 
	    $adhar_payment_bridge =  $translate->_("Aadhaar payment bridge");
		$nonadhar_payment_bridge =  $translate->_("Non Aadhaar Payment");   
		  $cumulative =  $translate->_("Cumulative");
		$FINANCIAL_YEAR = $translate->_("Financial_Year");
		$NO_OF_SCHEMES = $translate->_("NO_OF_SCHEMES");
		$adhar_based =  $translate->_("AADHAR BASED");
		$beneficareies =  $translate->_("Beneficiaries");
		$more_details =  $translate->_("More details");
		$nonadhar_based =  $translate->_("NON AADHAR BASED");
		$adhar_based_funds_transfer =  $translate->_("Aadhar Based Fund Transfer");
		$in_cash =  $translate->_("In Cash");
		$in_kind =  $translate->_("In Kind");
		$others =  $translate->_("Others");
		$departments =  $translate->_("Departments");
		$schemes =  $translate->_("SCHEMES");
		$savings =  $translate->_("SAVINGS");
		$schemes =  $translate->_("SCHEMES");
		$total_funds_transfer =  $translate->_("Total funds transfer");
		$total_beneficaries =  $translate->_("Total Beneficiaries Transaction");
		$savings =  $translate->_("SAVINGS");
		$schemes =  $translate->_("SCHEMES");
		$total_funds_transfer =  $translate->_("Total funds transfer");
		$total_beneficaries =  $translate->_("Total Beneficiaries Transaction");
		$skip_main_content =  $translate->_("Skip to main content");
		$screen_reader_acess =  $translate->_("Screen reader access");
		//$footer_content  =  $translate->_("Content owned, updated and maintained by the Scheme Management System, Government of India");
		$footer_content  =  $translate->_("&copy;2017 Designed, Developed by Direct Benefit Transfer (DBT) Mission and Maintained by Ministries / Departments, Government of India.");

		$login =  $translate->_("Login");
		$previous =  $translate->_("Previous");
		$next =  $translate->_("Next");
		$scheme_management_system=  $translate->_("DBT APP");
		$govt_of_india  =  $translate->_("Government of India");
		$home  =  $translate->_("Home");
		$scheme =  $translate->_("Scheme");
		$report  =  $translate->_("Report");
		$feedback  =  $translate->_("Feedback");
	   $photogallery =  $translate->_("Photo Gallery");
	    $multimedia =  $translate->_("Multimedia");
		$india =  $translate->_("India");
		$fy =  $translate->_("Fy");
		$TOTAL_NUMBER_OF_TRANSACTION =  $translate->_("TOTAL_NUMBER_OF_TRANSACTION");
		$image_gallery =  $translate->_("IMAGE_GALLERY");
		$video_gallery =  $translate->_("VIDEO_GALLERY");
		$not_in_scale =  $translate->_("NOT_TO_SCALE");
	  /***************end *******************/
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');

define("SELECT_USERS_ACTIVATE_DEACTIVATE","Please select at least one user");

define("SELECT_ROLES_ACTIVATE_DEACTIVATE","Please select at least one role");

#################################################################################################################################################

		define("BASE_PATH","dbtapp_aadhar");
		define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']."/dbtapp_aadhar/");
		define("WEB_LINK","http://192.168.100.132/dbtappstate/");
		define("FORGET_PASSWORD","I've forgotten my password.");
		define("audit_log","Audit Log");
		define("FEEDBACK_MSG","We have recieved your complaint/feedback and we will get back to you soon.");
		define("COPYRIGHT","&copy; Copyright DBT APP");
		define("WEBSITE_TITLE","DBT APP");
		define("RECORD_FOUND","Record(s) Found.");
		define("RECORD_INSERTED","Record has been Added Successfully.");
		define("RECORD_UPDATED","Record has been Updated Successfully.");
		define("RECORD_DELETED","Record has been Deleted Successfully.");
		define("RECORD_ACTIVATED","Record has been Activated Successfully.");
		define("RECORD_EMPTY","No Record Found.");
		define("Scheme_assign_msg","Assigned Scheme To Scheme Owner");
		 define("add_panchayat","Add Panchayat");
		 define("scheme_info","Scheme Information");
		 define("edit_panchayat","Edit Panchayat");
         define("SEARCH_BY","Search By:");
		define("ACTION_TAKEN","Action Taken");
		define("ACTION_DATE","Activity Date & Type");
		define("ACTION_TYPE","Activity Type");
		define("ATTACHMENTS","Attachments");
		define("add_subdistrict","Add Sub District");
		define("CUSTOMER_UPDATE_HISTORY","Update History");
		define("LATEST_UPDATE_HISTORY","Latest Update History");
		define("CUSTOMER_UPDATED_ENGINEER","Update By");
		define("VIEW_DETAILS","View Details");
		define("ADD_STATE","Add State");
		define("ADD_SUBDISTRICT","Add Sub District");
		define("Edit_STATE","Edit State");
		define("PROJECT_START_DATE","Start Date");
		define("PROJECT_END_DATE","End Date");
		define("MAXIMUM_200_CHAR","Maximum 200 Character allow.");
		define("SITE_DESCRIPTION","Description");
		define("SITE_ATTACHMENT","Attachment");
        define("REPORT","Report");
		define("CUSTOMER_REPORT","Report");
		define("NUMBER_OF_PROJECTS","Number of Projects");
		define("PROJECT_MANAGER","Scheme Owner");
		define("LOCATION_NAME1","Scheme");
		define("Add_Content","Add Content");
		define("PROJECT_NAME","Ministry Name");
		define("LOCATION_NAME","Scheme Name");
		define("LOCATION_SITE_INFORMATION","Location & Site Information");
		define("REMARK_STATUS","Action");
		define("REMARK_INFORMATION","Immediate Action Information");
		define("REMARK","Remark");
		define("content_management_edit","Edit Content");
		define("content_management","Content Management");
		define("feedback_management","Feedback Management");
		define("CAPTCHA_NOTEMPTY","Captcha can't be empty.");
		define("CAPTCHA_INCORRECT","Please enter a vallid captcha code.");
		define("CAPTCHA_EMPTY","Captcha can't be empty.");
		define("CSRF_ATTACK","This is CSRF attack. Please correct and try again.");
	    define("edit_subdistrict","Edit Sub District");
		define("SELECT_PER_PAGE","Records Per Page");
		define("IMMEDIATE_ACTION_INFORMATION","Immediate action information updated succesfully.");
		define("Add_feedback","Add Feedback");
		define("SCHEME_CODIFICATION_UTILITY", "Generate Scheme Code");
		define("SCHEME_CODIFICATION","Scheme Code");
		define("CUSTOMER_BACK_TO_PROJECT_UPDATE_PAGE","Back to Project Update Page");
		define("NO_FREE_PROJECT","There is no free Scheme's.");
		define("ASSIGN_PROJECT","Assign Scheme");
		define("MY_ACCOUNT","My Account");
		define("EDIT_USER","Edit User");
	define("UPLOAD_FILE_CSV_ONLY_INSTRUCTION","Allowed file format: CSV");
        define("CHANGE_PASSWORD","Change Password");
        define("MANAGE_PROJECT","Ministry");
        define("MANAGE_ROLE","Roles");
        define("MANAGE_USER","Users");
        define("MANAGE_LOCATION","Scheme");
        define("MANAGE_ASSIGN_ENGINEER","Assign Engineer");
        define("ADD_NEW_MINISTRY","Add New Ministry");
        define("ADD_NEW_LOCATION","Add New Scheme");
        define("EDIT_LOCATION","Edit Scheme");
		define("EDIT_Feedback","Edit Feedback");
		define("VIEW_Feedback","View Feedback");
        define("HISTORY","History");
        define("UPDATE_HISTORY","Update History");
		define("ASSIGNED","Assigned");
        define("USERNAME","Username");
        define("EMAIL","email");
		define("PSR1","psr1");
		define("NOT_ASSIGN","Request for Assign");
		define("IN_PROGRESS","In Progress");
        define("CLOSED","Closed");
		define("CANCEL","Cancel");
		define("SCHM",$scheme);
		define("DASHBOARD",$dashboard);
		define("MPRREPORT",$mprreport);

		/// user mail start here ///////
		define("MAIL_SUBJECT","User Account Information");
		define("MAIL_FROM","info@dbtbharat.gov.in");
		define("MAIL_NAME","Account Information");	 
		define("MESSAGE_BODY","Dear {fname},<br /><br />Welcome to the DBT APP.<br /><br/>
        Login ID and password for DBT APP is mentioned below.<br /><br />
        Username: <strong>{user_name}</strong><br />
        Password: <strong>{user_password}</strong><br /><br/>
        Regards,<br />DBT Bharat Team <br />
        Website: {web_link}<br />
        (This is a system generated message. Please do not reply to this email.)");
		
		define("MAIL_SUBJECT_CODIFY","New Scheme Code Created");	
		define("MAIL_NAME_CODIFY","Scheme Code");	
		define("MAIL_SHEMEME_FROM","info@dbtbharat.gov.in");	
	//Assign Scheme Owner Mail
	define("MAIL_NAME_ASSIGN_PROJECT","Scheme Assigned | DBT APP");
	define("ASSIGN_PROJECT_MAIL_SUBJECT","Scheme Assigned | DBT APP");
	define("ASSIGN_PROJECT_MESSAGE_BODY","Dear {user_name},<br /><br />This is to update you that you are assigned with a new Scheme task.<br />The detailed information about the task can be seen by login into the portal with your credentials.<br /><br /> The Portal Link is {web_link}.<br /><br />Scheme details:<br /><br />");
	define("DROP_PROJECT_MESSAGE_BODY","Dear {user_name},<br /><br />This is to inform you that the assigned project has been taken back from you.<br /><br />");
	define("ASSIGN_PROJECT_MESSAGE_BODY_SECOND","<br />Note: For more information, visit the portal with your credential.<br /><br />Regards<br />DBT Bharat Team <br />Website: {web_link}<br />(This is a system generated mail. Please do not reply on this address.)");

	define("DROP_PROJECT_MESSAGE_BODY_SECOND","<br /><br />Regards<br />Project Status Report Management Team <br />Website: {web_link}<br />(This is a system generated mail. Please do not reply on this address.)");
	define("MAIL_NAME_DROP_PROJECT","DBT APP");
	define("ASSIGN_TOPM_PROJECT_TOPM_MESSAGE_BODY","Dear {user_name},<br /><br />This is to inform you that the Installation report has been uploaded into the portal.<br />You can see the report using the below mention detail:<br /><br /><br />Site: <strong>{site_name}</strong><br />Location: <strong>{location_name}</strong><br />Project: <strong>{project_name}</strong><br /> <br /><br />Regards<br />Project Status Report Management Team<br />Website: {web_link}<br />(This is a system generated mail. Please do not reply on this address.)");
	//Drop Project email to the scheme owner
	define("DROP_PROJECT_MAIL_SUBJECT","Scheme Withdrwan | DBT APP");
	define("DROP_PROJECT","Dear {user_name},<br /><br />This is to inform you that the assigned Scheme has been taken back from you.<br />Below are the mention details:<br /><br />");
	define("DROP_PROJECT_SECOND","<br /><br />Note: For more information, visit the portal with your credential.<br /><br />Regards<br />DBT Bharat Team<br />Website: {web_link}<br />(This is a system generated mail. Please do not reply on this address.)");
	//Assign Scheme Owner Mail	
		
		
		
		
	
		////feedback mail content///
		define("MAIL_SUBJECT_FEEDBACK","Feedback/Complaint"); 
		define("MAIL_ADMIN","feedback@dbtbharat.gov.in"); 
		define("MAIL_NAME_FEEDBACK","Feedback/Complaint"); 
		define("FORGOT_SUBJECT","Password Reset | DBT APP");
		define("ADD_NEW_DISTRICT","Add New District");
		define("YEAR_FROM", '2012');
		define("CANTEMPTY", "can't be empty!");
		define("NUMERICVALIDATION", "Only Numeric Value are allowed in mobile num!");
		define("ALPHABETSVALIDATION", "can take only alphabet value.");
		define("ALPHANUMERICVALIDATION", "can take only alphanumeric value.");
		define("EMAILVALIDATION", "Please enter correct email address.");
		define("FILE_FORMAT_ERROR", "Invalid file or file extension");
		define("FILE_SIZE_ERROR_2MB", "File size is greater than 2MB");
		define("FILE_DIMENSION_ERROR", "Upload image for banner 1304x327 and for photogallery width <= 1024");
		define("ATLEAST_ONEVALUE", "Please input atleast one value.");
		define("ALREADYLOGEDIN", "This user already loged in to another system, Please logout.");
		define("ALLREADY_AVAILABLE", "You can only import data for this scheme.");
		define("SPECIALCHARVALIDAION", "field has some special characters. These are not allowed. Please remove them and try again.");
		define("ALLREADY_EXIST", "This record is already Exists.");
		define("CSV_EXPORT", "Export");
		//define("TOTAL_NUMBER_OF_BENEFICIARIES", $total_number_of_beneficiaries);
		define("MORE_DETAILS", "More details");
		//define("TOTAL_STATES", $total_states);
		//define("TOTAL_DISTRICT", $total_district);
		//define("TOTAL_VILLAGES", $total_villages);
		define("STORY_INSERTED", "Approval request has been sent to Application Administrator");
		define("ADMIN_EMAIL", "feedback@dbtbharat.gov.in");
		define("adhar_based",$adhar_based);
		define("nonadhar_based",$nonadhar_based);
		define("adhar_based_funds_transfer",$adhar_based_funds_transfer);
		define("in_cash",$in_cash);
		define("in_kind",$in_kind);
		define("others",$others);
		define("schemes",$schemes);
		define("skip_main_content",$skip_main_content);
		define("screen_reader_acess",$screen_reader_acess);
		define("footer_content",$footer_content);
		define("login",$login);
		define("scheme_management_system",$scheme_management_system);
		define("govt_of_india",$govt_of_india);
		define("home",$home);
		define("scheme",$scheme);
		define("report",$report );
		define("feedback",$feedback);
		define("beneficareies",$beneficareies);
		define("more_details",$more_details);
		define("india",$india);
		define("cumulative",$cumulative);
		define("fy",$fy);
		define("NO_OF_SCHEMES", $NO_OF_SCHEMES);
		define("TOTAL_NUMBER_OF_TRANSACTIONS",$TOTAL_NUMBER_OF_TRANSACTION);
		define("DOCUMENTS",$document);
		define("NOT_TO_SCALE",$not_in_scale);
		define("CENTRAL",$central);
		define("stateuts",$stateuts);
		define("DBTCELL",$dbtcell);
		define('DBT_ONBOARDING_MONITORING_PROGRESS',"DBT Scheme Progress Report");
		define("DBT_SCHEMES", "DBT Schemes");
		define("CSV_ASSESSMENT_EXPORT", "Export Report");
		define("SNo", "SNo.");
		define("SchemeName", "Scheme Name");
		define("SchemeType", "Scheme Type");
		define("StateName", "State Name");
		define("DistrictName", "District Name");
		define("ASSIGNED_SCHEME_MESSAGE","Below Scheme has been assigned to the State User. You cannot unassigned.");
		#Breadcrumb Variable Assigning Start
			define("Home","/");
		#Breadcrumb Variable Assigning End
 if($_SERVER['SERVER_ADDR']=='::1'){$server='localhost';}else{$server=$_SERVER['SERVER_ADDR'];}
	define("WEB_SERVICE_LINK","http://".$server."/".BASE_PATH."/wsdl/AadhaarStatus_npcinet.wsdl");
	define("FILE_WRITE_LINK",$server.'/'.BASE_PATH);
	define("AADHAR_SEEDED_SUCCES","Aadhar Number was valid and Seeded with aadhar.");
	define("AADHAR_SEEDED_FAIL","Aadhar Number was not valid and Seeded with aadhar.");
	define("AADHAR_UIDAI_SUCCESS","Aadhar Number was valid with UIDAI.");
	define("AADHAR_UIDAI_FAIL","Aadhar Number was not valid with UIDAI.");

	define("B_FIRST_TAB_MSG","NPCI and Aadhaar validated List");
	define("B_SECOND_TAB_MSG","NPCI Validation Failed List");
	define("B_THIRED_TAB_MSG","Aadhaar Validation Failed List");
	define("B_FOURTH_TAB_MSG","List for PFMS");
	define("B_FIFTH_TAB_MSG","All Beneficiary List");
	
	define("T_FIRST_TAB_MSG","Payment Initiation");
	define("T_SECOND_TAB_MSG","Transaction available for PFMS");
	define("T_THIRED_TAB_MSG","Awaiting Transaction confirmation");
	define("T_FOURTH_TAB_MSG","Transaction History");
	
	define("TOLDM_FIRST_TAB_MSG","All Transactions");
	define("TOLDM_SECOND_TAB_MSG","Awaiting Transaction confirmation");
	

	//define("pfms_WRITE_LINK_XML","http://192.168.100.132/dbtapp_aadhar/");
//************* uidai static value variable start now **********************
	define("API_VERSION","1.6");
	define("ASA_LICENCE_KEY","MH4hSkrev2h_Feu0lBRC8NI-iqzT299_qPSSstOFbNFTwWrie29ThDo");
	define("LK","eDBT-4IO6Y3GTY098GQB");
	define("AC","public");
	define("SA","ZZ1057CABI");
	define("TID","public");
	define("UDC","293874298374");
	define("PIP","NA");
	define("TXN","eDBT");
	define("PUBLIC_CERT_PATH","http://".$server.'/'.BASE_PATH."/library/Uidai/pre_prod.cer");
	define("P12_FILE","http://".$server.'/'.BASE_PATH."/library/Uidai/eMudraCerti.pfx");
	define("DECRYPT_FILE","http://".$server.'/'.BASE_PATH."/library/Uidai/eMudhraCertificate.pfx");
	define("AUTHPASS","Auth@1234");
	define("AUTHURLCURL","http://10.249.34.231:8080/NicASAServer/ASAMain");
	define("MS","E");
	define("MS_AD","P");//Value (E or P)....When MS_AD define as E the MV must be define as 100
	define("MV","70");
		
//************* uidai static value variable end now **********************
	}

			protected function _initRouter(){
				$router = Zend_Controller_Front::getInstance()->getRouter();
				$router->addRoute('', new Zend_Controller_Router_Route('', array('controller'=>'auth','action'=>'login')));
			}
		}


