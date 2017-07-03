<?php
require_once 'Zend/Session.php';
require_once 'Zend/Db/Table/Abstract.php';
require_once 'Zend/Session.php';
class Application_Model_Folder extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'folders';
		
		public $_name = 'documents';
		public function insertfolderdetails($dataform,$level)
			{
					$userid = new Zend_Session_Namespace('userid');
					
				//echo $folder->folder;
				//die;
				$folder_table = new Zend_Db_Table('folders');
				if($dataform['parent_id']=="")
				{
					$dataform['parent_id'] =1;
				}
				$date=date("Y-m-d H:i:s");
				$datainsert="";
				$datainsert = array('name'=> $dataform['name'],'description'=> $dataform['description'],'display_status'=> 1,'created'=>$date,
							'owner'=>$userid->userid,'pub_pri'=>$dataform['display_status'],'parent'=>$dataform['parent_id'],'level'=>($level+1));
					 
				$insertdata=$folder_table->insert($datainsert);
				$select_table = new Zend_Db_Table('folders');
				$rowselect = $select_table->fetchRow($select_table->select()->where('name = ?',$dataform['name']));


				// echo  $rowselect["fid"]."name=".$rowselect["name"];   
				//return $rowselect["fid"];
				$fidInitial = $rowselect["parent"];
				$storeArray = $rowselect["fid"].",".$rowselect["parent"];
				$nameoffolder = $rowselect["name"];
					
				$select_table1 = new Zend_Db_Table('folders');
				$rowselect1 = $select_table1->fetchRow($select_table1->select()->where('fid = ?',$dataform['parent_id']));
				$nameofParent = $rowselect1["folder_path"];
				
				//$folder_path = $nameofParent;
				if(isset($nameofParent))
				{
					$folder_path = $nameofParent."/".$nameoffolder;
				}
					$updatedetails_selecttable = new Zend_Db_Table('folders');

					$data1 = array('folder_path'=> $folder_path);
					$where1 = array('fid = ?'=> $rowselect["fid"]);
						$update_values = $updatedetails_selecttable->update($data1, $where1);
					//	echo dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path;
						//die;
						if(!is_dir(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path))
						{
							mkdir(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path);
							chmod(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path,0777);
						}
				
				return $rowselect["fid"];
				//print_r($storeArray);
			    //return $insertdata;
					  // echo "<pre>";
					// print_r($insertdata);
					//	echo "</pre>";
						//die;
		}

		public function insertfolderdetailswithPrivate($dataform,$level)
			{
					$userid = new Zend_Session_Namespace('userid');
					
				//echo $folder->folder;
				//die;
				$folder_table = new Zend_Db_Table('folders');
				if($dataform['parent_id']=="")
				{
					$dataform['parent_id'] =1;
				}
				$date=date("Y-m-d H:i:s");
				$datainsert="";
				$datainsert = array('name'=> $dataform['name'],'description'=> $dataform['description'],'display_status'=> 1,'created'=>$date,
							'owner'=>$userid->userid,'pub_pri'=>0,'parent'=>$dataform['parent_id'],'level'=>($level+1));
					 
				$insertdata=$folder_table->insert($datainsert);
				$select_table = new Zend_Db_Table('folders');
				$rowselect = $select_table->fetchRow($select_table->select()->where('name = ?',$dataform['name']));


				// echo  $rowselect["fid"]."name=".$rowselect["name"];   
				//return $rowselect["fid"];
				$fidInitial = $rowselect["parent"];
				$storeArray = $rowselect["fid"].",".$rowselect["parent"];
				$nameoffolder = $rowselect["name"];
					
				$select_table1 = new Zend_Db_Table('folders');
				$rowselect1 = $select_table1->fetchRow($select_table1->select()->where('fid = ?',$dataform['parent_id']));
				$nameofParent = $rowselect1["folder_path"];
				
				//$folder_path = $nameofParent;
				if(isset($nameofParent))
				{
					$folder_path = $nameofParent."/".$nameoffolder;
				}
					$updatedetails_selecttable = new Zend_Db_Table('folders');

					$data1 = array('folder_path'=> $folder_path);
					$where1 = array('fid = ?'=> $rowselect["fid"]);
						$update_values = $updatedetails_selecttable->update($data1, $where1);
					//	echo dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path;
						//die;
						if(!is_dir(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path))
						{
							mkdir(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path);
							chmod(dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path,0777);
						}
				
				return $rowselect["fid"];
				//print_r($storeArray);
			    //return $insertdata;
					  // echo "<pre>";
					// print_r($insertdata);
					//	echo "</pre>";
						//die;
		}
			
			public function folderlist($start,$limit)
			{
				  $select_table = new Zend_Db_Table('folders');
				  $row123 = $select_table->fetchAll($select_table->select()->where('display_status = ?',1)->order('name DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row123; 
			}






/*
			public function folderclientusers($folder)
			{
				//echo "aaa".$folder;
				//die;
				$select_table1 = new Zend_Db_Table('folders');
				  $row1 = $select_table1->fetchRow($select_table1->select()->from(array('r' => 'folders'),
                    array('r.name as title'))->where('id = ? ',trim(intval($folder))));
				// echo "<pre>";
				//	 print_r($row1);
					//	echo "</pre>";
					//	die;
				 return $row1; 
			}
*/
			
			public function countfolder()
			{
				  $count_table = new Zend_Db_Table('folders');
				  $count_row = $count_table->fetchAll($count_table->select()->order('name DESC'));
				  return count($count_row); 
			}

	
	public function checkparentunderfolder($fid)
			{
				  $checkparent = new Zend_Db_Table('folders');
				  $check_row = $checkparent->fetchRow($checkparent->select()->where('fid = ?',$fid));
				  
				  return $check_row; 
			}


		/*public function folder_documentcount($id)
			{ 
					
			$select_table = new Zend_Db_Table('folders');
			$count=$select_table->fetchAll($select_table->select()->where('owner=?'=> $id);
			return count($count); //counts the number of folders for admin


			$count=$select_table->fetchAll($select_table->select()->where('owner=?' =>$id );
            return count($count);

			$select_tabe= new Zend_Db_Table('documents');
			$select->from(array('f' => 'folders'), array('fid','parent','created','owner','sequence','pub_pri','display_status,','descripttion'))
			
			->joinInner(array('d' => 'documents'), 'f.parent = d.folderid' ,'f.owner=d.owner'));
			$count.=$select_table->fetchAll($select); 
			return count($count);
			}

 */


			
		public function checkasfolderid($id)
	        {
               //echo $id;
			  // die;
				$checking_as_folder = new Zend_Db_Table('folders');
				$checkasfoldering = $checking_as_folder->fetchRow($checking_as_folder->select()->where('fid = ?',intval($id)));
				 
				 //echo  $checkasfoldering['parent'];
				// die;
				 $checkselect = $checking_as_folder->fetchRow($checking_as_folder->select()->where('fid = ?',$checkasfoldering['parent']));
					return $checkselect['pub_pri'];
				 
				 //return   $checkselect;
			
			}



			public function publicasfolderid($id)
	        {
               //echo $id;
			  // die;
				$checking_as_folder = new Zend_Db_Table('folders');
				$checkasfoldering = $checking_as_folder->fetchAll($checking_as_folder->select()->where('parent = ?',intval($id)));
				 
				 //echo  $checkasfoldering['parent'];
				// die;
				
				 
				return   $checkasfoldering;
			
			}



			public function editfolderclient($id)
	        {
                $select_table = new Zend_Db_Table('folders');
				$rowselect = $select_table->fetchRow($select_table->select()->where('fid = ?',trim(intval($id))));
				 return $rowselect;     
			
			}


			public function editfolderdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				//echo "ID=".$id;
				//die;
						$select_table_before = new Zend_Db_Table('folders');
						$before_row = $select_table_before->fetchRow($select_table_before->select()->where('fid = ?',$id));
						//$parentId = $before_row["parent"];


						$updatedetails_selecttable = new Zend_Db_Table('folders');

						$data="";
						$where="";
						$data = array('name'=> $editdataform['name'],'pub_pri'=> $editdataform['show'],'description'=> $editdataform['description']);
						$where = array('fid = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);

						$select_table1 = new Zend_Db_Table('folders');
						$rowselect123 = $select_table1->fetchRow($select_table1->select()->where('fid = ?',$id));
						$parentId = $rowselect123["parent"];
						
						$select_table2 = new Zend_Db_Table('folders');
						$rowselect2 = $select_table2->fetchRow($select_table2->select()->where('fid = ?',$parentId));
						$nameofParent = $rowselect2["folder_path"];
				
						//$folder_path = $nameofParent;
						if(isset($nameofParent))
						{
							$folder_path = $nameofParent."/".$editdataform['name'];
						}
						//$folder_path;
						//die;
							$updatedetails_selecttable1 = new Zend_Db_Table('folders');

						$data1 = array('folder_path'=> $folder_path);
						$where1 = array('fid = ?'=> $id);
						$update_values1 = $updatedetails_selecttable1->update($data1, $where1);
						
						//echo dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$before_row["folder_path"]."=========".dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path;

						//die;
					//	echo dirname($_SERVER['SCRIPT_FILENAME'])."/data/uploads/".$folder_path;
				 //rename(dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$rowselect1['folder_path'],dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path) or die("Unable to rename");
				//exec("mv dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$rowselect1['folder_path']   dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path") or die("Unable to rename");
				//echo " mv /web/html/drdo/DMS/data/uploads/".$before_row['folder_path']."==========  /web/html/drdo/DMS/data/uploads/".$folder_path;
				//die;
				chmod(dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$before_row['folder_path'],0777);
				exec (" mv ".dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$before_row['folder_path']."  ".dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path);
 
								
			}

			public function deletefolder($id)
			{
					
					$where="";
					$where = array('fid = ?'      => $id);

					$select_table1 = new Zend_Db_Table('folders');
					$rowselect123 = $select_table1->fetchRow($select_table1->select()->where('fid = ?',$id));
					$folder_path = $rowselect123["folder_path"];
					//echo dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path;
						//die;
					$delete_user = new Zend_Db_Table('folders');
					$delete_values = $delete_user->delete($where);
					chmod(dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path,0777);
					//exec(dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path);
					exec("rm -rf ".dirname($_SERVER['SCRIPT_FILENAME']).'/data/uploads/'.$folder_path);

			}

			public function inactivefolder($folderIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('folders');
					$data="";
					$where="";
					$data = array('display_status'=> $sttaus);
					$where = array('fid IN (?)'=> $folderIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkfolderclient($name)
	        {
				
				$select_table = new Zend_Db_Table('folders');

				$rowselect = $select_table->fetchAll($select_table->select()->where('name = ?',trim(($name))));
				return count($rowselect); 
			
			}

			public function checkfolderclientEdit($name,$id)
	        {
				
				$select_table = new Zend_Db_Table('folders');

				$rowselect = $select_table->fetchAll($select_table->select()->where('name = ?',trim(($name)))->where('fid <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}

			public function folderParent()
			{
				  $select_table = new Zend_Db_Table('folders');
				  $row123 = $select_table->fetchRow($select_table->select()->where('parent = ?',0)->order('name DESC'));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row123; 
			}



			public function folderlistbypermission($pid)
			{
				  $select_table = new Zend_Db_Table('folders');
				  $row123 = $select_table->fetchAll($select_table->select('fid,name')->where('parent = ?',$pid)->order('name DESC'));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row123; 
			}

			public function folderlistbypermissionchild($pid)
			{
				  $select_table = new Zend_Db_Table('folders');
				  $row123 = $select_table->fetchAll($select_table->select('fid,name')->where('parent <> ?',1)->where('parent = ?',$pid)->order('name DESC'));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row123; 
			}

				public function countparentfolder($id)
			{
				  $select_table = new Zend_Db_Table('folders');
				  $rowcountfolder = $select_table->fetchAll($select_table->select()->where('parent = ?',$id)->where('display_status=1'));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return count($rowcountfolder); 
			}

			public function getUserIdbyFolderId($id)
			{
				  $select_table = new Zend_Db_Table('folders');
				  $rowUserid = $select_table->fetchRow($select_table->select()->where('fid = ?',$id));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $rowUserid["owner"]; 
			}
			
			public function displayfolderlist()
			{
				$userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
								if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
				   $select_table = new Zend_Db_Table('folders');
				   $rowlist = $select_table->fetchAll($select_table->select()->where('display_status = ?',1)->order('parent ASC'));
								}
								else
							{
						$select_table = new Zend_Db_Table('folders');
				   $rowlist = $select_table->fetchAll($select_table->select()->where("owner=".$userid->userid." or pub_pri=1")->where('display_status = ?',1)->order('parent ASC'));
							
							}
				  return $rowlist; 
			}

			# This is used for search
		
		

		
			
			public function searchFolderList($name,$folderId,$startDate,$endDate, $start,$limit)
			{
				
		
				   $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
				
				
				//if name has value but folder select all folders, start date is blank, end date is blank.
				if(isset($name)&& $folderId=="0" && $startDate=="" && $endDate=="")
				{
				
				 //  $row123 = $select_table->fetchAll($select_table->select()->where('display_status = ?',1)->where('pub_pri = ?',1)->where('name LIKE ?',"%$name%")->order('name DESC')->limit($limit,$start));
				
				  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						//->where("d.name LIKE ?","%".$name."%")
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								
								//	echo "<pre>";
								//	 print_r($name_org);
								//	echo "</pre>";
								//	die;
								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
			->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
									//echo "<pre>";
									// print_r($folder_org);
									//echo "</pre>";
									//die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						//->where("d.name LIKE ?","%".$name."%")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
							
							
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
									// print_r($folder_org);
									//echo "</pre>";
									//die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				}





				//if name has no value and folder has value, start date is blank, end date is blank.

				else if($name=="" && $folderId!=0 && $startDate=="" && $endDate=="")
				{
					
					$userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("f.display_status=1")->order('d.created DESC');
						
						
						$select_org = $select_table->fetchAll($select);
									
							
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
									
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
			->where("f.parent = ?",$folderId)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where("f.display_status=1")->order('d.created DESC');
						
						
						$select_org = $select_table->fetchAll($select);
									
							
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								

							
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
									 //print_r($folder_org);
									//echo "</pre>";
									//die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				}

			//if name has value and folder has value, start date is blank, end date is blank.

				else if($name!=="" && $folderId!=0 && $startDate=="" && $endDate=="")
				{
				
				$userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						//->where("d.name LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						
						$select_org = $select_table->fetchAll($select);
									
							
								//	echo "<pre>";
									// print_r($select_org);
									//echo "</pre>";
								//	die;

									
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
			->where("f.parent = ?",$folderId)
		   ->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						//->where("d.name LIKE ?","%".$name."%")
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where("f.display_status=1")->order('d.created DESC');
						
						
						$select_org = $select_table->fetchAll($select);
									
									
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
						->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
								//	print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}

				}



			//if name has no value and folder has selected All Folders, start date is not blank, end date is blank.

				else if($name=="" && $folderId==0 && $startDate!="" && $endDate=="")
				{
				
					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
				//	echo $newStartDate ;


					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
						->where('d.created >= ?',$newStartDate)
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								
									
								//echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
			->where('f.created >= ?',$newStartDate)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						->where('d.created >= ?',$newStartDate)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where('f.created >= ?',$newStartDate)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}







			//if name has no value and folder has selected All Folders, start date is blank, end date is not blank.
				else if($name=="" && $folderId==0 && $startDate=="" && $endDate!="")
				{
				
				//echo "aaa";
				//die;
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";
					//echo $end ;
					//die;

					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						
						//$select->from(array('d' => 'documents'), array( 'did as docid',explode(" ",'created') as 'searchdate','owner as oid','folderid as foldid','name as dname'))

						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
						->where('d.created < ?',$end)
						//->where('d.created = ?',$newEndDate)
						//->where("d.created<". $newEndDate." or d.created = ".$newEndDate."")
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								
									
								//echo "<pre>";
									// print_r($select_org);
									//echo "</pre>";
									//die;
								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
			->where('f.created < ?',$end)
	   //->where('f.created = ?',$newEndDate)
		 //  ->where("f.created<". $newEndDate." or f.created = ".$newEndDate."")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
									//echo "<pre>";
									// print_r($folder_org);
									//echo "</pre>";
									//die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						->where('d.created <= ?',$end)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where('f.created <= ?',$end)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}





				//if name has value and folder has selected All Folders, start date is not blank, end date is blank.

				else if($name!="" && $folderId==0 && $startDate!="" && $endDate=="")
				{
				
					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
				//	echo $newStartDate ;


					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('d.created >= ?',$newStartDate)
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								
							
							
								
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
		   ->where("f.name LIKE ?","%".$name."%")
			->where('f.created >= ?',$newStartDate)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('d.created >= ?',$newStartDate)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									//		echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//die;


					

								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.created >= ?',$newStartDate)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}





					//if name has value and folder has selected All Folders, start date is blank, end date is not blank.
				else if($name!="" && $folderId==0 && $startDate=="" && $endDate!="")
				{
				
				
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";
					//echo $newEndDate ;
					//die;

					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('d.created <= ?',$end)
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
									//echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
								
						
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
		   ->where("f.name LIKE ?","%".$name."%")
			->where('f.created <= ?',$end)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
					//	->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('d.created <= ?',$end)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;

						
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.created <= ?',$end)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}








//if name has no value and folder has value, start date is not blank, end date is blank.

				else if($name=="" && $folderId!=0 && $startDate!="" && $endDate=="")
				{
				
			
					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
				//	echo $newStartDate ;


					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where('d.created >= ?',$newStartDate)
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent = ?",$folderId)
			->where('f.created >= ?',$newStartDate)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where('d.created >= ?',$newStartDate)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									//		echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//die;


					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where('f.created >= ?',$newStartDate)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}










	//if name has no value and folder has value, start date is blank, end date is not blank.
				else if($name=="" && $folderId!=0 && $startDate=="" && $endDate!="")
				{
				
			//	echo "aaa";
			//	die;
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";
					//echo $newEndDate ;
					//die;

					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
						->where("d.folderid = ?",$folderId)
						->where('d.created <= ?',$end)
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
						
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		  ->where("f.parent = ?",$folderId)
			->where('f.created <= ?',$end)
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where('d.created <= ?',$end)
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;

					
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where('f.created <= ?',$end)
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}





//if name has no value and folder has selected All Folders, start date is not blank, end date is not blank.
				else if($name=="" && $folderId==0 && $startDate!="" && $endDate!="")
				{
				
				

					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
					
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";
					//echo $newEndDate ;
					//die;

					  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						//->where("d.name LIKE ?","%".$name."%")
						//->where('d.created <= ?',$newEndDate)
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						//->where(("d.name LIKE ?","%".$name."%") OR ("t.docname LIKE ?","%".$name."%"))
						//->orWhere("t.docname LIKE ?","%".$name."%")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								
									
								//echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
			->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							//echo $userid->userid;
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									//		echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
					
				}




		
			//if name has value and folder has value, start date is not blank, end date is not blank.


				else if($name!="" && $folderId!=0 && $startDate!="" && $endDate!="")
				{
				
			//	echo "aaa";
			//	die;

			$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
					
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
						$end = $newEndDate." 23:59:59";
					

				  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						//->where("d.name LIKE ?","%".$name."%")
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where("f.display_status=1")->order('d.created DESC');

						$select_org = $select_table->fetchAll($select);
						
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
						
						
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		  // ->where("f.parent!=0")
		   ->where("f.parent = ?",$folderId)
			->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
			->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
									//echo "<pre>";
								//	print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
											//echo "<pre>";
											//print_r($select_org);
											//echo "</pre>";
											//die;
								

								
							
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
									// print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				
				}




				
				


				//if name has value and folder has value, start date is not blank, end date is blank.


				else if($name!="" && $folderId!=0 && $startDate!="" && $endDate=="")
				{
				
			//	echo "aaa";
			//	die;

					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
					
					
				  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("d.created >='".$newStartDate."'")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where("f.display_status=1")->order('d.created DESC');

						$select_org = $select_table->fetchAll($select);
						
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
						
						
									
								
								
								$folder_table = new Zend_Db_Table('folders');
								$select = $folder_table->select();
								$select->setIntegrityCheck(false);
								$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
								->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
								 // ->where("f.parent!=0")
								->where("f.parent = ?",$folderId)
								->where("f.created >='".$newStartDate."'")
								->where("f.name LIKE ?","%".$name."%")
								->where("f.display_status=1")
								->order('f.created DESC');
			
			
									$folder_org = $folder_table->fetchAll($select);
								
									//echo "<pre>";
								//	print_r($folder_org);
									//echo "</pre>";
									//die;

							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where("d.created >='".$newStartDate."'")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
										//	print_r($select_org);
										//	echo "</pre>";
											//die;
								

						
							
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where("f.created >='".$newStartDate."'")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				
				}



		
		
			//if name has value and folder has value, start date is blank, end date is not blank.


				else if($name!="" && $folderId!=0 && $startDate=="" && $endDate!="")
				{
				
				//echo "aaa";
				//die;

					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];

					$end = $newEndDate." 23:59:59";

				  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("d.created<='".$end."'")
						//->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where("f.display_status=1")->order('d.created DESC');

						$select_org = $select_table->fetchAll($select);
						
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
						
						
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		  // ->where("f.parent!=0")
		   ->where("f.parent = ?",$folderId)
			->where("f.created<='".$end."'")
			->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
									//echo "<pre>";
								//	print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where("d.created<='".$end."'")
					//	->where("d.name LIKE ?","%".$name."%")
							->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
											//echo "<pre>";
											//print_r($select_org);
											//echo "</pre>";
											//die;
								

								
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where("f.created<='".$end."'")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
									// print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				
				}
			

				

			
			//if name has no value and folder has value, start date is not blank, end date is not blank.


				else if($name=="" && $folderId!=0 && $startDate!="" && $endDate!="")
				{
				
			//	echo "aaa";
			//	die;

					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
					
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";


					$userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.folderid = ?",$folderId)
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						->where("f.display_status=1")->order('d.created DESC');

						$select_org = $select_table->fetchAll($select);
						
									
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
									
						
							
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		  // ->where("f.parent!=0")
		   ->where("f.parent = ?",$folderId)
			->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	print_r($folder_org);
								//	echo "</pre>";
								//	die;					
									
									$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.folderid=".$folderId." and (d.owner=".$userid->userid." or f.pub_pri=1)")
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
											
											//echo "<pre>";
										//	print_r($select_org);
										//	echo "</pre>";
										//	die;
								

							
							
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent=".$folderId." and (f.owner=".$userid->userid." or f.pub_pri=1)")
					->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				
				}




			//if name has value and folder has no value, start date is not blank, end date is not blank.


				else if($name!="" && $folderId==0 && $startDate!="" && $endDate!="")
				{
				
					$startDateArray = explode("/",$startDate);
					$newStartDate = $startDateArray["2"]."-".$startDateArray["0"]."-".$startDateArray["1"];
					
					$endDateArray = explode("/",$endDate);
					$newEndDate = $endDateArray["2"]."-".$endDateArray["0"]."-".$endDateArray["1"];
					$end = $newEndDate." 23:59:59";


				  $userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');
			
						if($userid->userid == 1)//if user is Admin then listing is dispaly
								{
									
						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version','docname'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						//->where("t.docname LIKE ?","%".$name."%")
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where("f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									
								//	echo "<pre>";
								//	 print_r($select_org);
								//	echo "</pre>";
								//	die;
								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
		   ->where("f.parent!=0")
			->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
			->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	print_r($folder_org);
								//	echo "</pre>";
								//	die;
							
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid','name as dname'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid = ?",$folderId)
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						->where("d.created >='".$newStartDate."' and d.created<='".$end."'")
						->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//	echo "<pre>";
										//	print_r($select_org);
										//	echo "</pre>";
											//die;
								

								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					->where("f.created >='".$newStartDate."' and f.created<='".$end."'")
					->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
							//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							}
				
				
				}




				else
						{
						
						$userid = new Zend_Session_Namespace('userid');
					$role = new Zend_Session_Namespace('role');

						if($userid->userid == 1)//if user is Admin then listing is dispaly
							{

						$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
							->where("f.display_status=1")->order('d.created DESC');
						//->where("d.folderid=1 and f.display_status=1")->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
									//echo "<pre>";
									// print_r($select_org);
									//echo "</pre>";
									//die;
								
							
								
								$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
			//->where("f.parent=1 and f.display_status=1")->order('f.created DESC')
	    ->where("f.parent!=0")
			//->where("f.name LIKE ?","%".$name."%")
			->where("f.display_status=1")
		   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
								
								//	echo "<pre>";
								//	 print_r($folder_org);
								//	echo "</pre>";
								//	die;
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
							//	echo "<pre>";
								//	 print_r($print_all_output);
								//	echo "</pre>";
								//	die;
								
							}

							else
							{
							
							$select_table = new Zend_Db_Table('documents');
						$select = $select_table->select();
						$select->setIntegrityCheck(false);
						$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
						->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
						->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username'))
						->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','folder_path'))
						//->where("d.folderid=1 and (d.owner=".$userid->userid." or f.pub_pri=1)")
						//->where('f.display_status = ?',1)
						//->order('d.created DESC');
						->where("d.owner=".$userid->userid." or f.pub_pri=1")
						//->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
						//->where("d.name LIKE ?","%".$name."%")
						->where('f.display_status = ?',1)
						->order('d.created DESC');
						
						$select_org = $select_table->fetchAll($select);
										//		echo "<pre>";
									// print_r($select_org);
									//echo "</pre>";
									//die;
								
							
								
					$folder_table = new Zend_Db_Table('folders');
					$select = $folder_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
					->joinInner(array('u' => 'dbt_users'), 'f.owner = u.id', array('id as usersid','username'))
					//->where("f.parent=1 and (f.owner=".$userid->userid." or f.pub_pri=1)")
					//->where('f.display_status = ?',1)
					//->order('f.created DESC');
					->where("f.parent!=0")
					->where("f.owner=".$userid->userid." or f.pub_pri=1")
					//->where("f.name LIKE ?","%".$name."%")
					->where('f.display_status = ?',1)
					->order('f.created DESC');

			
			
			$folder_org = $folder_table->fetchAll($select);
								//echo "<pre>";
									// print_r($folder_org);
									//echo "</pre>";
									//die;	
			
							$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
							//$all_val=$print_all_output."~".$start."~".$limit;
							if(count($print_all_output)>0)
								{
								$print_all_output[]['limit'].=$limit;
								$print_all_output[]['start'].=$start;
								}
								return $print_all_output;
							
								//echo "<pre>";
									// print_r($print_all_output);
									//echo "</pre>";
									//die;
								
							
							}
				
				
		
			
			
				}
				

			
			


			}
			




			




	public function getPrivatePublic($fid)
	{
				  $select_table = new Zend_Db_Table('folders');
				  $rowUserid = $select_table->fetchRow($select_table->select()->where('fid = ?',$fid));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $rowUserid["pub_pri"]; 
	}
	

/*
public function foldersize($path){

//echo $path;
//die;
$totalsize = 0;
 $totalcount = 0;
  $dircount = 0;
  if ($handle = opendir ($path))
  {
    while (false !== ($file = readdir($handle)))
    {
      $nextpath = $path . '/' . $file;
      if ($file != '.' && $file != '..' && !is_link ($nextpath))
      {
        if (is_dir ($nextpath))
        {
          $dircount++;
		 // echo "aaaa";
          $result = foldersize($nextpath);
          $totalsize += $result['size'];
          $totalcount += $result['count'];
          $dircount += $result['dircount'];
        }
        elseif (is_file ($nextpath))
        {
          //echo "aaaa";
		  $totalsize += filesize ($nextpath);
          $totalcount++;
        }
     }
    }
  }
  closedir ($handle);
  $total['size'] = $totalsize;
  $total['count'] = $totalcount;
  $total['dircount'] = $dircount;
  return $total;
		}



		public function sizeFormat($bytes){ 
				 if($size<1024)
    {
        return $size." bytes";
    }
    else if($size<(1024*1024))
    {
        $size=round($size/1024,1);
        return $size." KB";
    }
    else if($size<(1024*1024*1024))
    {
        $size=round($size/(1024*1024),1);
        return $size." MB";
    }
    else
    {
        $size=round($size/(1024*1024*1024),1);
        return $size." GB";
    } 
}



function folderSize($dir){
$count_size = 0;
$count = 0;
$dir_array = scandir($dir);
//print_r($dir_array);
//die;
  foreach($dir_array as $key=>$filename){
   
	if($filename!=".." && $filename!="."){
      echo "--".count(scandir($filename));
	  if(count(scandir($filename))>0){
          $new_foldersize = folderSize($dir."/".$filename);
          $count_size = $count_size+ $new_foldersize;
        }else if(is_file($dir."/".$filename)){
          $count_size = $count_size + filesize($dir."/".$filename);
          $count++;
        }
   }
 }
 die;
return $count_size;
}
*/


/*
function recursive_directory_size($directory, $format=FALSE)
{
     $size = 0;
    if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }
     if(!file_exists($directory) || !is_dir($directory) || !is_readable($directory))
     {
         return -1;
     }
     if($handle = opendir($directory))
     {
         while(($file = readdir($handle)) !== false)
         {
             $path = $directory.'/'.$file;
             if($file != '.' && $file != '..')
             {
                 if(is_file($path))
                 {
                     $size += filesize($path);
                 }elseif(is_dir($path))
                 {
                     $handlesize = recursive_directory_size($path);
                     if($handlesize >= 0)
                     {
                         $size += $handlesize;
                     }else{
                         return -1;
                     }
                 }
             }
         }
         closedir($handle);
     }
	 echo "--".$size;
	 die;

     if($format == TRUE)
     {
         if($size / 1048576 > 1)
         {
             return round($size / 1048576, 1).' MB';
         }elseif($size / 1024 > 1)
         {
             return round($size / 1024, 1).' KB';
         }else{
             return round($size, 1).' bytes';
         }
     }else{
         return $size;
     }
	 
 }


*/

public function getFolderIdAndName($name)
	{
				  $select_table = new Zend_Db_Table('folders');
				  $rowUserid = $select_table->fetchRow($select_table->select()->where('name = ?',$name));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $rowUserid["fid"]."~".$rowUserid["name"]; 
	}
	public function user_name($unme)
	{
	          $select_table = new Zend_Db_Table('dbt_users');
				  $rowUserid = $select_table->fetchRow($select_table->select()->where('id = ?',$unme));
				
				  return $rowUserid; 
	}




}