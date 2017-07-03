<?php
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
class PageController extends Zend_Controller_Action
{

    public function init()
    { 
                $rolearray = array('1','4');
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname');
                $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');		
               }elseif(($admname->adminname!='')&&($this->method_name=='frontcontentview')){
                    $this->_helper->layout->setLayout('sites/layout'); 
               }elseif($role->role==1 || $role->role==4){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
			  //$layout = $this->_helper->layout();
			  $ajaxContext = $this->_helper->getHelper('AjaxContext');
			  $ajaxContext->addActionContext('pagelang', 'html')->initContext();
			  $request = $this->getRequest();
			  //print_r( $request);
			  if($request->getParam('action')=="index"){
				$this->_redirect('/');
			 }

    }
	
public function pagelangAction(){
	    // $this->_helper->layout->disableLayout();
	$this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
		// print_r($_GET);
		 
		 $language = $_GET['language'];
		// echo $language;
		 
		 $slang = new Zend_Session_Namespace('languageold');
		 $slang ->language = $language;
		//EXIT;
	      //  $request = $this->getRequest();
			//$language	= $request->getParam('language');
			
			//echo $language;
			//die;
	  
	  
  }
 public function pageschemeAction()
  {
	  //$search = $_GET['title'];
	$request = $this->getRequest();
	$langname = $request->getParam('lang');
	
	  $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
				$this->rolearray = array("1");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
               
   
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
    	
   
	
	$cmi_list = new Application_Model_Page;
  

 	
	$cmishow_list = $cmi_list->pageschemelist($langname);
	//print_r($cmishow_list);
	//echo $cmishow_list;

	$this->view->assign('pagedata', $cmishow_list);
	
	$this->view->assign('counttotalcmireports', $countcmi);
	  
  }
 
 
public function pageviewAction()
  {
	
	//$search = $_GET['title'];
	$request = $this->getRequest();
	$langname = $request->getParam('lang');
	
	  $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){
                 $this->_redirect('');
            }
			
				$this->rolearray = array("1");
            $role = new Zend_Session_Namespace('role');	
            if(!in_array($role->role,$this->rolearray)){
                $this->_redirect('');
            }	
               
   
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
    	
   
	
	$cmi_list = new Application_Model_Page;
  

 	
	$cmishow_list = $cmi_list->pagelist($langname);
	//print_r($cmishow_list);
	//echo $cmishow_list;

	$this->view->assign('pagedata', $cmishow_list);
	
	$this->view->assign('counttotalcmireports', $countcmi);
	}



	public function frontcontentviewAction(){
		//$this->_helper->layout->setLayout('admin/layout');
		 $slang = new Zend_Session_Namespace('languageold');
		 $langid =   $slang ->language;
		$request = $this->getRequest();
		$contentId = safexss(base64_decode($request->getParam('id')));
		$cmi_list = new Application_Model_Page;
		if($langid==1){
			$cmishow_list = $cmi_list->frontpageContentViewhindi($contentId);
		}
		else{
			$cmishow_list = $cmi_list->frontpageContentView($contentId);
		}
		if($langid  == 1){
			$pagecnt = $cmi_list->getpagetitlehindi($contentId);
		}else{
			$pagecnt = $cmi_list->getpagetitle($contentId);
		}
		foreach($pagecnt as $k=>$v)
             {
		      $titlebrdcrumn  = $v['title'];
			 
			  $this->view->assign('titlebrdcrumn', $titlebrdcrumn);
              } 

		if($cmishow_list["title"]!=""){
			$this->view->assign('title', $cmishow_list["title"]);
			$this->view->assign('description', $cmishow_list["description"]);
			$this->view->assign('idpage', $contentId);
			 $this->view->assign('langid', $langid);
		}
		else{
				$this->_redirect('/');
		}
	}
	
	
	

}