<?php
//https://nach.npci.org.in/CMAadhaar/AadhaarStatusService      103.14.161.34
// https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl
  // $topn 		= (int) '123456789012';
  // $mobile		= (int) '1234567890';
  // $request		= 'ABCD000001';
  // $requestdate	='2015-03-10 14:47:47.741';
  $client = new SoapClient("https://nach.npci.org.in/CMAadhaar/AadhaarStatusService");
  $result = $client->TopGoalScorers(array('aadhaarNumber'=>'123456789012', 'mobileNumber'=>'1234567890', 'requestNumber'=>'ABCD000001', 'requestedDateTimeStamp'=>'2015-03-10 14:47:47.741'));
  // Note that $array contains the result of the traversed object structure
  $array = $result->TopGoalScorersResult->tTopGoalScorer;
print_r($array);
// try{

// }catch(Exception $e){

// $data = $client->getFunction();
		// var_dump($data);
	
// } 

  


?>

  