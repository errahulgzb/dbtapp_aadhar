<?php 
class Application_Form_Utschemeowner extends Zend_Form {

    public function init(){
    // Set the method for the display form to POST
    }
    public function importscheme(){
        $this->setMethod('post');
        $this->addElement('file', 'importfile', array( 
                            'label'      => '',
                            'required'   => true,
							'class'   => 'form-control',
                            'id' => 'importfile',
                            ));
        $this->addElement('hidden', 'scheme_id', array( 
                            'label'      => '',
                            'required'   => true,
                            'id' => 'scheme_id',
                            )); 
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
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true
        ));
	}
}
?>
