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

class WebserviceController extends Zend_Controller_Action
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



   public function indexAction(){
    // die("ksdjcfdscf");
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
		$form = new Application_Form_Webservice();
		$form->addform();
		$this->view->form = $form;
		$request = $this->getRequest();
    if ($this->getRequest()->isPost()) {
      if ($form->isValidPartial($request->getPost())) {
         $dataform=$request->getPost();

         //print_r($dataform);die;
        $webservices = new Application_Model_Webservice;
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
               /******** check this for transcation Id *********/
       $countdata = $webservices->check_transcation_id(safexss($dataform['transaction_id']));

       $update_transction_data = $webservices->update_transction_data(safexss($dataform['transaction_id']));
       //print_r($update_transction_data);die("hello");
                /******* end ***********************/
        if($update_transction_data == 1){
          $check_transcation_count=$webservices->check_transcation(safexss($dataform['transaction_id']));
          $get_scheme_name=$webservices->get_scheme_name(safexss($check_transcation_count[0]['scheme_id']));
          $get_state_name=$webservices->get_state_name(safexss($check_transcation_count[0]['state_code']));

					$description = '';
					$description .= 'Web Service: Transaction Enabled</br>';
					$description .= '<span>Request ID:</span>'.$dataform['transaction_id'].'</br>';
          $description .= '<span>Scheme Code:</span>'.$check_transcation_count[0]['scheme_id'].'</br>';
					$description .= '<span>Scheme Name:</span>'.$get_scheme_name.'</br>';
					$description .= '<span>State Name:</span>'.$get_state_name.'</br>';
					$description .= '<span>State Code:</span>'.$check_transcation_count[0]['state_code'].'</br>';
					$description .= '<span>Transaction Date:</span>'.$check_transcation_count[0]['transaction_date'].'</br>';


					$auditlog = array(
					"uid" => $userid->userid,
					"application_type" => 'DBT Web Service Status',
					"description" => $description
					);
					$webservices->insertauditlog($auditlog);

          $this->view->assign('successMsg', "Transaction Enabled sucessfully!");
					$this->_redirect('/webservice/?actmsg=update');
        }elseif($update_transction_data == 0){
          $this->view->assign('errorMsg', 'Transaction Enabled Error');
					$this->_redirect('/webservice/?actmsg=error');
        }elseif($update_transction_data == 2){
          $this->view->assign('errorMsg', 'Transaction ID not avilable for data update');
          $this->_redirect('/webservice/?actmsg=error1');
        }elseif($update_transction_data == 3){
          $this->view->assign('errorMsg', 'Transaction ID not exist');
          $this->_redirect('/webservice/?actmsg=error1');
        }
      }
    }
	}



  public function gettransactiondetailAction(){
    $admname = new Zend_Session_Namespace('adminMname');
    $userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
       $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
      $this->_redirect("");
        }
    $request = $this->getRequest();//create request object
        $transaction_id = safexss($request->getParam('transaction_id'));
        //echo $transaction_id;die;
      if($request->isPost()){
        $webserviceObj = new Application_Model_Webservice;
        $showdata = $webserviceObj->check_transcation_id(safexss($transaction_id));
          print_r($showdata);die;
        $this->view->assign("mainheader",$showdata);
            }
  }

/*******************end***************************************************************/


}
