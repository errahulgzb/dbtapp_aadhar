<?php 
class AadhaarStatus{ 
public $aadhaarNumber; 
public $mobileNumber;
public $requestNumber;
public $requestedDateTimeStamp;
//public $xmlParams;
	public function AadhaarStatus($aadhaarNumber = null,$mobileNumber = null,$requestNumber = null,$requestedDateTimeStamp = null){
		$this->aadhaarNumber = $aadhaarNumber;
		$this->mobileNumber = $mobileNumber;
		$this->requestNumber = $requestNumber;
		$this->requestedDateTimeStamp = $requestedDateTimeStamp;
	}
}