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
require_once 'Soaplib/nusoap.php';
require_once 'Uidai/xmlseclibs.php';

//file incusion end here

Class TestaadharvalidateapiController extends Zend_Controller_Action{
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
	
/*  --------------- aadhar validate by ncpi controller start now-------------*/ 

	public function validateaadharAction(){
			
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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		$status = $request->getParam('status');
		$scm_type = base64_decode($request->getParam('scm_type'));
		$paramid = "scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id)."&scm_type=".base64_encode($scm_type)."&status=".base64_encode($status);
		$valid_aadhar = new Application_Model_Validateaadhar();
//die("jsdfh");

		//$schemedetail = $valid_aadhar->getschemename($scheme_id);
		$show_list = $valid_aadhar->aadharvalidate($scheme_id);
		//print_r($show_list);die;
		$aadhar_status_true=0;
		$aadhar_status_false=0;
		if(sizeof($show_list)>0){
		for($i=0;$i<sizeof($show_list);$i++){
		 try{
		$client = new nusoap_client(WEB_SERVICE_LINK,true);
		$request_param = array('arg0'=>array(
								"aadhaarNumber" => $show_list[$i]['aadhar_num'],
								"mobileNumber" => $show_list[$i]['mobile_num'],
								"requestNumber" => $show_list[$i]['uniq_user_id'],
								"requestedDateTimeStamp" =>$show_list[$i]['created'].'.064'
								));
		$result=$client->call('getAadhaarStatus', $request_param);
			$err = $client->getError();
			//print_r($result);die;
//***********************file error log for soap webservices **************************
					
					// $fname = "C:\xampp\htdocs\dbtapp_aadhar\error_log.txt";
					// $fhandle = fopen($fname,"w");
					// fwrite($fhandle,$err."<br /><br />");
					// fclose($fhandle);

			if($result['return']['status']=='A'){
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_status($scheme_id,$aadhar_number,1,Y,$result['return']['error']);
				$aadhar_status_true+=1;
			}else{ 
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_status($scheme_id,$aadhar_number,2,N,$result['return']['error']);
				$aadhar_status_false+=1;
				
			}	
}
		catch(Exception $err){
			 continue;
		echo 1;exit;
		 }}
		echo $paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false).'&validate_status='.base64_encode(2);exit;
		}else{
			
			echo $paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false).'&validate_status='.base64_encode(2);exit;
		}
		

	
}

/*  --------------- aadhar validate by ncpi controller end now-------------*/ 


/*  --------------- aadhar validate by uidai controller start now-------------*/ 

	public function validateaadharbyuidaiAction(){
			
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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		$status = $request->getParam('status');
		$scm_type = base64_decode($request->getParam('scm_type'));
		$paramid = "scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id)."&scm_type=".base64_encode($scm_type)."&status=".base64_encode($status);
		$valid_aadhar = new Application_Model_Testvalidateaadhar();
//die("jsdfh");

		//$schemedetail = $valid_aadhar->getschemename($scheme_id);
		$show_list = $valid_aadhar->aadharvalidatebyuidai($scheme_id);
		//print_r($show_list);die;
		$aadhar_status_true=0;
		$aadhar_status_false=0;
		for($i=0;$i<sizeof($show_list);$i++){
			//echo "<pre>";print_r($show_list);die;
			$aadhaar_no = $show_list[$i]['aadhar_num'];
			$txn = "NIC".$aadhaar_no.date("YmdHis")."eDBT";
			$ts = date('Y-m-d').'T'.date('H:i:s');
			$name=$show_list[$i]['name'];
			$gender=$show_list[$i]['gender'];
			$dob=$show_list[$i]['dob'];
			$state_name=$show_list[$i]['state_name'];
			$pincode=$show_list[$i]['pincode'];
			$pid_block="<Pid ts=\"$ts\" ver=\"1.0\"><Demo>
	<Pi ms=\"MS\" name=\"$name\" gender=\"$gender\" dob=\"$dob\" dobt=\"D\"></Pi>
	<Pa ms=\"MS\"  state=\"$state_name\"></Pa>
		<Pfa ms=\"MS\" mv=\"MV\" av=\"\" lav=\"\" lmv=\"\"/>
		</Demo></Pid>";
			
			// generate aes-256 session key
			$session_key = openssl_random_pseudo_bytes(32);

			//DECRYPT_FILE
			// generate auth xml
			$auth_xml = '<Auth ac="'.AC.'" lk="'.LK.'" sa="'.SA.'" tid="'.TID.'" txn="'.$txn.'" uid="'.$aadhaar_no.'" ver="'.API_VERSION.'"><Uses bio="n" otp="n" pa="y" pfa="n" pi="y" pin="n"/><Meta fdc="NA" idc="NA" lot="P" lov="'.$pincode.'" pip="NA" udc="'.UDC.'"/><Skey ci="'.$this->public_key_validity().'">'.$this->encrypt_session_key($session_key).'</Skey><Data type="X">'.$this->encrypt_pid($pid_block, $session_key).'</Data><Hmac>'.$this->calculate_hmac($pid_block, $session_key).'</Hmac></Auth>';
//echo $auth_xml;exit;


			// xmldsig the auth xml
			$doc = new DOMDocument();
			$doc->loadXML($auth_xml);
			$objDSig = new XMLSecurityDSig('');
			$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
			$objDSig->addReference(
				$doc,
				XMLSecurityDSig::SHA256,
				array(
					'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
					'http://www.w3.org/2001/10/xml-exc-c14n#'
				),
				array('force_uri' => true)
			);
			$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));
			openssl_pkcs12_read(file_get_contents(P12_FILE), $key, AUTHPASS);
			$objKey->loadKey($key["pkey"]);
			$objDSig->add509Cert($key["cert"]);
			$objDSig->sign($objKey, $doc->documentElement);
			//print_r($auth_xml);die;

			// make a request to uidai
			// $ch = curl_init(AUTHURLCURL);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_xml);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  // "Accept: application/xml",
			  // "Content-Type: application/xml"
			// )); 
			// $response = curl_exec($ch);
			$response = '<?xml version="1.0" encoding="UTF-8"?><AuthRes code="9857fc6edc3742e0aed4d7da797b5d30" info="02{3c33368bc3bb81ffc312ff6456ad9f1829ea81bcac538cad2d8a4c3905b2844c,75fc22916af427c31fc3904a2dbcc8c33d94e6a6e75958c33ced9724f4a392dd,01b002002b002020,1.0,20170512123749,0,0,0,1.6,73bd5abf744d9c0ec0c3c44e9a7c2eaf9cc2fc9e9d3e49f2c11a3b09ee314cc3,57266d21922bc5f567bb4426c4924210afe690a51a98dec2b612612ef76440ca,ZZ1057CABI,P,208017,23,E,100,NA,E,NA,NA,NA,NA,NA,efa1f375d76194fa51a3556a97e641e61685f914d446979da50a551a4333ffd7}" error="999" ret="n" ts="2017-05-12T18:07:50.162+05:30" txn="NIC76822806982220170512123749eDBT"><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>QDQB3QO7Qc5ku/5YNl7flvJoL4B+PWrKU729GbtApjA=</DigestValue></Reference></SignedInfo><SignatureValue>pk8b5MLs0mA2GnLSRotNktST6Ud1J7BdSahBlLN9FwMjJGfQ2ux2sGJnKFjbfvF3VOgijzuQ9ksX
ckbtixEw2KMOWnbXgsmDiMc5Y8F1subawdgVlpEQ1fNxeLPoMnG59j1AqjTkTT4YtIm5seRGhTIZ
ryJJfTuP4C0OxcgBKmIK2UH9meooRKhZ0tUw8zRqQy40FjOZ8GYjJ8WO3NvOLM1oQd4HNtaYPP6W
ciQfG0M0KLjwPtPuShVcp5j0oN0aYeVPQFruaBMg2JnOnKzcUkGWdOF32mYnCezNhnLeUzxpdDaI
fvRF4Q7a5H5xPplnKQHcVZmqdRp8xcO2Qf/Rhg==</SignatureValue></Signature></AuthRes>';
			//$xml=simplexml_load_file($response);
			$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
			$json = json_encode($xml);
			$array = json_decode($json, true);
	
				
				if($array['@attributes']['ret']=='y'){
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_byuidai($scheme_id,$aadhar_number,1,"done");
				$aadhar_status_true+=1;
			}elseif($array['@attributes']['ret']=='n'){ 
					$errorRet=errorRet($array['@attributes']['error']);
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_byuidai($scheme_id,$aadhar_number,2,$errorRet);
				$aadhar_status_false+=1;
				
			}else{
			}	

		 }
		echo $paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false).'&validate_status='.base64_encode(2);exit;

	
}

/*  --------------- aadhar validate by uidai controller end now-------------*/ 


/*  --------------- non aadhar uidai validate controller start now-------------*/ 

	public function nonaadharuidaivalidateAction(){
			
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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		$status = $request->getParam('status');
		$scm_type = base64_decode($request->getParam('scm_type'));
		$paramid = "scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id)."&scm_type=".base64_encode($scm_type)."&status=".base64_encode($status);

		$request1 = $this->getRequest();
		$dataform = $request1->getPost();
	
		$valid_aadhar = new Application_Model_Validateaadhar();

		
		
		$show_list = $valid_aadhar->nonuidaiaadharvalidate($scheme_id);
		if(isset($dataform['validate']) && $dataform['validate']=='Click here to Aadhaar Number Validate'){
		$aadhar_status_true=0;
		$aadhar_status_false=0;
		for($i=0;$i<sizeof($show_list);$i++){
			
			if(in_array($show_list[$i]['id'],$dataform['beneficiary_id'])){

			$aadhaar_no = $show_list[$i]['aadhar_num'];
			$txn = "NIC".$aadhaar_no.date("YmdHis")."eDBT";
			$ts = date('Y-m-d').'T'.date('H:i:s');
			$name=$show_list[$i]['name'];
			$gender=$show_list[$i]['gender'];
			$dob=$show_list[$i]['dob'];
			$state_name=$show_list[$i]['state_name'];
			$pincode=$show_list[$i]['pincode'];
			$pid_block="<Pid ts=\"$ts\" ver=\"1.0\"><Demo>
	<Pi ms=\"MS\" name=\"$name\" gender=\"$gender\" dob=\"$dob\" dobt=\"D\"></Pi>
	<Pa ms=\"MS\"  state=\"$state_name\"></Pa>
	<Pfa ms=\"MS\" mv=\"MV\" av=\"\" lav=\"\" lmv=\"\"/>
	</Demo></Pid>";
			
			// generate aes-256 session key
			$session_key = openssl_random_pseudo_bytes(32);

			
			// generate auth xml
			$auth_xml = '<Auth ac="'.AC.'" lk="'.LK.'" sa="'.SA.'" tid="'.TID.'" txn="'.$txn.'" uid="'.$aadhaar_no.'" ver="'.API_VERSION.'"><Uses bio="n" otp="n" pa="y" pfa="n" pi="y" pin="n"/><Meta fdc="NA" idc="NA" lot="P" lov="'.$pincode.'" pip="NA" udc="'.UDC.'"/><Skey ci="'.$this->public_key_validity().'">'.$this->encrypt_session_key($session_key).'</Skey><Data type="X">'.$this->encrypt_pid($pid_block, $session_key).'</Data><Hmac>'.$this->calculate_hmac($pid_block, $session_key).'</Hmac></Auth>';
//echo $auth_xml;exit;


			// xmldsig the auth xml
			$doc = new DOMDocument();
			$doc->loadXML($auth_xml);
			$objDSig = new XMLSecurityDSig('');
			$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
			$objDSig->addReference(
				$doc,
				XMLSecurityDSig::SHA256,
				array(
					'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
					'http://www.w3.org/2001/10/xml-exc-c14n#'
				),
				array('force_uri' => true)
			);
			$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));
			openssl_pkcs12_read(file_get_contents(P12_FILE), $key, AUTHPASS);
			$objKey->loadKey($key["pkey"]);
			$objDSig->add509Cert($key["cert"]);
			$objDSig->sign($objKey, $doc->documentElement);
			//print_r($auth_xml);die;

			// make a request to uidai
			// $ch = curl_init(AUTHURLCURL);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $auth_xml);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  // "Accept: application/xml",
			  // "Content-Type: application/xml"
			// )); 
			// $response = curl_exec($ch);
			$response = '<?xml version="1.0" encoding="UTF-8"?><AuthRes code="9857fc6edc3742e0aed4d7da797b5d30" info="02{3c33368bc3bb81ffc312ff6456ad9f1829ea81bcac538cad2d8a4c3905b2844c,75fc22916af427c31fc3904a2dbcc8c33d94e6a6e75958c33ced9724f4a392dd,01b002002b002020,1.0,20170512123749,0,0,0,1.6,73bd5abf744d9c0ec0c3c44e9a7c2eaf9cc2fc9e9d3e49f2c11a3b09ee314cc3,57266d21922bc5f567bb4426c4924210afe690a51a98dec2b612612ef76440ca,ZZ1057CABI,P,208017,23,E,100,NA,E,NA,NA,NA,NA,NA,efa1f375d76194fa51a3556a97e641e61685f914d446979da50a551a4333ffd7}" ret="n" ts="2017-05-12T18:07:50.162+05:30" txn="NIC76822806982220170512123749eDBT"><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>QDQB3QO7Qc5ku/5YNl7flvJoL4B+PWrKU729GbtApjA=</DigestValue></Reference></SignedInfo><SignatureValue>pk8b5MLs0mA2GnLSRotNktST6Ud1J7BdSahBlLN9FwMjJGfQ2ux2sGJnKFjbfvF3VOgijzuQ9ksX
ckbtixEw2KMOWnbXgsmDiMc5Y8F1subawdgVlpEQ1fNxeLPoMnG59j1AqjTkTT4YtIm5seRGhTIZ
ryJJfTuP4C0OxcgBKmIK2UH9meooRKhZ0tUw8zRqQy40FjOZ8GYjJ8WO3NvOLM1oQd4HNtaYPP6W
ciQfG0M0KLjwPtPuShVcp5j0oN0aYeVPQFruaBMg2JnOnKzcUkGWdOF32mYnCezNhnLeUzxpdDaI
fvRF4Q7a5H5xPplnKQHcVZmqdRp8xcO2Qf/Rhg==</SignatureValue></Signature></AuthRes>';
			//$xml=simplexml_load_file($response);
			$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
			$json = json_encode($xml);
			$array = json_decode($json, true);
	

			if($array['@attributes']['ret']=='y'){
				
				$status_update=$valid_aadhar->update_aadhar_byuidai($scheme_id,$aadhaar_no,1,"done");
				$aadhar_status_true+=1;
			}elseif($array['@attributes']['ret']=='n'){ 
					$errorRet=errorRet($array['@attributes']['error']);
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_byuidai($scheme_id,$aadhar_number,2,$errorRet);
				$aadhar_status_false+=1;
				
			}else{
			}
		  }
		}
	$this->_redirect('/schemeowner/nonaadharuidailist?'.$paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false));
	}else{
			if(sizeof($dataform['beneficiary_id'])>0){
			$aadhar_status1=0;
			for($i=0;$i<sizeof($show_list);$i++){
				if(in_array($show_list[$i]['id'],$dataform['beneficiary_id'])){
		 $delete_reportlist = $valid_aadhar->deletebeneficiary($scheme_id,$show_list[$i]['id']);
			$aadhar_status1+=1;
			  }
			}
		$this->_redirect('/schemeowner/nonaadharuidailist?'.$paramid.'&aadhar_status1='.base64_encode($aadhar_status1));
		}
	$this->_redirect('/schemeowner/nonaadharuidailist?'.$paramid.'&aadhar_status1='.base64_encode("not"));
		}
	
		
		//echo $paramid.'&aadhar_status='.base64_encode($aadhar_status);exit;
	}
	



/*  --------------- non aadhar seeded validate controller start now-------------*/ 

	public function nonaadharseededvalidateAction(){
			
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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		$status = $request->getParam('status');
		$scm_type = base64_decode($request->getParam('scm_type'));
		$paramid = "scheme_id=".base64_encode($scheme_id)."&min_id=".base64_encode($min_id)."&scm_type=".base64_encode($scm_type)."&status=".base64_encode($status);

		$request1 = $this->getRequest();
		$dataform = $request1->getPost();
		//echo $dataform['validate'];	echo "<pre>";print_r($dataform);die;
		$valid_aadhar = new Application_Model_Validateaadhar();

		
		
		$show_list = $valid_aadhar->nonseededaadharvalidate($scheme_id);
		//echo sizeof($show_list); echo "<pre>";print_r($show_list);die;
		if(isset($dataform['validate']) && $dataform['validate']=='Click here to Aadhaar Number Validate'){
		$aadhar_status_true=0;
		$aadhar_status_false=0;
		for($i=0;$i<sizeof($show_list);$i++){
			
			if(in_array($show_list[$i]['id'],$dataform['beneficiary_id'])){

		try{	
		$client = new nusoap_client(WEB_SERVICE_LINK,true);
		//print_r($client);die("hello");
		$request_param = array('arg0'=>array(
								"aadhaarNumber" => $show_list[$i]['aadhar_num'],
								"mobileNumber" => $show_list[$i]['mobile_num'],
								"requestNumber" => $show_list[$i]['uniq_user_id'],
								"requestedDateTimeStamp" =>$show_list[$i]['created'].'.064'
								));

			//print_r($request_param);echo "<pre>";
			//$result=$client->call('getAadhaarStatus', $request_param);
		
			$result=$client->call('getAadhaarStatus', $request_param);
			$err = $client->getError();

			

			if($result['return']['status']=='A'){
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_status($scheme_id,$aadhar_number,1,Y,$result['return']['error']);
				$aadhar_status_true+=1;
			}else{ 
				$aadhar_number=$show_list[$i]['aadhar_num'];
				$status_update=$valid_aadhar->update_aadhar_status($scheme_id,$aadhar_number,2,N,$result['return']['error']);
				$aadhar_status_false+=1;
				}
			} catch(Exception $err){
		$this->_redirect('/schemeowner/nonaadharseededlist?'.$paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false).'&status=1');
		}

		  }
		}
	$this->_redirect('/schemeowner/nonaadharseededlist?'.$paramid.'&aadhar_status='.base64_encode($aadhar_status_true).'&aadhar_status_false='.base64_encode($aadhar_status_false));
	}else{
			if(sizeof($dataform['beneficiary_id'])>0){
			$aadhar_status1=0;
			for($i=0;$i<sizeof($show_list);$i++){
				if(in_array($show_list[$i]['id'],$dataform['beneficiary_id'])){
		 $delete_reportlist = $valid_aadhar->deletebeneficiary($scheme_id,$show_list[$i]['id']);
			$aadhar_status1+=1;
			  }
			}
		$this->_redirect('/schemeowner/nonaadharseededlist?'.$paramid.'&aadhar_status1='.base64_encode($aadhar_status1));
		}
	$this->_redirect('/schemeowner/nonaadharseededlist?'.$paramid.'&aadhar_status1='.base64_encode("not"));
		}
	
		
		//echo $paramid.'&aadhar_status='.base64_encode($aadhar_status);exit;
	}
	

/*  --------------- non aadhar seeded validate controller end now-------------*/ 


// ***************** delete beneficiary start now ******************************

public function deletebeneficiaryAction()
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
        
            $request = $this->getRequest();
           $scheme_id = base64_decode($request->getParam('scheme_id'));
		   $beneficiary=base64_decode($request->getPost('beneficiary'));

            $delete_report = new Application_Model_Validateaadhar;
			
            $delete_reportlist = $delete_report->deletebeneficiary($scheme_id,$beneficiary);
			
          if($delete_reportlist>0){
				echo "1";exit;
			}else{
				echo "0";exit;
		}
	}

	
	// create pfms xml file for all beneficiary user
	public function createpfmsxmlbeneficiaryAction(){

			

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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Validateaadhar();
		$schemedetail = $cmi_list->getschemename($scheme_id);
			
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$schemedetails = $schemedetail->toArray();
			if($schemedetails[0]['scheme_type']==1){
				$scheme_type='Cash';
			}elseif($schemedetails[0]['scheme_type']==2){
			$scheme_type='Kind';
		}else{
				$scheme_type='Other';
			}
		
		$getbeneficiarydetail = $cmi_list->getallpfmsbeneficiaries($scheme_id);
// echo "<pre>";print_r($schemedetails);echo "<pre>";print_r($getbeneficiarydetail);echo "<pre>";die;

		if($getbeneficiarydetail>0 && $schemedetail>0 && $scheme_code>0){

					
			//Beneficiary request xml to PFMS	
			$benreqcount = $cmi_list->benreqcount(date('d-m-Y'));
			
			$sno = 0001;
			$MsgId = $OrgId_SchmeCd.'DBTBENREQ'.date(dmY).$sno; //Unique Message Identifier. Source System Id given by PFMS(DBTBENREQDDMMYYYYN).
			$CreDtTm = date("Y-m-d").'T'.date("h:i:s"); //It is data and time when the message is generated in source system.
			$NbOfTxs = count($getbeneficiarydetail); //Should be grater than 0. Count of CstmrTxInf tag.
			$Src = '0009'; //State Scheme Source System Code. Provided by PFMS.
			$Dest = 'CPSMS'; //Since this file is always sent to PFMS the value for this attribute will always contain “CPSMS”.
			$InitgPty_Nm = 'DDO'; //Owner Agency of Data i.e. District Level DDO.
			$InitgPty_PrTry_Id = 'DDO'; // Data Owner Agency Unique Code i.e. for District Level DDO in PFMS. Reference Key of agency / PFMS Unique Code.
			$CstmrInfId = '1302170002'; //Batch Number- Source System Id given by PFMS. (DDMMYYXXXX)
			$CstmrInfDt = '2017-02-13'; //YYYY-MM-DD format Batch Date
			$OrgId_Cd = 'DDO'; //Implementing Agency Code. Owner Agency of Data i.e. District Level DDO.
			$OrgId_Nm = 'DDO'; //Implementing Agency name. Data Owner Agency Unique Code i.e. for District Level DDO in PFMS.
			$OrgId_SchmeCd = 'EXV94'; //Scheme Code - Scheme code needs to be taken from PFMS before starting data exchange.
						
			$xmlfile = '<DbtBeneficiaries xmlns="http://cpsms.nic.in/BeneficiaryDataRequest">
				<CstmrDtls>
					<GrpHdr>
						<MsgId>'.$MsgId.'</MsgId>
						<CreDtTm>'.$CreDtTm.'</CreDtTm>
						<NbOfTxs>'.$NbOfTxs.'</NbOfTxs>
						<Src>'.$Src.'</Src>
						<Dest>'.$Dest.'</Dest>
						<InitgPty>
							<Nm>'.$InitgPty_Nm.'</Nm>
							<PrTry>
								<Id>'.$InitgPty_PrTry_Id.'</Id>
							</PrTry>
						</InitgPty>
					</GrpHdr>
					<CstmrInf>
						<CstmrInfId>'.$CstmrInfId.'</CstmrInfId>
						<CstmrInfDt>'.$CstmrInfDt.'</CstmrInfDt>';

							for($i=0;$i<sizeof($getbeneficiarydetail);$i++){

								$xmlfile .= '
							<CstmrTxInf>
								<OrgId>
									<Cd>'.$OrgId_Cd.'</Cd>
									<Nm>'.$OrgId_Nm.'</Nm>
									<SchmeCd>'.$OrgId_SchmeCd.'</SchmeCd>
								</OrgId>
								<Cstmr>
									<Purp>
										<Cd>'.$getbeneficiarydetail[$i]['purp_cd'].'</Cd>
									</Purp>
									<PrTry>
										<Id>'.$getbeneficiarydetail[$i]['uniq_user_id'].'</Id>
									</PrTry>
									<CPSMSId>'.$getbeneficiarydetail[$i]['pfmsbeneficiary_code'].'</CPSMSId>
									<Tp>'.$scheme_type.'</Tp>
									<Titl>'.$getbeneficiarydetail[$i]['beneficiary_title'].'</Titl>
									<Nm>'.$getbeneficiarydetail[$i]['name'].'</Nm>
									<NmRegLang>'.$getbeneficiarydetail[$i]['beneficiary_regional_lang'].'</NmRegLang>
									<PstlAdr>
										<AdrLine1>'.$getbeneficiarydetail[$i]['home_address'].'</AdrLine1>
										<AdrLine2></AdrLine2>
										<AdrLine3></AdrLine3>
										<Prtry>
											<LctnCd>'.$getbeneficiarydetail[$i]['village_code'].'</LctnCd>
											<TwnCd></TwnCd>
											<BlokCd>'.$getbeneficiarydetail[$i]['block_code'].'</BlokCd>
											<SubDvsnCd></SubDvsnCd>
											<DstCd>'.$getbeneficiarydetail[$i]['district_code'].'</DstCd>
											<PrvcCd></PrvcCd>
											<CtryCd>91</CtryCd>
										</Prtry>
										<PstCd>'.$getbeneficiarydetail[$i]['pincode'].'</PstCd>
									</PstlAdr>
									<Gendr>'.$getbeneficiarydetail[$i]['gender'].'</Gendr>
									<BirthDt>'.$getbeneficiarydetail[$i]['dob'].'</BirthDt>
									<ReltnCd></ReltnCd>
									<ReltnNm></ReltnNm>
									<Ctg></Ctg>
									<Rlg></Rlg>
									<YrOfPs></YrOfPs>
									<CtctDtls>
										<PhneNb></PhneNb>
										<MobNb>'.$getbeneficiarydetail[$i]['mobile_num'].'</MobNb>
										<EmailAdr>'.$getbeneficiarydetail[$i]['email_id'].'</EmailAdr>
									</CtctDtls>
									<CstmrAcct>
										<CstmrAgt>
											<FinInstnId>
												<BICFI>'.$getbeneficiarydetail[$i]['bank_name'].'</BICFI>
												<BrnchId>'.$getbeneficiarydetail[$i]['ifsc'].'</BrnchId>
											</FinInstnId>
										</CstmrAgt>
										<AcctId>
											<Othr>
												<BBAN>'.$getbeneficiarydetail[$i]['bank_account'].'</BBAN>
												<SOSE>'.$getbeneficiarydetail[$i]['aadhar_num'].'</SOSE>
											</Othr>
										</AcctId>
									</CstmrAcct>
								</Cstmr>
							</CstmrTxInf>
							';
							}

						$xmlfile .= '</CstmrInf>
				</CstmrDtls> 
			</DbtBeneficiaries>';

					// print $xmlfile; exit;	
					

			$filename = "benrequest_".time().".xml";
			$myfile = fopen("/var/www/html/pfms/benrequest/".$filename, "w") or die("Unable to open file!");
			chmod("/var/www/html/pfms/benrequest/".$filename, 0777);

			fwrite($myfile, $xmlfile);
			fclose($myfile);
		}	
		
	}
	
	// create pfms xml file for all beneficiary transaction 
	public function createpfmsxmlfortransactionAction(){
		
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
		$scheme_id = base64_decode($request->getParam('scheme_id'));
		$min_id = base64_decode($request->getParam('min_id'));
		
		if(($scheme_id == '') || (!is_numeric($scheme_id))){
			$this->_redirect('');
		}
		$cmi_list = new Application_Model_Testvalidateaadhar();
		$schemedetail = $cmi_list->getschemename($scheme_id);
			
		$scheme_code = $cmi_list->getschemecode($scheme_id);
		$schemedetails = $schemedetail->toArray();
			if($schemedetails[0]['scheme_type']==1){
				$scheme_type='Cash';
			}elseif($schemedetails[0]['scheme_type']==2){
			$scheme_type='Kind';
		}else{
				$scheme_type='Other';
			}
		
		$getbeneficiarydetail = $cmi_list->getallpfmstransaction($scheme_id);
		// echo "<pre>";print_r($schemedetails);
		// echo "<pre>";print_r($scheme_code->toArray()); exit;
		// echo "<pre>";print_r($getbeneficiarydetail);echo "<pre>"; exit;
		
		if($getbeneficiarydetail>0 && $schemedetail>0 && $scheme_code>0){
			
			$scheme_details_data = $schemedetail->toArray();
			$transactionTableName = 'dbt_'.$scheme_details_data['0']['scheme_table'].'_transaction';
			$schemetablename = 'dbt_'.$scheme_details_data['0']['scheme_table'];
			$transactionids = array();
			
			$schemecodedata = $scheme_code->toArray();
			$scheme_code_data = $schemecodedata[0];
					
			//Payment request xml to PFMS	
			$OrgId_SchmeCd = $scheme_details_data['0']['pfms_scheme_code']; //Scheme Code - Scheme code needs to be taken from PFMS before starting data exchange.
			$payreqcount = $cmi_list->payreqcount(date('d-m-Y'));
			$sno = $payreqcount + 1;
			$MsgId = $OrgId_SchmeCd.'DBTPAYREQ'.date('dmY').$sno; //Unique Message Identifier. Source System Id given by PFMS(DBTPAYREQDDMMYYYYN).
			$CreDtTm = date("Y-m-d").'T'.date("h:i:s"); //It is data and time when the message is generated in source system.
			$NbOfTxs = count($getbeneficiarydetail); //Should be grater than 0. Count of CstmrTxInf tag.
		// echo $NbOfTxs; exit;
			$Src = '0009'; //It is source system id allotted by PFMS.
			$Dest = 'PFMS'; //Since this file is always sent to PFMS the value for this attribute will always contain “PFMS”.
			$InitgPty_Nm = 'DDO'; //Owner Agency of Data i.e. Distrcit level Dept DDO
			$InitgPty_PrTry_Id = 'DDO'; //Data Owner Agency Unique Code in PFMS
			
			$ds = '4444'; //DS refers to the 4 digit Source System Id allotted by PFMS
			$PmtInfId = 'CP'.$ds.date('dmY').$sno;
		// echo $PmtInfId; exit;
			$PmtInfDt = '2017-02-13'; //YYYY-MM-DD format Batch Date
			$PmtAuthCtreCd = $scheme_code_data['scheme_codification']; //DBT Mission Scheme Code for Central Sector Schemes
			$PmtAuthPrvcCd = ''; //External System Token No. or Unique ID issued by the Treasury against the Bill submitted by Dept
			$OrgIdCd = $InitgPty_PrTry_Id; // Data Owner Agency Unique Code in PFMS
			$OrgIdNm = $PmtInfId; //Same as Batch ID <PmtInfId>
			$OrgIdSchmeCd = $scheme_details_data['0']['pfms_scheme_code']; //Scheme code needs to be taken from PFMS before starting data exchange
			
			//======================
			$OrgIdCtg = ''; //Category of Beneficiary. Possible values would be shared. It is optional tag and value if provided will be validated against the master
			$OrgIdRegType = ''; //F- Fresh, R-Renewal . It is applicable only for Scholarship Schemes
			$PmtMtd = ''; //The value will be always given as per below codes. A- Account Based Payment, U- Aadhaar Based Payment
			$BtchBookg = 'true';
			$ReqdExctnDt = date('Y-m-d'); //YYYY-MM-DD formatted date on which payment is to be processed. It will be used only if payment is to be made in future date. It should be greater or equal to current date
			//======================

			$EndToEndId = 'AI876544760034DF';//As generated in the source system i.e. treasury System. It should be unique across all transactions throughout the life of integration.
			
			$DigestValue = 'ORIMgATDWfXSCd09dnx226rGfeE=';
			$SignatureValue = 'q1XuY3SE4hSVr7iPvFgvrqWBLikm3EwiBZLFcOzb+dvNhIu7WJcy8PvJLgf....';
			$X509Certificate = 'MIID6DCCAtCgAwIBAgIKVgQ37ClrrLtPnzANBgkqhkiG9w0BAQsFADBjMQ....';
			$RSAKeyValueModulus = 'yUGmhAwYeENc7w9UAlYR67RKbM815BaRbPoiwsKbtTPhCFa0+jUROjcJSVc....';
			$Exponent = 'AQAB';
			
			$CtrlSum = 0;
			foreach ($getbeneficiarydetail as $key=>$amountval) {
				$CtrlSum += $amountval['amount'];
			}
			
	$xmlfile = '<DbtPayment xmlns="http://cpsms.nic.in/PaymentRequest">
	<CstmrCdtTrfInitn>
		<GrpHdr>
			<MsgId>'.$MsgId.'</MsgId>
			<CreDtTm>'.$CreDtTm.'</CreDtTm>
			<NbOfTxs>'.$NbOfTxs.'</NbOfTxs>
			<CtrlSum>'.$CtrlSum.'</CtrlSum>
			<Src>'.$Src.'</Src>
			<Dest>'.$Dest.'</Dest>
			<InitgPty>
				<Nm>'.$InitgPty_Nm.'</Nm>
				<PrTry>
					<Id>'.$InitgPty_PrTry_Id.'</Id>
				</PrTry>
			</InitgPty>
		</GrpHdr>
		<PmtInf>
			<PmtInfId>'.$PmtInfId.'</PmtInfId>
			<PmtInfDt>'.$PmtInfDt.'</PmtInfDt>
			<PmtAuthCtre>
				<Cd>'.$PmtAuthCtreCd.'</Cd>
			</PmtAuthCtre>
			<PmtAuthPrvc>
				<Cd>'.$PmtAuthPrvcCd.'</Cd>
			</PmtAuthPrvc>
			<OrgId> 
				<Cd>'.$OrgIdCd.'</Cd>
				<Nm>'.$OrgIdNm.'</Nm>
				<SchmeCd>'.$OrgIdSchmeCd.'</SchmeCd>
			</OrgId>
			<BtchBookg>'.$BtchBookg.'</BtchBookg>
			<ReqdExctnDt>'.$ReqdExctnDt.'</ReqdExctnDt>';
			$i = 0;
			foreach ($getbeneficiarydetail as $key=>$value) {
				$transactionids[$i] = $getbeneficiarydetail[$i]['txn_id'];
				$xmlfile .= '
			<CdtTrfTxInf>
				<PmtId>
					<EndToEndId>'.$EndToEndId.'</EndToEndId>
				</PmtId>
				<Amt>
					<CtreAmt>'.$value['amount'].'</CtreAmt>
				</Amt>
				<CdtrAgt>
					<FinInstnId>
						<BICFI>'.$value['bank_name'].'</BICFI>
						<BrnchId>'.$value['ifsc'].'</BrnchId>
					</FinInstnId>
				</CdtrAgt>
				<Cdtr>
					<PrTry>
						<Id>'.$value['uniq_user_id'].'</Id>
					</PrTry>
					<CPSMSId>'.$value['pfmsbeneficiary_code'].'</CPSMSId>
					<Tp>'.$value['beneficiary_type'].'</Tp>
					<Titl>'.$value['beneficiary_title'].'</Titl>
					<Nm>'.$value['name'].'</Nm>
					<PstlAdr>
						<Prtry>
							<DstCd>'.$value['district_code'].'</DstCd>
							<PrvcCd>'.$value['state_code'].'</PrvcCd>
						</Prtry>
					</PstlAdr>
					<CdtrAcct>
						<Id>
							<Othr>
								<BBAN>'.$value['bank_account'].'</BBAN>
								<SOSE>'.$value['aadhar_num'].'</SOSE>
							</Othr>
						</Id>
					</CdtrAcct>
				</Cdtr>
				<Purp>
					<Cd>'.$value['purpose'].'</Cd>
					<Amt>
						<CtreAmt>'.$value['amount'].'</CtreAmt>
					</Amt>
				</Purp>
				<RmtInf>
					<Strd>
						<FrmDt>'.date("Y-m-d", strtotime($value['from_payment_date'])).'</FrmDt>
						<ToDt>'.date("Y-m-d", strtotime($value['to_payment_date'])).'</ToDt>
					</Strd>
					<RltdInf>
						<Rem>'.$value['remarked'].'</Rem>
					</RltdInf>
				</RmtInf>
			</CdtTrfTxInf>';
			$i++;
			}
			
			$xmlfile .= '
		</PmtInf>
	</CstmrCdtTrfInitn>
	<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
		<SignedInfo>
			<CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
			<SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" />
			<Reference URI="">
				<Transforms>
					<Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
					<Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments" />
				</Transforms>
				<DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" />
				<DigestValue>'.$DigestValue.'</DigestValue>
			</Reference>
		</SignedInfo>
		<SignatureValue>'.$SignatureValue.'</SignatureValue>
		<KeyInfo>
			<X509Data>
				<X509Certificate>'.$X509Certificate.'</X509Certificate>
			</X509Data>
			<KeyValue>
				<RSAKeyValue>
					<Modulus>'.$RSAKeyValueModulus.'</Modulus>
					<Exponent>'.$Exponent.'</Exponent>
				</RSAKeyValue>
			</KeyValue>
		</KeyInfo>
	</Signature>
</DbtPayment>';
			
			$filename = "payrequest_".time().".xml";
			$myfile = fopen("/var/www/html/pfms/payrequest/".$filename, "w") or die("Unable to open file!");
			chmod("/var/www/html/pfms/payrequest/".$filename, 0777);
			fwrite($myfile, $xmlfile);
			fclose($myfile);
			
			
			$updatepayxmlstatus = $cmi_list->updatepayxmlstatus($transactionTableName,$transactionids);
			$updatepayreqcount = $cmi_list->updatepayreqcount(date('d-m-Y'),$sno);
			
			if($updatepayreqcount){
				$this->_redirect('/managetransaction/schemeview');
			} else {
				$this->_redirect('/managetransaction/schemeview');
			}
		
		}

		
		
	}

public function encrypt_using_session_key($data, $session_key)
{
    $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $pad = $blockSize - (strlen($data) % $blockSize);
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $session_key, $data . str_repeat(chr($pad), $pad), MCRYPT_MODE_ECB));
}
public function encrypt_pid($pid_block, $session_key)
{
    return $this->encrypt_using_session_key($pid_block, $session_key);
}

public function decrypt_using_symmetric_key($data, $symmetric_key)
{
    $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $pad = $blockSize - (strlen($data) % $blockSize);
    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $symmetric_key, $data, MCRYPT_MODE_ECB);
}

public function calculate_hmac($data, $session_key)
{
    return $this->encrypt_using_session_key(hash('sha256', $data, true), $session_key);
}

public function public_key_validity()
{
    //global PUBLIC_CERT_PATH;
    $certinfo = openssl_x509_parse(file_get_contents(PUBLIC_CERT_PATH));
    return date('Ymd', $certinfo['validTo_time_t']);
}

public function encrypt_session_key($session_key)
{
   // global PUBLIC_CERT_PATH;
    $pub_key = openssl_pkey_get_public(file_get_contents(PUBLIC_CERT_PATH));
    $keyData = openssl_pkey_get_details($pub_key);
    openssl_public_encrypt($session_key, $encrypted_session_key, $keyData['key'], OPENSSL_PKCS1_PADDING);
    return base64_encode($encrypted_session_key);
}

}
?>