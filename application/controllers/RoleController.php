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

class RoleController extends Zend_Controller_Action
{
    protected $rolearray=array('1','4');
    
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
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
                $role = new Zend_Session_Namespace('role');
			if($admname->adminname==''){
				$this->_redirect('');
			}
                if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
        
		//$this->view->assign('title', 'Hello, World!');
		$form    = new Application_Form_Role();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
		if ($form->isValidPartial($request->getPost())) {
								//if($_POST['vercode'] == $_SESSION["vercode"])
								//{
									
								$dataform=$request->getPost();
									$Contactusobj = new Application_Model_Role;
									//echo "ssss=". $dataform['name'];
									$countdata = $Contactusobj->checkroleclient($dataform['name']);
								  
									
									if($countdata == 0)
									{
										$match  = $Contactusobj->insertroledetails($dataform);
										/***************audit log start by braj***************/
											$description = 'Add New Role </br>';							
											$description .= 'Role Name : '.$dataform[name].'</br>';
											
											$auditlog = array(
														"uid" => $userid->userid,
														"application_type" => 'Role',
														"description" => $description
													);
											$auditobj = new Application_Model_Auditlog;
											$auditobj->insertauditlog($auditlog);
										/***************audit log end by braj***************/
										//$this->_helper->redirector('roleview');
                                        $this->_redirect('/role/roleview?actmsg=add');
										die;
									}
									else
									{
										$this->view->assign('errorMessage', 'Role is already exists.');
										
										 return;
									}
									// echo $match;
									//die;
									// echo "<pre>";
				// print_r($match);
					//echo "</pre>";
					//die;
										
										
								
									
									
		}
		
	}
	}

public function roleviewAction()
	{
	
        $admname = new Zend_Session_Namespace('adminMname');
        $role = new Zend_Session_Namespace('role');
        if($admname->adminname==''){
                $this->_redirect('');
        }
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
                
        $request = $this->getRequest();
        $cmi_list = new Application_Model_Role;
        
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_UPDATED);
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

	$cmishow_list = $cmi_list->rolelist($start,$limit);
	//echo "<pre>";
				// print_r($cmishow_list);
				//	echo "</pre>";
				//	die;
	$countcmi = $cmi_list->countrole();
	
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
							$page_name ='roleview';
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
							//echo $paginate;
							$this->view->assign('paginate', $paginate);
							}	
							
					}


public function roleeditAction()
	{
	
			$admname = new Zend_Session_Namespace('adminMname');
			$userid = new Zend_Session_Namespace('userid');
            $role = new Zend_Session_Namespace('role');
			if($admname->adminname==''){
				$this->_redirect('');
			}
                    if(!in_array($role->role,$this->rolearray)){
                        $this->_redirect('');
                    }
 
	$form    = new Application_Form_Role();
		$form->addform();
		$this->view->form = $form;
					
		
	$request = $this->getRequest();
	$editid=$request->getParam('id');
	//print_r($editid);


	$edit_show = new Application_Model_Role;
	$showdetails = $edit_show->editroleclient($editid);
/*echo "<pre>";
				print_r($showdetails);
				echo "</pre>";
			die;*/
	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					$editdataform=$request->getPost();
									$id=$request->getParam('id');
				   /**********vallidation to check captcha code ************/
				   		if($editdataform['vercode'] != $_SESSION["vercode"])
						{
							   $msg="Please enter a correct code!";
								//$this->view->assign('msg', $msg);
								$this->view->assign('errorMessage', "Please enter a correct code!");
								return false;
						}
			        /*****************end***********************/
									$companyobj = new Application_Model_Role;

									$countdata= $companyobj->checkroleclientEdit($editdataform['name'], $id);
									
									
									
									if($countdata == 0)
									{
										$match = $companyobj->editroledetails($editdataform,$id);
										/***************audit log start by braj***************/
											$description = 'Edit Role </br>';						
											$description .= 'Role Name : '.$editdataform[name].'</br>';
											
											$auditlog = array(
														"uid" => $userid->userid,
														"application_type" => 'Role',
														"description" => $description
													);
													
											$auditobj = new Application_Model_Auditlog;
											$auditobj->insertauditlog($auditlog);
										/***************audit log end by braj***************/
										$this->_redirect('/role/roleview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'Your title is already exists in the database.');
										
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

	public function deleteAction()
	{


		$admname = new Zend_Session_Namespace('adminMname');
                $role = new Zend_Session_Namespace('role');
		if($admname->adminname==''){
			$this->_redirect('');
		}
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
 
		$request = $this->getRequest();
		$id=$request->getParam('id');
		

		$delete_report = new Application_Model_Role;

		$delete_reportlist = $delete_report->deleterole($id);

                $this->_redirect('/role/roleview?actmsg=del');
	}


	public function roleinactiveAction()
	{

		$admname = new Zend_Session_Namespace('adminMname');
		$user_id = new Zend_Session_Namespace('userid');
        $role = new Zend_Session_Namespace('role');
		if($admname->adminname==''){
			$this->_redirect('');
		}
		//$this->view->assign('ad', $admname->adminname);
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
 
		if ($this->getRequest()->isPost())
			{
		$request = $this->getRequest();
		$activeIds = $request->getPost();
		//echo "count=".count($activeIds);
		//die;
	//	$activeId = explode(" ",$activeIds);
	//	print_r($activeIds);
	//	print_r(array_keys($activeIds));
		//	echo "SSSSSSSS=".$activeIds["status"];
		foreach($activeIds as $key=>$val)
		{
			$roleobj = new Application_Model_Role;				

				if(is_array($val))
				{

					foreach($val as $key1=>$val1)
					{
						$ids .= $key1.",";	
						$rolenamelist .= $roleobj->getrolename($key1).", ";						
					}

				}
				$roleid = substr($ids,0,strlen($ids)-1);
				$roleIds = explode(",",$roleid);

		}
	
		//print_r($roleIds);
		//die;


		$Inactive_report = new Application_Model_Role;

		$Inactive_reportlist = $Inactive_report->inactiverole($roleIds,$activeIds["status"]);

		/***************audit log start by braj***************/
			$description = '';
			if($activeIds["status"] == 0){
				$description .= 'Status : Inactivated </br>';
			} else if($activeIds["status"] == 1){
				$description .= 'Status : Activated </br>';
			}
			$description .= 'Role : '.$rolenamelist.'</br>';
			
			$auditlog = array(
						"uid" => $user_id->userid,
						"application_type" => 'Role',
						"description" => $description
					);
			$auditobj = new Application_Model_Auditlog;
			$auditobj->insertauditlog($auditlog);
		/***************audit log end by braj***************/
		
        //$this->_redirect('/role/roleview');
        $this->_redirect('/role/roleview?actmsg=del');
		
		}
	}


}



