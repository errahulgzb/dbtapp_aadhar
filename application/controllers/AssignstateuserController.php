<?php
/* Role Definition:
 * 1 => Administrator
 * 4 => Project Manager
 */
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class AssignstateuserController extends Zend_Controller_Action
{
    //protected $rolearray=array('1','4','6');
    protected $rolearray=array('4');
    public function init(){
        $role = new Zend_Session_Namespace('role');
        if($role->role==1 || $role->role==6 || $role->role==4){
            $this->_helper->layout->setLayout('admin/layout');
        }
			   
    }
	
/* ---------------------Show details for assign user state start now ------------*/
   public function indexAction(){
		 $admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }     
			
    	$request = $this->getRequest();
    	$cmi_list = new Application_Model_Assignstateuser;
	
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
       
	if(isset($start)){
	// This variable is set to zero for the first page
        $start = 0;
        }
        else
        {
          $start=$request->getParam('start');
        }
        $page=0;
        $limit=25;

           
			
			if($role->role==4){
				$checkscheme = $cmi_list->checkscheme($userid->userid);
				}
				//echo $checkscheme;
				$pagination1=0;
				$cmishow_list=array();
				$countcmi=0;
				$start=0;
				if($checkscheme==1){
			$cmishow_list = $cmi_list->assignStateUserList($start,$limit);
			$pagination1=$this->pagination_search($countcmi,$start,$limit);	
			$countcmi = $cmi_list->countAssignStateUserList();
			$this->view->assign('cmidata', $cmishow_list);
			$this->view->assign('pagination', $pagination1);
            $this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//$this->view->assign('checkschemecount', $checkscheme);
				}
			
		
		
	}

/*----------------Show details for assign user state End now---------------*/

/*----------------Edit for assign user state Start now---------------------*/
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
        $form    = new Application_Form_Assignstateuser(); 
			
        $form->editForm();
		//die("hello");
        $this->view->form = $form;
		
		$edit_show = new Application_Model_Assignstateuser;
		
        $request = $this->getRequest();
		$editid=safexss(base64_decode($request->getParam('userid')));
		//die("kdje");
        ///////////Disable selected elements in the checkbox ////////////
        $pmArray1=$edit_show->showAssignStateUser($editid);
		
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
		//echo "<pre>";print_r($pmArray11);die;
        $showprojectname=$pmArray11->toArray();
		$countFreeProject =count($showprojectname);
		$this->view->assign('countFreeProject', $countFreeProject);
		
	if ($this->getRequest()->isPost()){
            if ($form->isValidPartial($request->getPost())){
				   $editdataform=$request->getPost();
			//check for selected scheme name
				   /**********vallidation to check captcha code 26th july ************/
				   		if($editdataform['vercode'] != $_SESSION["vercode"]){
							   $msg="Please enter a correct captcha code!";
								$this->view->assign('errorMessage', "Please enter a correct captcha code!");
								return false;
						}
					if($editdataform['sessionCheck']!=$captcha->captcha){
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   return false;
					}
					
				$valarray=explode(',',$pmArrayList1[0]['scheme_id']);
				foreach($valarray as $valcurrentscheme){
						$valcurrentscheme1[]=$valcurrentscheme;
					}
				if(isset($valcurrentscheme1) && $valcurrentscheme1<>'' && $editdataform['allcities']<>'' && $editdataform['projectname']==''){
					if($role->role == 4){
						$userscheme = $edit_show->pidListUser($editid);//which ids are not assigned
						$pr_name = $dataarr = "";
						$i=0;
						foreach($userscheme as $val){
							$pr_name[$i] = $val['id'];
							$i++;
						}
						$dataarr = array_unique(array_merge($pr_name,$editdataform['allcities']));
						$editdataform['projectname']=array_diff($valcurrentscheme1,$dataarr);
						//echo "<pre>";print_r($dataarr);
						//exit;
					}else{
						$editdataform['projectname']=array_diff($valcurrentscheme1,$editdataform['allcities']);
					}
				}else{
				if($role->role == 4){
					//echo "<pre>";print_r($editdataform);die;
					if($editdataform['allcities'] == ""){
						$editdataform['allcities'] = array();
					}						
					$dataarr = array_unique(array_merge($valcurrentscheme1,$editdataform['projectname']));
					$editdataform['projectname']=array_diff($dataarr,$editdataform['allcities']);
					
					// echo "<pre>";print_r($editdataform);
					// exit;
					}
				}				
                $id=base64_decode($request->getParam('userid'));							
                $companyobj = new Application_Model_Assignstateuser;
				
				/******audit log start*****/
				$userdetail  = $companyobj->getusername($editid);
				$username  = $userdetail[0]['username'];
				
				/*******end****************/
					foreach($editdataform['projectname'] as $val)
				    {
					   $value .= $val.",";
					   //print_r($val);
					   
					   /******audit log start*****/
					    $schemedteail  = $companyobj->getschemenamenn($val);
						$schemename  = $schemedteail[0]['scheme_name'];
						//echo $schemename;
						 $description = 'Assign State User</br>';							
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
					
					$check_val = explode(",",$value);
		//print_r($editdataform);exit;
					$match = $companyobj->updateData($editdataform,$id);
                    $this->_redirect('/assignstateuser?actmsg=edit');
            }
        }
    }

/*----------------Edit for assign user state End now---------------------*/

/*-------------- pagination function start now---------------------------*/

 public function pagination_search($nume,$start,$limit)
    {
        if($nume > $limit)
        {
            $page_name ='/assignmanager?search='.$_GET['search'];
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
            $this->view->assign('paginate', $paginate);
        }	

    }
/*-------------- pagination End now---------------------------*/
 
}



