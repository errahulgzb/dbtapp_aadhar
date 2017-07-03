<?php 
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

$data = array('aadhaarNumber'=>'123456789012','mobileNumber'=>'1234567890','requestNumber'=>'ABCD000001','requestedDateTimeStamp'=>'2015-03-10 14:47:47.741');

$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding='utf-8'?><arg0/>");

$xml = $xml_user_info->addChild('soapenv:Envelope');
		
		xmlGENrator($xml,$data);
		$xml_file = $xml_user_info->asXML();
		echo trim($xml_file);die;
?>