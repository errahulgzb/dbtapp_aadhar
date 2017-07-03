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

class AssignmanagerController extends Zend_Controller_Action
{
    protected $rolearray=array('1','6');
    public function init()
    {
        /* Initialize action controller here */
		$role = new Zend_Session_Namespace('role');
        if($role->role==1 || $role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
    }

    public function indexAction()
    {
	 //echo "dilip";die;
        $admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }         
    	$request = $this->getRequest();
    	$cmi_list = new Application_Model_Assignmanager;
	
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
       
	if(isset($start)){// This variable is set to zero for the first page
        $start = 0;
        }
        else
        {
          $start=$request->getParam('start');
        }
        $page=0;
        $limit=25;

            $cmishow_list = $cmi_list->assignManagerList($start,$limit);
            //echo "<pre>";print_r($cmishow_list);exit;
            $countcmi = $cmi_list->countAssignManagerList();
    	    $this->view->assign('cmidata', $cmishow_list);
            $pagination1=$this->pagination_search($countcmi,$start,$limit);
            $this->view->assign('pagination', $pagination1);
            $this->view->assign('start', $start);
	        $this->view->assign('counttotalcmireports', $countcmi);
         
    }
        
     
    public function addAction()
    {
        ob_start();
        $admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }	
        $form    = new Application_Form_Assignmanager();
        $form->addform();
        $this->view->form = $form;
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($form->isValidPartial($request->getPost())) {

                $dataform=$request->getPost();
				//print_r($dataform);die;
                $contactusobj = new Application_Model_Assignmanager;
                $match  = $contactusobj->insertData($dataform);
                $this->_redirect('/assignmanager?actmsg=add');						
            }
		
	} 
    }

    
    public function editAction()
    {
			
		$captcha = new Zend_Session_Namespace('captcha');
		$userid = new Zend_Session_Namespace('userid');
		$captcha->captcha = session_id();
	$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }	
        $form    = new Application_Form_Assignmanager();
		//die("skjdks");
        $form->editForm();
		
        $this->view->form = $form;
		$edit_show = new Application_Model_Assignmanager;
        $request = $this->getRequest();
		$editid  = safexss($request->getParam('id'));
        ///////////Disable selected elements in the checkbox ////////////
        $pmArray1=$edit_show->showAssignManager($editid);
        //echo "----".$pmArray1;die;
        $pmArrayList1=$pmArray1->toArray();
        //print_r($pmArrayList1);exit;
        $projectid=explode(",",$pmArrayList1[0]['scheme_id']);
        $form->getElement('projectname')->setAttrib('disable', $projectid);
        ///////////Disable selected elements in the checkbox ////////////
	//$showdetails = $pmArray1;

	$this->view->assign('cmidata', $pmArrayList1);
       // print_r($showdetails->toArray());die;
	$form->populate($pmArrayList1);
	$this->view->form = $form;
	# Here we are counting if  any project is free or not.
	$pmArray11=$edit_show->pidList($editid);
        $showprojectname=$pmArray11->toArray();
		$countFreeProject =count ($showprojectname);
		$this->view->assign('countFreeProject', $countFreeProject);
	if ($this->getRequest()->isPost())
        {
            if ($form->isValidPartial($request->getPost()))
            {
                $editdataform=$request->getPost();
				//print_r($editdataform);die;
				   /**********vallidation to check captcha code 26th july ************/
				   		if($editdataform['vercode'] != $_SESSION["vercode"])
						{
							   $msg="Please enter a correct captcha code!";
								//$this->view->assign('msg', $msg);
								$this->view->assign('errorMessage', "Please enter a correct code!");
								return false;
						}
					if($editdataform['sessionCheck']!=$captcha->captcha)
				    {
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   return false;
					}
			        /*****************end***********************/
				//print_r($editdataform);die;
                $id=safexss($request->getParam('id'));							
                $companyobj = new Application_Model_Assignmanager;
				
				/******audit log start*****/
				$userdetail  = $companyobj->getusername($id);
				$username  = $userdetail[0]['username'];

				/*******end****************/
				//print_r($editdataform);
					foreach($editdataform['projectname'] as $val)
				    {
					  // $value .= $val.",";
					   //print_r($value);
					   
					   /******audit log start*****/
					    $schemedteail  = $companyobj->getschemenamenn($val);
						$schemename  = $schemedteail[0]['scheme_name'];
						//echo $schemename;
						 $description = 'Assign Scheme</br>';							
						$description .= '<span>User Name:</span>'.$username.'</br>';
					    $description .= '<span>Scheme:</span>'.$schemename;
						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'Assign Scheme',
						"description" => $description
						);
						$auditobj = new Application_Model_Auditlog;
						$auditobj->insertauditlog($auditlog);  
				       /*******end****************/
					   
					}
					
					//$check_val = explode(",",$value);
//print_r($editdataform);exit;
					$match = $companyobj->updateData($editdataform,$id);
                    $this->_redirect('/assignmanager?actmsg=edit');
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
        $id=$request->getParam('id');
        $delete_report = new Application_Model_Assignmanager;
        $delete_reportlist = $delete_report->deleteData($id);
        $this->_redirect('/assignmanager?actmsg=del');
    }    
        
        
    public function pagination_search($nume,$start,$limit)
    {
        if($nume > $limit)
        {
            $page_name ='assignmanager';
            $this1 = $start + $limit; 
            $back = $start - $limit; 
            $next = $start + $limit;

            $paginate="";
            $paginate.='<ul class="pagination">';

             if($back >=0)
            {
                $paginate.='<li><a href="'.$page_name.'?start='.$back.'" class="head2">&lt; PREV</a></li>';
            }
            $i=0;
            $l=1;
            for($i=0;$i < $nume;$i=$i+$limit)
            {
                if($i <> $start)
                {
                        $paginate.='<li><a href="'.$page_name.'?start='.$i.'" class="text">'.$l.'</a></li>';
                }
                else
                {
                        $paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
                }
                $l=$l+1;
            }
            if($this1 < $nume)
            { 
                $paginate.='<li><a href="'.$page_name.'?start='.$next.'" class="head2">NEXT &gt;</a></li>';
            }
            $paginate.='</ul>';
            $this->view->assign('paginate', $paginate);
        }	

    }
                                        
                                        
                                        
/*
	public function inactiveAction()
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
	
		

		$Inactive_report = new Application_Model_Location;

		$Inactive_reportlist = $Inactive_report->inactivelocation($projectIds,$activeIds["status"]);

        $this->_redirect('/location/locationview');

		
		}
	}

  */
}



