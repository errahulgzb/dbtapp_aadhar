ddd<?PHP


class Application_Form_EditDistrict extends Zend_Form
{
 public function init()
        {
        // Set the method for the display form to POST
        }

        public function addform()
	{
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
                            'isEmpty'   =>  'State name can\'t be empty'
                            )
                            )),
                            array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'State name field has special characters.Please remove them and try again.')))
                     ),
            ));
	
            
            
            
        $state_name = new Zend_Form_Element_Select('state_id',array( 			   
                    'label'        => '',
		    'required'   => true,
                    'multiOptions' => array(
                    '0'    => 'Select State',

                ),
                'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


         $required = new Zend_Validate_NotEmpty ();
         $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	 $state_name->addValidators (array ($required));          
	 $state_model_object = new Application_Model_District; 
	 $statelist  = $state_model_object->stateList();
         foreach($statelist as $key => $value)
         {
                $name = $value['title'];			
                $state_name->addMultiOption($value['id'], $name);
         }
        $this->addElement($state_name);
		
		
        
        
        
        
        $this->addElement('text', 'district_code', array( 			
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
                            'isEmpty'   =>  'State name can\'t be empty'
                            )
                            )),
                            array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'State name field has special characters.Please remove them and try again.')))
                     ),
            ));
        
        
        
        $this->addElement('text', 'town_level', array( 			
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
                            'isEmpty'   =>  'State name can\'t be empty'
                            )
                            )),
                            array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'State name field has special characters.Please remove them and try again.')))
                     ),
            ));
        
        
        $this->addElement('text', 'town_code', array( 			
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
                            'isEmpty'   =>  'State name can\'t be empty'
                            )
                            )),
                            array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'State name field has special characters.Please remove them and try again.')))
                     ),
            ));
        
        
        /// project field select box
		
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
          
        $role = new Application_Model_District; 
	 	$showrolename  = $role->roleuser($_GET['id']);

		
    
		foreach($showrolename as $key => $value)
			{
			$name = $value['title'];
			
			$role_collect->addMultiOption();
		
			}

        $this->addElement($role_collect);
		
		
	
        
        
        
        
        
        
        
        
        
        
		
		 
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
