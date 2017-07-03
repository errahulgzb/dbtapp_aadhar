<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_District extends Zend_Db_Table_Abstract 
{
        public function stateList()
        {
            $select_table = new Zend_Db_Table('dbt_state');
            $row = $select_table->fetchAll($select_table->select()->where('status =1')->order('id DESC'));
            return $row; 
        }
	
      public function insertDistrictstate($statecode,$districtname,$districtcode)
        {
            $district_table = new Zend_Db_Table('dbt_district');
            $date= time();
            $datainsert="";
            $datainsert = array(
                    'district_name'=> $districtname,
                    'district_code'=> $districtcode,
                    'state_code'=> $statecode,
                    'status'=> 1
            );
            $insertdata=$district_table->insert($datainsert);
            return $insertdata;	 
       }	
  		
	public function insertDistrict($dataform)
        {
            $district_table = new Zend_Db_Table('dbt_district');
            $date= time();
            $datainsert="";
            $datainsert = array(
                    'district_name'=> $dataform['title'],
                    'district_code'=> $dataform['district_code'],
                    'state_code'=> $dataform['state_id'],
                    'status'=> 1
            );
            $insertdata=$district_table->insert($datainsert);
            return $insertdata;	 
       }	


       
       public function importIntoTable($dataform,$tablename)
        {
           //print_r($dataform);die; 
           /*
            * $dataform[0] => state code
            * $dataform[1] => District code
            * $dataform[2] => Town code
            * $dataform[3] => Town Level
            * $dataform[4] => state/district/town name
            */
           
           /*
            * $dataform[0] => state code
            * $dataform[1] => District code
            * $dataform[2] => District name
            * $dataform[3] => Sub District code
            * $dataform[4] => Sub District name
            * $dataform[5] => Villege code
            * $dataform[6] => Villege name
            */
           $datainsert='';
           $district_table ='';
             if(($tablename=='dbt_state')&&($dataform[1]==0)&&($dataform[3]==0)&&($dataform[5]==0)){
              $district_table = new Zend_Db_Table('dbt_state');
               $datainsert = array(
                       'state_name'=> $dataform[2],
                       'state_code'=> $dataform[0],
                       'status'=> 1
               ); 
               $insertdata=$district_table->insert($datainsert);
            }
           if(($tablename=='dbt_district')&&($dataform[1]!=0)&&($dataform[3]==0)&&($dataform[5]==0)){
                $district_table = new Zend_Db_Table('dbt_district');
               $datainsert = array(
                       'district_name'=> $dataform[4],
                       'state_code'=> $dataform[0],
                       'district_code'=> $dataform[1],
                       'status'=> 1
               ); 
               $insertdata=$district_table->insert($datainsert);
           }              
           if(($tablename=='dbt_subdistrict')&&($dataform[1]!=0)&&($dataform[3]!=0)&&($dataform[5]==0)){
                $district_table = new Zend_Db_Table('dbt_subdistrict');
                $datainsert = array(
                       'subdistrict_name'=> $dataform[4],
                       'subdistrict_code'=> $dataform[3],
                       'district_code'=> $dataform[1],
                       'status'=> 1
                ); 
                $insertdata=$district_table->insert($datainsert);
           }
           
           if(($tablename=='dbt_village')&&($dataform[1]!=0)&&($dataform[3]!=0)&&($dataform[5]!=0)){
                $district_table = new Zend_Db_Table('dbt_village');
                $datainsert = array(
                       'village_name'=> $dataform[6],
                       'village_code'=> $dataform[5],
                       'subdistrict_code'=> $dataform[3],
                       'status'=> 1
                ); 
                $insertdata=$district_table->insert($datainsert);
           }
           return $insertdata;	 
       }	
       
       
       public function importIntoTable1($dataform,$tablename)
        {
           //print_r($dataform);die; 
           /*
            * $dataform[0] => state code
            * $dataform[1] => District code
            * $dataform[2] => Town code
            * $dataform[3] => Town Level
            * $dataform[4] => state/district/town name
            */
           
           /*
            * $dataform[0] => state code
            * $dataform[1] => District code
            * $dataform[2] => District name
            * $dataform[3] => Sub District code
            * $dataform[4] => Sub District name
            * $dataform[5] => Villege code
            * $dataform[6] => Villege name
            */
           
           /*
           $district_table = new Zend_Db_Table('dbt_state');
            $datainsert = array(
                'state_code'=> trim($dataform[0]),    
                'state_name'=> trim($dataform[1]),
                'state_short_name'=> trim($dataform[2]),   
                'status'=> 1
            ); 
            $insertdata=$district_table->insert($datainsert);
            */
           
           $district_table = new Zend_Db_Table('dbt_district');
            $datainsert = array(
                'state_code'=> trim($dataform[0]),    
                'district_code'=> trim($dataform[1]),
                'district_name'=> trim($dataform[2]),   
                'status'=> 1
            ); 
            $insertdata=$district_table->insert($datainsert);
           
           return $insertdata;	 
       }
       
        public function getDistricts($start,$limit)
        {   
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_district');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('district' => 'dbt_district'), array('district_name', 'id','district_code','created','status'));				
                $select->joinLeft(array('state' => 'dbt_state'), 'district.state_code = state.state_code', array('state_name'));
                //$select->where('district.status = ?', '1');
                $select->order('district.id DESC')->limit($limit,$start);
                $select_org = $select_table->fetchAll($select);
                return $select_org;
        }
		
		 public function getDistrictsstate($start,$limit,$state_code)
        {   
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_district');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('district' => 'dbt_district'), array('district_name', 'id','district_code','created','status'));				
                $select->joinLeft(array('state' => 'dbt_state'), 'district.state_code = state.state_code', array('state_name'));
                $select->where('district.state_code = ?', $state_code);
                $select->order('district.id DESC')->limit($limit,$start);
                $select_org = $select_table->fetchAll($select);
				//echo $select; die;
                return $select_org;
        }

		
		public function findstate($state_code)
        {   
            //echo $state_code;
                /* $select_table = new Zend_Db_Table('dbt_state');
                $select = $select_table->select();
          
                $select->from(array('state' => 'dbt_state'), array('state_name'));				
                $select->where('state.state_code = ?', $state_code);
                $select_org = $select_table->fetchAll($select);
				//echo $select; die;
                return $select_org; */
				
				
				$select_table = new Zend_Db_Table('dbt_state');
				$rowselect = $select_table->fetchRow($select_table->select()->where('state_code = ?',trim(intval($state_code))));
				 return $rowselect;     
				
				
				
		
		}
        
        public function countDistricts()
        {
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_district');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('district' => 'dbt_district'), array('district_name', 'id','district_code','created','status'));				
                $select->joinLeft(array('state' => 'dbt_state'), 'district.state_code = state.state_code', array('state_name'));
                //$select->where('district.status = ?', '1');
                $select->order('district.id DESC')->limit($limit,$start);
                $select_org = $select_table->fetchAll($select);
		return count($select_org); 
	  }
	  
	  
	   public function countDistrictsbasedstate($state_code)
        {
                $search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_district');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('district' => 'dbt_district'), array('district_name', 'id','district_code','created','status'));				
                $select->joinLeft(array('state' => 'dbt_state'), 'district.state_code = state.state_code', array('state_name'));
                $select->where('district.state_code = ?', $state_code);
                $select->order('district.id DESC')->limit($limit,$start);
                $select_org = $select_table->fetchAll($select);
		return count($select_org); 
	  }















/*
		public function insertDistrictDetails($dataform)
			{
			 
				$user_table = new Zend_Db_Table('dbt_district');
				$date= time();
				$datainsert="";
				$datainsert = array(
                                        'title'=> $dataform['title'] ,
                                        ''=> $dataform['projectname'] ,
                                        'customer_id'=> $dataform['customer'] ,
                                        'created'=> $date ,
                                        'status'=> 1
                                    );
					 $insertdata=$user_table->insert($datainsert);
					 return $insertdata;	 
				}
			 
           */ 
                        
                        
                        
                        
			
                            public function customeruser()
			{
				 $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->fetchAll($select_table->select()->where('status =1')->order('id DESC'));
				echo "ddddd";die;
				  return $row; 
				  
				  
				 /* 
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
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

				return $rowlist; */
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
			
			
		public function checkdistrictexists($statecode,$districtname,$districtcode)
	        {	
			
				
				
			/* 	$select_table = new Zend_Db_Table('dbt_district');
				
				$rowselect = $select_table->fetchAll($select_table->select()->where('state_code = ?',trim(($statecode)))->where('district_name = ?',trim(($districtname)))->where('district_code = ?',trim(($districtcode))));
				
				
				return count($rowselect);  */
				
				
				$select_table = new Zend_Db_Table('dbt_district');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_district'), array('district_code','state_code','district_name','status','created','updated'));
				$select->where('l.state_code=?',$statecode);
				$select->where('l.district_name=?',$districtname);
				$select->where('l.district_code=?',$districtcode);
				
						//echo $select; exit;
            
				$select_org = $select_table->fetchAll($select);
				return count($select_org);
				//return $select;
			
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

}