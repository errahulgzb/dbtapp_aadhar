<?PHP

class Application_Form_ChangeUserPassword extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function changepassword()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

      $this->addElement('password', 'oldpassword', array(
            'label'      => '',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'oldpassword',
			'class'   => 'form-control',
			'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
		   ),
			   'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Old password field can\'t be empty'
                    )
                ))
            ),
        ));
			
			$this->addElement('password', 'newpassword', array(
            'label'      => '',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'newpassword',
			'class'   => 'form-control',
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
                ))
                    
                 ),
            
        ));


        $this->addElement('password', 'conformnewpassword', array(
            'label'      => '',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'conformnewpassword',
			'class'   => 'form-control',
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
                ))
                    
                 ),
            
        ));


		/* end */
		
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



	 public function isValidPartial($formData)
    {
        //call the parent method for basic form validation
        $isValid = parent::isValidPartial($formData);
 
        if($isValid)
        {
            //custom validation
           

				 if(strlen($formData['newpassword']) <= 7)
				{
					$this->newpassword->setErrors(array('Password field should be minimum 8 characters.'));
					
					$isValid = false;
				}
			 if(!($formData['newpassword'] == $formData['conformnewpassword']))
            {
                //$this->newpassword->setErrors(array('New Password and Confirm password should be same.'));
				 $this->conformnewpassword->setErrors(array('New Password and Confirm password should be same.'));
                $isValid = false;
            }

			
				
        }
 
        return $isValid;
    }
	



}
