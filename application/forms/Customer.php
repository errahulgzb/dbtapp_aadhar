<?PHP


class Application_Form_Customer extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');




/************************************************************/
$list_folder_collect = new Zend_Form_Element_Select('customer',array( 			
            
		    'id'  => 'customer',
            'label'        => '',
			'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Customer',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist = new Application_Model_Customer; 
	 	$showfoldername  = $folderlist->displayfolderlist();

	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername as $key => $value)
			{
				$name = $value['organisation'];
				$list_folder_collect->addMultiOption($value['id'],$name);
			}

        $this->addElement($list_folder_collect);

/************************************************************/




/************************************************************/
		$caste_collect = new Zend_Form_Element_Select('project',array( 			
            
		    'id' => 'project',
            'label'        => '',
				'registerInArrayValidator' => false,
			'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Project',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist1 = new Application_Model_Customer; 
	 	$showfoldername1  = $folderlist1->show1();

	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername1 as $key => $value)
			{
				
				$caste_collect->addMultiOption($value['id'],$value['title']);
			}

        $this->addElement($caste_collect);


/************************************************************/

/************************************************************/


	$location_collect = new Zend_Form_Element_Select('location',array( 			
            
		    'id' => 'location',
            'label'        => '',
				'registerInArrayValidator' => false,
		    'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Location',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist2 = new Application_Model_Customer; 
	 	$showfoldername2  = $folderlist2->location();

	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername2 as $key => $value)
			{
				
				$location_collect->addMultiOption($value['id'],$value['title']);
			}

        $this->addElement($location_collect);

		

/************************************************************/


/************************************************************/

		$site_collect = new Zend_Form_Element_Select('site',array( 			
            
		    'id' => 'site',
            'label'        => '',
		    'required'   => true,
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => 'Select Site',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist3 = new Application_Model_Customer; 
	 	$showfoldername3  = $folderlist3->site();

	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername3 as $key => $value)
			{
				
				$site_collect->addMultiOption($value['id'],$value['title']);
			}

        $this->addElement($site_collect);

/************************************************************/


/************************************************************/
$survey_collect = new Zend_Form_Element_Select('survey',array( 			
            
		    'id'  => 'survey',
            'label'        => '',
		     'required'   => true,
            'multiOptions' => array(
            '0'    => 'Select Installation Engineer',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist4 = new Application_Model_Customer; 
	 	$showfoldername4  = $folderlist4->survey();

	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername4 as $key => $value)
			{
				$name = $value['firstname'].' '.$value['lastname'];
				$survey_collect->addMultiOption($value['id'],$name);
			}

        $this->addElement($survey_collect);

/************************************************************/


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



