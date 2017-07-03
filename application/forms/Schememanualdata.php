<?PHP


class Application_Form_Schememanualdata extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'no_of_beneficiries_in_scheme', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of transaction allow 0nly Numeric Value.')))
                 ),
        ));
		$this->addElement('text', 'no_of_beneficiries_with_aadhar', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of transaction data seeded with Aadhaar allow 0nly Numeric Value.')))
                 ),
        ));
		$this->addElement('text', 'total_fund_transfer', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9.]*$/', 'messages'=>array('regexNotMatch'=>'Total Fund transferred allow 0nly Numeric Value.')))
                 ),
        ));
		$this->addElement('text', 'using_aadhar_bridge_payment', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9.]*$/', 'messages'=>array('regexNotMatch'=>'Using Aadhar payment bridge allow 0nly Numeric Value.')))
                 ),
        ));
		$this->addElement('text', 'without_aadhar_bridge_payment', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9.]*$/', 'messages'=>array('regexNotMatch'=>'Without Aadhar payment bridge allow 0nly Numeric Value.')))
                 ),
        ));
		$this->addElement('text', 'saving', array( 			
            'required'   => false,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9.]*$/', 'messages'=>array('regexNotMatch'=>'Saving allow 0nly Numeric Value.')))
                 ),
        ));

		$month = new Zend_Form_Element_Select('month',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select Month',
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December',         
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$month->addValidators (array($required));
		$this->addElement($month);

		$currentyear = date('Y');
		$currentmonth = date('m');
		if ($currentmonth > 03) {$currentyear = $currentyear + 1;}
		$year = new Zend_Form_Element_Select('year',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => 'Select Year',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$year->addValidators (array($required));
		for($i=$currentyear; $i >= YEAR_FROM; $i--){
			$financialyearfrom = $i - 1;
			$financialyearto = $i;
			$yearval = $financialyearfrom.'-'.$financialyearto;
			$year->addMultiOption($yearval, $yearval);
		}
		$this->addElement($year);


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
