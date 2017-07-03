<?PHP


class Application_Form_ContentManagement extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST

//$request = $this->getRequest(); 
//$id = $request->getParam('id');//echo $id;die;
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
                )) array('Regex',
                        false,
                          array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Sort Order will only accept numeric value.')))
                 ),
        ));*/

		$this->addElement('text', 'sort_order', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '3',

			
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
		
      /* $this->addElement('text', 'title', array( 			
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
                        'isEmpty'   =>  'Title is rquired and can\'t be empty'
                    )
                )),
                   array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Title field has special characters.Please remove them and try again.')))
    ),          
  ));*/

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
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Title field has special characters.Please remove them and try again.')))
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
			//'registerInArrayValidator' => false,
              'class'   => 'form-control',
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



//About content description
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
//content description



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
	
	
	/*****for the om *****************/
	public function contentform()
	{

		// Set the method for the display form to POST
		$this->setMethod('post');

		$this->addElement('text', 'title', array( 			
			'required'   => true,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors'),
			'validators' => array(
				array('notEmpty', true, array(
					'messages' => array('isEmpty'   =>  'Title '.CANTEMPTY)
				)),
				array('Regex',
					false,
					array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Title '.ALPHABETSVALIDATION)))
			),
		));
			
		$this->addElement('text', 'filenumber', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors')
		));

		$this->addElement('file', 'uploadfile', array( 
			'class'   => 'form-control',
			'label'      => '',
			'required'   => true,
			'id' => 'uploadfile',
			'validators' => array(
				array('notEmpty', true, array('messages' => array('isEmpty'   =>  'Upload image '.CANTEMPTY)))
			),
		));
			

		$this->addElement('text', 'sort_order', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
			'validators' => array(
			array('Regex',
			false,
			array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Sort Order field will only accept numeric value.')))
			),
		));
			
			
		$language = new Zend_Form_Element_Select('language',array(  
			'label'        => '',
			'required'   => true,
			'class'   => 'form-control',
			'multiOptions' => array(
			'2'    => 'English',
			'1'    => 'Hindi',
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
		
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$language->addValidators (array($required));
		$this->addElement($language);

		// Category drop down
		$language = new Zend_Form_Element_Select('category',array(  
			'label'        => '',
			'required'   => true,
			'class'   => 'form-control',
			'multiOptions' => array(
			''    => 'Select Category'
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
			
		$content_cat = new Application_Model_Contentmanagement; 
		$get_om_category = $content_cat->get_om_category();
		
		foreach($get_om_category as $key => $value){ 
		$catval = $value['title'];  
		$language->addMultiOption($value['id'], $catval);        
		}

		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$language->addValidators (array($required));
		$this->addElement($language);
			
		//Sub Category Dropdown
		$subcat_select = new Zend_Form_Element_Select('sub_category',array(  
			'label'        => '',
			'required'   => false,
			'class'   => 'form-control',
			'multiOptions' => array(
			''    => 'Select Sub Category'
			),
			'RegisterInArrayValidator' => false,
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
			
		$this->addElement($subcat_select);
			
		$this->addElement('text', 'filedate', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
			'validators' => array(
			array('Regex',
			false,
			array('/^\d{2}\/\d{2}\/\d{4}$/', 'messages'=>array('regexNotMatch'=>'Date is not valid')))
			),
		));
			
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
		
	public function editomform()  // Edit om form
	{

		// Set the method for the display form to POST
		$this->setMethod('post');
		
		// title
		$this->addElement('text', 'title', array( 			
			'required'   => true,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors'),
			'validators' => array(
				array('notEmpty', true, array(
					'messages' => array('isEmpty'   =>  'Title '.CANTEMPTY)
				)),
				array('Regex',
					false,
					array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Title '.ALPHABETSVALIDATION)))
			),
			));
		
		//file number	
		$this->addElement('text', 'filenumber', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'decorators'=>Array('ViewHelper','Errors')
		));

		$this->addElement('file', 'uploadfile', array( 
			'class'   => 'form-control',
			'label'      => '',
			'required'   => false,
			'id' => 'uploadfile',
			'validators' => array(
			array('notEmpty', true, array('messages' => array('isEmpty'   =>  'Upload image '.CANTEMPTY)))
			),
		));
		
		//sort order	
		$this->addElement('text', 'sort_order', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '3',
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
			'validators' => array(
			array('Regex',
			false,
			array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Sort Order field will only accept numeric value.')))
			),
		));
			
		//language
		$language = new Zend_Form_Element_Select('language',array(  
			'label'        => '',
			'required'   => true,
			'class'   => 'form-control',
			'multiOptions' => array(
			'2'    => 'English',
			'1'    => 'Hindi',
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
		
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$language->addValidators (array($required));
		$this->addElement($language);

		// Category drop down
		$cat_select = new Zend_Form_Element_Select('category',array(  
			'label'        => '',
			'required'   => true,
			'class'   => 'form-control',
			'multiOptions' => array(
			'0'    => 'Select Category'
			),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
			
		$content_cat = new Application_Model_Contentmanagement; 
		$get_om_category = $content_cat->get_om_category();

		foreach($get_om_category as $key => $value){ 
		$catval = $value['title'];  
		$cat_select->addMultiOption($value['id'], $catval);        
		}

		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$cat_select->addValidators (array($required));
		$this->addElement($cat_select);
			
		
		//Sub Category Dropdown
		$subcat_select = new Zend_Form_Element_Select('sub_category',array(  
			'label'        => '',
			'required'   => false,
			'class'   => 'form-control',
			'multiOptions' => array(
			'0'    => 'Select Sub Category'
			),
			'RegisterInArrayValidator' => false,
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
		));
			
		$this->addElement($subcat_select);
		$content_cat1 = new Application_Model_Contentmanagement; 
		$get_om_category1 = $content_cat1->omgetsubcatdata(trim($_GET['id']));

		foreach($get_om_category1 as $key => $value){ 
		$catval = $value['title'];  
		$subcat_select->addMultiOption($value['id'], $catval);        
		}
		$this->addElement($subcat_select);
		
		//file date		
		$this->addElement('text', 'filedate', array( 			
			'required'   => false,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'decorators'=>Array(
			'ViewHelper','Errors'
			),
			'validators' => array(
			array('Regex',
			false,
			array('/^\d{2}\/\d{2}\/\d{4}$/', 'messages'=>array('regexNotMatch'=>'Date is not valid')))
			),
		));
			
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
