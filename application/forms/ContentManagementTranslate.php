<?PHP


class Application_Form_ContentManagementTranslate extends Zend_Form
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
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Title is required and can\'t be empty'
                    )
                ))
                    /* array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Title field has special characters.Please remove them and try again.'))) */
    )     
 /* 'attribs'    => array('disabled' => 'disabled')	 */	
  ));
		
		
		
		    $this->addElement('hidden', 'lang', array( 			
            'required'   => true,
			'type' => 'hidden',
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'value' => '1',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   )
                    
  ));
  
		
		
		
		//// customer field select box
	/*	
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
	 $role = new Application_Model_Location; 
	 $showrolename  = $role->customeruser($_GET['id']);

		
    
		foreach($showrolename as $key => $value)
			{
				$name = $value['organisation'];			
				$customer_collect->addMultiOption($value['id'], $name);
		
			}

        $this->addElement($customer_collect);
		*/
		
		//// project field select box
		
	  $language = new Zend_Form_Element_Select('language',array(  
            'label'        => '',
		    'required'   => true,
              'class'   => 'form-control',
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Select Language--',
                      
        ),
		
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 'attribs'    => array('disabled' => 'disabled')	
		
		 ));
     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$language->addValidators (array($required));
          
        $objlang = new Application_Model_Contentmanagement; 
	 	$showlanguage = $objlang->language();
        $language->setValue("1");		
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showlanguage as $key => $value){
			$name = $value['title'];			
			$language->addMultiOption($value['id'], $name);
		}
        $this->addElement($language);

//to add schme type
        $schmeType = new Zend_Form_Element_Select('schemetype',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Scheme Type--',
            '1'    => 'All',
            '2'    => 'Gas',
            '3'    => 'Kind',
            '4'    => 'Others',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));
        $this->addElement($schmeType);
//add schme type end here

//About scheme description
        $this->addElement('textarea', 'description', array( 			
            'required'   => false,
            'class'   => 'form-control',
			 //'id' => 'mceEditor',
			 'id' => 'editor1',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10000',
			'cols' => '1000',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Descrption is required and can\'t be empty'
                    )
                )),),          
  ));
//scheme description



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

		
	}

}
