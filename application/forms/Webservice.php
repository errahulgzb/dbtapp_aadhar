<?PHP
class Application_Form_Webservice extends Zend_Form{
public function init(){
// Set the method for the display form to POST
}
public function addform(){
	$rolearr = new Zend_Session_Namespace('role');
		// Set the method for the display form to POST
        $this->setMethod('post');
       $this->addElement('text', 'transaction_id', array(
           'required'   => true,
		   'autocomplete' => 'off',
           'class'   => 'form-control',
		   'placeholder' => 'Request Id',
           'filters'    => array('StringTrim'),
			'maxlength'  => '50',
                        'decorators'=>Array(
                            'ViewHelper','Errors'
                        ),
			   'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Transaction Id can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Transaction Id field has special characters.Please remove them and try again.')))
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


		/*  end */
	}
}
