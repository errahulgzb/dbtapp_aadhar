<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Role extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';
		public function insertroledetails($dataform)
			{
			
				$user_table = new Zend_Db_Table('dbt_roles');

				$datainsert="";
				$datainsert = array(
							'title'=> $dataform['name'],
							'status'=> 1
							);
					 
					 $insertdata=$user_table->insert($datainsert);
					return $insertdata;
					  // echo "<pre>";
					// print_r($insertdata);
					//	echo "</pre>";
						//die;
				}
			
			public function rolelist($start,$limit)
			{
				  $select_table = new Zend_Db_Table('dbt_roles');
				  $row = $select_table->fetchAll($select_table->select()->order('title DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
			}
			public function roleuser($start,$limit){
				  $select_table = new Zend_Db_Table('dbt_roles');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC')->limit($limit,$start));
				  return $row; 
			}
			
	//get the scheme owner role from the role tables;
			public function schemeownerrole(){
				  $select_table = new Zend_Db_Table('dbt_roles');
				  $row = $select_table->fetchAll($select_table->select()->where('id in (4,12)')->order('title DESC'));
				  return $row; 
			}
	//get the state owner role from the role tables;
			public function stateownerrole(){
				  $select_table = new Zend_Db_Table('dbt_roles');
				  $row = $select_table->fetchAll($select_table->select()->where('id = ? ','12')->order('title DESC'));
				  return $row; 
			}		


			public function countrole()
			{
				  $count_table = new Zend_Db_Table('dbt_roles');
				  $count_row = $count_table->fetchAll($count_table->select()->order('title DESC'));
				  return count($count_row); 
			}



			public function editroleclient($id)
	        {
                $select_table = new Zend_Db_Table('dbt_roles');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}


			public function editroledetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$updatedetails_selecttable = new Zend_Db_Table('dbt_roles');

						$data="";
						$where="";
						$data = array('title'=> $editdataform['name']);
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function deleterole($id)
			{
					$delete_user = new Zend_Db_Table('dbt_roles');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_user->delete($where);

			}

			public function inactiverole($roleIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_roles');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $roleIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkroleclient($name)
	        {
				
				$select_table = new Zend_Db_Table('dbt_roles');

				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name))));
				return count($rowselect); 
			
			}

			public function checkroleclientEdit($name,$id)
	        {
				
				$select_table = new Zend_Db_Table('dbt_roles');

				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}
			public function getrolename($id)
			{
				$select_table = new Zend_Db_Table('dbt_roles');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				$rolename = $rowselect['title'];
				return $rolename;
			}



}