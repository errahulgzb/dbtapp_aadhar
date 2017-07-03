<?PHP


class Application_Form_District extends Zend_Form
{

        
         public function init()
        {
        // Set the method for the display form to POST
        }
		
		public function addform()
	     {
			 $this->setMethod('post');
			 
			  $state_name = new Zend_Form_Element_Select('state_id',array( 			   
                    'label'        => '',
					'required'   => true,
					'class'   => 'form-control',
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
                $name = $value['state_name'];			
                $state_name->addMultiOption($value['id'], $name);
         }
        $this->addElement($state_name);
			 
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
        'regexNotMatch'=>'District name has special characters.Please remove them and try again.')))
                     ),
            ));
			
			
			  $this->addElement('text', 'district_code', array( 			
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
                            'isEmpty'   =>  'District code can\'t be empty'
                            )
                            ))
                            /* array('Regex',
                                false,
                                  array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
        'regexNotMatch'=>'District code has special characters.Please remove them and try again.'))) */
                     ),
            ));
			 
		 }	 
        
        public function importform()
	{
            $this->setMethod('post');
            $this->addElement('file', 'importfile', array( 
                            'label'      => '',
                            'required'   => true,
							'class'   => 'form-control',
                            'id' => 'importfile',
                            ));
		 
// Add a captcha
        $this->addElement('text', 'vercode', array(
            //'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
			 'style'    => array('width:338px'),
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

	}
        
        
}
