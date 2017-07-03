<?php
require_once 'Zend/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class ResetpasswordController extends Zend_Controller_Action
{
   
    
    public function indexAction()
        {
        $this->_helper->layout->disableLayout();
        
        }
    
    
    public function ResetpasswordAction()
        {
	    $this->_helper->layout->disableLayout();
        $form    = new Application_Form_Forgot();
        $form->addform();
        $this->view->form = $form;
        $request=$this->getRequest();
      
        if($request->getParam('actmsg')=='forgotpass'){
            //$this->view->assign('successMsg', 'Password has been sent to your email Id.');
        }
        if ($this->getRequest()->isPost())
            {
            if ($form->isValidPartial($request->getPost()))
                {
                $dataform=$request->getPost();
                $cpobj = new Application_Model_Forgot;
                $match  = $cpobj->password($dataform['username']);
                $aa = count($match);
                if($aa == 0)
                    {
                        $message="Username does not exist.";
                        $this->view->assign('errorMsg', $message);
                    }
                else{
                    $pass = $cpobj->createcode(8);
                    //$update_table_pass = $cpobj->updatedetails($pass,$dataform['username']);

                     //$link = $this->url(array('controller' => 'comments', 'action' => 'add'));
                    $link = $this->_helper->url->url(array("controller" => "forgot", "action" => "forgot", "params"=>array("id"=>"asdfjfhajsd","base"=>"juju")));

                    $mailObj = new Zend_Mail(); // obj form zend mail
                    $msg="Dear ".ucfirst($match['firstname']).",<br /><br />Please note your account details and keep them safe with you. You are requested to change your password immediately. Please click o below link or copy and paste into browser to reset your password.<br /><br />".$link."<br /><br />Thanks & Regards,<br />Data Center Team <br /><br />Website: http://180.151.3.109/velocisdatacenter<br /><br />This is a system generated mail. Please do not reply on this address. ";
                    echo $msg."sssss";exit;
                    $subject='Password Reset | DBT'; // set subject title
                    $from='<contactus@dbt.in>'; // set from title
                    $mailObj->setSubject($subject); // call setSubject() Method
                    $mailObj->setBodyHtml($msg); // call setBodyHtml() Method
                    if($match['email']!='')
                        {
                        $to1=$match['email'];
                        $mailObj->addTo($to1, $match['email']); // call addTo() Method
                        }
                    $to1 = "upendra.yadav@velocis.co.in";
                    $mailObj->setFrom($from, $name = 'DBT'); // call setFrom() Method
                    $mailObj->send(); // Send Mail
                    //$this->_redirect('/auth/login?message=sent'); // page redirect
                    $this->view->assign('successMsg', $to1);
                    }

                }
            }

        }
       
}
?>
