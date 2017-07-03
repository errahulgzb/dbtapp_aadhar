<?php  
// error_reporting(1);
// ini_set("error_reporting","E_ALL");
// ini_set("error_display",true);
//##################################################### Params #################
// $scheme_code = "EMPTY";
// $state_code = 2;
// $date = "2017-01-27";


$scheme_code = "CRT2011";
$state_code = 10;
$date = "2017-01-06";
//##################################################### Params #################

			$options = array(
                'soap_version'=>SOAP_1_1,
                'exceptions'=>true,
                'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE
                ); 
  $client = new SoapClient("http://192.168.100.132/dbtappstate/application/dbtappservice/wsdlgenerator.php?wsdl",$options);
  try{
	  //$response = $client->getDailyWiseReport("HUIBG",20,date("Y-m-d"));
	  //$response = $client->ValidatorFactor("HUIBG",20,"2017-12-13");
	  $response = $client->GETSchemeDetailsData(trim($scheme_code),trim($state_code),trim($date));
		
		// $xml = new SimpleXMLElement($response) or die('cant connect');
		// $dataTobe = (array)$xml;
		// echo $dataTobe['account']->village_name;
		// print '<pre>';
		// print_r($dataTobe['account']);
		// exit;
		
		
		echo "<pre>"; print_r($response);exit;
		echo $response;exit;
  } catch(SoapFault $client){
		//$data = $client->getFunction();
		
		echo '<pre dir="ltr">';
		print_r($client->getMessage());
		echo '</pre>';
	}
	
  
?>