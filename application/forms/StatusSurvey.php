<?PHP
class Application_Form_StatusSurvey extends Zend_Form
{
	public function init()
	{
		// Set the method for the display form to POST
	}

	public function addform()
	{

			// Set the method for the display form to POST
			$this->setMethod('post');

			
			$this->addElement('radio', 'status', array(
				'required'   => true,
				'multiOptions' => array(
					'1' => IN_PROGRESS,
					'2' => 'Closed'
					
				),
				'value' => '1' //key of multiOption
			));
		
		

			$this->addElement('textarea', 'Comment', array(            
					'required'   => true,
					'maxlength' =>'50',
					
					'class'   => 'validate[required] text-input',
					'decorators'=>Array(
						'ViewHelper','Errors'
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
}
