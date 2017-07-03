<?php
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
require_once('Zend/Mail/Transport/Smtp.php');
require_once 'Zend/Mail.php';


class ForgotController extends Zend_Controller_Action
{
    public function init()
    { 
        $admname = new Zend_Session_Namespace('adminMname');
        $role = new Zend_Session_Namespace('role');	
        $this->_helper->layout->setLayout('sites/layout');
    }
	
	   public function forgotAction(){
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$role = new Zend_Session_Namespace('role');
		if(!empty($role->role)){
				$this->_redirect('');	
			}
        $form    = new Application_Form_Forgot();
        $form->addform();
        $this->view->form = $form;
        $request=$this->getRequest();
        if($this->getRequest()->isPost()){
            if ($form->isValidPartial($request->getPost())){
                $dataform=$request->getPost();
				  if($dataform['vercode']!= $_SESSION["vercode"]){
						$msg="Please enter a correct code!";
						$this->view->assign('msg',$msg);
						return false;
		           }
				if($dataform['sessionCheck']!=$captcha->captcha){
					$this->view->assign('msg',CSRF_ATTACK);
					return false;
				}
                $cpobj = new Application_Model_Forgot;
                $match  = $cpobj->password(trim($dataform['username']));
				$temp = $cpobj->encryptpassword($dataform['username']);
				$id = $match['id'];
				$path =WEB_LINK.'forgot/resetpassword?userid='.base64_encode($id).'&login_temp='.$temp;
                $aa = count($match);
                if($aa == 0){
                        $message="This username doesn't match with the database.";
                        $this->view->assign('msg', $message);
                    }
                else{
                    $mailObj = new Zend_Mail(); // obj form zend mail
                    $msg="Dear ".$match['firstname'].",<br /><br />Please take a note of your account details and keep them safe with you. You are requested to change<br />your password immediately. Please click on <a href='".$path."'>Reset Password</a> to change your password. <br/><br/>
					Regards,<br />DBT Bharat Team<br />
					Website: ".WEB_LINK."<br />
					(This is a system generated message. Please do not reply to this email)";
					//echo $msg;exit;
                    $subject= FORGOT_SUBJECT; // set subject title
                    $from = MAIL_FROM; // set from title
                    $mailObj->setSubject($subject); // call setSubject() Method
                    $mailObj->setBodyHtml($msg); // call setBodyHtml() Method
                    if($match['email']!=''){
                        $to1=trim($match['email']);
                        $mailObj->addTo($to1, $match['email']); // call addTo() Method
                    }
                    //$mailObj->setFrom($from, $name = $match['firstname']); // call setFrom() Method
					$mailObj->setFrom($from, "DBT APP");
                    $mailObj->send(); // Send Mail
                    $this->_redirect('/auth/login?message=sentmail'); // page redirect
                    }
                }
            }

        } 
		
		 public function resetpasswordAction(){
			$form = new Application_Form_Forgot();
			$form->resetpasswordform();
			$this->view->form = $form;
			$request = $this->getRequest();
			if(!$request->getParam('userid')){
				$this->_redirect("");
				//echo "aaaa";exit;
			}
			//echo $request->getParam('userid');
			if(!$request->getParam('login_temp')){
				$this->_redirect("");
				//echo "nnnn";exit;
			}
			//echo $request->getParam('userid')."1111".$request->getParam('temp_pass');exit;
			$temp_pass = safexss($request->getParam('login_temp'));
			$userid = safexss(base64_decode($request->getParam('userid')));
			//echo $userid; 
			$captcha = new Zend_Session_Namespace('captcha');
		   $captcha->captcha = session_id();	
			$role = new Zend_Session_Namespace('role');
			if(!empty($role->role)){
			     $this->_redirect('');	
			}
		   $useridnew = safexss($request->getParam('userid'));
			if(empty($useridnew)){
			     $this->_redirect('');	
			}
			$this->view->assign('userid', $useridnew);
			$this->view->assign('login_temp', $temp_pass);
			 if($this->getRequest()->isPost()){
				if($form->isValidPartial($request->getPost())){
					$dataform=$request->getPost();
		/*****check for length for new password and confirm password*******/
					//echo "aaa";exit;
					$newpass = strlen(trim($dataform['newpassword']));
				    $conformnewpassword = strlen(trim($dataform['conpass']));
					if($newpass<8){
							$msg="New Password field will take minimum 8 characters!";
							$this->view->assign('msg', $msg);
							return false;
						}
					if($conformnewpassword<8){
							$msg="Confirm Password field will take minimum 8 characters!";
							$this->view->assign('msg', $msg);
							return false;
						}
						
					 /**********vallidation to check captcha code 26th july ************/
					if($dataform['vercodene']!= $_SESSION["vercode"]){
							$msg="Please enter a correct code!";
							$this->view->assign('msg', $msg);
							return false;
					}
					if($dataform['sessionCheck']!=$captcha->captcha){
							$this->view->assign('msg',CSRF_ATTACK);
							return false;
					}
					
					$cpobj = new Application_Model_Forgot;
					$match = $cpobj->checkuser($userid,$temp_pass);
					$aa = count($match);
					//echo $userid."----".$aa;exit;
					if($aa == 0){
						$message="Sorry! Unable to change password.";
						$this->view->assign('msg', $message);
					}
					else{
						$match  = $cpobj->updateuser($dataform,$userid);
							if($match){
							  $this->_redirect('/auth/login?message=updatepass');
						    }
					}
				}
			 }	
			 
			 
		 }
	


        
}
?>
