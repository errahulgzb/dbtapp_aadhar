<?PHP


class Application_Form_EditScheme extends Zend_Form
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
			'maxlength'  => '250',
			//'readonly' => 'readonly',
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
                          array('/^[a-z][a-z0-9.,&\(\)\/ \-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Scheme name field has special characters.Please remove them and try again.'))) 
                 )
          
        ));
		//

//Add Scheme Code
		$this->addElement('text', 'scheme_codification', array( 			
            //'required'   => true,
            'class'   => 'form-control scheme_codification',
            'filters'    => array('StringTrim'),
			//'placeholder' => 'Provided By PFMS',
			'maxlength'  => '7',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),  
			'attribs'    => array('disabled' => 'disabled')	
		));

		//Add PFMS Scheme Code
		$this->addElement('text', 'pfms_scheme_code', array( 			
            //'required'   => true,
            'class'   => 'form-control scheme_codification',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Provided By PFMS',
			'maxlength'  => '7',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),   
			
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
          $id=safexss(base64_decode($_GET['id']));
        $role = new Application_Model_DbtMinistry; 
	 	$showrolename  = $role->projectuseredit($id);
		$showdetails = $role->edituserclient($id);
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
            '0'    => '--Select Benefit Type--',
            '1'    => 'In Cash',
            '2'    => 'In Kind',
            '3'    => 'In Others',
                      
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
//to add schme group
        $schmeGroup = new Zend_Form_Element_Select('scheme_group',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
             '0'    => '==Select Type Of The Scheme==',
            '1'    => 'Central Sector Scheme',
            //'2'    => 'State/UTs Scheme',    
            '3'    => 'Centrally Sponsored Scheme', 
            //'4'    => 'District Scheme',   	   
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeGroup->addValidators (array($requiredtype));
    $this->addElement($schmeGroup);
//add schme group end here

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
