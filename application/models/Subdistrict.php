<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Subdistrict extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';
		public function insertsubdistrict($dataform)
			{
			
				$user_table = new Zend_Db_Table('dbt_subdistrict');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				          'subdistrict_code'=> $dataform['subdistrict_code'],
				            'district_code'=> $dataform['district_code'],
							'state_code'=> '',
							'subdistrict_name'=> $dataform['subdistrict_name'],
							'status'=> 1
                   						
									);
							 // print_r($datainsert); exit;		
					
					 $insertdata=$user_table->insert($datainsert);
					 
					return $insertdata;
					 
				}
				
			public function getdistrictbaseddtate($statecode)
			{
				

				
				$select_table = new Zend_Db_Table('dbt_district');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'dbt_district'), array( 'district_code','district_name'));		
				$select_query->where('state_code=?',$statecode);		
				$select_query->where('status=1');
				$select_query->order('state_code ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				return $rowlist; 
			}
			public function insertContentManagementTranslationdetails($dataform,$rowid)
			{
			  
				$user_table = new Zend_Db_Table('dbt_content_management');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				             'menu_type' => '',
							'sort_order' => '',
							'title'=> $dataform['title'],
							'language'=> $dataform['lang'],
							'description'=> $dataform['description'],
							'status'=> 1,
                            'translation_id' => 1							
									);
							 // print_r($datainsert); exit;		
					
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
			
			public function subdistrictlist($start,$limit,$search)
			{   
			
			  //echo "model".$search;
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				
				
				$select_table = new Zend_Db_Table('dbt_subdistrict');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_subdistrict'), array('id','subdistrict_code','district_code','state_code','subdistrict_name','status','created','updated'));
				
				
				
			
				$select->joinLeft(array('district' => 'dbt_district'), 'l.district_code = district.district_code', array('district.district_name as district_name'));
				
				
				$select->joinLeft(array('state' => 'dbt_state'), 'district.state_code = state.state_code', array('state.state_name as state_name'));
				
				//$select->joinLeft(array('w' => 'dbt_language'),'w.language = l.id');
				//if($user_role==4){          // check role for admin 
					//$select->where('l.project_id IN (?)', $projectids);
				//}
				//$select->where('l.translation_id!=1');
				$select->where('l.subdistrict_name LIKE ?','%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
						//echo $select; exit;
            
				$select_org = $select_table->fetchAll($select);
			
				//echo $select;
				//die;
				return $select_org;
			}

			public function countstate()
			{
				
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				$select_table = new Zend_Db_Table('dbt_subdistrict');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_subdistrict'),array('id','subdistrict_code','district_code','state_code','subdistrict_name','status','created','updated'));	
				
				//$select->joinLeft(array('u' => 'users'), 'l.customer_id = u.id', array('firstname','lastname','organisation'));
				//$select->joinLeft(array('p' => 'projects'), 'l.project_id = p.id', array('p.title as projectname'));
				
				//$select->where('l.title LIKE ? OR u.organisation LIKE ?', '%'.$search.'%', '%'.$search.'%', '%'.$search.'%')
				//$select->ORwhere('p.title LIKE ?', '%'.$search.'%');
				//$select->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				//->order('l.id DESC')->limit($limit,$start);
					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				return count($select_org); 
			}

			
			public function locationuser($start,$limit)
			{
				  $select_table = new Zend_Db_Table('locations');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC')->limit($limit,$start));
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
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login users 
				$user_role = $role->role;
				// select distinct customer from projects table 				
				
				$nm22 = new Zend_Db_Table('projects');
				$select = $nm22->select();
				$select->from(array('p' => 'projects'), array( 'customer_id'));	
				if($user_role==4){          // check role for admin 
					$select->where('p.id IN (?)', $projectids);
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
				
				$select_table = new Zend_Db_Table('users');
				$select_query =  $select_table->select();
				$select_query->from(array('u' => 'users'), array( 'id','organisation','firstname','lastname','username'));	

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
			public function assign_projectids($pmid=null)
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
				   $pids[] = $val[project_id];
				  
				   
				}
				$arr[] = implode(',',$pids);
				$abc = end($arr);
				$str = explode(',',$abc);
				 return $str;


			}
			
			
			public function district()
			{
				  $select_table = new Zend_Db_Table('dbt_district');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('district_name ASC'));
				
				  return $row; 
			}
			
				
			public function state( )
			{
				
				
				
				
				  $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('state_name ASC'));
				
				  return $row; 
			}
			
			public function statecode($subdistrictid)
			{
			/******* return district_code from the dbt_subdistrict tble*******/
				    $select_table = new Zend_Db_Table('dbt_subdistrict');
					$select_query =  $select_table->select();
					$select_query->from(array('p' => 'dbt_subdistrict'), array( 'district_code'));		
					$select_query->where('id=?',$subdistrictid);		
					$select_query->where('status=1');			
					$rowlist = $select_table->fetchAll($select_query);
					$val = $rowlist->toArray(); 
				
			      $districtcode = $val[0]['district_code']; 
				   // return $districtcode;
				  /***************** end ****************/
				  
				  
				 	/******* return state_code from the dbt_district tble*******/
					
					$select_table = new Zend_Db_Table('dbt_district');
					$select_query =  $select_table->select();
					$select_query->from(array('p' => 'dbt_district'), array( 'state_code'));		
					$select_query->where('district_code=?',$districtcode);		
					$select_query->where('status=1');			
					$rowlist = $select_table->fetchAll($select_query);
					$val = $rowlist->toArray(); 
				
			         $statecode = $val[0]['state_code']; 
				   return $statecode;
					/******************* end *********/
			
			}

			public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('locations');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				  
				  $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login users 
				
				$select_table = new Zend_Db_Table('projects');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'projects'), array( 'id','title'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $projectids);	
				}	
				$select_query->where('status=1');
				//$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('title ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  
				return $rowlist; 
			}
			
			


			public function editsubdistrict($id)
	        {
                $select_table = new Zend_Db_Table('dbt_subdistrict');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}

			
			public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('locations');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}
			
			

			public function editsubdistrictdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_subdistrict');

						$data="";
						$where="";						
						$data = array(
						  
                           'subdistrict_code'=> $editdataform['subdistrict_code'],
				            'district_code'=> $editdataform['district_code'],
							'state_code'=> '',
							'subdistrict_name'=> $editdataform['subdistrict_name'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}
			
			
		public function editcontentmanagementtranslationdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');

						$data="";
						$where="";						
						$data = array(
						   'menu_type'=> '',
						    'sort_order'=> '',
							'title'=> $editdataform['title'] ,

							'description'=> $editdataform['description'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function deletelocation($id)
			{
					$delete_project = new Zend_Db_Table('locations');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_project->delete($where);

			}

			public function inactivesubdistrict($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_subdistrict');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkcontent($subdistrictname,$subdistrictcode)
	        {	
			  //echo $subdistrictname; 
			 //echo  $subdistrictcode;
			//$select_table = new Zend_Db_Table('dbt_district');
			//$rowselect = $select_table->fetchAll($select_table->select()->where('district_name = ?',trim(($name))));
				//return count($rowselect); 
				$data_table = new Zend_Db_Table('dbt_subdistrict');
				$select = $data_table->select();
				$select->where('subdistrict_name = ?', $subdistrictname);
				$select->ORwhere('subdistrict_code = ?', $subdistrictcode);
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				//echo $select;
				return $recordcount;
			}

			

//

}
