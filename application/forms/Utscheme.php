<?PHP


class Application_Form_Utscheme extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{
	$admname = new Zend_Session_Namespace('adminMname'); 
	$userid = new Zend_Session_Namespace('userid');
	$role = new Zend_Session_Namespace('role');	
	
		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.'))) */
    ),          
  ));
  
  
  
/************ Add language Element 28th june ******************/
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

		foreach($showlanguage as $key => $value){
			$name = $value['title'];			
			$language->addMultiOption($value['id'], $name);
		}
        $this->addElement($language);



/****************** end **********************/  
/************* Add Language Hidden field *****/

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
  


/******************** end***************/
		
		//// project field select box
		
	  $ministry = new Zend_Form_Element_Select('ministry_id',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
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
			$ministry->addValidators (array($required));
          
        $objministry = new Application_Model_DbtMinistry; 
	 	$showministryname = $objministry->roleuser();   
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showministryname as $key => $value){
			$name = $value['ministry_name'];			
			$ministry->addMultiOption($value['id'], $name);
		}
        $this->addElement($ministry);
		
		
		
 
//to add schme type
        $schmeType = new Zend_Form_Element_Select('scheme_type',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Benefit Type--',
            '1'    => 'Cash',
            '2'    => 'Kind',
            '3'    => 'Others',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
    $this->addElement($schmeType);
//add schme type end here

//to add schme group
        $schmeGroup = new Zend_Form_Element_Select('scheme_group',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme Group--',
            '1'    => 'PAHAL',
            '2'    => 'MGNREGS',
            '3'    => 'NSAP',
            '4'    => 'SCHOLARSHIP SCHEME',
            '5'    => 'OTHERS',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeGroup->addValidators (array($requiredtype));
    $this->addElement($schmeGroup);
//add schme group end here


//to add pfms on scheme
        $pfms = new Zend_Form_Element_Select('pfms',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select PFMS--',
            'yes'    => 'Yes',
            'no'    => 'No',                     
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$pfms->addValidators (array($requiredtype));
    $this->addElement($pfms);
//add pfms on scheme end here

//to add schme UT

	if($role->role == 2){
		$objstate = new Application_Model_Utscheme; 
		$utlist = $objstate->getutlist();

		$cmi_list = new Application_Model_Utscheme;
		$stateid = $cmi_list->getstateid($userid->userid);
		if ($stateid < 10){
			$stateid = '0'.$stateid;
		}
		$utname = new Zend_Form_Element_Select('ut_name',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
                                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
		$requiredtype = new Zend_Validate_NotEmpty ();
		$requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$utname->addValidators (array($requiredtype));
		   
			foreach($utlist as $key => $value){
				$name = $value['state_name'];
				if($value['state_code'] == $stateid){
					$utname->addMultiOption($value['state_code'], $name);
				}
			}
		$utname->setValue($stateid);
		$this->addElement($utname);
			
		
	} else {
        $utname = new Zend_Form_Element_Select('ut_name',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select UT Name--',                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
		$requiredtype = new Zend_Validate_NotEmpty ();
		$requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$utname->addValidators (array($requiredtype));
		$objstate = new Application_Model_Utscheme; 
			$utlist = $objstate->getutlist();   
			foreach($utlist as $key => $value){
				$name = $value['state_name'];			
				$utname->addMultiOption($value['state_code'], $name);
			}
		$this->addElement($utname);
	}
//add schme UT end here

//to add schme category
        $schmeCategory = new Zend_Form_Element_Select('scheme_category',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
			'0'    => '--Select Type Of The Scheme--',
			'1'    => 'Central Sector Scheme',
			'2'    => 'State/UTs Scheme',    
			'3'    => 'Centrally Sponsored Scheme', 
			'4'    => 'District Scheme',                       
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeCategory->addValidators (array($requiredtype));
    $this->addElement($schmeCategory);
//add schme category end here


//About scheme description
        $this->addElement('textarea', 'description', array( 			
            'required'   => true,
			 //'class'   => 'mceEditor',
			 //'id' => 'mceEditor',
                           'class'   => 'form-control',
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

            $this->addElement('file', 'uploadscheme', array( 
                            'label'      => '',
                            'required'   => false,
                            'id' => 'uploadscheme',
                            ));
        
   /*     
//to add file type in scheme
        //to add schme type
        $filename = new Zend_Form_Element_Select('file', 'filename', array(          
            'required'   => true,
             'class'   => 'filename',
             'id' => 'filename',
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
            'validators' => array(
                array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'file can\'t be empty'
                    )
                )),),          
  ));
    * */
   
//add schme type end here
//filetype add end here



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
	
	public function editform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.'))) */
    ),          
  ));
  
  
  
/************ Add language Element 28th june ******************/
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

		foreach($showlanguage as $key => $value){
			$name = $value['title'];			
			$language->addMultiOption($value['id'], $name);
		}
        $this->addElement($language);



/****************** end **********************/  
/************* Add Language Hidden field *****/

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
  


/******************** end***************/
		
		//// project field select box
		
	  $ministry = new Zend_Form_Element_Select('ministry_id',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
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
			$ministry->addValidators (array($required));
          
        $objministry = new Application_Model_DbtMinistry; 
	 	$showministryname = $objministry->roleuser();   
	 	// echo "<pre>";
	 	// print_r($showministryname);
	 	// echo "</pre>";
	 	// exit;
		foreach($showministryname as $key => $value){
			$name = $value['ministry_name'];			
			$ministry->addMultiOption($value['id'], $name);
		}
        $this->addElement($ministry);
		
		
		
 
//to add schme type
        $schmeType = new Zend_Form_Element_Select('scheme_type',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme Type--',
            '1'    => 'Cash',
            '2'    => 'Kind',
            '3'    => 'Others',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
    $this->addElement($schmeType);
//add schme type end here

//to add schme group
        $schmeGroup = new Zend_Form_Element_Select('scheme_group',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme Group--',
            '1'    => 'PAHAL',
            '2'    => 'MGNREGS',
            '3'    => 'NSAP',
            '4'    => 'SCHOLARSHIP SCHEME',
            '5'    => 'OTHERS',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeGroup->addValidators (array($requiredtype));
    $this->addElement($schmeGroup);
//add schme group end here


//to add pfms on scheme
        $pfms = new Zend_Form_Element_Select('pfms',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select PFMS--',
            'yes'    => 'Yes',
            'no'    => 'No',                     
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$pfms->addValidators (array($requiredtype));
    $this->addElement($pfms);
//add pfms on scheme end here


//to add schme UT
        $utname = new Zend_Form_Element_Select('ut_name',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select UT Name--',                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs'    => array('disabled' => 'disabled')
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$utname->addValidators (array($requiredtype));
	$objstate = new Application_Model_Utscheme; 
	 	$utlist = $objstate->getutlist();   
		foreach($utlist as $key => $value){
			$name = $value['state_name'];			
			$utname->addMultiOption($value['state_code'], $name);
		}
    $this->addElement($utname);
//add schme UT end here

//to add schme category
        $schmeCategory = new Zend_Form_Element_Select('scheme_category',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
				'0'    => '--Select Type Of The Scheme--',
				'1'    => 'Central Sector Scheme',
				'2'    => 'State/UTs Scheme',    
				'3'    => 'Centrally Sponsored Scheme', 
				'4'    => 'District Scheme',                    
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
    $requiredtype = new Zend_Validate_NotEmpty ();
    $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeCategory->addValidators (array($requiredtype));
    $this->addElement($schmeCategory);
//add schme category end here


//About scheme description
        $this->addElement('textarea', 'description', array( 			
            'required'   => true,
			 //'class'   => 'mceEditor',
			 //'id' => 'mceEditor',
                           'class'   => 'form-control',
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

            $this->addElement('file', 'uploadscheme', array( 
                            'label'      => '',
                            'required'   => false,
                            'id' => 'uploadscheme',
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
