<?PHP


class Application_Form_Group extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'name', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));

		$this->addElement('textarea', 'description', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',
			'rows'=>'4',
			'columns'=>'50',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));



 $role_collect = new Zend_Form_Element_MultiCheckbox('check',array( 			
            
		    
            'label'        => '',
		'required'   => true,
            'multiOptions' => array(
       //     '0'    => '---Select---',
                      
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));



        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

		$user = new Application_Model_User; 
	 	$showusername  = $user->userlist($_GET['id']); 
		//echo "<pre>";
			//	print_r($showusername);
				//echo "</pre>";
				//die;
		foreach($showusername as $key => $value)
			{
			
					$name = $value['username'];
					//echo $name;
					//die;
					$role_collect->addMultiOption($value['uid'],$name);
				   
			}
			$this->addElement($role_collect); 
	}

}
