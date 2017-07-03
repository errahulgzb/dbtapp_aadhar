<?PHP


class Application_Form_ContentManagementEdit extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');
		
		
		//to add menu_type added on 1st jully//
        $menuType = new Zend_Form_Element_Select('menu_type',array(  
            'label'        => '',
		    'required'   => true,
            'class'   => 'form-control',
			//'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '--Select Menu Type--',
            '1'    => 'Header',
            '2'    => 'Footer',
			'3'    => 'Simple Content',
                      
        ),
		
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   
		
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$menuType->addValidators (array($requiredtype));
        $this->addElement($menuType);
//add schme type end here///

		/*******add sort order text box *********/
		 /* $this->addElement('text', 'sort_order', array( 			
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
                        'isEmpty'   =>  'Sort Order field is rquired and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Sort Order field has special characters.Please remove them and try again.')))
    ),          
  ));*/

  $this->addElement('text', 'sort_order', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '2',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Sort Order field is required and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Sort Order field will only accept numeric value.')))
                 ),
        ));
		
		/*************end ************/

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
                        'isEmpty'   =>  'Title can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Title field has special characters.Please remove them and try again.'))) */
    ),    
  ));
  
		
		
		
		 $this->addElement('hidden', 'lang', array( 			
            'required'   => true,
			'type' => 'hidden',
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'value' => '2',
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
       $language->setValue("2");			
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
            'class'   => 'form-control',
		    'required'   => true,
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
