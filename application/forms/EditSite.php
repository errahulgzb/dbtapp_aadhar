<?PHP

class Application_Form_EditSite extends Zend_Form
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
		
		$this->addElement('radio', 'site_status', array(
				'required'   => true,
				'multiOptions' => array(
					'1' => 'Open',
					'2' => 'Close',
					
				),
				'value' => '1' //key of multiOption
			));

//start date and end date

$this->addElement('text', 'start_date', array(
             'label'      => '',
             'id'         => 'dateselector',
	         'placeholder'=>'Start Date',
			 'filters'    => array('StringTrim'),
             'class' => 'button',
			 'style'    => array('width:100px'),
			 'attribs' => array('readonly' => 'true'),
			 'autocomplete' => 'off',
			 'decorators'=>Array(
			 'ViewHelper',
			 'Errors',
			
		   ),
			
   
            
        ));

		$this->addElement('text', 'end_date', array(
            'label'      => '',
           'id'         => 'dateselector1',
            'filters'    => array('StringTrim'),
			'placeholder'=>'End Date',
            'class' => 'button',
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
		$showdetails = $role->edituserclient($_GET['id']);
		foreach($showrolename as $key => $value)
			{
			$name = $value['organisation'];
			
			$customer_collect->addMultiOption($value['id'], $name);
		
			}
		$customer_collect->setValue($showdetails['customer_id']);
        $this->addElement($customer_collect);
		
		
		// project 
	
	  $role_collect = new Zend_Form_Element_Select('projectname',array( 			
            
		    
            'label'        => '',
		    'required'   => true,
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
	 	$showrolename  = $role->roleuserproject($_GET['id']);
		$showdetails = $role->edituserclient($_GET['id']);
		
		//echo "<pre>";
				//print_r($showrolename);
				//echo "</pre>";
				//die;

		
    
		foreach($showrolename as $key => $value)
			{
			$name = $value['title'];
			
			$role_collect->addMultiOption($value['id'], $name);
		
			}
		
		$role_collect->setValue($showdetails['project_id']);

        $this->addElement($role_collect);
		
		
		
		
			
	  $role_collect1 = new Zend_Form_Element_Select('locationname',array( 			
            
		    
            'label'        => '',
		    'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Location',
                      
        ),
			'registerInArrayValidator' => false,
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	  $role_collect1->addValidators (array ($required));
          
        $role1 = new Application_Model_Site; 
	 	$showrolename1  = $role1->locationuseredit($_GET['id']);
		$showdetails1 = $role1->edituserclient($_GET['id']);
		
		//echo "<pre>";
				//print_r($showrolename);
				//echo "</pre>";
				//die;

		
    
		foreach($showrolename1 as $key => $value)
			{
			$name1 = $value['title'];
			
			$role_collect1->addMultiOption($value['id'], $name1);
		
			}

      
		
		
		
		$role_collect1->setValue($showdetails1['location_id']);

        $this->addElement($role_collect1);
		
		
		
			
	
		 
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
