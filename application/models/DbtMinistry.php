<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbtMinistry extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';
		public function insertministrydetails($dataform)
		{
			$user_table = new Zend_Db_Table('dbt_ministry');
			$datainsert="";
			$var_ttl = $dataform['ministry_name'];
				
			$row1 = $user_table->fetchRow($user_table->select()->where( 'ministry_name = ?',$var_ttl));
	 
			if(count($row1)>0)
				{
	             return 'Already Exist';
	            }				
				else{
				$datainsert = array(
							'ministry_name'=> $dataform['ministry_name'],
							'translation_id' => 0,
							'language' => $dataform['lang'],
							'status'=> 1							
							);					 
						$insertdata=$user_table->insert($datainsert);

				
				return $insertdata;
			       }	 
		}	//insertministrydetails end
		
		public function insertministrydetailsfirst($dataform,$rowid)
		{
				
						$user_table = new Zend_Db_Table('dbt_ministry');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				             'ministry_name'=> $dataform['ministry_name'] ,
							'translation_id' => 1,
							'language' =>  $dataform['lang'] ,
							'status'=> 1							
									);
							 // print_r($datainsert); exit;		
					
					 $insertdata=$user_table->insert($datainsert);
					 
					    $data="";
						$where="";						
						$data = array(
					
							'translation_id'=> $insertdata						
							);
						$where = array('id = ?'=> $rowid);
						/* print_r($rowid);
						print_r($data);
						exit; */
						$update_values = $user_table->update($data,$where);
					 
					return $insertdata;

			       }
			public function ministrylist($start,$limit)
			{
				$search = safexss($_GET['search']);
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('p' => 'dbt_ministry'), array('ministry_name','translation_id','id','status'))
				//->where('p.ministry_name LIKE ?', '%'.$search.'%');
				
				->where('p.translation_id!=1')
				->where('p.ministry_name LIKE ?', '%'.$search.'%')
				->order('p.id DESC')->limit($limit,$start);
				//->order('p.ministry_name DESC')->limit($limit,$start);	

				$select_org = $select_table->fetchAll($select);
				return $select_org;
			}

		   public function countministry()
			{
				 $search = safexss($_GET['search']);
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('p' => 'dbt_ministry'), array('ministry_name','status','translation_id'))
				->where('p.translation_id!=1')
				->where('p.ministry_name LIKE ?', '%'.$search.'%')
				//->where('p.translation_id ==1')
				->order('p.id DESC');
				$select_org = $select_table->fetchAll($select);
			   return count($select_org); 
			}
			
			public function projectuser()
			{
				  $select_table = new Zend_Db_Table('dbt_ministry');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('ministry_name DESC'));
				  return $row; 			
			}
			public function editprojectclient($id)
	        {
				
                $select_table = new Zend_Db_Table('dbt_ministry');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}

			
			public function edituserclient($id)
	        {
				
                 $select_table = new Zend_Db_Table('dbt_ministry');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}
			
			

			public function editschemedetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//print_r($editdataform);
				//die;
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_ministry');

						$data="";
						$where="";						
						$data = array(
							'ministry_name'=> $editdataform['ministry_name']
							);
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function deleteproject($id)
			{
					$delete_project = new Zend_Db_Table('dbt_ministry');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_project->delete($where);
//==================================
				//Update master data table (no of dipartments)
						$data_table = new Zend_Db_Table('dbt_home_page_master_data_current_year');
						$select = $data_table->select();
						$select_org = $data_table->fetchAll($select);
						$masterdatacount = count($select_org);

						$select = $user_table->select();
						$select_org = $user_table->fetchAll($select);
						$ministrycount = count($select_org);

						if ($masterdatacount == 0) {
							$datainsert="";
							$datainsert = array(
								'number_of_departments'=> $ministrycount,
								'status'=> 1
							);
							$insertdata=$data_table->insert($datainsert);
						} else {
							$data="";
							$data = array('number_of_departments'=> $ministrycount);
							$update_values = $data_table->update($data);
						}
				//Update master data table (no of dipartments) end
//==================================

			}

			public function inactiveministry($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_ministry');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			
			
			public function inactiveproject($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_ministry');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkprojectclient($name)
	        {	
			
				
				
				
				$select_table = new Zend_Db_Table('dbt_ministry');
				
				$rowselect = $select_table->fetchAll($select_table->select()->where('ministry_name = ?',trim(($name))));
				
				
				return count($rowselect); 
			
			}

			public function checkministryclientEdit($name,$id)
	        {
				
				$select_table = new Zend_Db_Table('dbt_ministry');

				$rowselect = $select_table->fetchAll($select_table->select()->where('ministry_name = ?',trim(($name)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}
			public function checkPOAclientEdit($cust,$poa)
	        {
				
				$project_poa = new Zend_Db_Table('dbt_ministry');

				$cust_id = $project_poa->fetchAll($project_poa->select()->where( 'plan_of_act = ?',trim($poa))->where('customer_id != ?',trim($cust)));
				return count($cust_id); 
			
			}
			public function roleuser()
			{
				  $select_table = new Zend_Db_Table('dbt_ministry');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('ministry_name DESC'));
				
				  return $row; 
			}
			public function ministryUser($minid = null){
				  $select_table = new Zend_Db_Table('dbt_ministry');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->where('id=?',$minid)->order('ministry_name DESC'));
				  return $row; 
			}
			public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('dbt_scheme');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				  
				  $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				//$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login users 
				
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'dbt_ministry'), array( 'id','ministry_name'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $projectids);	
				}	
               	$select_query->where('translation_id!=1');
				$select_query->where('status=1');
				//$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('ministry_name ASC');	
					//echo $select_query;exit;			
				$rowlist = $select_table->fetchAll($select_query);
				return $rowlist; 
			}
			public function assign_projectids($pmid=null){
				$nm22 = new Zend_Db_Table('assign_manager');
				$select = $nm22->select();
				$select->from(array('am' => 'assign_manager'), array('project_id'));	
				$select->where('pm_id=?',trim(intval($pmid)));			
				$select->where('status=1');
				$row22 = $nm22->fetchAll($select);
				$ids =  $row22->toArray();
				$pids = array(); 
				$arr  = array();
				foreach($ids as $key=>$val)
				{
				   $pids[] = $val['project_id'];
				}
				$arr[] = implode(',',$pids);
				$abc = end($arr);
				$str = explode(',',$abc);
				 return $str;
			}
			public function getministryname($id)
			{
				$select_table = new Zend_Db_Table('dbt_ministry');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?', $id));
				$ministryname = $rowselect['ministry_name'];
				return $ministryname;
			}
	
			

}