<?PHP
class Application_Form_Pfmsform extends Zend_Form{
	public function init(){
//die("sdff");
		// Set the method for the display form to POST
	}
	public function addform(){
//$i = strtotime("now");
//echo $i;exit; 
$_SESSION['arrayindex']=1;
$cmi_list1 = new Application_Model_Schemeimport();

$scheme_id=base64_decode($_GET['scheme_id']);
//$cmishow_list1 = $cmi_list1->pfmsbeneficiaries($start = null,$limit = null,$scheme_id);
//print_r($cmishow_list1);die;
		// Set the method for the display form to POST
		$this->setMethod('post');



			
				$this->addElement('text', 'beneficiary_amount', array(
				'required'   => false,
				'name' => "beneficiary_amount".$_SESSION['arrayindex'],
				'isArray' => true,
				'class'   => 'form-control',
				'filters'    => array('StringTrim'),
				'maxlength'  => '20',
				'placeholder' => 'Amount',
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),
				'validators' => array(
						array('Regex',
							false,
							  array('/^[0-9]*$/', 'messages'=>array('regexNotMatch'=>'Amount field is allowed only numeric input.')))
					 ),
			));

//*********** remark field*********************

	$this->addElement('text', 'remarked_field', array(
			'required'   => false,
		//'name' => "beneficiary_amount".$i,
				'isArray' => true,
			'class'   => 'form-control',
			'filters'    => array('StringTrim'),
			'maxlength'  => '20',
			'placeholder' => 'Remark',
			'decorators'=>Array(
			'ViewHelper','Errors'
		   ),
			
		));
//}}
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
            'ignore' => true,
        ));

		
	}

}
