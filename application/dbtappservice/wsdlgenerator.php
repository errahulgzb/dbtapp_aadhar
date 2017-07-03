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

//generate a array for beneficiary detail and transaction detail 
function getArray($arraydata = null,$transaction_id=null){
	$datatoBind = array();
		$multiarray = array();
		$i = 1;
		//$tr_id = "";
		//return count($arraydata);
		
		foreach($arraydata as $val){
				$payment_date=date('d-m-Y',strtotime($val['approval_transaction_date']));
				if($val['village_code']=='0'){
					$val['village_code']='';
					}if($val['block_code']=='0'){
						$val['block_code']='';
						}
			$multiarray[$i] = array(
			"location" => array(
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
			),
		"details" => array(
				"benefit_transfer_mode" => $val['fund_transfer'],
				"amount" => $val['amount'],
				"no_of_beneficiries_transactions" => $val['no_of_beneficiries'],
				"no_of_aadhaarseeded_beneficiries_transactions" => $val['AadharSeededBeneficiaries'],
				"transaction_date" => $payment_date,
				"payment_date" => $payment_date,
				"requestid" => $transaction_id,
			),
		);
			//$tr_id .= "".$val['ref_tid'].",";
			$i++;
		}
		return $multiarray;
	
}


//to add the method for fetch record corresponding to the scheme code in scheme
function GETSCHEMETB($scheme_code = null){
	Global $db;
		
	$getData = $db->query("SELECT CONCAT('dbt_',scheme_table) AS beneftb, id AS scheme_id, scheme_type as scm FROM dbt_scheme WHERE scheme_codification='".trim($scheme_code)."'",PDO::FETCH_ASSOC);
		
	$row = array();
	foreach($getData as $rowdata){
		$row[0] =$rowdata['beneftb'];
		$row[1] =$rowdata['scheme_id'];
		$row[2] =$rowdata['scm'];
//print_r($row);exit;
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
            $key = 'transaction-details';

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
	}else if(!intval($state_code) && $state_code != "00"){
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
array('scheme_code'=>'xsd:string','state_code'=>'xsd:string','date'=>'xsd:date'),
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
	$scm_type = $return_value[2];
	//RETURN $scm_type."AAA";
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
		$dataarr2=$dataarrarray=$arraydata2=$arraydata1=$dataarr1=array();
		
$existcountquery="SELECT count(TR.id) AS datacount FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."'";
			
			if($state_code!=0){
				$existcountquery.=" AND BENE.state_code=$state_code";
				}
				
			//return $existcountquery;
			$existcountarrays=array();
			$existcountarray = $db->query($existcountquery,PDO::FETCH_ASSOC);
			foreach($existcountarray as $existcountarrayss){
						$existcountarrays=array("datacount"=>$existcountarrayss['datacount']);
				}
	if(array_filter($existcountarrays)>0 && !empty($existcountarrays)){
$countquery="SELECT count(TR.id) AS datacount FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1'";
			
			if($state_code!=0){
				$countquery.=" AND BENE.state_code=$state_code";
				}
				
			//return $countquery;
			$countarrayfulls=array();
			$countarrayfull = $db->query($countquery,PDO::FETCH_ASSOC);
			foreach($countarrayfull as $countarrayfulls){
						$countarrayfulls=array("datacount"=>$countarrayfulls['datacount']);
				}

// check data can we have already sent to you or not ************************
			if($countarrayfulls['datacount']>0 && !empty($countarrayfulls))
			{
		 $query="Select count(*) AS total_count,sum(IF(district_code!='0',1,0)) AS countdistrict,sum(IF(block_code!='0',1,0)) AS countblock,sum(IF(village_code!='0',1,0)) AS countvill from $tb";
		 //return $query;
		$countarr=$db->query("Select count(*) AS total_count,sum(IF(district_code!='0',1,0)) AS countdistrict,sum(IF(block_code!='0',1,0)) AS countblock,sum(IF(village_code!='0',1,0)) AS countvill from $tb",PDO::FETCH_ASSOC);
		$countarray=array();
		foreach($countarr as $countval){
				$countarray=array('totalcount'=>$countval['total_count'],
									'countdistrict'=>$countval['countdistrict'],
									'countblock'=>$countval['countblock'],
									'countvill'=>$countval['countvill']
								 );
							}
		

		// creating transaction id here now
		$transaction_id = "DBTTXN".strtotime(date("Y-m-d H:i:s")).$state_code;
//******************************* village level data start now ************************
			if($countarray['totalcount']==$countarray['countdistrict'] && $countarray['countdistrict']==$countarray['countblock'] && $countarray['countblock']==$countarray['countvill']){
			$query='';
			$query="SELECT BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,SUM(TR.amount) AS amount,IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date) AS approval_transaction_date,GROUP_CONCAT(TR.id) AS ref_tid,count(IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1)) AS AadharSeededBeneficiaries,IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer) AS fund_transfer,count(IF(TR.id!='',1,0)) AS no_of_beneficiries FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1' AND BENE.village_code!=0";
			
			if($state_code!=0){
				$query.=" AND BENE.state_code=$state_code";
				}
				$query.=" group by IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer),BENE.village_code";
				//return $query;
			$dataarrfull = $db->query($query,PDO::FETCH_ASSOC);
			$dataarr= getArray($dataarrfull,$transaction_id);
				//return $dataarr;
			// update status of transaction table start now ************
				$dataarrfull = $db->query($query,PDO::FETCH_ASSOC);
				foreach($dataarrfull as $dataarrfulls){
								$query1 = "UPDATE ".$tb."_transaction SET service_status='1' WHERE id IN(".$dataarrfulls['ref_tid'].")";
				$db->query($query1);
								}
			// update status of transaction table end now ************
			}
//********************** block level data start now ******************************** 
			if($countarray['countblock']>$countarray['countvill'] || $countarray['countblock']==$countarray['countvill'])
				{
				if($countarray['countvill']!=0){
					$query='';
					$query="SELECT BENE.id AS ben_id,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,SUM(TR.amount) AS amount,IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date) AS approval_transaction_date,GROUP_CONCAT(TR.id) AS ref_tid,count(IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1)) AS AadharSeededBeneficiaries,IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer) AS fund_transfer,count(IF(TR.id!='',1,0)) AS no_of_beneficiries FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1' AND BENE.village_code!=0";
						if($state_code!=0){
							$query.=" AND BENE.state_code=$state_code";
								}
							$query.=" group by IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer),BENE.village_code";
						//return $query;
					$idarr1 = $db->query($query,PDO::FETCH_ASSOC);
					
						// creating array for beneficiary id
						$beneidarr=array();
						$i=1;
						foreach($idarr1 as $idarr1s){
								$beneidarr[$i]=array("id"=>$idarr1s['ben_id']);
								$i++;
							}
						
					// generating string of beneficiary id with comma seprated
					foreach($beneidarr as $beneidarrs){
								$idstringbene .=$beneidarrs['id'].",";
								}
						$idstringbenes=rtrim($idstringbene,',');
						

		
				// generate array here
				$idarr1 = $db->query($query,PDO::FETCH_ASSOC);
				
				$arraydata1= getArray($idarr1,$transaction_id);
					}
				// update status of transaction table start now ************
				$idarr1 = $db->query($query,PDO::FETCH_ASSOC);
				foreach($idarr1 as $idarr1s){
				$query4 = "UPDATE ".$tb."_transaction SET service_status='1' WHERE id IN(".$idarr1s['ref_tid'].")";
				$db->query($query4);
				}
			// update status of transaction table end now ************

						if($idstringbenes!='' && $idstringbenes!=null){
						$query='';
						$query="SELECT BENE.id AS ben_id,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,SUM(TR.amount) AS amount,IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date) AS approval_transaction_date,GROUP_CONCAT(TR.id) AS ref_tid,count(IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1)) AS AadharSeededBeneficiaries,IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer) AS fund_transfer,count(IF(TR.id!='',1,0)) AS no_of_beneficiries FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1' AND BENE.id NOT IN($idstringbenes) AND BENE.block_code!=0";
						if($state_code!=0){
							$query.=" AND BENE.state_code=$state_code";
								}
							$query.=" group by IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer),BENE.block_code";
						}else{
							$query='';
							$query="SELECT BENE.id AS ben_id,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,SUM(TR.amount) AS amount,IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date) AS approval_transaction_date,GROUP_CONCAT(TR.id) AS ref_tid,count(IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1)) AS AadharSeededBeneficiaries,IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer) AS fund_transfer,count(IF(TR.id!='',1,0)) AS no_of_beneficiries FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1' AND BENE.block_code!=0";
						if($state_code!=0){
							$query.=" AND BENE.state_code=$state_code";
								}
							$query.=" group by IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer),BENE.block_code";
						
						}
			//return $query;
					$idarr2 = $db->query($query,PDO::FETCH_ASSOC);
					$arraydata2= getArray($idarr2,$transaction_id);
					
					$dataarr1=array_merge($arraydata1,$arraydata2);
//return $dataarr1;
					

				// update status of transaction table start now ************
				$idarr2 = $db->query($query,PDO::FETCH_ASSOC);
				foreach($idarr2 as $idarr2s){
						$query1 = "UPDATE ".$tb."_transaction SET service_status='1' WHERE id IN(".$idarr2s['ref_tid'].")";
						$db->query($query1);
								}
				// update status of transaction table end now ************
				
				}
//************************ district level data start now**********************************
				if($countarray['countdistrict']>$countarray['countblock']){
							$query='';
							$query="SELECT BENE.id AS ben_id,BENE.village_code,BENE.village_name,BENE.panchayat_code,BENE.panchayat_name,BENE.block_code,BENE.block_name,BENE.district_code,BENE.district_name,BENE.state_code,BENE.state_name,SUM(TR.amount) AS amount,IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date) AS approval_transaction_date,GROUP_CONCAT(TR.id) AS ref_tid,count(IF(BENE.aadhar_seeded='' OR BENE.aadhar_seeded='N' OR BENE.aadhar_seeded='n',0,1)) AS AadharSeededBeneficiaries,IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer) AS fund_transfer,count(IF(TR.id!='',1,0)) AS no_of_beneficiries FROM ".$tb." AS BENE INNER JOIN ".$tb."_transaction AS TR ON BENE.uniq_user_id = TR.uniq_user_id WHERE IF('$scm_type'='2',TR.transaction_date,TR.approval_transaction_date)='".$date."' AND TR.service_status='0' AND TR.transaction_status='1' AND BENE.district_code!=0 AND BENE.block_code=0 AND BENE.village_code=0";
						if($state_code!=0){
							$query.=" AND BENE.state_code=$state_code";
								}
							$query.=" group by IF('$scm_type'='2',TR.transfer_by,TR.fund_transfer),BENE.district_code";
					//return $query;

					$idarr3 = $db->query($query,PDO::FETCH_ASSOC);
					
					$dataarr2= getArray($idarr3,$transaction_id);

					
				// update status of transaction table start now ************
				$idarr3 = $db->query($query,PDO::FETCH_ASSOC);
				foreach($idarr3 as $idarr3s){
				$query1 = "UPDATE ".$tb."_transaction SET service_status='1' WHERE id IN(".$idarr3s['ref_tid'].")";
				$db->query($query1);
				}
			// update status of transaction table end now ************
				
					}
					if(!empty($dataarr1) || !empty($dataarr2)){
				$dataarr=array_merge($dataarr1,$dataarr2);
					}
//return $dataarr;
//************** data insert in service manage table for record start now*******************
			$scheme_id = $return_value[1];
			if(!empty($dataarr)){
			$query = "INSERT INTO dbt_service_manage(request_id,state_code,transaction_date,scheme_code,scheme_id)VALUES('$transaction_id',$state_code,'$date','$scheme_code',$scheme_id)";
			//return $query;
			$db->query($query);
			}
		
//************** data insert in service manage table for record end now*******************
		
		//return $datatoBind;
		if(count(array_filter($dataarr) > 0) && !empty($dataarr)){
			$data = array_filter($dataarr);
		}
		
		else{
			$data = array("EMPTY"=>"No Record Found!");
		}
		}else{
			$data = array("EMPTY"=>"we have already sent to you data!");
			}
		}else{
			$data = array("EMPTY"=>"No Record Found!");
			}
		//creating object of SimpleXMLElement
		$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><transaction-master/>");
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
array('scheme_code'=>'xsd:string','state_code'=>'xsd:string','date'=>'xsd:date'),
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