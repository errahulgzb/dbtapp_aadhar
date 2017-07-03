<?PHP


class Application_Form_EditMinistryTranslate extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');
		
		
		
		/***********add language form element *************/
		
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
           $language->setValue("1");	
        $objlang = new Application_Model_Contentmanagement; 
	 	$showlanguage = $objlang->language(); 
       		
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showlanguage as $key => $value){
			$name = $value['title'];			
			$language->addMultiOption($value['id'], $name);
		}
        $this->addElement($language);

		/************* langauge hidden variable*******/
		
		
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
  
		
		/************end***********/

       $this->addElement('text', 'ministry_name', array( 			
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
                        'isEmpty'   =>  'Ministry name can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Ministry name field has special characters.Please remove them and try again.'))) */
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
