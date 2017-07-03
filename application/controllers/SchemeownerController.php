<?php
/* Role Definition:
 * 1 => Administrator
 * 4 => Scheme Owner
 */
//these are the files to must include for the controller
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';
//file incusion end here

Class SchemeownerController extends Zend_Controller_Action{
	protected $roleArray = array("1","4","6","12");
	protected $rolearray = array("1","4","6","12");
	
	function init(){
        
            $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
            $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
            /* Initialize action controller here */
                $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                if($admname->adminname==''){
                   //$this->_helper->layout->setLayout('sites/layout');
               }/*elseif(($admname->adminname!='')&&($this->method_name=='schemeview')){
                    $this->_helper->layout->setLayout('sites/layout');
               }*/
			 else if(($role->role==1) || ($role->role==4) || ($role->role==6) || ($role->role==12)){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  //$this->_helper->layout->setLayout('layout'); 
				  $this->_helper->layout->setLayout('admin/layout');
               }
		
		
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('districtfind', 'html')->initContext();
        $ajaxContext->addActionContext('districtwiseblock', 'html')->initContext();
		$ajaxContext->addActionContext('blockwisepanchayat', 'html')->initContext();
		$ajaxContext->addActionContext('blockwisevillage', 'html')->initContext();
		$ajaxContext->addActionContext('panchayatwisevillage', 'html')->initContext();

	}
	public function pr_man($data = null, $pm = null){
		echo "<pre>";print_r($data);echo "</pre>";
		if($pm == 1){
			exit;
		}
	}

	function schemeviewAction(){
    //echo "aaaa";exit;
		$roleArray = array("1","4","6","12");
		//creating the object of current session which are assign from the auth controller
		//this is the session variable initializer into the zend.
        $request = $this->getRequest(); 
		if($request->getParam('actmsg')=='add'){
		$this->view->assign('successMsg', RECORD_INSERTED);
		}
		$userid = new Zend_Session_Namespace('userid');
		$role = new Zend_Session_Namespace('role');
		//echo $role->role;die;
		if($role->role==6 or $role->role==12){
			//$this->_helper->layout->setLayout('admin/layout');
		}
		//print_r($roleArray);exit;
		if((in_array($role->role, $roleArray)) && ($userid->userid)){
			$objAllscheme = new Application_Model_Assignmanager;
			$AllSceme = $objAllscheme->AssignedScheme($userid->userid,$role->role);
			if(is_array($AllSceme)){
				$this->view->assign("dataFound",$AllSceme);
			}else{
				$this->view->assign("dataFound",$AllSceme);//it will throw No record found!
			}
		}else{
			$this->_redirect("");
		}
	}
	
  public function pagination_search($schmename = null,$scheme_id = null,$nume = null,$year = null,$month= null, $start = null,$limit = null,$page_name=null){
//echo $_GET['validate_status'];die;
		if($nume > $limit){
        $page_name = $page_name.'?search='.$_GET['search'].'&scheme_id='.$_GET['scheme_id']."&min_id=".$_GET['min_id']."&scm_type=".$_GET['scm_type']."&year=".$year."&month=".$month."&status=".$_GET['status']."&benef_id=".$_GET['benef_id']."&name=".$_GET['name']."&dob=".$_GET['dob']."&validate_status=".$_GET['validate_status'];
//echo $page_name;die;
        $this1 = $start + $limit; 
        $back = $start - $limit; 
        $next = $start + $limit;
        $paginate="";
        $paginate.='<ul class="pagination">';
        if($back >=0){
          $paginate.='<li><a href="'.$page_name.'&start='.$back.'" class="head2">&lt; PREV</a></li>';
        }
        $i=0;
        $l=1;
        for($i=0;$i < $nume;$i=$i+$limit){
          if($i <> $start){
            $paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
          } else {
            $paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
          }
          $l=$l+1;
        }
        // echo "Aaaaa";exit;
        if($this1 < $nume) {
          $paginate.='<li><a href="'.$page_name.'&start='.$next.'" class="head2">NEXT &gt;</a></li>';
        }
        $paginate.='</ul>';
		//print_r($paginate);die;
        $this->view->assign('paginate', $paginate);
        }
    }
    public function schemeownereditAction(){

    }
	
	
//Below function is using for the import beneficiaries details	
    public function importschemeAction(){
		$admname = new Zend_Session_Namespace('adminMname'); 
        $role = new Zend_Session_Namespace('role');
        $userid = new Zend_Session_Namespace('userid');
       	$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
        if(in_array($role->role, $this->roleArray)){
            $request = $this->getRequest();
			if($request->getParam('actmsg')=='filesizeerror'){
				$this->view->assign('errorMessage', FILE_SIZE_ERROR_2MB);
			}elseif($request->getParam('actmsg')=='fileformaterror'){
				$this->view->assign('errorMessage', FILE_FORMAT_ERROR);
			}
            $form = new Application_Form_Schemeowner();
            $schemeImport = new Application_Model_Schemeimport;
            $scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
            $tb_name = $schemeImport->getTable($scheme_id);
            $schemenm = safexss(base64_decode($request->getParam('scheme_name')));
			
			$scheme_code = $schemeImport->getschemecode($scheme_id);
			$this->view->assign('scheme_code', $scheme_code);
			
		//below data for the assign param in url
		
		$escheme_id = safexss($request->getParam('scheme_id'));
		$emin_id = safexss($request->getParam('min_id'));
		$estatus = safexss($request->getParam('status'));
		$escm_type = safexss($request->getParam('scm_type'));
		//scheme_id=MQ==&min_id=MQ==&status=0&scm_type=MQ==
		$red = "scheme_id=".$escheme_id."&min_id=".$emin_id."&scm_type=".$escm_type."&status=".$estatus;
		
		
		$escheme_name = safexss($request->getParam('scheme_name'));
		$dscheme_name = safexss(base64_decode($request->getParam('scheme_name')));
		$this->view->assign("scheme_name",$dscheme_name);
		$escm_type = safexss($request->getParam('scm_type'));
		
		$paramid = "scheme_id=".$escheme_id."&scheme_name=".$escheme_name."&min_id=".$emin_id."&scm_type=".$escm_type."&status=".$estatus;
		//upper data for the assign param in url
			
		
			
            $data = $schemeImport->findSchemeName($scheme_id);
            $this->view->assign('schmid',safexss(base64_encode($scheme_id)));
            $this->view->assign('schemenm',safexss(base64_encode($schemenm)));
            $this->view->assign("dataofscheme",$schemenm);
            $this->view->assign("scheme_name",$data[0]['scheme_name']);
            $scheme_type=$data[0]['scheme_type'];
            $somedata = array("scheme_id"=>$scheme_id);
            $form->importscheme();
            $form->populate($somedata);
            $this->view->form = $form;
			if ($this->getRequest()->isPost()) {
                $dataform=$request->getPost();
			/**********vallidation to check captcha code 26th july ************/
            if($dataform['vercode'] != $_SESSION["vercode"]){
                $msg="Please enter a correct captcha code!";
                $this->view->assign('errorMessage', "Please enter a correct captcha code!");
                return false;
            }
			if($dataform['sessionCheck']!=$captcha->captcha){
                $this->view->assign('errorMessage',CSRF_ATTACK);
                return false;
            }
            $tablename = $tb_name;//creating here the tablename to insert
            $filename = $_FILES['importfile']['name'];
			$fileFormat = array ('csv','CSV');
            $fieltempval = 0;
            $allow_extension_only=array('application/vnd.ms-excel','text/csv','text/CSV');
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
			$qstring="scheme_id=".base64_encode($scheme_id)."&scheme_name=".base64_encode($schemenm);
            if(in_array(end( explode ( '.', $filename)), $fileFormat) && $fieltempval == 0){
				//$target_path = $_SERVER['DOCUMENT_ROOT'].'sms/data/schemedata/';
				$target_path = DOCUMENT_ROOT.'data/schemedata/';
				$target_path1 = $target_path . basename($fTemp);
				$target_path2 = $target_path . basename($fTemp1);
				$data = file_get_contents($_FILES['importfile']['tmp_name']);
				//echo "<pre>";print_r($data);die;
                $dataCheck = substr($data,0,2);
                if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == "" ){
					//echo $dataCheck."---".$data;exit;
                    $this->_redirect('/schemeowner/importscheme?actmsg=fileformaterror&'.$paramid);
                }else{
					$filename = $_FILES["importfile"]["name"];                   
					$fileFormat = array ('csv','CSV');
					if(in_array(end(explode ('.', $filename)), $fileFormat)){
					//$target_path = $_SERVER['DOCUMENT_ROOT'].'sms/data/schemedata/'.time().$filename;
					$target_path = DOCUMENT_ROOT.'data/schemedata/'.time().$filename;
					move_uploaded_file($_FILES['importfile']['tmp_name'], $target_path);
					$fp = fopen($target_path, "r");
					$skip = '0';
					//$start = $dataform['year'];
					//this is the current financial year end here
					$dataGet = $schemeImport->findSchemeName($dataform['scheme_id']);
					if(count($dataGet) > 0){
						$nameofScheme = $dataGet['0']['scheme_name'];
					}else{
						$this->_redirect("/schemeowner/schemeview?notfound=notfound");
					}
				   $host = $_SERVER['HTTP_HOST'];
				  // $target_pathnew = $host."/".BASE_PATH."/data/schemedata/".time().$filename;
				   $target_pathnew = "/sms/data/schemedata/".time().$filename;
				   $path = "<a href='".$target_pathnew."'>".$filename."</a>";
				   //$schemeImport->InsertauditdataCSV($userid->userid,$dataform['scheme_id'],time().$filename);
					$tablename = $tb_name;
					$scheme_id = $dataGet['0']['id'];
					$val = 0;
					$dataform = "";
					/******* code to insert in the audit log*********/
					$min_id = base64_decode($request->getParam('min_id'));
					$mindetail = $schemeImport->getministryname($min_id);
					$minname = $mindetail[0]['ministry_name']; 
					$schemeidobj = new Application_Model_Auditlog();
					$description = '';	
					$description.= 'Transaction file upload</br>';	
					$description.= '<span>Ministry:</span>'.$minname.'</br>';					
					$description.= '<span>Scheme:</span>'.$dscheme_name.'</br>';
					//$description.= '<span>File:</span>'.$path.'</br>';
					$auditlog = array(
					"uid" => $userid->userid,
					"application_type" => 'Transaction file upload',
					"description" => $description
					); 
					$schemeidobj->insertauditlog($auditlog); 
						/*******end***********************/
						
						
					//Below variable is use to assigned already aadhar existance into database;	
					$existance = $notsave =$lenaadhaar= 0;
					//Above variable is use to assigned already aadhar existance into database;	
					while(($data= fgetcsv($fp, 1000, ",")) !== FALSE ){
						if($skip > 1){
					//echo "<pre>";print_r($data);die;
							//$dateg = date("d",strtotime($data[27]));
							//$monthg = date("m",strtotime($data[27]));
							//$yearg = date("Y",strtotime($data[27]));
							//$data[1] = "22-10-2017";
							//echo date("Y-m-d",strtotime($data[1]));;exit;
							$dataform = array(
								'beneficiary_title'=> safexss($data[0]),
								'name'=> safexss($data[1]),
								'uniq_user_id' => '',
								'dob' => $data[2],
								'gender' => safexss($data[3]),
								'aadhar_num' => safexss($data[4]),
								'aadhar_seeded' => safexss($data[5]),
								'mobile_num' => safexss($data[6]),
								'email_id' => $data[7],
								'scheme_specific_unique_num' => safexss($data[8]),
								'scheme_specific_family_num' => safexss($data[9]),
								'home_address' => safexss($data[10]),
								'village_code' => safexss($data[11]),
								'village_name' => safexss($data[12]),
								//'panchayat_code' => $data[13],
								//'panchayat_name' => $data[14],
								'block_code' => safexss($data[15]),
								'block_name' => safexss($data[16]),
								'district_code' => safexss($data[17]),
								'district_name' => safexss($data[18]),
								'state_code' => safexss($data[19]),
								'state_name' => safexss($data[20]),
								'pincode' => safexss($data[21]),
								'ration_card_num' => safexss($data[22]),
								'tin_family_id' => safexss($data[23]),
								'bank_account' => safexss($data[24]),
								'ifsc' => safexss($data[25]),
								'bank_name' => safexss($data[26]),
								'beneficiary_regional_lang' => safexss($data[27]),
								'beneficiary_type' => safexss($data[28]),
								//'amount' => $data[25],
								//'fund_transfer' => $data[26],
								//'transaction_date' => $data[27],
								//'year' => $yearg,
								//'month' => $monthg,
								//'day' => $dateg,
								'scheme_id' => $scheme_id
							);
				//echo (strlen(trim($data[4])));print_r($dataform);die;
							if((trim($data[4]) != "") && (strlen(trim($data[4])) == 12) && is_numeric(trim($data[4])) && trim($data[2]) != "" && trim($data[3]) != "" && is_numeric(trim($data[6])) && is_numeric(trim($data[21])) && is_numeric(trim($data[24]))!=''){
								$counting = $schemeImport->getAadhaar($data[4], $scheme_id);
								//echo count($counting)."<br />";
								if(count($counting)> 0){
									$existance+=1;
								}else if(trim($data[4]) != "" && is_numeric(strlen(trim($data[4])))==12 && trim($data[2]) != "" && trim($data[3]) != "" && is_numeric(trim($data[6])) && is_numeric(trim($data[24]))!='' && is_numeric(trim($data[21]))!=''){
									$schemeImport->dataInsertForSchemeImport($dataform,$tablename,$scheme_id);
								}else{
								$notsave +=1; 
							}
						 }else{
							$lenaadhaar+=1;
							}
						}
							$skip +=1;
					}
					//exit;
						fclose($fp);
						unlink($target_path);
						//$this->_redirect("/schemeowner/beneficiarydatalist?success=success");
						//$this->view->assign("aadharexisted",$existance);
						$cnt = base64_encode($existance);
						$lenad = base64_encode($lenaadhaar);
						$ntsv = base64_encode($notsave);
						$validate_status=base64_encode(1);
						$this->_redirect("/schemeowner/aadharvalidate?existval=$cnt&ntsv=$ntsv&lenad=$lenad&".$red.'&validate_status='.$validate_status);
					}else{
						$this->view->assign("errorMessage","Please select valid file to upload scheme data !");
					}
						$this->_redirect("/schemeowner/aadharvalidate?".$red);
				}
				}else{
					$this->_redirect('/schemeowner/importscheme?actmsg=fileformaterror&'.$paramid);
				}
			}
		}else{
			$this->_redirect("");
        }
	}
//Above function is using for the import beneficiaries details	
	
	
	
	
	
	
	   
    
	
	
	
		/*****************end****************/
	public function schemedatabenlistAction(){
		$userid = new Zend_Session_Namespace('userid');
		$role = new Zend_Session_Namespace('role');
		if($role->role==6){
			$this->_helper->layout->setLayout('admin/layout');
		}
		if(!in_array($role->role, $this->roleArray)){
		  $this->_redirect("");
		}else{
		  $request = $this->getRequest();
		  if((!$request->getParam("scheme_name")) && (!$request->getParam("scheme_id"))){
			$this->_redirect("");
		  }else{
        //echo "Aaaa";exit;
          $scmid = base64_decode($request->getParam("scheme_id"));
          $importObj = new Application_Model_Schemeimport;
          $tb_name = $importObj->getTable($scmid);

			if(isset($start)){   // This variable is set to zero for the first page
			$start = 0;      
			} else {
			  $start=$request->getParam('start');         
        }
        $page=0;
        $limit=1500;
        $schemedetail = array();
        $scheme_name = safexss(base64_decode($request->getParam("scheme_name")));
        //echo preg_replace('/[^A-Za-z0-9]/', '', $scheme_name);exit;
        $scheme_id = safexss(base64_decode($request->getParam("scheme_id")));
        $month = 0;
        $year = 0;
		
		
        if(!$request->getParam("year") && !$request->getParam("month")){
           $encryptScheme = safexss($request->getParam("scheme_name"));
           $encryptSchemeId = safexss($request->getParam("scheme_id"));
          $month = 0;
          $year = 0;
          $tablename = $tb_name;
        }else{
          if($request->getParam("year") != 0){
            $encryptScheme = safexss($request->getParam("scheme_name"));
            $encryptSchemeId = safexss($request->getParam("scheme_id"));
            $month = $request->getParam("month");
            $year = $request->getParam("year");
            $yearextend = $year+1;
            $tablename = $tb_name;
          }else{
            $encryptScheme = safexss($request->getParam("scheme_name"));
            $encryptSchemeId = safexss($request->getParam("scheme_id"));
            // $fstart = date("Y");
            // $fend = date("Y") + 1;
            $curre_year = strtotime(date("d-m-Y"));          
            $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
            if($curre_year > $fixedyear){
              $fstart = date("Y");
            }else if($curre_year <= $fixedyear){
              $dataa = date("Y")-1;
              $fstart = $dataa;
            }
            $dateend = $fstart+1;
            $fend = $dateend;
            $month = $request->getParam("month");
            $year = $request->getParam("year");
            $tablename = $tb_name;
          }
        }
        $schemedetail = array("0" => base64_encode($scheme_name), "1" => base64_encode($scheme_id), "2" => base64_encode($tablename));
        $dataGet = $importObj->GetBeneficiaries($tablename, $scheme_id, $userid->userid, $month,$year,$start,$limit);
        $this->view->assign('scheme_id', $schemedetail);
        $this->view->assign("cmidata", $dataGet);
        $countcmi = $importObj->countschemedata($tablename);
        $this->view->assign('counttotalcmireports', $countcmi);
        $this->view->assign('start', $start);
        $page_name = 'schemedatabenlist';
        //echo $month;exit;
        $pagination1=$this->pagination_search($encryptScheme,$encryptSchemeId,$countcmi,$year,$month,$start,$limit,$page_name);
        $this->view->assign('pagination', $pagination1);
				}
			}
		}
	
	
	/* Below function is use for the Insert Record of beneficiery in database */
	public function beneficiaryrecordAction(){
		//echo "aaaa";exit;
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
			if($admname->adminname==''){
				 $this->_redirect('');
			}
		$role = new Zend_Session_Namespace('role');	
		if(!in_array($role->role,$this->roleArray)){
				$this->_redirect('');
		}
		if($role->role==6){
			$this->_helper->layout->setLayout('admin/layout');
		}
		$userid = new Zend_Session_Namespace('userid');
	    $userObj = new Application_Model_User;
		$request = $this->getRequest(); //$this should refer to a controller
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
	    $scm_type = safexss(intval(base64_decode($request->getParam('scm_type'))));
		$status = safexss(base64_decode($request->getParam('status')));
		$schemedecodeid = safexss($request->getParam('scheme_id'));
		$mindecodeid = safexss($request->getParam('min_id'));
		$scheme_type = safexss(base64_decode($request->getParam('scheme_type')));
		// echo $scm_type; exit;
		if($scheme_id == '' || (!is_numeric($scheme_id)) || $min_id == '' || (!is_numeric($min_id))){
			$this->_redirect('');
		}
        $this->view->assign('schemeid', $scheme_id);
        $this->view->assign('min_id', $min_id);
		$this->view->assign('scm_type', $scm_type);
		if($request->getParam('actmsg')=='add'){
			$this->view->assign('successMsg', RECORD_INSERTED);
		}elseif($request->getParam('actmsg')=='edit'){
			$this->view->assign('successMsg', RECORD_UPDATED);
		}elseif($request->getParam('actmsg')=='del'){
			$this->view->assign('successMsg', RECORD_DELETED);
		}elseif($request->getParam('actmsg')=='inactivate'){
			$this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
		}elseif($request->getParam('actmsg')=='atLeastonevalue'){
			$this->view->assign('errorMessage', ATLEAST_ONEVALUE);
		}elseif($request->getParam('actmsg')=='valuenotmatched'){
			$this->view->assign('errorMessage', VALUE_NOT_MATCHED);
		}elseif($request->getParam('actmsg')=='alreadyavailable'){
			$this->view->assign('errorMessage', ALLREADY_AVAILABLE);
		}elseif($request->getParam('actmsg')=='wrongbeneficiary'){
			$this->view->assign('errorMessage', WRONG_BENEFICIARY);
		}
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature){
		$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			if($pos !== false) {
				//$this->_redirect('/schememanualdata?scheme_id='.base64_encode($scheme_id));
			}
		} else {
			$_SESSION['LastRequest'] = $RequestSignature;
		}
		$schemeidobj = new Application_Model_Schemeimport();
		$scheme_code = $schemeidobj->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
	    $schemedetail = $schemeidobj->getschemename($scheme_id);
		/**** get the ministry detail*****/
		 $mindetail = $schemeidobj->getministryname($min_id);
		 /******end***************/
		//print_r($schemedetail->toArray());exit;
		$asignedschemeid = $schemeidobj->checkasignedschemeid($userid->userid,$scheme_id);
		if($asignedschemeid > 0 || $role->role == 1 || $role->role == 6){
			$this->view->assign('scheme_id', $schemedetail);
			$form = new Application_Form_BeneficiaryRecord();
			$form->addform();
			$this->view->form = $form;
			
		//below is the post method calling
		if($this->getRequest()->isPost()){
			$request = $this->getRequest();
			if($form->isValidPartial($request->getPost())){
				
				$dataform = $request->getPost();
				
			/**********vallidation to check captcha code ************/
				if($dataform['vercode'] != $_SESSION["vercode"]){
					$msg="Please enter a correct code!";
					$this->view->assign('errorMessage', "Please enter a correct code!");
					return false;
				}
			/*****************end********************/
				if($dataform['aadhar_num'] != ""){
					$aadhaar_num = $schemeidobj->getAadhaar($dataform['aadhar_num'],$scheme_id);
					if(count($aadhaar_num) > 0){
						$this->view->assign('errorMessage', "This Aadhaar number has already taken.");
						return false;
					}
				}
				$datatb = $schemeidobj->getTable($scheme_id);
				//echo "<pre>";print_r($dataform);
				$insscheme = $schemeidobj->dataInsertForScheme($dataform,$datatb,$scheme_id);
				if($insscheme){
					/*********code to insert in the audit log*****************/
					$schemename = $schemedetail[0]['scheme_name'];
					$minname =  $mindetail[0]['ministry_name'];
					$beneficiary_title = safexss($dataform['beneficiary_title']);
					$name = safexss($dataform['name']);
					$email_id = $dataform['email_id'];
					$dob = $dataform['dob'];
					$gender = $dataform['gender'];
					if($gender == 1 || $gender == "M")
					{
					$genderval = 'Male';
					}
					else if($gender == 2 || $gender == "F")
					{
					$genderval = 'Female';
					}
					$aadhar_num = $dataform['aadhar_num'];
					$bank_account = $dataform['bank_account'];
					$bank_name = $dataform['bank_name'];
					$beneficiary_regional_lang = $dataform['beneficiary_regional_lang'];
					$aadhar_seeded = $dataform['aadhar_seeded'];
					if(strtolower($aadhar_seeded) == 'y')
					{
					$aadharseededval = 'Yes';	
					}
					else if(strtolower($aadhar_seeded) == 'n')
					{
					$aadharseededval = 'No';
					}
					else
					{
					$aadharseededval = 'No';	
					}
					$mobile_num = $dataform['mobile_num'];
					$scheme_specific_unique_num = $dataform['scheme_specific_unique_num'];
					$scheme_specific_family_num = $dataform['scheme_specific_family_num'];
					$ration_card_num = $dataform['ration_card_num'];
					$tin_family_id = $dataform['tin_family_id'];
					$home_address = $dataform['home_address'];
					$postalcode = $dataform['pincode'];

					$statecode  = $dataform['state_name'];
					$statename = $schemeidobj->sms_state($statecode);
					$districtcode  = $dataform['district_name'];
					$districtname = $schemeidobj->sms_district($districtcode);
					$blockcode  = $dataform['block_name'];
					$blockname = $schemeidobj->sms_block($blockcode);
					$villagecode  = $dataform['village_name'];
					$villagename = $schemeidobj->sms_village($villagecode);


					$home_addressetext = wordwrap($home_address, 25, "\n", true); 
					$schemeidobjnew = new Application_Model_Auditlog();
					$description = '';	
					$description .= 'Add Beneficiary</br>';	
					$description .= '<span>Scheme Name:</span>'.$schemename.'</br>';	
					$description .= '<span>Ministry Name:</span>'.$minname.'</br>';	
					$description .= '<span>Title:</span>'.$beneficiary_title.'</br>';						
					$description .= '<span>Name:</span>'.$name.'</br>';						
					$description .= '<span>Email-ID:</span>'.$email_id.'</br>';
					$description .= '<span>Beneficiary language:</span>'.$beneficiary_regional_lang.'</br>';
					$description .= '<span>DOB:</span>'.$dob.'</br>';
					$description .= '<span>Gender:</span>'.$genderval.'</br>';
					$description .= '<span>Bank Name:</span>'.$bank_name.'</br>';
					if($aadhar_num!='')
					{
					$description .= '<span>Aadhaar Number:</span>'.$aadhar_num.'</br>';
					}
					if($bank_account!='')
					{
					$description .= '<span>Account Number:</span>'.$bank_account.'</br>';
					}
					if($aadhar_seeded!='')
					{
					$description .= '<span>Seeded with Aadhar:</span>'.$aadharseededval.'</br>';
					}
					if($mobile_num!='')
					{
					$description .= '<span>Mobile Number:</span>'.$mobile_num.'</br>';
					}
					if($scheme_specific_unique_num!='')
					{
					$description .= '<span>Scheme Specific Unique Number:</span>'.$scheme_specific_unique_num.'</br>';
					}
					if($scheme_specific_family_num!='')
					{
					$description .= '<span>Scheme Specific family Number:</span>'.$scheme_specific_family_num.'</br>';
					}
					if($ration_card_num!='')
					{
					$description .= '<span>Ration card number:</span>'.$ration_card_num.'</br>';
					}
					if($tin_family_id!='')
					{
					$description .= '<span>TIN Family number:</span>'.$tin_family_id.'</br>';
					}
					$description .= '<span>State Name:</span>'.$statename.'</br>';
					$description .= '<span>District Name:</span>'.$districtname.'</br>';
					$description .= '<span>Block Name:</span>'.$blockname.'</br>';
					$description .= '<span>Village Name:</span>'.$villagename.'</br>';
					$description .= '<span>Home Address:</span>'.$home_addressetext.'</br>';
					$description .= '<span>Postal Code:</span>'.$postalcode.'</br>';

					$auditlog = array(
					"uid" => $userid->userid,
					"application_type" => 'DBT Add Beneficiary',
					"description" => $description
					); 
					$schemeidobjnew->insertauditlog($auditlog);  

					/**********end*********************************************/
				}	
				$schemedecodeid = $request->getParam('scheme_id');
				$mindecodeid = $request->getParam('min_id');
				if($insscheme){
					$this->view->assign('successMsg', "Data saved sucessfully!");
					$qstring="scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id)."&scm_type=".base64_encode($scm_type).'&validate_status='.base64_encode(1);
					$this->_redirect("/schemeowner/aadharvalidate?existval=$cnt&ntsv=$ntsv&".$qstring);
					//$this->_redirect('/schemeowner/beneficiarydatalist?actmsg=add&'.$qstring);
				}
			}
		}
	}else {
		$this->_redirect('/schemeowner/schemeview');
	}
	}
	
	
	
	//show the all record of valid NPCI AND UIDAI beneficiaries
	public function beneficiarydatalistAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		//die("jshds");
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller
		if($request->getParam('benef_id')!=''){
			$querystr = safexss($request->getParam('benef_id'));
			$_GET['benef_id'] = $querystr;
		}
		if($request->getParam('name')!=''){
			$querystr = safexss($request->getParam('name'));
			$_GET['name'] = $querystr;
		}
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();
		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		$this->view->assign('scheme_data', $schemedetails);
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature){
		 	$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			if ($pos !== false) {
				//$this->_redirect('/schemeowner/beneficiarydatalist?scheme_id='.base64_encode($schemedetails[0]['id']));
			}
		}else{
		  $_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=1000;
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$asignedschemeid = $cmi_list->checkasignedschemeid($userid->userid,$scheme_id);
		if($asignedschemeid > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->beneficiarydatalist($start,$limit,$scheme_id);
			//print_r($cmishow_list);die;
			$countcmi = $cmi_list->countbeneficiarydata($scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'beneficiarydatalist';
//echo $schmepagingid.' sdfvdsf1'.$minpagingid.' sdfsdf2'.$countcmi.'sdfsdf3'.$year.' sdfsdf4'.$month.' '.$page_name;die;
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			//echo $pagination1;die;
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/schemeview');
		}
	}
	
	// end of all valid beneficiaries


//show the all record of beneficiaries
	public function allbeneficiarydatalistAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		//die("jshds");
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller
		if($request->getParam('benef_id')!=''){
			$querystr = safexss($request->getParam('benef_id'));
			$_GET['benef_id'] = $querystr;
		}
		if($request->getParam('name')!=''){
			$querystr = safexss($request->getParam('name'));
			$_GET['name'] = $querystr;
		}
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();
		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		$this->view->assign('scheme_data', $schemedetails);
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature){
		 	$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			if ($pos !== false) {
				//$this->_redirect('/schemeowner/beneficiarydatalist?scheme_id='.base64_encode($schemedetails[0]['id']));
			}
		}else{
		  $_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=1000;
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$asignedschemeid = $cmi_list->checkasignedschemeid($userid->userid,$scheme_id);
		if($asignedschemeid > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->allbeneficiarydatalist($start,$limit,$scheme_id);
			//print_r($cmishow_list);die;
			$countcmi = $cmi_list->countallbeneficiarydata($scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'allbeneficiarydatalist';
//echo $schmepagingid.' sdfvdsf1'.$minpagingid.' sdfsdf2'.$countcmi.'sdfsdf3'.$year.' sdfsdf4'.$month.' '.$page_name;die;
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			//echo $pagination1;die;
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/schemeview');
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
    public function blockwisepanchayatAction(){
             $request = $this->getRequest();
             $block =$request->getParam('block');  
               //$district = 55;
               // echo $district;
               // exit;
               if ($this->getRequest()->isPost()){      
               $panchayat_show = new Application_Model_DbtState;
                 $showdetails = $panchayat_show->blockwisepanchayat($block);
                //  echo $district;
                // exit;
                 $html = "";
                 $html .='<option value="0">==Select Panchayat==</option>';
                  foreach ($showdetails as $key => $val)
                      {
                       $html .='<option value="'.$val['panchayat_code'].'">'.$val['panchayat_name'].'</option>';
                     }    
                     echo $html;    
                exit;
             }
    }
	public function blockwisevillageAction(){
             $request = $this->getRequest();
             $block =$request->getParam('block');  
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
    public function panchayatwisevillageAction(){
             $request = $this->getRequest();
             $panchayat =$request->getParam('panchayat');  
               //$district = 55;
               // echo $district;
               // exit;
               if ($this->getRequest()->isPost()){       
               $panchayat_show = new Application_Model_DbtState;
                 $showdetails = $panchayat_show->panchayatwisevillage($panchayat);
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
	
	//show the all record of the beneficiaries
	public function viewbeneficiariesrecordAction(){
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
		$request = $this->getRequest();//$this should refer to a controller
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$id = safexss(base64_decode($request->getParam('id')));
		$uuid = safexss(base64_decode($request->getParam('uuid')));
		if($scheme_id == '' || (!is_numeric($scheme_id)) || $uuid == "" || $id == ""){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();
		$cmishow_list = $cmi_list->beneficiarydatalistfullview($scheme_id, $id, $uuid);
		$this->view->assign('cmidata', json_encode($cmishow_list));
	}
	
	
	
	
	
	//this function is use to edit transactional form
	public function beneficiaryeditAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
             $this->_redirect('');
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
            $this->_redirect('');
        }
		$request = $this->getRequest();//$this should refer to a controller
		$encodescheme = $request->getParam('scheme_id');		
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$id = safexss(base64_decode($request->getParam('id')));
		$uuid = safexss(base64_decode($request->getParam('uuid')));
		$scmtype = safexss(base64_decode($request->getParam('scmtype')));
		if($scheme_id == '' || (!is_numeric($scheme_id)) || $uuid == "" || $id == ""){
			$this->_redirect('');
		}
		$modelobj = new Application_Model_Schemeimport();
		/**** get the scheme and the ministry detail*****/
		$scheme_code = $modelobj->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
	    $schemedetail = $modelobj->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$schmdetail = $schemedetail->toArray();
		$schemename = $schmdetail[0]['scheme_name'];
		$this->view->assign('schemename', $schemename);
		$minid  = $schmdetail[0]['ministry_id'];
		$mindetail = $modelobj->getministryname($minid);
		$ministryname = $mindetail[0]['ministry_name'];
		
		/******end****************************************/
		$datageting = $modelobj->beneficiaryaddtransdatalist($start = null,$limit = null,$scheme_id, $uuid, $id);
		//print_r($datageting);die;
		//if(!$request->isPost()){
			$form = new Application_Form_BenefitTransaction();
				if($scmtype == 1 || $scmtype == 3){
					$form->incashothers();
				}
				else if($scmtype == 2){
					$form->inkind();
				}
			$name = $datageting['0']['name'];
			$aadhar_num = $datageting['0']['aadhar_num'];
			$amount = $datageting['0']['amount'];
			$fund_transfer = $datageting['0']['fund_transfer'];
			$transaction_date = $datageting['0']['transaction_date'];
			$form->populate(array('scheme_id'=>$encodescheme,'uniq_user_id'=>$uuid,"name"=>$name,"aadhar_num"=>$aadhar_num,'id'=>$id,'scmtype'=>$scmtype,'amount'=>$amount,'fund_transfer'=>$fund_transfer,'transaction_date'=>$transaction_date));
			$this->view->form = $form;
		//}
		if($request->isPost()){
			$dataform = $request->getPost();
			if($form->isValidPartial($dataform)){
			/**********vallidation to check captcha code ************/
				if($dataform['vercode']!= $_SESSION["vercode"]){
					$msg="Please enter a correct code!";
					$this->view->assign('errorMessage', "Please enter a correct code!");
					return false;
				}
				if(base64_decode($dataform['uuid']) != $uuid){
					$this->view->assign('errorMessage', "CSRF ATTACK!");
					return false;
				}
				if(base64_decode($dataform['id']) != $id){
					$this->view->assign('errorMessage', "CSRF ATTACK!");
					return false;
				}
				$data = $modelobj->insertoldmethodtransaction(json_encode($dataform),$scheme_id,$uuid, $id, $scmtype);
				
				if($data > 0){
					if($data)
					{
						/*****code to insert in the audit log for manage transaction******/

                        $amount = $dataform['amount'];
						$fund_transfer = $dataform['fund_transfer'];
						$transaction_date = $dataform['transaction_date'];
						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
						$description .= 'Edit Transaction</br>';	
						$description .='<span>Scheme:</span>'.$schemename.'</br>';
						$description .='<span>Ministry:</span>'.$ministryname.'</br>';
						if($uuid!='')
						{
						$description .='<span>Beneficiary Id:</span>'.$uuid.'</br>';
						}
                        if($name!='')	
                        {							
						$description .= '<span>Name:</span>'.$name.'</br>';
						}
						if($aadhar_num!='')	
                        {							
						$description .= '<span>Aadhar Number:</span>'.$aadhar_num.'</br>';
						}
						if($amount!='')	
                        {							
						$description .= '<span>Amount:</span>'.$amount.'</br>';
						}
						if($fund_transfer!='')	
                        {							
						$description .= '<span>Fund Transfer Method:</span>'.$fund_transfer.'</br>';
						}
						if($transaction_date!='')	
                        {							
						$description .= '<span>Transaction Date:</span>'.$transaction_date.'</br>';
						}
						if($dataform['transfer_by']!='')	
                        {							
						$description .= '<span>Transfer By:</span>'.$dataform['transfer_by'].'</br>';
						}
						$auditlog = array(
						"uid" => $userid->userid,
						"application_type" => 'Manage Transaction',
						"description" => $description
						); 
						$schemeidobj->insertauditlog($auditlog);				/***************end************************************************/
					}
					$this->view->assign('successMsg', "Data saved sucessfully!");
					$qstring = "id=".base64_encode($id)."&scheme_id=".base64_encode($scheme_id)."&uuid=".base64_encode($uuid)."&scmtype=".base64_encode($scmtype);
					$this->_redirect('/managetransaction/viewbeneficiariesrecord?actmsg=add&'.$qstring);
				}else{
					$this->view->assign('errorMessage', "Something went wrong!");
					return false;
				}
				//$this->pr_man($dataform,1);
			/*****************end********************/
			}
		}
	}
	
	
	
	
	
	public function csvexportmethodAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 //exit;
			 $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			//exit;
			$this->_redirect("");
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
		$exppageno = base64_decode($request->getParam('exppageno'));
		
		if($scheme_id == "" && $min_id == ""){
			exit;
		}else{
			$dbobj = new Application_Model_Schemeimport();
			$dataassign = $dbobj->csvexportmethoddb($scheme_id,$min_id,$month,$year,$exppageno);
			$schemename = $dbobj->getschemename(base64_decode($scheme_id));
			$this->view->assign("exportarray",$dataassign);
			$this->view->assign("schemename",$schemename);
		}
	}
	
	//below function is use for the export single beneficiaries record
	public function exportbeneficiaryAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 //exit;
			 //return false;
			 $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			//return false;
			$this->_redirect("");
        }
		$request = $this->getRequest();
		if(!$request->getParam('scheme_id')){
			return false;
		}
		if(!$request->getParam('id')){
			return false;
		}
		if(!$request->getParam('uuid')){
			return false;
		}
		$scheme_id = safexss($request->getParam('scheme_id'));
		$uuid = safexss($request->getParam('uuid'));
		$id = safexss($request->getParam('id'));
		if($scheme_id == "" && $uuid == ""){
			return false;
		}else{
			$dbobj = new Application_Model_Schemeimport();
			$dataassign = $dbobj->singleuserdb($scheme_id,$id,$uuid);
			$schemename = $dbobj->getschemename(base64_decode($scheme_id));
			$this->view->assign("exportarray",$dataassign);
			$this->view->assign("schemename",$schemename);
		}
	}
	
	//Below function is use for the manage the full transaction data
	public function managetransactionAction(){
		$admname = new Zend_Session_Namespace('adminMname');
        $role = new Zend_Session_Namespace('role');
        $userid = new Zend_Session_Namespace('userid');
       	$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
        if(in_array($role->role, $this->roleArray)){
            $request = $this->getRequest();
			if($request->getParam('actmsg')=='filesizeerror'){
				$this->view->assign('errorMessage', FILE_SIZE_ERROR_2MB);
			}elseif($request->getParam('actmsg')=='fileformaterror'){
				$this->view->assign('errorMessage', FILE_FORMAT_ERROR);
			}
            $form = new Application_Form_Schemeowner();
            $schemeImport = new Application_Model_Schemeimport;
			
            $scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
			$scm_type = safexss(base64_decode($request->getParam('scm_type')));
            $tb_name = $schemeImport->getTable($scheme_id);
			$scheme_name = $schemeImport->findSchemeName($scheme_id);
            $schemenm = $scheme_name[0]['scheme_name'];
			$scheme_code = $schemeImport->getschemecode($scheme_id);
			$this->view->assign('scheme_code', $scheme_code);
		/***** get the  ministry name *******/
		$minid = safexss(base64_decode($request->getParam('min_id')));
		$mindetail = $schemeImport->getministryname($minid);
		$miname = $mindetail[0]['ministry_name'];
		/***********end**********************/
		//below data for the assign param in url
		$escheme_id = safexss($request->getParam('scheme_id'));
		$emin_id = safexss($request->getParam('min_id'));
		$estatus = safexss($request->getParam('status'));
		$escm_type = safexss($request->getParam('scm_type'));
		$paramid = "scheme_id=".$escheme_id."&min_id=".$emin_id."&scm_type=".$escm_type."&status=".$estatus;
		//upper data for the assign param in url
		
            $this->view->assign('schmid',base64_encode($scheme_id));
            $this->view->assign('schemenm',base64_encode($schemenm));
            $this->view->assign("dataofscheme",$schemenm);
            $this->view->assign("scheme_name",$schemenm);
            $scheme_type=$scheme_name[0]['scheme_type'];
            $somedata = array("scheme_id"=>$scheme_id);
            $form->importschemetransact();//calling the form of transaction
            $form->populate($somedata);
            $this->view->form = $form;
			if($this->getRequest()->isPost()) {
               $dataform=$request->getPost();
			/**********vallidation to check captcha code 26th july ************/
            if($dataform['vercode'] != $_SESSION["vercode"]){
                $msg="Please enter a correct code!";
                $this->view->assign('errorMessage', "Please enter a correct code!");
                return false;
            }
			if($dataform['sessionCheck']!=$captcha->captcha){
                $this->view->assign('errorMessage',CSRF_ATTACK);
                return false;
            }
            $tablename = $tb_name;//creating here the tablename to insert
            $filename = $_FILES['importfile']['name'];
			$fileFormat = array ('csv','CSV');
            $fieltempval = 0;
            $allow_extension_only=array('application/vnd.ms-excel','text/csv','text/CSV');
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
            if(in_array(end( explode ( '.', $filename)), $fileFormat) && $fieltempval == 0){
				$target_path = DOCUMENT_ROOT.'data/schemedata/';
				$target_path1 = $target_path . basename($fTemp);
				$target_path2 = $target_path . basename($fTemp1);
				$data = file_get_contents($_FILES['importfile']['tmp_name']);
                $dataCheck = substr($data,0,2);
                if($dataCheck=="PK" || $dataCheck == "MZ" || $dataCheck == "NE" || $dataCheck == "PE" || $dataCheck == "LX" || $dataCheck == "LE" || $dataCheck == "W3" || $dataCheck == "W4" || $dataCheck == "DL" || $dataCheck == "MP" || $dataCheck == "P2" || $dataCheck == "P3" || $dataCheck == "Ta" || $data == "" ){
					//echo $dataCheck."---".$data;exit;
                    $this->_redirect('/schemeowner/importscheme?actmsg=fileformaterror&'.$paramid);
                }else{
					
					$filename = $_FILES["importfile"]["name"];                   
					$fileFormat = array ('csv','CSV');
					if(in_array(end(explode ('.', $filename)), $fileFormat)){
					$target_path = DOCUMENT_ROOT.'data/schemedata/'.time().$filename;
					move_uploaded_file($_FILES['importfile']['tmp_name'], $target_path);
					$fp = fopen($target_path, "r");
					$skip = 0;
					$scheme_id = $scheme_name['0']['id'];
					$dataform = "";
					/******* code to insert in the audit log*********/
					 $target_pathnew = DOCUMENT_ROOT."data/schemedata/".time().$filename;
                     $path = "<a href='".$target_pathnew."'>".$filename."</a>";	
					$schemeidobj = new Application_Model_Auditlog();
					$description = '';	
					$description .= 'Bulk Transaction file upload</br>';	
					$description .= '<span>Ministry:</span>'.$miname.'</br>';					
					$description .= '<span>Scheme:</span>'.$schemenm.'</br>';
					//$description .= '<span>File:</span>'.$path.'</br>';
					$auditlog = array(
					"uid" => $userid->userid,
					"application_type" => 'Bulk Transaction file upload',
					"description" => $description
					); 
					$schemeidobj->insertauditlog($auditlog); 
					/*******end***********************/
					while(($data= fgetcsv($fp, 1000, ",")) !== FALSE ){
						//print_r($data);
						if($skip > 1){
							//echo $skip;exit;
							//$dateg = date("d",strtotime($data[28]));
							//$monthg = date("m",strtotime($data[28]));
							//$yearg = date("Y",strtotime($data[28]));
							$id = null;
							$uuid = $data[0];
							//if()
							//print_r($data);exit;
							$dataform = array(
								'amount' => $data[25],
								'fund_transfer' => $data[26],
								'transaction_date' => $data[27],
								'transfer_by' => "",
								//'year' => $yearg,
								//'month' => $monthg,
								//'day' => $dateg,
								//'scheme_id' => $scheme_id
							);
							//$this->pr_man($dataform,1);
							if(($scm_type == 1 || $scm_type == 3) && $data[25] !="" && $data[26] !="" && $data[27] !=""){
								$schemeImport->insertbeneficiaryrecord(json_encode($dataform), $scheme_id, $uuid, $id, $scm_type);
							}else if($scm_type == 2 && $data[27] !=""){
								$dataform['transfer_by'] = $data[28];
								$schemeImport->insertbeneficiaryrecord(json_encode($dataform), $scheme_id, $uuid, $id, $scm_type);
							}
						}
							$skip +=1;
					}
					//exit;
					//exit;
					
					//scheme_id=MTg=&min_id=MQ==&status=1&scm_type=MQ==
					
					
						fclose($fp);
						unlink($target_path);
						$this->_redirect("/managetransaction/beneficiarydatalist?success=success&".$paramid);
					}else{
						$this->view->assign("errorMessage","Please select valid file to upload scheme data !");
					}
						$this->_redirect("/managetransaction/schemedatabenlist?success=success&".$paramid);
				}
				}else{
					$this->_redirect('/schemeowner/importscheme?actmsg=fileformaterror&'.$paramid);
				}
			}
		}else{
			$this->_redirect("");
        }
	}

/*  ----download simple DBT portal sms doucment controller start now---------*/

public function downloadAction(){
	$admname = new Zend_Session_Namespace('adminMname');
    $role = new Zend_Session_Namespace('role');
	if($admname->adminname==''){
		$this->_redirect("");
    }
    $role = new Zend_Session_Namespace('role');
    if(!in_array($role->role,$this->rolearray)){
		$this->_redirect("");
	}
     $request = $this->getRequest();
	 $filename=$request->getParam('filename');
     $file = DOCUMENT_ROOT.'samplecsv/'.$filename;
    if (false === file_exists($file)) {
        return $this->redirect('routename-file-does-not-exist');
    }
	$filetype = finfo_file($file);
    header("Content-Type: {$filetype}");
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    readfile($file);
    exit();
	}

/*  ----download simple DBT portal sms doucment controller start end---------*/


/*  --------------- aadhar validate controller start now-------------*/ 

	public function aadharvalidateAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		//die("jshds");
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller
		
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();
		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=1000;
		
		if($schemedetails > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->aadharvalidate($start,$limit,$scheme_id);

			//echo "</pre>";print_r($cmishow_list);echo "</pre>";die;

			$countcmi = $cmi_list->countaadharvalidate($scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'aadharvalidate';
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/beneficiarydatalist');
		}

	}
	

/*  --------------- aadhar validate controller end now-------------*/ 


// beneficiary list with aadhar seeded aadhar number start now

	public function aadharseededlistAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		//die("jshds");
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller
		
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();
		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
        if($request->getParam('actmsg')=='add'){
            $this->view->assign('successMsg', RECORD_INSERTED);
        }elseif($request->getParam('actmsg')=='edit'){
            $this->view->assign('successMsg', RECORD_UPDATED);
        }elseif($request->getParam('actmsg')=='del'){
            $this->view->assign('successMsg', RECORD_DELETED);
        }elseif($request->getParam('actmsg')=='inactivate'){
            $this->view->assign('successMsg', 'Selected record has been Deactivated successfully.');
        }
		// Remove query string on page refresh
		session_start();
		$RequestSignature = md5($_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING'].print_r($_POST, true));
		if ($_SESSION['LastRequest'] == $RequestSignature){
		 	$pos = strpos($_SERVER['QUERY_STRING'], 'actmsg=');
			if ($pos !== false) {
				//$this->_redirect('/schemeowner/beneficiarydatalist?scheme_id='.base64_encode($schemedetails[0]['id']));
			}
		}else{
		  $_SESSION['LastRequest'] = $RequestSignature;
		}
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=1500;
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$asignedschemeid = $cmi_list->checkasignedschemeid($userid->userid,$scheme_id);
		if($asignedschemeid > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->beneficiaryaadharseededdatalist($start,$limit,$scheme_id);
			$countcmi = $cmi_list->countbeneficiaryaadharseededdata($scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'beneficiarydatalist';
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/aadharseededlist');
		}
	}

// beneficiary list with aadhar seeded aadhar number start now

	public function nonaadharseededlistAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller

		if($request->getParam('benef_id')!=''){
			$querystr = safexss($request->getParam('benef_id'));
			$_GET['benef_id'] = $querystr;
		}
		if($request->getParam('name')!=''){
			$querystr = safexss($request->getParam('name'));
			$_GET['name'] = $querystr;
		}
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();

	

		$schemedetail = $cmi_list->getschemename($scheme_id);
		
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		$this->view->assign('scheme_data', $schemedetails);
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=800;
		
		if($schemedetail > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->noaadharseededvalidate($start,$limit,$scheme_id);

			//echo "</pre>";print_r($cmishow_list);echo "</pre>";die;

			$countcmi = $cmi_list->countnoaadharseededvalidate($start,$limit,$scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'nonaadharseededlist';
	//echo $schmepagingid.' sdfvdsf1'.$minpagingid.' sdfsdf2'.$countcmi.'sdfsdf3'.$year.' sdfsdf4'.$month.' '.$page_name;die;
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			//print_r($pagination1);die;
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/beneficiarydatalist');
		}

	}

//*********************** edit non aadhar beneficiary detail start now******************
public function nonseededbenficiaryeditAction()
	{
		
		$captcha = new Zend_Session_Namespace('captcha');
		$captcha->captcha = session_id();
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		
        if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller
		
		$form    = new Application_Form_Editnonaadharseeded();
			$form->addform();
			$this->view->form = $form;

		$request = $this->getRequest();
		$editid=safexss(base64_decode($request->getParam('id')));
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$scm_type = safexss(base64_decode($request->getParam('scm_type')));

		$min_id = safexss(base64_decode($request->getParam('min_id')));
		//$edituidai = safexss(base64_decode($request->getParam('edituidai')));
		//echo $scm_type;die();
		$edit_show = new Application_Model_Schemeimport;

		$showdetails = $edit_show->editnonseededbeneficiary($scheme_id,$editid);
		//print_r($showdetails);die;
		$scheme_code = $edit_show->getschemecode($scheme_id);
		 $mindetail = $edit_show->getministryname($min_id);

		$this->view->assign('scheme_code', $scheme_code);
	    $schemedetail = $edit_show->getschemename($scheme_id);

		$this->view->assign('scheme_id', $schemedetail);
		
		
	//echo "<pre>";print_r($showdetails);die;
			$beneficiary_title = $showdetails['0']['beneficiary_title'];
			$name = $showdetails['0']['name'];
			$dob =$showdetails['0']['dob'];
			$state_name=$showdetails['0']['state_code'];
			$scheme_specific_unique_num=$showdetails['0']['scheme_specific_unique_num'];
			$scheme_specific_family_num=$showdetails['0']['scheme_specific_family_num'];
			$tin_family_id=$showdetails['0']['tin_family_id'];
			$uniq_user_id = $showdetails['0']['uniq_user_id'];
			$email_id = $showdetails['0']['email_id'];
			//$state_name = $showdetails['0']['state_name'];
			$aadhar_num = $showdetails['0']['aadhar_num'];
			$gender = $showdetails['0']['gender'];
			$mobile_num = $showdetails['0']['mobile_num'];
			$home_address = $showdetails['0']['home_address'];
			$village_name = $showdetails['0']['village_code'];
			$panchayat_name = $showdetails['0']['panchayat_name'];
			$block_name = $showdetails['0']['block_code'];
			$district_name = $showdetails['0']['district_code'];
			$pincode = $showdetails['0']['pincode'];
			$ration_card_num = $showdetails['0']['ration_card_num'];
			$bank_account = $showdetails['0']['bank_account'];
			$bank_name = $showdetails['0']['bank_name'];
			$ifsc = $showdetails['0']['ifsc'];
			$beneficiary_regional_lang = $showdetails['0']['beneficiary_regional_lang'];
			$beneficiary_type = $showdetails['0']['beneficiary_type'];
		//echo $district_name;die("testing");
			$form->populate(array('beneficiary_title'=>$beneficiary_title,'name'=>$name,'uniq_user_id'=>$uniq_user_id,"email_id"=>$email_id,"state_name"=>$state_name,'aadhar_num'=>$aadhar_num,'gender'=>$gender,'mobile_num'=>$mobile_num,'home_address'=>$home_address,'village_name'=>$village_name,'panchayat_name'=>$panchayat_name,'block_name'=>$block_name,'district_name'=>$district_name,'pincode'=>$pincode,'ration_card_num'=>$ration_card_num,'pincode'=>$pincode,'ration_card_num'=>$ration_card_num,'bank_account'=>$bank_account,'ifsc'=>$ifsc,'dob'=>$dob,'scheme_specific_unique_num'=>$scheme_specific_unique_num,'scheme_specific_family_num'=>$scheme_specific_family_num,'tin_family_id'=>$tin_family_id,'bank_name'=>$bank_name,'beneficiary_regional_lang'=>$beneficiary_regional_lang,'beneficiary_type'=>$beneficiary_type));
			$this->view->form = $form;

			if($request->isPost()){
			
			$dataform = $request->getPost();
			if($form->isValidPartial($dataform)){
			/**********vallidation to check captcha code ************/
				if($dataform['vercode']!= $_SESSION["vercode"]){
					$msg="Please enter a correct code!";
					$this->view->assign('errorMessage', "Please enter a correct code!");
					return false;
				}
				if(base64_decode($dataform['uuid']) != $uuid){
					$this->view->assign('errorMessage', "CSRF ATTACK!");
					return false;
				}
				if(base64_decode($dataform['id']) != $id){
					$this->view->assign('errorMessage', "CSRF ATTACK!");
					return false;
				}
				//print_r($dataform);die;
				$data = $edit_show->updatebeneficiaryrecord($dataform,$scheme_id,$editid);
				//print_r($data);die;
				if($data > 0){
					if($data)
					{
						/*****code to insert in the audit log for manage transaction******/

                        $amount = $dataform['amount'];
						$fund_transfer = $dataform['fund_transfer'];
						$transaction_date = $dataform['transaction_date'];
						$schemeidobj = new Application_Model_Auditlog();
						$description = '';	
					$description .= 'edit Beneficiary</br>';
					$description .= '<span>Scheme Name:</span>'.$schemedetail[0]['scheme_name'].'</br>';
					$description .= '<span>Ministry Name:</span>'.$mindetail.'</br>';
					if($beneficiary_title!='')
					{
					$description .= '<span>Beneficiary Title:</span>'.$beneficiary_title.'</br>';
					}
					$description .= '<span>Name:</span>'.$name.'</br>';						
					$description .= '<span>Email-ID:</span>'.$email_id.'</br>';
					$description .= '<span>DOB:</span>'.$dob.'</br>';
					$description .= '<span>Gender:</span>'.$gender.'</br>';
					if($beneficiary_regional_lang!='')
					{
					$description .= '<span>Beneficiary Language:</span>'.$beneficiary_regional_lang.'</br>';
					}
					if($aadhar_num!='')
					{
					$description .= '<span>Aadhaar Number:</span>'.$aadhar_num.'</br>';
					}
					$description .= '<span>Bank Name:</span>'.$bank_name.'</br>';
					$description .= '<span>Beneficiary Type:</span>'.$beneficiary_type.'</br>';
					if($bank_account!='')
					{
					$description .= '<span>Account Number:</span>'.$bank_account.'</br>';
					}
					
					if($mobile_num!='')
					{
					$description .= '<span>Mobile Number:</span>'.$mobile_num.'</br>';
					}
					if($scheme_specific_unique_num!='')
					{
					$description .= '<span>Scheme Specific Unique Number:</span>'.$scheme_specific_unique_num.'</br>';
					}
					if($scheme_specific_family_num!='')
					{
					$description .= '<span>Scheme Specific family Number:</span>'.$scheme_specific_family_num.'</br>';
					}
					if($ration_card_num!='')
					{
					$description .= '<span>Ration card number:</span>'.$ration_card_num.'</br>';
					}
					if($tin_family_id!='')
					{
					$description .= '<span>TIN Family number:</span>'.$tin_family_id.'</br>';
					}
					$description .= '<span>State Name:</span>'.$statename.'</br>';
					$description .= '<span>District Name:</span>'.$districtname.'</br>';
					$description .= '<span>Block Name:</span>'.$blockname.'</br>';
					$description .= '<span>Village Name:</span>'.$villagename.'</br>';
					$description .= '<span>Home Address:</span>'.$home_addressetext.'</br>';
					$description .= '<span>Postal Code:</span>'.$postalcode.'</br>';

					$auditlog = array(
					"uid" => $userid->userid,
					"application_type" => 'DBT edit Beneficiary',
					"description" => $description
					); 
						$schemeidobj->insertauditlog($auditlog);				/***************end************************************************/
					}
					$this->view->assign('successMsg', "Data saved sucessfully!");
					
			$qstring = "id=".base64_encode($id)."&scheme_id=".base64_encode($scheme_id)."&uuid=".base64_encode($uuid)."&scm_type=".base64_encode($dataform['scheme_type']);
//echo base64_encode($dataform['edituidai']);die;
					if(isset($dataform['edituidai']) && base64_decode($dataform['edituidai'])==1){
					$this->_redirect('/schemeowner/nonaadharuidailist?actmsg=add&'.$qstring);
					
				}else{
					
					$this->_redirect('/schemeowner/nonaadharseededlist?actmsg=add&'.$qstring);
				}

				}else{
					$this->view->assign('errorMessage', "Something went wrong!");
					return false;
				}
				//$this->pr_man($dataform,1);
			/*****************end********************/
			}
		}

	}


//*********************** edit non aadhar beneficiary detail end now******************


// beneficiary list with non aadhar validate by uidai start now

	public function nonaadharuidailistAction(){
			
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
		//echo $role->role;die;
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller

		if($request->getParam('benef_id')!=''){
			$querystr = safexss($request->getParam('benef_id'));
			$_GET['benef_id'] = $querystr;
		}
		if($request->getParam('name')!=''){
			$querystr = safexss($request->getParam('name'));
			$_GET['name'] = $querystr;
		}
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Schemeimport();

	

		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		$this->view->assign('scheme_data', $schemedetails);
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=1000;
		
		if($schemedetails >0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->noaadharuidaivalidate($start,$limit,$scheme_id);

			//echo "</pre>";print_r($cmishow_list);echo "</pre>";die;

			$countcmi = $cmi_list->countnoaadharuidaivalidate($start,$limit,$scheme_id);
			//$this->pr_man($cmishow_list,1);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'nonaadharuidailist';
	//echo $schmepagingid.' sdfvdsf1'.$minpagingid.' sdfsdf2'.$countcmi.'sdfsdf3'.$year.' sdfsdf4'.$month.' '.$page_name;die;
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			//print_r($pagination1);die;
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
			//echo "aaab";exit;
		}else{
			$this->_redirect('/schemeowner/importscheme?'.$paramid);
		}

	}


// pfms beneficiary data list start now

	public function pfmsbeneficiarydataAction(){
		//die("jhjcs");	
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
		
        if($admname->adminname==''){
             $this->_redirect('');
        }
		
        $role = new Zend_Session_Namespace('role');	
        if(!in_array($role->role,$this->rolearray)){
			$this->_redirect('');
        }
		if($role->role==6){
            $this->_helper->layout->setLayout('admin/layout');
        }
		$request = $this->getRequest();//$this should refer to a controller

		if($request->getParam('benef_id')!=''){
			$querystr = safexss($request->getParam('benef_id'));
			$_GET['benef_id'] = $querystr;
		}
		if($request->getParam('name')!=''){
			$querystr = safexss($request->getParam('name'));
			$_GET['name'] = $querystr;
		}
		$scheme_id = safexss(base64_decode($request->getParam('scheme_id')));
		$min_id = safexss(base64_decode($request->getParam('min_id')));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		
		// $form = new Application_Form_Pfmsform();
			// $form->addform();
			// $this->view->form = $form;

		$cmi_list = new Application_Model_Schemeimport();

	

		$schemedetail = $cmi_list->getschemename($scheme_id);
		$this->view->assign('scheme_id', $schemedetail);
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$this->view->assign('scheme_code', $scheme_code);
		$this->view->assign('min_id', $min_id);
		$schemedetails = $schemedetail->toArray();
		$this->view->assign('scheme_data', $schemedetails);
		// Remove query string on page refresh end
			if(isset($start)){
				// This variable is set to zero for the first page
				$start = 0;
			}else{
				  $start=$request->getParam('start');
				}
		$page=0;
		$limit=800;
		
		if($schemedetail > 0 || $role->role == 1 || $role->role == 6 || $role->role == 12){
			$cmishow_list = $cmi_list->pfmsdatabeneficiaries($start,$limit,$scheme_id);
			$countcmi = $cmi_list->countpfmsdatabeneficiaries($start,$limit,$scheme_id);
			$this->view->assign('cmidata', $cmishow_list);
			$minpagingid = $request->getParam('min_id');
			$schmepagingid = $request->getParam('scheme_id');
			$page_name = 'nonaadharseededlist';
			$pagination1=$this->pagination_search($schmepagingid,$minpagingid, $countcmi,$year,$month,$start,$limit,$page_name);
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			$this->view->assign('counttotalcmireports', $countcmi);
		//echo "aaab";exit;
		
			
			}
		else{
			$this->_redirect('/schemeowner/beneficiarydatalist');
		}

	}

public function csvexportmethodpfmsinitationAction(){
		$admname = new Zend_Session_Namespace('adminMname');
		$userid = new Zend_Session_Namespace('userid');
        if($admname->adminname==''){
			 //exit;
			 $this->_redirect("");
        }
        $role = new Zend_Session_Namespace('role');
        if(!in_array($role->role,$this->rolearray)){
			//exit;
			$this->_redirect("");
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
		$scheme_id = $request->getParam('scheme_id');
		$min_id = $request->getParam('min_id');
		$month = $request->getParam('month');
		$year = $request->getParam('year');
		//$exppageno = base64_decode($request->getParam('exppageno'));
		
		if($scheme_id == "" && $min_id == ""){
			exit;
		}else{
			$dbobj = new Application_Model_Schemeimport();
			$dataassign = $dbobj->csvexportmethodpfmsinitationdb($scheme_id,$min_id,$month,$year);
			$schemename = $dbobj->getschemename(base64_decode($scheme_id));
			$this->view->assign("exportarray",$dataassign);
			$this->view->assign("schemename",$schemename);
		}
	}

	// check varcode function start now 

public function checkvercodeAction(){
			$request=$this->getRequest(); 
            $vercode=$request->getParam('vercode');
			//echo $_SESSION["vercode"];
		 if($vercode != $_SESSION["vercode"]){
                echo json_encode("failed");exit;
            }else{
				echo json_encode("successful");exit;
			}	
		}
}
?>