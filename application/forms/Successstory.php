<?PHP


class Application_Form_Successstory extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');

       $this->addElement('text', 'title', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors'),
            'validators' => array(
					array('notEmpty', true, array(
					'messages' => array(
						'isEmpty'   =>  'Title is required and can\'t be empty'))),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9!*_:()@., -]{0,}$/i', 'messages'=>array('regexNotMatch' => 'Title field has special characters.Please remove them and try again.')))
                 ),
        ));

		$this->addElement('text', 'author', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors'),
            'validators' => array(
					array('notEmpty', true, array(
					'messages' => array(
						'isEmpty'   =>  'Author is required and can\'t be empty'))),
                    array('Regex',
                        false,
                          array('/^[a-zA-Z0-9!*_:()@., -]{0,}$/i', 'messages'=>array('regexNotMatch' => 'Author field has special characters.Please remove them and try again.')))
                 ),
        ));

		$this->addElement('textarea', 'description', array( 			
			'required'   => true,
			'class'   => 'form-control',
			'id' => 'editor1',
			'filters'    => array('StringTrim'),
			'maxlength'  => '10000',
			'cols' => '1000',
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
			'validators' => array(
				array('notEmpty', true, array(
					'messages' => array(
						'isEmpty'   =>  'Descrption is required and can\'t be empty'))),
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
