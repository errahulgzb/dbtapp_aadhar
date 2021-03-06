<?PHP
class Application_Form_BeneficiaryRecord extends Zend_Form{
	public function init(){
		// Set the method for the display form to POST
	}
	public function addform(){
		// Set the method for the display form to POST
		$this->setMethod('post');
		
		$this->addElement('text', 'name', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Name',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Name field is allowed only character input.')))
                 ),
        ));
		$this->addElement('text', 'email_id', array( 			
            //'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'placeholder' => 'Email-ID',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
		   /*
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Email can\'t be empty'
                    )
                )),*/
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z]{2,4}$/', 'messages'=>array(
'regexNotMatch'=>'Please enter valid Email-ID.')))
                 ),
        ));
		$this->addElement('text', 'dob', array(			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'readonly'  => readonly,
			'placeholder' => 'dd-mm-yyyy',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
          'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'DOB can\'t be empty'
                    )
                )),
             array('Regex',
                        false,
                          array('/^[0-9-]*$/', 'messages'=>array(
'regexNotMatch'=>'Please enter valid date format.')))
                 ),
        ));
		
		
		
		
	//Below is use for the gender	
		$gender = new Zend_Form_Element_Select('gender',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Select gender==',
			'M'    => 'Male',
			'F'    => 'Female',
			'T'    => 'Transgender',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$required->setMessage('Gender can\'t be  empty.');
		$gender->addValidators(array($required));
		$this->addElement($gender);
		
		
		
	//Below is use for the gender
		$this->addElement('text', 'aadhar_num', array( 			
        'required'   => true,
		'class'   => 'form-control',
        'filters'    => array('StringTrim'),
		'maxlength'  => '12',
		'placeholder' => 'Aadhaar Number',
		'decorators'=>Array(
		'ViewHelper','Errors'
		),
        'validators' => array(
            array('Regex',
                false,
                array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Aadhaar Number field is allowed only numeric input.')))
            ),
        ));
		if(base64_decode($_GET['scm_type']) == 1 || base64_decode($_GET['scm_type']) == 3)
		{
		$this->addElement('text', 'bank_account', array(
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Bank Account',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
          'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Account Number can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array(
'regexNotMatch'=>'Account field is allowed only numeric input.')))
                 ),
        ));
		}
		else
		{
			$this->addElement('text', 'bank_account', array(
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Bank Account',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Bank Account field is allowed only numeric input.')))
				 ),
		));
		}
if(base64_decode($_GET['scm_type']) == 1 || base64_decode($_GET['scm_type']) == 3)
		{
	//this fields are used to take input of ifsc code	
	$this->addElement('text', 'ifsc', array(
			'required'   => true,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'IFSC',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[a-zA-Z0-9]*$/', 'messages'=>array('regexNotMatch'=>'IFSC field is allowed only numeric and character input.')))
				 ),
		));
	}else{

$this->addElement('text', 'ifsc', array(
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'IFSC',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[a-zA-Z0-9]*$/', 'messages'=>array('regexNotMatch'=>'IFSC field is allowed only numeric and character input.')))
				 ),
		));
}
	//above fields are used to take input of ifsc code	
		
		$aadhar_seeded = new Zend_Form_Element_Select('aadhar_seeded',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            'N' => '==Select Option==',
			//'Y' => 'Yes',
			'N' => 'No',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($aadhar_seeded);
		
		$this->addElement('text', 'mobile_num', array(
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'placeholder' => 'Mobile Number',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Mobile Number field is allowed only numeric input.')))
                 ),
        ));
		$this->addElement('text', 'scheme_specific_unique_num', array(
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Scheme Specific Unique Number',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9]*$/', 'messages'=>array('regexNotMatch'=>'Scheme specific unique number field is allowed only numeric input.')))
                 ),
        ));
		
		$this->addElement('text', 'scheme_specific_family_num', array(
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Scheme Specific Family Number',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                    false,
                      array('/^[a-zA-Z0-9]*$/', 'messages'=>array('regexNotMatch'=>'Scheme specific family number field is allowed only numeric input.')))
                 ),
        ));
		
		
		$state = new Zend_Form_Element_Select('state_name',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Select State==',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty();
		$required->setType($required->getType() | Zend_Validate_NotEmpty::ZERO);
		$required->setMessage('State can\'t be  empty.');
		$state->addValidators(array($required));
		$stateget = new Application_Model_DbtState;
		$role=new Zend_Session_Namespace('role');
		$state_code=new Zend_Session_Namespace('state_code');
		if($role->role==12){
				$stateget = $stateget->statesgetbystatecode($state_code->state_code);
				foreach($stateget as $statek => $stateval){
			$state->addMultiOption($stateval['state_code'], $stateval['state_name']);
			$state->setValue($stateval['state_code']);
			    $stateval = $stateval['state_code'];
		}
				
		}else{
				$stateget = $stateget->statesget();
				foreach($stateget as $statek => $stateval){
			$state->addMultiOption($stateval['state_code'], $stateval['state_name']);
			
		}
		}
		
		$this->addElement($state);
		
		$district = new Zend_Form_Element_Select('district_name',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0' => '==Select District==',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
			$stategets = new Application_Model_DbtState;
			if($role->role==12){
		$state_district = $stategets->statewisedistrict($state_code->state_code);
			$state_district_current_id=null;
			foreach($state_district as $state_districts){
				    $statename = $state_districts['district'];
					$district->addMultiOption($state_districts['distcode'],$statename);
					$state_district_current_id=$state_districts['distcode'];
			}
			}
		$required = new Zend_Validate_NotEmpty();
		$required->setType($required->getType() | Zend_Validate_NotEmpty::ZERO);
		$required->setMessage('District can\'t be  empty.');
		$district->addValidators(array($required));
		$this->addElement($district);
		
		
		$block = new Zend_Form_Element_Select('block_name',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0' => '==Select Block==',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		// $required = new Zend_Validate_NotEmpty();
		// $required->setType($required->getType() | Zend_Validate_NotEmpty::ZERO);
		// $required->setMessage('Block can\'t be  empty.');
		// $block->addValidators(array($required));
		$this->addElement($block);
		
		
		$village = new Zend_Form_Element_Select('village_name',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0' => '==Select Village==',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		// $required = new Zend_Validate_NotEmpty();
		// $required->setType($required->getType() | Zend_Validate_NotEmpty::ZERO);
		// $required->setMessage('Village can\'t be  empty.');
		// $village->addValidators(array($required));
		$this->addElement($village);
		//Drop down end here for the listing
		
		
		$this->addElement('textarea', 'home_address', array(
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'rows' => '2',
			'cols' => '15',
			'maxlength'  => '100',
			'placeholder' => 'Home Address',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Home Address can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9~`!#$^*_:?()@., \"\'\- \n\r ]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Home Address field is allowed only specific input.')))
                 ),
        ));
		
		
		$this->addElement('text', 'pincode', array(
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '6',
			'placeholder' => 'Postal Code',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Postal Code can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array(
'regexNotMatch'=>'Postal Code field is allowed only numeric input.')))
                 ),
        ));
		
		
$this->addElement('text', 'ration_card_num', array(
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Ration card number',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Ration card number field is allowed only numeric input.')))
				 ),
		));
		
		
				$this->addElement('text', 'tin_family_id', array(
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'TIN Family number',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Tin Family number field is allowed only numeric input.')))
				 ),
		));
		
		
		
		$aa=$this->addElement('text', 'scheme_id', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme id '.CANTEMPTY
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Scheme id '.NUMERICVALIDATION)))
                 ),
        ));
	//$aa->setValue('4');
//$form->setDefaults(array('scheme_id' => '354'));


// Add new field in beneficiary 

$this->addElement('text', 'beneficiary_type', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Beneficiary Type',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Beneficiary Type can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Beneficiary Type field is allowed only character input.')))
                 ),
        ));

$this->addElement('text', 'agency_code', array(
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Agency code',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			'validators' => array(
					array('Regex',
						false,
						  array('/^[a-zA-Z0-9]*$/', 'messages'=>array('regexNotMatch'=>'IFSC field is allowed only numeric and character input.')))
				 ),
		));



$this->addElement('text', 'agency_name', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Agency name',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Agency name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Agency name field is allowed only character input.')))
                 ),
        ));

$this->addElement('text', 'beneficiary_regional_lang', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Regional Language',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Regional Language can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Regional Language field is allowed only character input.')))
                 ),
        ));

		$beneficiary_title = new Zend_Form_Element_Select('beneficiary_title',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '' => '==Select Option==',
			'Mr' => 'Mr.',
			'Ms' => 'Ms.',
			'Mrs' => 'Mrs.'
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($beneficiary_title);

$category_name = new Zend_Form_Element_Select('category_name',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '' => '==Select Option==',
			'General' => 'General',
			'OBC' => 'OBC',
			'SC' => 'SC',
			'ST' => 'ST',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($category_name);

$relation_indicate = new Zend_Form_Element_Select('relation_indicate',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '' => '==Select Option==',
			'F' => 'Father',
			'M' => 'Mother',
			'H' => 'Husband',
			'N' => 'Other',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($relation_indicate);

$checkaadhar = new Zend_Form_Element_Select('checkaadhar',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            'Y' => 'Yes',
			'N' => 'No',
			
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($checkaadhar);


$this->addElement('text', 'relation_name', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Relation name',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Relation name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Relation name field is allowed only character input.')))
                 ),
        ));

$this->addElement('text', 'bank_name', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Bank name',
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Bank name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Bank name field is allowed only character input.')))
                 ),
        ));


$religion = new Zend_Form_Element_Select('religion',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '' => '==Select Option==',
			'Hindu' => 'Hindu',
			'Muslim' => 'Muslim',
			'Sikhism' => 'Sikhism',
			'Buddhism' => 'Buddhism',
			'Jainism' => 'Jainism',
			'Other' => 'Other',
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$this->addElement($religion);

$year_beneficiary = new Zend_Form_Element_Select('year_beneficiary',array(  
            'label'        => '',
		    'required'   => false,
            'class'   => 'form-control',
            'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
			$startyear=1950;
			$currentyear=date('Y');
			for($i=$startyear;$i<=$currentyear;$i++){
			$year_beneficiary->addMultiOption($i,$i);
			}
		$this->addElement($year_beneficiary);

// end of new field add in form 

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


        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

		
	}

}
