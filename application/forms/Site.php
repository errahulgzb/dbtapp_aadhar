<?PHP


class Application_Form_Site extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'title', array( 			
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
                        'isEmpty'   =>  'Site name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Site name field has special characters.Please remove them and try again.')))
                 ),

          
        ));
		$this->addElement('text', 'dateselector', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
						
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));
		$this->addElement('text', 'dateselector1', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
						
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            

          
        ));
		
		$this->addElement('radio', 'site_status', array(
				'required'   => true,
				'multiOptions' => array(
					'1' => 'Open',
					'2' => 'Close',
					
				),
				'value' => '1' //key of multiOption
			));



			$this->addElement('text', 'startDate', array(
            'label'      => '',
           'id'         => 'dateselector',
			'filters'    => array('StringTrim'),
            'class' => 'button',
			'placeholder'=>'Start Date',
			'style'    => array('width:100px'),
			'attribs' => array('readonly' => 'true'),
			 'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
			
   
            
        ));

		$this->addElement('text', 'endDate', array(
            'label'      => '',
           'id'         => 'dateselector1',
            'filters'    => array('StringTrim'),
            'class' => 'button',
			'placeholder'=>'End Date',
			'style'    => array('width:100px'),
			'attribs' => array('readonly' => 'true'),
			 'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
		
   
            
        ));
			
			
			
			
		// customer dropdown
		
	 $customer_collect = new Zend_Form_Element_Select('customer',array( 			
            
		    
            'label'        => '',
		    'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Customer',
                      
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$customer_collect->addValidators (array ($required));
          
        $role = new Application_Model_Site; 
	 	$showrolename  = $role->customeruser();

		foreach($showrolename as $key => $value)
			{
			$name = $value['organisation'];
			
			$customer_collect->addMultiOption($value['id'], $name);
		
			}

        $this->addElement($customer_collect);
		
		
	
		// project dropdown
	 $role_collect = new Zend_Form_Element_Select('projectname',array( 			
            
		    
            'label'        => '',
		    'required'   => true,
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => 'Select Project',
                      
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$role_collect->addValidators (array ($required));
          
        $role = new Application_Model_Site; 
	 	$showrolename  = $role->roleuser();

		foreach($showrolename as $key => $value)
			{
			$name = $value['title'];
			
			$role_collect->addMultiOption();
		
			}

        $this->addElement($role_collect);
		
	
		// Location  dropdown start 
		
	$location_collect = new Zend_Form_Element_Select('locationname',array( 			
            
		    
            'label'        => '',
		    'required'   => true,
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => 'Select Location',
                      
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	  $location_collect->addValidators (array ($required));
          
        $location = new Application_Model_Site; 
	 	$showrolename  = $location->locationuser($_GET['id']);    
		foreach($showrolename as $key => $value)
			{
			$name = $value['title'];
			
			$location_collect->addMultiOption();
		
			}
		
        $this->addElement($location_collect);

        

		
		
		 
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
