<?php
//these are the files to must include for the controller
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
//require_once 'Zend/XmlRpc/Generator/XmlWriter.php';
//file incusion end here

Class MisreportController extends Zend_Controller_Action{
	protected $rolearray = array("1","4","6","12");
	function init(){
        $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        /* Initialize action controller here */
            $role = new Zend_Session_Namespace('role');
            $admname = new Zend_Session_Namespace('adminMname'); 
            if($admname->adminname==''){}
			   else if(($role->role==1) || ($role->role==4) || ($role->role==6)  || ($role->role==12)){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
				  $this->_helper->layout->setLayout('admin/layout');
               }
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('districtfind', 'html')->initContext();
        $ajaxContext->addActionContext('districtwiseblock', 'html')->initContext();
		$ajaxContext->addActionContext('blockwisevillage', 'html')->initContext();
		$ajaxContext->addActionContext('panchayatwisevillage', 'html')->initContext();
		$ajaxContext->addActionContext('exportcsvdata', 'html')->initContext();
		$ajaxContext->addActionContext('exportxmldata', 'html')->initContext();
		$ajaxContext->addActionContext('getcurrentschemetype', 'html')->initContext();
	}
	public function pr_man($data = null, $pm = null){
		echo "<pre>";print_r($data);echo "</pre>";
		if($pm == 1){
			exit;
		}
	}
public function districtfindAction(){
             $request = $this->getRequest();
              $state_code = safexss($request->getParam('state_code'));  
               if ($this->getRequest()->isPost()){       
               //$state_code = 09;
               $state_show = new Application_Model_DbtState;
                 $showdetails = $state_show->statewisedistrict($state_code);
				 //echo $showdetails;exit;
                 $html = "";
                 $html .='<option value="0">==Select District==</option>';
                  foreach ($showdetails as $key => $val)
                      {
                       $html .='<option value="'.$val['distcode'].'">'.$val['district'].'</option>';
                     }    
                     echo $html;    
                exit;
                 //$this->view->assign('data', $showdetails);
             }
    }
    public function districtwiseblockAction(){
             $request = $this->getRequest();
             $district =safexss($request->getParam('district'));  
               //$district = 55;
               // echo $district;
               // exit;
               if ($this->getRequest()->isPost()){       
               $block_show = new Application_Model_DbtState;
                 $showdetails = $block_show->districtwiseblock($district);
                //  echo $district;
                // exit;
                 $html = "";
                 $html .='<option value="0">==Select Block==</option>';
                  foreach ($showdetails as $key => $val)
                      {
                       $html .='<option value="'.$val['subdistrict_code'].'">'.$val['subdistrict_name'].'</option>';
                     }    
                     echo $html;    
                exit;
                 //$this->view->assign('data', $showdetails);
             }
    }
	public function blockwisevillageAction(){
             $request = $this->getRequest();
             $block =safexss($request->getParam('block'));  
               //$district = 55;
               // echo $district;
               // exit;
               if ($this->getRequest()->isPost()){      
               $panchayat_show = new Application_Model_DbtState;
                 $showdetails = $panchayat_show->blockwisevillage($block);
                //  echo $district;
                // exit;
                 $html = "";
                 $html .='<option value="0">==Select Village==</option>';
                  foreach ($showdetails as $key => $val)
                      {
                       $html .='<option value="'.$val['village_code'].'">'.$val['village_name'].'</option>';
                     }    
                     echo $html;    
                exit;
             }
    }
  public function pagination_search($nume = null,$start = null, $limit = null){
		if($nume > $limit){
        $page_name = "#";
        $paginate="";
        $paginate.='<ul class="pagination">';
        $i=0;
        $l=1;
        for($i=0; $i < $nume; $i=$i+$limit){
          if($i <> $start){
            $paginate.='<li><a href="javascript:void(0)" id="'.$i.'" class="text">'.$l.'</a></li>';
          } else if($i == $start) {
             $paginate.='<li><a href="javascript:void(0)" id="'.$i.'" class="text">'.$l.'</a></li>';
          }
          $l=$l+1;
        }
          $paginate.='</ul>';
          return $paginate;
        }
    }
	
	public function csvexportmethodAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 //exit;
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			//exit;
        }
		$request = $this->getRequest();
		if(!$request->getParam('scheme_id')){
			//exit;
		}
		if(!$request->getParam('min_id')){
			//exit;
		}
		if(!$request->getParam('month')){
			$month = null;
		}
		if(!$request->getParam('year')){
			$year = null;
		}
		$scheme_id = safexss($request->getParam('scheme_id'));
		$min_id = safexss($request->getParam('min_id'));
		$month = safexss($request->getParam('month'));
		$year = safexss($request->getParam('year'));
		
		if($scheme_id == "" && $min_id == ""){
			exit;
		}else{
			$dbobj = new Application_Model_Schemeimport();
			$dataassign = $dbobj->csvexportmethoddb($scheme_id,$min_id,$month,$year);
			$schemename = $dbobj->getschemename(base64_decode($scheme_id));
			$this->view->assign("exportarray",$dataassign);
			$this->view->assign("schemename",$schemename);
		}
	}
	
	
	
	
	
	
	
	public function schemereportAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect("");
        }
		$form = new Application_Form_SchemeReport();
		$form->getdropdown();
		$this->view->form = $form;
	}
	
//below functon are using for the display all the scheme according to the ministry selection	
	public function getallschemeAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect("");
        }
		//echo $userid->userid;exit;
		$request = $this->getRequest();
        $min_id = safexss($request->getParam('min_id'));
			if($this->getRequest()->isPost()){
				$misObj = new Application_Model_Misreport;
				$showdetails = $misObj->Getscheme($userid->userid,$min_id, "","",$role->role);
                $html = "";
                $html .='<option value="0">==Select Scheme==</option>';
                 foreach ($showdetails as $key => $val){
                     $html .='<option value="'.$val['scm_id'].'">'.$val['scm_name'].'</option>';
                    }
                    echo $html;
                exit;
             }
	}
	
	
	
//this function is displaying the record of the scheme related	
	public function getschemedetailsAction(){
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
        $min_id = safexss($request->getParam('min_id'));
		$scheme_id = safexss($request->getParam('scm_id'));
		
		if(!$request->getParam('start')){
			$start = 0;
		}else{
			$start = $request->getParam('start');
		}
		$limit = 400;
			if($request->isPost()){
				$st 	= safexss($request->getParam('state_code'));
				$dt 	= safexss($request->getParam('district_code'));
				$bl 	= safexss($request->getParam('block_code'));
				$vl 	= safexss($request->getParam('village_code'));
				$gender = safexss($request->getParam('gender'));
				$ft 	= safexss($request->getParam('fund_transfer'));
				$tb 	= safexss($request->getParam('transfer_by'));
				$ans 	= safexss($request->getParam('aadhar_num_status'));
				$bas 	= safexss($request->getParam('bank_account_status'));
				
				if(!$request->getParam('todate')){
					$todate = 0;
				}else{
					$todate = $request->getParam('todate');
				}
				
				if(!$request->getParam('fromdate')){
					$fromdate = 0;
				}else{
					$fromdate = $request->getParam('fromdate');
				}
				
				$misObj = new Application_Model_Misreport;
				$showdetails = $misObj->GetSchemeData($min_id,$scheme_id,$st,$dt,$bl,$vl,$gender,$ft,$tb,$todate,$fromdate,$ans,$bas,$start,$limit);
				$this->view->assign("schemerecord",$showdetails);
		//calling the function for the pagination
				$count = $misObj->CountSchemeDataJoin($min_id, $scheme_id,$st,$dt,$bl,$vl,$gender,$ft,$tb,$todate,$fromdate,$ans,$bas);
				$pagination=$this->pagination_search($count,$start,$limit);
				$this->view->assign('pagination', $pagination);
				$indexing = ($start) + 1;
				$this->view->assign("totalrecordcount",$count);
                $this->view->assign("indexof",$indexing);
		//displaying record number in span like {1-10 out of 100}
				$this->view->assign("startof",$start);
				$datacount = count(array_filter($showdetails));
				$this->view->assign("currpos",$datacount);
				if($count == 0){
					$setMet = 0;
				}else if($count > 0){
					$setMet = 1;
				}
				$this->view->assign("setMet",$setMet);
				//exit;
            }
	}
	
	
	
	
	
//this function is displaying the record of the scheme related header value
	public function getschememainAction(){
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
        $min_id = safexss($request->getParam('min_id'));
		$scheme_id = safexss($request->getParam('scm_id'));
			if($request->isPost()){
				$misObj = new Application_Model_Misreport;
				$showdata = $misObj->GetMainSchemeData($min_id, $scheme_id);
              
				$this->view->assign("mainheader",$showdata);
            }
	}

	//function is using for the export csv record		
	public function exportxmldataAction(){
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
        $min_id = safexss($request->getParam('min_id'));
		$scheme_id = safexss($request->getParam('scm_id'));
			//if($request->isPost()){
				$st = safexss($request->getParam('state_code'));
				$dt = safexss($request->getParam('district_code'));
				$bl = safexss($request->getParam('block_code'));
				$vl = safexss($request->getParam('village_code'));
				$gender = safexss($request->getParam('gender'));
				$ft = safexss($request->getParam('fund_transfer'));
				$tb = safexss($request->getParam('transfer_by'));
				
				if(!$request->getParam('todate')){
					$todate = 0;
				}else{
					$todate = $request->getParam('todate');
				}
				
				if(!$request->getParam('fromdate')){
					$fromdate = 0;
				}else{
					$fromdate = $request->getParam('fromdate');
				}
				
				$todate = 0;
				$fromdate = 0;
				
				$start = $limit = null;
				$ans = safexss($request->getParam('aadhar_num_status'));
				$bas = safexss($request->getParam('bank_account_status'));
				//echo $ans.' '.$bas;die;
				$misObj = new Application_Model_Misreport;
				$scm_name = $misObj->getschemename($scheme_id);
				$showdetails = $misObj->GetSchemeDataExportxml($min_id,$scheme_id,$st,$dt,$bl,$vl,$gender,$ft,$tb,$todate,$fromdate,$ans,$bas);
				$this->view->assign("schemerecord",$showdetails);
				$this->view->assign("scheme_name",$scm_name[0]['scheme_name']);
          //  }
	}
	
//function is using for the export csv record	
	public function exportcsvdataAction(){
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
        $min_id = safexss($request->getParam('min_id'));
		$scheme_id = safexss($request->getParam('scm_id'));
				$st = safexss($request->getParam('state_code'));
				$dt = safexss($request->getParam('district_code'));
				$bl = safexss($request->getParam('block_code'));
				$vl = safexss($request->getParam('village_code'));
				$gender = safexss($request->getParam('gender'));
				$ft = safexss($request->getParam('fund_transfer'));
				$tb = safexss($request->getParam('transfer_by'));
				
				
				if(!$request->getParam('todate')){
					$todate = 0;
				}else{
					$todate = $request->getParam('todate');
				}
				
				if(!$request->getParam('fromdate')){
					$fromdate = 0;
				}else{
					$fromdate = $request->getParam('fromdate');
				}
				
				
				$todate = 0;
				$fromdate = 0;
				
				$start = $limit = null;
				$ans = safexss($request->getParam('aadhar_num_status'));
				$bas = safexss($request->getParam('bank_account_status'));
				//echo $ans.' '.$bas;die;
				$misObj = new Application_Model_Misreport;
				$scm_name = $misObj->getschemename($scheme_id);
				$showdetails = $misObj->GetSchemeDataExport($min_id,$scheme_id,$st,$dt,$bl,$vl,$gender,$ft,$tb,$todate,$fromdate,$ans,$bas);
				$this->view->assign("schemerecord",$showdetails);
				$this->view->assign("scheme_name",$scm_name[0]['scheme_name']);
				$this->view->assign("scheme_codification",$scm_name[0]['scheme_codification']);
	}

//this function is displaying the record of the scheme type value
	public function getcurrentschemetypeAction(){
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
        $scheme_id = $request->getPost('scm_id');
		//echo $scheme_id;die;
			if($request->isPost()){
				$misObj = new Application_Model_Misreport;
				$showdata = $misObj->getcurrentschemeType($scheme_id);
				if(isset($showdata[0]['scheme_type']) && ($showdata[0]['scheme_type']==1 or $showdata[0]['scheme_type']==3)){
                  echo 1;exit;
				}else if(isset($showdata[0]['scheme_type']) && ($showdata[0]['scheme_type']==2)){
				echo 0;exit;
				}
				
            }
		exit;
	}
}
?>