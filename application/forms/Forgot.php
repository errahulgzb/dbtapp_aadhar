
<?PHP


class Application_Form_Forgot extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'label'      => '',
			'autocomplete' => 'off',
            'required'   => true,
			'class'   => 'form-control',
			'placeholder' => 'username',
            'filters'    => array('StringTrim'),
			'decorators'=>Array(
			'ViewHelper','Errors',
		   ),
			 'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'User name can\'t be empty'
                    )
                )),
                    
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

       		
	}
        
        
    public function resetpasswordform(){
            $this->setMethod('post');
            $this->addElement('password', 'newpassword', array(
                  'label'      => '',
				  'class' => 'form-control',
                  'required'   => true,
				  'placeholder' => 'New password',
                  'filters'    => array('StringTrim'),
                              'size'     =>'37',
                              'id'       =>'newpassword',
                               'autocomplete' => 'off',
                              'decorators'=>Array(
                              'ViewHelper',
                              'Errors',

		   ),
			   'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'New password field can\'t be empty'
                    )
                )),
				array('Regex',
                        false,
                          array('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{8,30})', 'messages'=>array(
'regexNotMatch'=>'Your password quality is too bad. Please use some special character,Numbers,Upper case Letter and Lower case letter!')))
                 ),
            
        ));
		
		
		
		
		   $this->addElement('password', 'conpass', array(
                  'label'      => '',
                  'required'   => true,
				  'class' => 'form-control',
				  'placeholder' => 'Confirm password',
                  'filters'    => array('StringTrim'),
                              'size'     =>'37',
                              'id'       =>'conpass',
                               'autocomplete' => 'off',
                              'decorators'=>Array(
                              'ViewHelper',
                              'Errors',

		   ),
			   'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Confirm password field can\'t be empty'
                    )
                )),
				array('Regex',
                        false,
                          array('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{8,30})', 'messages'=>array(
'regexNotMatch'=>'Your password quality is too bad. Please use some special character,Numbers,Upper case Letter and Lower case letter!')))
                 ),
            
        ));



	
        $this->addElement('password', 'conformnewpassword', array(
            'label'      => '',
            'required'   => true,
			'class' => 'form-control',
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'conformnewpassword',
			 'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),

			   'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Confirmpassword field can\'t be empty'
                    ))),
					array('Regex',
                        false,
                          array('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{8,30})', 'messages'=>array(
'regexNotMatch'=>'Your password quality is too bad. Please use some special character,Numbers,Upper case Letter and Lower case letter!')))
					),
        ));


		/* end */
		
			// Add a captcha
        $this->addElement('text', 'vercodene', array(
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
