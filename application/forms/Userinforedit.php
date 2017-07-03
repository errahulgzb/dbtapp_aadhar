<?PHP

class Application_Form_EditUser extends Zend_Form
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
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));
			
			$this->addElement('password', 'password', array(
            'label'      => '',
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'id'       =>'password',
			 'autocomplete' => 'off',
			'style'    => array('width:240px'),
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
            
        ));


        $this->addElement('text', 'firstname', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),          
        ));

		$this->addElement('text', 'lastname', array(            
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'form-control',
			'maxlength'  => '30',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));
		
		
		
		/**********For role *********/
		
		$this->addElement('text', 'name', array(            
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'form-control',
			'maxlength'  => '30',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
        ));
		
		
		/************ end ***********/
		

		$this->addElement('text', 'mobile', array(           
            'required'   => true,
            'filters'    => array('StringTrim'),
			'class'   => 'form-control',
			'maxlength'  => '12',
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
			'class'   => 'form-control',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
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



	   	 
$edit_show = new Application_Model_User;
	$showdetails = $edit_show->edituserclient($_GET['id']);

	//$rolemole_show = new Application_Model_Role;
	//$detailspage = $rolemole_show->roleclientusers($showdetails['role']);

			 
  // echo $detailspage['title'];
				//echo "<pre>";
				//print_r($detailspage);
				//echo "</pre>";
			//	die;
		foreach($showrolename as $key => $value)
			{
			$name = $value['name'];
			//echo $name;
		//	die;
			$role_collect->addMultiOption($value['id'], $name);
			}
//echo $_GET['id'];
//die;
 $role_collect->setValue($showdetails['role']);
//echo $role_collect;
//die;
        $this->addElement($role_collect);


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
