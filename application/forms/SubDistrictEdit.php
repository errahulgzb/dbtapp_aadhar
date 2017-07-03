<?PHP


class Application_Form_SubDistrictEdit extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

		
		
		
		//// project field select box
		$district_code  = $_GET['id'];
		
	  $state = new Zend_Form_Element_Select('state_code',array(  
            'label'        => '',
		    'required'   => true,
			'class'   => 'form-control',
			//'setRequired' =>true,
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Select State--',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   )
		 ));
     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$state->addValidators (array($required));
          
        $objlang = new Application_Model_Subdistrict; 
	 	$showstate = $objlang->state( ); 
      	$statecode = $objlang->statecode($district_code); 
	 	
		foreach($showstate as $key => $value){
			$name = $value['state_name'];			
			$state->addMultiOption($value['state_code'], $name);
		}
	    $this->addElement($state);
		$state->setValue('35');	
		
		/********* display all the district in the form ****/
		  $state = new Zend_Form_Element_Select('district_code',array(  
            'label'        => '',
		    'required'   => true,
			'class'   => 'form-control',
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Select District--',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   )
		 ));
     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$state->addValidators (array($required));
          
        $objlang = new Application_Model_Subdistrict; 
	 	$showdistrict = $objlang->district(); 
      	
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showdistrict as $key => $value){
			$name = $value['district_name'];			
			$state->addMultiOption($value['district_code'], $name);
		}
        $this->addElement($state);
		
		
		
		/************* end*********************/


  $this->addElement('text', 'subdistrict_name', array( 			
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
                        'isEmpty'   =>  'Sub District name is rquired and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Sub District name has special characters.Please remove them and try again.')))
    ),          
  ));
  
    $this->addElement('text', 'subdistrict_code', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('Digits'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Sub District code is rquired and can\'t be empty'
                    )
                ))
                    /* array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Title field has special characters.Please remove them and try again.'))) */
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

}
