<?PHP


class Application_Form_EditCustomer extends Zend_Form
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
            '0'    => '---Select Customer---',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist = new Application_Model_Customer; 
	 	$showfoldername  = $folderlist->displayfolderlist();
		$showdetails = $folderlist->edituserclient($_GET['id']);
	 ///echo $showdetails['user_id']; exit;
  
		foreach($showfoldername as $key => $value)
			{
				$name = $value['organisation'];
				$list_folder_collect->addMultiOption($value['id'],$name );
			}
		$list_folder_collect->setValue($showdetails['user_id']);
		
        $this->addElement($list_folder_collect);

/************************************************************/




/************************************************************/
		$caste_collect = new Zend_Form_Element_Select('project',array( 			
            
		    'id' => 'project',
            'label'        => '',
				'registerInArrayValidator' => false,
		     'required'   => true,
            'multiOptions' => array(
            '0'    => '---Select Project---',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist1 = new Application_Model_Customer; 
	 	$showfoldername1  = $folderlist1->showproject($_GET['id']);
		$showprojectname  = $folderlist1->selectedproject($_GET['id']);
	
			 
  
		foreach($showfoldername1 as $key => $value)
			{
				
				$caste_collect->addMultiOption($value['id'],$value['title']);
			}

		$caste_collect->setValue($showprojectname['project_id']);		
        $this->addElement($caste_collect);


/************************************************************/

/************************************************************/


	$location_collect = new Zend_Form_Element_Select('location',array( 			
            
		    'id' => 'location',
            'label'        => '',
				'registerInArrayValidator' => false,
		     'required'   => true,
            'multiOptions' => array(
            '0'    => '---Select Location---',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist2 = new Application_Model_Customer; 
	 	$showfoldername2  = $folderlist2->locationproject($_GET['id']);
		$showlocationname  = $folderlist2->selectedproject($_GET['id']);
	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername2 as $key => $value)
			{
				
				$location_collect->addMultiOption($value['id'],$value['title']);
			}

		$location_collect->setValue($showlocationname['location_id']);	
        $this->addElement($location_collect);

		

/************************************************************/


/************************************************************/

		$site_collect = new Zend_Form_Element_Select('site',array( 			
            
		    'id' => 'site',
            'label'        => '',
		     'required'   => true,
			'registerInArrayValidator' => false,
            'multiOptions' => array(
            '0'    => '---Select Site---',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist3 = new Application_Model_Customer; 
	 	$showfoldername3  = $folderlist3->locationsite($_GET['id']);
		$showprojectname  = $folderlist3->selectedproject($_GET['id']);
	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername3 as $key => $value)
			{
				
				$site_collect->addMultiOption($value['id'],$value['title']);
			}
		$site_collect->setValue($showprojectname['sites_id']);	
        $this->addElement($site_collect);

/************************************************************/


/************************************************************/
$survey_collect = new Zend_Form_Element_Select('survey',array( 			
            
		    'id'  => 'survey',
            'label'        => '',
		   'required'   => true,
            'multiOptions' => array(
            '0'    => '---Select Installation Engineer---',
                    
        ),
			
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			 
      
          
        ));
        
        $folderlist4 = new Application_Model_Customer; 
	 	$showfoldername4  = $folderlist4->survey();
		$showsurveyname  = $folderlist4->selectedproject($_GET['id']);
	//echo "<pre>";
			//	print_r($showfoldername);
			//	echo "</pre>";
			//	die;
	
			 
  
		foreach($showfoldername4 as $key => $value)
			{
				$name = $value['firstname'].' '.$value['lastname'];
				$survey_collect->addMultiOption($value['id'],$name);
			}
		$survey_collect->setValue($showsurveyname['survey_id']);	
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



