<?PHP


class Application_Form_Utbeneficiarydataedit extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');
		
		//to add schme UT
        $utname = new Zend_Form_Element_Select('ut_name',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Category--',                      
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
		));
		$requiredtype = new Zend_Validate_NotEmpty ();
		$requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$utname->addValidators (array($requiredtype));
		$objstate = new Application_Model_Utscheme; 
			$utlist = $objstate->getutlist();   
			foreach($utlist as $key => $value){
				$name = $value['state_name'];			
				$utname->addMultiOption($value['state_code'], $name);
			}
		$ut_code = base64_decode($_REQUEST['state']);
		$utname->setValue($ut_code);
		$this->addElement($utname);
	
       $this->addElement('text', 'totalnoofbeneficiarieswithaadhaar', array( 			
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
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of beneficiaries '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'totalnoofbeneficiarieswithseededbankac', array( 			
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
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of beneficiaries data seeded with Aadhaar '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'totalnoofbeneficiaries', array( 			
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
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total Fund transferred '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'totalnoofbeneficiarieswithbankac', array( 			
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
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Using Aadhar bridge payment '.NUMERICVALIDATION)))
                 ),
        ));
		// $this->addElement('text', 'without_aadhar_bridge_payment', array( 			
            // 'required'   => false,
			// 'class'   => 'form-control',
            // 'filters'    => array('StringTrim'),
			// 'maxlength'  => '100',
			// 'decorators'=>Array(
			// 'ViewHelper','Errors'
		   // ),
            // 'validators' => array(
                    // array('Regex',
                        // false,
                          // array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Without Aadhar bridge payment '.NUMERICVALIDATION)))
                 // ),
        // ));
	

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
		for($i=$currentyear; $i >= 2017; $i--){
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
