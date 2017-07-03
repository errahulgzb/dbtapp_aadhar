<?php
require_once 'Zend/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Captcha/Image.php';
class AuthController extends Zend_Controller_Action
{
    protected $controller_name=null;
    protected $method_name=null;
    public function init()
    { 
            //$layout_array=array();
            $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
            $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
            //$this->_helper->layout->disableLayout();
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
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

	
	public function downloadAction()
    {
	
	
	   $this->_helper->layout->disableLayout();
       $this->_helper->viewRenderer->setNoRender();
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=".BASE_PATH."samplecsv/DBT_Portal_DB-Schema.csv"); 
        $pdfiledata = file_get_contents($filename);
        echo $pdfiledata;
		
		
	}
    
    public function indexAction(){
		$userid = new Zend_Session_Namespace('userid');
		if($userid->userid ==''){
			$this->_redirect('/auth/login');		
		}
		$this->_redirect('');
	}
    
    	
    public function pglangAction(){
	     $this->_helper->layout->disableLayout();
		 
		 print_r($_POST);
  }
 

 
	
    
    public function loginAction(){
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname');
		$role = new Zend_Session_Namespace('role');
		if($admname->adminname!=''){
			$this->_redirect('/user/userinfo');		
		}
		$form    = new Application_Form_Auth();
		$this->view->form = $form;
        $request = $this->getRequest();
	    $reportid=safexss($request->getParam('reportid'));
	    $login = safexss($request->getParam('login_as'));
		$story_to = safexss(base64_decode($request->getParam('story_to')));
	    $ben = safexss($request->getParam('ben_as'));	    
		$archive = safexss(base64_decode($request->getParam('archive')));
	    $rprtid = safexss(base64_decode($reportid));
	    if($login){
	    	$login_as = base64_decode($login);
	    }else {
	    	$login_as = "";
	    }
	    if($ben){
	    	$ben_as = base64_decode($ben);
	    }else {
	    	$ben_as = "";
	    }
		$admname = new Zend_Session_Namespace('adminMname');
		$role = new Zend_Session_Namespace('role');
		$userid = new Zend_Session_Namespace('userid');
		$ministryid = new Zend_Session_Namespace('ministryid');
		$state_code = new Zend_Session_Namespace('state_code');
		if($this->getRequest()->isPost()){
				//Do the rest of the processing here
			$request=$this->getRequest();;
			$form = new Application_Form_Auth();
			$dataform=$request->getPost();
			
		if($dataform['vercode'] != $_SESSION["vercode"]){
			//echo "dsd"; die;
			   $msg="Please enter a correct code!";
				$this->view->assign('msg', $msg);
				return false;
		}
		 if($dataform['sessionCheck']!=$captcha->captcha){
						  $msg="This is CSRF attack. Please correct and try again.";
					   $this->view->assign('msg',$msg);
					   return false;
					} 
			$cpobj = new Application_Model_Auth;
			$match  = $cpobj->ckhval($dataform['admin'],$cpobj->findMd5Value($dataform['password']));
			//print_r($match); die;
			if($match[0] == 2){
				$msg="Your account has been deactivated.Please contact site administrator.";
				$this->view->assign('mat', $match[0]);
				$this->view->assign('msg', $msg);
			}
			else if($match[0] == 0){
				$msg="Username or Password is incorrect!";
				$this->view->assign('mat', $match[0]);
				$this->view->assign('msg', $msg);
			}
			else if($match[0] == 1){
				
					////added by vik
					$agencyroles=array(7,8,9,10,11,12);
					////
				
					$admname->adminname=$dataform['admin'];
					$role->role=($match[1]);
					session_regenerate_id();
					$userid->userid=$match[2];
					$ministryid->ministryid=$match[4];
					$state_code->state_code=$match[5];
					$this->view->assign('ad', $admname);
					$this->view->assign('rl', $role->role);	
					$login_statatus = 1;
					$userobj = new Application_Model_Auth;
					$userdata  = $userobj->updateloginstatus($match[2],$login_statatus);
					
					
					$lastLogin = new Application_Model_User;
					$lastloginupdated = $lastLogin->getuserLastLogin($userid->userid);
			
						if($role->role==1){
							$this->_redirect('/user/userview');
						}else if($role->role==4){
							$this->_redirect('/schemeowner/schemeview');
						}else if($role->role==6){
							$this->_redirect('schemeowner/schemeview');
						}
						else if($role->role==12){
							$this->_redirect('schemeowner/schemeview');
						}
						else{
							$this->_redirect("");
						}
			}
		}
	}

	public function homeAction()
    {
		$admname = new Zend_Session_Namespace('adminMname');
		if($admname->adminname==''){
		
		$this->_helper->layout->setLayout('cp');
		$this->_redirect('/cp');
		
		}
		//$admname = new Zend_Session_Namespace('adminMname');
		$role = new Zend_Session_Namespace('role');
		//echo $admname->adminname; die;
		//echo $role->role; die;
		$this->view->assign('ad', $admname->adminname);
		$this->view->assign('rl', $role->role);
		$this->_helper->layout->setLayout('home');
	}
	public function logoutAction()
    {
		//echo "Before Regenerate". session_id();
		$userid = new Zend_Session_Namespace('userid');
		$userid = $userid->userid;
		$login_statatus = 0;
		$userobj = new Application_Model_Auth;
		$userdata  = $userobj->updateloginstatus($userid,$login_statatus);
		//$this->_helper->layout->disableLayout();	
		Zend_Session::destroy(true);
		
		$sessid = session_regenerate_id();
		setcookie("PHPSESSID",$sessid,time()-3600,"/");
		session_unset();
		//echo "Before destroy". session_id();
		session_destroy();
		//echo "After destroy". session_id();	
//die;
	session_write_close();
		setcookie(session_name(),'',0,'/');
		session_regenerate_id(true);
		$this->_redirect();
	}
}
?>

