<?php
class Application_Form_SchemeReport extends Zend_Form{
	public function init(){
		// Set the method for the display form to POST
	}
	public function getdropdown(){
		// Set the method for the display form to POST
		$this->setMethod('post');
		$user = new Application_Model_Misreport;
		$role = new Zend_Session_Namespace('role');
		$userid = new Zend_Session_Namespace('userid');
		$state_code = new Zend_Session_Namespace('state_code');
		
//searching by the ministry		
		$ministry_collect = new Zend_Form_Element_Select('ministry_name',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            //'' => '==Select Ministry==',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),
        ));
		if($role->role == 6){//for ministry user
			$ministryid = new Zend_Session_Namespace('ministryid');
			$showministryname = $user->Getministry($userid->userid, $ministryid->ministryid,$role->role);
		}else if($role->role == 1){//for Admin
			$showministryname = $user->Getministry();
		}else if($role->role == 4){//for scheme owner
			$showministryname = $user->Getministry($userid->userid,"",$role->role);
		}
		else if($role->role == 12){//for scheme owner
			$showministryname = $user->Getministry($userid->userid,"",$role->role);
		}
		else{
			$showministryname = $user->Getministry();
		}
        foreach($showministryname as $key => $value){
            $ministry = $value['min_name'];
            $ministry_collect->addMultiOption($value['min_id'], $ministry);        
        }
        $this->addElement($ministry_collect);
		if($role->role == 6 || $role->role == 4 || $role->role == 1 || $role->role == 12){
			if(isset($value['min_id']) or !empty($value['min_id'])){
			$ministry_collect->setValue($value['min_id']);
			$valselect = $value['min_id'];
			}else{
			$ministry_collect->addMultiOption(0, "==Select Ministry==");
			$valselect = null;
			}
		}
		//echo $valselect;exit;
//searching by the ministry	end here		


//listing the scheme by the ministry

		//getting here the Ministry Name from the USer Model and display against to the Minisry User Role 
		$scheme_collect = new Zend_Form_Element_Select('scheme_name',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            //'' => '==Select Scheme==',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
		if($role->role == 6 || $role->role == 4 || $role->role == 1 || $role->role == 12){
			$showministryname = $user->Getscheme($userid->userid,$ministryid->ministryid,$valselect);
			$curent_scheme_id=null;
			if(count(array_filter($showministryname)) > 0){
				foreach($showministryname as $key => $value){			
					$scheme = $value['scm_name'];
					$scheme_collect->addMultiOption($value['scm_id'], $scheme);
					$curent_scheme_id=$value['scm_id'];
				}
			}else{
				$scheme_collect->addMultiOption(0, "==Select Scheme==");
				$curent_scheme_id=null;
			}
		}
		
        $this->addElement($scheme_collect);
//listing the scheme by the ministry end here



/*--------- Aadhar Number field status start now---------------*/

		$aadhar_num_status = new Zend_Form_Element_Select('aadhar_num_status',array(  
            'label'        => '',
		    //'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Aadhar No.==',
			'1'    => 'Yes',
			'2'    => 'No',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		$this->addElement($aadhar_num_status);
/*--------- Aadhar Number field status end now---------------*/

/*--------- Bank Account Number field status start now---------------*/

		$bank_account_status = new Zend_Form_Element_Select('bank_account_status',array(  
            'label'        => '',
		    //'required'   => true,
            'class'   => 'form-control',
            'multiOptions' => array(
            '0'    => '==Seeded With Aadhaar==',
			'1'    => 'Yes',
			'2'    => 'No',
            ),
		    'decorators'=>Array(
			'ViewHelper','Errors'
			),
		 ));
		$this->addElement($bank_account_status);
/*--------- Bank Account Number field status end now---------------*/
		

		
		
		
//Listing with the state
		
		if($state_code->state_code == "" || $state_code->state_code == 0){
			//echo "aaa";exit;
				$state_collect = new Zend_Form_Element_Select('state_code',array(
				'label'        => '',
				'class'   => 'form-control',
				'multiOptions' => array(
				'0' => '==Select State==',
			),  
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),     
			));
			$st = new Application_Model_DbtState;
			$state_name = $st->getAllstate();
			//echo "<pre>";print_r($state_name);exit;
			foreach($state_name as $key => $value){
				$state_name = $value['state_name'];
				//echo $state_name."aaa<br />";
				$state_collect->addMultiOption($value['state_code'], $state_name);
			}
			$this->addElement($state_collect);
		}
		
		
		else if($state_code->state_code != "" || $state_code->state_code != 0){
			//echo "bbb".$state_code->state_code;exit;
				$state_collect = new Zend_Form_Element_Select('state_code',array(
				'label'        => '',
				'class'   => 'form-control',
				'multiOptions' => array(
			),
				'decorators'=>Array(
				'ViewHelper','Errors'
			   ),     
			));
			$st = new Application_Model_DbtState;
			$state_name = $st->getAllstate($state_code->state_code);
			//echo "<pre>";print_r($state_name);exit;
			foreach($state_name as $key => $value){
				$state_name = $value['state_name'];
				$state_collect->addMultiOption($value['state_code'], $state_name);
			}
			$this->addElement($state_collect);
		}
//Listing with the state







//Listing with the District
		$dist_collect = new Zend_Form_Element_Select('district_code',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Select District==',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
		if($state_code->state_code != ""){
			$st = new Application_Model_DbtState;
			$dist = $st->getAllDist($state_code->state_code);
			//echo "<pre>";print_r($state_name);exit;
			foreach($dist as $key => $value){
				$dist_name = $value['district_name'];
				$dist_collect->addMultiOption($value['district_code'], $dist_name);
			}
		}
        $this->addElement($dist_collect);
//Listing with the District



//Listing with the Block
		$block_collect = new Zend_Form_Element_Select('block_code',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Select Block==',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
        $this->addElement($block_collect);
//Listing with the Block


//Listing with the village
		$village_collect = new Zend_Form_Element_Select('village_code',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Select Village==',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
        $this->addElement($village_collect);
//Listing with the village


//Listing with the Gender
		$gender_collect = new Zend_Form_Element_Select('gender',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Gender==',
			'M' => 'Male',
			'F' => 'Female',
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
        $this->addElement($gender_collect);
//Listing with the Gender

			//$currentscheme = $user->getcurrentschemeType($curent_scheme_id);
			//ho $currentscheme[0]['scheme_type'];die;

//Listing with the Fund Transfer

       
	
		$ft_collect = new Zend_Form_Element_Select('fund_transfer',array(
            'label'        => '',
			'class'   => 'form-control',
            'multiOptions' => array(
            '0' => '==Fund Transfer==',
			'APB' => 'APB',
			'NEFT' => 'NEFT',
			'NACH' => 'NACH',
			'RTGS' => 'RTGS',
			'CASH' => 'CASH'
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
        $this->addElement($ft_collect);

//Listing with the Fund Transfer



//Listing with the Transfer By

		$ft_collect = new Zend_Form_Element_Select('transfer_by',array(
            'label' => '',
			'class' => 'form-control',
            'multiOptions' => array(
            '0' => '==Transfer By==',
			'Bio Authentication' => 'Bio Authentication',
			'Demographic Authentication' => 'Demographic Authentication',
			'Manual Validation' => 'Manual Validation'
        ),  
            'decorators'=>Array(
            'ViewHelper','Errors'
           ),     
        ));
        $this->addElement($ft_collect);

//Listing with the Transfer By


//Add to date for the search criteria
        $this->addElement('text', 'todate', array(
            'label'      => '',
            'required'   => false,
			'readonly'  => readonly,
			'class'   => 'form-control',
			'placeholder' => 'mm/dd/yyyy',
				 'autocomplete' => 'off',
				'decorators'=>Array(
			'ViewHelper',
			'Errors',
		   )
        ));
//Add to date for the search criteria


//Add from date for the search criteria
        $this->addElement('text', 'fromdate', array(
            'label'      => '',
            'required'   => false,
			'readonly'  => readonly,
			'class'   => 'form-control',
			'placeholder' => 'mm/dd/yyyy',
				 'autocomplete' => 'off',
				'decorators'=>Array(
			'ViewHelper',
			'Errors',
		   )
        ));
//Add from date for the search criteria


        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));	
	}
}
