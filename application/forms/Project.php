<?PHP


class Application_Form_Project extends Zend_Form
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

 // $this->addElement('text', 'plan_of_act', array( 			
           
	// 		 'class'   => 'validate[required] text-input',
 //            'filters'    => array('StringTrim'),
                       
	// 		'decorators'=>Array(
	// 		'ViewHelper','Errors'
	// 	   ),'validators' => array(

	// 		    array('notEmpty', true, array(
 //                    'messages' => array(
 //                        'isEmpty'   =>  'POA field can\'t be empty'
 //                    )
 //                ))),
           

          
 //        ));	


		
	   $this->addElement('textarea', 'details', array( 			
           
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
                       
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Details field can\'t be empty'
                    )
                ))),
  
        ));	
		
		
	  // $role_collect = new Zend_Form_Element_Select('customer',array( 			
            
		    
   //          'label'        => '',
		 //    'required'   => true,
   //          'multiOptions' => array(
   //          '0'    => 'Select Customer',
                      
   //      ),
			
		 //    'decorators'=>Array(
			// 'ViewHelper','Errors'
		 //   ),
		     
		
		 // ));


  //    $required = new Zend_Validate_NotEmpty ();
  //    $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	 // $role_collect->addValidators (array ($required));
          
  //       $role = new Application_Model_Project; 
	 // 	$showrolename  = $role->roleuser($_GET['id']);

		// //echo "<pre>";
		// 		//print_r($showrolename);
		// 		//echo "</pre>";
		// 		//die;

		
    
		// foreach($showrolename as $key => $value)
		// 	{
		// 	$name = $value['organisation'];
			
		// 	$role_collect->addMultiOption($value['id'], $name);
		
		// 	}

  //       $this->addElement($role_collect);
		
		
		
		// date picker.......
		
			 // Start Date input box
		// $this->addElement('text', 'start_date', array(
  //           'label'      => '',
  //          'id'         => 'projectdate11',
		// 	'filters'    => array('StringTrim'),
  //           'class' => 'button',
		// 	'placeholder'=>'Start Date',
		// 	'style'    => array('width:100px'),
		// 	'attribs' => array('readonly' => 'true'),
		// 	 'autocomplete' => 'off',
		// 	'decorators'=>Array(
		// 	'ViewHelper',
		// 	'Errors',
			
		//    ),'validators' => array(

		// 	    array('notEmpty', true, array(
  //                   'messages' => array(
  //                       'isEmpty'   =>  'Start date can\'t be empty'
  //                   )
  //               ))),
			
   
            
  //       ));


		// $this->addElement('text', 'end_date', array(
  //           'label'      => '',
  //          'id'         => 'projectdate12',
  //           'filters'    => array('StringTrim'),
  //           'class' => 'button',
		// 	'placeholder'=>'End date',
		// 	'style'    => array('width:100px'),
		// 	'attribs' => array('readonly' => 'true'),
		// 	 'autocomplete' => 'off',
		// 	'decorators'=>Array(
		// 	'ViewHelper',
		// 	'Errors',
			
		//    ),'validators' => array(

		// 	    array('notEmpty', true, array(
  //                   'messages' => array(
  //                       'isEmpty'   =>  'End date can\'t be empty'
  //                   )
  //               ))),
		
   
            
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
