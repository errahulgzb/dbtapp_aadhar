<?PHP


class Application_Form_EditFolder extends Zend_Form
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
			'maxlength'  => '15',
			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),			   
		    'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Name field can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Name field has special characters.Please remove them and try again.'))),
                 ),
            
          
        ));

		  // Add the description element
        $this->addElement('textarea', 'description', array(            
            'required'   => true,
			'maxlength' =>'50',
			
			'class'   => 'validate[required] text-input',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Description field can\'t be empty'
                    )
                )),
                   





                 ),
        ));



		  $folder_collect = new Zend_Form_Element_Select('show',array( 
		//  $this->addElement('radio', 'display_status', array(
				'required'   => true,
			 'class'   => 'validate[required] text-input',
				'label'=>'Membership Status:',
							
			  'decorators'=>Array(
			'ViewHelper','Errors'
		   ),

		'multiOptions'=>array(
       
		'1' => 'Public',
        '0' => 'Private'
		)
		
		));
		

		$edit_show = new Application_Model_Folder;
		//echo $_GET['fid'];
		//die;
	$showdetails = $edit_show->editfolderclient($_GET['fid']);
			//echo "aaa";
			//die;
			//echo $showdetails['fid'];
			//echo $showdetails['pub_pri'];
			//die;
		
				 $folder_collect->setValue($showdetails['pub_pri']);
//echo $folder_collect;
			$this->addElement($folder_collect);
        
		
		
		// Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
		 $this->addElement('hidden', 'hidden', array(
            'ignore'   => true,
            'label'    => 'parent_id',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

		
	}

}
