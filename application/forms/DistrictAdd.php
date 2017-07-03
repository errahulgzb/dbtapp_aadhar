<?PHP


class Application_Form_DistrictAdd extends Zend_Form
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
                    'class'   => 'form-control',
                    'filters'    => array('StringTrim'),
                    'maxlength'  => '100',
                    'decorators'=>Array(
                        'ViewHelper','Errors'
                    ),
                    'validators' => array(
                          array('notEmpty', true, array(
                            'messages' => array(
                            'isEmpty'   =>  'District name can\'t be empty'
                            )
                            )),
                            array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'District name field has special characters.Please remove them and try again.')))
                     ),
            ));
	
            
            
            
       
		
		
        
        
        
        
        $this->addElement('text', 'district_code', array( 			
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
                            'isEmpty'   =>  'State name can\'t be empty'
                            )
                            ))
                          /*   array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'State name field has special characters.Please remove them and try again.'))) */
                     ),
            ));
        
        
        
        $this->addElement('text', 'town_level', array( 			
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
                    'class'   => 'form-control',
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
			'class'   => 'form-control',
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

        
        
        
        
        
        
        
        
        
        
        
        
        public function importform()
	{
            $this->setMethod('post');
           $this->addElement('file', 'importfile', array( 
                            'label'      => '',
                            'required'   => false,
                            'id' => 'importfile',
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
