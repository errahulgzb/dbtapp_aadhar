<?PHP


class Application_Form_Editfolder extends Zend_Form
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

		  // Add the description element
        $this->addElement('textarea', 'description', array(            
            'required'   => true,
			
			'class'   => 'validate[required] text-input',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 500))
                )
        ));



		  $folder_collect = new Zend_Form_Element_Select('display_status',array( 
		//  $this->addElement('radio', 'display_status', array(
				'required'   => true,
			 'class'   => 'validate[required] text-input',
				'label'=>'Membership Status:',
							
			  'decorators'=>Array(
			'ViewHelper','Errors'
		   ),

		'multiOptions'=>array(
        ''    => '---Select---',
		'1' => 'Public',
        '0' => 'Private'
		)
		
		));
		

		$edit_show = new Application_Model_Folder;
		echo $_GET['fid'];
		//die;
	$showdetails = $edit_show->editfolderclient($_GET['fid']);
			//echo "aaa";
			//die;
			echo $showdetails['fid'];
			echo $showdetails['pub_pri'];
			//die;
		
				 $folder_collect->setValue($showdetails['pub_pri']);
echo $folder_collect;
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
