<?PHP


class Application_Form_Info_Scheme extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '45',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.')))
    ),          
  ));
  
  
  

/************* Add Language Hidden field *****/




/******************** end***************/
		
		//// project field select box
		
	  $ministry = new Zend_Form_Element_Select('ministry_id',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Ministry--',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));
     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$ministry->addValidators (array($required));
          
        $objministry = new Application_Model_DbtMinistry; 
	 	$showministryname = $objministry->roleuser();   
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showministryname as $key => $value){
			$name = $value['ministry_name'];			
			$ministry->addMultiOption($value['id'], $name);
		}
        $this->addElement($ministry);
		
		
		
 




		
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
