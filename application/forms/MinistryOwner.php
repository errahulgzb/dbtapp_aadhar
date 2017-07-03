<?PHP


class Application_Form_MinistryOwner extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');
		
		/*******Add Scheme Name***********/
		
		     $this->addElement('text', 'scheme_name', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '150',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.'))) */
    ),          
  ));
		
		
		/***********end***************/
		
		
		/**********Add Dbt eligibilty *********/
		//to add schme type
        $dbtEligible = new Zend_Form_Element_Select('dbt_eligible',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Dbt Eligibilty--',
            '1'    => 'Yes',
            '2'    => 'No',                  
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),w
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$dbtEligible->addValidators (array($requiredtype));
        $this->addElement($dbtEligible);
		
		/************end****************/
		
		
		
		/************Add Dbt benefit type**************/
		
		
		//to add schme type
        $benefitType = new Zend_Form_Element_Select('benefit_type',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme Type--',
            '1'    => 'Cash',
            '2'    => 'Kind',
            '3'    => 'Others',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$benefitType->addValidators (array($requiredtype));
	  $this->addElement($benefitType);
	/*************end dbt benefit***************/
	 /******* dbt eligibility type************/
        $eligibiltyType = new Zend_Form_Element_select('eligibility_type',array(  
            'label'        => '',
            'required'   => true,
            //'registerInArrayValidator' => false,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Dbt Eligibility Type--',
            '1'    => 'Individual',
            '2'    => 'HouseHold',
            '3'    => 'ServiceEnablers',
                      
        ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
		 ));
     $requiredtype = new Zend_Validate_NotEmpty ();
     $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
	$eligibiltyType->addValidators (array($requiredtype));
	  $this->addElement($eligibiltyType);
	/*************end dbt eligibility typet***************/
	
	
	/************************add specific reason************/
	
	$this->addElement('textarea', 'specific_reason', array( 			
            'required'   => true,
            'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '500',
			'rows' => '2',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(
			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Scheme name can\'t be empty'
                    )
                ))/* ,
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Schme name field has special characters.Please remove them and try again.'))) */
    ),          
  ));
	
	
	/*******************end***************************/
		
		
			// Add a captcha
        $this->addElement('text', 'vercode', array(
            //'label'      => 'Please enter the 5 letters displayed below:',
            'required'   => true,
			'class'   => 'form-control captchain',
			'placeholder' => 'Captcha',
				 'autocomplete' => 'off',
				'decorators'=>Array(
			'ViewHelper',
			'Errors',
		   ),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 20))
                )
        ));
		

		
		
		
	}

}
