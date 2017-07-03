<?PHP


class Application_Form_UtEditScheme extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			//'readonly' => 'readonly',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))
                    /* array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Scheme name field has special characters.Please remove them and try again.'))) */
                 )
            //'attribs'    => array('disabled' => 'disabled')
          
        ));
		
		
		/************ Add language Element 28th june ******************/
 $language = new Zend_Form_Element_Select('language',array(  
            'label'        => '',
            'required'   => true,
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



/****************** end **********************/  
		
	  $role_collect = new Zend_Form_Element_Select('ministry_id',array( 			
                'label'        => '',
		'required'   => true,
		'registerInArrayValidator' => false,
                'class'   => 'form-control',
                'multiOptions' => array(
                    '0'    => '--Select Ministry--',
                ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));


     $required = new Zend_Validate_NotEmpty ();
     $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$role_collect->addValidators (array ($required));
          
        $role = new Application_Model_DbtMinistry; 
	 	$showrolename  = $role->projectuseredit($_GET['id']);
		$showdetails = $role->edituserclient($_GET['id']);
		foreach($showrolename as $key => $value)
			{
			$name = $value['ministry_name'];
			$role_collect->addMultiOption($value['id'], $name);
			}		
		$role_collect->setValue($showdetails['id']);

        $this->addElement($role_collect);
		
//to add schme type
        $schmeType = new Zend_Form_Element_Select('scheme_type',array(  
            'label'        => '',
		    'required'   => true,
			'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme Type--',
            '1'    => 'Cash',
            '2'    => 'Kind',
            '3'    => 'Others',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		
		 ));
        $this->addElement($schmeType);
//add schme type end here

//About scheme description
        $this->addElement('textarea', 'description', array( 			
            'required'   => true,
            'class'   => 'form-control',
			 //'class'   => 'mceEditor',
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
                        'isEmpty'   =>  'Descrption can\'t be empty'
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
