<?PHP


class Application_Form_Schemecodification extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
echo $userid->userid; 
}

public function addform()
	{
$role = new Zend_Session_Namespace('role');
$role = $role->role;
/*********text box for the scheme name******/
		
				$this->addElement('text', 'scheme_name', array( 			
					'required'   => true,
					'class'   => 'form-control',
					'filters'    => array('StringTrim'),
					'minlength'  => '3',
					'maxlength'  => '250',
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
								  array('/^[a-zA-Z0-9., \'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.')))
			),          
		  ));
  
/*********end************************/
/***********dropdown for location************************************/
$state = new Zend_Form_Element_Select('state',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select State--', 
            '99'    =>  'Central',				
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
         ));
             //$opted = $ministryget->selectUserstate($userid->userid);
            //foreach($opted as $list) {
                //$idstate = $list->state;
            //}
	   $ministryget = new Application_Model_Diststateschemereport();
        $stateData = $ministryget->selectstate();
            foreach($stateData as $list) {
                $name = ucfirst($list->state);
                $state->addMultiOption($list->id, $name);
        }
        $this->addElement($state);
/********end******************************************************/
/***********dropdown for ministry*********************************/
		//// project field select box
		if($role == 2)
        {
	  $ministry = new Zend_Form_Element_Select('ministry_id',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Department--',
                      
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
		}
		else
		{
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
		}
		
		/*******************end*****************/
		
/*********add for the schme type info********/

/**********set acess for application admin and the central administrator***************/
if($role == 1 || $role == 3)
{
	
  $schmeType = new Zend_Form_Element_Select('scheme_type_info',array(  
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
	$schmeType->addValidators (array($requiredtype));
        $this->addElement($schmeType);
		
}
else if($role == 6)
{
	  $schmeType = new Zend_Form_Element_Select('scheme_type_info',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Type Of The Scheme--',
            '1'    => 'Central Sector Scheme', 
            '3'    => 'Centrally Sponsored Scheme', 			
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
        $this->addElement($schmeType);
}
else if($role == 2)
{
	
		  $schmeType = new Zend_Form_Element_Select('scheme_type_info',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Type Of The Scheme--',
             '2'    => 'State/UTs Scheme',  
            '3'    => 'Centrally Sponsored Scheme', 				 
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
		  $schmeType->setValue("2");	
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
        $this->addElement($schmeType);
	
	
	
	
}
else if($role == 5)
{
	
		  $schmeType = new Zend_Form_Element_Select('scheme_type_info',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Type Of The Scheme--',
            '4'    => 'District Scheme',   			
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
      $schmeType->setValue("4");	
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$schmeType->addValidators (array($requiredtype));
        $this->addElement($schmeType);
	
	
}	

/************end************************/
  
  
  
  	$this->addElement('textarea', 'description', array(
			 'required'   => true,
			 //'class'   => 'mceEditor',
			 //'id' => 'mceEditor',
                 'class'   => 'form-control',
			// 'id' => 'editor1',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10000',
			'rows'  => '5',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme Description field can\'t be empty'
                    )
                ))  , 
             array('Regex',
                        true,
                          array('/^[a-zA-Z0-9~`!#$^*_:?()@., \"\'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Scheme Description field has special characters.Please remove them and try again.'))) 
			),        
		));
	  /**********add a captcha ********/
	  
/*************add city name ********************/
	  $city_collect = new Zend_Form_Element_Select('cityname',array(       
            'label'        => '',
		'class'   => 'form-control',

            'multiOptions' => array(
            '0'    => '--Select District--',          
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ), 
		   
'registerInArrayValidator' => false,	   
        ));
    //     echo "bb";
    // die;
     
//state entry end here
 
    $this->addElement($city_collect);
	
	
	/*******************end**************/
	
	/********add dropdown**********************/
	
		/***********dropdown for location************************************/
$mincss = new Zend_Form_Element_Select('minnamecode',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select centrally sponsored  ministry--',                  
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
         ));
             //$opted = $ministryget->selectUserstate($userid->userid);
            //foreach($opted as $list) {
                //$idstate = $list->state;
            //}
	   $ministryget = new Application_Model_Schemecodification();
        $minData = $ministryget->selectministryschemegenerated();


            foreach($minData as $list) {
                $name = ucfirst($list->ministryname);
                $mincss->addMultiOption($list->ministry_id, $name);
        }
        $this->addElement($mincss);
/********end******************************************************/



/*************add ministry scheme name ********************/
	  $minschemenamecollect = new Zend_Form_Element_Select('schmnm',array(       
            'label'        => '',
		'class'   => 'form-control',

            'multiOptions' => array(
            '0'    => '--Select Centrally sponsored scheme--',          
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ), 
		   
'registerInArrayValidator' => false,	   
        ));
     
//state entry end here
 
    $this->addElement($minschemenamecollect);
	
	
	/*******************end**************/
	
	/***************end************************/
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
	  /***********end*************/
  
  $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
		
	}
}