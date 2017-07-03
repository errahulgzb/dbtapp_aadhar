<?PHP


class Application_Form_Assignmanager extends Zend_Form
{

    public function init()
    {
    // Set the method for the display form to POST
    }

    public function addform()
    {
        $this->setMethod('post');
        $customer_collect = new Zend_Form_Element_Select('projectmanager',array( 			
                                    'label'        => '',
                                    'required'   => true,
									 
                                    'multiOptions' => array(
                                    '0'    => 'Select Manager', 
                                ),
                                'decorators'=>Array(
                                    'ViewHelper','Errors'
                               ),
                            ));
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$customer_collect->addValidators (array ($required));          
	$role = new Application_Model_Assignmanager; 
	//$showusername  = $role->userList();
        $showusername  = $role->userListAdd();
        foreach($showusername as $key => $value)
        {
            $name = $value['firstname'].' '.$value['lastname'];			
            $customer_collect->addMultiOption($value['id'], $name);
        }
        $this->addElement($customer_collect);
        
        
        
	$role_collect = new Zend_Form_Element_MultiCheckbox('projectname',array( 			
                    'label'        => '',
		    'required'   => true,
                    'disableLoadDefaultDecorators' => true,
                    'separator' => '&nbsp;',
                    'registerInArrayValidator' => false,
                    'multiOptions' => array(),
		    'decorators'=>Array(
                        'ViewHelper','Errors'
		   ) ,
		 ));
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        $role_collect->addValidators (array ($required));
        $role = new Application_Model_Assignmanager; 
        $pmArray11=$role->pidList();
        $showprojectname=$pmArray11->toArray();
        foreach($showprojectname as $key => $value)
        {
            $name = $value['title'];
            $role_collect->addMultiOption($value['id'], $name);
        }
        $this->addElement($role_collect);

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));	
    }
    
  
 /*****************************************************************************/    
    public function editform()
    {
        $role = new Application_Model_Assignmanager;
        
        $this->setMethod('post');
        $customer_collect = new Zend_Form_Element_Select('projectmanager',array( 			
                        'label'        => '',
                        'required'   => true,
						'readonly'  => "readonly",
						 'class'   => 'form-control',
                        'multiOptions' => array(
                       // '0'    => 'Select Manager', 
                    ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		   'attribs' => array('readonly' => 'readonly')
		 ));
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$customer_collect->addValidators (array ($required));
        $showrolename  = $role->userListEdit($_GET['id']);
        foreach($showrolename as $key => $value){
            $name = $value['firstname'].' '.$value['lastname'];			
            $customer_collect->addMultiOption($value['id'], $name);
        }
        $pmArray=$role->assignedmanager($_GET['id']);
		//print_r($pmArray);
		$pmArrayList=$pmArray->toArray();
        $customer_collect->setValue($pmArrayList['id']);
        $this->addElement($customer_collect);
		
		
	$role_collect = new Zend_Form_Element_MultiCheckbox('projectname',array(	    'label' => '',
		    'required' => true,
            'registerInArrayValidator' => false,
			'disableLoadDefaultDecorators' => true,
			'separator' => '&nbsp;',
            'multiOptions' => array(),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        $role_collect->addValidators (array ($required));
        //echo $_GET['id'];exit;
        $pmArray11=$role->selectpidList($_GET['id']);
        $showprojectname=$pmArray11->toArray();
        //echo "<pre>";
       // print_r($showprojectname);exit;
        foreach($showprojectname as $key => $value)
        {
            $name = $value['scheme_name'];
            $role_collect->addMultiOption($value['id'], $name);
        }
        $pmArray1=$role->showAssignManager($_GET['id']);
        $pmArrayList1=$pmArray1->toArray();
        if($pmArrayList1[0]['scheme_id']){
            $role_collect->setValue(explode(",",$pmArrayList1[0]['scheme_id']));
        }
        //$this->formSelect('projectname', array('disable' => explode(",",$pmArrayList1[0]['project_id'])), $role_collect);
        //$this->setAttrib('disable', array(1, 2,3,4,5,6,7,8,9,10 ));
        $this->addElement($role_collect);

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
