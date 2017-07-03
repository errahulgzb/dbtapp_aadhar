<?php



require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Document extends Zend_Db_Table_Abstract 
{
	
	/* function safestring($strr)
					{
					
					$badtags=array('<','&lt;','>','&gt;','&');
					$str=str_replace($badtags,"",$strr);
						if (get_magic_quotes_gpc()) 
						{
							$str = stripslashes($str);
						}
						//return $str = trim(mysql_real_escape_string($str));
						return $str = trim($str);
					}

*/
	 public function docsave($dataform,$uid,$sid)
			{
			//echo $dataform['sid'];
			//die;
					 Zend_Session::start();
			
		$session_id=Zend_Session::getId();				
					
				//	echo $uid;
					//print_r($dataform);
				//	die;
					$doc_table = new Zend_Db_Table('documents');

			$filenamedata=$this->getUploadedFileListFromQueue();

			
			if(empty($filenamedata))
				{
				return "Please upload atleast one document to proceed.";
				
				}
			
			

			$seperatebycomma = str_replace("@",",",$filenamedata);
			// echo $seperatebycomma;
			 //die;
			 $files_separate=explode(",",$seperatebycomma);

			//print_r($files_separate);
			//die;
			 foreach($files_separate as $key=>$value)
				{
				$i=0;
				//echo $value;
					$nm1 = new Zend_Db_Table('temp_file_upload');
					$row1 = $nm1->fetchAll($nm1->select()->where('fid = ?',$dataform['sid'])->where('activedoc = ?',1)->where('docname = ?',$value)->order('version DESC'));
					////echo "<pre>";
					//print_r($row1);
					//echo "</pre>";
					//die;
					//$a=explode('.',$row1['version']);
					//echo $row1[$i]['docname'];
					if(count($row1)>0)
					{
						if($row1[$i]['version'] == 0)
						{
						$updatedetails_selecttable = new Zend_Db_Table('temp_file_upload');
					    $data="";
						$where="";
						$data = array('version'=> 1);
						$where = array('activedoc = ?'=> 1,
							'docname = ?'=>$value,
							'fid = ?'=>$dataform['sid']);
						$update_values = $updatedetails_selecttable->update($data,$where);
												
						$data1="";
						 $where1="";
						$data1 = array('version'=> 2);
						$where1 = array('activedoc = ?'=> 0,
							'docname = ?'=>$value,
							'fid = ?'=>$dataform['sid']);
						$update1_values1 = $updatedetails_selecttable->update($data1,$where1);
						//return $update_values;
						
						}
						else
						{
							$a=($row1[$i]['version']+1);
						$updatedetails_selecttable = new Zend_Db_Table('temp_file_upload');
						$data1="";
						 $where1="";
						$data1 = array('version'=> $a);
						$where1 = array('activedoc = ?'=> 0,
							'docname = ?'=>$value,
							'fid = ?'=>$dataform['sid']);
						$update1_values1 = $updatedetails_selecttable->update($data1,$where1);
						}

					}
	
				}

					$date=date("Y-m-d H:i:s");

			$datainsert="";
			$datainsert = array(
					    'name'=> $dataform['name'],
					    'description'=> $dataform['description'],
				'created'=> $date,
				'folderid'=> $dataform['sid'],
				'owner'=> $uid,
				'filePath'=> $filenamedata
				
						        );
                 
				 $docsave=$doc_table->insert($datainsert);
				//return $docsave;
				 $lastInsertId = $this->getAdapter()->lastInsertId();

				  $updatedetails_selecttable = new Zend_Db_Table('temp_file_upload');

						$data="";
						$where="";
						$data = array(
							
						'documentid'=> $lastInsertId,
							'activedoc'=>1
							
						
						);
						$where = array('sessionId = ?'=> $session_id);
						$update_values = $updatedetails_selecttable->update($data, $where);
   
				
				
					return $update_values;
			
			
			}
	
	  public function temporary_file($newFileName)
		  {
		 
			$nm1 = new Zend_Db_Table('temp_file_upload');
			$row1 = $nm1->fetchAll($nm1->select()->where('docname = ?', $newFileName));
			//return $row->pagedesc;
			//echo count($row1);
			//die;
			return count($row1);
		}
	
	 public function folder_containing($folderscont)
		  {
		 
			$nm1 = new Zend_Db_Table('folders');
			$row1 = $nm1->fetchRow($nm1->select()->where('fid = ?', $folderscont));
			//return $row->pagedesc;
			//echo count($row1);
			//die;
			return $row1;
		}

/*
		public function joinfold_templist($fcontain,$start,$limit)
				{
			$select_table = new Zend_Db_Table('documents');
			$select = $select_table->select();
			$select->setIntegrityCheck(false);
				$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
			
			->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
       ->joinInner(array('u' => 'users'), 'd.owner = u.id', array('id as usersid','username'))
			->where('d.folderid=?',$fcontain)->order('d.created DESC')->limit($limit,$start);
			
			
			$select_org = $select_table->fetchAll($select);
			return $select_org;
	
		//	echo "<pre>";
		//print_r($select_org);
			//		echo "</pre>";
			//		die;
				}


*/
/*
	
*/

					public function information_containing($fcontain)
				{
						$info_table = new Zend_Db_Table('folders');
					$select = $info_table->select();
					$select->setIntegrityCheck(false);
					$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path','description'))
					
					
			   ->joinInner(array('u' => 'users'), 'f.owner = u.id', array('id as usersid','username','email'))
					->where('fid=?',$fcontain);
				  
			  
					
					
					$info_org = $info_table->fetchRow($select);
					//return $info_gain=$info_org->toArray();
					return $info_org;
				
				}


				public function joinfold_templist($fcontain,$start,$limit)
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
								->joinInner(array('u' => 'users'), 'd.owner = u.id', array('id as usersid','username','firstname','lastname'))
								->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
								->where('d.folderid=?',$fcontain)
								->where("f.display_status=1")
								->order('d.created DESC');
								
								$select_org = $select_table->fetchAll($select);
													
								} 
								else
								{
								$select_table = new Zend_Db_Table('documents');
								$select = $select_table->select();
								$select->setIntegrityCheck(false);
								$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
								->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
								->joinInner(array('u' => 'users'), 'd.owner = u.id', array('id as usersid','username','firstname','lastname'))
								->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
							//	->where('d.folderid=?',$fcontain)->where('d.owner=?',$userid->userid OR 'f.pub_pri=?',1)->order('d.created DESC');
								->where("d.folderid=".$fcontain." and (d.owner=".$userid->userid." or f.pub_pri=1)")
								->where("f.display_status=1")
								->order('d.created DESC');
								
								$select_org = $select_table->fetchAll($select);
								//	echo "<pre>";
								//	 print_r($select_org->toArray());
								//	echo "</pre>";
								//		die;
									
								}
							

			
			if($userid->userid == 1)
			{
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			
			
       ->joinInner(array('u' => 'users'), 'f.owner = u.id', array('id as usersid','username'))
			->where('f.parent=?',$fcontain)
		   ->where("f.display_status=1")
	   ->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
			}
			else
			{
			//echo $userid->userid;
			//die;
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri','display_status','folder_path'))
			->joinInner(array('u' => 'users'), 'f.owner = u.id', array('id as usersid','username','firstname','lastname'))
			//->where("'f.parent=".$fcontain." and (f.owner=".$userid->userid." or f.pub_pri=1)'");
			->where("f.parent=".$fcontain." and (f.owner=".$userid->userid." or f.pub_pri=1)")
			->where("f.display_status=1")
			->order('f.created DESC');
			
			$folder_org = $folder_table->fetchAll($select);
			
			}
			
			$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
			//$all_val=$print_all_output."~".$start."~".$limit;
			if(count($print_all_output)>0){
				$print_all_output[]['limit'].=$limit;
				$print_all_output[]['start'].=$start;
			}
			return $print_all_output;
	
			//echo "<pre>";
		
		//print_r($print_all_output);
					//echo "</pre>";
					//die;
				}




					public function countfolder($fcontain)
				{
			$userid = new Zend_Session_Namespace('userid');
			$role = new Zend_Session_Namespace('role');
			
			if($userid->userid == 1)
			{
			$select_table = new Zend_Db_Table('documents');
			$select = $select_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
			->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
			->joinInner(array('u' => 'users'), 'd.owner = u.id', array('id as usersid','username'))
			->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri'))
			->where('d.folderid=?',$fcontain)->order('d.created DESC');
			
			$select_org = $select_table->fetchAll($select);
								
			} 
			else
			{
			$select_table = new Zend_Db_Table('documents');
			$select = $select_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
			->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
			->joinInner(array('u' => 'users'), 'd.owner = u.id', array('id as usersid','username'))
			->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri'))
		//	->where('d.folderid=?',$fcontain)->where('d.owner=?',$userid->userid OR 'f.pub_pri=?',1)->order('d.created DESC');
			->where("d.folderid=".$fcontain." and (d.owner=".$userid->userid." or f.pub_pri=1)")
			->order('d.created DESC');
			
			$select_org = $select_table->fetchAll($select);
			//	echo "<pre>";
			//	 print_r($select_org->toArray());
			//	echo "</pre>";
			//		die;
				
			}
		

			
			if($userid->userid == 1)
			{
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri'))
			
			
       ->joinInner(array('u' => 'users'), 'f.owner = u.id', array('id as usersid','username'))
			->where('f.parent=?',$fcontain)->order('f.created DESC');
			
			
			$folder_org = $folder_table->fetchAll($select);
			}
			else
			{
			//echo $userid->userid;
			//die;
			$folder_table = new Zend_Db_Table('folders');
			$select = $folder_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('f' => 'folders'), array( 'fid as foldid','created','owner as oid','parent as parentid','name as docname','sequence as version','pub_pri'))
			->joinInner(array('u' => 'users'), 'f.owner = u.id', array('id as usersid','username'))
			//->where("'f.parent=".$fcontain." and (f.owner=".$userid->userid." or f.pub_pri=1)'");
			->where("f.parent=".$fcontain." and (f.owner=".$userid->userid." or f.pub_pri=1)")
			->order('f.created DESC');
			
			$folder_org = $folder_table->fetchAll($select);
			
			}
			
			$print_all_output = array_merge($folder_org->toArray(),$select_org->toArray());
			return count($print_all_output);
	
			
	}
	 
		 public function temp_file_upload()
		  {
		  Zend_Session::start();
			//$rowNum = mysql_fetch_array(mysql_query("select count(*) as cnt from temp_file_upload where sessionId='".session_id()."'"));
		//$rowNum = $rowNum["cnt"];
		//echo "rowNum : ".$rowNum;
		$session_id=Zend_Session::getId();

			$nm = new Zend_Db_Table('temp_file_upload');
			$row = $nm->fetchAll($nm->select()->where('sessionId = ?', $session_id));
			//return $row->pagedesc;
			
			return count($row);
		}
		# This function will check the same file should not be uploaded twice......
		 public function checkExistFileUpload($filename)
		  {
			  Zend_Session::start();
			//$rowNum = mysql_fetch_array(mysql_query("select count(*) as cnt from temp_file_upload where sessionId='".session_id()."'"));
		//$rowNum = $rowNum["cnt"];
		//echo "rowNum : ".$rowNum;docname
		$session_id=Zend_Session::getId();

			$nm = new Zend_Db_Table('temp_file_upload');
			$row = $nm->fetchRow($nm->select()->where('sessionId = ?', $session_id)->where('docname = ?', $filename)->where('activedoc = ?', 0));
			//return $row->pagedesc;
			
			return count($row);
			
		}

		# This function will delete all the record of the same session.....
		 public function deleteSameSessionData()
		  {
			  Zend_Session::start();
			//$rowNum = mysql_fetch_array(mysql_query("select count(*) as cnt from temp_file_upload where sessionId='".session_id()."'"));
		//$rowNum = $rowNum["cnt"];
		//echo "rowNum : ".$rowNum;docname
		$session_id=Zend_Session::getId();

			$nm = new Zend_Db_Table('temp_file_upload');


			$where = array('sessionId = ?'=> $session_id,'activedoc = ?'=> 0,);
						$row1 = $nm->delete($where);
						
			
			
		}
	
		 
		 public function temp_file_upload_insert($newFileName,$a)
		  {
			Zend_Session::start();
			$session_id=Zend_Session::getId();
//echo $session_id;
//die;

			$session = new Zend_Session_Namespace('clientlogin');
			$nm = new Zend_Db_Table('temp_file_upload');
			 $datainsert1 = array(
					//'pagedesc'      => $content,
					//'lodgereportId'      => $reportid,
					'docname'      => $newFileName,
					'sessionId'      => $session_id,
					'uploadType'      => '',
					'fid'      => $a,	
					
					
					);

				$insertdatafile=$nm->insert($datainsert1);

			//	echo "<pre>";
			//	print_r($insertdatafile);
			//	echo "</pre>";
			//	die;
			return $insertdatafile;
		  }
			
			 public function deleteUploadedFile($id,$type)
				  {
					Zend_Session::start();
					$session_id=Zend_Session::getId();

				   $nm = new Zend_Db_Table('temp_file_upload');
					$row = $nm->fetchRow($nm->select()->where('id = ?', $id));
					//return $row->pagedesc;
					$numberrow=count($row);
					//echo $numberrow;
					//die;
						if($numberrow>0)
					  {
						$target_path = $_SERVER['DOCUMENT_ROOT'].'/DMS/application/uploads/';
						$target_path = $target_path . $row["docname"];	
						unlink($target_path);
						$where = array(
										'id = ?'=> $id,
										);
						$row1 = $nm->delete($where);
					  }

					  if($type!="queue")
						{
							

							$row2 = $nm->fetchAll($nm->select()->where('sessionId = ?', $session_id)->order("id ASC"));
							$rowcount=count($row2);
								if($rowcount>0)
									{
									//////////////////////////////////////////////////////////////////////////////
											$target_path = $_SERVER['DOCUMENT_ROOT'].'/DMS/application/uploads/';
											$target_path = $target_path . $row["docname"];	
											unlink($target_path);
											$where = array(
															'sessionId = ?'=> $session_id,
															);
											$row1 = $nm->delete($where);
										 
										  /////////////////////////////////////////////////////////////////////////
										//while($rsTempFileDisplay   = mysql_fetch_array($sqlTempFileDisplay1))
										foreach($row2 as $key=>$value)
											{
									// Because we are just skipping the 6 characters from the file which has been upoaded and database also.
										$fileName = substr($value["docname"],6);
												if (strpos ( $fileName, "!!" )) {
													$fileName = str_replace ("!!", " ", $fileName);
												}



												$string .='<table border="0" cellspacing="0" cellpadding="2" width="100%" style="margin-bottom: 10px;"><tr><td width="40%" style="border-bottom: solid 1px black;"><a alt="dd" href="/download_file.php?filename='.$value["docname"].'" target="_blank" style="color: #3E5B9B; font-family: Verdana;font-size: 12px;">'.$fileName.'</a></td><td width="30%" align="right" style="border-bottom: solid 1px black; vertical-align: bottom"><a href="javascript:deleteUploadedFile('.$value["id"].')" style="color: #3E5B9B; font-size: 14px; font-weight: bold">Delete</a></td></tr></table>';
											}
											
											return $string;
											//die;
								
								    }
									else
									return 0;
									//die;
					  
					    }else
							 {
								return "queueFile";
								//die;
							 }
						
				  // return 1;
				   }



								public function getUploadedFileListFromQueue()
								{
								Zend_Session::start();
								$session_id=Zend_Session::getId();
								 $nm = new Zend_Db_Table('temp_file_upload');
								$row2 = $nm->fetchAll($nm->select()->where('sessionId = ?', $session_id));
							$rowcount=count($row2);
								if($rowcount>0)
									{
									$fileName="";
									foreach($row2 as $key=>$value)
											{
										$fileName .= $value["docname"]."@";

											}
									$fileNameList = substr($fileName,0,strlen($fileName)-1);
									return $fileNameList;
									}
									else
									{
										$a='';
										return $a;
									}
		
	 	
								}			






}