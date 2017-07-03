<?php

class Application_Form_Contactus extends Zend_Form
{

    public function init()
    {
       

    }

	public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'fname', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));

		$this->addElement('text', 'lname', array(            
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'validate[required] text-input',
			'maxlength'  => '30',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));









		$this->addElement('text', 'phone', array(           
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'validate[required,custom[phone]] text-input',
			
			'maxlength'  => '30',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           'validators' => array(
               array('validator' => 'StringLength', 'options' => array(0, 20))
             )
          
        ));

		$this->addElement('text', 'email', array( 
			'label'      => '',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'maxlength'  => '40',
			'class'   => 'validate[required,custom[email]] text-input',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the comment element
        $this->addElement('textarea', 'address', array(            
            'required'   => true,
			
			'class'   => 'validate[required] text-input',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 500))
                )
        ));
		

		
        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
			'maxlength'  => '30',
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            ),
			'decorators'=>Array(
			'Errors'
		   ),
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

	 public function isValidPartial(array $formData)
    {
        //call the parent method for basic form validation
        $isValid = parent::isValidPartial($formData);
 
        if($isValid)
        {
            //custom validation
            
			if(!(is_numeric($formData['phone'])))
				{
						$this->phone->setErrors(array('Phone number field should contain only numeric value. Please correct and try again.'));
					    $isValid = false;
				}
			
			if(!($formData['vercode'] == $_SESSION['vercode']))
            {
                $this->vercode->setErrors(array('Wrong varification code.'));
                $isValid = false;
            }
        }
 
        return $isValid;
    }


}
