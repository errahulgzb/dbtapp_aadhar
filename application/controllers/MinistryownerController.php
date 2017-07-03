<?php
/* Role Definition:
 * 1 => Administrator
 * 2 => Survey User [Installation Manager]
 * 3 => Customer
 * 4 => Project Manager
 */
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class MinistryownerController extends Zend_Controller_Action
{
    protected $rolearray=array('1','2','3','4','5','6');
	
	  protected $rolearraynew = array('1');
    public function init()
    {
        $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		$this->_helper->layout->setLayout('admin/layout');
		
		/* Initialize action controller here */
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('ministryowneraddnewAction', 'html')->initContext();
                $role = new Zend_Session_Namespace('role');
			if ($this->_helper->FlashMessenger->hasMessages()) {
			     $this->view->messages = $this->_helper->FlashMessenger->getMessages();
			}	
                if($role->role==1){
                    $this->_helper->layout->setLayout('sites/layout');
                }
    }
	
public function ministryownerviewdetailAction()
  {
	  $this->_helper->layout->setLayout('admin/layout');
	  $admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
		$this->rolearray = array("1","3");
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }	

		
	$request = $this->getRequest();
	$editid = safexss(base64_decode($request->getParam('id')));;
	//print_r($editid);
	$edit_show = new Application_Model_Ministryowner;
	$showdetails = $edit_show->showphasetwodata($editid);
	$this->view->assign('cmidata', $showdetails);
	  
	  
  } 
	public function schemeaddedlistAction()
    {
	
	//$search = $_GET['title'];
     $this->_helper->layout->setLayout('admin/layout');
	  $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
            $this->rolearray = array("1","3");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
 
        //Searching
        $search ="";
        $search = intval($_GET['m_type']);
		
		///Searching 
	
		if($_GET['savetype'] == '0')
		{
        $savetype = intval($_GET['savetype']);
		}
		else
		{
			$savetype =  1;
		}
	
	//echo $savetype;
		
        $this->view->assign('search', $search);
     
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
	$request = $this->getRequest();
	$cmi_list = new Application_Model_Ministryowner;
	//$result = $cmi_list->PhaseoneReportcsvphasetwo();
	//echo "<pre>";
	//print_r($result);
	
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
	if(isset($start))
				{                         // This variable is set to zero for the first page
				$start = 0;
			
				}
				else
				{
				  $start=$request->getParam('start');
				 
				}

		$page=0;


		$limit=10;
		
	$cmishow_list = $cmi_list->schemeaddeddetail($start,$limit,$search,$savetype);
	//die;
	$countcmi = $cmi_list->countschemeaddeddetail($search,$savetype);
	$this->view->assign('cmidata', $cmishow_list);
	$page_name='schemeaddedlist';
	$pagination1=$this->pagination_schemeadded($countcmi,$start,$limit,$search,$savetype,$page_name);
	$this->view->assign('pagination', $pagination1);
	$this->view->assign('start', $start);
	$this->view->assign('counttotalcmireports', $countcmi);
        
        //searching
        $ministry=$cmi_list->getMinistry($search,$table='dbt_scheme_eligbility_assessment');
        $this->view->assign("ministry_array",$ministry);
        //echo "<pre>";
        //print_r($ministry);
       // echo "</pre>";
       // die;
        
	}
	
	
	public function dashboardformtwoAction()
    {
	
	//$search = $_GET['title'];
     $this->_helper->layout->setLayout('admin/layout');
	  $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
            $this->rolearray = array("1","3");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
 
        //Searching
        $search ="";
        $search = intval($_GET['m_type']);
        $this->view->assign('search', $search);
     
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
	$request = $this->getRequest();
	$cmi_list = new Application_Model_Ministryowner;
	//$result = $cmi_list->PhaseoneReportcsvphasetwo();
	//echo "<pre>";
	//print_r($result);
	
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
	if(isset($start))
				{                         // This variable is set to zero for the first page
				$start = 0;
			
				}
				else
				{
				  $start=$request->getParam('start');
				 
				}

		$page=0;


		$limit=10;
	$cmishow_list = $cmi_list->schemeaddeddetail($start,$limit,$search);
	//die;
	$countcmi = $cmi_list->countschemeaddeddetail($search);
	$this->view->assign('cmidata', $cmishow_list);
	$page_name='dashboardformtwo';
	$pagination1=$this->pagination_schemeadded($countcmi,$start,$limit,$search,$page_name);
	$this->view->assign('pagination', $pagination1);
	$this->view->assign('start', $start);
	$this->view->assign('counttotalcmireports', $countcmi);
        
        //searching
        $ministry=$cmi_list->getMinistry($search,$table='dbt_scheme_eligbility_assessment');
        $this->view->assign("ministry_array",$ministry);
        //echo "<pre>";
        //print_r($ministry);
       // echo "</pre>";
       // die;
        
	}
	
	
	public function pagination_schemeadded($nume,$start,$limit,$search,$savetype,$page_name)
					{
		
							if($nume > $limit)
							{
							//$page_name ='schemeaddedlist?m_type='.$search;
							$page_name =$page_name.'?m_type='.$search.'&savetype='.$savetype;
							$this1 = $start + $limit; 
							$back = $start - $limit; 
							$next = $start + $limit;
							
							$paginate="";
							$paginate.='<ul class="pagination">';

                            if($back >=0)
							{
								$paginate.='<li><a href="'.$page_name.'&start='.$back.'" class="head2">&lt; PREV</a></li>';
							}
							$i=0;
							$l=1;
							for($i=0;$i < $nume;$i=$i+$limit)
							{
								if($i <> $start)
								{
									$paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
								}
								else
								{
									$paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
								}
								$l=$l+1;
							
							}

							if($this1 < $nume)
							{ 
								$paginate.='<li><a href="'.$page_name.'&start='.$next.'" class="head2">NEXT &gt;</a></li>';
							}
							$paginate.='</ul>';
							//echo $paginate;
							$this->view->assign('paginate', $paginate);
							}	
							
					}
	
	public function csvexportschemeaddedAction()
   {
            $search ="";
            $search = intval($_GET['m_type']);
            $this->view->assign('search', $search);
				if($_GET['savetype'] == 0)
				{
				  $savetype = $_GET['savetype'];
				}
				else if($_GET['savetype'] == 1)
				{
                  $savetype = $_GET['savetype'];
				} else {
					$savetype = 1;
				}
				$this->view->assign('savetype', $savetype);
    $request = $this->getRequest();
	$ministryowner = new Application_Model_Ministryowner;
	$phaseid = safexss($request->getParam('phaseid'));
	$currentdate = date('d-m-y');
	$filename = "phasetwo-report-".$currentdate.".csv";
		if($request->isGet()){
			$output .= '"",';
			$output .= '"Detailed Analysis  of the Scheme",';
			$output .="\n";
            $output .= '"Ministries/Department",';
			$output .= '"Name of the Scheme",';
			$output .= '"Type of the Scheme",';
			$output .= '"Type of Benefit",';
		    $output .= '"Description of the Scheme",';
			$output .= '"Fund Allocated",';
			$output .= '"Description of the Process Flow",';
			$output .= '"Total number of Target  Beneficaries",';
			$output .= '"Total Number of Eligible Beneficiaries",';
			$output .= '"Digitized Database for Beneficiaries",';
			$output .= '"MIS Portal of Beneficiary Database",';
			$output .= '"Aadhaar Seeding in BD",';
			$output .= '"Bank Account Number in BD",';
			$output .= '"Mobile Number in BD",';
            $output .= '"Aadhaar linkage with  Bank Details",';
			$output .= '"Mode of Payment",';
			$output .= '"Integrated with  PFMS",';
            $output .= '"Last Updated(dd/mm/yyyy hh:mm:ss)",';
			$output .="\n";
			$ministryid = new Zend_Session_Namespace("ministryid");
			$ministryowner = new Application_Model_Ministryowner;
			//$result = $ministryowner->schemeaddeddetailcsv();
			//$ministryname  = $ministryowner->getministryname($ministryid->ministryid);
			/**********get the data from scheme eligibility assesment********/
			$result = $ministryowner->PhaseoneReportcsvphasetwo($search,$savetype);
			/*****************end************/
			$increment = 1;
			$min_name="";
			$flag=0;
			foreach($result as $key => $data){
				
				 $updatedate = $ministryowner->changedateformat($data['updated']);
						
				$output .='"'.$data['ministryname'].'",';
				$output .='"'.$data["name_of_scheme"].'",';
				if($data['type_of_scheme'] == 1){
					$type_of_scheme = "Central Sector";
				}else if($data['type_of_scheme'] == 2){
					$type_of_scheme =  "Centrally Sponsored";
				}
				else if(empty($data['type_of_scheme'])){
				$type_of_scheme =  "Not Found";
				}
				$output .='"'.$type_of_scheme.'",';
				
				if($data['type_of_benefit'] == 1){
					$type_of_benefit = "Cash";
				}else if($data['type_of_benefit'] == 2){
					$type_of_benefit =  "In Kind";
				}else if($data['type_of_benefit'] == 3){
					$type_of_benefit =  "Other Transfers";
				}else if(empty($data['type_of_benefit'])){
				$type_of_benefit =  "Not Found";
				}
				
				$output .='"'.$type_of_benefit.'",';
				//$output .='"'.$data["scheme_description"].'",';
				$output .='"'.str_replace('"'," ",$data["scheme_description"]).'",';
				//$output .='"'.htmlspecialchars($data["scheme_description"]).'",';
				
				$output .='"'.$data["fund_allocation"].'",';
				$output .='"'.$data["process_flow_description"].'",';
				$output .='"'.$data["target_beneficiary"].'",';
				$output .='"'.$data["total_eligble_beneficiary"].'",';
				if($data['digitized_beneficiary_status'] == 1){
					$digitized_beneficiary_status = "Yes";
				}else if($data['digitized_beneficiary_status'] == 2){
					$digitized_beneficiary_status =  "No";
				}
				else if(empty($data['digitized_beneficiary_status'])){
				$digitized_beneficiary_status =  "Not Found";
				}
				$output .='"'.$digitized_beneficiary_status.'",';
				if($data['mis_portal_status'] == 1){
					$mis_portal_status = "Yes";
				}else if($data['mis_portal_status'] == 2){
					$mis_portal_status =  "No";
				}
				else if(empty($data['mis_portal_status'])){
				$mis_portal_status =  "Not Found";
				}
				$output .='"'.$mis_portal_status.'",';
				$output .='"'.$data['aadhar_seeding_bd'].'",';
				$output .='"'.$data['bank_account_bd'].'",';
				$output .='"'.$data['mobile_number_bd'].'",';
				$output .='"'.$data['aadhar_linkage_account'].'",';
				if($data['mode_of_payment'] == 1){
					$mode_of_payment = "APBS";
				}else if($data['mode_of_payment'] == 2){
					$mode_of_payment =  "AEPS";
				}else if($data['mode_of_payment'] == 3){
					$mode_of_payment =  "NACH";
				}else if($data['mode_of_payment'] == 4){
					$mode_of_payment =  "OTHERS";
				}
				else if(empty($data['mode_of_payment'])){
				$mode_of_payment =  "Not Found";
				}
				$output .='"'.$mode_of_payment.'",';
				if($data['pfms_payment'] == 1){
					$pfms_payment = "Yes";
				}else if($data['pfms_payment'] == 2){
					$pfms_payment =  "No";
				}else if(empty($data['pfms_payment'])){
				$pfms_payment =  "Not Found";
				}
				$output .='"'.$pfms_payment.'",';
				$output .='"'.$updatedate.'",';
				$output .="\n";	
				$increment++;
			}
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			echo $output;
			exit;
		}





 }	
 
 
 
 public function csvexportadminphaseoneAction()
	{
		
		 // echo "test"; die;
            $search ="";
            $search = intval($_GET['m_type']);
			 $eligible_type = intval($_GET['eligible_type']);
            $this->view->assign('search', $search);
            $request = $this->getRequest();
            $currentdate = date('d-m-y');
		     $filename = " phaseone-report-".$currentdate.".csv";
		     if($request->isGet()){
			$output .= '"",';
			$output .= '"Comprehensive list of all the schemes",';
			$output .="\n";
            $output .= '"Ministries/Department",';
			$output .= '"Name of the Scheme",';
			$output .= '"DBT Applicability (Yes/No)",';
			//$output .= '"Target Beneficiaries",';
		    //$output .= '"Type of Benefit",';
			 $output .= '"Type of Benefit",';
			$output .= '"DBT Applicability Type",';
			$output .= '"Remarks",';
			$output .= '"Last Updated(dd/mm/yyyy hh:mm:ss)",';
			$output .="\n";
			$datacount = count($arr);
			$ministryid = new Zend_Session_Namespace("ministryid");
			$ministryowner = new Application_Model_Ministryowner;
			//$result = $ministryowner->PhaseoneReport($ministryid->ministryid,$userid->userid);
			$result = $ministryowner->PhaseoneReportcsv($search,$eligible_type);
			$ministryname  = $ministryowner->getministryname($ministryid->ministryid);
			$increment = 1;
			$min_check = '';
			foreach($result as $key => $data){
                 $updatedate='';
				 $updatedate = $ministryowner->changedateformat($data['updated']);
				 //echo  $updatedate;
				//$output .='"'.$ministryname[0]['ministry_name'].'",';
				
				$output .='"'.$data['ministryname'].'",';
				$output .='"'.$data["scheme_name"].'",';
				if($data['dbt_eligibility'] == 1){
					$dbt_eligibility = "Yes";
				}else if($data['dbt_eligibility'] == 2){
					$dbt_eligibility =  "No";
				}
				$output .='"'.$dbt_eligibility.'",';
				//$eligibilitytype = explode(",",$data['dbt_eligibility_type']);
				if($data['benefit_type'] == 1){
				  $benefit_type = "Cash";
				}else if($data['benefit_type'] == 2){
					$benefit_type = "In Kind";
				}else if($data['benefit_type'] == 3){
					$benefit_type = "Other Transfers";
				}
                else
                 {
                    $benefit_type = "N/A";
                 }
				 $output .='"'.$benefit_type.'",';
				$seligibletype = array();
				foreach (explode(",",$val['dbt_eligibility_type']) as $val) {
						//$seligibletype.='tt';
							if($val== 1){
								$seligibletype[$val]=  "Individual ";
							}else if($val == 2){
								$seligibletype[$val]= "HouseHold ";
							}else if($val == 3){
								$seligibletype[$val]= "Service Enablers ";
							}else{
								$seligibletype[$val]= "N/A ";
							}
					}
			/* 	if ($seligibletype){
					$seligibletypeval = implode(",",$seligibletype);
				} else {
					$seligibletypeval = 'N/A';
				} */

               
				 $output .='"'.implode(",",$seligibletype).'",';
                 
                 
				
				if($data["specific_reason"] == "" || $data['dbt_eligibility_type']!=''){
					$specificreason =  "N/A";
				}else{
					$specificreason =  $data["specific_reason"];
				}
				 $output .='"'.$specificreason.'",';
				  $output .='"'. $updatedate.'",';
				$output .="\n";	
				$increment++;
				//echo $output;die;
			}
			//die;
			//echo $output;die;
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			echo $output;
			exit;
		}
	}
	public function csvexportAction()
	{
		 $request = $this->getRequest();
        $currentdate = date('d-m-y');
		$filename = "ministryowner-".$currentdate.".csv";
		if($request->isGet()){
			$output .= '"",';
			$output .= '"Comprehensive list of all the schemes",';
			$output .="\n";
            $output .= '"Ministries/Department",';
			$output .= '"Name of the Scheme",';
			$output .= '"DBT Applicability (Yes/No)",';
			//$output .= '"Target Beneficiaries",';
		    //$output .= '"Type of Benefit",';
			$output .= '"Type of Benefit",';
			$output .= '"DBT Applicability Type",';
			$output .= '"Remarks",';
			$output .="\n";
			$datacount = count($arr);
			$ministryid = new Zend_Session_Namespace("ministryid");
			$ministryowner = new Application_Model_Ministryowner;
			$result = $ministryowner->PhaseoneReport($ministryid->ministryid,$userid->userid);
			$ministryname  = $ministryowner->getministryname($ministryid->ministryid);
			$increment = 1;
			foreach($result as $key => $data){

				$output .='"'.$ministryname[0]['ministry_name'].'",';
				$output .='"'.$data["scheme_name"].'",';
				if($data['dbt_eligibility'] == 1){
					$dbt_eligibility = "Yes";
				}else if($data['dbt_eligibility'] == 2){
					$dbt_eligibility =  "No";
				}
				$output .='"'.$dbt_eligibility.'",';
				$eligibilitytype = explode(",",$data['dbt_eligibility_type']);
				if($data['benefit_type'] == 1){
				  $benefit_type = "Cash";
				}else if($data['benefit_type'] == 2){
					$benefit_type = "In Kind";
				}else if($data['benefit_type'] == 3){
					$benefit_type = "Other Transfers";
				}
                else
                 {
                    $benefit_type = "N/A";
                 }
				 $output .='"'.$benefit_type.'",';
				$seligibletype = array();
				foreach($eligibilitytype as $k=>$v)
				{
					if($v== 1){
						$seligibletype[$v] =  "Individual ";
					}else if($v == 2){
						$seligibletype[$v] = "HouseHold ";
					}else if($v == 3){
						$seligibletype[$v] = "Service Enablers ";
					}					
				}
				if ($seligibletype){
					$seligibletypeval = implode(",",$seligibletype);
				} else {
					$seligibletypeval = 'N/A';
				}

               
				 $output .='"'.$seligibletypeval.'",';
                 
                 
				
				if($data["specific_reason"] == "" || $data['dbt_eligibility_type']!=''){
					$specificreason =  "N/A";
				}else{
					$specificreason =  $data["specific_reason"];
				}
				 $output .='"'.$specificreason.'",';
				$output .="\n";	
				$increment++;
			}
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			echo $output;
			exit;
		}
	}
	public function csvexportphasetwoAction()
	{
		 $request = $this->getRequest();
        $currentdate = date('d-m-y');
		$filename = "ministryowner-".$currentdate.".csv";
		if($request->isGet()){
			$output .= '"",';
			$output .= '"Comprehensive list of all the schemes",';
			$output .="\n";
            $output .= '"Ministries/Department",';
			$output .= '"Name of the Scheme",';
			$output .= '"DBT Applicability (Yes/No)",';
			//$output .= '"Target Beneficiaries",';
		    //$output .= '"Type of Benefit",';
			$output .= '"Type of Benefit",';
			$output .= '"DBT Applicability Type",';
			$output .= '"Remarks",';
			$output .="\n";
			$datacount = count($arr);
			$ministryid = new Zend_Session_Namespace("ministryid");
			$ministryowner = new Application_Model_Ministryowner;
			$result = $ministryowner->PhasetwoReport($ministryid->ministryid,$userid->userid);
			$ministryname  = $ministryowner->getministryname($ministryid->ministryid);
			$increment = 1;
			foreach($result as $key => $data){

				$output .='"'.$ministryname[0]['ministry_name'].'",';
				$output .='"'.$data["scheme_name"].'",';
				if($data['dbt_eligibility'] == 1){
					$dbt_eligibility = "Yes";
				}else if($data['dbt_eligibility'] == 2){
					$dbt_eligibility =  "No";
				}
				$output .='"'.$dbt_eligibility.'",';
				$eligibilitytype = explode(",",$data['dbt_eligibility_type']);
				if($data['benefit_type'] == 1){
				  $benefit_type = "Cash";
				}else if($data['benefit_type'] == 2){
					$benefit_type = "In Kind";
				}else if($data['benefit_type'] == 3){
					$benefit_type = "Other Transfers";
				}
                else
                 {
                    $benefit_type = "N/A";
                 }
				 $output .='"'.$benefit_type.'",';
				$seligibletype = array();
				foreach($eligibilitytype as $k=>$v)
				{
					if($v== 1){
						$seligibletype[$v] =  "Individual ";
					}else if($v == 2){
						$seligibletype[$v] = "HouseHold ";
					}else if($v == 3){
						$seligibletype[$v] = "Service Enablers ";
					}					
				}
				if ($seligibletype){
					$seligibletypeval = implode(",",$seligibletype);
				} else {
					$seligibletypeval = 'N/A';
				}

               
				 $output .='"'.$seligibletypeval.'",';
                 
                 
				
				if($data["specific_reason"] == "" || $data['dbt_eligibility_type']!=''){
					$specificreason =  "N/A";
				}else{
					$specificreason =  $data["specific_reason"];
				}
				 $output .='"'.$specificreason.'",';
				$output .="\n";	
				$increment++;
			}
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			echo $output;
			exit;
		}
	}
   public function indexAction()
    {
		/*$user_array=array('87');
		$userid = new Zend_Session_Namespace("userid");
		if(!in_array($userid->userid,$this->user_array)){
			
		}else{
			$this->_redirect('');
		}
		*/
		ob_start();
		$Contactusobj = new Application_Model_Ministryowner;
		$tempmin = $Contactusobj->listministry();
		$this->view->assign('ministrytempdata',$tempmin);
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                     $this->_redirect('');
                }
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
		$form = new Application_Form_MinistryOwner();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		$ministryid = new Zend_Session_Namespace('ministryid');
		/**** get the ministry name *************/
		$ministrynamenew  = $Contactusobj->getministryname($ministryid->ministryid);
		$ministryname  = $ministrynamenew[0]['ministry_name'];
		/*****end********************************/
		/********* code for view scheme ias page 14th aug*****/
		/*******code for paging*********/
			if(isset($start))
				{                         // This variable is set to zero for the first page
				$start = 0;
			
				}
				else
				{
				  $start=$request->getParam('start');
				 
				}

			$page=0;


			$limit=10;
		
		
		/************end***********/
		$userid = new Zend_Session_Namespace("userid");
		$Contactusobj = new Application_Model_Ministryowner;
		$result = $Contactusobj->PhaseoneReport($ministryid->ministryid,$userid->userid);
		$this->view->assign("resultant",$result);
		/************end**************/
	
		if ($this->getRequest()->isPost()){
			   $dataform=$request->getPost();
				$Contactusobj = new Application_Model_Ministryowner;
				 /**********vallidation to check captcha code 26th july ************/
				   		if($_POST['vercode']!= $_SESSION["vercode"])
						{
							 echo "Please enter a correct code!";
							   die;
						}

						if($_POST['sessionCheck']!=$captcha->captcha)
						{
						   echo  CSRF_ATTACK;
						   die;
						}
					
			        /*****************end***********************/
				
				/*******validation for ministry owner form ***********/
				 $len =  $_POST['uniquidnew'];
				 //echo $len; die;
				 for ($x = 1; $x <= $len; $x++) {
					 if(isset($_POST['scheme-name-'.$x])) {
                     $scheme_name =  $_POST['scheme-name-'.$x];
					$dbt_eligible =  $_POST['dbt-eligible-'.$x];
					$benefit_type =  $_POST['benefit-type-'.$x];
					$eligibility_type = $dataform['eligibility-type-'.$x];
					$specific_reason =  $_POST['specific-reason-'.$x];
					//$result = $Contactusobj->checkcontentschemename($scheme_name);
					
                      $result = $Contactusobj->checkcontentschemename($scheme_name,$ministryid->ministryid);
					//echo $result; die;
					if($result == 1)
					{
						echo "This scheme name is allready exists in this ministry!"; 
						die;
					}
					
					if($scheme_name == '')
					{
						echo "Please enter a scheme name!"; 
						die;
					}
					
					if (preg_match("/[^A-Za-z0-9 _ ,&-]/", $scheme_name))
						{
							echo "Invalid Characters in Scheme Name!";die;
						}	
					if(trim($scheme_name) == '')
					{
					 echo "White Space are not allowed in Scheme Name!";die;
					}
		
					if($dbt_eligible == 0)
					{
						echo "Please enter a DBT Applicability!";
						die;
					}
					if(($benefit_type == 0) && ($dbt_eligible == 1))
					{
						echo "Please enter a Benefit Type!";
						die;
					}
					if($dbt_eligible == 1)
					{
						if($eligibility_type == '')
						{
						echo "Please enter a DBT Applicability Type!";
						die;
						}
					}
					if($dbt_eligible == 2)
					{
						if($specific_reason == '')
						{
						echo "Please enter a Specific Reason!";
						die;
						}
						if(ltrim($specific_reason)!= $specific_reason) 
						{
							echo "White Space are not allowed in Specific Reason!";
							die;
						}
						if(preg_match("/[^a-zA-Z0-9~`!#$^*_:?()@., \"\'-\n\r]/", $specific_reason))
						{
							echo "Invalid Characters In Specific Reason!";
							die;
						}
					 /* if(preg_match("/[^A-Za-z0-9 _ ,.\'-]/", $specific_reason))
						{
							echo "Invalid Characters In Specific Reason!";
							die;
						} */
						
					}
				 }
					
					
					
					/****code to insert in the audit log******/
						if($dbt_eligible == 1)
						{
						  $dbt_applicabilty = 'Yes';
						}
						else if($dbt_eligible == 2)
						{
						   $dbt_applicabilty = 'No';
						}

						if($benefit_type == 1)
						{
						  $type_of_benefit = 'Cash';
						}
						else if($benefit_type == 2)
						{
						   $type_of_benefit = 'In Kind';
						}
						else if($benefit_type == 3)
						{
						  $type_of_benefit = 'Other Transfers';
						}


						if($eligibility_type == 1)
						{
						   $eligibilityType = 'Individual';
						}
						else if($eligibility_type == 2)
						{
						   $eligibilityType = 'HouseHold';
						}
						else if($eligibility_type == 3)
						{
						   $eligibilityType = 'ServiceEnablers';
						}

						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
						$description .= 'Form One</br>';	
						$description .= 'Ministry Name: '.$ministryname.'</br>';						
						$description .= 'Scheme Name: '.$scheme_name.'</br>';
						$description .= 'DBT Applicability: '.$dbt_applicabilty.'</br>';
						if($dbt_eligible == 1)
						{
						$eligibleType = array();
						foreach($eligibility_type as $key=>$val)
						{
						if($val == 1)
						{
						$eligibleType[] = 'Individual'; 
						}
						if($val == 2)
						{
						$eligibleType[] = 'HouseHold'; 
						}
						if($val == 3)
						{
						$eligibleType[] = 'ServiceEnablers'; 
						}
						}					
						$eligibletype = implode(",",$eligibleType);
						$description .= 'Type of Benefit: '.$type_of_benefit.'</br>';
						$description .= 'Eligibilty Type: '.$eligibletype.'</br>';
						}
						else if($dbt_eligible == 2)
						{
						$text = wordwrap($specific_reason, 25, "\n", true);
						$description .= 'Specify Reason: '.$text.'</br>';
						}

						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'DBT Applicability Assessment',
						"description" => $description
						); 
						$schemeidobj->insertauditlog($auditlog);
						
					/********** audit log end***************************/
                 }
				
				
				/**************end******************/
				$match  = $Contactusobj->insministryowner($_POST,$ministryid->ministryid);
				//echo "<pre>"; print_r($match); exit;
				
				if($match)
				{
						
					echo 1;
					//$this->_redirect('/ministryowner/viewschemeias?act=successadd');
					die;
				}
				//echo "<pre>"; print_r($match); exit;
				 //$countdata = $Contactusobj->checkcontent($dataform['title']);	
				  /**********vallidation to check captcha code 26th july ************/
					
			       /*****************end***********************/
								
		
	  } 
	}
	public function ministrydraftinsertAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		$i = $_POST['i'];
		$scheme_name = $_POST['scheme_name'];
		$dbt_eligible = $_POST['dbt_eligible'];
		$benefit_type = $_POST['benefit_type'];
		$dbt_eligible_type = $_POST['dbt_eligible_type']; 
		$dbt_specificreason = $_POST['dbt_specificreason'];
		$ministryid = new Zend_Session_Namespace('ministryid');
		$Contactusobj = new Application_Model_Ministryowner;
		$returned = $Contactusobj->instempowner($scheme_name,$dbt_eligible,$benefit_type,$dbt_eligible_type,$dbt_specificreason,$ministryid->ministryid);
		
	}
		
	 public function ministryowneraddnewAction()
     {
	   $this->_helper->layout->disableLayout();
		ob_start();
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                     $this->_redirect('');
                }
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
				
		$form = new Application_Form_MinistryOwnerAddNew();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		
		if ($this->getRequest()->isPost()) {
		if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
			  //echo "<pre>";print_r($dataform); exit;
				$Contactusobj = new Application_Model_Ministryowner;
				 $countdata = $Contactusobj->checkcontent($dataform['title']);	
				  /**********vallidation to check captcha code 26th july ************/
					if($dataform['vercode'] != $_SESSION["vercode"])
					{
						   $msg="Please enter a correct code!";
							//$this->view->assign('msg', $msg);
							$this->view->assign('errorMessage', "Please enter a correct code!");
							return false;
					}
					 if($dataform['sessionCheck']!=$captcha->captcha)
				    {
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   return false;
					}
			       /*****************end***********************/
				if($countdata == 0)
				{
					$match  = $Contactusobj->insertContentManagementdetails($dataform);
					
					//$this->_helper->redirector('locationview');
                       $this->_redirect('/contentmanagement/contentmanagementview?actmsg=add');
				}
				else
				{
					$this->view->assign('errorMessage', 'This Title has allready Exists.');
					return false;
					 //$this->_redirect('/contentmanagement?actmsg=rpt');
					 
				}						
			}
		}
	}
	
	 public function dbteligibetypeAction()
		{
			//echo "test"; die;
			$this->_helper->layout->disableLayout();
			ob_start();
		    $form = new Application_Form_DbtEligibeType();
			$form->addform();
		    $this->view->form = $form;
			$request = $this->getRequest();

		}
		
		public function dbtspecificreasonAction()
		{
			$this->_helper->layout->disableLayout();
			ob_start();
		    $form = new Application_Form_DbtSpecificReason();
			$form->addform();
		    $this->view->form = $form;
			$request = $this->getRequest();

		}
	
	public function ministryuserAction(){
		$userid = new Zend_Session_Namespace("userid");
		$admin = new Zend_Session_Namespace("adminMname");
		$role = new Zend_Session_Namespace("role");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
        }else{
			echo $userid->userid."<br />";
			echo $admin->adminMname;
			exit;
		}
	}
	
	
	
	
	
	
	
	public function ministryschemeaddAction(){
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
        }else{
			$form = new Application_Form_Ministryscheme();
			$ministryowner = new Application_Model_Ministryowner;

			//echo "Aaaassssss";exit;
			$form->addform();
			$request = $this->getRequest();
			if($request->getParam('actmsg')=='saved'){
				$this->view->assign('successMsg', RECORD_INSERTED);
			}
			if(!$request->getParam("phaseone") && !$request->getParam("ministryid")){
				$this->_redirect("");
			}else{
				
				$decodePhaseid = safexss(base64_decode($request->getParam("phaseone")));
				$decodeMinistryid = safexss(base64_decode($request->getParam("ministryid")));
				
				$this->view->assign("phase_id",$request->getParam("phaseone"));
				$this->view->assign("ministryid",$request->getParam("ministryid"));
				
				$submitted = $ministryowner->SelectForViewSubmit($decodePhaseid,$decodeMinistryid);
				//echo "aaaaa".$submitted.$decodePhaseid;exit;
				if($submitted > 0){
					$phaseone = safexss($request->getParam("phaseone"));
					$mini_id = safexss($request->getParam("ministryid"));
					//echo "Aaaa";exit;
					$this->_redirect("ministryowner/viewschemeadded?phaseone=$phaseone&ministryid=$mini_id");
				}
				
				
				$nameofschemepop = $ministryowner->selectSchemeNamePop($decodePhaseid,$decodeMinistryid);
				if(count($nameofschemepop) > 0 ){
					$nameofschemepop['phase_id'] = safexss(base64_encode($nameofschemepop['phase_id']));
					$nameofschemepop['ministry_id'] = safexss(base64_encode($nameofschemepop['ministry_id']));
					$form->populate($nameofschemepop);
				//below code use for show the scheme name of data	
					/*$nameofscheme = $ministryowner->selectSchemeName($decodePhaseid,$decodeMinistryid);
					$somedata = array("name_of_scheme"=>$nameofscheme[0]['scheme_name'],
					"ministry_id"=>$request->getParam("ministryid"),
					"phase_id"=>$request->getParam("phaseone"));
					$form->populate($somedata);*/
				//below code use for show the scheme name of data	
				}else{		
					$nameofscheme = $ministryowner->selectSchemeName($decodePhaseid,$decodeMinistryid);
					$somedata = array("name_of_scheme"=>$nameofscheme[0]['scheme_name'],
					"ministry_id"=>$request->getParam("ministryid"),
					"phase_id"=>$request->getParam("phaseone"));
				$form->populate($somedata);
				}
			}
                        //print_r($nameofscheme);



		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature)
		{
			$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			$pos1 = strpos($_SERVER['QUERY_STRING'], 'errmsg=');
			if (($pos !== false) || ($pos1 !== false)) {
				$qstringval="ministryid=".$request->getParam("ministryid")."&phaseone=".$request->getParam("phaseone");
				$this->_redirect('/ministryowner/ministryschemeadd?'.$qstringval);
			}
		} else {
		  $_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end 

			
			$this->view->form = $form;
			if ($_SESSION['dataform']){ 
					$form->populate($_SESSION['dataform']);
			}
			unset($_SESSION['dataform']);
			$scheme_detail=$ministryowner->viewUploadDetailsOfScheme($decodePhaseid,$decodeMinistryid);
			$deletequerystring = 'fileid='.base64_encode($scheme_detail[0]['id']).'&phaseid='.base64_encode($scheme_detail[0]['phase_id']).'&ministryid='.base64_encode($scheme_detail[0]['ministry_id']).'&filename='.base64_encode($scheme_detail[0]['filename']);
			$this->view->assign('deletequerystring', $deletequerystring); 
            $this->view->assign('filename', $scheme_detail[0]['filename']);    

 

			if($request->isPost()){
				$dataform=$request->getPost();
			
								
			$qstring="ministryid=".$dataform['ministry_id']."&phaseone=".$dataform['phase_id'];
       
                /****************If click on save button: Starts ****************/
                             
                $decodePhaseid = safexss(base64_decode($request->getParam("phaseone")));
				$decodeMinistryid = safexss(base64_decode($request->getParam("ministryid")));
				$nameofschemepop = $ministryowner->selectSchemeNamePop($decodePhaseid,$decodeMinistryid);
                                
                                
                                
                          ////$scheme_detail=$ministryowner->viewDetailsofScheme(base64_encode($request->getParam("phaseone")),base64_encode($request->getParam("ministryid")));
                          // print_r($scheme_detail);      
          if($dataform['save']=='save'){
				$_SESSION['dataform'] = $dataform;
				$ministryowner = new Application_Model_Ministryowner;
				$request = $this->getRequest();
                $validatemsg = $this->customValidation($dataform);
//echo $validatemsg; exit;
				if ($validatemsg == '0'){
					if(!$request->getParam("phaseone") && !$request->getParam("ministryid")){
						//echo "Unable to save records!";exit;
										$this->view->assign("errorMessage", "Unable to save records!");
					}else{
							$this->view->assign("filename", $scheme_detail[0]['filename']);
						if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['phase_id'])) {
							$dataform['phase_id'] = base64_decode($dataform['phase_id']);
						} else {
							$dataform['phase_id'] = $dataform['phase_id'];
						}
										if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['ministry_id'])) {
							$dataform['ministry_id'] = base64_decode($dataform['ministry_id']);	
						} else {
							$dataform['ministry_id'] = $dataform['ministry_id'];
						}
								
									//File upload validation        
									$fileFormat = array ('pdf','PDF');
									$allow_extensions=array('application/pdf');
								   
									if($_FILES['uploadfile']['name'] != ""){
										$filename = $_FILES['uploadfile']['name'];
										$fieltempval =0;
										//Check file validation
										$fieltempval = $this->fileUploadValidation($_FILES,$fileFormat,$allow_extensions);
										//If valid file then upload the file into server.
										if($fieltempval==0){
											 $target_path = $_SERVER['DOCUMENT_ROOT'].'/data/ministryowner/'.time().$filename;
											move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path);
											$dataform['filename'] = time().$_FILES["uploadfile"]["name"];
										}else{
										  $this->view->assign("errorMessage", "Only PDF File can be uploaded."); 
										  $fieltempval =2;
										}
									}
									 $nameofscheme = $ministryowner->selectSchemeName($dataform['phase_id'],$dataform['ministry_id']);       
									 $dataform["name_of_scheme"] = $nameofscheme[0]['scheme_name'];
									 
									if($dataform["name_of_scheme"] != '' && $fieltempval!=2){
											$dataform["phase_id"] = base64_encode($dataform["phase_id"]);
											$ministryowner->SaveRecord($dataform,"save",$filename);
											//echo "Data has been successfully saved!";exit;
											$this->_redirect('/ministryowner/ministryschemeadd?actmsg=saved&'.$qstring);
												//$this->view->assign("saved", "Data has been saved successfully.");
									}else{
											//echo "You have entered wrong information!\n Please refresh the page and enter the data again.";exit;
									}
									//$nameofscheme = $ministryowner->selectSchemeName($dataform['phase_id'],$dataform['ministry_id']);
									}
						} else {
							//$this->view->assign("errorMsg", $validatemsg);
							$errormsg = base64_encode($validatemsg);
							$this->_redirect('/ministryowner/ministryschemeadd?errorMessage=errorMsg&'.$qstring.'&errmsg='.$errormsg);
						}
                                /****************If click on save: Ends Code ****************/
              }else{        
                             
                        /****************If click on submit button: Starts ****************/    
                                
              if ($form->isValidPartial($request->getPost())) {
				$fileFormat = array ('pdf','PDF');
				if($_FILES['uploadfile']['name'] != ""){
					$filename = $_FILES['uploadfile']['name'];
                    $fieltempval = 0;
                    $allow_extension_only=array('application/pdf');
                    if((count(explode('.',$filename))>2)||(preg_match("/[\/|~|`|;|:|]/", $filename))){
						//echo $filename."aaaa";exit;
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%0A\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%0D\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%22\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%27\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%3C\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%3E\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%00\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%3b\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%3d\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%29\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%28\b/i", $filename)){
                            $fieltempval = 1;
                    }elseif(preg_match("/\b%20\b/i", $filename)){
                            $fieltempval = 1;
                    }
                   $qstring="ministryid=".$dataform['ministry_id']."&phaseone=".$dataform['phase_id'];
                   if(in_array(end( explode ( '.', $filename)), $fileFormat) && $fieltempval == 0){
                    $target_path = $_SERVER['DOCUMENT_ROOT'].'/data/ministryowner/';
                    $target_path1 = $target_path . basename ($fTemp);
                    $target_path2 = $target_path . basename ($fTemp1);
                    
					//$data = file_get_contents($target_path1);
					
                    $data = file_get_contents($_FILES['uploadfile']['tmp_name']);
                    $dataCheck = substr($data,0,2);
		//echo "----".$dataCheck;die;	
                    if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == "" )
                            {
								//echo $filename."jiji".$dataCheck;exit;
                                    $this->_redirect('/ministryowner/ministryschemeadd?actmsg=fileformaterror&'.$qstring);
                    } else {
						
							$filename = $_FILES["uploadfile"]["name"];    
							$fileFormat = array ('pdf','PDF');
						if (in_array(end(explode ('.', $filename)), $fileFormat)){
							$target_path = $_SERVER['DOCUMENT_ROOT'].'/data/ministryowner/'.time().$filename;
							move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path);
							$dataform['filename'] = time().$_FILES["uploadfile"]["name"];
							$filename = time().$_FILES["uploadfile"]["name"];
							//echo $dataform['filename']."aaa";exit;
						}
						else{
								//echo "PLease select valid file to upload";exit;
							}	   
						}
					}
					//echo $filename."aaaa";exit;
				}	
					$nameofscheme = $ministryowner->selectSchemeName(base64_decode($dataform['phase_id']),base64_decode($dataform['ministry_id']));
					$dataform["name_of_scheme"] = $nameofscheme[0]['scheme_name'];
					//print_r($dataform);exit;
					
					if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['phase_id'])) {
						//return TRUE;
						
						$dataform['phase_id'] = $dataform['phase_id'];
					} else {
						$dataform['phase_id'] = base64_decode($dataform['phase_id']);
					}
					if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['ministry_id'])) {
						//return TRUE;
						$dataform['ministry_id'] = $dataform['ministry_id'];
						
					} else {
						$dataform['ministry_id'] = base64_decode($dataform['ministry_id']);
					}
					
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        if($filename !='' &&($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == ""))
                                        {
                                                                            //echo $filename."jiji".$dataCheck;exit;
                                               // $this->_redirect('/ministryowner/ministryschemeadd?actmsg=fileformaterror&'.$qstring);
                                            $this->view->assign("errorMessage","There is some error in upload file formate.");
                                        } else {
                                        
												/******insert in to the audit log*********/

												$ministrynamenew = $ministryowner->getministryname($decodeMinistryid);
												$schemenamenew  = $ministryowner->selectSchemeName($decodePhaseid,$decodeMinistryid);
												$ministryname  = $ministrynamenew[0]['ministry_name'];
												$schemename  = $schemenamenew[0]['scheme_name'];
												if($dataform['type_of_scheme'] == 1)
												{
												$type_of_scheme = 'Central Sector';
												}
												else if($dataform['type_of_scheme'] == 2)
												{
												$type_of_scheme = 'Centrally Sponsored';
												}
												if($dataform['digitized_beneficiary_status'] == 1)
												{
												$digitized_beneficiary_status = 'Yes';
												}
												else if($dataform['digitized_beneficiary_status'] == 2)
												{
												$digitized_beneficiary_status = 'No';
												}
												if($dataform['mis_portal_status'] == 1)
												{
												$mis_portal_scheme = 'Yes';
												}
												else if($dataform['mis_portal_status'] == 2)
												{
												$mis_portal_scheme = 'No';
												}

												if($dataform['type_of_benefit'] == 1)
												{
												$type_of_benefit = 'Cash';
												}
												else if($dataform['type_of_benefit'] == 2)
												{
												$type_of_benefit = 'In Kind';
												}
												else if($dataform['type_of_benefit'] == 3)
												{
												$type_of_benefit = 'Other Transfers';
												}



												if($dataform['pfms_payment'] == 1)
												{
												$pfmspayment  = 'Yes';
												}
												else if($dataform['pfms_payment'] == 2)
												{
												$pfmspayment = 'No';
												}



												if($dataform['mode_of_payment'] == 1)
												{
												$mode_of_payment  = 'APBS';
												}
												else if($dataform['mode_of_payment'] == 2)
												{
												$mode_of_payment = 'AEPS';
												}else if($dataform['mode_of_payment'] == 3)
												{
												$mode_of_payment = 'NACH';
												}else if($dataform['mode_of_payment'] == 4)
												{
												$mode_of_payment = 'OTHERS';
												}


												$schemeidobj = new Application_Model_Auditlog();
												$description = '';	
												$description .= 'Form two submit</br>';	
												$description .= 'Ministry Name: '.$ministryname.'</br>';						
												$description .= 'Scheme Name: '.$schemename.'</br>';
												$description .= 'Type of the Scheme: '.$type_of_scheme.'</br>';
												$description .= 'Fund Allocated for the Scheme: '.$dataform['fund_allocation'].'</br>';
												$description .= 'Implementing Agency (if any): '.$dataform['implemeting_agency'].'</br>';
												$description .= 'Target Beneficiaries: '.$dataform['target_beneficiary'].'</br>';
												$description .= 'Total Number of Eligible Beneficiaries : '.$dataform['total_eligble_beneficiary'].'</br>';
												$description .= 'Digitized Beneficiary Database (BD) in place:'.$digitized_beneficiary_status.'</br>';
												$description .= 'MIS Portal in Place for the scheme: '.$mis_portal_scheme.'</br>';
												$description .= 'Aadhaar Seeding in BD: '.$dataform['aadhar_seeding_bd'].'</br>';
												$description .= 'Bank Accounts Number in BD: '.$dataform['bank_account_bd'].'</br>';
												$description .= 'Mobile Number in BD: '.$dataform['mobile_number_bd'].'</br>';
												$description .= 'Aadhaar linkage with Bank Account: '.$dataform['aadhar_linkage_account'].'</br>';
												//$description .= 'Scheme Description:'.$dataform['scheme_description'].'</br>';
												$description .= 'Type of Benefits:'.$type_of_benefit.'</br>';
												$description .= 'Details of Benefit:'.$dataform['details_of_benefit'].'</br>';
												//$description .= 'Description of Process flow:'.$dataform['process_flow_description'].'</br>';
												$description .= 'Payment Linked to PFMS:'.$pfmspayment.'</br>';
												$description .= 'Mode of Payment (if applicable):'.$mode_of_payment.'</br>';
												$auditlog = array(
												"uid" => $userid->userid,
												"application_type" => 'DBT Applicability Assessment',
												"description" => $description
												); 
												$schemeidobj->insertauditlog($auditlog);
												/***********audit log end********************/
                                        $ministryowner->SaveRecord($dataform,"submit",$filename);
										//$this->_redirect('ministryowner/viewschemeias?act=successadd');
										$this->_redirect('/ministryowner/ministryschemeadd?actmsg=saved&'.$qstring);
                                        
                                        
                                        
                                        
                                        
                    // if($dataform["save"]){
						// $ministryowner->SaveRecord($dataform,"save");
					// }else if($dataform["submit"]){
						// $ministryowner->SaveRecord($dataform,"submit");
						// $this->_redirect('ministryowner/viewschemeias?act=successadd');
					// }else{
						
					// }
                                        
                                }
                                
                                
				}else{
				}
			}
		}
	}
        }
	
	
	public function deletesavedfileAction(){
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
        }else{

			$request = $this->getRequest();

			$phaseid = safexss($request->getParam("phaseid"));
			$ministryid = safexss($request->getParam("ministryid"));
			$fileid = safexss($request->getParam("fileid"));
			$filename = safexss($request->getParam("filename"));

			$this->view->assign("phaseid",$request->getParam("phaseid"));
			$this->view->assign("ministryid",$request->getParam("ministryid"));
			$this->view->assign("fileid",$request->getParam("fileid"));
			$this->view->assign("filename",$request->getParam("filename"));

			if($request->isPost()){
				$dataform=$request->getPost();
				if($dataform['yes']=='yes'){
					$ministryowner = new Application_Model_Ministryowner;
					$match = $ministryowner->deletesavedfile(base64_decode($fileid));
					if($match) {
						unlink($_SERVER["DOCUMENT_ROOT"].'dbt/data/ministryowner/'.base64_decode($filename));
						$this->_redirect('/ministryowner/ministryschemeadd?phaseone='.$phaseid.'&ministryid='.$ministryid);
					} else {
						$this->_redirect('/ministryowner/ministryschemeadd?phaseone='.$phaseid.'&ministryid='.$ministryid);
					}
				} else {
					$this->_redirect('/ministryowner/ministryschemeadd?phaseone='.$phaseid.'&ministryid='.$ministryid);
				}
			}

		}
	}
	
	
	
	
	
	
	public function ajaxministryschemeaddAction(){
		
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
        }else{			
				$ministryowner = new Application_Model_Ministryowner;
				$request = $this->getRequest();
				if(!$request->getParam("phaseone") && !$request->getParam("ministryid")){
					echo "Unable to save records!";exit;
				}else{
					
			if($request->isPost()){
				$dataform=$request->getPost();
				
				
				
				if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['phase_id'])) {
						//return TRUE;
						$dataform['phase_id'] = base64_decode($dataform['phase_id']);
						
					} else {
						$dataform['phase_id'] = $dataform['phase_id'];
					}
				if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $dataform['ministry_id'])) {
						//return TRUE;
						//echo "aaaa".$dataform['ministry_id'];exit;
						$dataform['ministry_id'] = base64_decode($dataform['ministry_id']);
						
					} else {
						//echo "bbb";exit;
						$dataform['ministry_id'] = $dataform['ministry_id'];
						
					}	
					
				//echo "<pre>";
				//print_r($dataform);
				//exit;	
					
				//$dataform['phase_id'] = base64_decode($dataform['phase_id']);
				//$dataform['ministry_id'] = base64_decode($dataform['ministry_id']);
				
				
					$nameofscheme = $ministryowner->selectSchemeName($dataform['phase_id'],$dataform['ministry_id']);
				
					$dataform["name_of_scheme"] = $nameofscheme[0]['scheme_name'];
						//echo $dataform["name_of_scheme"];exit;
						if($dataform["name_of_scheme"]!= ''){
							$dataform["phase_id"] = base64_encode($dataform["phase_id"]);
							
							/*****insert in to the audit log*****************/
							$decodePhaseid = base64_decode($request->getParam("phaseone"));
							$decodeMinistryid = base64_decode($request->getParam("ministryid"));
							$ministrynamenew = $ministryowner->getministryname($decodeMinistryid);
							$schemenamenew  = $ministryowner->selectSchemeName($decodePhaseid,$decodeMinistryid);
							$ministryname  = $ministrynamenew[0]['ministry_name'];
							$schemename  = $schemenamenew[0]['scheme_name'];
							if($dataform['type_of_scheme'] == 1)
							{
							$type_of_scheme = 'Central Sector';
							}
							else if($dataform['type_of_scheme'] == 2)
							{
							$type_of_scheme = 'Centrally Sponsored';
							}
							if($dataform['digitized_beneficiary_status'] == 1)
							{
							$digitized_beneficiary_status = 'Yes';
							}
							else if($dataform['digitized_beneficiary_status'] == 2)
							{
							$digitized_beneficiary_status = 'No';
							}
							if($dataform['mis_portal_status'] == 1)
							{
							$mis_portal_scheme = 'Yes';
							}
							else if($dataform['mis_portal_status'] == 2)
							{
							$mis_portal_scheme = 'No';
							}

							if($dataform['type_of_benefit'] == 1)
							{
							$type_of_benefit = 'Cash';
							}
							else if($dataform['type_of_benefit'] == 2)
							{
							$type_of_benefit = 'In Kind';
							}
							else if($dataform['type_of_benefit'] == 3)
							{
							$type_of_benefit = 'Other Transfers';
							}



							if($dataform['pfms_payment'] == 1)
							{
							$pfmspayment  = 'Yes';
							}
							else if($dataform['pfms_payment'] == 2)
							{
							$pfmspayment = 'No';
							}



							if($dataform['mode_of_payment'] == 1)
							{
							$mode_of_payment  = 'APBS';
							}
							else if($dataform['mode_of_payment'] == 2)
							{
							$mode_of_payment = 'AEPS';
							}else if($dataform['mode_of_payment'] == 3)
							{
							$mode_of_payment = 'NACH';
							}else if($dataform['mode_of_payment'] == 4)
							{
							$mode_of_payment = 'OTHERS';
							}


							$schemeidobj = new Application_Model_Auditlog();
							$description = '';	
							$description .= 'Form Two Save</br>';	
							$description .= 'Ministry Name: '.$ministryname.'</br>';						
							$description .= 'Scheme Name: '.$schemename.'</br>';
							if($type_of_scheme!='')
							{
							$description .= 'Type of the Scheme: '.$type_of_scheme.'</br>';
							}
							if($dataform['fund_allocation']!='')
							{
							$description .= 'Fund Allocated for the Scheme: '.$dataform['fund_allocation'].'</br>';
							}
							if($dataform['implemeting_agency']!='')
							{
							$description .= 'Implementing Agency (if any): '.$dataform['implemeting_agency'].'</br>';
							}
							if($dataform['target_beneficiary']!='')
							{
							$description .= 'Target Beneficiaries: '.$dataform['target_beneficiary'].'</br>';
							}
							if($dataform['total_eligble_beneficiary']!='')
							{
							$description .= 'Total Number of Eligible Beneficiaries : '.$dataform['total_eligble_beneficiary'].'</br>';
							}
							if($digitized_beneficiary_status!='')
							{
							$description .= 'Digitized Beneficiary Database (BD) in place:'.$digitized_beneficiary_status.'</br>';
							}
							if($mis_portal_scheme!='')
							{
							$description .= 'MIS Portal in Place for the scheme: '.$mis_portal_scheme.'</br>';
							}
							if($dataform['aadhar_seeding_bd']!='')
							{
							$description .= 'Aadhaar Seeding in BD: '.$dataform['aadhar_seeding_bd'].'</br>';
							}
							if($dataform['bank_account_bd']!='')
							{
							$description .= 'Bank Accounts Number in BD: '.$dataform['bank_account_bd'].'</br>';
							}
							if($dataform['mobile_number_bd']!='')
							{
							$description .= 'Mobile Number in BD: '.$dataform['mobile_number_bd'].'</br>';
							}
							if($dataform['aadhar_linkage_account']!='')
							{
							$description .= 'Aadhaar linkage with Bank Account: '.$dataform['aadhar_linkage_account'].'</br>';
							}
							if($type_of_benefit!='')
							{
							//$description .= 'Scheme Description:'.$dataform['scheme_description'].'</br>';
							$description .= 'Type of Benefits:'.$type_of_benefit.'</br>';
							}
							if($dataform['details_of_benefit']!='')
							{
							$description .= 'Details of Benefit:'.$dataform['details_of_benefit'].'</br>';
							}
							//$description .= 'Description of Process flow:'.$dataform['process_flow_description'].'</br>';
							if($pfmspayment!='')
							{
							$description .= 'Payment Linked to PFMS:'.$pfmspayment.'</br>';
							}
							if($mode_of_payment!='')
							{
							$description .= 'Mode of Payment (if applicable):'.$mode_of_payment.'</br>';
							}
							$auditlog = array(
							"uid" => $userid->userid,
							"application_type" => 'DBT Applicability Assessment',
							"description" => $description
							); 
							$schemeidobj->insertauditlog($auditlog);
							/****************audit log end*************************************/
							$ministryowner->SaveRecord($dataform,"save");
							echo "Data has been successfully saved!";exit;
						}else{
							echo "You have entered wrong information!\n Please refresh the page and enter the data again.";exit;
						}
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function viewschemeiasAction(){
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		$ministryid = new Zend_Session_Namespace("ministryid");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
		}else{
			if(!$ministryid->ministryid){
				$this->_redirect("ministryowner/index?msg='notminid'");
			}
			$ministryowner = new Application_Model_Ministryowner;
			//$result = $ministryowner->PhaseoneReport($ministryid->ministryid,$userid->userid);
			$result = $ministryowner->PhasetwoReport($ministryid->ministryid,$userid->userid);
			
			//print_r($result);exit;
				$this->view->assign("resultant",$result);
		}
		
	}
	public function activitycellAction(){
	
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		$ministryid = new Zend_Session_Namespace("ministryid");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
		}else{
		}
	}
	public function ministryownereditAction()
    {

       $request = $this->getRequest();
		$id = base64_decode($request->getParam('id'));
		$Contactusobj = new Application_Model_Ministryowner;
		$ministryownerrecrd = $Contactusobj->listministryowner($id);
		$this->view->assign("ministryownerrecrd",$ministryownerrecrd);



   }
   
   public function ministryeditAction()
    {
		
		//print_r($_POST); die;
		$this->_helper->layout->disableLayout();
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();	
		$id =  $_POST['ministryownerid'];
		$scheme_name =  $_POST['schemename'];
		$dbt_eligible =  $_POST['dbt-eligible'];
		$benefit_type =  $_POST['benefit-type'];
		$eligibility_type = $_POST['eligibility-type'];
		$specific_reason =  $_POST['specific-reason'];
		 /**********vallidation to check captcha code 26th july ************/
			if($_POST['vercode']!= $_SESSION["vercode"])
			{
				 echo "Please enter a correct code!";
				   die;
			}

			if($_POST['sessionCheck']!=$captcha->captcha)
			{
			   echo  CSRF_ATTACK;
			   die;
			}		
	   /*****************end***********************/
		/* if($scheme_name == '')
		{
			echo "Please enter a scheme name!"; 
			die;
		}
		if (preg_match("/[^A-Za-z0-9 _ ,&-]/", $scheme_name))
		{
			echo "Invalid Characters in Scheme Name!";
			die;
		}	
		if(trim($scheme_name) == '')
		{
		   echo "White Space are not allowed in Scheme Name!";die;
		}
 */
		if($dbt_eligible == 0)
		{
			echo "Please enter a DBT Applicability";
			die;
		}
		if(($benefit_type == 0) && ($dbt_eligible == 1))
		{
			echo "Please enter a Benefit Type!";
			die;
		}
		if($dbt_eligible == 1)
		{
		if($eligibility_type == '')
		{
			echo "Please enter a DBT Applicability Type!";
			die;
		}
		}
		if($dbt_eligible == 2)
		{
		if($specific_reason == '')
		{
			echo "Please enter a Specific Reason!";
			die;
		}
		if(ltrim($specific_reason)!= $specific_reason) 
		{
			echo "White Space are not allowed in Specific Reason!";
			die;
		}

		if(preg_match("/[^a-zA-Z0-9~`!#$^*_:?()@., \"\'-\n\r]/", $specific_reason))
		{
			echo "Invalid Characters In Specific Reason!";
			die;
		}

		}
		
		$Contactusobj = new Application_Model_Ministryowner;
		$ministryownerrecrd = $Contactusobj->listministryowner($id);
		$eligibiltytype = $ministryownerrecrd[0]['dbt_eligibility'];
		//print_r($_POST); die;
		if($eligibiltytype  == 2)
		{
				
		$match  = $Contactusobj->ministryowneredit($_POST,$id);
		}
		else
		{
			echo 0;
			die;
		}
		     if($match)
				{
					
					/****insert in to the audit log******/
					$userid = new Zend_Session_Namespace("userid");
					$ministryid = new Zend_Session_Namespace('ministryid');
					$ministrynamenew  = $Contactusobj->getministryname($ministryid->ministryid);
					$ministryname  = $ministrynamenew[0]['ministry_name'];
					if($dbt_eligible == 1)
						{
						  $dbt_applicabilty = 'Yes';
						}
						else if($dbt_eligible == 2)
						{
						   $dbt_applicabilty = 'No';
						}

						if($benefit_type == 1)
						{
						  $type_of_benefit = 'Cash';
						}
						else if($benefit_type == 2)
						{
						   $type_of_benefit = 'In Kind';
						}
						else if($benefit_type == 3)
						{
						  $type_of_benefit = 'Other Transfers';
						}


						if($eligibility_type == 1)
						{
						   $eligibilityType = 'Individual';
						}
						else if($eligibility_type == 2)
						{
						   $eligibilityType = 'HouseHold';
						}
						else if($eligibility_type == 3)
						{
						   $eligibilityType = 'ServiceEnablers';
						}

						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
						$description .= 'Edit Form One</br>';	
						$description .= 'Ministry Name: '.$ministryname.'</br>';						
						$description .= 'Scheme Name: '.$_POST['schemenamenew'].'</br>';
						$description .= 'DBT Applicability: '.$dbt_applicabilty.'</br>';
						if($dbt_eligible == 1)
						{
						$eligibleType = array();
						foreach($eligibility_type as $key=>$val)
						{
						if($val == 1)
						{
						$eligibleType[] = 'Individual'; 
						}
						if($val == 2)
						{
						$eligibleType[] = 'HouseHold'; 
						}
						if($val == 3)
						{
						$eligibleType[] = 'ServiceEnablers'; 
						}
						}					
						$eligibletype = implode(",",$eligibleType);
						$description .= 'Type of Benefit: '.$type_of_benefit.'</br>';
						$description .= 'Eligibilty Type: '.$eligibletype.'</br>';
						}
						else if($dbt_eligible == 2)
						{
						$description .= 'Specify Reason: '.$specific_reason.'</br>';
						}
						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'DBT Applicability Assessment',
						"description" => $description
						); 
						$schemeidobj->insertauditlog($auditlog);
				     /*****************audit log end*******************/
					echo 1;
					die;
				}
	}
	public function viewschemeaddedAction(){
		$userid = new Zend_Session_Namespace("userid");
		$role = new Zend_Session_Namespace("role");
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
        }else{
			$form = new Application_Form_Ministryschemeview();
			$ministryowner = new Application_Model_Ministryowner;
			//echo "Aaaassssss";exit;
			$form->addform();
			$request = $this->getRequest();
			if(!$request->getParam("phaseone") && !$request->getParam("ministryid")){
				$this->_redirect("");
			}else{
				
				$decodePhaseid = safexss(base64_decode($request->getParam("phaseone")));
				$decodeMinistryid = safexss(base64_decode($request->getParam("ministryid")));
				
				$this->view->assign("phase_id",$request->getParam("phaseone"));
				$this->view->assign("ministryid",$request->getParam("ministryid"));
				
				$submitted = $ministryowner->SelectForViewSubmit($decodePhaseid,$decodeMinistryid);
				
				$nameofschemepop = $ministryowner->selectSchemeNamePop($decodePhaseid,$decodeMinistryid);
                                //print_r($nameofschemepop);die;
				if(count($nameofschemepop) > 0 ){
					$form->populate($nameofschemepop);
				}else{		
					$nameofscheme = $ministryowner->selectSchemeName($decodePhaseid,$decodeMinistryid);
					$somedata = array("name_of_scheme"=>$nameofscheme[0]['scheme_name'],
					"ministry_id"=>$request->getParam("ministryid"),
					"phase_id"=>$request->getParam("phaseone"));
				$form->populate($somedata);
				}
			}
			
			$this->view->form = $form;
                        $this->view->assign('filename', $nameofschemepop['filename']);
		}
		
	}

 
       /******************** validation: File Upload *************************/
        function fileUploadValidation($files,$fileFormat,$allow_extension_only){
           //print_r($files);
            $filename = $files['uploadfile']['name'];
            $fieltempval = 0;
            //$allow_extension_only=array('application/pdf');
            if((count(explode('.',$filename))>2)||(preg_match("/[\/|~|`|;|:|]/", $filename))){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%0A\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%0D\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%22\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%27\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%3C\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%3E\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%00\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%3b\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%3d\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%29\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%28\b/i", $filename)){
                 $fieltempval = 1;
            }elseif(preg_match("/\b%20\b/i", $filename)){
                 $fieltempval = 1;
            }

            if(in_array(end( explode ( '.', $filename)), $fileFormat) && $fieltempval == 0){
                    //$target_path = $_SERVER['DOCUMENT_ROOT'].BASE_PATH.'/data/ministryowner/';
                    $data = file_get_contents($files['uploadfile']['tmp_name']);
                    $dataCheck = substr($data,0,2);	
                    if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == "" )
                    {
                        //$this->_redirect('/ministryowner/ministryschemeadd?actmsg=fileformaterror&'.$qstring);
                        $fieltempval = 1;
                    } else {
                        $fieltempval = 0;   
                   }
                }else{
                   $fieltempval = 1;
                }
                return $fieltempval;
            }
/******************** validation: File Upload *************************/
	//DBT Applicability assessment validation code in case of save.
		function customValidation($dataform){
			$Err = 0;
			if ((!preg_match("/^[a-zA-Z0-9., \'-]{0,}$/i",$dataform['fund_disburse_description'])) && ($dataform['fund_disburse_description'] != "") && ($Err == 0)) {
				$Err = "Description of Fund disbursement mechanism field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-zA-Z0-9., \'-]{0,}$/i",$dataform['process_flow_description'])) && ($dataform['process_flow_description'] != "") && ($Err == 0)) {
				$Err = "Process flow Description field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-zA-Z0-9., \'-]{0,}$/i",$dataform['details_of_benefit'])) && ($dataform['details_of_benefit'] != "") && ($Err == 0)) {
				$Err = "Details of Benefit field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-zA-Z0-9., \'-]{0,}$/i",$dataform['scheme_description'])) && ($dataform['scheme_description'] != "") && ($Err == 0)) {
				$Err = "Scheme Description field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9.]{0,}$/i",$dataform['aadhar_linkage_account'])) && ($dataform['aadhar_linkage_account'] != "") && ($Err == 0)) {
				$Err = "Aadhaar linkage with Bank Account field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9.]{0,}$/i",$dataform['mobile_number_bd'])) && ($dataform['mobile_number_bd'] != "") && ($Err == 0)) {
				$Err = "Mobile Number in BD field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9.]{0,}$/i",$dataform['bank_account_bd'])) && ($dataform['bank_account_bd'] != "") && ($Err == 0)) {
				$Err = "Bank Accounts Number field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9.]{0,}$/i",$dataform['aadhar_seeding_bd'])) && ($dataform['aadhar_seeding_bd'] != "") && ($Err == 0)) {
				$Err = "Aadhaar Seeding field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-z][a-z0-9()@., \'-]{0,}$/i",$dataform['details_of_actions_init'])) && ($dataform['details_of_actions_init'] != "") && ($Err == 0)) {
				$Err = "Details of action initiated field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-z][a-z0-9()@., \'-]{0,}$/i",$dataform['digitized_details_of_act'])) && ($dataform['digitized_details_of_act'] != "") && ($Err == 0)) {
				$Err = "Details of action being taken field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9]{0,}$/i",$dataform['total_eligble_beneficiary'])) && ($dataform['total_eligble_beneficiary'] != "") && ($Err == 0)) {
				$Err = "Total Eligible beneficiary field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9]{0,}$/i",$dataform['target_beneficiary'])) && ($dataform['target_beneficiary'] != "") && ($Err == 0)) {
				$Err = "Target Beneficiary field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[a-z][a-z0-9()@., \'-]{0,}$/i",$dataform['implemeting_agency'])) && ($dataform['implemeting_agency'] != "") && ($Err == 0)) {
				$Err = "Implementing Agency field has special characters.Please remove them and try again.";
			}
			if ((!preg_match("/^[0-9]{0,}$/i",$dataform['fund_allocation'])) && ($dataform['fund_allocation'] != "") && ($Err == 0)) {
				$Err = "Fund Allocated field has special characters.Please remove them and try again.";
			}
			return $Err;
		}
                
                
          /********************Used for admin - Generate Report ******************/      
    public function phaseonereportAction()
    {
	        ob_start();
                $search ="";
                $search = intval($_GET['m_type']);
				 $eligible_type = intval($_GET['eligible_type']);
                //echo "------------:".$search;
                $this->view->assign('search', $search);
                $this->_helper->layout->setLayout('admin/layout');
		$Contactusobj = new Application_Model_Ministryowner;
		$tempmin = $Contactusobj->listministry();
		$this->view->assign('ministrytempdata',$tempmin);
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                     $this->_redirect('');
                }
                $rolearraylist=array('1','3');
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$rolearraylist)){
                    $this->_redirect('');
                }
		$form = new Application_Form_MinistryOwner();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		$ministryid = new Zend_Session_Namespace('ministryid');
		/********* code for view scheme ias page 14th aug*****/
		/*******code for paging*********/
			if(isset($start))
				{                         // This variable is set to zero for the first page
				$start = 0;
			
				}
				else
				{
				  $start=$request->getParam('start');
				 
				}

			$page=0;


			$limit=10;
		
		
		/************end***********/
		$userid = new Zend_Session_Namespace("userid");
		$Contactusobj = new Application_Model_Ministryowner;
                $ministry=$Contactusobj->getMinistry($search,$table='dbt_ministry_owner');
                $this->view->assign("ministry_array",$ministry);
                //echo "<pre>";
                //print_r($ministry);
                //echo "</pre>";
                //die;
		$result = $Contactusobj->PhaseoneReport($ministryid->ministryid,$userid->userid,$search,$eligible_type);
		//echo $eligible_type;
		 /* echo "<pre>";
		print_r($result);
		die;  */
		$result_count = $Contactusobj->countPhaseoneReport($ministryid->ministryid,$userid->userid,$search,$eligible_type);
		$this->view->assign("result_count",$result_count);
		$this->view->assign("resultant",$result);
		/************end**************/
	
		if ($this->getRequest()->isPost()) {
				
			   $dataform=$request->getPost();
			    
				$Contactusobj = new Application_Model_Ministryowner;
				
				
				 /**********vallidation to check captcha code 26th july ************/
				   		if($_POST['vercode']!= $_SESSION["vercode"])
						{
							 echo "Please enter a correct code!";
							   die;
						}

						if($_POST['sessionCheck']!=$captcha->captcha)
						{
						   echo  CSRF_ATTACK;
						   die;
						}
					
			        /*****************end***********************/
				
				/*******validation for ministry owner form ***********/
				 $len =  $_POST['uniquidnew'];
				 //echo $len; die;
				 for ($x = 1; $x <= $len; $x++) {
					 if(isset($_POST['scheme-name-'.$x])) {
                     $scheme_name =  $_POST['scheme-name-'.$x];
					$dbt_eligible =  $_POST['dbt-eligible-'.$x];
					$benefit_type =  $_POST['benefit-type-'.$x];
					$eligibility_type = $dataform['eligibility-type-'.$x];
					$specific_reason =  $_POST['specific-reason-'.$x];
					//$result = $Contactusobj->checkcontentschemename($scheme_name);
                                        $result = $Contactusobj->checkcontentschemename($scheme_name,$ministryid->ministryid);
					//echo $result; die;
					if($result == 1)
					{
						echo "This scheme name is allready exists in this ministry!"; 
						die;
					}
					
					if($scheme_name == '')
					{
						echo "Please enter a scheme name!"; 
						die;
					}
					if (preg_match("/[^A-Za-z0-9 _ ,&-]/", $scheme_name))
						{
							echo "Invalid Characters in Scheme Name!";die;
						}	
					if(trim($scheme_name) == '')
					{
					 echo "White Space are not allowed in Scheme Name!";die;
					}
		
					if($dbt_eligible == 0)
					{
						echo "Please enter a DBT Applicability!";
						die;
					}
					if(($benefit_type == 0) && ($dbt_eligible == 1))
					{
						echo "Please enter a Benefit Type!";
						die;
					}
					if($dbt_eligible == 1)
					{
						if($eligibility_type == '')
						{
						echo "Please enter a DBT Applicability Type!";
						die;
						}
					}
					if($dbt_eligible == 2)
					{
						if($specific_reason == '')
						{
						echo "Please enter a Specific Reason!";
						die;
						}
						if(ltrim($specific_reason)!= $specific_reason) 
						{
							echo "White Space are not allowed in Specific Reason!";
							die;
						}
						
						
					 if(preg_match("/[^A-Za-z0-9 _ ,.\'-]/", $specific_reason))
						{
							echo "Invalid Characters In Specific Reason!";
							die;
						}
						
					}
				 }
					
                 }
				
				
				/**************end******************/
				$match  = $Contactusobj->insministryowner($_POST,$ministryid->ministryid);
				//echo "<pre>"; print_r($match); exit;
				
				if($match)
				{
					echo 1;
					//$this->_redirect('/ministryowner/viewschemeias?act=successadd');
					die;
				}
				//echo "<pre>"; print_r($match); exit;
				 //$countdata = $Contactusobj->checkcontent($dataform['title']);	
				  /**********vallidation to check captcha code 26th july ************/
					
			       /*****************end***********************/
								
		
	} 
	}   
           /***********************************************************************/
		   
		   /*********new export method********************************************/
		   public function ministryownerpahasetwoexportAction(){
			   	$output_array=array();
				$k=0;
				$output_array[$k]=array(
						'sno'=>'SNo',
						'ministry_department'=>'Ministries/Department',
						'name_of_scheme' => 'Name of the Scheme',
						'type_of_scheme' => 'Type of the Scheme',
						'type_of_benefit' => 'Type of Benefit',
						'description_of_scheme' => 'Description of the Scheme',
                        'fund_allocated' => 'Fund Allocated',
                       'description_of_the_process_flow' => 'Description of the Process Flow',
                       'total_target_beneficiaries' => 'Total number of Target  Beneficaries',
                       'total_eligible_beneficiaries' => 'Total Number of Eligible Beneficiaries',
                       'digitized_database_beneficiaries' => 'Digitized Database for Beneficiaries',
                       'mis_portal_beneficiaries_database' => 'MIS Portal of Beneficiary Database',
	                 	'aadhar_seeding_beeding' => 'Aadhaar Seeding in BD',
                       'bank_account_number_bd' => 'Bank Account Number in BD',
                       'mobile_num_bd' => 'Mobile Number in BD',
	                   'aadhar_linking_bank_details' => 'Aadhaar linkage with  Bank Details',
                        'mode_of_payment' => 'Mode of Payment',
                       'integrated_pfms' => 'Integrated with  PFMS',	
					   	'last_updated'  => 'Last Updated(dd/mm/yyyy hh:mm:ss)',
					);
				$search ="";
				$search = intval($_GET['m_type']);
				// echo $_GET['savetype']; exit;
				if($_GET['savetype'] == 0)
				{
				  $savetype = $_GET['savetype'];
				}
				else if($_GET['savetype'] == 1)
				{
                  $savetype = $_GET['savetype'];
				} else {
					$savetype = 1;
				}
				$this->view->assign('savetype', $savetype);
				
				$this->view->assign('search', $search);
				$ministryowner = new Application_Model_Ministryowner;
				$result = $ministryowner->PhaseoneReportcsvphasetwo($search,$savetype);
				foreach($result as $val)
				{
					$k++;
					if($val['digitized_beneficiary_status'] == 1){
					$digitized_beneficiary_status = "Yes";
					}else if($val['digitized_beneficiary_status'] == 2){
					$digitized_beneficiary_status =  "No";
					}
					else if(empty($val['digitized_beneficiary_status'])){
					$digitized_beneficiary_status =  "Not Found";
					}
					if($val['mis_portal_status'] == 1){
					$mis_portal_status = "Yes";
					}else if($val['mis_portal_status'] == 2){
					$mis_portal_status =  "No";
					}
					else if(empty($val['mis_portal_status'])){
					$mis_portal_status =  "Not Found";
					}
					if($val['mode_of_payment'] == 1){
					$mode_of_payment = "APBS";
					}else if($val['mode_of_payment'] == 2){
					$mode_of_payment =  "AEPS";
					}else if($val['mode_of_payment'] == 3){
					$mode_of_payment =  "NACH";
					}else if($val['mode_of_payment'] == 4){
					$mode_of_payment =  "OTHERS";
					}
					else if(empty($val['mode_of_payment'])){
					$mode_of_payment =  "Not Found";
					}
					if($val['pfms_payment'] == 1){
					$pfms_payment = "Yes";
					}else if($val['pfms_payment'] == 2){
					$pfms_payment =  "No";
					}else if(empty($val['pfms_payment'])){
					$pfms_payment =  "Not Found";
					}
				   $updatedate = $ministryowner->changedateformat($val['updated']);
					$output_array[$k]=array(
					'sno'=>$k,
					'ministry_department'=> $val['ministryname'],
					'name_of_scheme'=> $val['name_of_scheme'],
					'type_of_scheme' => $val['type_of_scheme'],
					'type_of_benefit' => $val['type_of_benefit'],
					'description_of_scheme' => $val['scheme_description'],
					'fund_allocated' => $val['fund_allocation'],
					'description_of_the_process_flow' => $val['process_flow_description'],
					'total_target_beneficiaries' => $val['target_beneficiary'],
					'total_eligible_beneficiaries' => $val['total_eligble_beneficiary'],
					'digitized_database_beneficiaries' => $digitized_beneficiary_status,
					'mis_portal_beneficiaries_database' => $mis_portal_status,
					'aadhar_seeding_beeding' => $val['aadhar_seeding_bd'],
					'bank_account_number_bd' => $val['bank_account_number_bd'],
					'mobile_num_bd' => $val['mobile_number_bd'],
					'aadhar_linking_bank_details' => $val['aadhar_linkage_account'],
					'mode_of_payment' => $mode_of_payment,
                     'integrated_pfms' => $pfms_payment,
                     'last_updated' => $updatedate,
					);
				}
					/**======================================= Code for export Starts====================================**/
				if(trim($_REQUEST['downsection'])=="excel")
				{
				$ExcelName="excel".rand(0,999999).".php";
				$requireDataModel=$this->exporToExcel($output_array);

				$getData2=json_encode($requireDataModel);
				$pathVal=APPLICATION_PATH."/excel/".$ExcelName;

				$fp=fopen($pathVal,"w");
				fwrite($fp,$getData2);
				fclose($fp);

				$callPath="";
				if(isset($_SERVER['HTTPS'])){
				$callPath = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
				}
				else{
				$callPath= 'http';
				}
				$callPath.="://".$_SERVER['HTTP_HOST']."/dbtliveserver/application/excel/excelWork.php?curFile=".$ExcelName ;
				header("location: $callPath");;
				exit;
				}

		/**======================================== Code for export Ends===================================**/
			   
		   }
		 /*======Phase one report==============**/
		 
		   public function ministryownerpahaseoneexportAction(){
			   	$output_array=array();
				$k=0;
				$output_array[$k]=array(
						'sno'=>'SNo',
						'ministry_department'=>'Ministries/Department',
						'name_of_scheme' => 'Name of the Scheme',
						'dbt_applicability' => 'DBT Applicability (Yes/No)',
						'type_of_benefit' => 'Type of Benefit',
						'dbt_applicability_type' => 'DBT Applicability Type',
                        'remarks' => 'Remarks',
                       'last_updated' => 'Last Updated(dd/mm/yyyy hh:mm:ss)'
					);
				$search ="";
				$eligible_type ="";
				$search = intval($_GET['m_type']);
				$eligible_type = intval($_GET['eligible_type']);
				$this->view->assign('search', $search);
				$ministryowner = new Application_Model_Ministryowner;
				//$result = $ministryowner->PhaseoneReportcsvphasetwo($search);
                $result = $ministryowner->PhaseoneReportcsv($search,$eligible_type);
				/* echo "<pre>";
				print_r($result);
				die; */
              
				foreach($result as $val)
				{
					
					$k++;
				    $minname = $val['ministryname'];
                    $schemename = $val['scheme_name'];
                  if($val["specific_reason"])
					{
					$specificreason = $val["specific_reason"];
					}else
					{
					$specificreason = "N/A";
					}
					if($val['dbt_eligibility'] == 1){
					$dbt_eligibility = "Yes";
					}else if($val['dbt_eligibility'] == 2){
					$dbt_eligibility =  "No";
					}
					if($val['benefit_type'] == 1){
					$benefit_type = "Cash";
					}else if($val['benefit_type'] == 2){
					$benefit_type = "In Kind";
					}else if($val['benefit_type'] == 3){
					$benefit_type = "Other Transfers";
					}
					else
					{
					$benefit_type = "N/A";
					}
					$seligibletype=array();
					  $updatedate = $ministryowner->changedateformat($val['updated']);
                   //$eligibilitytype = explode(",",$val['dbt_eligibility_type']);
					
					foreach (explode(",",$val['dbt_eligibility_type']) as $valnn) {
						//$seligibletype.='tt';
							if($valnn== 1){
								$seligibletype[$valnn]=  "Individual ";
							}else if($valnn == 2){
								$seligibletype[$valnn]= "HouseHold ";
							}else if($valnn == 3){
								$seligibletype[$valnn]= "Service Enablers ";
							}else{
								$seligibletype[$valnn]= "N/A ";
							}
					}
				
				   $updatedate = $ministryowner->changedateformat($val['updated']);
					$output_array[$k]=array(
					'sno'=>$k,
					'ministry_department'=> $minname,
					'name_of_scheme'=> $schemename,
					'dbt_applicability' => $dbt_eligibility,
					'type_of_benefit' => $benefit_type,
					'dbt_applicability_type' => implode(",",$seligibletype),
					'remarks' => $specificreason,
					'last_updated' => $updatedate,
					 
					);
				}
				//die;
				/* print_r($output_array);
                  die; */
					/**======================================= Code for export Starts====================================**/
				if(trim($_REQUEST['downsection'])=="excel")
				{
				$ExcelName="excel".rand(0,999999).".php";
				$requireDataModel=$this->exporToExcelphaseone($output_array);

				$getData2=json_encode($requireDataModel);
				$pathVal=APPLICATION_PATH."/excel/".$ExcelName;

				$fp=fopen($pathVal,"w");
				fwrite($fp,$getData2);
				fclose($fp);

				$callPath="";
				if(isset($_SERVER['HTTPS'])){
				$callPath = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
				}
				else{
				$callPath= 'http';
				}
				$callPath.="://".$_SERVER['HTTP_HOST']."/dbtliveserver/application/excel/excelWork.php?curFile=".$ExcelName ;
				header("location: $callPath");;
				exit;
				}

		/**======================================== Code for export Ends===================================**/
			   
		   }
		 
		 
		 
		 /***==end============================***/
		 
		 /**===== Generate Functions Starts=====**/
		public function exporToExcelphaseone($arr)
		{
			array_push($margeAllData,$arr);
			//$get_scheme_name=$this->getNameScheme.".xlsx";
            $currentdate = date('d/m/y');
			$get_scheme_name="phaseone-report-".$currentdate.".xlsx";
			$excelArrData["fileName"]=$get_scheme_name;
			$excelArrData["excelTitle"]="Phaseone Report";
			$excelArrData["topHeader"]="Comprehensive list of all the schemes";
			//$excelArrData["topReportArr"]=array("Financial Year :2016_17");
			$excelArrData["topReportArr"]=array("");
			$excelArrData["data"]=$arr;
			return $excelArrData;

		}// makeDataFormate($arr)

		/**===== Generate Functions Ends=====**/
		/**===== Generate Functions Starts=====**/
		public function exporToExcel($arr)
		{
			array_push($margeAllData,$arr);
			//$get_scheme_name=$this->getNameScheme.".xlsx";
            $currentdate = date('d/m/y');
			$get_scheme_name="phasetwo-report-".$currentdate.".xlsx";
			$excelArrData["fileName"]=$get_scheme_name;
			$excelArrData["excelTitle"]="Phasetwo Report";
			$excelArrData["topHeader"]="Detailed Analysis  of the Scheme";
			//$excelArrData["topReportArr"]=array("Financial Year :2016_17");
			$excelArrData["topReportArr"]=array("");
			$excelArrData["data"]=$arr;
			return $excelArrData;

		}// makeDataFormate($arr)

		/**===== Generate Functions Ends=====**/
		   
		   /************end******************************************************/
		   
		   
		   /*********phase one view page**************/
		   public function phaseoneAction()
		   {
			   
			   $this->_helper->layout->setLayout('admin/layout');
				$admname = new Zend_Session_Namespace('adminMname'); 
				$userid = new Zend_Session_Namespace('userid');
				if($admname->adminname==''){
				$this->_redirect('');
				}
				$role = new Zend_Session_Namespace('role');	
		
				if(!in_array($role->role,$this->rolearraynew)){
				$this->_redirect('');
				}	

				$request = $this->getRequest();
				$cmi_list = new Application_Model_Ministryowner;

				if($request->getParam('actmsg')=='add'){
				$this->view->assign('successMsg', RECORD_INSERTED);
				}elseif($request->getParam('actmsg')=='edit'){
				$this->view->assign('successMsg', RECORD_UPDATED);
				}elseif($request->getParam('actmsg')=='del'){
				$this->view->assign('successMsg', RECORD_DELETED);
				}elseif($request->getParam('actmsg')=='inactivate'){
				$this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
				}

				if(isset($start))
				{                         // This variable is set to zero for the first page
				$start = 0;

				}
				else
				{
				$start=$request->getParam('start');

				}

				$page=0;


				$limit=10;

				//echo "<pre>";  print_r($transferengineer[0]);die;
				$cmishow_list = $cmi_list->phaseoneschemelist($start,$limit);
				$countcmi = $cmi_list->phaseoneschemelistcount();	
			
				$this->view->assign('cmidata', $cmishow_list);
				$pagination1=$this->pagination_search($countcmi,$start,$limit);
				$this->view->assign('pagination', $pagination1);
				$this->view->assign('start', $start);
				$this->view->assign('counttotalcmireports', $countcmi);
		   }
		   
		   
		   	public function pagination_search($nume,$start,$limit)
					{
		
							if($nume > $limit)
							{
							$page_name ='phaseone?search='.$_GET['search'];
							$this1 = $start + $limit; 
							$back = $start - $limit; 
							$next = $start + $limit;
							
							$paginate="";
							$paginate.='<ul class="pagination">';
							if($back >=0)
							{
							$paginate.='<li><a href="'.$page_name.'&start=0" class="head2">&lt; FIRST</a></li>';
							$paginate.='<li><a href="'.$page_name.'&start='.$back.'" class="head2">&lt; PREV</a></li>';
							}
							//if($nume <= 100){$start = 0;}
							$i=$start;
							$l=$start/10;
							if($l < 1) {$l = 1;} else {$l = $l+1;}
							$countnum = $l + 10;
							if($nume > 100){
							for($i=$start;$i < $nume;$i=$i+$limit)
							{
							if($countnum > $l) {
							if($i <> $start)
							{
							$paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
							}
							else
							{
							$paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
							}
							}
							$l=$l+1;

							}
							} else {
							$l = 1;
							for($i=0;$i < $nume;$i=$i+$limit)
							{
							if($i <> $start)
							{
							$paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
							}
							else
							{
							$paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
							}
							$l=$l+1;

							}
							}
							$last = $nume - $nume%10;
							if($this1 < $nume)
							{ 
							$paginate.='<li><a href="'.$page_name.'&start='.$next.'" class="head2">NEXT &gt;</a></li>';
							$paginate.='<li><a href="'.$page_name.'&start='.$last.'" class="head2">LAST &gt;</a></li>';
							}
							$paginate.='</ul>';
							//echo $paginate;
							$this->view->assign('paginate', $paginate);
							}	
							
					}
		   /**********phase one view page end*******************************/
		   
		   
		   /************PHASE ONE SCHEME INACTIVE****************/
		   
			public function phaseoneschemeinactiveAction()
			{
				
				//echo "test"; die;

			$admname = new Zend_Session_Namespace('adminMname'); 
			$user_id = new Zend_Session_Namespace('userid');
			if($admname->adminname==''){
				 $this->_redirect('');
			}
			$role = new Zend_Session_Namespace('role');	
			if(!in_array($role->role,$this->rolearraynew)){
				$this->_redirect('');
			}	
					
			/* if($_POST['sessionCheck']!=$captcha->captcha)
			 {
					   //$this->view->assign('errorMessage',CSRF_ATTACK);
						   $this->_redirect('/scheme/schemeview');
						 die;
					   //return false;
			} */

			//$this->view->assign('scheme_name', 'Hello, World!');

			if ($this->getRequest()->isPost())
			{
			$request = $this->getRequest();
			$activeIds = $request->getPost();
			$Inactive_report = new Application_Model_Ministryowner;
			foreach($activeIds as $key=>$val)
			{
				if(is_array($val))
				{
					foreach($val as $key1=>$val1)
					{
						
						/* echo "<pre>";
						print_r($key1); */
						
						
						
						$ids .= $key1.",";	
						 /*******get the scheme name ************/
						$schemedata =  $Inactive_report->getschemenamenn($key1);
						$schmname = $schemedata[0]['scheme_name'];
						//echo $schmname;
						 /*******end*****************************/		

						/***************audit log start***************/
							$description = '';
							$description .= 'Scheme List: '.$schmname.'</br>';
							if($activeIds["status"] == 0){
							$description .= 'Status : Inactivated';
							} else if($activeIds["status"] == 1){
							$description .= 'Status : Activated';
							}

							$auditlog = array(
							"uid" => $user_id->userid,
							"application_type" => 'DBT Applicability Assessment',
							"description" => $description
							);
							// print_r($auditlog); exit;
							$auditobj = new Application_Model_Auditlog;
							$auditobj->insertauditlog($auditlog);
						/***************audit log end by braj***************/						 
					}
				}
				$projectid = substr($ids,0,strlen($ids)-1);
				$projectIds = explode(",",$projectid);
			}	
			
			

			$Inactive_reportlist = $Inactive_report->inactivescheme($projectIds,$activeIds["status"]);
			$this->_redirect('/ministryowner/phaseone');


			}
			}
		   /*************END***********************************/
		   
		  /**********phase one form  ************/
		
		 public function phaseoneaddAction(){
			 $captcha->captcha = session_id();
			 $userid = new Zend_Session_Namespace('userid');
			$admname = new Zend_Session_Namespace('adminMname'); 
			if($admname->adminname==''){
			$this->_redirect('');
			}
			$role = new Zend_Session_Namespace('role');	
			if(!in_array($role->role,$this->rolearraynew)){
			$this->_redirect('');
			}
			$Contactusobj = new Application_Model_Ministryowner;
			//$ministry=$Contactusobj->getMinistry($search,$table='dbt_ministry_owner');
			$ministry=$Contactusobj->getministrynnew();

			$this->view->assign("ministry_array",$ministry);
			$request = $this->getRequest();
				if ($this->getRequest()->isPost())
				{
				   //$request = $this->getRequest();
				   
					$dataform = $request->getPost();
			        
					  /****vallidation to check all the scheme fields**********/
				   $eligibletypecnt =  count($dataform['eligibility-type-1']);
				   
				   if(empty($dataform['m_type']))
					{
						$msg="Please enter a ministry!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
				
					if(empty($dataform['scheme-name-1']))
					{
						$msg="Please enter a scheme name!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
					if($dataform['dbt-eligible-1'] == 0)
					{
						$msg="Please enter a DBT Applicability!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
				   if($dataform['dbt-eligible-1'] == 1)
					{
						
						if($dataform['benefit-type-1'] == 0)
						{
							$msg="Please enter a Type of Benefit!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}
						if($eligibletypecnt == 0)
						{
							$msg="Please enter a DBT Applicability Type!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}
						
						
						
					}
					
					if($dataform['dbt-eligible-1'] == 2)
					{
						if(empty($dataform['specific-reason-1']))
						{
							$msg="Please enter a specific reason!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}

					}
				   
				   /************end scheme fields***********************************/
					
					
					
					  $eligibletype = array();
					  foreach($dataform['eligibility-type-1'] as $k =>$v)
					  {
						  if($v == 1)
						  {
							  $eligibletype[] = 'Individual'; 
						  }
						  else  if($v == 2)
						  {
							  $eligibletype[] = 'HouseHold'; 
						  }
						   else  if($v == 3)
						  {
							  $eligibletype[] = 'ServiceEnablers'; 
						  }
					  }
					if ($eligibletype){
					$eligibiltytype  = implode(",",$eligibletype);
					} 
					 // die;
					/**********vallidation to check captcha code 26th july ************/
					if($dataform['vercode']!= $_SESSION["vercode"])
					{
					$msg="Please enter a correct code!";
					$this->view->assign('errorMessage', $msg);
					return false;
					}

					if($dataform['sessionCheck']!=$captcha->captcha)
					{
					$this->view->assign('errorMessage',CSRF_ATTACK);
					return false;
					}
					/*****************end***********************/
					// die;
					$result = $Contactusobj->checkcontentschemename($dataform['scheme-name-1'],$dataform['m_type']);
					if($result == 1)
					{
						$msg="This scheme is allready exists!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
                  	$objmin  = new Application_Model_Ministryowner;
					$data = $objmin->phaseoneins($dataform);
					//print_r($data);
					
					    if($data){ 
						/*****code to insert in audit log*************/
						$minname =  $Contactusobj->getminname($dataform['m_type']);
						$ministryname =  $minname[0]['ministry_name'];
						
						if($dataform['dbt-eligible-1'] == 1)
						{
						$dbt_eligiblity = 'Yes';
						}
						else if($dataform['dbt-eligible-1'] == 2)
						{
						$dbt_eligiblity = 'No';
						}
						if($dataform['benefit-type-1'] == 1)
						{
						$benefit_type = 'Cash';
						}
						else if($dataform['benefit-type-1'] == 2)
						{
						$benefit_type = 'In Kind';
						}
						else if($dataform['benefit-type-1'] == 3)
						{
						$benefit_type = 'Other Transfers';
						}
						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
						$description .= 'Add Scheme</br>';				
						$description .= 'Ministry Name : '.$ministryname.'</br>';
						$description .= 'Scheme Name: '.$dataform['scheme-name-1'].'</br>';
						$description .= 'DBT Applicability: '.$dbt_eligiblity.'</br>';
						 if($dataform['dbt-eligible-1'] == 1)
						{
							$description .= 'Type of Benefit: '.$benefit_type.'</br>';
							$description .= 'Eligbility type: '.$eligibiltytype.'</br>';
						}
						else if($dataform['dbt-eligible-1'] == 2)
						{
							$description .= 'Specify Reason:'.$dataform['specific-reason-1'].'</br>';
						} 
						
						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'Scheme',
						"description" => $description
						); 
						$schemeidobj->insertauditlog($auditlog);
						/******end**********audit log******************/
						$this->_redirect('/ministryowner/phaseone?actmsg=add');
						}
						else
						{
						  $this->_redirect('/ministryowner/phaseoneadd?actmsg=error');
						}



				}
			 
			 
			 
		 }
		
		
		
		/************end*************************/ 
		
		/********phase one edit form*****************/
		
		public function phaseoneeditAction()
		{
				$request = $this->getRequest();
				$id = safexss(base64_decode($request->getParam('id')));
				$this->view->assign('phasetwoid',$id);
				$captcha = new Zend_Session_Namespace('captcha');
				$captcha->captcha = session_id();
				$userid = new Zend_Session_Namespace('userid');
				$Contactusobj = new Application_Model_Ministryowner;
				$ministryownerrecrd = $Contactusobj->listministryowner($id);
				$this->view->assign("ministryownerrecrd",$ministryownerrecrd);
				$admname = new Zend_Session_Namespace('adminMname'); 
				$user_id = new Zend_Session_Namespace('userid');
				if($admname->adminname==''){
				   $this->_redirect('');
				}
				$role = new Zend_Session_Namespace('role');	
				if(!in_array($role->role,$this->rolearraynew)){
				    $this->_redirect('');
				}	
			 
				$request = $this->getRequest();
				if ($this->getRequest()->isPost())
				{
				//$request = $this->getRequest();

				   $dataform = $request->getPost();
				   /****vallidation to check all the scheme fields**********/
				   $eligibletypecnt =  count($dataform['eligibility-type']);
				
					if(empty($dataform['schemename']))
					{
						$msg="Please enter a scheme name!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
					if($dataform['dbt-eligible'] == 0)
					{
						$msg="Please enter a DBT Applicability!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
				   if($dataform['dbt-eligible'] == 1)
					{
						
						if($dataform['benefit-type'] == 0)
						{
							$msg="Please enter a Type of Benefit!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}
						if($eligibletypecnt == 0)
						{
							$msg="Please enter a DBT Applicability Type!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}
						
						
						
					}
					
					if($dataform['dbt-eligible'] == 2)
					{
						if(empty($dataform['specific-reason']))
						{
							$msg="Please enter a specific reason!";
							$this->view->assign('errorMessage', $msg);
							return false;
						}

					}
				   
				   /************end scheme fields***********************************/
					/**********vallidation to check captcha code 26th july ************/
					if($dataform['vercode']!= $_SESSION["vercode"])
					{
					$msg="Please enter a correct code!";
					$this->view->assign('errorMessage', $msg);
					return false;
					}

					if($dataform['sessionCheck']!=$captcha->captcha)
					{
					$this->view->assign('errorMessage',CSRF_ATTACK);
					return false;
					}
					/*****************end***********************/
					  
                      $ministryownerrecrdss = $Contactusobj->phaseonedit($dataform,$id);
					  if($ministryownerrecrdss)
					  {
					    /*****code to insert in audit log*************/
						$minname =  $Contactusobj->getminname($ministryownerrecrd[0]['ministry_id']);
						$ministryname =  $minname[0]['ministry_name'];
						$eligibletype = array();
						foreach($dataform['eligibility-type'] as $k =>$v)
						{
						if($v == 1)
						{
						$eligibletype[] = 'Individual'; 
						}
						else  if($v == 2)
						{
						$eligibletype[] = 'HouseHold'; 
						}
						else  if($v == 3)
						{
						$eligibletype[] = 'ServiceEnablers'; 
						}
						}
						if ($eligibletype){
						$eligibiltytype  = implode(",",$eligibletype);
						} 
						if($dataform['dbt-eligible'] == 1)
						{
						$dbt_eligiblity = 'Yes';
						}
						else if($dataform['dbt-eligible'] == 2)
						{
						$dbt_eligiblity = 'No';
						}
						if($dataform['benefit-type'] == 1)
						{
						$benefit_type = 'Cash';
						}
						else if($dataform['benefit-type'] == 2)
						{
						$benefit_type = 'In Kind';
						}
						else if($dataform['benefit-type'] == 3)
						{
						$benefit_type = 'Other Transfers';
						}
						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
						$description .= 'Edit Scheme</br>';				
						 $description .= 'Ministry Name : '.$ministryname.'</br>';
						$description .= 'Scheme Name: '.$dataform['schemename'].'</br>';
						$description .= 'DBT Applicability: '.$dbt_eligiblity.'</br>';
						 if($dataform['dbt-eligible'] == 1)
						{
							$description .= 'Type of Benefit: '.$benefit_type.'</br>';
							$description .= 'Eligbility type: '.$eligibiltytype.'</br>';
						}
						else if($dataform['dbt-eligible'] == 2)
						{
							$description .= 'Specify Reason:'.$dataform['specific-reason'].'</br>';
						} 
						
						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'Scheme',
						"description" => $description
						); 
						$schemeidobj->insertauditlog($auditlog);
						/******end**********audit log******************/
						  
						  $this->_redirect('/ministryowner/phaseone/?actmsg=update');
					  }
					

				}
		}
		
		
		
		/************end phase one edit form*********/
		   
  
}



