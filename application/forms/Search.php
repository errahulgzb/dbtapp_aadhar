<?PHP


class Application_Form_Search extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('get');
		//	echo "<pre>";
		//	print_r($_GET);
		//	echo "</pre>";
       $this->addElement('text', 'name', array( 			
            //'required'   => true,
			 'class'   => 'validate[required] text-input',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            
          
        ));
			
			 
			 
			 $list_folder_collect = new Zend_Form_Element_Select('folder',array( 			
            
		    
            'label'        => '',
		//'required'   => true,
            'multiOptions' => array(
            '0'    => 'All Folders',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist = new Application_Model_Folder; 
	 	$showfoldername  = $folderlist->displayfolderlist();


	
			 
  //echo $detailspage['title'];
			/*	echo "<pre>";
				print_r($showfoldername);
				echo "</pre>";
				die;*/
		foreach($showfoldername as $key => $value)
			{
				//$name = $value['name'];
			//echo $name;
		//	die;
				$list_folder_collect->addMultiOption($value['fid'],$value['name']);
			}
//echo $_GET['id'];
//die;
// $list_folder_collect->setValue();
//echo $role_collect;
//die;$this->addElement($role_collect);
        $this->addElement($list_folder_collect);
		
		// date picker.......
		
			 // Start Date input box
		$this->addElement('text', 'startDate', array(
            'label'      => '',
           'id'         => 'datepicker',
			'filters'    => array('StringTrim'),
            'class' => 'button',
			//'style'    => array('width:100px'),
			'attribs' => array('readonly' => 'true'),
			 'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
			
   
            
        ));


		$this->addElement('text', 'endDate', array(
            'label'      => '',
           'id'         => 'datepicker1',
            'filters'    => array('StringTrim'),
            'class' => 'button',
			//'style'    => array('width:100px'),
			'attribs' => array('readonly' => 'true'),
			 'autocomplete' => 'off',
			'decorators'=>Array(
			'ViewHelper',
			'Errors',
			
		   ),
		
   
            
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



 public function isValidPartial($formData)
    {
      
		//call the parent method for basic form validation
        $isValid = parent::isValidPartial($formData);
 
        if($isValid)
        {
            //custom validation
           

				
			 if(($formData['startDate'] != "")&& ($formData['endDate'] != ""))
            {
              // echo "aaa";
			   if(($formData['endDate'] ) < ($formData['startDate'])){

			   $this->endDate->setErrors(array('End Date cannot be smaller than Start Date.'));
			   $isValid = false;
			   }
				
            }

			
				
        }
 
        return $isValid;
    }





}
