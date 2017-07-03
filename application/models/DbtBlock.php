<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbtBlock extends Zend_Db_Table_Abstract 
{
        public function stateList()
        {
            $select_table = new Zend_Db_Table('dbt_state');
            $row = $select_table->fetchAll($select_table->select()->where('status =0')->order('id DESC'));
            return $row; 
        }
        		
	public function insertDistrict($dataform)
        {
            $district_table = new Zend_Db_Table('dbt_district');
            $date= time();
            $datainsert="";
            $datainsert = array(
                    'title'=> $dataform['title'] ,
                    'state_id'=> $dataform['state_id'],
                    'district_code'=> $dataform['district_code'] ,
                    'status'=> 1
            );
            $insertdata=$district_table->insert($datainsert);
            return $insertdata;	 
       }	


       
        public function getBlock($start,$limit)
        {   
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_block');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('block' => 'dbt_block'), array('title', 'id','district_id','state_code','block_code','status'));				
                $select->joinLeft(array('district' => 'dbt_district'), 'block.district_id = district.district_code', array('title as district_name'));
                $select->joinLeft(array('state' => 'dbt_state'), 'block.state_code = state.state_code', array('title as state_name'));
                //$select->where('district.status = ?', '1');
                $select->order('block.id DESC')->limit($limit,$start);
                //echo $select;exit;
                $select_org = $select_table->fetchAll($select);
               // echo "<pre>";print_r($select_org);exit;
                return $select_org;
        }

        
        public function countDistricts()
        {
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_district');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('district' => 'dbt_district'), array('title', 'id','district_code','created','status'));				
                $select->joinLeft(array('state' => 'dbt_state'), 'district.state_id = state.id', array('title as state_name'));
                //$select->where('district.status = ?', '1');
                $select->order('district.id DESC')->limit($limit,$start);
                $select_org = $select_table->fetchAll($select);
		return count($select_org); 
	}











       
                        
                        
			
                            public function customeruser()
			{
				 $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->fetchAll($select_table->select()->where('status =1')->order('id DESC'));
				echo "ddddd";die;
				  return $row; 
				  
				  
			}
			         
                                
                                
                                public function locationlist($start,$limit)
			{   
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				
				$search = $_GET['search'];
				$select_table = new Zend_Db_Table('locations');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'locations'), array('title', 'id','created','project_id','changed','status'));				
				$select->joinLeft(array('u' => 'dbt_users'), 'l.customer_id = u.id', array('firstname','lastname','organisation'));
				$select->joinLeft(array('p' => 'projects'), 'l.project_id = p.id', array('p.title as projectname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.project_id IN (?)', $projectids);
				}
				
				$select->where('l.title LIKE ? OR p.title LIKE ? OR u.organisation LIKE ?', '%'.$search.'%', '%'.$search.'%', '%'.$search.'%')
				//$select->ORwhere('p.title LIKE ?', '%'.$search.'%');
				//$select->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				return $select_org;
			}

			public function countlocation()
			{
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				
				$search = $_GET['search'];
				$select_table = new Zend_Db_Table('locations');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'locations'), array('title', 'id','created','project_id','changed','status'));				
				$select->joinLeft(array('u' => 'dbt_users'), 'l.customer_id = u.id', array('firstname','lastname','organisation'));
				$select->joinLeft(array('p' => 'projects'), 'l.project_id = p.id', array('p.title as projectname'));
				if($user_role==4){          // check role for admin 
					$select->where('l.project_id IN (?)', $projectids);
				}
				
				$select->where('l.title LIKE ? OR p.title LIKE ? OR u.organisation LIKE ?', '%'.$search.'%', '%'.$search.'%', '%'.$search.'%')
				//$select->ORwhere('p.title LIKE ?', '%'.$search.'%');
				//$select->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
					
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
			
			
			public function roleuser()
			{
				  $select_table = new Zend_Db_Table('projects');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC'));
				
				  return $row; 
			}

			public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('locations');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				 // echo $projectrow['project_id'];
				//echo "<pre>";print_r($projectrow); exit;
				  // $select_table = new Zend_Db_Table('projects');
				  // $row = $select_table->fetchAll($select_table->select()->where('status = 1 and customer_id='.$projectrow['customer_id'])->order('title DESC'));
				  // return $row; 
				  
				  
				  $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				
				$select_table = new Zend_Db_Table('projects');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'projects'), array( 'id','title'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $projectids);	
				}	
				$select_query->where('status=1');
				$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('title ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  
				return $rowlist; 
			}
			
			


			public function editlocationclient($id)
	        {
                $select_table = new Zend_Db_Table('locations');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}

			
			public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('locations');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}
			
			

			public function editlocationdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('locations');

						$data="";
						$where="";						
						$data = array(
							'title'=> $editdataform['title'] ,
							'project_id'=> $editdataform['projectname'] ,
							'customer_id'=> $editdataform['customer'] ,	
							'changed'=> $date,
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

			public function inactivelocation($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('locations');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checklocationclient($name,$project)
	        {	
			
				
				
				
				$select_table = new Zend_Db_Table('locations');
				
				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name)))->where('project_id = ?',trim(($project))));
				
				
				return count($rowselect); 
			
			}

			public function checklocationclientEdit($name,$id,$projectname)
	        {
				
				$select_table = new Zend_Db_Table('locations');

				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name)))->where('project_id = ?',trim(($projectname)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}

// function for ajax project
	
		public function projectlocationlist($customer_id)
			{  
				  // $select_table = new Zend_Db_Table('projects');
				  // $row = $select_table->fetchAll($select_table->select()->where('customer_id ='.$customer_id)->order('title DESC'));
				  
				
				 // return $row; 
				 
				 
				 $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				
				$select_table = new Zend_Db_Table('projects');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'projects'), array( 'id','title'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $projectids);	
				}	
				$select_query->where('customer_id=?',$customer_id);
				$select_query->where('status=1');
				$select_query->order('title ASC');				
				$rowlist = $select_table->fetchAll($select_query);
					
					// $select_table1 = new Zend_Db_Table('projects');
				   // $rowlist1 = $select_table1->fetchAll($select_table1->select()->where('customer_id = ?',trim(intval($id)))->where('status =1')->where('id IN (?)', $projectids)->order('title ASC'));
							
						
				  return $rowlist; 
			}

			public function importIntoTable($dataform,$tablename)
        	{
        		// echo $tablename;
        		// echo "<pre>";
	         //   print_r($dataform);die; 
	           
	           if($tablename=='dbt_block'){
	                $district_table = new Zend_Db_Table('dbt_block');
	               $date= time();
	               $datainsert="";
	               $datainsert = array(
	                       'title'=> ltrim($dataform[4],"'"),
	                       'block_code'=> ltrim($dataform[3],"'"),
	                       'district_id'=> ltrim($dataform[1],"'"),
	                       'state_code'=> ltrim($dataform[0],"'"),
	                       'status'=> 1
	               );  
	           }else if($tablename=='dbt_village'){
	                $district_table = new Zend_Db_Table('dbt_village');
	                $date= time();
	                $datainsert="";
	                $datainsert = array(
	                       'title'=> ltrim($dataform[6],"'"),
	                       'village_code'=> ltrim($dataform[5],"'"),
	                       'block_code'=> ltrim($dataform[3],"'"),
	                       'district_code'=> ltrim($dataform[1],"'"),
	                       'state_code'=> ltrim($dataform[0],"'"),
	                       'status'=> 1
	                );  
	           }
	           
	            $insertdata=$district_table->insert($datainsert);
	            return $insertdata;	 
       	}

}