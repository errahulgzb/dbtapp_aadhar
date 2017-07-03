<?PHP


class Application_Form_EditSchememanualdata extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'total_no_of_beneficiaries', array( 			
            'required'   => true,
			'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Total No. of beneficiaries can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of beneficiaries can take only numeric value.')))
                 ),
        ));
		$this->addElement('text', 'total_no_of_beneficiaries_with_aadhar', array( 			
            'required'   => true,
			'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Total No. of beneficiaries can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total No. of beneficiaries data seeded with Aadhaar can take only numeric value.')))
                 ),
        ));
		$this->addElement('text', 'total_fund_transfered', array( 			
            'required'   => true,
			'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Total Fund transferred can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Total Fund transferred can take only numeric value.')))
                 ),
        ));
		$this->addElement('text', 'using_aadhar_bridge_payment', array( 			
            'required'   => true,
			'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Total Fund transferred can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Using Aadhar bridge payment can take only numeric value.')))
                 ),
        ));
		$this->addElement('text', 'without_aadhar_bridge_payment', array( 			
            'required'   => true,
			'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Total Fund transferred can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Without Aadhar bridge payment can take only numeric value.')))
                 ),
        ));

		$month = new Zend_Form_Element_Select('month_select_list',array(  
            'label'        => '',
		    'required'   => true,
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
		$year = new Zend_Form_Element_Select('year_select_list',array(  
            'label'        => '',
		    'required'   => true,
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
		for($i=$currentyear; $i >= $currentyear - 5; $i--){
			$year->addMultiOption($i, $i);
		}
		$this->addElement($year);

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
