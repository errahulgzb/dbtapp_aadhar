<?PHP


class Application_Form_Ministryschemeview extends Zend_Form {

public function init(){
	//echo "Aaaa";exit;
	// Set the method for the display form to POST
}

public function addform(){
		// Set the method for the display form to POST
        $this->setMethod('post');
		/*******Add Scheme Name***********/
		     $this->addElement('text', 'name_of_scheme', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'attribs'    => array('disabled' => 'disabled') 				
  ));		
			
			$this->addElement('hidden', 'ministry_id', array( 
							'label'      => '',
                            //'required'   => true,
                            'id' => 'ministry_id',
							'decorators'=>Array(
								'ViewHelper','Errors'
							   ),
							   'attribs'    => array('disabled' => 'disabled') 	
							   
				));				
			$this->addElement('hidden', 'phase_id', array( 
				'label'      => '',
				//'required'   => true,
				'id' => 'phaseid',
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),
		   ));				
		/***********end***************/
		
	/**********Add schme type *********/
	
		$radio = new Zend_Form_Element_Radio('type_of_scheme', array(
			'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            //'class'   => 'form-control',
			'multiOptions'=> array(
				"1" => "Central Sector",                 
				"2" => "Centrally Sponsored",
			),
        
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Please select type of scheme!'
                    )
                )) ,
            ),     
			'attribs'    => array('disabled' => 'disabled')			
		));
		$radio->setSeparator('  ');
		$this->addElement($radio);
		
		/************Fund Allocated for the Scheme**************/
		     $this->addElement('text', 'fund_allocation', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Fund Allocated field can\'t be empty!'
                    )
                )) ,
                    array('Regex',
                        false,
                          array('/^[0-9]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Fund Allocated field has special characters.Please remove them and try again.')))
				),
			'attribs'    => array('disabled' => 'disabled')
			  ));   
		/*************end Fund Allocated for the Scheme***************/
	
	
	
	/************Implementing Agency**************/
		     $this->addElement('text', 'implemeting_agency', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
				'attribs'    => array('disabled' => 'disabled')
			));   
		/*************end Implementing Agency***************/
		
		
		/************Target Beneficiary**************/
		     $this->addElement('text', 'target_beneficiary', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 'attribs'    => array('disabled' => 'disabled')
			));   
		/*************end Target Beneficiary***************/
		
		
		/************Total Number of Eligble Beneficiary**************/
		     $this->addElement('text', 'total_eligble_beneficiary', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
			
			));   
		/*************end Total Number of Eligble Beneficiary***************/
		
		
		/************Digitized Beneficiary Database**************/	
		
		$radio = new Zend_Form_Element_Radio('digitized_beneficiary_status', array(
			'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            //'class'   => 'form-control',
			'multiOptions'=> array(
				"1" => "Yes",                 
				"2" => "No",
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
		));
		$radio->setSeparator('  ');
		$this->addElement($radio);
		
		
		
		 $this->addElement('textarea', 'digitized_details_of_act', array( 
				//'required'   => true,
				'class'   => 'form-control',
				'filters'    => array('StringTrim'),
				'maxlength'  => '500',
				'placeholder' => 'If No, please provide details of action being taken for the same',
				
				'rows' => "5",
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),
				'attribs'    => array('disabled' => 'disabled')
			
			));   
		/*************end Digitized Beneficiary Database***************/
		
		
		/************MIS Portal in place of the Scheme**************/
		$radio = new Zend_Form_Element_Radio('mis_portal_status', array(
			'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            //'class'   => 'form-control',
			'multiOptions'=> array(
				"1" => "Yes",                 
				"2" => "No",
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
		));
		$radio->setRequired(true);
		$radio->setSeparator('  ');
		$this->addElement($radio);
		
		
		$this->addElement('textarea', 'details_of_actions_init', array( 			
				//'required'   => true,
				'label' => '',
				'class'   => 'form-control',
				'filters'    => array('StringTrim'),
				'maxlength'  => '500',
				'placeholder' => 'If No, please specify details of actions initiated',
				'rows' => "5",
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),
				'attribs'    => array('disabled' => 'disabled')
			
			));   
		
		/*************end MIS Portal in place of the Scheme***************/
		
		
/*****Status of Aadhar Seeding in beneficiary Database (BD) and aadhar linkage in accounts************/
		
	/*****Aadhar Seeding in BD************/
		$this->addElement('text', 'aadhar_seeding_bd', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')          
		));
	/******end Aadhar Seeding in BD***********/
		
		
		
	/*****Bank Account Number in BD************/
		$this->addElement('text', 'bank_account_bd', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'attribs'    => array('disabled' => 'disabled')        
		));
	/******end Bank Account Number in BD***********/
		
		
		
		
	/*****Mobile Number in BD************/
		$this->addElement('text', 'mobile_number_bd', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')        
		));
	/******end Mobile Number in BD***********/
		
		
		
		
	/*****Aadhar Linkage with Bank Account************/
		$this->addElement('text', 'aadhar_linkage_account', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')        
		));
	/******end Aadhar Linkage with Bank Account***********/
		
		
/******Status of Aadhar Seeding in beneficiary Database (BD) and aadhar linkage in accounts***********/
		
		
	/*****Brief Description of the Scheme************/
		$this->addElement('textarea', 'scheme_description', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '250',
			'rows'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')        
		));
	/******end Brief Description of the Scheme***********/
	
	
	/*****Type of Benefit************/
		$this->addElement('radio', 'type_of_benefit', array(
			'required'   => true,			
            'class'   => 'radio-inline',
            'filters'    => array('StringTrim'),
			'multiOptions'  => array(
			"1" => "Cash",
			"2" => "In Kind",
			"3" => "Other Transfers"
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),'attribs'    => array('disabled' => 'disabled')
		));
	/******end Type of Benefit***********/
	
	
	/*****Deails of Benefit************/
		$this->addElement('textarea', 'details_of_benefit', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'rows'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')       
		));
	/******end Deails of Benefit***********/
	
	
	
	/*****Description of Process flow(Special focus on level for example automation in fund disbursal)************/
		$this->addElement('textarea', 'process_flow_description', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '250',
			'rows'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')       
		));
	/******end Description of Process flow(Special focus on level for example automation in fund disbursal***********/
	
	
	
	/*****Payment linked to PFMS************/
		
		$radio = new Zend_Form_Element_Radio('pfms_payment', array(
			'required'   => true,
            'class'   => 'radio-inline',
            //'filters'    => array('StringTrim'),
			'multiOptions'  => array(
				"1" => "Yes",
				"2" => "No"
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
		));
		$radio->setSeparator('  ');
		$this->addElement($radio);
	/******end Payment linked to PFMS***********/
		
	/*****Mode of Payment (If Applicable)************/
        $mode_of_payment = new Zend_Form_Element_Select('mode_of_payment',array(  
            'label'        => '',
            'required'   => 'required',
            'registerInArrayValidator' => false,
            'class'   => 'form-control',
			"multiOptions" => array(
			"0" => "==Select Option==",
			"1" => "APBS",
			"2" => "AEPS",
			"3" => "NACH",
			"4" => "OTHERS"
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),'attribs'    => array('disabled' => 'disabled')
		 ));
        $this->addElement($mode_of_payment);
		
		/************end Mode of Payment (If Applicable)****************/
		
		
	/*****Description of Fund Disbursment Mechanism(From the consolidated Fund of India to the end Beneficiary)************/
		$this->addElement('textarea', 'fund_disburse_description', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'rows'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')        
		));
	/******end Description of Fund Disbursment Mechanism(From the consolidated Fund of India to the end Beneficiary)***********/
	
	
	//file upload in scheme
			// $this->addElement('text', 'uploadfile', array( 
                    // 'label'      => '',
                    // 'required'   => false,
					// 'id' => 'uploadfile',
            // ));
			$this->addElement('text', 'filename', array(
			'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'attribs'    => array('disabled' => 'disabled')        
		));
	//file upload end here
		
		
		
	}
}
