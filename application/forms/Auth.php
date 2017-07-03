<?php
use Zend\Captcha;
use Zend\Form\Form;
class Application_Form_Auth extends Zend_Form
{

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'admin', array(
            'label'      => '',
			'class' => 'form-control',
			'autocomplete' => 'off',
            'required'   => true,
            'placeholder' => 'username',
            'filters'    => array('StringTrim'),
			'decorators'=>Array(
			'ViewHelper',
			'Errors',

			
		   ),
           
        ));

		$this->addElement('password', 'password', array(
            'label'      => '',
			'class' => 'form-control',
			'autocomplete' => 'off',
            'required'   => true,
            'placeholder' => 'password',
            'filters'    => array('StringTrim'),
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))
                   
    ),          
           
        ));
		
		
		
		//add a figlet captcha

/*
  $this->addElement('captcha', 'captcha', array(
            'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Dumb',
                'wordLen' => 5,
                'timeout' => 10,
           'messageTemplates' => array(
    'missingvalue' => 'Empty captcha value'
   
),
            ),
			
			
        ));
	
*/

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
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
        ));

        // And finally add some CSRF protection
      /*   $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
 */
		


    }
}
