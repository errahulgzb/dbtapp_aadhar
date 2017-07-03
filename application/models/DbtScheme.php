<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbtScheme extends Zend_Db_Table_Abstract{
	public function createTable($schemename = null, $schemeid = null){
        $tablename1 = substr(preg_replace('/[^A-Za-z0-9]/', '', strtolower($schemename)), 0, 20)."_".$schemeid;
        $update = new Zend_Db_Table("dbt_scheme");
    	$updateval = '';
    	$updateval = array('scheme_table'=> $tablename1);
    	$where = array('id = ?'=> $schemeid);
    	$update->update($updateval,$where);
	/* Creating default table for the all the scheme*/	
    	$tb_name = "dbt_".$tablename1;
		$db = Zend_Db_Table::getDefaultAdapter();
		
    	$db->query("create table $tb_name(
			id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			uniq_user_id varchar(10),
		    name varchar(50),
		    dob date,
			gender varchar(7), 
			aadhar_num varchar(20),
			mobile_num varchar(13),
			email_id varchar(50), 
			scheme_specific_unique_num varchar(50), 
		 	scheme_specific_family_num varchar(50),
		 	home_address varchar(100),
			village_code varchar(20),
		 	village_name varchar(50),
			panchayat_code varchar(20),
		 	panchayat_name varchar(50),
		 	block_code varchar(20),
		 	block_name varchar(50),
		 	district_code varchar(20),
		 	district_name varchar(50),
		 	state_code varchar(20),
		 	state_name varchar(50),
		 	pincode varchar(10),
		 	ration_card_num varchar(30), 
		 	tin_family_id varchar(30),
			bank_account varchar(25),
			ifsc varchar(25),
			aadhar_seeded varchar(1) default 'N',
		 	amount varchar(20),
		 	fund_transfer varchar(20),
		 	transaction_date varchar(50),
		 	year varchar(4),
			month varchar(10),
		 	day varchar(10),
			scheme_id int(11),
			uploaded_by int NOT NULL, 
		 	status int(1),
			error_remark varchar(200),
			aadhar_validate int(1) default '0',
			uidai_error_remark varchar(200),
			uidai_aadhar_validate int(1) default '0',
            csv_status int(1) default '1',
			purp_cd varchar(25) comment 'Purpose of Beneficiary Record. A=Add/U=Update/D=Delete.',
			pfmsbeneficiary_code varchar(25) comment 'Beneficiary Code in PFMS - PFMS Beneficiary Code provided in response file,Mandatory when Purpose is U or D',
			beneficiary_title varchar(10) comment 'Beneficiary Title. Eg. Mr, Mrs, Ms, Dr, etc….',
			beneficiary_regional_lang varchar(10) comment 'Beneficiary Name in Regional Language',
			beneficiary_type varchar(50) comment 'Beneficiary Type',
			pfms_request_id varchar(10) comment 'Unique Message Identifier. Source System Id given by PFMS(DBTBENREQDDMMYYYYN).',
			request_generated_time timestamp,
			nbOf_txs varchar(10) comment 'Should be grater than 0. Count of CstmrTxInf tag.',
			initg_pty_nm varchar(10) comment 'Owner Agency of Data i.e. District Level DDO.',
			Initgpty_prTry_id varchar(10) comment 'Data Owner Agency Unique Code i.e. for District Level DDO in PFMS. Reference Key of agency / PFMS Unique Code.',
			cstmr_inf_id varchar(10) comment 'Batch Number- Source System Id given by PFMS.',
			cstmr_inf_dt datetime comment 'YYYY-MM-DD format Batch Date',
			pfms_xml_status int(1) default '0' comment 'PFMS Status for xml generated',
			bank_name varchar(10) comment 'Bank Name of beneficiary',
			beneficiary_pfms_status int(1) default '0' comment 'beneficiary status from pfms response',
			pfms_rejection_code varchar(20) comment 'Error Code return by pfms',
			pfms_error_remark varchar(100) comment 'beneficiary status from pfms response',
		 	updated timestamp,
		 	created datetime)");
			
		$db->query("create table ".$tb_name."_transaction(
			id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			uniq_user_id varchar(10),
			txn_id varchar(50),
			user_id varchar(10),
			transfer_by varchar(30),
		 	amount varchar(20),
		 	fund_transfer varchar(20),
		 	transaction_date date,
		 	status int(1),
			service_status int(1) default '0',
			request_id varchar(25),
			pfms_request_id varchar(25),
			remarked varchar(300),
			pfms_status int(1) default '0',
			transaction_status int(1) default '0',
			pfms_xml_status int(1) default '0',
			from_payment_date date,
			to_payment_date date,
			purpose varchar(100),
			pfms_transaction_error_code varchar(50),
			pfms_transaction_remark_code varchar(300),
			payment_mode_by int(1) default '1',
			approval_transaction_date date,
		 	updated timestamp,
		 	created datetime)
			");
	}


	public function getministry(){
		$select_table = new Zend_Db_Table('dbt_ministry');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('cms' => 'dbt_ministry'), array('ministry_name','translation_id','id as ministryid'))->where('cms.language = 2')->where('cms.status = 1');
		$select_menu = $select_table->fetchAll($select);
		return $select_menu->toArray();
	}
			
			
	public function getscheme($ministry_id){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('schm' => 'dbt_scheme'), array('scheme_name','translation_id','id'));
		$select->where('schm.ministry_id  = ?', $ministry_id);
		$select->where('schm.status=1');
		//echo $select;die;
		$select_schme = $select_table->fetchAll($select);
		//print_r($select_feedbackrec);
		//echo $select;
		//die;
		return $select_schme->toArray();
	}
	public function getschemenew($ministry_id){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('schm' => 'dbt_scheme'), array('scheme_name','translation_id','id'));
		$select->where('schm.ministry_id  = ?', $ministry_id);
		$select->where('schm.translation_id !=1');
		$select->where('schm.status=1')->order('schm.scheme_name ASC');
		//echo $select;die;
		$select_schme = $select_table->fetchAll($select);
		//print_r($select_feedbackrec);
		//echo $select;
		//die;
		return $select_schme->toArray();
	}
	public function titleListByLang($translation_id, $cmsid){
		$search = '';
		$select_table = new Zend_Db_Table('dbt_ministry');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('mns' => 'dbt_ministry'), array('ministry_name', 'id as ministryid'));
		$select->where('mns.id  = ?', $translation_id);
		//echo $select;
		$select_feedbackrec = $select_table->fetchRow($select);
		return $select_feedbackrec;
	}
	public function titleListByScheme($translation_id, $cmsid){
	    $search = '';
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('mns' => 'dbt_scheme'), array('scheme_name', 'id'));
		$select->where('mns.id  = ?', $translation_id);
		$select->where('schm.status=1');
		//echo $select;
		$select_feedbackrec = $select_table->fetchRow($select);				
		return $select_feedbackrec;
	}
	
	
	public function insertschemedetails($dataform){
		
		$user_table = new Zend_Db_Table('dbt_scheme');
		$datainsert="";
		$datainsert = array(
			'scheme_name'=> $dataform['scheme_name'] ,
			'ministry_id'=> $dataform['ministry_id'] ,
			'scheme_type'=> $dataform['scheme_type'] ,
            'scheme_group'=> $dataform['scheme_group'] ,
			'scheme_codification' => $dataform['scheme_codification'],
			'pfms_scheme_code' => $dataform['pfms_scheme_code'],
			'description'=> $dataform['description'],
            'filename'=> '',
            'filepath'=> '',
			'language'=> $dataform['lang'],
			'translation_id' => 0,	
			'status'=> 1							
		);	
			//echo "<pre>";print_r($datainsert);die;
		$insertdata=$user_table->insert($datainsert);
		return $insertdata;
	}	

public function insertschemetranslationwithouthindi($dataform,$rowid)
			{
				$user_table = new Zend_Db_Table('dbt_scheme');
				$datainsert="";
				$datainsert = array(
							'scheme_name'=> $dataform['scheme_name_hindi'] ,
							'ministry_id'=> '' ,
							'scheme_type'=> '',
							'description'=> $dataform['description_hindi'],
							'language'=> $dataform['lang'],
							'translation_id' => 1,	
							'status'=> 1							
									);					 
					 $insertdata=$user_table->insert($datainsert);
					 
					 
					  $data="";
						$where="";						
						$data = array(
					
							'translation_id'=> $insertdata						
							);
						$where = array('id = ?'=> $rowid);
						//print_r($rowid);
						//print_r($data);
						//exit;
						$update_values = $user_table->update($data, $where);
					return $insertdata;
				}				
			public function schemelist($start,$limit)
			{   
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				//$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				$search = safexss(@$_GET['search']);
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_scheme'), array('scheme_name', 'id','ministry_id','scheme_codification','description','scheme_type','scheme_group','status','translation_id'));				
				$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('p.ministry_name as ministryname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.ministry_id IN (?)', $ministryids);
				}	
                //$select->where('l.translation_id!=1');				
				$select->where('l.scheme_name LIKE ? OR p.ministry_name LIKE ?', '%'.$search.'%', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				//echo "<pre>";
				//echo "aaaa";exit;
				return $select_org;
			}

			public function countlocation(){
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				//$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				
				$search = safexss(@$_GET['search']);
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_scheme'), array('scheme_name', 'id','ministry_id','status'));
				$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('p.ministry_name as ministryname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.ministry_id IN (?)', $ministryids);
				}
				// $select->where('l.translation_id!=1');	
				$select->where('l.scheme_name LIKE ? OR p.ministry_name LIKE ?', '%'.$search.'%', '%'.$search.'%')
				->order('l.id DESC');//->limit($limit,$start);
					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				  return count($select_org); 
			}

			
			public function locationuser($start,$limit)
			{
				  $select_table = new Zend_Db_Table('dbt_scheme');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('scheme_name DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
				  
				  
				  
			
			
			
			
			}

			public function customeruser()
			{
				 // $select_table = new Zend_Db_Table('users');
				//  $row = $select_table->fetchAll($select_table->select()->where('status =1 and role=3')->order('id DESC'));
				
				 // return $row; 
				  
				  
				  
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				//$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				// select distinct customer from dbt_ministry table 				
				
				$nm22 = new Zend_Db_Table('dbt_ministry');
				$select = $nm22->select();
				$select->from(array('p' => 'dbt_ministry'), array( 'customer_id'));	
				if($user_role==4){          // check role for admin 
					$select->where('p.id IN (?)', $ministryids);
				}		
				$select->where('status=1');
				$row22 = $nm22->fetchAll($select);
				
				$ids =  $row22->toArray();
				$pids = array(); 
				$arr  = array();
				foreach($ids as $key=>$val)
				{
				   $pids[] = $val[customer_id];
				  
				   
				}
				//echo "<pre>"; print_r($pids); exit;
				
				$select_table = new Zend_Db_Table('dbt_users');
				$select_query =  $select_table->select();
				$select_query->from(array('u' => 'dbt_users'), array( 'id','organisation','firstname','lastname','username'));	

				$select_query->where('role =3');

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $pids);	
				}	
				$select_query->where('status=1');
				$select_query->order('organisation ASC');
				
				$rowlist = $select_table->fetchAll($select_query);

				return $rowlist; 
			}
			
			
			
			
			/*****************Assigned Installation Engineer List*************/
			public function assign_ministryids($pmid=null)
			{
				$nm22 = new Zend_Db_Table('assign_manager');
				$select = $nm22->select();
				$select->from(array('am' => 'assign_manager'), array( 'project_id'));	
				$select->where('pm_id=?',trim(intval($pmid)));			
				$select->where('status=1');
				$row22 = $nm22->fetchAll($select);

				$ids =  $row22->toArray();
				
				
				$pids = array(); 
				$arr  = array();
				foreach($ids as $key=>$val)
				{
				   $pids[] = $val[ministry_id];
				  
				   
				}
				$arr[] = implode(',',$pids);
				$abc = end($arr);
				$str = explode(',',$abc);
				 return $str;


			}
			
			
			public function roleuser()
			{
				  $select_table = new Zend_Db_Table('dbt_ministry');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('ministry_name DESC'));
				
				  return $row; 
			}

			public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('dbt_scheme');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				  
				  $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				//$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'dbt_ministry'), array( 'id','ministry_name'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $ministryids);	
				}	
				$select_query->where('status=1');
				//$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('scheme_name ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  
				return $rowlist; 
			}
			
			
public function editschemeclienthindi($id)
	        {
				
				//echo $id;
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				$select->from(array('schmm' => 'dbt_scheme'), array('translation_id'));
				$select->where('schmm.id  = ?', $id);
						//echo $select;die;
				$select_cnttilte = $select_table->fetchAll($select);
			
				$translation_id  = $select_cnttilte[0][translation_id];
				//echo $translation_id;
                $select_table = new Zend_Db_Table('dbt_scheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($translation_id))));
				 return $rowselect;     
			
			}

			public function editschemeclient($id)
	        {
                $select_table = new Zend_Db_Table('dbt_scheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
       public function language()
			{
				  $select_table = new Zend_Db_Table('dbt_language');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC'));
				
				  return $row; 
			}
			
			
			public function editschemetranslateclient($id)
	        {
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select_query =  $select_table->select();
				$select_query->from(array('s' => 'dbt_scheme'), array( 'id','ministry_id','scheme_type','filename','filepath'));	
				
				$rowlist = $select_table->fetchAll($select_query);

				return $rowlist; 
				
			}		
			
			public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('dbt_scheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}
			
			

			public function editschemedetails($editdataform,$id)
	        {
				//echo "<pre>";print_r($editdataform);die;
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_scheme');

						$data="";
						$where="";						
						$data = array(
							'scheme_name'=> $editdataform['scheme_name'] ,
							'ministry_id'=> $editdataform['ministry_id'] ,
							'scheme_type'=> $editdataform['scheme_type'] ,
                            'scheme_group'=> $editdataform['scheme_group'],
							'pfms_scheme_code'=> $editdataform['pfms_scheme_code'],
							'description'=> $editdataform['description']
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}
			
			
			public function editschemetranslatedetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_scheme');

						$data="";
						$where="";						
						$data = array(
							'scheme_name'=> $editdataform['scheme_name'] ,
							'ministry_id'=> $editdataform['ministry_id'] ,
							'scheme_type'=> $editdataform['scheme_type'] ,
							'description'=> $editdataform['description'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}


			public function deletelocation($id)
			{
					$delete_project = new Zend_Db_Table('dbt_scheme');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_project->delete($where);
				//Update master data table (no of dipartments)
						$data_table = new Zend_Db_Table('dbt_home_page_master_data_current_year');
						$select = $data_table->select();
						$select_org = $data_table->fetchAll($select);
						$masterdatacount = count($select_org);

						$select = $user_table->select();
						$select->where('language != ?', 1);
						$select_org = $user_table->fetchAll($select);
						$schemecount = count($select_org);

						if ($masterdatacount == 0) {
							$datainsert="";
							$datainsert = array(
								'number_of_schemes'=> $schemecount,
								'status'=> 1
							);
							$insertdata=$data_table->insert($datainsert);
						} else {
							$data="";
							$data = array('number_of_schemes'=> $schemecount);
							$update_values = $data_table->update($data);
						}
				//Update master data table (no of dipartments) end


			}

			public function inactivescheme($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_scheme');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkschemeclient($name,$ministry)
	        {	
			$select_table = new Zend_Db_Table('dbt_scheme');
			$rowselect = $select_table->fetchAll($select_table->select()->where('scheme_name = ?',trim(($name)))->where('ministry_id = ?',trim(($ministry))));
				return count($rowselect); 
			}
			
		  public function checkschemetranslationclient($name)
	        {	
			$select_table = new Zend_Db_Table('dbt_scheme');
			$rowselect = $select_table->fetchAll($select_table->select()->where('scheme_name = ?',trim(($name))));
		      return count($rowselect); 
			}

			public function checkschemeclientEdit($name,$id,$ministryname)
	        {
				
				$select_table = new Zend_Db_Table('dbt_scheme');

				$rowselect = $select_table->fetchAll($select_table->select()->where('scheme_name = ?',trim(($name)))->where('ministry_id = ?',trim(($ministryname)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}

// function for ajax project
	
		public function projectlocationlist($customer_id)
			{  
				$userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				//$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'dbt_ministry'), array( 'id','ministry_name'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $ministryids);	
				}	
				$select_query->where('customer_id=?',$customer_id);
				$select_query->where('status=1');
				$select_query->order('scheme_name ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  return $rowlist; 
			}
			
			
			/********get the dbt applicable list from the ministry owner table***********/
	public function getdbtapplicableministrylist( )
			{ 
				
				$ministry = $this->getministry();
				$data = array();
				$i = 0;
				foreach($ministry as $k=>$v)
				{

					$ministryname = $v['ministry_name'];
					$select_table = new Zend_Db_Table('dbt_ministry_owner');
					$select = $select_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('ministryowner' => 'dbt_ministry_owner'), array('scheme_name'));				
					$select->where('ministryowner.ministry_id=?',$v['ministryid']);
					$select->where('ministryowner.dbt_eligibility=1');	
					$select->where('ministryowner.status=1');	
					$select->order('ministry_id ASC');
					$select_org = $select_table->fetchAll($select);
					$schemedata = $select_org->toArray();  
					if(!empty($schemedata))
					{
					$applicabledata = array('ministry_name' =>$ministryname,'scheme_data' => $schemedata);
					$data[$i] = $applicabledata;
					$i++;
					}
					
				}
				return $data;
			}
	
	
	/****************end*********************************************************/
	
	
	/***********count total schemes dbt applicable schemes and non dbt applicable schemes******************/
	public function counttotalschemes( )
			{ 
				$select_table = new Zend_Db_Table('dbt_ministry_owner');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_ministry_owner'), array('count(scheme_name) as schemenamecount','count(distinct(ministry_id)) as mincount'));
				$select->where('l.status=1');	
				//$select->where('l.translation_id!=1');			
				$select_org = $select_table->fetchAll($select);
				return $select_org->toArray();
			}
		public function counttotalapplicableschemes( )
			{ 
				$select_table = new Zend_Db_Table('dbt_ministry_owner');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_ministry_owner'), array('count(scheme_name) as schemenamecount','count(distinct(ministry_id)) as mincount'));
				$select->where('l.dbt_eligibility=1');	
				$select->where('l.status=1');	
				//$select->where('l.translation_id!=1');			
				$select_org = $select_table->fetchAll($select);
				return $select_org->toArray();
			}
   public function counttotalnonapplicableschemes( )
			{ 
				$select_table = new Zend_Db_Table('dbt_ministry_owner');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_ministry_owner'), array('count(scheme_name) as schemenamecount','count(distinct(ministry_id)) as mincount'));
				$select->where('l.dbt_eligibility=2');	
				$select->where('l.status=1');	
				//$select->where('l.translation_id!=1');			
				$select_org = $select_table->fetchAll($select);
				return $select_org->toArray();
			}
	/******************end*******************************************************************/
	
	
		/********get the ministry name************/
	public function getminname($ministry_id)
			{
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('min' => 'dbt_ministry'), array('ministry_name','id'));
				$select->where('min.id  = ?', $ministry_id);
				$select->where('min.status=1');
				$select_schme = $select_table->fetchAll($select);
				return $select_schme->toArray();
			}
/***********end****************************/


	public function getschemenamenn($schemeid = null)
			{
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('schm' => 'dbt_scheme'), array('scheme_name','id'));
				$select->where('schm.id  = ?', $schemeid);
				//$select->where('schm.status=1');
				$select_schme = $select_table->fetchAll($select);
				return $select_schme->toArray();
				//return $select;
				//echo $select;
			}
//********************* check scheme code start now ***************


public function checkschemecode($scheme_code=null)
	        {	
			$select_table = new Zend_Db_Table('dbt_scheme');
			$select = $select_table->select();
			$select->from(array("scm" =>"dbt_scheme"),array("count(scm.id) as counted"));
			$select->where('scm.scheme_codification = ?',trim(($scheme_code)));
			$rowselect = $select_table->fetchAll($select)->toArray();
	//echo $select;die;
		      return $rowselect[0]['counted']; 
			}

//******************** check scheme code end now ******************
}