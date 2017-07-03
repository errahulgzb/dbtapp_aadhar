<?PHP


class Application_Form_Scorecard extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
		//==================
        $this->setMethod('post');
		

		//this field is use for the add states into the form as multioption
        $state_collect = new Zend_Form_Element_Select('select_scheme',array(       
            'label'        => '',
		'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme--',          
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
		
	   $ministry_owner_obj = new Application_Model_Ministryowner;
        $form1_results = $ministry_owner_obj->getPhaseOneData($ministryid=null);
        foreach($form1_results as $key => $value){
            $name = $value['scheme_name'];  
            $state_collect->addMultiOption($value['scheme_name'], $name);        
        }
        $this->addElement($state_collect);


		     $this->addElement('text', 'beneficiary_data_available_with_ministry', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Beneficiary data available with Ministry field can\'t be empty'
                    )
                ))
			),          
		  ));
		
		//==================
        $digitized = new Zend_Form_Element_Select('digitized_database_for_beneficiaries',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '1'    => 'Yes',
            '2'    => 'No',                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
		 $requiredtype = new Zend_Validate_NotEmpty ();
		 $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$digitized->addValidators (array($requiredtype));
        $this->addElement($digitized);
		
		//==================
		$mis_portal = new Zend_Form_Element_Select('mis_portal_of_beneficiary_database',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '1'    => 'Yes',
            '2'    => 'No',                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
		 $requiredtype = new Zend_Validate_NotEmpty ();
		 $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$mis_portal->addValidators (array($requiredtype));
        $this->addElement($mis_portal);
		
		//==================
        $this->setMethod('post');		
		     $this->addElement('text', 'aadhaar_seeding_in_bd', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Aadhaar Seeding in BD field can\'t be empty'
                    )
                ))
			),          
		  ));
		  
		  //==================
        $this->setMethod('post');		
		     $this->addElement('text', 'bank_account_number_in_bd', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Bank Account Number in BD field can\'t be empty'
                    )
                ))
			),          
		  ));
		  
		  //==================
        $this->setMethod('post');		
		     $this->addElement('text', 'mobile_number_in_bd', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Mobile Number in BD / Integration with DBT Portal field can\'t be empty'
                    )
                ))
			),          
		  ));
		  
		  //==================
        $this->setMethod('post');		
		     $this->addElement('text', 'aadhaar_seeding_in_bank_ac', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Aadhaar seeding in Bank A/c field can\'t be empty'
                    )
                ))
			),          
		  ));
		  
		//==================
        $payment = new Zend_Form_Element_Select('payment_through_apb',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '1'    => 'Yes',
            '2'    => 'Others',                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
		 $requiredtype = new Zend_Validate_NotEmpty ();
		 $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$payment->addValidators (array($requiredtype));
        $this->addElement($payment);
		
		//==================
        $pfms = new Zend_Form_Element_Select('integrated_with_pfms',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '1'    => 'Yes',
            '2'    => 'No',                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
		 $requiredtype = new Zend_Validate_NotEmpty ();
		 $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$pfms->addValidators (array($requiredtype));
        $this->addElement($pfms);
		
	/*******************end***************************/
		
		
			// Add a captcha
        $this->addElement('text', 'vercode', array(
            //'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
			'class'   => 'form-control captchain',
			'placeholder' => 'Captcha',
				 'autocomplete' => 'off',
				'decorators'=>Array(
			'ViewHelper',
			'Errors',
		   ),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
                )
        ));
		

		
		
		
	}

}
