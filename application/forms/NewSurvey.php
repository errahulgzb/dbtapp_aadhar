<?PHP
class Application_Form_NewSurvey extends Zend_Form
{
	public function init()
	{
		// Set the method for the display form to POST
	}

	public function addform()
	{

			// Set the method for the display form to POST
			$this->setMethod('post');

			
			$this->addElement('select', 'funct_type_start', array(
				'required'   => true,
				'multiOptions' => array(
					'0' => 'Activity Type',
					'1' => 'Pre Installation',
					'2' => 'Installation',
					'3' => 'Post Installation'
				),
				'value' => '0' //key of multiOption
			));
			/*			
			$this->addElement('text', 'name', array( 			
					'required'   => true,
					 'class'   => 'validate[required] text-input',
					'filters'    => array('StringTrim'),
					'maxlength'  => '15',
					'style'    => array('width:338px'),
					'decorators'=>Array(
						'ViewHelper','Errors'
					 ),  
			));
*/
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

			$this->addElement('textarea', 'actiontaken', array(            
					'required'   => true,
					'maxlength' =>'200',
					'style'    => array('width:338px;height:100px'),
					'class'   => 'validate[required] text-input',
					'decorators'=>Array(
						'ViewHelper','Errors'
					),		  
			));

			$this->addElement('textarea', 'poa', array(            
				'required'   => true,
				'maxlength' =>'200',
				'style'    => array('width:338px;height:100px'),
				'class'   => 'validate[required] text-input',
				'decorators'=>Array(
					'ViewHelper','Errors'
				),		  
			));

			

	$this->addElement('select', 'funct_type_follow', array(
				'required'   => true,
				'multiOptions' => array(
					'0' => 'Follow Up Activity',
					'1' => 'Pre Installation',
					'2' => 'Installation',
					'3' => 'Post Installation'
				),
				'value' => '0' //key of multiOption
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

			   $this->endDate->setErrors(array('Follow Up Date cannot be smaller than Activity Date.'));
			   $isValid = false;
			   }
				
            }

				 if(($formData['startDate'] == "")&& ($formData['endDate'] != ""))
            {
              // echo "aaa";
			  $today=date("m/d/Y");
			 // echo $formData['endDate'];
			  if(($formData['endDate'] ) < ($today) ) {

			  $this->endDate->setErrors(array('Follow Up Date cannot be smaller than Activity Date.'));
			   $isValid = false;
			   }
				
            }

			
				
        }
 
        return $isValid;
    }






}
