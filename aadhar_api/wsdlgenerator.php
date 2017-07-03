<?php
// error_reporting(1);
// ini_set("error_reporting","E_ALL");
// ini_set("error_display",true);
require_once('config.php'); // for database connection if using
require_once('lib/nusoap.php');  //nusoap inclueded 
$namespace='wsdlgenerator';//this is the location of coursecreation.php page
$server = new soap_server();//  Create object of soap server
$server->configureWSDL('GetSchemeDayWiseRecord');// Create wsdlfile name My Service or any name



//############################################################################
//to add the method for fetch record corresponding to the scheme code in scheme
function GETSCHEMETB($scheme_code = null){
	Global $db;
	$getData = $db->query("SELECT CONCAT('dbt_',scheme_table) AS beneftb, id AS scheme_id FROM dbt_scheme WHERE scheme_codification='".trim($scheme_code)."'",PDO::FETCH_ASSOC);
	$row = array();
	foreach($getData as $rowdata){
		$row[0] =$rowdata['beneftb'];
		$row[1] =$rowdata['scheme_id'];
		return $row;
	}
}
//fetch record function end here
$server->register(
// method name:
'GETSCHEMETB',
// parameter list:
array("scheme_code"=>"xsd:string"),
// return value(s):
array('return'=>'xsd:Array'),
// namespace:
$namespace,
// soapaction: (use default)
false,
// style: rpc or document
'rpc',
// use: encoded or literal
'encoded',
// description: documentation for the method
'Get the scheme table');
//############################################################################





//############################################################################
//to add the method for generate the xml record
function xmlGENrator($obj, $array) {
    foreach ($array as $key => $value){
        if(is_numeric($key))
            $key = 'beneficiary-details';

        if (is_array($value)){
            $node = $obj->addChild($key);
            xmlGENrator($node, $value);
        }
        else{
            $obj->addChild($key, htmlspecialchars($value));
        }
    }
}
//fetch record function end here
$server->register(
// method name:
'xmlGENrator',
// parameter list:
array("obj"=>"xsd:Array","array"=>"xsd:Array"),
// return value(s):
array('return'=>'xsd:string'),
// namespace:
$namespace,
// soapaction: (use default)
false,
// style: rpc or document
'rpc',
// use: encoded or literal
'encoded',
// description: documentation for the method
'Return the xml data to the its function');
//############################################################################




//############################################################################
function ValidatorFactor($scheme_code,$state_code,$date){
	if(($scheme_code == "") || ($state_code == "") || ($date == "")){
		return $data = array("EMPTY"=>"Parameters Missing");
	}else if(!intval($state_code)){
		 return $data = array("EMPTY"=>"Parameters Omitted");
	}else if($date != date("Y-m-d",strtotime("$date"))){
		 return $data = array("EMPTY"=>"Parameters Omitted!");
	}else{
		return $data = array();
	}
	return $data;
}

// Now register method
$server->register(
// method name:
'ValidatorFactor',
// parameter list:
array('scheme_code'=>'xsd:string','state_code'=>'xsd:integer','date'=>'xsd:date'),
// return value(s):
array('return'=>'xsd:Array'),
// namespace:
$namespace,
// soapaction: (use default)
false,
// style: rpc or document
'rpc',
// use: encoded or literal
'encoded',
// description: documentation for the method
'Get the Validation of the parameter');
//############################################################################




//############################################################################
//This function will take the all record based on the search criteria
function GETSchemeDetailsData($scheme_code,$state_code,$date){
	$datem = ValidatorFactor($scheme_code,$state_code,$date);
	//return $datem;
	if(array_key_exists("EMPTY",$datem)){
		//creating object of SimpleXMLElement
		$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><beneficiary-master/>");
		//function call to convert array to xml
		xmlGENrator($xml_user_info,$datem);
		//saving generated xml file
		//$xml_file = $xml_user_info->asXML('users.xml');
		$xml_file = $xml_user_info->asXML();
		//success and error message based on xml creation
		return $xml_file;
	}
	$return_value = GETSCHEMETB($scheme_code);//getting the scheme table by the scheme code
	$tb = $return_value[0];
	//RETURN $tb."AAA";
	if($tb == ""){
		//creating object of SimpleXMLElement
		$datem = array("EMPTY" => "Not Found");
		$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><beneficiary-master/>");
		//function call to convert array to xml
		xmlGENrator($xml_user_info,$datem);
		//saving generated xml file
		//$xml_file = $xml_user_info->asXML('users.xml');
		$xml_file = $xml_user_info->asXML();
		//success and error message based on xml creation
		return $xml_file;
	}else{
		Global $db;
		$dataarr = array();
		/*
			"SELECT BENE.name,BENE.dob,BENE.gender,BENE.aadhar_num,BENE.mobile_num,BENE.email_id,BENE.scheme_specific_unique_num,BENE.scheme_specific_family_num,BENE.home_address,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,BENE.pincode,BENE.ration_card_num,BENE.tin_family_id,BENE.bank_account,BENE.ifsc,BENE.aadhar_seeded,TR.uniq_user_id,TR.transfer_by,TR.amount,TR.fund_transfer,TR.transaction_date,TR.id AS ref_tid FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE BENE.state_code='".$state_code."' AND TR.transaction_date='".$date."' AND TR.service_status='0' ORDER BY TR.transaction_date DESC"
		*/
		
		$dataarr = $db->query("SELECT BENE.name,BENE.dob,BENE.gender,BENE.aadhar_num,BENE.mobile_num,BENE.email_id,BENE.scheme_specific_unique_num,BENE.scheme_specific_family_num,BENE.home_address,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,BENE.pincode,BENE.ration_card_num,BENE.tin_family_id,BENE.bank_account,BENE.ifsc,BENE.aadhar_seeded,TR.uniq_user_id,TR.transfer_by,TR.amount,TR.transaction_date,TR.id AS ref_tid,IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1) AS AadharSeededBeneficiaries,IF(TR.fund_transfer='APB' OR TR.fund_transfer='apb','APB','NON_APB') AS fund_transfer,IF(TR.id!='',1,0) AS no_of_beneficiries,IF(TR.fund_transfer='APB' OR TR.fund_transfer='apb',1,0) AS no_of_abp_beneficiries,TR.transaction_date AS Payment_Date FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE BENE.state_code='".$state_code."' AND TR.transaction_date='".$date."' AND TR.service_status='0' ORDER BY TR.transaction_date DESC",PDO::FETCH_ASSOC);
		$datatoBind = array();
		$multiarray = array();
		$i = 1;
		$tr_id = "";
		//return count($dataarr);
		$transaction_id = "DBTTXN".strtotime(date("Y-m-d H:i:s")).$state_code;
		foreach($dataarr as $val){
			$multiarray[$i] = array(
			"details" => array(
				"name" => $val['name'],
				"dob" => $val['dob'],
				"gender" => $val['gender'],
				"aadhar_num" => $val['aadhar_num'],
				"mobile_num" => $val['mobile_num'],
				"email_id" => $val['email_id'],
				"scheme_specific_unique_num" => $val['scheme_specific_unique_num'],
				"scheme_specific_family_num" => $val['scheme_specific_family_num'],
				"ration_card_num" => $val['ration_card_num'],
				"tin_family_id" => $val['tin_family_id'],
			),
			"location" => array(
				"address" => $val['home_address'],
				"village_code" => $val['village_code'],
				"village_name" => $val['village_name'],
				"panchayat_code" => $val['panchayat_code'],
				"panchayat_name" => $val['panchayat_name'],
				"block_code" => $val['block_code'],
				"block_name" => $val['block_name'],
				"district_code" => $val['district_code'],
				"district_name" => $val['district_name'],
				"state_code" => $val['state_code'],
				"state_name" => $val['state_name'],
				"pincode" => $val['pincode'],
			),
			"transactionDetails" => array(
				"RequestID" => $transaction_id,
				"aadhar_seeded" => $val['aadhar_seeded'],
				"bank_account" => $val['bank_account'],
				"ifsc" => $val['ifsc'],
				"amount" => $val['amount'],
				"fund_transfer" => $val['fund_transfer'],
				"transaction_date" => $val['transaction_date'],
				"transfer_by" => $val['transfer_by'],
				
				"AadharSeededBeneficiaries" => $val['AadharSeededBeneficiaries'],
				"no_of_beneficiries" => $val['no_of_beneficiries'],
				"no_of_abp_beneficiries" => $val['no_of_abp_beneficiries'],
				"Payment_Date" => $val['Payment_Date'],
			),
		);
			$tr_id .= "".$val['ref_tid'].",";
			//$datatoBind[$i] = $val;
			$i++;
		}
		if(!empty($multiarray)){
			//return $tb;
			//Here Update function will call for the incremental data
			$query = "UPDATE ".$tb."_transaction SET service_status='1' WHERE id IN(".trim($tr_id,",").")";
			$db->query($query);
			
			$scheme_id = $return_value[1];
			$query = "INSERT INTO dbt_service_manage(request_id,state_code,transaction_date,scheme_code,scheme_id)VALUES('$transaction_id',$state_code,'$date','$scheme_code',$scheme_id)";
			//return $query;
			$db->query($query);
		}
		
		//return $datatoBind;
		if(count(array_filter($multiarray) > 0) && !empty($multiarray)){
			$data = array_filter($multiarray);
		}else{
			$data = array("EMPTY"=>"No Record Found!");
		}
		//creating object of SimpleXMLElement
		$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><beneficiary-master/>");
		//function call to convert array to xml
		
		xmlGENrator($xml_user_info,$data);
		//return $data;
		//saving generated xml file
		//$xml_file = $xml_user_info->asXML('users.xml');
		$xml_file = $xml_user_info->asXML();
		//success and error message based on xml creation
		return trim($xml_file);
	}
	//return $tb;
}
//fetch record function end here
$server->register(
// method name:
'GETSchemeDetailsData',
// parameter list:
array('scheme_code'=>'xsd:string','state_code'=>'xsd:integer','date'=>'xsd:date'),
// return value(s):
//array('return'=>'xsd:Array'),
array('return'=>'xsd:string'),
// namespace:
$namespace,
// soapaction: (use default)
false,
// style: rpc or document
'rpc',
// use: encoded or literal
'encoded',
// description: documentation for the method
'Fetch Record fro the database');
//############################################################################




// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
$server->service($HTTP_RAW_POST_DATA);
exit();
?>