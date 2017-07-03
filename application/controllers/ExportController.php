<?php
//these are the files to must include for the controller
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
//file incusion end here

Class ExportController extends Zend_Controller_Action{
	protected $rolearray = array("1","4","6");
	function init(){
            $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
            $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
            /* Initialize action controller here */
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif($role->role==1){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
				  $this->_helper->layout->setLayout('admin/layout');
               }
		
		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('districtfind', 'html')->initContext();
	}
	public function pr_man($data = null, $pm = null){
		echo "<pre>";print_r($data);echo "</pre>";
		if($pm == 1){
			exit;
		}
	}
	

//BElow function is displaying State to the annonymous user and all
	public function showstateAction(){
		$model = new Application_Model_DbtState();
		$statedata = $model->getAllState();
		$this->view->assign("statearr",$statedata);
	}
//BElow function is displaying District to the annonymous user and all
	public function showdistrictAction(){
		$model = new Application_Model_DbtState();
		$distdata = $model->getAllDist();
		$this->view->assign("distarr",$distdata);
	}
//BElow function is displaying BLock to the annonymous user and all
	public function showblockAction(){
		$model = new Application_Model_DbtState();
		$blockdata = $model->getAllBlock();
		$this->view->assign("blockarr",$blockdata);
	}
//Below function is displaying Village to the annonymous user and all
	public function showvillageAction(){
		$model = new Application_Model_DbtState();
		$villdata = $model->getAllVill();
		$this->view->assign("villarr",$villdata);
	}
}
?>