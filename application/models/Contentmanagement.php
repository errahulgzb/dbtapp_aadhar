<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Contentmanagement extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';
	
	
	
	/** $id is the current row id *********/
	public function getmenutype($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));

				
				
				 
				 
				$menu_type = $rowselect['menu_type'];
				return $menu_type;
				
			}
			
	/************* end **************/		
	
	
	
	
	public function gettranslationid($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));

				
				
				 
				 
				$translation_id = $rowselect['translation_id'];
				return $translation_id;
				
			}
	
		/** $id is the current row id *********/
	public function getsortorder($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));

				
				
				 
				 
				$sort_order = $rowselect['sort_order'];
				return $sort_order;
				
			}
			
	/************* end **************/	
		public function insertContentManagementdetails($dataform)
			{
			
				$user_table = new Zend_Db_Table('dbt_content_management');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				          'menu_type'=> $dataform['menu_type'],
				            'sort_order'=> $dataform['sort_order'],
							'title'=> $dataform['title'],
							'language'=> $dataform['lang'],
							'description'=> $dataform['description'],
							'status'=> 1,
                            'translation_id' => 0							
									);
							 // print_r($datainsert); exit;		
					
					 $insertdata=$user_table->insert($datainsert);
					 
					return $insertdata;
					 
				}
				
				
			public function insertContentManagementTranslationdetails($dataform,$menutyp,$sortorder,$rowid)
			{
			  
				$user_table = new Zend_Db_Table('dbt_content_management');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				             'menu_type' => $menutyp,
							'sort_order' => $sortorder,
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
			
			public function contentmanagementlist($start,$limit,$search)
			{   
			
			 // echo "model".$search;
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				
				
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_content_management'), array('title','menu_type','sort_order','translation_id','id','description','language','status','created','updated'));
					$select->joinLeft(array('langu' => 'dbt_language'), 'l.language = langu.id', array('langu.title as langname'));
				
				//$select->joinLeft(array('w' => 'dbt_language'),'w.language = l.id');
				//if($user_role==4){          // check role for admin 
					//$select->where('l.project_id IN (?)', $projectids);
				//}
				$select->where('l.translation_id!=1');
				//$select->where('l.language LIKE ?','%'.$search.'%')
				if($search!=""){         
				$select->where('l.menu_type =?',$search);
				}
				$select->order('l.id DESC')->limit($limit,$start);
						//echo $select; exit;
				$select_org = $select_table->fetchAll($select);
			
				//echo $select;
				//die;
				return $select_org;
			}

			public function countContentmanagement($search)
			{
				
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_content_management'), array('title','menu_type','sort_order','id','description','language','status','created','updated'));	
               $select->where('l.translation_id!=1');
				if($search!=""){         
				$select->where('l.menu_type =?',$search);
				}
				//$select->joinLeft(array('u' => 'dbt_users'), 'l.customer_id = u.id', array('firstname','lastname','organisation'));
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

			
			/*public function locationuser($start,$limit)
			{
				  $select_table = new Zend_Db_Table('locations');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
				  
				  
				  
			
			
			
			
			}*/

		/*	public function customeruser()
			{
				 // $select_table = new Zend_Db_Table('dbt_users');
				//  $row = $select_table->fetchAll($select_table->select()->where('status =1 and role=3')->order('id DESC'));
				
				 // return $row; 
				  
				  
				  
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

				return $rowlist; 
			}
			*/
			
			
			
			/*****************Assigned Installation Engineer List*************/
			public function assign_projectids($pmid=null)
			{
				$nm22 = new Zend_Db_Table('dbt_assign_manager');
				$select = $nm22->select();
				$select->from(array('am' => 'dbt_assign_manager'), array( 'project_id'));	
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
			
			
			public function language()
			{
				  $select_table = new Zend_Db_Table('dbt_language');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC'));
				
				  return $row; 
			}

			/*public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('locations');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				  
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
				//$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('title ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  
				return $rowlist; 
			}
			*/
			


			public function editcontentmanagement($id)
	        {
                $select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}

			
			/*public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('locations');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}*/
			
			

			public function editcontentmanagementdetails($editdataform,$id,$translationid)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');

						$data="";
						$where="";						
						$data = array(
						   'menu_type'=> $editdataform['menu_type'] ,
						    'sort_order'=> $editdataform['sort_order'] ,
							'title'=> $editdataform['title'] ,
							'description'=> $editdataform['description']						
							);					
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
							$updatedetails_selecttable_menu = new Zend_Db_Table('dbt_content_management');
						
						$datanew="";
						$wherenew="";						
						$datanew = array(
					
							'menu_type'=> $editdataform['menu_type'],
                           'sort_order'=> $editdataform['sort_order'] 							
							);
						$wherenew= array('id = ?'=> $translationid);
						//print_r($rowid);
						//print_r($data);
						//exit;
						$update_valuesnew = $updatedetails_selecttable_menu->update($datanew, $wherenew);
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
						  
							'title'=> $editdataform['title'] ,

							'description'=> $editdataform['description'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			/*public function deletelocation($id)
			{
					$delete_project = new Zend_Db_Table('locations');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_project->delete($where);

			}*/

			public function inactivecontentmanagement($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkcontent($name)
	        {	
			$select_table = new Zend_Db_Table('dbt_content_management');
			$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name))));
				return count($rowselect); 
			}

			public function ominsertdata($dataform)
			{ 
				//print"dsd<pre>";print_r($dataform);die;
				$user_table = new Zend_Db_Table('dbt_om');
				$createdtime = date("Y-m-d H:i:s");
				
				if($dataform['sort_order']==0) $dataform['sort_order']=NULL;
				if($dataform['sub_category']==0) $dataform['sub_category']=NULL;

				$datainsert = array(
					'title' => $dataform['title'],
					'filenumber' => $dataform['filenumber'],
					'filedate' => $dataform['filedate'],
					'sort_order'=> $dataform['sort_order'],
					'language'=> $dataform['language'],
					'category'=> $dataform['category'],
					'subcategory'=> $dataform['sub_category'],
					'filename'=> $dataform['filename'],
					'filepath'=> $dataform['filepath'],
					'created'=> $createdtime,
					'status'=> 1,				
				);

				$insertdata=$user_table->insert($datainsert);
				
			}
			
			public function get_om_category()
			{ 
			  $select_table = new Zend_Db_Table('dbt_category');
			  $row = $select_table->fetchAll($select_table->select());
			  return $row; 
			}
			
			public function get_om_subcategory($cat_id)
			{
			  $select_table = new Zend_Db_Table('dbt_subcategory');
			  $row = $select_table->fetchAll($select_table->select()->where('cat_id = ?',trim(($cat_id))));
			  return $row; 
			}
			
			public function omcontentlist($start,$limit,$search)
			{   			
				// echo "model".$search;
			    //$role = new Zend_Session_Namespace('role');
				//$userid = new Zend_Session_Namespace('userid');
				//$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				//$user_role = $role->role;
				//die('aaaaaaaaaaaaaa');
				$select_table = new Zend_Db_Table('dbt_om');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_om'), array('id','title','category','subcategory','status','created','updated'));
				$select->joinLeft(array('langu' => 'dbt_language'), 'l.language = langu.id', array('langu.title as langname'));
				$select->joinLeft(array('cat' => 'dbt_category'), 'l.category = cat.id', array('cat.title as category'));
				$select->joinLeft(array('subcat' => 'dbt_subcategory'), 'l.subcategory = subcat.id', array('subcat.title as subcategory'));
				
				//$select->joinLeft(array('w' => 'dbt_language'),'w.language = l.id');
				//if($user_role==4){          // check role for admin 
					//$select->where('l.project_id IN (?)', $projectids);
				//}
				//$select->where('l.translation_id!=1');
				//$select->where('l.language LIKE ?','%'.$search.'%')
				if($search!=""){   
				
			if($search!=""){   
			
				$catr = $this->getAdapter()->quoteInto('cat.title LIKE ?', "%$search%");
				$titler = $this->getAdapter()->quoteInto('l.title LIKE ?', "%$search%");
				
				
				$select->where($catr." OR ".$titler);
				//$select->orWhere('l.category LIKE ?', "%$search%");
				}
				}
				$select->order('l.id DESC')->limit($limit,$start);

				$get_records = $select_table->fetchAll($select);//print"<pre>";print_r($get_records);die;

				return $get_records;
				
			}
			
			public function countomrecords($search)
			{
				//die('sdsd');
				//$role = new Zend_Session_Namespace('role');
				//$userid = new Zend_Session_Namespace('userid');
				//$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				//$user_role = $role->role;
				$select_table = new Zend_Db_Table('dbt_om');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_om'), array('title'));	
				$select->joinLeft(array('cat' => 'dbt_category'), 'l.category = cat.id', array('cat.title as category'));
				//$select->where('l.translation_id!=1');
				if($search!=""){         
				$catr = $this->getAdapter()->quoteInto('cat.title LIKE ?', "%$search%");
				$titler = $this->getAdapter()->quoteInto('l.title LIKE ?', "%$search%");
				$select->where($catr." OR ".$titler);
				}
				$select_org = $select_table->fetchAll($select);//echo count($select_org);die;
				return count($select_org); 
			}
			
			public function omeditdata($id)
	        {
                $select_table = new Zend_Db_Table('dbt_om');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				return $rowselect;     
			
			}
			
			public function editomdetails($editdataform,$id)
	        {
		
				$update =time("Y-m-d H:i:s");
				
				if($editdataform['sort_order']==0) $editdataform['sort_order']=NULL;
				if($editdataform['sub_category']==0) $editdataform['sub_category']=NULL;
				
				
				/*$select = $updatedetails_selecttable->select();
				$select->setIntegrityCheck(false);
				$select->from(array('c' => 'dbt_category'), array('c.title as cat','c.id'));
				$select->joinLeft(array('s' => 'dbt_subcategory'), 'c.id = s.cat_id', array('s.title as subcat','s.id'));
				$select->where('c.id =?',$editdataform['category']);
				$select->where('s.id =?',$editdataform['sub_category']);
				$select_org = $updatedetails_selecttable->fetchRow($select);//echo $select;die;
				
				//$subcat=$select_org['subcat'];
				//$cat=$select_org['cat'];
				
				echo"<pre>";print_r($select_org);die;*/
				
				$data="";
				$where="";						
				$data = array(
					'title'=> $editdataform['title'] ,
					'filenumber'=> $editdataform['filenumber'] ,
					'filedate'=> $editdataform['file_date'] ,
					'sort_order'=> $editdataform['sort_order'],						
					'language'=> $editdataform['language'],						
					'category'=> $editdataform['category'],						
					'subcategory'=> $editdataform['sub_category']																
				);	
				
				if(isset($editdataform['filename'])) 
					$data['filename']=$editdataform['filename'];
				
				if(isset($editdataform['filepath'])) 
					$data['filepath']=$editdataform['filepath'];		
			
				$where = array('id = ?'=> $id);
				
				$updatedetails_selecttable = new Zend_Db_Table('dbt_om');
				$update_values = $updatedetails_selecttable->update($data, $where);

			}
			
			public function omgetsubcatdata($id)
	        { 
				$updatedetails_selecttable = new Zend_Db_Table('dbt_subcategory');
                $select = $updatedetails_selecttable->select();
				$select->setIntegrityCheck(false);
				$select->from(array('s' => 'dbt_subcategory'), array('s.title','s.id'));
				$select->where("cat_id=(select category from dbt_om where id=$id)");
				$select_org = $updatedetails_selecttable->fetchAll($select);
				
				return $select_org;     
			
			}
			
			public function inactiveom($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_om');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					//print $data."       ";print $where;die;
					$update_values = $updatedetails_selecttable->update($data, $where);
			}
			
			public function documentomlist()
			{   			
				$cat=4;
				$select_table = new Zend_Db_Table('dbt_om');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('om' => 'dbt_om'), array('id','title as doctitle','filepath','filename'));
				$select->joinLeft(array('cat' => 'dbt_category'), 'om.category = cat.id');
				$select->where("om.category = ?", $cat);
				$select_org = $select_table->fetchAll($select);//echo count($select_org);die;
				return $select_org;
			}

//

}
