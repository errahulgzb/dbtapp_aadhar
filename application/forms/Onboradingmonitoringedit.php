<?PHP


class Application_Form_Onboradingmonitoringedit extends Zend_Form {

public function init(){
	//echo "Aaaa";exit;
	// Set the method for the display form to POST
}


public function addform(){
	
	    $this->setMethod('post');
		/*******Add Scheme Name***********/
	// echo base64_decode($_REQUEST['id']); exit;
	$onboardingmonitoringedit = new Application_Model_OnboardingMonitoring;
	$data = $onboardingmonitoringedit->getschmemename(base64_decode($_REQUEST['id']));
	$formtwodata = $onboardingmonitoringedit->getformdata(base64_decode($_REQUEST['id']));
	
	$pdsinkind = array(1026);
	if(in_array(base64_decode($_REQUEST['id']), $pdsinkind)){
		$parameterdata = $onboardingmonitoringedit->getparameter(4);
	} else if($data[0]['benefit_type'] == 3){
		$parameterdata = $onboardingmonitoringedit->getparameter(1);
	} else {
		$parameterdata = $onboardingmonitoringedit->getparameter($data[0]['benefit_type']);
	}
	// print '<pre>';
	// print_r($parameterdata);
	// exit;
	
	
	//Benefit Type
	if($data[0]['benefit_type'] == 1)
	{
		$benefit_type = 'Cash';
	}
	else if($data[0]['benefit_type'] == 2)
	{
		$benefit_type = 'In Kind';
	}
	else if($data[0]['benefit_type'] == 3)
	{
		$benefit_type = 'Other Transfers';
	}
	// if(in_array(base64_decode($_REQUEST['id']), $pdsinkind)){
		// $benefit_type = 'PDS In-Kind';
	// }
	
	//Form Two Available or Not
	if($formtwodata[0]['save'] == 0)
	{
		$savestatus = 'No';
		$keyval = 0;
	}
	if($formtwodata[0]['save'] == 1)
	{
		$savestatus = 'Yes';
		$keyval = 1;
	}
	// echo $formtwodata[0]['save']; exit;
		//Scheme Name
	    $this->addElement('text', 'name_of_scheme', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			'value' => $data[0]['scheme_name'],
			'decorators'=>Array(
				'ViewHelper','Errors'
			  ),  
			'attribs'    => array('disabled' => 'disabled') 				
		));	
		
		//Total No. of Beneficiaries
		// $this->addElement('text', 'total_number_of_beneficiaries', array( 			
            // 'required'   => true,
            // 'class'   => 'form-control',
            // 'filters'    => array('StringTrim'),
			// 'maxlength'  => '20',
			// 'decorators'=>Array(
			// 'ViewHelper','Errors'
		   // ),
            // 'validators' => array(
			    // array('notEmpty', true, array(
                    // 'messages' => array(
                        // 'isEmpty'   =>  'Total Number of Beneficiaries can\'t be empty!'
                    // )
                // )) ,
                    // array('Regex',
                        // false,
                          // array('/^[0-9]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Total Number of Beneficiaries field has special characters.Please remove them and try again.')))
				// ),
		// )); 
		//Benefit Type
		$this->addElement('text', 'benefit_type', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'value' => $benefit_type,
			'decorators'=>Array(
				'ViewHelper','Errors'
			  ),  
			'attribs'    => array('disabled' => 'disabled') 				
		));
		
		// print '<pre>';
		// print_r($parameterdata);
		// exit;
		$i = 0;
		$count = count($parameterdata);
		while($i < $count) {
		  if ($parameterdata[$i]['parameter'] != ''){
			if ($parameterdata[$i]['parameter'] == 'parameter1' || $parameterdata[$i]['parameter'] == 'parameter12' || $parameterdata[$i]['parameter'] == 'parameter21'){
				
					$objdigitization = new Application_Model_OnboardingMonitoring; 
					$showbeneficiarydigitization = $objdigitization->getparametervalue($parameterdata[$i]['id']); 			
					
					// print '<pre>';
					// print_r($parameterdata);
					// exit;
					
					if ($parameterdata[$i]['parameter'] == 'parameter1' && $keyval == 0){
						$keyvalue = $showbeneficiarydigitization[1]['id'];
					} else if ($parameterdata[$i]['parameter'] == 'parameter1' && $keyval == 1){
						$keyvalue = $showbeneficiarydigitization[0]['id'];
					} else if ($parameterdata[$i]['parameter'] == 'parameter12' && $keyval == 0){
						$keyvalue = $showbeneficiarydigitization[1]['id'];
					} else if ($parameterdata[$i]['parameter'] == 'parameter12' && $keyval == 1){
						$keyvalue = $showbeneficiarydigitization[0]['id'];
					} else if ($parameterdata[$i]['parameter'] == 'parameter21' && $keyval == 0){
						$keyvalue = $showbeneficiarydigitization[1]['id'];
					} else if ($parameterdata[$i]['parameter'] == 'parameter21' && $keyval == 1){
						$keyvalue = $showbeneficiarydigitization[0]['id'];
					} else {
						$keyvalue = $keyval;
					}
					
					// print '<pre>';
					// print_r($showbeneficiarydigitization);
					// exit;
					
					// foreach($showbeneficiarydigitization as $key => $value){
						// $name = $value['parameter_name'];			
						// $beneficiarydigitization->addMultiOption($value['id'], $name);
					// }
				
				/************ Add Whether Form II has been completed ******************/
				 $formtwocompleted = new Zend_Form_Element_Select($parameterdata[$i]['parameter'],array(  
							'label'        => '',
							'required'   => true,
							'class'   => 'form-control',
							//'registerInArrayValidator' => false,
							// 'attribs'    => array('disabled' => 'disabled'),
							'multiOptions' => array(
							$keyvalue => $savestatus
							),
							'decorators'=>Array(
								'ViewHelper','Errors'
							),
						
						 ));
						 
						$required = new Zend_Validate_NotEmpty ();
						$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER);
						$formtwocompleted->addValidators (array($required));
						
						// $objformtwocompleted = new Application_Model_OnboardingMonitoring; 
						// $formtwocompleteddetail = $objformtwocompleted->getparametervalue($parameterdata[$i]['id']); 			
						$formtwocompleted->setValue($formtwodata[0]['save']);
						
						$this->addElement($formtwocompleted);

				/****************** end **********************/
			} else if ($parameterdata[$i]['parameter'] == 'parameter11' || $parameterdata[$i]['parameter'] == 'parameter10' || $parameterdata[$i]['parameter'] == 'parameter4' || $parameterdata[$i]['parameter'] == 'parameter13'  || $parameterdata[$i]['parameter'] == 'parameter15' || $parameterdata[$i]['parameter'] == 'parameter18'  || $parameterdata[$i]['parameter'] == 'parameter21' || $parameterdata[$i]['parameter'] == 'parameter22' || $parameterdata[$i]['parameter'] == 'parameter24' || $parameterdata[$i]['parameter'] == 'parameter28' || $parameterdata[$i]['parameter'] == 'parameter31' || $parameterdata[$i]['parameter'] == 'parameter32' || $parameterdata[$i]['parameter'] == 'parameter33' || $parameterdata[$i]['parameter'] == 'parameter34' || $parameterdata[$i]['parameter'] == 'parameter35' || $parameterdata[$i]['parameter'] == 'parameter36'){
				/************ Add beneficiarydigitization ******************/
				 $beneficiarydigitization = new Zend_Form_Element_Select($parameterdata[$i]['parameter'],array(  
						'label'        => '',
						'required'   => true,
						'class'   => 'form-control',
						//'registerInArrayValidator' => false,
						'multiOptions' => array(
						''    => '--Select--',	  
					),
						'decorators'=>Array(
						'ViewHelper','Errors'
					   ),
					
					 ));
					 
					$required = new Zend_Validate_NotEmpty ();
					$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER);
					$beneficiarydigitization->addValidators (array($required));
					
					$objdigitization = new Application_Model_OnboardingMonitoring; 
					$showbeneficiarydigitization = $objdigitization->getparametervalue($parameterdata[$i]['id']); 			
					
					foreach($showbeneficiarydigitization as $key => $value){
						$name = $value['parameter_name'];			
						$beneficiarydigitization->addMultiOption($value['id'], $name);
					}
					$this->addElement($beneficiarydigitization);

				/****************** end **********************/ 
			} else {
				$this->addElement('text', $parameterdata[$i]['parameter'], array( 			
					'required'   => true,
					'class'   => 'form-control',
					'filters'    => array('StringTrim'),
					'maxlength'  => '20',
					'value'  => '0',
					'decorators'=>Array(
					'ViewHelper','Errors'
				   ),
					'validators' => array(
							array('Regex',
								false,
								  array('/^[0-9]{0,}$/i', 'messages'=>array('regexNotMatch'=> $parameterdata[$i]['parameter_name'].' field has special characters.Please remove them and try again.')))
						),
				));
			}
			// echo $parameterdata[$i]['parameter']." <br>";
		  }
			$i++;
		} 
		// echo 'hi'; exit;
		
		//Add Infrastructure Support
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_pfms', 
			array (
				'multiOptions' => array(
					'pfms' => 'PFMS'
				)
			)
		);
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_npci', 
			array (
				'multiOptions' => array(
					'npci' => 'NPCI'
				)
			)
		);
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_meity', 
			array (
				'multiOptions' => array(
					'meity' => 'MeitY'
				)
			)
		);
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_dfs', 
			array (
				'multiOptions' => array(
					'dfs' => 'DoFS'
				)
			)
		);
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_termdot', 
			array (
				'multiOptions' => array(
					'termdot' => 'TERM (DoT)'
				)
			)
		);
		$this->addElement ( 
			'multiCheckbox', 'infrastructure_support_dbtmission', 
			array (
				'multiOptions' => array(
					'dbtmission' => 'DBT Mission'
				)
			)
		);
		$this->addElement('text', 'pfms', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		$this->addElement('text', 'npci', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		$this->addElement('text', 'meity', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		$this->addElement('text', 'dfs', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		$this->addElement('text', 'termdot', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		$this->addElement('text', 'dbtmission', array( 			
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		));
		// Add Remarks
		$this->addElement('textarea', 'remarks', array( 			
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '1000',
			'rows'  => '5',
			'decorators'=>Array(
				'ViewHelper','Errors'
			  ),  			
		));
		
		
		
}


}