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

class ContentmanagementController extends Zend_Controller_Action
{
    protected $rolearray=array('1','6');
    public function init()
    {
		$this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
		
        /* Initialize action controller here */
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('process', 'html')->initContext();
                $role = new Zend_Session_Namespace('role');
                if($role->role==1 || $role->role==6){
                    $this->_helper->layout->setLayout('admin/layout');
                }
			}


	
	// function for ajax request project list 
	/*public function processAction()
	{		$request = $this->getRequest();
			$editid	=$request->getParam('customer_id');
			
			if ($this->getRequest()->isPost())
			{ 	
				$edit_show = new Application_Model_Location;
				$showdetails = $edit_show->projectlocationlist($editid);
				$html = "";
				$html .='<option value="0">Select Project</option>';
				 foreach ($showdetails as $key => $val)
					 {
							$html .='<option value="'.$val['id'].'">'.$val['title'].'</option>';
					}	 
					echo $html;
				
				exit;
				//$this->view->assign('data', $showdetails);
			}
			
			
	}*/

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
		//$this->view->assign('title', 'Hello, World!');
		$form = new Application_Form_ContentManagement();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		
		if ($this->getRequest()->isPost()) {
		if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
			  //echo "<pre>";print_r($dataform); exit;
				$Contactusobj = new Application_Model_Contentmanagement;
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

public function contentmanagementviewAction()
{
	
	@$search = $_GET['type'];
   
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
	$cmi_list = new Application_Model_Contentmanagement;
	
     	$language = $cmi_list->language();
		
 
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
  

$transferengineer = $cmi_list->assign_projectids($userid->userid);
$this->view->assign('transferengineer', $transferengineer[0]);
$this->view->assign('language', $language);
 	
//echo "aaaa".$transferengineer[0];die;
//if()
//echo "<pre>";  print_r($search);die;
	$cmishow_list = $cmi_list->contentmanagementlist($start,$limit,$search);
	$countcmi = $cmi_list->countContentmanagement($search);
	//echo "<pre>";
				// print_r($cmishow_list);
				//	echo "</pre>";
				//	die;
	    //echo "dilp---";   die;			
	//$countcmi = $cmi_list->countContentmanagement();
	//echo $countcmi;
	//$countcmi  = 4;
	$this->view->assign('cmidata', $cmishow_list);
	$this->view->assign('search', $search);

	   
	
	$pagination1=$this->pagination_search($countcmi,$start,$limit,$search);
					            $this->view->assign('pagination', $pagination1);
								$this->view->assign('start', $start);
	
	$this->view->assign('counttotalcmireports', $countcmi);
	}


	public function pagination_search($nume,$start,$limit,$search)
					{
		
							if($nume > $limit)
							{
							$page_name ='contentmanagementview?type='.@$search;
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

public function contentmanagementtranslateeditAction()
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


        $form    = new Application_Form_ContentManagementEdit();
        $form->addform();
        $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);


	$edit_show = new Application_Model_Contentmanagement;
	$showdetails = $edit_show->editcontentmanagement($editid);

	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					$editdataform=$request->getPost();
									$id=safexss($request->getParam('id'));
                                   //echo $id;
								  // die;
								  /**********vallidation to check captcha code 26th july ************/
					if($editdataform['vercode'] != $_SESSION["vercode"])
					{
						   $msg="Please enter a correct code!";
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
									$companyobj = new Application_Model_Contentmanagement;

									//$countdata= $companyobj->checklocationclientEdit($editdataform['title'], $id,$editdataform['projectname']);
									
									// echo "===".$countdata; 
									//if($countdata == 0)
									if($id)
									{
										
										$match = $companyobj->editcontentmanagementtranslationdetails($editdataform,$id);
										  $this->_redirect('/contentmanagement/contentmanagementview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'This title is already Exists.');
										
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
public function contentmanagementeditAction()
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


        $form    = new Application_Form_ContentManagementEdit();
        $form->addform();
        $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);


	$edit_show = new Application_Model_Contentmanagement;
	$showdetails = $edit_show->editcontentmanagement($editid);
	
	$translationid = $edit_show->gettranslationid($editid);
   
	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					$editdataform=$request->getPost();
									$id=safexss($request->getParam('id'));
                                   //echo $id;
								  // die;
								 
									$companyobj = new Application_Model_Contentmanagement;

									//$countdata= $companyobj->checklocationclientEdit($editdataform['title'], $id,$editdataform['projectname']);
									
									// echo "===".$countdata; 
									//if($countdata == 0)
									//$countdata = $companyobj->checkcontent($editdataform['title']);	
								
								 /**********vallidation to check captcha code 26th july ************/
					if($editdataform['vercode'] != $_SESSION["vercode"])
					{
						   $msg="Please enter a correct code!";
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
									
									if($id)
									{
										
										
										$match = $companyobj->editcontentmanagementdetails($editdataform,$id,$translationid);
										  $this->_redirect('/contentmanagement/contentmanagementview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'This title is already Exists.');
										
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
public function contentmanagementtranslateAction()
{
	
		ob_start();
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$request = $this->getRequest();
	    $rowid = safexss($request->getParam('id'));
		$this->view->assign('crntrowid', $rowid);
		$admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                     $this->_redirect('');
                }
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$this->rolearray)){
                    $this->_redirect('');
                }
		//$this->view->assign('title', 'Hello, World!');
		$form = new Application_Form_ContentManagementTranslate();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
/**************** code to get the menu_type and the sort order ***********/
    $id = safexss($_GET['id']);
	$Contactusobj = new Application_Model_Contentmanagement;
	$menu_type =  $Contactusobj->getmenutype($id);
	$sortorder =  $Contactusobj->getsortorder($id);
	


/******************* end ************************/
		if ($this->getRequest()->isPost()) {
		if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
			  //echo "<pre>";print_r($dataform); exit;
				$Contactusobj = new Application_Model_Contentmanagement;
				 $countdatanew = $Contactusobj->checkcontent($dataform['title']);	
				// echo "<pre>";print_r($dataform); exit;
				
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
				
				if($countdatanew == 0)
				{
					//echo "<pre>";print_r($dataform); exit;
				
					$match  = $Contactusobj->insertContentManagementTranslationdetails($dataform,$menu_type,$sortorder,$rowid);
					
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
	/*public function deleteAction()
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


            $delete_report = new Application_Model_Location;

            $delete_reportlist = $delete_report->deletelocation($id);

        $this->_redirect('/location/locationview?actmsg=del');
	}*/


	public function contentinactiveAction()
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
                    
			if($_POST['sessionCheck']!=$captcha->captcha)
				    {
					   //$this->view->assign('errorMessage',CSRF_ATTACK);
						  $this->_redirect('/contentmanagement/contentmanagementview');
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
	
		

		$Inactive_report = new Application_Model_Contentmanagement;

		$Inactive_reportlist = $Inactive_report->inactivecontentmanagement($projectIds,$activeIds["status"]);

        $this->_redirect('/contentmanagement/contentmanagementview?actmsg=ina');

		
		}
	}

	/*********om add page********************/
	
	public function omaddAction()
	{
		//die('fdf');
		ob_start();
		
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$role = new Zend_Session_Namespace('role');
		if($admname->adminname=='')
		$this->_redirect('');

		if(!in_array($role->role,$this->rolearray))
		$this->_redirect('');

		$form = new Application_Form_ContentManagement();
		$form->contentform();
		$this->view->form = $form;
		
		$request = $this->getRequest();
		if($request->getParam('actmsg')=='filesizeerror'){
		$this->view->assign('errorMessage', FILE_SIZE_ERROR_2MB);
		}elseif($request->getParam('actmsg')=='fileformaterror'){
		$this->view->assign('errorMessage', FILE_FORMAT_ERROR);
		}elseif($request->getParam('actmsg')=='filedimensionerror'){
		$this->view->assign('errorMessage', FILE_DIMENSION_ERROR);
		} 
		
		if ($this->getRequest()->isPost()){ 
			
			if($form->isValidPartial($request->getPost())){				
				
				$dataform = $request->getPost();
				
					/***** captcha validation */
					if($dataform['vercode'] != $_SESSION["vercode"])
					{
						$msg="Please enter a correct code!";
						//$this->view->assign('msg', $msg);
						$this->view->assign('errorMessage', "Please enter a correct code!");
						return false;
					}
					if($dataform['sessionCheck']!=$captcha->captcha)
				    {
						$message="This is CSRF attack. Please correct and try again.";
						$this->view->assign('errorMessage', $message);
						return false;
					}
				
				$fileFormat = array ('pdf','PDF','doc','docx','DOC','DOCX');
				$allow_extension_only=array('application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf','application/msword');
				
				if($_FILES['uploadfile']['name'] != ""){
					
					$filename = $_FILES['uploadfile']['name'];
					$fieltempval =0;
					
					//Check file validation
					$fieltempval = $this->fileUploadValidation($_FILES,$fileFormat,$allow_extension_only);
					
					//If valid file then upload the file into server.
					if($fieltempval==0){ 
					
						$target_path = $_SERVER['DOCUMENT_ROOT'].'/dbtbharat/data/om/'.time().$filename;
						move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path);

						$dataform['filename'] = $_FILES["uploadfile"]["name"];
						$dataform['filepath'] = 'data/om/'.time().$filename;
						
						$ins_om_data = new Application_Model_Contentmanagement;
						$ins_om_data = $ins_om_data->ominsertdata($dataform);
						$this->view->assign('successMsg', RECORD_INSERTED);
						
						$form->reset();
					
					}elseif($fieltempval==2){
						
						$this->view->assign("errorMessage", "File size should not be greater than 5MB."); 

					}else{
						
						$this->view->assign("errorMessage", "Only PDF,doc,docx File can be uploaded."); 
						
					} 
				}

			}
		}
	
	}
	
	// function for ajax request sub category list 
	public function getomsubcategoryAction()
	{		
	
		$request = $this->getRequest();
		//print_r($request);
		$cat_id	= $request->getParam('omcategory');
			
		if ($this->getRequest()->isPost())
		{ 	
			$edit_show = new Application_Model_Contentmanagement;
			$showdetails = $edit_show->get_om_subcategory($cat_id);
			$html = "";
			$html .='<option value="">Select Sub Category</option>';
			foreach ($showdetails as $key => $val)
			{
				$html .='<option value="'.$val['id'].'">'.$val['title'].'</option>';
			}	 
			echo $html;

			exit;
			//$this->view->assign('data', $showdetails);
		}
				
	}
	
	/******************** validation: File Upload *************************/
	
        function fileUploadValidation($files,$fileFormat,$allow_extension){

            $filename = $files['uploadfile']['name'];
            $fieltempval = 0;
           
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

                    $data = file_get_contents($files['uploadfile']['tmp_name']);
                    $dataCheck = substr($data,0,2);
                    if($dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == "" )
                    {
                        //$this->_redirect('/ministryowner/ministryschemeadd?actmsg=fileformaterror&'.$qstring);
                        $fieltempval = 1;
                    } else {
						if($files['uploadfile']['size']>10485760)
							$fieltempval = 2;   
                        else 
							$fieltempval = 0;   
                   }
            }else{
                $fieltempval = 1;
            } 
            return $fieltempval; // 0-PASS, 1-INVALID FILE, 2- LARGE FILE
        }
	/******************** validation: File Upload *************************/
	
	public function omviewAction()
	{
		
		@$search = $_GET['title'];
	   
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
		if($admname->adminname=='')
			$this->_redirect('');
		
		$role = new Zend_Session_Namespace('role');
		
		if(!in_array($role->role,$this->rolearray))
			$this->_redirect('');
				
		$request = $this->getRequest();
		$cmi_list = new Application_Model_Contentmanagement;
		
		$language = $cmi_list->language();

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
			$start = 0;// This variable is set to zero for the first page
		else
			$start=$request->getParam('start');

			$page=0;
			$limit=3;
	  
		//$transferengineer = $cmi_list->assign_projectids($userid->userid);
		//$this->view->assign('transferengineer', $transferengineer[0]);
		//$this->view->assign('language', $language);

		$omshow_list = $cmi_list->omcontentlist($start,$limit,$search);
		//print"<pre>";print_r($omshow_list);die;
		$countcmi = $cmi_list->countomrecords($search);

		$this->view->assign('omdata', $omshow_list);
		$this->view->assign('search', $search);

		$pagination1=$this->pagination_search_om($countcmi,$start,$limit,$search);
		$this->view->assign('pagination', $pagination1);
		$this->view->assign('start', $start);
		$this->view->assign('counttotalcmireports', $countcmi);//die('sdsd');
		
	}
	
	public function pagination_search_om($nume,$start,$limit,$search)
	{

			if($nume > $limit)
			{
			$page_name ='omview?title='.@$search;
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
	
	public function ominactiveAction()
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
                    
			if($_POST['sessionCheck']!=$captcha->captcha)
				    {
					   //$this->view->assign('errorMessage',CSRF_ATTACK);
						  //$this->_redirect('/contentmanagement/contentmanagementview');
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
	//print"<pre>";print_r($projectIds);
	//print_r($activeIds);die;
		

		$Inactive_report = new Application_Model_Contentmanagement;

		$Inactive_reportlist = $Inactive_report->inactiveom($projectIds,$activeIds["status"]);

        $this->_redirect('/contentmanagement/omview');

		
		}
	}
	
	public function omeditAction()
	{	
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
        if($admname->adminname=='')
            $this->_redirect('');

        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray))
            $this->_redirect('');

        $form    = new Application_Form_ContentManagement();
        $form->editomform();
        $this->view->form = $form;

		
		$request = $this->getRequest();
		$editid=safexss($request->getParam('id'));
	
		$edit_show = new Application_Model_Contentmanagement;
		$showdetails = $edit_show->omeditdata($editid);
		//$translationid = $edit_show->gettranslationid($editid);
		//print_r($translationid);die;
		
		$this->view->assign('cmidata', $showdetails);
		$form->populate($showdetails->toArray());//print_r($showdetails);die;
		$this->view->form = $form;
	
	
		if ($this->getRequest()->isPost())
			{ 
				if ($form->isValidPartial($request->getPost()))
					{
						
						$editdataform=$request->getPost();
						$id=safexss($request->getParam('id'));

						if($editdataform['vercode'] != $_SESSION["vercode"])
						{
							$msg="Please enter a correct code!";
							//$this->view->assign('msg', $msg);
							$this->view->assign('errorMessage', "Please enter a correct code!");
							return false;
						}
						if($editdataform['sessionCheck']!=$captcha->captcha)
						{
						   $this->view->assign('errorMessage',CSRF_ATTACK);
						   return false;
						}
										
						if($id) // if url has id
						{

							$fileFormat = array ('pdf','PDF','doc','docx','DOC','DOCX');
							$allow_extension_only=array('application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf','application/msword');
							
							empty($_FILES['uploadfile']['name']) ? $file_check= 0 : $file_check= 1;

							if($file_check==1){
								
								$filename = $_FILES['uploadfile']['name'];
								$fieltempval =0;
								
								//Check file validation
								$fieltempval = $this->fileUploadValidation($_FILES,$fileFormat,$allow_extension_only);

								//If valid file then upload the file into server.
								if($fieltempval==0){ 
									
									if (file_exists($_SERVER['DOCUMENT_ROOT'].'/dbtbharat/'.$showdetails['filepath']))
									unlink($_SERVER['DOCUMENT_ROOT'].'/dbtbharat/'.$showdetails['filepath']);

									$target_path = $_SERVER['DOCUMENT_ROOT'].'/dbtbharat/data/om/'.time().$filename;
									move_uploaded_file($_FILES['uploadfile']['tmp_name'], $target_path); 

									$editdataform['filename'] = $_FILES["uploadfile"]["name"];
									$editdataform['filepath'] = 'data/om/'.time().$filename;
									
									
									
								}elseif($fieltempval==2){
									
									$this->view->assign("errorMessage", "File size should not be greater than 5MB."); 
									
								}else{
									
									$this->view->assign("errorMessage", "Only PDF,doc,docx File can be uploaded.");
									
								} 
								
							}
							
							$ins_om_data = new Application_Model_Contentmanagement;
							$ins_om_data = $ins_om_data->editomdetails($editdataform,$id);
							
							
							$this->view->assign('successMsg', RECORD_UPDATED);
							$this->view->assign('uploaded_file', $editdataform['filepath']);

						}
						else
						{
							$this->view->assign('errorMessage', 'This title is already Exists.');
							return;
						}
					
					}


			}
	}
	
	public function documentomAction()
	{
		@$search = $_GET['title'];
		//echo "sasas";die;		
		$request = $this->getRequest();
		$cmi_list = new Application_Model_Contentmanagement;
		
		$documentom = $cmi_list->documentomlist();
		//print"<pre>";print_r($omshow_list);die;
		//$countcmi = $cmi_list->countomrecords($search);

		$this->view->assign('documentomdata', $documentom);
		//$this->view->assign('search', $search);
		
	}
  
}



