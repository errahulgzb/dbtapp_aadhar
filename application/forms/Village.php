<?PHP


class Application_Form_Panchayat extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

		
		/********** state name ********/
		 $state = new Zend_Form_Element_Select('state_code',array(  
            'label'        => '',
		    'required'   => true,
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
	 	$showstate = $objlang->state(); 
      	
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showstate as $key => $value){
			$name = $value['state_name'];			
			$state->addMultiOption($value['state_code'], $name);
		}
        $this->addElement($state);
		
		
		
		/********end***********/
		
		
/********* display all the district in the form ****/
		  $state = new Zend_Form_Element_Select('district_code',array(  
            'label'        => '',
		    'required'   => true,
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



		/********* add subdistrict_code*********/
		
		  $state = new Zend_Form_Element_Select('subdistrict_code',array(  
            'label'        => '',
		    'required'   => true,
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Select Sub District--',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   )
		 ));
     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$state->addValidators (array($required));
          
        $objlang = new Application_Model_Panchayat; 
	 	$showsubdistrict = $objlang->subdistrictlist(); 
      	
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showsubdistrict as $key => $value){
			$name = $value['subdistrict_name'];			
			$state->addMultiOption($value['subdistrict_code'], $name);
		}
        $this->addElement($state);
		
		
		/************ end**************/
		
		
		/******add panchayat_code*****/
		
		  $this->addElement('text', 'panchayat_name', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
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
  
  
  /********************end ***********/
  
  
     $this->addElement('text', 'panchayat_code', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
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
