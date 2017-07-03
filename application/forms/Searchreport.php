<?PHP


class Application_Form_Searchreport extends Zend_Form
{
public function init()
{
// Set the method for the display form to POST
}

public function addform()
	{
        $roleid = new Zend_Session_Namespace('role');
        $userid = new Zend_Session_Namespace('userid');
        //for the current finacial year
            $curre_year = strtotime(date("d-m-Y"));          
            $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
            if($curre_year > $fixedyear){
                $start = date("Y");
                $getend = $start + 1;
                $end_date = $getend;
            }
            //echo $curre_year;exit;
        //for the current finacial year

		// Set the method for the display form to POST

        $this->setMethod('post');

       $year = new Zend_Form_Element_Select('year',array(  
            'label'        => '',
            'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            //'0'    => '--Select Scheme Type--',
            $start."_".$end_date => $start."-".$end_date,                      
        ),
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
         ));
        //  $requiredtype = new Zend_Validate_NotEmpty ();
        //  $requiredtype->setType ($requiredtype->getType() | Zend_Validate_NotEmpty::INTEGER | Zend_Validate_NotEmpty::ZERO);
        // $year->addValidators (array($requiredtype));
        //   //  $this->setValue($start."_".$end_date);
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
            //print_r($ministryData->toArray());
            //exit;
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
        //echo $roleid->role;exit;
            // $opted = $ministryget->selectUserstate($userid->userid);
            // foreach($opted as $list) {
            //     $idstate = $list->state;
            // }
        $stateData = $ministryget->selectstate();
            foreach($stateData as $list) {
                $name = ucfirst($list->state);
                $state->addMultiOption($list->id, $name);
        }
        $this->addElement($state);
//showing state end here

/*
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
        // $districtData = $ministryget->selectdistrict($idstate);
        //   print_r($districtData);exit;
        //     foreach($districtData as $list) {
        //         $name = ucfirst($list->district);
        //         $district->addMultiOption($list->distritctcode, $name);
        // }
        $this->addElement($district);

         //echo "lorem";exit;
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
       
    // if($idstate != "" && $cityid != ""){

    //     $blockData = $ministryget->selectblock($cityid);
    //         //echo "<pre>";
    //         //print_r($ministryData->toArray());exit;
    //         foreach($blockData as $list) {
    //             $blockname = ucfirst($list->blockname);
    //             $block->addMultiOption($blockname->blockcode, $blockname);
    //         }
    //      }
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
        */
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
