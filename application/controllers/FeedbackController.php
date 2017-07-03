<?php
/* require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
require_once('Zend/Mail/Transport/Smtp.php');
require_once 'Zend/Mail.php'; */

require_once 'Zend/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
class FeedbackController extends Zend_Controller_Action
{

    public function init()
    {
    	//$layout = $this->_helper->layout();
               /* $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif($role->role==1 || $role->role==4){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
               */
               
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname');
				
                $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif(($admname->adminname!='')&&($this->method_name=='index')){
                    $this->_helper->layout->setLayout('sites/layout'); 
               }elseif($role->role==1 || $role->role==4){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
               
               
    }

     public function indexAction()
    {
		  
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		/****get the session info********/
		$userid = new Zend_Session_Namespace('userid');
		if($userid->userid)
		{
		$iserinfo = new Application_Model_Feedback;
		$usrdata = $iserinfo->getusrinfo($userid->userid);
		$firstname = 	$usrdata['firstname'];
		$lastname  = 	$usrdata['lastname'];
		$name 	= $firstname.' '.$lastname;	
		$email  = 	$usrdata['email'];
		$mobile  = 	$usrdata['mobile'];
		$this->view->assign('name',$name);
		$this->view->assign('email',$email);
		$this->view->assign('mobile',$mobile);

		}
		//echo $userid->userid;
		/******************end***********/
		$form    = new Application_Form_Feedback();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {

//echo 'hello'; exit;
			if($form->isValidPartial($request->getPost())) {
           
					$dataform=$request->getPost();
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
					$Contactusobj = new Application_Model_Feedback;
					$match  = $Contactusobj->insertFeedbackdetails($dataform);
					//$emailrecieverrecord = $Contactusobj->feedbackemailrecievr();
					
	             
					/******** mail to be sent after the user submit the feedback form ********/
					//use Zend\Mail;
					
					/* foreach($emailrecieverrecord  as $k=>$v)
					{
					$toemail = $v['toemail']; 
					$ccemail = $v['ccemail']; 
					} */
					
					//$toemail = MAIL_ADMIN;
					$toemail = 'chakshu303@gmail.com';
					$type = $dataform['type'];
					$name = $dataform['name'];
					$email = $dataform['email'];
					$mobile = $dataform['mobile'];
					$details = $dataform['details'];
                     if($type == 01)
					 {
						 $fedbacktype = 'Complaint';
					 }
					  if($type == 02)
                      {
						 $fedbacktype = 'Feedback';  
					  }
					$mailObj = new Zend_Mail(); // obj form zend mail
					$message = "Dear Administrator,<br/><br/>
					You have received a feedback/complaint.<br/><br/>
					The details of the feedback/complaint are as follows:<br/><br/>
					Feedback Type:".$fedbacktype."<br/>
					Name:".$name."<br/>
					Email:".$email."<br/>
					Mobile:".$mobile."<br/>
					Details:".$details."<br/><br/>
					Regards,<br />DBT Bharat Team <br />
					Website: ".WEB_LINK."<br />
					(This is a system generated message. Please do not reply to this email)";
					
                    $subject= MAIL_SUBJECT_FEEDBACK; // set subject title
					//echo $subject;
					$name = MAIL_NAME_FEEDBACK;
					$mailObj->setSubject($subject);
					$mailObj->setBodyHtml($message);
					$mailObj->addTo($toemail,$name);
					$mailObj->setFrom($email,$name);
					$mailObj->send();
					/******* end*****************/
					$this->_redirect('/feedback/?actmsg=add');					
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
	
	
	
public function feedbackviewdetailAction()
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
	//print_r($showdetails);
	
	

	$this->view->assign('cmidata', $showdetails);
	  
	  
  } 
 
public function feedbackviewAction()
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
	$cmi_list = new Application_Model_Feedback;

		
 
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
	$cmishow_list = $cmi_list->feedbacklist($start,$limit);
	$countcmi = $cmi_list->countFeedback();
	$countcmisearch = $cmi_list->countFeedbacksearch();
	//echo "<pre>";
				// print_r($cmishow_list);
				//	echo "</pre>";
				//	die;
	    //echo "dilp---";   die;			
	//$countcmi = $cmi_list->countContentmanagement();
	//echo $countcmi;
	//$countcmi  = 4;
	$this->view->assign('cmidata', $cmishow_list);

	   
	
	$pagination1=$this->pagination_search($countcmisearch,$start,$limit);
					            $this->view->assign('pagination', $pagination1);
								$this->view->assign('start', $start);
	
	$this->view->assign('counttotalcmireports', $countcmi);
	}
	
	
	public function pagination_search($nume,$start,$limit)
					{
		
							if($nume > $limit)
							{
							$page_name ='feedbackview?search='.$_GET['search'];
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

}