<?php
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";die;

//echo $_SERVER['REQUEST_URI'];die;
//https://nach.npci.org.in/CMAadhaar/AadhaarStatusService      103.14.161.34
// https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl 
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
//ini_set("soap.wsdl_cache_enabled", "0")
$url ="https://103.14.161.34/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl";

$params = array("arg0" => array('aadhaarNumber'=>'123456789012','mobileNumber'=>'1234567890','requestNumber'=>'ABCD000001','requestedDateTimeStamp'=>'2015-03-10 14:47:47.741'));

       $headers = array(
                    "Content-type: text/xml;charset=\"utf-8\"",
                    "Accept: text/xml",
                    "Cache-Control: no-cache",
                    "Pragma: no-cache",
                    "Content-length: ".strlen($params),
                );

        // PHP cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//echo $xml_post_string;die;
//print_r($headers);die;
        $response = curl_exec($ch);
//die("excute12");
if($errno = curl_errno($ch)) {
    $error_message = curl_strerror($errno);
    echo "cURL error ({$errno}):\n {$error_message}";
}
		var_dump($response);die;
        curl_close($ch);
?>