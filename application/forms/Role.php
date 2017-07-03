<?PHP


class Application_Form_Role extends Zend_Form
{

public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{

		// Set the method for the display form to POST
        $this->setMethod('post');

       $this->addElement('text', 'name', array( 			
            'required'   => true,
			'class'   => 'form-control',
            'filters'    => array('StringTrim'),
			'maxlength'  => '100',

			
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
            'validators' => array(

			    array('notEmpty', true, array(
                    'messages' => array(
                        'isEmpty'   =>  'Role name can\'t be empty'
                    )
                )),
                    array('Regex',
                        false,
                          array('/^[a-z][a-z0-9., \'-]{0,}$/i', 'messages'=>array(
'regexNotMatch'=>'Role name field has special characters.Please remove them and try again.')))
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

}
