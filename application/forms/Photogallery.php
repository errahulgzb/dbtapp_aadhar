<?PHP


class Application_Form_Photogallery extends Zend_Form
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
						'messages' => array('isEmpty'   =>  'Title '.CANTEMPTY)
					)),
					array('Regex',
						false,
						array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Title '.ALPHABETSVALIDATION)))
				),
			));

			$type = new Zend_Form_Element_Select('type',array(  
				'label'        => '',
				'required'   => true,
				'class'   => 'form-control',
				'multiOptions' => array(
					'0'    => 'Select Type',
					'1'    => 'Photo Gallery',
					'2'    => 'Home Page Banner',
                                        '3'    => 'Video',
					'4'    => 'Youtube Embedded Video',
				),
				'decorators'=>Array(
				'ViewHelper','Errors'
				),
			
			 ));
                        $this->addElement('text', 'embed_code', array( 			
				'required'   => false,
				'class'   => 'form-control',
				'filters'    => array('StringTrim'),
				'maxlength'  => '100',
				'decorators'=>Array('ViewHelper','Errors'),
				
			));
                        
			$required = new Zend_Validate_NotEmpty ();
			$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$type->addValidators (array($required));
			$this->addElement($type);

			$language = new Zend_Form_Element_Select('language',array(  
				'label'        => '',
				'required'   => true,
				'class'   => 'form-control',
				'multiOptions' => array(
					'0'    => 'Select Language',
					'1'    => 'Hindi',
					'2'    => 'English',
				),
				'decorators'=>Array(
				'ViewHelper','Errors'
				),
			
			 ));
			$required = new Zend_Validate_NotEmpty ();
			$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$language->addValidators (array($required));
			$this->addElement($language);

			$this->addElement('file', 'uploadimage', array( 
				'class'   => 'form-control',
				'label'      => '',
				'required'   => true,
				'id' => 'uploadimage',
				'validators' => array(
					array('notEmpty', true, array('messages' => array('isEmpty'   =>  'Upload image '.CANTEMPTY)))
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
