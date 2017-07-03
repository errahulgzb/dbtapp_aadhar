<?php
/* Role Definition:
 * 1 => Administrator
 * 2 => Survey User
 * 3 => Customer
 * 4 => Project Manager
 */
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
class MinistryController extends Zend_Controller_Action
{
	protected $rolearray=array('1');
    public function init()
    {
        /* Initialize action controller here */
        $role = new Zend_Session_Namespace('role');
        if($role->role==1){
            $this->_helper->layout->setLayout('admin/layout');
        }
    }
	public function indexAction()
    {
		ob_start();
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
                if($admname->adminname==''){
                     $this->_redirect('');
                }
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }	
                        
		//$this->view->assign('title', 'Hello, World!');
		$form = new Application_Form_Ministry();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		
		if ($this->getRequest()->isPost()) {
			
			
		if ($form->isValidPartial($request->getPost())) {
							
			   $dataform=$request->getPost();
			 // echo "<pre>";print_r($dataform); exit;
			  
			  
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
			  
			  
				$Contactusobj = new Application_Model_DbtMinistry;
								
			$match  = $Contactusobj->insertministrydetails($dataform);
           if($match=='Already Exist')
			{
			   $message= "This Ministry already Exists.";
		     $this->view->assign('mess',$message);   
		    }
			elseif($match=='Already Exist in DB')
			{
			    $msg = "PO Number already Exists";
				$this->view->assign("ass_poa",$msg);
			}
			else
			{
				/***************audit log start by braj***************/
					$description = 'Add New Ministry </br>';							
					$description .= '<span>Ministry Name:</span>'.$dataform[ministry_name];
					
					$auditlog = array(
								"uid" => $userid->userid,
								"application_type" => 'Ministry',
								"description" => $description
							);
							
					$auditobj = new Application_Model_Auditlog;
					$auditobj->insertauditlog($auditlog);
				/***************audit log end by braj***************/
				//$this->_helper->redirector('projectview');
				$this->_redirect('/ministry/ministryview?actmsg=add');
			}									
		}
		
	} 
	}
	
public function ministryinactiveAction()
	{
			$captcha = new Zend_Session_Namespace('captcha');
			$captcha->captcha = session_id();
            $admname = new Zend_Session_Namespace('adminMname'); 
			$user_id = new Zend_Session_Namespace('userid');
            if($admname->adminname==''){
                 $this->_redirect('');
            }
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
                    
		
			if($_POST['sessionCheck']!=$captcha->captcha)
				    {
					   //$this->view->assign('errorMessage',CSRF_ATTACK);
						  $this->_redirect('/ministry/ministryview');
						 die;
					   //return false;
					}
	//$this->view->assign('title', 'Hello, World!');
		
		if ($this->getRequest()->isPost())
			{
		$request = $this->getRequest();
		$activeIds = $request->getPost();
	
		foreach($activeIds as $key=>$val)
		{
			$ministryobj = new Application_Model_DbtMinistry;
				if(is_array($val))
				{

					foreach($val as $key1=>$val1)
					{
						$ids .= $key1.",";	
						$ministrynamelist .= $ministryobj->getministryname($key1).", ";							
					}

				}
				$projectid = substr($ids,0,strlen($ids)-1);
				$projectIds = explode(",",$projectid);

		}
	
		

		$Inactive_report = new Application_Model_DbtMinistry;

		$Inactive_reportlist = $Inactive_report->inactiveministry($projectIds,$activeIds["status"]);

		/***************audit log***************/
			$description = '';
			if($activeIds["status"] == 0){
				$description .= '<span>Status : </span>Inactive</br>';
			} else if($activeIds["status"] == 1){
				$description .= '<span>Status : </span>Active</br>';
			}
			$description .= '<span>Ministry : </span>'.$ministrynamelist.'</br>';
			$auditlog = array(
						"uid" => $user_id->userid,
						"application_type" => 'Ministry',
						"description" => $description
					);
			$auditobj = new Application_Model_Auditlog;
			$auditobj->insertauditlog($auditlog);
		/***************audit log end by braj***************/
		
        $this->_redirect('/ministry/ministryview?add=updated');

		
		}
	}
	
public function ministrytranslateAction()
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
                        
		//$this->view->assign('title', 'Hello, World!');
		$form = new Application_Form_MinistryTranslate();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		$id = safexss($_GET['id']);
		$this->view->assign("id",$id);
		if ($this->getRequest()->isPost()) {
			
			
		if ($form->isValidPartial($request->getPost())) {
							
			   $dataform=$request->getPost();
			  //echo "<pre>";print_r($dataform); exit;
				$Contactusobj = new Application_Model_DbtMinistry;
				 /**********vallidation to check captcha code 26th july ************/
			  if($dataform['vercode']!= $_SESSION["vercode"])
					{
					   $msg="Please enter a correct code!";
						$this->view->assign('mess', $msg);
						return false;
					}
				 if($dataform['sessionCheck']!=$captcha->captcha)
				    {
					  $message="This is CSRF attack. Please correct and try again.";
					  $this->view->assign('mess', $message);
					   return false;
					}
			  /*****************end***********************/				
			$match  = $Contactusobj->insertministrydetailsfirst($dataform,$id);
           if($match=='Already Exist')
			{
			   $message= "This Ministry already Exists.";
		     $this->view->assign('mess',$message);   
		    }
			elseif($match=='Already Exist in DB')
			{
			    $msg = "PO Number already Exists";
				$this->view->assign("ass_poa",$msg);
			}
			else
			{
		   //$this->_helper->redirector('projectview');
                                        $this->_redirect('/ministry/ministryview?actmsg=add');
			}									
		}
		
	} 
	}
	
	
	
	public function ministrytranslateeditAction()
    {
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
        
		$form = new Application_Form_MinistryTranslate();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		$editid=safexss($request->getParam('id'));
	$edit_show = new Application_Model_DbtMinistry;
	$showdetails = $edit_show->editprojectclient($editid);
	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	 
	if ($request->isPost()){

				if ($form->isValidPartial($request->getPost()))
					{
				   $editdataform=$request->getPost();
				   $id=safexss($request->getParam('id'));
                    	  
				   $companyobj = new Application_Model_DbtMinistry;
				   
				   
				    /**********vallidation to check captcha code 26th july ************/
				if($editdataform['vercode']!= $_SESSION["vercode"])
					{
					   $msg="Please enter a correct code!";
						$this->view->assign('errorMessage',$msg);
						return false;
					}
				 if($editdataform['sessionCheck']!=$captcha->captcha)
				    {
					  $message="This is CSRF attack. Please correct and try again.";
					  $this->view->assign('errorMessage', $message);
					   return false;
					}
			     /*****************end***********************/
     			   $countdata= $companyobj->checkministryclientEdit($editdataform['ministry_name'], $id);
				   					
							if($countdata == 0)
							{
							$match = $companyobj->editschemedetails($editdataform,$id);
							$this->_redirect('/ministry/ministryview?actmsg=edit');
							}
							elseif($countdata>0)
							{
							$this->view->assign('errorMessage', 'Your Ministry is already exists.');
							return;
							}
					}


			}
		
		
		
	}
	public function ministryviewAction()
	{
	//echo "Aaaa";exit;	
		
	  $admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
	
	$request = $this->getRequest();
	if($request->getParam('search')!=''){
		$querystr = safexss($request->getParam('search'));
		$_GET['search'] = $querystr;
	}
	
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


		$limit=25;
		
	    $cmi_list = new Application_Model_DbtMinistry;
		$ministryshow_details = $cmi_list->ministrylist($start,$limit);
		//print_r($ministryshow_details);
		$countcmi = $cmi_list->countministry();
		
		$this->view->assign('cmidata', $ministryshow_details);
		$this->view->assign('ministrycount', $countcmi);
		$pagination1=$this->pagination_search($countcmi,$start,$limit);
		$this->view->assign('pagination', $pagination1);
		$this->view->assign('start', $start);
//echo "aaaaa";exit;
	   // $form->populate($ministryshow_details->toArray());
		//print_r($ministrydata->toArray());
	}
	public function pagination_search($nume,$start,$limit)
					{
		
							if($nume > $limit)
							{
							$page_name ='ministryview?search='.$_GET['search'];
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


public function ministryeditAction()
	{
	$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
	$admname = new Zend_Session_Namespace('adminMname');
	$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
        
		$form = new Application_Form_EditMinistry();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		$editid=safexss($request->getParam('id'));
	$edit_show = new Application_Model_DbtMinistry;
	$showdetails = $edit_show->editprojectclient($editid);
	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	 
	if ($request->isPost()){

				if ($form->isValidPartial($request->getPost()))
					{
				   $editdataform=$request->getPost();
				   $id=$request->getParam('id');
                    	  
				   $companyobj = new Application_Model_DbtMinistry;
				    /**********vallidation to check captcha code 26th july ************/
			    if($editdataform['vercode']!= $_SESSION["vercode"])
					{
					   $message="Please enter a correct code!";
						$this->view->assign('errorMessage',$message);
						return false;
					}
					if($editdataform['sessionCheck']!=$captcha->captcha)
				    {
					  $message="This is CSRF attack. Please correct and try again.";
					  $this->view->assign('errorMessage', $message);
					   return false;
					}

			    /*****************end***********************/
				   
				   
     			   $countdata= $companyobj->checkministryclientEdit($editdataform['ministry_name'], $id);
				   					
							if($countdata == 0)
							{
							$match = $companyobj->editschemedetails($editdataform,$id);
							/***************audit log start***************/
								$description = 'Edit Ministry </br>';							
								$description .= '<span>Ministry :<span>'.$editdataform[ministry_name];
								
								$auditlog = array(
											"uid" => $userid->userid,
											"application_type" => 'Ministry',
											"description" => $description
										);
										
								$auditobj = new Application_Model_Auditlog;
								$auditobj->insertauditlog($auditlog);
							/***************audit log end by braj***************/
							$this->_redirect('/ministry/ministryview?actmsg=edit');
							}
							elseif($countdata>0)
							{
							$this->view->assign('errorMessage', 'Your Ministry is already exists.');
							return;
							}
					}


			}
	}

	public function deleteAction()
	{

            $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }
            
            
		$request = $this->getRequest();
		$id=safexss($request->getParam('id'));
		

		$delete_report = new Application_Model_DbtMinistry;

		$delete_reportlist = $delete_report->deleteproject($id);

        $this->_redirect('/ministry/ministryview?actmsg=del');
	}


	public function projectinactiveAction()
	{

	
	
            $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
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
	
		

		$Inactive_report = new Application_Model_DbtMinistry;

		$Inactive_reportlist = $Inactive_report->inactiveproject($projectIds,$activeIds["status"]);

        $this->_redirect('/project/projectview');

		
		}
	}
				
}