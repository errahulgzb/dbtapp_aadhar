<?PHP


class Application_Form_Feedback extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
		$this->setMethod('post');
		
        $this->addElement('text', 'scheme_code', array( 			
            'required'   => true,
		'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '10',

			
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
  
  $locator = new Zend_Form_Element_Select('locator',array(  
            'label'        => '',
		    'required'   => true,
                    'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select locator',
			'01' => 'State',
			'02' => 'Central',
			'03' => 'Joint',       
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$locator->addValidators (array($required));
		$this->addElement($locator);
		
		
		$state = new Zend_Form_Element_Select('state',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select State--', 
			'0' => 'National',                   
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
         ));
             $opted = $ministryget->selectUserstate($userid->userid);
            foreach($opted as $list) {
                $idstate = $list->state;
            }
        //$stateData = $ministryget->selectstate();
            foreach($stateData as $list) {
                $name = ucfirst($list->state);
                $state->addMultiOption($list->id, $name);
        }
        $this->addElement($state);
		
		$ministry = new Zend_Form_Element_Select('ministry',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Ministry--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
      //getting all the record from ministry  
        $ministryget = new Application_Model_Schemereport;
        $ministryData = $ministryget->selectministry();
            //echo "<pre>";
            //print_r($ministryData->toArray());
            //exit;
            foreach($ministryData as $list) {
                $name = ucfirst($list->ministry);
                $ministry->addMultiOption($list->id, $name);
            }
        $this->addElement($ministry);

		
		$component_code = new Zend_Form_Element_Select('component_code',array(  
            'label'        => '',
		    'required'   => true,
                    'class'   => 'form-control',
            'multiOptions' => array(
            '0' => 'Select locator',
			'01' => 'DBT',
			'02' => 'Non DBT',
			       
			),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		
		 ));
		$required = new Zend_Validate_NotEmpty ();
		$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		$component_code->addValidators (array($required));
		$this->addElement($component_code);
		
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
                )),
                    array('Regex',
                        false,
                          array('/^[A-Za-z0-9]$/', 'messages'=>array(
'regexNotMatch'=>'Sponsored scheme bank field has special characters.Please remove them and try again.')))
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
  
  $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
		
	}
}