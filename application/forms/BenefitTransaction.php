<?php
class Application_Form_BenefitTransaction extends Zend_Form{
	public function init(){
		// Set the method for the display form to POST
	}
	public function incashothers(){
		// Set the method for the display form to POST
		$this->setMethod('post');
		
		$this->addElement('text', 'amount', array(
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Amount',
			'maxlength'  => '20',
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
'regexNotMatch'=>'Account Number field is allowed only numeric input.')))
                 ),
        ));
		
		
		//Below is use for the gender	
		$fund_transfer = new Zend_Form_Element_Select('fund_transfer',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Select Method==',
			'APB'    => 'APB',
			'NEFT'    => 'NACH / NEFT / RTGS ',
			'CASH'    => 'CASH',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		$requiredtype = new Zend_Validate_NotEmpty ();
		$requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$requiredtype->setMessage('Fund Transfer Method can\'t be  empty.');
		$fund_transfer->addValidators (array($requiredtype));
		$this->addElement($fund_transfer);
		
		$this->addElement('text', 'transaction_date', array(			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'placeholder' => 'dd-mm-yyyy',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
'validators' => array(
			array('notEmpty', true, array(
				'messages' => array(
					'isEmpty'   =>  'Transaction Date can\'t be empty'
				)
			)),
		 array('Regex',
					false,
					  array('/^[0-9-]*$/', 'messages'=>array(
'regexNotMatch'=>'Please enter valid date format.')))
			 ),
			'attribs' => array('readonly' => 'readonly')	 
        ));
		
		$this->addElement('text', 'uniq_user_id', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
        ));
		$this->addElement('text', 'name', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'placeholder' => "Name",
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
        ));
		$this->addElement('text', 'aadhar_num', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'placeholder' => "Aadhar Number",
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
        ));
		$this->addElement('hidden', 'scheme_id', array(	
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
                )))
        ));
	
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
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));	
	}
	
//If scheme benefit type is Kind then this form will load	
	public function inkind(){
		// Set the method for the display form to POST
		$this->setMethod('post');
		
		//below is using for the select transfer date
		$transfer_by = new Zend_Form_Element_Select('transfer_by',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Select Option==',
			'Bio Authentication' => 'Bio Authentication',
			'Demographic Authentication' => 'Demographic Authentication',
			'Manual Validation' => 'Manual Validation',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		$requiredtype = new Zend_Validate_NotEmpty ();
		$requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$requiredtype->setMessage('Fund Transfer Method can\'t be  empty.');
		$transfer_by->addValidators (array($requiredtype));
		$this->addElement($transfer_by);
	//above is using for the select transfer by
		
		
		
		$this->addElement('text', 'transaction_date', array(			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '50',
			'placeholder' => 'dd-mm-yyyy',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
          'validators' => array(
			array('notEmpty', true, array(
				'messages' => array(
					'isEmpty'   =>  'Transaction Date can\'t be empty'
				)
			)),
		 array('Regex',false,array('/^[0-9-]*$/', 'messages'=>array('regexNotMatch'=>'Please enter valid date format.')))
			 ),
			'attribs' => array('readonly' => 'readonly')
        ));
		
		$this->addElement('text', 'uniq_user_id', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs' => array('disabled' => 'disabled')
        ));
		$this->addElement('text', 'name', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'placeholder' => "Name",
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs' => array('disabled' => 'disabled')
        ));
		$this->addElement('text', 'aadhar_num', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'placeholder' => "Aadhar Number",
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs' => array('disabled' => 'disabled')
        ));
		$this->addElement('hidden', 'scheme_id', array(	
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
                ))),
        ));
	
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
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));	
	}
}
