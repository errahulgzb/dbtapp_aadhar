<?PHP


class Application_Form_EditProject extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'title', array( 			
            'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Project name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Project name field has special characters.Please remove them and try again.')))
                 ),

          
        ));
		
		$this->addElement('textarea', 'details', array( 			
          
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
           

          
        ));	
		// $this->addElement('text', 'plan_of_act', array(
  //           'label'      => '',
  //          'id'         => 'plan_of_act',
		// 	'filters'    => array('StringTrim'),
            			
		// 	'decorators'=>Array(
		// 	'ViewHelper',
		// 	'Errors',
			
		//    ),
			
   
            
  //       ));
		
	 //  $role_collect = new Zend_Form_Element_Select('customer',array( 			
            
		    
  //           'label'        => '',
		//     'required'   => true,
  //           'multiOptions' => array(
  //           '0'    => 'Select Customer',
                      
  //       ),
			
		//     'decorators'=>Array(
		// 	'ViewHelper','Errors'
		//    ),
		
		//  ));


  //    $required = new Zend_Validate_NotEmpty ();
  //    $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
		// 	$role_collect->addValidators (array ($required));
          
  //       $role = new Application_Model_Project; 
	 // 	$showrolename  = $role->roleuser();
		// $showdetails = $role->edituserclient($_GET['id']);
		
		// //echo "<pre>";
		// 		//print_r($showrolename);
		// 		//echo "</pre>";
		// 		//die;

		
    
		// foreach($showrolename as $key => $value)
		// 	{
		// 	$name = $value['organisation'];
			
		// 	$role_collect->addMultiOption($value['id'], $name);
		
		// 	}

      
		
		
		
		// $role_collect->setValue($showdetails['customer_id']);

  //       $this->addElement($role_collect);
		
		
		
		// // date picker.......
		
		// 	 // Start Date input box
		// $this->addElement('text', 'start_date', array(
  //           'label'      => '',
  //          'id'         => 'projectdate',
		// 	'filters'    => array('StringTrim'),
  //           'class' => 'button',
		// 	'style'    => array('width:100px'),
		// 	'attribs' => array('readonly' => 'true'),
		// 	 'autocomplete' => 'off',
		// 	'decorators'=>Array(
		// 	'ViewHelper',
		// 	'Errors',
			
		//    ),
			
   
            
  //       ));


		// $this->addElement('text', 'end_date', array(
  //           'label'      => '',
  //          'id'         => 'projectdate1',
  //           'filters'    => array('StringTrim'),
  //           'class' => 'button',
		// 	'style'    => array('width:100px'),
		// 	'attribs' => array('readonly' => 'true'),
		// 	 'autocomplete' => 'off',
		// 	'decorators'=>Array(
		// 	'ViewHelper',
		// 	'Errors',
			
		//    ),
		
   
            
  //       ));
		 
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
