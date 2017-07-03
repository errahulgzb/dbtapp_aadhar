<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbtSchemeinfo extends Zend_Db_Table_Abstract 
{
	
		public function createTable($schemename = null, $schemeid = null){
			//echo $schemename;exit;
			$curre_year = strtotime(date("d-m-Y"));          
            $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
            if($curre_year > $fixedyear){
                $start = date("Y");
                $end = date("Y") + 1;
                $dateended = $end;
            }else{
            	$start = date("Y");
            	$end = date("Y") + 1;
            	$dateended = $end;
            }	
          
            $tablename1 = substr(preg_replace('/[^A-Za-z0-9]/', '', strtolower($schemename)), 0, 20)."_".$schemeid;
           
            //echo $tablename;exit;
            $update = new Zend_Db_Table("dbt_scheme");
    		$updateval = '';
    		$updateval = array('scheme_table'=> $tablename1);
    		$where = array('id = ?'=> $schemeid);
    		$update->update($updateval,$where);

    		 $datestart = $start - 7;

            for($i = $datestart; $i <= $start; $i++){
            	$yearfrom = $i;
            	$yearto = $i + 1;
            	$year_created = $yearfrom."_".$yearto;

    		$tb_name = "dbt_".$tablename1."_".$year_created;
			$db = Zend_Db_Table::getDefaultAdapter();
    		$db->query("create table $tb_name(
				id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			    name varchar(50),
			    dob varchar(20),
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
		 		amount varchar(20),
		 		fund_transfer varchar(20),
		 		transaction_date varchar(50),
		 		year varchar(4),
				month varchar(10),
		 		day varchar(10),
				scheme_id int(11),
				crontype int(1) default '0', 
		 		status int(1 ),
		 		updated timestamp,
		 		created datetime)");
    		}
		}


		public function getministry()
			{
				
				
		$select_table = new Zend_Db_Table('dbt_ministry');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
			//$select->from(array('ministry' => 'dbt_ministry'),  array('ministry_name', 'id','language','status','created','updated','translation_id'))->where('ministry.language = 2')->where('ministry.status = 1')->order('ministry.ministry_name ASC');
		$select->from(array('cms' => 'dbt_ministry'), array('ministry_name','translation_id','id as ministryid'))->where('cms.language = 2')->where('cms.status = 1')->order('cms.id ASC');
		$select_menu = $select_table->fetchAll($select);
	
		return $select_menu->toArray();

			}
			
			
			public function getscheme($ministry_id)
			{
				
				
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
			
			
			public function getschemenew($ministry_id)
			{
			  $select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('schm' => 'dbt_scheme'), array('scheme_name','translation_id','id'));
				$select->where('schm.ministry_id  = ?', $ministry_id);
				$select->where('schm.translation_id !=1');
				$select->where('schm.status=1');
						//echo $select;die;
				$select_schme = $select_table->fetchAll($select);
				//print_r($select_feedbackrec);
				//echo $select;
				//die;
				return $select_schme->toArray();
			}
				public function titleListByLang($translation_id, $cmsid){
		
		    //echo $lang;
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
	
	
		public function insertschemedetails($dataform)
			{
				$user_table = new Zend_Db_Table('dbt_scheme');
				$datainsert="";
				$datainsert = array(
							'scheme_name'=> $dataform['scheme_name'] ,
							'ministry_id'=> $dataform['ministry_id'] ,
							'scheme_type'=> $dataform['scheme_type'] ,
							'description'=> $dataform['description'],
                                                        'filename'=> '',
                                                        'filepath'=> '',
														'language'=> $dataform['lang'],
														'translation_id' => 0,	
							'status'=> 1							
									);					 
				$insertdata=$user_table->insert($datainsert);

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
				$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				$search = @$_GET['search'];
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_scheme'), array('scheme_name', 'id','ministry_id','description','scheme_type','status','translation_id'));				
				$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('p.ministry_name as ministryname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.ministry_id IN (?)', $ministryids);
				}	
                $select->where('l.translation_id!=1');				
				$select->where('l.scheme_name LIKE ? OR p.ministry_name LIKE ?', '%'.$search.'%', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				//echo "<pre>";
				//echo "aaaa";exit;
				return $select_org;
			}

			public function countlocation()
			{
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				
				$search = @$_GET['search'];
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_scheme'), array('scheme_name', 'id','ministry_id','status'));
				$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('p.ministry_name as ministryname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.ministry_id IN (?)', $ministryids);
				}
				 $select->where('l.translation_id!=1');	
				$select->where('l.scheme_name LIKE ? OR p.ministry_name LIKE ?', '%'.$search.'%', '%'.$search.'%')
				//$select->ORwhere('p.scheme_name LIKE ?', '%'.$search.'%');
				//$select->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
					
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
				$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
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
				$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				
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
				$ministryids =  $this->assign_ministryids($userid->userid);// chk assign project ids for login users 
				
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

}