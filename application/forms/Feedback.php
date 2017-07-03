<?PHP


class Application_Form_Feedback extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

		$type = new Zend_Form_Element_Select('type',array(  
            'label'        => '',
		    'required'   => true,
                    'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select type',
			'01' => 'Complaint',
			'02' => 'Feedback',       
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$type->addValidators (array($required));
		$this->addElement($type);

        $this->addElement('text', 'name', array( 			
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
                        'isEmpty'   =>  'Name is required and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Name field has special characters.Please remove them and try again.')))
    ),          
  ));
  
  
   $this->addElement('text', 'email', array( 			
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
                        'isEmpty'   =>  'Email is required and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[A-Za-z0-9\.|-|_]*[@]{1}[A-Za-z0-9\.|-|_]*[.]{1}[a-z]{2,5}$/i', 'messages'=>array(
'regexNotMatch'=>'Email field has special characters.Please remove them and try again.')))
    ,          
  )));
  $this->addElement('text', 'mobile', array( 			
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
                        'isEmpty'   =>  'Mobile is required and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Mobile field will only accept numeric value.')))
                 ),
        ));
		
		$this->addElement('textarea', 'details', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'rows' => '2',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Detail is required and can\'t be empty'
                    )
                ))
                    
                 ,
						array('Regex',
                        false,
                          array('/^[a-zA-Z0-9~`!#$^*_:?()@., \"\'\- \n\r ]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Detail field has special characters.Please remove them and try again.')))
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
