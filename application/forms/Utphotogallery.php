<?PHP


class Application_Form_Utphotogallery extends Zend_Form
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
						array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array('regexNotMatch'=>'Title '.NUMERICVALIDATION)))
				),
			));

			
			
			//to add schme UT
		
	if($role->role == 2){
		$objstate = new Application_Model_Utscheme; 
		$utlist = $objstate->getutlist();
		$cmi_list = new Application_Model_Utscheme;
		$stateid = $cmi_list->getstateid($userid->userid);
		
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
            '0'    => '--Select Category--',                      
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
			
			$language = new Zend_Form_Element_Select('language',array(  
				'label'        => '',
				'required'   => true,
				'class'   => 'form-control',
				'multiOptions' => array(
					'0'    => 'Select Language',
					'1'    => 'Hindi',
					'2'    => 'English',
				),
				'decorators'=>Array(
				'ViewHelper','Errors'
				),
			
			 ));
			$required = new Zend_Validate_NotEmpty ();
			$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$language->addValidators (array($required));
			$this->addElement($language);
			

			$this->addElement('file', 'uploadimage', array( 
				'class'   => 'form-control',
				'label'      => '',
				'required'   => true,
				'id' => 'uploadimage',
				'validators' => array(
					array('notEmpty', true, array('messages' => array('isEmpty'   =>  'Upload image '.CANTEMPTY)))
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
