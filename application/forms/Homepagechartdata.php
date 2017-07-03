<?PHP


class Application_Form_Homepagechartdata extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'mgnregs_data', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Mgnregs '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'nsap_data', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'NSAP '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'pahal_data', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'PAHAL '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'scholarship_data', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Scholarship '.NUMERICVALIDATION)))
                 ),
        ));
		$this->addElement('text', 'others_data', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Others '.NUMERICVALIDATION)))
                 ),
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
