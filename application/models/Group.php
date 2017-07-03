<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Group extends Zend_Db_Table_Abstract  
{
	// protected $_name = 'group';
		public function insertgroupdetails($dataform)
			{
			
				$user_table = new Zend_Db_Table('grouping');
				$date=date("Y-m-d H:i:s");
				$datainsert="";
				$datainsert = array(
							'groupname'=> $dataform['name'],
							'description'=>	$dataform['description'],	
							'dateAdded'=>$date,
							'dateModify'=>$date,
							'status'=> 1 ,
							
									);
				$insertdata=$user_table->insert($datainsert);
				//return $insertdata;
				$lastId= $insertdata->lastInsertId();
				//return $lastId;
				// die;
				
					 //  echo "<pre>";
					// print_r($insertdata);
					//	echo "</pre>";
					//die;     
   
				$user_table1=new Zend_Db_Table('group_user');
				$datainsert1="";
				$datainsert1= array(
							 'groupid'=> $lastId;
								 //'uid'=> $dataform['uid'];
							 ); 
				
				$insertdata1=$user_table1->insert($datainsert1);
				return $insertdata1; 
					//echo "<pre>";
					// print_r($insertdata1);
					//echo "</pre>";
						//die;
				}
			
			public function grouplist($start,$limit)
			{
				  $select_table = new Zend_Db_Table('group');
				  $row = $select_table->fetchAll($select_table->select()->order('name DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
			}


			public function countgroup()
			{
				  $count_table = new Zend_Db_Table('group');
				  $count_row = $count_table->fetchAll($count_table->select()->order('name DESC'));
				  return count($count_row); 
			}



			public function editgroupclient($id)
	        {
                $select_table = new Zend_Db_Table('group');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
				
			}


			public function editgroupdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$updatedetails_selecttable = new Zend_Db_Table('group');

						$data="";
						$where="";
						$data = array('name'=> $editdataform['name']);
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function deletegroup($id)
			{
					$delete_user = new Zend_Db_Table('group');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_user->delete($where);

			}


}