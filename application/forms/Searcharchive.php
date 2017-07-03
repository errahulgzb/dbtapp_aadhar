<?PHP


class Application_Form_Searcharchive extends Zend_Form
{

public function init()
{
    $roleArray = array("1","2","3","5");
// Set the method for the display form to POST
}

public function addform()
	{
        //for the current finacial year
        $currentyear = date('Y');
		$currentmonth = date('m');
		if ($currentmonth < 4) {$currentyear = $currentyear - 1;}
            //echo $curre_year;exit;
        //for the current finacial year

		// Set the method for the display form to POST

        $this->setMethod('post');
		$financialyearfrom = $currentyear - 1;
		$financialyearto = $currentyear;
       $year = new Zend_Form_Element_Select('year',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            $financialyearfrom.'_'.$financialyearto => $financialyearfrom.'-'.$financialyearto,
            //$end_date."_".$start => $end_date."-".$start,                      
        ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
         ));
		for($i=$currentyear; $i >= YEAR_FROM + 1; $i--){
			$financialyearfrom = $i - 2;
			$financialyearto = $i - 1;
			$yearval = $financialyearfrom.'_'.$financialyearto;
			$yearval1 = $financialyearfrom.'-'.$financialyearto;
			$year->addMultiOption($yearval, $yearval1);
		}
        $this->addElement($year);

//showing the ministry select option

        $ministry = new Zend_Form_Element_Select('ministry',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Ministry--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
      //getting all the record from ministry  
        $ministryget = new Application_Model_Schemereport;
        $ministryData = $ministryget->selectministry();
            //echo "<pre>";
            //print_r($ministryData->toArray());exit;
            foreach($ministryData as $list) {
                $name = ucfirst($list->ministry);
                $ministry->addMultiOption($list->id, $name);
            }
        $this->addElement($ministry);

//showing ministry end here

//showing the scheme select option

        $scheme = new Zend_Form_Element_Select('scheme',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Scheme--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
        $this->addElement($scheme);
        
//showing scheme end here

//geeting role id from user;
         $roleid = new Zend_Session_Namespace('role');
         $userid = new Zend_Session_Namespace('userid');
//getting role id end here

//showing the state select option

        $state = new Zend_Form_Element_Select('state',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select State--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ), 
         ));
        
        $required = new Zend_Validate_NotEmpty ();
        $required->setType ($required->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
            $state->addValidators (array($required));
        //$state->setValue("09");
        if($roleid->role == 3 || $roleid->role == 5){
            $opted = $ministryget->selectUserstate($userid->userid);
            foreach($opted as $list) {
                $id = $list->state;
            }
            $state->setValue($id);
        }
         //getting all the record from state  
        
        $stateData = $ministryget->selectstate();
            foreach($stateData as $list) {
                $name = ucfirst($list->state);
                $state->addMultiOption($list->id, $name);
        }
         $this->addElement($state);
//showing state end here


//showing the district select option

        $district = new Zend_Form_Element_Select('district',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select District--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
       
        $this->addElement($district);
         if($roleid->role == 5){
            $opted = $ministryget->selectUserstate($userid->userid);
            //echo "<pre>";
            //print_r($opted->toArray());
            //exit;
            foreach($opted as $list) {
                $name = $list->cityname;
            }
            $this->setAttrib('readonly', 'readonly');
                $this->setDefault($name);
            //$this->setValue($list->id, $name)
        }
            
//showing district end here


//showing the block select option

        $block = new Zend_Form_Element_Select('block',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Block--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
        $this->addElement($block);
        
//showing block end here

//showing the panchayat select option

        $panchayat = new Zend_Form_Element_Select('panchayat',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Panchayat--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
        $this->addElement($panchayat);
        
//showing panchayat end here

//showing the village select option

        $village = new Zend_Form_Element_Select('village',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '--Select Village--',                    
            ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           )
         ));
        $this->addElement($village);
        
//showing village end here        
			
			
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
