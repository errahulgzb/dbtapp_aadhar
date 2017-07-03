<?PHP
class Application_Form_SearchSurvey extends Zend_Form
{
	public function init()
	{
		// Set the method for the display form to POST
	}

	public function addform()
	{

			// Set the method for the display form to POST
			$this->setMethod('get');

			//echo "<pre>";
			//print_r($_GET);
			//echo "</pre>";
			$this->addElement('radio', 'Status', array(
				'required'   => true,
                                'separator' => '&nbsp;',
				'multiOptions' => array(
					'1' => IN_PROGRESS,
					'2' => 'Closed'
					
				),
					'separator' => '',
				'value' => '1' //key of multiOption
			));

		
		
		$this->addElement ( 
    'multiCheckbox', 'Functional_type', 
    array (
        
		//'setrequired'   => true,
        'multiOptions' => array(
                    '1' => 'Pre Installation',
                    '2' => 'Installation',
                    '3' => 'Post Installation'
                   
                    ),
        'separator' => '',
					//'value' => '2' // select these 2 values
    )
);
	
				
			$this->addElement('submit', 'submit', array(
					'ignore'   => true,
					'label'    => 'Submit',
			));
			
			// And finally add some CSRF protection
			$this->addElement('hash', 'csrf', array(
					'ignore' => true,
			));	
	}

/*
public function isValidPartial($formData)
    {
      
		//call the parent method for basic form validation
        $isValid = parent::isValidPartial($formData);
 
        if($isValid)
        {
            //custom validation
           

				
		 if ($this->getId('checkbox_1') != '1' && $this->getValue('checkbox_2') != '1') {
             $this->getElement('checkbox_1')->setErrors(array('You  have to set check at least chexbkox_1 or checkbox 2'));
             $isValid = false;
		 }
			
				
        }
 
        return $isValid;
    }


*/



}
