<?php
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
require_once('Zend/Mail/Transport/Smtp.php');
require_once 'Zend/Mail.php';
class AuditController extends Zend_Controller_Action
{
	protected $rolearray = array("1","6","12","4");
    public function init()
    {
    	//$layout = $this->_helper->layout();
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif($role->role==1 || $role->role==6 || $role->role==4){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('checkaadhaar', 'html')->initContext();
    }

    public function indexAction(){
		$form    = new Application_Form_Feedback();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			if($form->isValidPartial($request->getPost())) {
           
					$dataform=$request->getPost();
					$Contactusobj = new Application_Model_Feedback;
					$match  = $Contactusobj->insertFeedbackdetails($dataform);
					$emailrecieverrecord = $Contactusobj->feedbackemailrecievr();
					
	             
					/******** mail to be sent after the user submit the feedback form ********/
					//use Zend\Mail;
					
					foreach($emailrecieverrecord  as $k=>$v)
					{
					$toemail = $v['toemail']; 
					$ccemail = $v['ccemail']; 
					}
					$type = $dataform['type'];
					$name = $dataform['name'];
					$email = $dataform['email'];
					$mobile = $dataform['mobile'];
					$details = $dataform['details'];
				
					
					
					 $mailObj = new Zend_Mail(); // obj form zend mail
                     $message = "
						<html>
						<head>
						<title>Feedback Form</title>
						</head>
						<body>
						<p>FeedBack Form!</p>
						<table>
						<tr>
						<th>Type</th>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Details</th>
						</tr>
						<tr>
						<td>'".$type."'</td>
						<td>'".$name."'</td>
						<td>'".$email."'</td>
						<td>'".$mobile."'</td>
						<td>'".$details."'</td>
						</tr>
						</table>
						</body>
						</html>
						";

                   $subject='Feedback  | DBT'; // set subject title
                    $from='<contactus@dbt.in>'; // set from title
                   
					
					
					
					
					//$mailObj->setBodyHtml($message);
					//$mailObj->setFrom($from,$from);
					//$mailObj->addTo($toemail,$toemail);
					//$mailObj->setSubject($subject);
					//$mailObj->send( );
                
					$name = MAIL_NAME;
                    $mailObj->setSubject($subject);
                    $mailObj->setBodyHtml($message);
                    $mailObj->addTo($toemail, $name);
                    $mailObj->setFrom($from, $name);
                    //$mailObj->send();
					
				
								
								


					
					/******* end*****************/
					$this->_redirect('/feedback/feedbackview?actmsg=add');					
			} else {
				// failure!
				
				//return $this->_helper->redirector('index');
				//die;
			}
		}
	}
public function feedbackeditAction()
{
	
	$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
		$this->rolearray = array("1");
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }	


        $form    = new Application_Form_Feedback();
        $form->addform();
        $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);


	$edit_show = new Application_Model_Feedback;
	$showdetails = $edit_show->editfeedbackmanagement($editid);

	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					               $editdataform=$request->getPost();
									$id=$request->getParam('id');
                                   //echo $id;
								  // die;
								 
									$companyobj = new Application_Model_Feedback;

									//$countdata= $companyobj->checklocationclientEdit($editdataform['title'], $id,$editdataform['projectname']);
									
									// echo "===".$countdata; 
									//if($countdata == 0)
									if($id)
									{
										
										$match = $companyobj->editfeedbackmanagementdetails($editdataform,$id);
										  $this->_redirect('/feedback/feedbackview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'This Location is already Exists.');
										
										 // $this->_redirect('/location/index?msg=rpt');
										return;
									}
									//if($match == "2")
					               // {
									//	$message="alreadyexist";
									//	$this->view->assign('msg',$message);
									//}
									//else
					              //  {
								    
									//}
			
					
					}


			}
	}
	
	
	
public function auditviewdetailAction()
  {
	  
	  $admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
		$this->rolearray = array("1");
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }	


        $form    = new Application_Model_Audit();
        $form->addform();
        $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);


	$edit_show = new Application_Model_Feedback;
	$showdetails = $edit_show->editfeedbackmanagement($editid);
	//print_r($showdetails);
	
	

	$this->view->assign('cmidata', $showdetails);
	  
	  
  } 
 
public function auditviewAction()
  {
	
	//$search = $_GET['title'];
	
	
	  $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
				$this->rolearray = array("1");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
               
   
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
    	
   
	$request = $this->getRequest();
	$cmi_list = new Application_Model_Audit;

		
 
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
  

 	
//echo "aaaa".$transferengineer[0];die;
//if()
//echo "<pre>";  print_r($transferengineer[0]);die;
	$cmishow_list = $cmi_list->auditlist($start,$limit);

	$countcmi = $cmi_list->countAudit();
	//echo "<pre>";
				// print_r($cmishow_list);
				//	echo "</pre>";
				//	die;
	    //echo "dilp---";   die;			
	//$countcmi = $cmi_list->countContentmanagement();
	//echo $countcmi;
	//$countcmi  = 4;
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
							$page_name ='auditview?search='.$_GET['search'];
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
					
					
					
	public function feedbackinactiveAction()
	{

            $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
				$this->rolearray = array("1");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
                    
		
		
	//$this->view->assign('title', 'Hello, World!');
		
		if ($this->getRequest()->isPost())
			{
		$request = $this->getRequest();
		$activeIds = $request->getPost();
	
		foreach($activeIds as $key=>$val)
		{
				if(is_array($val))
				{

					foreach($val as $key1=>$val1)
					{
						$ids .= $key1.",";							
					}

				}
				$projectid = substr($ids,0,strlen($ids)-1);
				$projectIds = explode(",",$projectid);

		}
	
		

		$Inactive_report = new Application_Model_Feedback;

		$Inactive_reportlist = $Inactive_report->inactivefeedbackmanagement($projectIds,$activeIds["status"]);

        $this->_redirect('/feedback/feedbackview');

		
		}
	}
//below function is use for the check Aadhaar Number is unique or not while inserting beneficiaries
	public function checkaadhaarAction(){
	//die("jhdwed");

		$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
            return false;
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            return false;
        }
		$modelobj = new Application_Model_Schemeimport();
		$request = $this->getRequest();
	
//echo $request->getParam("scheme_id");die;
		//echo "aaa";exit;
		if($request->isPost()){
			//print_r($request);die;
			$aadhaar = safexss($request->getParam("aadhaar_num"));
			$scheme_id = safexss($request->getParam("scheme_id"));
			$aadhaar_num = $modelobj->getAadhaar($aadhaar, $scheme_id);
			//print_r($aadhaar_num);exit;
			if(count($aadhaar_num) > 0){
				echo 1;
			}else{
				echo 0;
			}
			exit;
	}

	}
//below function is use for the check Aadhaar Number is unique or not while inserting beneficiaries end here


//below function is use for the check Aadhaar Number is unique or not while inserting beneficiaries
	public function checkaadhaarcurrentAction(){
		$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
            return false;
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            return false;
        }
		$modelobj = new Application_Model_Schemeimport();
		$request = $this->getRequest();
		//echo "aaa";exit;
		if($request->isPost()){
			$aadhaar = safexss($request->getParam("aadhaar_num"));
			$scheme_id = safexss($request->getParam("scheme_id"));
			$beneficiary_id = safexss($request->getParam("beneficiary_id"));
			$aadhaar_num = $modelobj->getcurrentAadhaar($aadhaar, $scheme_id,$beneficiary_id);
			//print_r($aadhaar_num);exit;
			if(count($aadhaar_num) > 0){
				echo 1;
			}else{
				echo 0;
			}
			exit;
		}
	}
//below function is use for the check Aadhaar Number is unique or not while inserting beneficiaries end here


// check scheme code for unique scheme code start now 
public function checkschemecodeAction(){
	//die("jhdwed");

		$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
            return false;
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            return false;
        }
		$modelobj = new Application_Model_Schemeimport();
		
		$request = $this->getRequest();

		if($request->isPost()){
			$scheme_codification = $request->getParam("scheme_codification");
			$scheme_code = $modelobj->checkSchemecode($scheme_codification);
			//echo "<pre>";print_r($aadhaar_num);exit;
			if(count($scheme_code) > 0){
				echo json_encode(1);die;
			}else{
				echo json_encode(0);die;
			}
			exit;
	}

	}
// check scheme code for unique scheme code end now 

// check scheme name for unique scheme name start now 
public function checkschemenameAction(){
	//die("jhdwed");

		$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
            return false;
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            return false;
        }
		$modelobj = new Application_Model_Schemeimport();
		
		$request = $this->getRequest();

		if($request->isPost()){
			$scheme_name = $request->getParam("schemename");
			$scheme_name = $modelobj->checkSchemename($scheme_name);
			//echo "<pre>";print_r($aadhaar_num);exit;
			if(count($scheme_name) > 0){
				echo json_encode(1);die;
			}else{
				echo json_encode(0);die;
			}
			exit;
	}

	}
// check scheme code for unique scheme code end now 


}