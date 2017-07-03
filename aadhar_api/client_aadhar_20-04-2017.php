<?php 

$wsdl = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:aad="http://aadhaar.npci.org/"><soapenv:Header/><soapenv:Body>
      <aad:getAadhaarStatus>
        <arg0>
           <aadhaarNumber>123456789012</aadhaarNumber>
           <mobileNumber>1234567890</mobileNumber>
           <requestNumber>ABCD000001</requestNumber>
           <requestedDateTimeStamp>2015-03-10 14:47:47.741</requestedDateTimeStamp>
         </arg0>
      </aad:getAadhaarStatus>
   </soapenv:Body>
</soapenv:Envelope>';
$client = new SoapClient($wsdl, array(  'soap_version' => SOAP_1_1,
                                        'trace' => true,
                                        )); 
try {
$params = array(
         //Your parameters here
          );    
$res = $client->__soapCall( 'SoapMethod', $params );
return $res;
} catch (SoapFault $e) {
echo "Error: {$e}";
}

//for debugging what the outgoing xml looks like
$client->__getLastRequest();

?>