<?PHP


class Application_Form_movefolder extends Zend_Form
{

		public function init()
		{
		// Set the method for the display form to POST
		}

		public function addform()
		{
			// Set the method for the display form to POST
			$this->setMethod('post');

			$this->addElement('select','movefolder',
				array(
					'label'			=> '',
					'required'		=> true,
					'value'			=> 'user',
					'multiOptions'	=> array(
						'f1'		=> 'f1',
						'f2'		=> 'f2',
						'f3'		=> 'f3',
					),
				)
			);

			$this->addElement('submit', 'submit', array(
				'ignore'   => true,
				'label'    => 'Submit',
			));
		}
}
