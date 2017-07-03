<?PHP
class Application_Form_User extends Zend_Form{
public function init(){
// Set the method for the display form to POST
}
public function addform(){
	$rolearr = new Zend_Session_Namespace('role');
		// Set the method for the display form to POST
        $this->setMethod('post');
       $this->addElement('text', 'username', array( 			
           'required'   => true,
		   'autocomplete' => 'off',
           'class'   => 'form-control',
		   'placeholder' => 'Username',
           'filters'    => array('StringTrim'),
			'maxlength'  => '50',
                        'decorators'=>Array(
                            'ViewHelper','Errors'
                        ),
			   'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Username can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9._, \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'User name field has special characters.Please remove them and try again.')))
                 ),
        ));
			
        $this->addElement('password', 'password', array(
            'label'      => '',
			'autocomplete' => 'off',
            'required'   => true,
            'class'   => 'form-control',
			'placeholder' => 'Password',
            'filters'    => array('StringTrim'),
			'size'     =>'37',
			'maxlength'  => '50',
			'id'       =>'password',
			 'autocomplete' => 'off',
			
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
			  'validators' => array(
			   array('Regex',
                        false,
                          array('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{8,30})', 'messages'=>array(
'regexNotMatch'=>'Your password quality is too bad. Please use some special character,Numbers,Upper case Letter and Lower case letter!')))),
        ));
		
		
		
		


        $this->addElement('text', 'firstname', array( 			
            'required'   => true,
			'autocomplete' => 'off',
            'class'   => 'form-control',
			'placeholder' => 'First Name',
            'filters'    => array('StringTrim'),
			'maxlength'  => '30',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			   'validators' => array(

			   array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'First name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'First name field has special characters.Please remove them and try again.')))
                 ),
            
          
        ));

		$this->addElement('text', 'lastname', array(            
            'required'   => true,
			'autocomplete' => 'off',
            'filters'    => array('StringTrim'),
			'placeholder' => 'Last Name',
			'class'   => 'form-control',
			'maxlength'  => '30',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			   'validators' => array(
                   
		   array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Last name can\'t be empty'
                    )
                )),
		   array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Last name field has special characters.Please remove them and try again.')))
				
		     

                 ),
            
          
        ));

		$this->addElement('text', 'mobile', array(           
            'required'   => true,
			'autocomplete' => 'off',
            'filters'    => array('StringTrim'),
			'class'   => 'form-control',
			'placeholder' => 'Mobile',
			'maxlength'  => '10',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
         'validators' => array(
                                array('Digits', false, array(
                    'messages' => array(
                        'notDigits'     => "Phone Invalid Digits, ex. 1234567890",
                        'digitsStringEmpty' => "",
                    ))),
                array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Mobile No. can\'t be empty'
                    )
                )),
                array('StringLength', false, array(10, 10, 'messages' => array(
                            'stringLengthInvalid'           => "Phone Length Invalid entry",
                            'stringLengthTooShort'          => "Phone Invalid Length , ex. 1234567890"
                    ))),
            ),

          
        ));

		$this->addElement('text', 'email', array( 
			'label'      => '',
			'autocomplete' => 'off',
            'required'   => true,
            'filters'    => array('StringTrim'),
			'placeholder' => 'Email-Id',
			'maxlength'  => '50',
			'class'   => 'form-control',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Email id field can\'t be empty'
                    )
                )),
                'EmailAddress',
            )
        ));
		
		$this->addElement('textarea', 'address', array( 
			          
            'filters'    => array('StringTrim'),			
			'class'   => 'form-control',			
			'decorators'=>Array(
			'ViewHelper','Errors',
			
		   ),
            
        ));
		
		
	$this->addElement('text', 'telephone', array(           
            
            'filters'    => array('StringTrim'),
			'class'   => 'form-control',
			'placeholder' => 'Telephone',
			
			'maxlength'  => '12',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
         'validators' => array(
                                array('Digits', false, array(
                    'messages' => array(
                        'notDigits'     => "Phone Invalid Digits, ex. 1234567890",
                        'digitsStringEmpty' => "",
                    ))),
            ),

        ));

    //this field is use for the add states into the form as multioption
        $state_collect = new Zend_Form_Element_Select('statename',array(       
            'label'        => '',
		'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Select State==',          
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
		
		   $state = new Application_Model_DbtState; 
        $showstatename = $state->statesget();
//echo "<pre>";print_r($showstatename);die;
        foreach($showstatename as $key => $value){
            $state_collect->addMultiOption($value['state_code'], $value['state_name']);
			}
        $this->addElement($state_collect);

$city_collect = new Zend_Form_Element_Select('cityname',array(       
            'label'        => '',
		'class'   => 'form-control',

            'multiOptions' => array(
            '0'    => '==Select District==',          
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
   //getting here the Ministry Name from the USer Model and display against to the Minisry User Role 
		$ministry_collect = new Zend_Form_Element_Select('ministry_name',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Select Ministry==',          
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));		
		$user = new Application_Model_User; 
        $showministryname = $user->Getministry();
        foreach($showministryname as $key => $value){
            $ministry = $value['ministry_name'];  
            $ministry_collect->addMultiOption($value['id'], $ministry);        
        }
        $this->addElement($ministry_collect);
		
		
		
		
 //getting here the Ministry Name from the USer Model and display against to the Minisry User Role End here
			$role_collect = new Zend_Form_Element_Select('name',array( 
				'label'        => '',
				'required'   => true,
				'class'   => 'form-control',
				'multiOptions' => array(
				'0'    => '==Select==',       
			),			
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),    
			  
			));
			$required = new Zend_Validate_NotEmpty ();
			$required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
			$role_collect->addValidators (array ($required));
			$role = new Application_Model_Role; 
			//$showrolename  = $role->roleuser($_GET['id'],$limit=0);
			//echo $rolearr->role;exit;
			if($rolearr->role == 6){
				$showrolename  = $role->schemeownerrole();
				foreach($showrolename as $key => $value){
						if($value['id']!=12){
					$name = $value['title'];
					$role_collect->addMultiOption($value['id'], $name);
					}
				}
			}else if($rolearr->role == 4){
				$showrolename  = $role->stateownerrole();
				foreach($showrolename as $key => $value){
					$name = $value['title'];
					$role_collect->addMultiOption($value['id'], $name);
				}
			}else{
				$showrolename  = $role->roleuser($_GET['id'],$limit=0);
				foreach($showrolename as $key => $value){
					if($value['id']!=12){
					$name = $value['title'];
					$role_collect->addMultiOption($value['id'], $name);
					}
				}
			}
			$this->addElement($role_collect);
		/* end */





		
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

		/*  end */
	}
}
