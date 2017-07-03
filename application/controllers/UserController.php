<?php
/* Role Definition:
 * 1 => Administrator
 * 4 => Scheme Owner
 * 6 => Ministry Owner
 */
?>
<?php
require_once 'Zend/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class UserController extends Zend_Controller_Action
{
    protected $rolearray=array('1',"6","4");
    public function init()
    {
           /* Initialize action controller here */
			$ajaxContext = $this->_helper->getHelper('AjaxContext');
			$ajaxContext->addActionContext('districtlistprocess', 'html')->initContext();
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname');
                //$this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                //$this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                if(($role->role==1)|| ($role->role==6) || ($role->role==4)){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
                    
                    
                    
    }

	// function for ajax request District list 
	public function districtlistprocessAction()
	{		$request = $this->getRequest();
	//print_r($request);
			$statename_id	= $request->getParam('statename_id');
			
			if ($this->getRequest()->isPost())
			{ 	
				$edit_show = new Application_Model_Subdistrict;
				$showdetails = $edit_show->getdistrictbaseddtate($statename_id);
				$html = "";
				$html .='<option value="0">Select District</option>';
				 foreach ($showdetails as $key => $val)
					 {
							$html .='<option value="'.$val['district_code'].'">'.$val['district_name'].'</option>';
					}	 
					echo $html;
				
				exit;
				//$this->view->assign('data', $showdetails);
			}
			
			
	}
    public function addAction(){ 
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
		$role = new Zend_Session_Namespace('role');
		$this->view->assign('ad', $admname->adminname);
		$this->view->assign('rl', $role->role);
	// echo $admname->adminname;
	// die;
		if($admname->adminname==''){
				$this->_redirect('');	
			}
			if(!in_array($role->role,$this->rolearray)){
				$this->_redirect('');
			}
 //        echo $admname->adminname;
	// die;
	
        $form = new Application_Form_User();
        $form->addform();
		
       // echo $admname->adminname;
	//die;
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($this->getRequest()->isPost()) {
            if ($form->isValidPartial($request->getPost())) {
                $dataform=$request->getPost();
				//print_r($dataform);die;
			 /**********vallidation to check captcha code 26th july ************/
				   //$mainCaptcha  =     preg_replace('/\s+/', '',$dataform['mainCaptcha']);
				   //$txtInput = $dataform['txtInput'];
				  if($dataform['vercode']!= $_SESSION["vercode"])
		          {
					//echo "dsd"; die;
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
                if($dataform['name'] == 2 && $dataform['statename'] == 0){
                	$this->view->assign('errorMessage', 'Please select state for the state officials!');
                        return;
                }
                $Contactusobj = new Application_Model_User;
                $countdata = $Contactusobj->checkuserclient($dataform['username']);
                $countemail = $Contactusobj->checkemail($dataform['email']);
                if(($countdata == 0)){
                    $match  = $Contactusobj->insertuserdetails($dataform);
					
					/***************audit log start***************/
						$description = 'Add New User </br>';
						if($dataform[name] && $dataform[name] != 0){
							$rolename = $Contactusobj->getuserrolename($dataform[name]);
							$description .= '<span>Role:</span>'.$rolename.'</br>';
						}
						if($dataform[ministry_name] && $dataform[ministry_name] != 0){
							$ministryname = $Contactusobj->Getministryname($dataform[ministry_name]);
							$description .= '<span>Ministry:</span>'.$ministryname .'</br>';
						}
						if($dataform[statename] && $dataform[statename] != 0){
							$statename = $Contactusobj->getuserstatename($dataform[statename]);
							$description .= '<span>State:</span>'.$statename.'</br>';
						}
						
						if($dataform[cityname] && $dataform[cityname] != 0){
							$cityname = $Contactusobj->getdistrictname($dataform[cityname]);
							$description .= '<span>City:</span>'.$cityname.'</br>';
						}
						$description .= '<span>User Name:</span>'.$dataform[username].'</br>';
						$description .= '<span>First Name:</span>'.$dataform[firstname].'</br>';
						$description .= '<span>Last Name:</span>'.$dataform[lastname].'</br>';
						$description .= '<span>Email:</span>'.$dataform[email].'</br>';
						$description .= '<span>Mobile:</span>'.$dataform[mobile];
						
						$auditlog = array(
									"uid" => $userid->userid,
									"application_type" => 'User',
									"description" => $description
								);
								
						$auditobj = new Application_Model_Auditlog;
						$auditobj->insertauditlog($auditlog);
					/***************audit log end by braj***************/
					
                    $mailObj = new Zend_Mail();
					$username = $dataform['username'];
					$fname = ucfirst($dataform['firstname']);
					$weblink = WEB_LINK;
						$body =   MESSAGE_BODY;
						$body = str_replace('{user_name}',$username,$body);
						$body = str_replace('{fname}',$fname,$body);
						$body = str_replace('{user_password}',$dataform['password'],$body);
						$body = str_replace('{web_link}',$weblink,$body);
				//	echo $body; exit;

                   

                    //$message="Hi, This is a Test mail";
                    $subject= MAIL_SUBJECT;
                      $to=$dataform['email'];
                    //$to = 'abhishek.srivastava@velocis.in';
                    $from=MAIL_FROM;
                    $name = MAIL_NAME;
                    $mailObj->setSubject($subject);
                    $mailObj->setBodyHtml($body);
                    $mailObj->addTo($to, $name);
                    $mailObj->setFrom($from, $name);
                    $mailObj->send();
                    $this->_redirect('/user/userview?actmsg=add');
                    //$this->_helper->redirector('?actmsg=add');
                }
                else
                {
                    if($countdata){
                        $this->view->assign('errorMessage', 'Your username is already exists in the database.');
                        return;
                    }
					/*elseif($countemail){
                        $this->view->assign('errorMessage', 'Your email is already exists in the database.');
                        return;   
                    }*/
                   
                }								
            }	
        }
    }

    
    public function userviewAction(){
	$admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$userid = new Zend_Session_Namespace('userid');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	if($admname->adminname==''){
            $this->_redirect('');
	}
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
        
	$request = $this->getRequest();
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='ina'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }
        
	$cmi_list = new Application_Model_User;
	if(isset($start))
            {    // This variable is set to zero for the first page
                $start = 0;
            }else{
              $start=$request->getParam('start');
            }

        $page=0;
        $limit=25;
		
	$cmishow_list = $cmi_list->userlist($start,$limit,$role->role);
	$count_list = $cmi_list->countuser($role->role);
   if($role->role==4){
	$checkscheme = $cmi_list->checkscheme($userid->userid);
		$this->view->assign('checkschemecount', $checkscheme);
		}
	//int_r($checkscheme);
	$this->view->assign('cmidata', $cmishow_list);
	
	$pagination1=$this->pagination_search($count_list,$start,$limit);
	
        $this->view->assign('pagination', $pagination1);
        $this->view->assign('start', $start);
	$this->view->assign('counttotalusers', $count_list);
    }


    public function pagination_search($nume,$start,$limit)
    {

        if($nume > $limit)
        {
        $page_name ='userview';
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


	public function usereditAction()
	{
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
		$role = new Zend_Session_Namespace('role');
		$this->view->assign('ad', $admname->adminname);
		$this->view->assign('rl', $role->role);
		//echo $admname->adminname;
		//die;
		if($admname->adminname==''){
			//echo "aaa";die;
			//$this->_helper->layout->setLayout('cp');
			$this->_redirect('');
			
			}
			if(!in_array($role->role,$this->rolearray)){
				$this->_redirect('');
			}

		$form    = new Application_Form_EditUser();
			$form->addform();
			$this->view->form = $form;
					
					
					

	
	$request = $this->getRequest();
	$editid=safexss($request->getParam('id'));
	
	//echo $editid;
	//print_r($editid);


	$edit_show = new Application_Model_User;
	$showdetails = $edit_show->edituserclient($editid);
	$getministryname = $edit_show->GetministryByUser($editid);
	$getstatename = $edit_show->getstatename($editid);
	$getdistrict = $edit_show->getdistrict($editid);
    $getusername = $edit_show->getusernameusers($editid);
	
	$getrolename = $edit_show->getrolename($editid);
	
	
	//$rolemole_show = new Application_Model_Role;
	//$detailspage = $rolemole_show->roleclientusers($showdetails['role']);

//echo $showdetails['role'];
			//	echo "<pre>";
				// print_r($showdetails);
				//echo "</pre>";
			//	die;
	$this->view->assign('cmidata', $showdetails);
	$this->view->assign('getstatename', $getstatename);
	$this->view->assign('getdistrict', $getdistrict);
	$this->view->assign('getministry', $getministryname);
	$this->view->assign('getusername', $getusername);
	
	$this->view->assign('getrolename', $getrolename);
	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	
	if ($this->getRequest()->isPost())
			{
				if ($form->isValidPartial($request->getPost()))
					{
				
					$editdataform=$request->getPost();
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
									$id=$request->getParam('id');
                                   //echo $id;
								  // die;
									$companyobj = new Application_Model_User;
									$countdata= $companyobj->checkuserclientEdit($editdataform['username'], $id);
									
                                    $countemail= $companyobj->checkemailEdit($editdataform['email'], $id);
									//echo $countdata; 
									//echo $countemail;
										//if(($countdata == 0)&&($countemail == 0))
									if(($countdata == 0))
									{
										$match = $companyobj->edituserdetails($editdataform,$id);
										/***************audit log start by braj***************/
											$description = 'User Edit </br>';
											$description .= '<span>User Name:</span>'.$getusername.'</br>';
											$description .= '<span>First Name:</span>'.$editdataform[firstname].'</br>';
											$description .= '<span>Last Name:</span>'.$editdataform[lastname].'</br>';
											$description .= '<span>Email:</span>'.$editdataform[email].'</br>';
											$description .= '<span>Mobile:</span>'.$editdataform[mobile];
											
											$auditlog = array(
														"uid" => $userid->userid,
														"application_type" => 'User',
														"description" => $description
													);
													
											$auditobj = new Application_Model_Auditlog;
											$auditobj->insertauditlog($auditlog);
										/***************audit log end by braj***************/
										$this->_redirect('/user/userview?actmsg=edit');
									}
									else
									{
                                                                            if($countdata){
                                                                                $this->view->assign('errorMessage', 'Your username is already exists in the database.');
										return;
                                                                            }/*if($countemail){
                                                                                $this->view->assign('errorMessage', 'Your email is already exists in the database.');
																				return;
                                                                            }*/
                                                                                
									}
			
					
					}


			}
	}

	public function deleteAction()
	{
        $admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//die;
	if($admname->adminname==''){
            //echo "aaa";die;
            //$this->_helper->layout->setLayout('cp');
            $this->_redirect('');
        }
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
        
            $request = $this->getRequest();
            $id=$request->getParam('id');


            $delete_report = new Application_Model_User;

            $delete_reportlist = $delete_report->deleteuser($id);
            $this->_redirect('/user/userview?actmsg=del');
	}
	public function userinactiveAction()
	{
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$user_id = new Zend_Session_Namespace('userid');
		$role = new Zend_Session_Namespace('role');
		$this->view->assign('ad', $admname->adminname);
		$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//echo $_POST['sessionCheck']."!=".$captcha->captcha;
	//die;
	 if($_POST['sessionCheck']!=$captcha->captcha)
				    {
					   //$this->view->assign('errorMessage',CSRF_ATTACK);
						 $this->_redirect('/user/userview?actmsg=errormsg');
						 die;
					   //return false;
					}
	if($admname->adminname==''){
		//echo "aaa";die;
		//$this->_helper->layout->setLayout('cp');
		$this->_redirect('');
		
		}
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
        
		if ($this->getRequest()->isPost())
			{
		$request = $this->getRequest();
		$activeIds = $request->getPost();
	
		foreach($activeIds as $key=>$val)
		{
			
				

				if(is_array($val))
				{
					$unameobj = new Application_Model_User;
					foreach($val as $key1=>$val1)
					{
						$ids .= $key1.",";	
						$getusernamelist .= $unameobj->getusernameusers($key1).", ";						
					}

				}
				$userid = substr($ids,0,strlen($ids)-1);
				$userIds = explode(",",$userid);

		}
	
		//print_r($userIds);
		//die;


		$Inactive_report = new Application_Model_User;

		$Inactive_reportlist = $Inactive_report->inactiveuser($userIds,$activeIds["status"]);

		/***************audit log start by braj***************/
			$description = '';
			if($activeIds["status"] == 0){
				$description .= '<span>Status :</span> Inactivated </br>';
			} else if($activeIds["status"] == 1){
				$description .= '<span>Status :</span> Activated </br>';
			}
			$description .= '<span>User Name List:  </span>'.$getusernamelist.'</br>';
			
			$auditlog = array(
						"uid" => $user_id->userid,
						"application_type" => 'User',
						"description" => $description
					);
			// print_r($auditlog); exit;
			$auditobj = new Application_Model_Auditlog;
			$auditobj->insertauditlog($auditlog);
		/***************audit log end by braj***************/
		
        $this->_redirect('/user/userview?actmsg=ina');

		
		}
	}

	
	/************display user image ***********/
	
	
		public function userimgAction()
	   {
		   
		   
		   
		   
		   
	   }
	
	
	/**********************end************/

/****************Display User Information: Starts ******************/

	public function userinfoAction()
	{
	$request = $this->getRequest();
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
        
            
            
        $lastweek='';
        $admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//die;
	if($admname->adminname==''){
		//echo "aaa";die;
		//$this->_helper->layout->setLayout('cp');
		$this->_redirect('');
		
		}
		
		
		$userid = new Zend_Session_Namespace('userid');
		$userObj = new Application_Model_User;

		$userRecord=$userObj->getUserName($userid->userid);
		//echo $userRecord['upload'];
		//echo "</pre>";print_r($userRecord);
		
		//die;

		
		$this->view->assign('imgsource', $userRecord['upload']);
		$this->view->assign('userRecord', $userRecord);
		//Below 2 lines are temp commented. It is used in the profile photo views.
                //$lastweek=$userObj->oneweekpublicdocs();
		//$this->view->assign('lastweek', $lastweek);
		
                //
                ////echo "<pre>";
	//	print_r($lastweek);
	//	echo "</pre>";
		//die;
	
	}
	
/****************Display User Information: Ends ******************/


/****************Edit User Profile: Starts ******************/
public function userinfoeditAction()
	{
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
	$admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//die;
	if($admname->adminname==''){
		//echo "aaa";die;
		//$this->_helper->layout->setLayout('cp');
		$this->_redirect('');
		
		}
	$form    = new Application_Form_EditUser();
	$form->addform();
	$this->view->form = $form;
				
	$request = $this->getRequest();
		if($request->getParam('actmsg')=='filesizeerror'){
            $this->view->assign('errorMessage', FILE_SIZE_ERROR_2MB);
        }elseif($request->getParam('actmsg')=='fileformaterror'){
            $this->view->assign('errorMessage', FILE_FORMAT_ERROR);
        }elseif($request->getParam('actmsg')=='filedimensionerror'){
            $this->view->assign('errorMessage', FILE_DIMENSION_ERROR);
        }
	$userid = new Zend_Session_Namespace('userid');
	$userObj = new Application_Model_User;
	//$editid=$userid->userid;
//echo $userid->userid;
//die;
	$edit_show = new Application_Model_User;
	$showdetails = $edit_show->edituserinfoclient($userid->userid);
	$userRecord=$userObj->getUserName($userid->userid);
	$this->view->assign('imgsource', $userRecord['upload']);
	//echo $showdetails['role']."aaaaas";
	//die;

	$this->view->assign('cmidata', $showdetails);
	$form->populate($showdetails->toArray());
	$this->view->form = $form;
	
	if ($this->getRequest()->isPost())
		{
			if ($form->isValidPartial($request->getPost()))
			{
				$editdataform=$request->getPost();
				 /**********vallidation to check captcha code 26th july ************/
				   	if($editdataform['vercode']!= $_SESSION["vercode"])
					{
					   $msg="Please enter a correct code!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
//echo $dataform['sessionCheck']."!=".$captcha->captcha;
//die;
					 if($editdataform['sessionCheck']!=$captcha->captcha)
				    {
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   return false;
					}
			        /*****************end***********************/
				$companyobj = new Application_Model_User;

//===========================================================
					$filename = $_FILES["userprofileimg"]["name"];
					$filesize = $_FILES["userprofileimg"]["size"];
					$fileFormat = array ('jpg','JPG','jpeg','JPEG','bmp','BMP','PNG','png','gif','GIF');
					$fTemp = $_FILES ['userprofileimg']['name'];

					if ($filename == "") { $filedata = 0; } else { $filedata = 1; }

					$fieltempval = 0;
					$allow_extension_only=array('image/gif','image/jpeg','image/png','image/x-ms-bmp');
					if($mpdf!="PDF" && !in_array($_FILES['userprofileimg']['type'], $allow_extension_only))
					{
						$fieltempval = 1;   			
					}
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

					if ((in_array(end( explode ( '.', $filename)), $fileFormat) && $fieltempval == 0) || $filedata == 0){
						if ($filesize <= 2097152) {

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
								if ($filedata == 1){ $fTemp1 = time().'_'.$fTemp; } else { $fTemp1 = 0; }
								
						$target_path = DOCUMENT_ROOT.'data/uploads/profileimage/';
                        $target_path1 = $target_path . basename ($fTemp);
						$target_path2 = $target_path . basename ($fTemp1);
						//echo basename ($fTemp)."---aaaa--".basename ($fTemp1)."---";die;
						//echo $target_path2;exit;
						$data = file_get_contents($_FILES['userprofileimg']['tmp_name']);
						//echo $data;exit;
						$dataCheck = substr($data,0,2);
						//echo $dataCheck.$data;exit;
						if($filedata == 0){ 
							$data = 1;
						}
						if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $data == "" ){
							//echo $target_path."aaaaa".$dataCheck;exit;
								$this->_redirect('/user/userinfoedit?actmsg=fileformaterror');
							} else {
									$countdata= $companyobj->checkuserinfoEdit($editdataform['username'], $userid->userid);
									if($countdata == 0){
										if($filedata == 1){
											//echo "aaa";die;
											move_uploaded_file($_FILES ['userprofileimg'] ['tmp_name'], $target_path2);
										}
										//echo "bbb";die;
										$match = $companyobj->edituserinfodetails($editdataform,$userid->userid,$fTemp1);
										/***************audit log start by braj***************/
											$getusername = $companyobj->getusernameusers($userid->userid);
											$description = 'User Profile Update </br>';
											$description .= 'User Name : '.$getusername.'</br>';
											$description .= 'First Name : '.$editdataform[firstname].'</br>';
											$description .= 'Last Name : '.$editdataform[lastname].'</br>';
											$description .= 'Email : '.$editdataform[email].'</br>';
											$description .= 'Mobile : '.$editdataform[mobile];
											$auditlog = array(
														"uid" => $userid->userid,
														"application_type" => 'User',
														"description" => $description
													);
											$auditobj = new Application_Model_Auditlog;
											$auditobj->insertauditlog($auditlog);
										/***************audit log end by braj***************/
										$this->_redirect('/user/userinfo?actmsg=edit');
									}
									else{
										$this->view->assign('errorMessage', 'Your title is already exists in the database.');
										 return;
									}
								}
							} else {
								$this->_redirect('/user/userinfoedit?actmsg=filesizeerror');								
							}
						} else {
							$this->_redirect('/user/userinfoedit?actmsg=fileformaterror');
						}
					}
				}
			}
/****************Edit User Profile: Ends ******************/


/****************Change  User Password: Starts ******************/
public function changeuserpasswordAction(){
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
	$admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//die;
	if($admname->adminname==''){
		$this->_redirect('');
	}
	$form = new Application_Form_ChangeUserPassword();
	$form->changepassword();
	$this->view->form = $form;
	$request = $this->getRequest();
	$userid = new Zend_Session_Namespace('userid');
	$userObj = new Application_Model_User;
	if ($this->getRequest()->isPost()) 
			{
					if ($form->isValidPartial($request->getPost())) {
						//echo "Sucesss";
						//die;
						$dataform=$request->getPost();
						 /**********vallidation to check captcha code 26th july ************/
				   if($dataform['vercode']!= $_SESSION["vercode"]){
					   $msg="Please enter a correct code!";
						$this->view->assign('errorMessage', $msg);
						return false;
					}
				if($dataform['sessionCheck']!=$captcha->captcha){
					   $this->view->assign('errorMessage',CSRF_ATTACK);
					   return false;
					}
					
				if(strlen($dataform['newpassword']) < 8){
					$this->view->assign('errorMessage',"New Password should contain 8 characters.");
					   return false;
				}else if(strlen($dataform['conformnewpassword']) < 8){
					$this->view->assign('errorMessage',"Confirm Password should contain 8 characters.");
					   return false;
				}
				
			         /*****************end***********************/
						$userObj = new Application_Model_User;
						$match2  = $userObj->changepassword($dataform);
				//echo $match2;
				//$this->view->assign('msg', $match2);
				if($match2==1){
					/***************audit log start by braj***************/
						$description = '';							
						$description .= 'Password has been changed. </br>';
						
						$auditlog = array(
									"uid" => $userid->userid,
									"application_type" => 'User',
									"description" => $description
								);
								
						$auditobj = new Application_Model_Auditlog;
						$auditobj->insertauditlog($auditlog);
					/***************audit log end by braj***************/
					$this->view->assign('successMsg', "Password has been changed Successfully.");
				}
				if($match2==2){
					$this->view->assign('errorMessage', "Old password is incorrect.");
				}
				//$this->_redirect('/user/changeuserpassword?msg=changed');

					}else{
					
					//echo "failure";
					
					
					}

		}
		
	}
/****************Change  User Password: Ends ******************/

        
/****************Change  User Password: Starts ******************/
public function resetpasswordAction()
	{
	
	$admname = new Zend_Session_Namespace('adminMname');
	$role = new Zend_Session_Namespace('role');
	$this->view->assign('ad', $admname->adminname);
	$this->view->assign('rl', $role->role);
	//echo $admname->adminname;
	//die;
	if($admname->adminname==''){
		//echo "aaa";die;
		//$this->_helper->layout->setLayout('cp');
		//$this->_redirect('');
		
		}
	$form    = new Application_Form_ChangeUserPassword();
	$form->changepassword();
	$this->view->form = $form;
			
	$request = $this->getRequest();

	$userid = new Zend_Session_Namespace('userid');
	$userObj = new Application_Model_User;
	
	//	echo $userid->userid;
		//die;
	
	
	if ($this->getRequest()->isPost()) 
			{
					if ($form->isValidPartial($request->getPost())) {
						//echo "Sucesss";
						//die;
						$dataform=$request->getPost();
						$userObj = new Application_Model_User;
						$match2  = $userObj->changepassword($dataform);
				//echo $match2;
				$this->view->assign('msg', $match2);
				//$this->_redirect('/user/changeuserpassword?msg=changed');

					}else{
					
					//echo "failure";
					
					
					}

		}
		
	}
/****************Change  User Password: Ends ******************/
        
        
        

	public function isValidSizeUpload() {	
						if ($_FILES ['myfile'] ['size'] == 0) {
							return true;
						} else if ($_FILES ['myfile'] ['size'] == '' || $_FILES ['myfile'] ['size'] > (10*1048576)) {		
							return false;
						}	
						return true;
					}
	
	
    public function startuploadAction()
    {
            $userid = new Zend_Session_Namespace('userid');
            $userid->userid=safexss($_GET['uid']);
            $this->_helper->layout->disableLayout();					
            $Lodgereportobj = new Application_Model_User;
            $this->view->assign('gettingid',$userid->userid);
			//print_r($_FILES); die;
    }

					
    public function uploadAction()
    {				
        $this->_helper->layout->disableLayout();
        $Lodgereportobj = new Application_Model_User;
        $request = $this->getRequest();
        $folderscont=safexss($request->getParam('uid'));
		 //print_r($_FILES);  exit;
        $result = 0;
        //desired file format
        //$fileFormat = array ('doc', 'docx', 'txt', 'csv', 'xls', 'xlsx', 'pdf', 'ppt', 'rtf', 'jpg', 'bmp' );
        $fileFormat = array ('jpg','JPG','jpeg','JPEG','bmp','BMP','PNG','png','tiff','TIFF','raw','RAW');
		
        if (!$this->isValidSizeUpload()) 
        {
            $result = -2;
        }else {
/************vallidation to check the file formaat*********************/
		$fieltempval = 0;
		$allow_extension_only=array('image/gif','image/jpeg','image/png','image/x-ms-bmp');
		$filename = $_FILES ['myfile'] ['name'];

			$target_path = DOCUMENT_ROOT.'dbt/data/uploads/profileimage/';
				
				//echo $target_path;exit;
		           // print_r($_FILES);  die;
					if(!in_array($_FILES ['myfile'] ['type'], $allow_extension_only))
					{
					 $fieltempval = 1;   			
					}
					if((count(explode('.',$filename))>2)||(preg_match("/[\/|~|`|;|:|]/",$filename))){
						$fieltempval = 1;
					}elseif(preg_match("/\b%0A\b/i", $filename )){
						$fieltempval = 1;
					}elseif(preg_match("/\b%0D\b/i", $filename )){
						$fieltempval = 1;
					}elseif(preg_match("/\b%22\b/i", $filename )){
						$fieltempval = 1;
					}elseif(preg_match("/\b%27\b/i", $filename )){
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
					
		
		
		/************************end******************************/

			   // print_r($_FILES); die;
               // if ((in_array ( end ( explode ( '.', $_FILES ['myfile'] ['name'] ) ), $fileFormat )) || ($fieltempval == 0)) {
				if (in_array(end( explode ( '.', $filename)), $fileFormat)){
                      
                        $fTemp = $_FILES ['myfile'] ['name'];	
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
                        if (strpos ( $fTemp, "-" )) {
                                $fTemp = str_replace ( "-", "!!", $fTemp );
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
                        $newFileName = $fTemp;

                        $this->view->assign('newFileName',$newFileName);
                        $target_path1 = $target_path . basename ( $newFileName );
						$data = file_get_contents($target_path1);
						$dataCheck = substr($data,0,2);
						// $file = fopen($target_path."test.txt","w");
						// echo fwrite($file,$data);
						// fclose($file);
						if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $data == "")
							{
								$tresult = - 6; //format not supported
								$this->view->assign('tresult',$tresult);
								die("This is not allowed.");
								
							} else{
						

							//echo "sdsd"; die;	
						  // print_r($_FILES ['myfile'] ['tmp_name']); 					
							if ($ret = (move_uploaded_file ( $_FILES ['myfile'] ['tmp_name'], $target_path1 ))) {
									$a=$_GET['uid'];
									$uploadedFileId = $Lodgereportobj->updateimage($newFileName,$a);
									//echo $uploadedFileId; die;
									$this->view->assign('uploadedFileId',$uploadedFileId);
									$this->view->assign('uuuid',$a);
									$result = 1;
									$this->view->assign('result',$result);
							}else 
							{
								$result = 2; 
								$this->view->assign('result',$result);
							}
							$filename = basename ( $newFileName );	
						}
                }else {
                        $result = - 1; //format not supported
                        $this->view->assign('result',$result);
						//$this->_redirect('/user/userinfo');
						//return false;
                }
           }
           sleep ( 1 );
        }

	public function uploadprofileimageAction()
	{
		$admname = new Zend_Session_Namespace('adminMname');
		$role = new Zend_Session_Namespace('role');
		if(!$role->role){
			$this->_redirect('');
		}
	}

    }


