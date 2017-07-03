<?PHP


class Application_Form_BeneficiaryDisclaimer extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

//About content description
        $this->addElement('textarea', 'disclaimer', array( 			
            'required'   => true,
            'class'   => 'form-control',
			 //'id' => 'mceEditor',
			 'id' => 'editor1',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10000',
			'cols' => '1000',
			'rows' => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
 'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Disclaimer is required and can\'t be empty'
                    )
                )) ,
            array('Regex',
                        false,
                          array('/^[a-zA-Z0-9~`!#$^*_:?()@., \"\'-\n\r ]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Disclaimer field has special characters.Please remove them and try again.')))
			),  				
  ));
//content description


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

