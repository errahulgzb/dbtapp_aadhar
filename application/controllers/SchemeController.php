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

class SchemeController extends Zend_Controller_Action
{
    protected $rolearray=array('1','6');
    public function init(){
        $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
        /* Initialize action controller here */
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('process', 'html')->initContext();
               $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif(($admname->adminname!='')&&(($this->method_name=='schemelist')||$this->method_name=='schemedetail')){
                    $this->_helper->layout->setLayout('sites/layout'); 
               }elseif($role->role==1 || $role->role==4 || $role->role==6){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
			   
    }
	/*
	// function for ajax request project list 
	public function processAction(){
		$request = $this->getRequest();
			$editid	=$request->getParam('customer_id');
			if ($this->getRequest()->isPost()){ 	
				$edit_show = new Application_Model_DbtScheme;
				$showdetails = $edit_show->projectlocationlist($editid);
				$html = "";
				$html .='<option value="0">Select Project</option>';
				 foreach ($showdetails as $key => $val){
							$html .='<option value="'.$val['id'].'">'.$val['scheme_name'].'</option>';
					}	 
					echo $html;
				
				exit;
				//$this->view->assign('data', $showdetails);
			}
	}
	*/
	

   public function indexAction(){
		ob_start();
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
		$form = new Application_Form_Scheme();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
			// $form->populate($dataform);
			// $this->view->form = $form;
//echo "<pre>";print_r($dataform);die;
				$Contactusobj = new Application_Model_DbtScheme;
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

				//print_r($dataform);die;
			  /*****************end***********************/
               /******** check this for duplicate scheme *********/
               $countdata = $Contactusobj->checkschemetranslationclient($dataform['scheme_name']);
				$count_scheme_code = $Contactusobj->checkschemecode($dataform['scheme_codification']);
				//print_r($count_scheme_code);die;
                /******* end ***********************/		   
				if($countdata == 0){
					if($count_scheme_code==0){
					$match  = $Contactusobj->insertschemedetails($dataform);
                    if($match){
					/******code to insert in the audit log*********/
					$minname =  $Contactusobj->getminname($dataform['ministry_id']);
					$ministryname =  $minname[0]['ministry_name'];
					if($dataform['scheme_type'] == 1)
					{
					  $scheme_type = 'Cash';
					}
					else if($dataform['scheme_type'] == 2)
					{
					  $scheme_type = 'Kind';
					}
					else if($dataform['scheme_type'] == 3)
					{
					  $scheme_type = 'Others';
					}
					if($dataform['scheme_group'] == 1)
					{
					  $scheme_type_info = 'Central Sector Scheme';
					}
					else if($dataform['scheme_group'] == 2)
					{
					   $scheme_type_info = 'State/UTs Scheme';
					}
					else if($dataform['scheme_group'] == 3)
					{
					   $scheme_type_info = 'Centrally Sponsored Scheme';
					}
					else if($dataform['scheme_group'] == 4)
					{
					  $scheme_type_info = 'District Scheme';
					}
                    $descriptionyu = 	$dataform['description'];				
                    $descriptiontext = wordwrap($descriptionyu, 25, "\n", true); 					
					$schemeidobj = new Application_Model_Auditlog();
					$description = '';	
					$description .= 'Add Scheme</br>';				
					$description .= '<span>Ministry:</span>'.$ministryname.'</br>';
					$description .= '<span>Scheme:</span>'.$dataform['scheme_name'].'</br>';
					$description .= '<span>Scheme Code:</span>'.$dataform['scheme_codification'].'</br>';
					$description .= '<span>Benefit Type:</span>'.$scheme_type.'</br>';
					$description .= '<span>Scheme Type:</span>'.$scheme_type_info.'</br>';
					$description .= '<span>PFMS Scheme Code:</span>'.$dataform['pfms_scheme_code'].'</br>';
                    $description .= '<span>Scheme Description:</span>'.$descriptiontext.'</br>';		
					$auditlog = array(
									"uid" => $userid->userid,
									"application_type" => 'Scheme',
									"description" => $description
								); 
			        $schemeidobj->insertauditlog($auditlog);
					/*****************end**************************/
                    	$Contactusobj->createTable($dataform['scheme_name'],$match);
                    	//$Contactusobj->updatetable($tb_name,$match);
                   	 	//create table behalf of the scheme name
                    	//$dataform['scheme_table'] = $tb_name; 
							$this->_redirect('/scheme/schemeview?actmsg=add');
                        }
                           else {
                                  $this->view->assign('errorMessage', 'There is some error');
                                  $this->_redirect('/scheme?actmsg=fileformaterror');
                                }
						}
						else{
                    $this->view->assign('errorMessage', 'This Scheme code has already Exists.');
					$this->_redirect('/scheme?actmsg=rptcode');
						}
				}
				else{
                    $this->view->assign('errorMessage', 'This Scheme name has already Exists.');
					$this->_redirect('/scheme?actmsg=rpt');
				}
			}
		} 
	}
public function schemelistAction()
	 {
		 
		// echo "language".$_SESSION["LANGUAGE_ID"];
		 
	 }
	 
	 
public function schemedetailAction()
	{
	  $request = $this->getRequest();
		$id = safexss(base64_decode($request->getParam('id')));
		if(empty($id))
		{
			$this->_redirect('/');
		}
	     $slang = new Zend_Session_Namespace('languageold');
		 $langid =   $slang ->language;
	     $cmi_list = new Application_Model_DbtScheme;

		 $schemewise = new Application_Model_Schemeview;
	 //echo "Aaaa".$id;exit;
	  $fundDetails = $schemewise->schemefunddetails($id);
	  //echo "Aaaa";exit;
	// echo count($fundDetails);
	  // echo "Aaaa";exit;
	 //print_r(array_count_values($fundDetails)); die(1);
		if(count($fundDetails)==0)
		{
			$this->_redirect('/');
		}

		 $this->view->assign("fund", $fundDetails);
	 
	 
	  

	  	if($langid  == 1)
		{
			//echo "first"; 
			//die;
		 	$showdetailshindi = $cmi_list->editschemeclienthindi($id);
		}
		else
		{
			// echo "second"; 
			//die;
	  		$showdetailsenglish = $cmi_list->editschemeclient($id);
		}
		if(empty($showdetailshindi))
		{
			//echo "third"; 
			//die;
			$showdetailsenglish = $cmi_list->editschemeclient($id);
		}
		
	 // print_r($showdetails);
	 if($showdetailsenglish!="" || $showdetailshindi!="")
		{
			$this->view->assign('cmidataeng', $showdetailsenglish);
		 $this->view->assign('cmidatahindi', $showdetailshindi);
		}
		else
		{
			$this->_redirect('/');
		}
		
	}
public function schemeviewAction(){	
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
	if($request->getParam('search')!=''){
		$querystr = safexss($request->getParam('search'));
		$_GET['search'] = $querystr;
	}
	$cmi_list = new Application_Model_DbtScheme;
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
	$cmishow_list = $cmi_list->schemelist($start,$limit);
	$countcmi = $cmi_list->countlocation();	
	/* echo "<pre>";
					print_r($cmishow_list);
					echo "</pre>";
				die; */
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
							$page_name ='schemeview?search='.$_GET['search'];
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

/*********add new methods for translate in hindi ********/

public function schemetranslateAction()
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
		//$this->view->assign('scheme_name', 'Hello, World!');
		$form = new Application_Form_SchemeTranslate();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
		
		
		
			$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);


	$edit_show = new Application_Model_DbtScheme;
	$showdetails = $edit_show->editschemeclient($editid);
   $this->view->assign('editid', $editid);
	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetails->toArray());
	$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
                   
		if ($form->isValidPartial($request->getPost())) {				
			   $dataform=$request->getPost();
				$Contactusobj = new Application_Model_DbtScheme;
                 //echo "<pre>";print_r($dataform); exit;
				 
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
				 
				 $countdata = $Contactusobj->checkschemetranslationclient($dataform['scheme_name_hindi']);		
                //echo $countdata;
            				//exit;
				if($countdata == 0)
				{
                                    //File uploading
                                    /* $filename = $_FILES["uploadscheme"]["name"];
                                                                      
                                    $fileFormat = array ('jpg','JPG','jpeg','JPEG','bmp','BMP','PNG','png','gif','GIF','pdf','doc','docx');
                                    $fTemp = $_FILES ['uploadscheme']['name'];
				    if (in_array(end( explode ( '.', $filename)), $fileFormat)){
                                        //echo "---fine";die;
                                        if (strpos ( $fTemp, "#" )) {
                                                $fTemp = str_replace ( "#", "!!", $fTemp );
                                        }	
                                        if (strpos ( $fTemp, "+" )) {
                                                $fTemp = str_replace ( "+", "!!", $fTemp );
                                        }		
                                        if (strpos ( $fTemp, "@" )) {
                                                $fTemp = str_replace ( "@", "!!", $fTemp );
                                        }		
                                        if (strpos ( $fTemp, "&" )) {
                                                $fTemp = str_replace ( "&", "!!", $fTemp );
                                        }
                                        if (strpos ( $fTemp, " " )) {
                                                $fTemp = str_replace ( " ", "!!", $fTemp );
                                        }
                                        if (strpos ( $fTemp, "(" )) {
                                                $fTemp = str_replace ( "(", "!!", $fTemp );
                                        }		
                                        if (strpos ( $fTemp, "'" )) {
                                                $fTemp = str_replace ( "'", "!!", $fTemp );
                                        }		
                                        if (strpos ( $fTemp, ")" )) {
                                                $fTemp = str_replace ( ")", "!!", $fTemp );
                                        }
                                        if (strpos ( $fTemp, "%" )) {
                                                $fTemp = str_replace ( "%", "!!", $fTemp );
                                        }

                                        //$filename =$fTemp;

                                        $target_path = $_SERVER['DOCUMENT_ROOT'].'/dbt/data/scheme_uploads/'.$fTemp;
                                        move_uploaded_file($_FILES['uploadscheme']['tmp_name'], $target_path);
                                        
                                        $file_path = '/data/scheme_uploads/'.$fTemp; */
                                        $match  = $Contactusobj->insertschemetranslationwithouthindi($dataform,$editid);
                                    /*     if($match==false){
                                          unlink($target_path);  
                                        }
                                            //$this->_helper->redirector('locationview');
                                            $this->_redirect('/scheme/schemeview?actmsg=add');
                                    else {
                                           $this->view->assign('errorMessage', 'This file format is not support!!! Please try another format.');
                                           $this->_redirect('/scheme?actmsg=fileformaterror');
                                    } */
									if($match)
									{
										 $this->_redirect('/scheme/schemeview?actmsg=add');
									}
									else
									{
										 $this->_redirect('/scheme?actmsg=fileformaterror');
									}
				}
				else
				{
                     $this->view->assign('errorMessage', 'This Scheme has already Exists.');
					 $this->_redirect('/scheme/schemetranslate?id='.$editid.'&actmsg=rpt');
				}						
		}
	} 
	}
	
	
	
	
public function schemetranslateeditAction()
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


        $form    = new Application_Form_EditScheme();
        $form->addform();
        $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	//print_r($editid);

   //print_r($editid); die;
	$edit_show = new Application_Model_DbtScheme;
	$showdetails = $edit_show->editschemeclient($editid);
	
	//print_r($showdetails); die;
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
									$companyobj = new Application_Model_DbtScheme;
									//echo $editdataform['ministry_id'];exit;
									 /**********vallidation to check captcha code 26th july ************/
									   if($editdataform['vercode']!= $_SESSION["vercode"])
										{
										   $msg="Please enter a correct code!";
											$this->view->assign('errorMessage', $msg);
											return false;
										}
										 if($editdataform['sessionCheck']!=$captcha->captcha)
										{
										   $this->view->assign('errorMessage',CSRF_ATTACK);
										   return false;
										}
			                        /*****************end***********************/
									
									$countdata= $companyobj->checkschemeclientEdit($editdataform['scheme_name'], $id,$editdataform['ministry_id']);
									
									// echo "===".$countdata; 
									
									if($countdata == 0)
									{
										$match = $companyobj->editschemetranslatedetails($editdataform,$id);
										  $this->_redirect('/scheme/schemeview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'This Scheme is already Exists.');
										
										 // $this->_redirect('/location/index?msg=rpt');
										return;
									}		
					
					}


			}
	}


/*************8 end**************************/

public function schemeeditAction()
{
    $userid = new Zend_Session_Namespace('userid');
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


     $form    = new Application_Form_EditScheme();
    $form->addform();
     $this->view->form = $form;

		
	$request = $this->getRequest();
	$editid=safexss(base64_decode($request->getParam('id')));
	//print_r($editid);

	$edit_show = new Application_Model_DbtScheme;
	$showdetails = $edit_show->editschemeclient($editid);
	$showdetailss=$showdetails->toArray();

	$this->view->assign('cmidata', $showdetails);

	$form->populate($showdetailss);
	$this->view->form = $form;
	//print_r($showdetailss); die;
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					$editdataform=$request->getPost();
		//print_r($editdataform); die;
									$id=safexss($request->getParam('id'));
                                   //echo $id;
								  // die;
									$companyobj = new Application_Model_DbtScheme;
									//echo $editdataform['ministry_id'];exit;

									 /**********vallidation to check captcha code 26th july ************/
									   if($editdataform['vercode']!= $_SESSION["vercode"])
										{
										   $msg="Please enter a correct code!";
											$this->view->assign('errorMessage', $msg);
											return false;
										}
					
										 if($editdataform['sessionCheck']!=$captcha->captcha)
											{
											   $this->view->assign('errorMessage',CSRF_ATTACK);
											   return false;
											}
			                      /*****************end***********************/
									$countdata= $companyobj->checkschemeclientEdit($editdataform['scheme_name'], $id,$editdataform['ministry_id']);
									
									// echo "===".$countdata; 
									
									if($countdata == 0)
									{
										//print_r($editdataform); die;
										  $match = $companyobj->editschemedetails($editdataform,$id);
										  /**************code to insert in the audit log**********/
										  	$minname =  $companyobj->getminname($editdataform['ministry_id']);
											$ministryname =  $minname[0]['ministry_name'];
											if($editdataform['scheme_type'] == 1)
											{
											  $scheme_type = 'Cash';
											}
											else if($editdataform['scheme_type'] == 2)
											{
											   $scheme_type = 'Kind';
											}
											else if($editdataform['scheme_type'] == 3)
											{
											   $scheme_type = 'Others';
											}
											if($editdataform['scheme_group'] == 1)
											{
											   $scheme_type_info = 'Central Sector Scheme';
											}
											else if($editdataform['scheme_group'] == 2)
											{
											   $scheme_type_info = 'State/UTs Scheme';
											}
											else if($editdataform['scheme_group'] == 3)
											{
											   $scheme_type_info = 'Centrally Sponsored Scheme';
											}
											else if($editdataform['scheme_group'] == 4)
											{
											   $scheme_type_info = 'District Scheme';
											}
											$descriptionyu = 	$editdataform['description'];
                                            $descriptiontext = wordwrap($descriptionyu, 25, "\n", true); 
											$schemeidobj = new Application_Model_Auditlog();
											$description = '';
											$description .= 'Edit Scheme</br>';				
											$description .= '<span>Ministry Name:</span>'.$ministryname.'</br>';
											$description .= '<span>Scheme Name:</span>'.$editdataform['scheme_name'].'</br>';
											$description .= '<span>Benefit Type:</span>'.$scheme_type.'</br>';
											$description .= '<span>Scheme Type:</span>'.$scheme_type_info.'</br>';
											$description .= '<span>Scheme Description:</span>'.$descriptiontext.'</br>';
											$auditlog = array(
											"uid" => $userid->userid,
											"application_type" => 'Scheme',
											"description" => $description
											);  
											$schemeidobj->insertauditlog($auditlog);
										  /****************end************************************/
										
										$this->_redirect('/scheme/schemeview?actmsg=edit');
									}
									else
									{
										$this->view->assign('errorMessage', 'This Scheme Name is already Exists.');
										
										 // $this->_redirect('/location/index?msg=rpt');
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


            $delete_report = new Application_Model_DbtScheme;

            $delete_reportlist = $delete_report->deletelocation($id);

        $this->_redirect('/location/locationview?actmsg=del');
	}


	public function schemeinactiveAction()
	{

            $admname = new Zend_Session_Namespace('adminMname'); 
		    $user_id = new Zend_Session_Namespace('userid');
            if($admname->adminname==''){
                 $this->_redirect('');
            }
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
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
	     $Inactive_report = new Application_Model_DbtScheme;
		foreach($activeIds as $key=>$val)
		{
				if(is_array($val))
				{

					foreach($val as $key1=>$val1)
					{
						$ids .= $key1.",";	
                         /*******get the scheme name ************/
						 $schemedata =  $Inactive_report->getschemenamenn($key1);
						 $schmname = $schemedata[0]['scheme_name'];
                         /*******end*****************************/
						/***************audit log start***************/
						$description = '';
						$description .= 'Scheme List: '.$schmname.'</br>';
						if($activeIds["status"] == 0){
						$description .= 'Status : Inactive';
						} else if($activeIds["status"] == 1){
						$description .= 'Status : Active';
						}

						$auditlog = array(
						"uid" => $user_id->userid,
						"application_type" => 'Scheme',
						"description" => $description
						);
						// print_r($auditlog); exit;
						$auditobj = new Application_Model_Auditlog;
						$auditobj->insertauditlog($auditlog);
						/***************audit log end***************/						 
					}

				}
				$projectid = substr($ids,0,strlen($ids)-1);
				$projectIds = explode(",",$projectid);
				

		}	
       
		$Inactive_report = new Application_Model_DbtScheme;

		$Inactive_reportlist = $Inactive_report->inactivescheme($projectIds,$activeIds["status"]);

        $this->_redirect('/scheme/schemeview?status=updated');

		
		}
	}
	
	/********get all the dbt applicable list from the ministry owner table******************/
public function dbtapplicablelistAction()
{
	//echo "test"; die;
		  $this->_helper->layout->setLayout('sites/layout');	
		$dbtapplicablelist  = new Application_Model_DbtScheme;
		$dbtapplicabledata = $dbtapplicablelist->getdbtapplicableministrylist();
		//print_r($dbtapplicabledata); die;
		//echo $dbtapplicabledata;
	    $this->view->assign('dbtapplicabledata',$dbtapplicabledata);
		/*****code added for count schemes and count applicable schemes**/
		$counttotaldata = $dbtapplicablelist->counttotalschemes();
		$totalschmcount = $counttotaldata[0]['schemenamecount'];
		$totalmincount = $counttotaldata[0]['mincount'];
		$this->view->assign('totalschmcount',$totalschmcount);
		$this->view->assign('totalmincount',$totalmincount);
		
	    $counttotaapplicableldata = $dbtapplicablelist->counttotalapplicableschemes();
		$totalschmapplicablecount = $counttotaapplicableldata[0]['schemenamecount'];
		$totalapplicablemincount = $counttotaapplicableldata[0]['mincount'];
		$this->view->assign('totalschmapplicablecount',$totalschmapplicablecount);
		$this->view->assign('totalapplicablemincount',$totalapplicablemincount);
		
		
	    $counttotalnonapplicableschemesdata = $dbtapplicablelist->counttotalnonapplicableschemes();
		$totalschmnonapplicablecount = $counttotalnonapplicableschemesdata[0]['schemenamecount'];
		$totalapplicablenonmincount = $counttotalnonapplicableschemesdata[0]['mincount'];
		$this->view->assign('totalschmnonapplicablecount',$totalschmnonapplicablecount);
		$this->view->assign('totalapplicablenonmincount',$totalapplicablenonmincount);
		/***********end**************************************************/
		
}

/*******************end***************************************************************/

  
}



