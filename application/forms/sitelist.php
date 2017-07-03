<?PHP


class Application_Form_User extends Zend_Form
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
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			   'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Username can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'User name field has special characters.Please remove them and try again.')))
                 ),

            
          
        ));
			
			$this->addElement('password', 'password', array(
            'label'      => '',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'password',
			 'autocomplete' => 'off',
			
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
			  'validators' => array(

			   array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Password field can\'t be empty'
                    )
                )),
                    
                 ),
            
        ));


        $this->addElement('text', 'firstname', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			   'validators' => array(

			   array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'First name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'First name field has special characters.Please remove them and try again.')))
                 ),
            
          
        ));

		$this->addElement('text', 'lastname', array(            
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'validate[required] text-input',
			'maxlength'  => '30',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			   'validators' => array(
                   
		   array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Last name can\'t be empty'
                    )
                )),
		   array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Last name field has special characters.Please remove them and try again.')))
				
		     

                 ),
            
          
        ));

		$this->addElement('text', 'mobile', array(           
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'validate[required,custom[phone]] text-input',
			
			'maxlength'  => '12',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
         'validators' => array(
                                array('Digits', false, array(
                    'messages' => array(
                        'notDigits'     => "Phone Invalid Digits, ex. 1234567890",
                        'digitsStringEmpty' => "",
                    ))),
                array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Mobile No. can\'t be empty'
                    )
                )),
                array('StringLength', false, array(10, 10, 'messages' => array(
                            'stringLengthInvalid'           => "Phone Length Invalid entry",
                            'stringLengthTooShort'          => "Phone Invalid Length , ex. 1234567890"
                    ))),
            ),

          
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
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Email id field can\'t be empty'
                    )
                )),
                'EmailAddress',
            )
        ));
/*
        // Add the comment element
        $this->addElement('textarea', 'comment', array(            
            'required'   => true,
			'style'    => array('width:338px;height:100px'),
			'class'   => 'validate[required] text-input',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 500))
                )
        ));
		
*/

/*
		$this->addElement('select','role',
array(
        'label'        => '',
		'required'   => true,
        'value'        => 'user',
        'multiOptions' => array(
            'user'    => 'user',
            'administrator'   => 'administrator',
            'guest'  => 'guest',
        ),
    )
);
       */


	   /*selection of role from database*/



	   	 $role_collect = new Zend_Form_Element_Select('name',array( 			
            
		    
            'label'        => '',
		'required'   => true,
            'multiOptions' => array(
            '0'    => '---Select---',
                      
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		  // $required = new Zend_Validate_NotEmpty ();
   // $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			//$role_collect->addValidators (array ($required));
			 
    
          
        ));

		  $required = new Zend_Validate_NotEmpty ();
    $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$role_collect->addValidators (array ($required));
          
        $role = new Application_Model_Role; 
	 	$showrolename  = $role->roleuser($_GET['id']);

		//echo "<pre>";
				//print_r($showrolename);
				//echo "</pre>";
				//die;

		
    
		foreach($showrolename as $key => $value)
			{
			$name = $value['name'];
			
			$role_collect->addMultiOption($value['id'], $name);
		
			}

        $this->addElement($role_collect);


		/* end */

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
/*
	 public function isValidPartial(array $formData)
    {
        //call the parent method for basic form validation
        $isValid = parent::isValidPartial($formData);
 
        if($isValid)
        {
            //custom validation
            
			if(!(is_numeric($formData['mobile'])))
				{
						$this->phone->setErrors(array('Mobile number field should contain only numeric value. Please correct and try again.'));
					    $isValid = false;
				}
			
			//if(!($formData['vercode'] == $_SESSION['vercode']))
         //   {
            //    $this->vercode->setErrors(array('Wrong varification code.'));
           //     $isValid = false;
          //  }

			
        }
 
        return $isValid;
    }
	*/


}
