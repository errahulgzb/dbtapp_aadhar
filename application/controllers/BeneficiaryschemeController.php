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

class BeneficiarySchemeController extends Zend_Controller_Action
{
protected $rolearray=array('1','4','6');
    public function init()
    {
        /* Initialize action controller here */
	        $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');       
               }elseif(($admname->adminname!='')&&($role->role!=1)&&($this->method_name=='schemedatalist')){
                    $this->_helper->layout->setLayout('layout');
               }elseif($role->role==1){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
        
        
        
        
    }
	
public function indexAction()
{
	   
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
				
			if($role->role==6){
				$this->_helper->layout->setLayout('admin/layout');
			}

		$userid = new Zend_Session_Namespace('userid');
	     $userObj = new Application_Model_User;
							
		$request = $this->getRequest(); //$this should refer to a controller
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		$schemedecodeid = safexss($request->getParam('scheme_id'));
		$mindecodeid = safexss($request->getParam('min_id'));
		$scheme_type = safexss(base64_decode($request->getParam('scheme_type')));

		if($scheme_id == '' || (!is_numeric($scheme_id)) || $min_id == '' || (!is_numeric($min_id))){
		$this->_redirect('');
		}
        	$this->view->assign('schemeid', $scheme_id);
           $this->view->assign('min_id', $min_id);
		if($request->getParam('actmsg')=='add'){
		$this->view->assign('successMsg', RECORD_INSERTED);
		}elseif($request->getParam('actmsg')=='edit'){
		$this->view->assign('successMsg', RECORD_UPDATED);
		}elseif($request->getParam('actmsg')=='del'){
		$this->view->assign('successMsg', RECORD_DELETED);
		}elseif($request->getParam('actmsg')=='inactivate'){
		$this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
		}elseif($request->getParam('actmsg')=='atLeastonevalue'){
		$this->view->assign('errorMessage', ATLEAST_ONEVALUE);
		}elseif($request->getParam('actmsg')=='valuenotmatched'){
		$this->view->assign('errorMessage', VALUE_NOT_MATCHED);
		}elseif($request->getParam('actmsg')=='alreadyavailable'){
		$this->view->assign('errorMessage', ALLREADY_AVAILABLE);
		}elseif($request->getParam('actmsg')=='wrongbeneficiary'){
		$this->view->assign('errorMessage', WRONG_BENEFICIARY);
		}
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature)
		{
		$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
		if ($pos !== false) {
			$this->_redirect('/schememanualdata?scheme_id='.base64_encode($scheme_id));
		}
		} else {
		$_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end   

		//$this->view->assign('scheme_id', $scheme_id);
		$schemeidobj = new Application_Model_Schememanualdata();
	    $schemedetail = $schemeidobj->getschemename($scheme_id);
		$asignedschemeid = $schemeidobj->checkasignedschemeid($userid->userid,$scheme_id);
	if ($asignedschemeid > 0 || $role->role == 1) {
	//$scheme_type = $schemedetail->toarray()[0]['scheme_type'];

	$this->view->assign('scheme_id', $schemedetail);
		$form = new Application_Form_BeneficiaryScheme();
		$form->addform();
		$this->view->form = $form;

		$request = $this->getRequest();

		if ($this->getRequest()->isPost()) {			
			if ($form->isValidPartial($request->getPost())) {
				$dataform=$request->getPost();
				
				   /**********vallidation to check captcha code ************/
				   		if($dataform['vercode'] != $_SESSION["vercode"])
						{
							   $msg="Please enter a correct code!";
								//$this->view->assign('msg', $msg);
								$this->view->assign('errorMessage', "Please enter a correct code!");
								return false;
						}
			        /*****************end***********************/
					
					$Contactusobj = new Application_Model_Beneficiaryscheme;
					$icountrecord = $Contactusobj->countbeneficiarydatamonthyearwise($scheme_id,$dataform['year'],$dataform['month']);
                   if($icountrecord == 0)
					{
					    $insscheme = $Contactusobj->insertschemebeneficarydata($dataform,$scheme_id,$min_id);
					}
					else
					{
						$this->view->assign('errorMessage', "This beneficiary is allready exists!");
						return false;
					}
					$schemedecodeid = $request->getParam('scheme_id');
		         $mindecodeid = $request->getParam('min_id');
					if($insscheme)
					{
						/***************audit log start by braj***************/
							$schemeidobj = new Application_Model_Auditlog();
							$schemedetail = $schemeidobj->schemename($scheme_id);
							$description = 'Add Beneficiary Data </br>';							
							$description .= 'Scheme Name : '.$schemedetail[0][scheme_name].'</br>';
							
							$description .= 'Total No of Beneficiaries : '.$dataform['total_num_of_beneficary'].'</br>';
							$description .= 'Total No of Beneficiaries With Bank A/C : '.$dataform['total_num_of_beneficary_with_bank_ac'].'</br>';
							$description .= 'Total No of Beneficiaries With Aadhaar : '.$dataform['total_num_of_beneficary_with_aadhaar'].'</br>';
							$description .= 'Total No of Beneficiaries With Seeded Bank A/C : '.$dataform['total_num_of_beneficary_with_with_seeded_bankac'].'</br>';
							$description .= 'Month : '.$dataform['month'].'</br>';
							$description .= 'Year : '.$dataform['year'];
							
													
							$auditlog = array(
										"uid" => $userid->userid,
										"application_type" => 'Scheme Beneficiary Data',
										"description" => $description
									);
									
							$auditobj = new Application_Model_Auditlog;
							$auditobj->insertauditlog($auditlog);
						/***************audit log end by braj***************/
						$this->view->assign('successMsg', "Data saved sucessfully!");
                         $qstring="scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id);
						    $this->_redirect('/beneficiaryscheme/beneficiarydatalist?actmsg=add&'.$qstring);
					}
				
			}
		}
	}else {
		$this->_redirect('/schemeowner/schemeview');
	}
	

}
public function beneficiaryeditAction()
{
	
	$captcha = new Zend_Session_Namespace('captcha');
	$captcha->captcha = session_id();
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
	if($admname->adminname==''){
	$this->_redirect('');
	}
	$role = new Zend_Session_Namespace('role');
	if($role->role==6){
	$this->_helper->layout->setLayout('admin/layout');
	}
	if(!in_array($role->role,$this->rolearray)){
	$this->_redirect('');
	}	
	$request = $this->getRequest();
	$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
	$min_id = safexss(base64_decode($request->getParam('min_id')));
	$edit_data = new Application_Model_Beneficiaryscheme;
	$asignedschemeid = $edit_data->checkasignedschemeid($userid->userid,$scheme_id);
	if ($asignedschemeid > 0 || $role->role == 1) {
			$form = new Application_Form_BeneficiaryEditScheme();
			$form->addform();
			$this->view->form = $form;
			$request = $this->getRequest();
			$editid = safexss(base64_decode($request->getParam('id')));
			$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
			if($request->getParam('actmsg')=='add'){
			$this->view->assign('successMsg', RECORD_INSERTED);
			}elseif($request->getParam('actmsg')=='edit'){
			$this->view->assign('successMsg', RECORD_UPDATED);
			}elseif($request->getParam('actmsg')=='del'){
			$this->view->assign('successMsg', RECORD_DELETED);
			}elseif($request->getParam('actmsg')=='inactivate'){
			$this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
			}elseif($request->getParam('actmsg')=='atLeastonevalue'){
			$this->view->assign('errorMessage', ATLEAST_ONEVALUE);
			}elseif($request->getParam('actmsg')=='valuenotmatched'){
			$this->view->assign('errorMessage', VALUE_NOT_MATCHED);
			}elseif($request->getParam('actmsg')=='wrongbeneficiary'){
			$this->view->assign('errorMessage', WRONG_BENEFICIARY);
			}elseif($request->getParam('actmsg')=='alreadyavailable'){
			$this->view->assign('errorMessage', ALLREADY_AVAILABLE);
			}elseif($request->getParam('actmsg')=='rpt'){
			$this->view->assign('errorMessage', ALLREADY_EXIST);
			}
			$edit_show = new Application_Model_Beneficiaryscheme;
			$showdetails = $edit_show->editbeneficiarydataclient($editid);
			$schemedetail = $edit_show->getschemename($scheme_id);
			$scheme_type123 = $schemedetail->toarray();
			$scheme_type =$scheme_type123[0]['scheme_type'];
			$this->view->assign('scheme_id', $schemedetail);
			$data = $showdetails->toArray();
			$totalnoofbeneficiaries = $data['totalnoofbeneficiaries'];
			$totalnoofbeneficiarieswithbankac = $data['totalnoofbeneficiarieswithbankac'];
			$totalnoofbeneficiarieswithaadhaar = $data['totalnoofbeneficiarieswithaadhaar'];
			$totalnoofbeneficiarieswithseededbankac = $data['totalnoofbeneficiarieswithseededbankac'];
			//$savingprevdata = $data['saving_prev'];
			$yearval = array('year' => $data['financial_year_from'].'-'.$data['financial_year_to']);
			$mergedata = array_merge($data, $yearval);

			$this->view->assign('cmidata', $showdetails);
			//$form->populate($showdetails->toArray());
			$form->populate($mergedata);
			$this->view->form = $form;
			if ($this->getRequest()->isPost())
			{

			if ($form->isValidPartial($request->getPost()))
				{
			
				$editdataform=$request->getPost();
				$id = safexss(base64_decode($request->getParam('id')));
				   /**********vallidation to check captcha code ************/
				   		if($editdataform['vercode'] != $_SESSION["vercode"])
						{
							   $msg="Please enter a correct code!";
								//$this->view->assign('msg', $msg);
								$this->view->assign('errorMessage', "Please enter a correct code!");
								return false;
						}
			        /*****************end***********************/
					$companyobj = new Application_Model_Beneficiaryscheme;
					$icountrecord = $companyobj->countbeneficiarydatamonthyearwise($scheme_id,$editdataform['year'],$editdataform['month']);
					
					$match = $companyobj->editbeneficary($editdataform,$id);
				
					//echo $match; die;
					if($match)
					{
						/***************audit log start by braj***************/
							$schemeidobj = new Application_Model_Auditlog();
							
							$schemedetail = $schemeidobj->schemename($scheme_id);
							$description = 'Edit Beneficiary Data </br>';							
							$description .= 'Scheme Name : '.$schemedetail[0][scheme_name].'</br>';
							
							$description .= 'Total No of Beneficiaries : '.$editdataform['totalnoofbeneficiaries'].'</br>';
							$description .= 'Total No of Beneficiaries With Bank A/C : '.$editdataform['totalnoofbeneficiarieswithbankac'].'</br>';
							$description .= 'Total No of Beneficiaries With Aadhaar : '.$editdataform['totalnoofbeneficiarieswithaadhaar'].'</br>';
							$description .= 'Total No of Beneficiaries With Seeded Bank A/C : '.$editdataform['totalnoofbeneficiarieswithseededbankac'].'</br>';
							$description .= 'Month : '.$editdataform['month'].'</br>';
							$description .= 'Year : '.$editdataform['year'];
							
							$auditlog = array(
									"uid" => $userid->userid,
									"application_type" => 'Scheme Beneficiary Data',
									"description" => $description
								);
							$auditobj = new Application_Model_Auditlog;
							$auditobj->insertauditlog($auditlog);
						/***************audit log end by braj***************/
						//$this->view->assign('successMsg', "Data update sucessfully!");
						$qstring="scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id);
						$this->_redirect('/beneficiaryscheme/beneficiarydatalist?actmsg=edit&'.$qstring);
					}
				}
				
			}		
	}
	else
	{
		$this->_redirect('/schemeowner/schemeview');
	}
	
	
}	


public function beneficiarydatalistAction()
{
   
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		
		$request = $this->getRequest(); //$this should refer to a controller
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		if($scheme_id == '' || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schememanualdata();
		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();

        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
        
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature)
		{
		 	$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			if ($pos !== false) {
				$this->_redirect('/beneficiaryscheme/beneficiarydatalist?scheme_id='.base64_encode($schemedetails[0]['id']));
			}
		} else {
		  $_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end   
        
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

		$scheme_id = base64_decode($request->getParam('scheme_id'));

		$asignedschemeid = $cmi_list->checkasignedschemeid($userid->userid,$scheme_id);

		if ($asignedschemeid > 0 || $role->role == 1) {
			$beneficiaryobj = new Application_Model_Beneficiaryscheme;
			$cmishow_list = $beneficiaryobj->beneficiarydatalist($start,$limit,$scheme_id);

            // echo $cmishow_list; die;
			$countcmi = $beneficiaryobj->countbeneficiarydata($scheme_id);
			//echo 	$countcmi; die;
			// print_r($countcmi);die;
			$this->view->assign('cmidata', $cmishow_list);
			//die;

			
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');

			$page_name = 'beneficiarydatalist';
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$start,$limit,$page_name);
									   $this->view->assign('pagination', $pagination1);
										$this->view->assign('start', $start);
			
			$this->view->assign('counttotalcmireports', $countcmi);
		} else {
			$this->_redirect('/beneficiaryscheme/beneficiarydatalist');
		}
}


public function pagination_search($scheme_id,$min_id,$nume,$start,$limit,$page_name=null)
        {

				if($nume > $limit)
				{
				$page_name = $page_name.'?search='.$_GET['search'].'&scheme_id='.$scheme_id.'&min_id='.$min_id;	
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



/******************PMO Report***********************/
public function beneficiaryreportAction()
{
  $request = $this->getRequest(); 
  $custom_rolearray=array('1','3');
  $role = new Zend_Session_Namespace('role');	
	
		if(!in_array($role->role,$custom_rolearray)){
				$this->_redirect('');
			}	
if($role->role==1 || $role->role==4){
            $this->_helper->layout->setLayout('admin/layout');
        }
  $month = $request->getParam('month');
  $year = $request->getParam('year');
  
  
 /*********disclaimer form *************/
if($request->getParam('actmsg')=='add'){
	$this->view->assign('successMsg', RECORD_INSERTED);
}elseif($request->getParam('actmsg')=='edit'){
	$this->view->assign('successMsg', RECORD_UPDATED);
}elseif($request->getParam('actmsg')=='del'){
	$this->view->assign('successMsg', RECORD_DELETED);
}elseif($request->getParam('actmsg')=='inactivate'){
	$this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
}
 if(!empty($year) && !empty($month))
 {
 $Contactusobj = new Application_Model_Beneficiaryscheme;
  $countrecordmonthyerwise  = $Contactusobj->countpmodisclaimer($year,$month);
  if($countrecordmonthyerwise == 1)
   {
	  $disclaimerdata   = $Contactusobj->getdatadisclaimer($year,$month);
	   $this->view->assign('disclaimerdata',$disclaimerdata);
	   $this->view->assign('month', $month);
	     $this->view->assign('year', $year);
   } 
    $this->view->assign('countrecordmonthyerwise',$countrecordmonthyerwise);
	
	$form = new Application_Form_BeneficiaryDisclaimer();
	 $form->addform();
	$this->view->form = $form;
	$this->view->assign('month', $month);
	$this->view->assign('year', $year); 
		if ($this->getRequest()->isPost()) {
		if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
				$captcha = new Zend_Session_Namespace('captcha');
				$captcha->captcha = session_id();
				$error = 0;
		
	
				if($dataform['vercode']!= $_SESSION["vercode"])
					{
						   $msg="Please enter a correct code!";
							//$this->view->assign('msg', $msg);
							$this->view->assign('errorMessage', "Please enter a correct code!");
								$error = 1;
								$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=errorMessage&'.$qstring);
							return false;
					}
					 if($dataform['sessionCheck']!=$captcha->captcha)
				    {
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   $error = 1;
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=errorMessage&'.$qstring);
					   return false;
					}
					if($countrecordmonthyerwise == 0  && $error == 0)
					{
						
						
						//print_r($dataform); die;
						$inspmobeneficiary  = $Contactusobj->insrcrdpmobeneficiary($dataform);
						//echo $inspmobeneficiary; die;
						
						//$qstring="month=".$month."&year=".$year;
						//$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=add&'.$qstring);
						  
					}
					else if($countrecordmonthyerwise!= 0  && $error == 0)
					{
						
						  $update =  $Contactusobj->disclaimereditmnth($dataform,$month);
						  
					}
					else
					{
						$this->view->assign('errorMessage', "There is some error!");
								$error = 1;
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=add&'.$qstring);
					}
					if($update)
					{
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=edit&'.$qstring);
					}
					else
					{
						$this->view->assign('errorMessage', "There is some error!");
								$error = 1;
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=add&'.$qstring);
					}
					if($inspmobeneficiary)
					{
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=edit&'.$qstring);
					}
					else
					{
						$this->view->assign('errorMessage', "There is some error!");
								$error = 1;
						$qstring="month=".$month."&year=".$year;
						$this->_redirect('/beneficiaryscheme/beneficiaryreport?actmsg=add&'.$qstring);
					}
			   
		}
		  } 
   
 }
   /*************end*************************/
  $Contactusobj = new Application_Model_Beneficiaryscheme;
 // $data = $Contactusobj->getpmoreportdata($year,$month);
  $schememanualdata = $Contactusobj->getpmodataschememanual($year,$month);
 
  $this->view->assign('schememanualdata', $schememanualdata);
  
  
  /****get the data from the pahaal******/
  $yearval = explode("-", $year);
  $financial_year_from = $yearval[0];
  $financial_year_to = $yearval[1];
  $table = 'dbt_pahal_1_'. $financial_year_from.'_'. $financial_year_to;
  $schememanualdatapahal = $Contactusobj->getpmodatapahaal($year,$month,$table);
  //echo $schememanualdatapahal;
 $this->view->assign('schememanualdatapahal', $schememanualdatapahal);
 
 
 /***************get the data from the  dbt_mgnregs_3_2016_2017******/

  $table = 'dbt_mgnregs_3_'. $financial_year_from.'_'. $financial_year_to;
  $schememanualmnregas = $Contactusobj->getpmodatamnregas($year,$month,$table);
 $this->view->assign('schememanualmnregas', $schememanualmnregas);
 /*******end*******************************************************/
 
 
  $schemebeneficiarydata = $Contactusobj->getpmodatabeneficairay($year,$month);
  $this->view->assign('schemebeneficiarydataww',$schemebeneficiarydata);
 
}



/*******************end*****************************/





}
?>