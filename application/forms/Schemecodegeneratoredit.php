<?PHP


class Application_Form_Schemecodegeneratoredit extends Zend_Form
{

public function init()
{

// Set the method for the display form to POST
}

public function addform()
	{
		 $ministryget = new Application_Model_Schemereport();
		// Set the method for the display form to POST
		$this->setMethod('post');
		
        $this->addElement('text', 'scheme_code', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',
			'readonly' => 'readonly',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme code is required and can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[A-Za-z0-9]*$/', 'messages'=>array('regexNotMatch'=>'Scheme code field will only accept numeric value.')))
                 ),
        ));
		
		$this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '45',
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
'regexNotMatch'=>'Scheme name field has special characters.Please remove them and try again.')))
    ),          
  ));  
  //echo "aaaaa";exit;
  $locator = new Zend_Form_Element_Select('locator',array(  
            'label'        => '',
		    'required'   => true,
              'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select locator',
			'1' => 'State',
			'2' => 'Central',
			'3' => 'Joint',       
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$locator->addValidators (array($required));
		$this->addElement($locator);
		
		
		
		
	  $state_name = new Zend_Form_Element_Select('state_id',array( 			   
                    'label'        => '',
					'required'   => true,
					'class'   => 'form-control',
                    'multiOptions' => array(
                    '0'    => 'Select State',
					'41'    => 'National',

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
		$schemestatename = new Application_Model_Schemecodegen; 
		$state_id  = $schemestatename->stateid($_GET['id']);
		$state  = $schemestatename->statename($state_id);
		foreach($statelist as $key => $value)
		{
		$name = $value['state_name'];			
		$state_name->addMultiOption($value['id'], $name);
		}
		//echo $state_id; die;
		if($state_id == 41)
		{
			$state_name->setValue('41');
		}
		else
		{
		$state_name->setValue($state['id']);
		}
		$this->addElement($state_name);
		
		$role_collect = new Zend_Form_Element_Select('ministry',array( 			
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
		 $schemeministry = new Application_Model_Schemecodegen; 
		 $minisy = $schemeministry->ministryid($_GET['id']);
		$showdetails = $role->edituserclient($minisy);
		foreach($showrolename as $key => $value)
			{
			$name = $value['ministry_name'];
			$role_collect->addMultiOption($value['id'], $name);
			}		
			
		$role_collect->setValue($showdetails['id']);

        $this->addElement($role_collect);

		
		$components_code = new Zend_Form_Element_Select('components_code',array(  
            'label'        => '',
		    'required'   => true,
                    'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select locator',
			'1' => 'DBT',
			'2' => 'Non DBT',
			       
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		 
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$components_code->addValidators (array($required));
		$this->addElement($components_code);
		
		$this->addElement('text', 'sponsored_scheme_bank', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '70',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Sponsored scheme bank can\'t be empty'
                    )
                ))
                    /* array('Regex',
                        false,
                          array('/^[A-Za-z0-9]$/', 'messages'=>array(
'regexNotMatch'=>'Sponsored scheme bank field has special characters.Please remove them and try again.'))) */
    ),          
  ));
  
  $this->addElement('text', 'sponsored_acc_no', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '45',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Sponsored Account no can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[A-Za-z0-9]*$/', 'messages'=>array(
'regexNotMatch'=>'Sponsored Account no field has special characters.Please remove them and try again.')))
    ),          
  ));
  
    /***********Add Scheme Type*************/
  
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
		   ),
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
        $this->addElement($schmeType);
  
  
  /*************end************/
  /*********Add Scheme Description********/
  
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
  
  /*************end****************/
  
  $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
		
	}
}