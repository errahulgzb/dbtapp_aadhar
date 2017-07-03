<?php
require_once 'Zend/Session.php';
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class SchemeviewController extends Zend_Controller_Action
{
  public function init()
      {
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                /*if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');   
               }elseif($role->role==1 || $role->role==4){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }*/
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');   
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
      }
    public function totalfundtransferAction(){
      //echo "Aaaaa";exit;
      $role = new Zend_Session_Namespace('role');
      $schemeview = new Application_Model_Schemeview;
      $amountinbulk = $schemeview->schmefinddata();
      $this->view->assign("schemewisedata", $amountinbulk);
      $this->view->assign("roleget", $role->role);
    }
    public function schemedetailsAction(){
      
    }
}
?>