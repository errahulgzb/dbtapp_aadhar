<?PHP


class Application_Form_User extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
       
		$this->setMethod('post');
		 $this->setAttrib('enctype', 'multipart/form-data');

		$this->addElement('file', 'upload_image', array( 
			'label'      => '',
            'required'   => false
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

}
