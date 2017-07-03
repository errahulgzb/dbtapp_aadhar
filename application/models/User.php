<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_User extends Zend_Db_Table_Abstract 
{
	 public $_name = 'dbt_roles';
		public $_primary = 'dbt_roles.id';
	
	// protected $_name = 'users';
	//public function insertuserdetails($dataform,$file_name)
		
	

	public function getuserLastLogin($userid){
		$select = new Zend_Db_Table('dbt_users');
		$rowselect = $select->fetchAll($select->select()->where('id = ?',trim(intval($userid))));
		//echo print_r($rowselect->toArray());exit;
		$lastArr = $rowselect->toArray();
		$last = $lastArr[0]['lastlogin'];
		//echo $last;exit;
		$lobj = new Zend_Session_Namespace('lastlogin');
		$lobj->lastlogin = $last;
		
		//echo $userid.date("Y-m-d H:i:s");exit;
		$datatoupdate = array();
		$datatoupdate = array(
						'lastlogin' => date("Y-m-d H:i:s"),
		);
		$where = array(
				'id = ?' => $userid
		);
		$dataupdated = $select->update($datatoupdate,$where);
		//echo $dataupdated;exit;
		return $dataupdated;
	}
		
		
		
		
		public	function findMd5Value($mixValue)
	{
		//$password1=trim($_POST['password']);
		$password = substr($mixValue, 0, 12);
		$password.= substr($mixValue, 22, 10);
		$password.= substr($mixValue, 37);
		return $password;
	}
		
		
		public function getstatename($editid)
			{
				
				
			   $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($editid))));

				
				
				 $select_tablestate = new Zend_Db_Table('dbt_state');
				 $rowselectstate = $select_table->fetchRow($select_tablestate->select()->where('state_code = ?',trim(intval($rowselect['state']))));
				 
				$state_name = $rowselectstate['state_name'];
				return $state_name;
				
				
			}
			
			
			public function getdistrict($editid)
			{
				
				
			   $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($editid))));

				
				
				$select_tabledistrict = new Zend_Db_Table('dbt_district');
				 $rowselectdistrict = $select_table->fetchRow($select_tabledistrict->select()->where('district_code = ?',trim(intval($rowselect['cityname']))));
				 
				$district_name = $rowselectdistrict['district_name'];
				return $district_name;
				//print_r($rowselect);
				
				
			}
				public function getrolename($editid)
			{
				
				
			   $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($editid))));

				
				
				$select_tablerole = new Zend_Db_Table('dbt_roles');
				 $rowselectrole= $select_table->fetchRow($select_tablerole->select()->where('id = ?',trim(intval($rowselect['role']))));
				 
				$role_name = $rowselectrole['title'];
				return $role_name;
				//print_r($rowselect);
				
				
			}
			
			
			
				
			public function getusernameusers($editid)
			{
				
				
			   $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($editid))));
				$username = $rowselect['username'];
				return $username;
				//print_r($rowselect);
			}
			
			public function insertuserdetails($dataform){
				$userid = new Zend_Session_Namespace('userid');
				$user_table = new Zend_Db_Table('dbt_users');
			    $date=date("Y-m-d H:i:s");
				$datainsert="";
			  	$datainsert = array(
					'state'=> $dataform['statename'],
					//'cityname'=> $dataform['cityname'],
					'ministry_name'=> $dataform['ministry_name'],
					'username'=> $dataform['username'],
					'password'=> hash_hmac('sha256', $dataform['password'], ''),
					'firstname'=> $dataform['firstname'],
					'lastname'=> $dataform['lastname'],
					'organisation'=> '',
					'mobile'=> $dataform['mobile'],
					'email'=> $dataform['email'],
					'telephone'=> '',
					'address'=> '',
					'role'=> $dataform['name'],
					'upload'=>'',
					'dateAdded'=>$date,
					'dateModify'=>$date,
					'status'=> 1,
					'created_by'=>$userid->userid,
					'tmp_password' => md5(uniqid(rand(), true)).substr(md5($dataform['username']),2,10)
				);
				$insertdata = $user_table->insert($datainsert);
				return $insertdata;
				
				}

	//this function list the all of user to the authenticated role which can see the list of users;
	//Params : Start and limit are use for the pagination and ministry param for the listing only scheme owner
	//Output : user list
	public function userlist($start,$limit,$ministry = null){
		$userid = new Zend_Session_Namespace('userid');
		$select_table = new Zend_Db_Table('dbt_users');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('u' => 'dbt_users'), array('username', 'id as uid','firstname','lastname','organisation','mobile','email','role as roleuserid','status as userstatus','state as stateid'))
		->joinInner(array('r' => 'dbt_roles'), 'u.role = r.id', array('id as roleid','title','status as rolestatus'))
		->where('r.status = 1')->order('u.id DESC');
		if($ministry == 6){
			$select->where("u.role = ?",4);
		}else if($ministry == 4){
				
			$select->where("u.role = ?",12);
			$select->where("u.created_by = ?",$userid->userid);
			
		}
		
		$select->limit($limit,$start);
		$select_org = $select_table->fetchAll($select);
		//echo $select;die;
		return $select_org;
	}


		public function countuser($ministry = null){
			$userid = new Zend_Session_Namespace('userid');
			$count_table = new Zend_Db_Table('dbt_users');
			$select = $count_table->select();
			$select->setIntegrityCheck(false);
			$select->from(array('u' => 'dbt_users'), array('username'))
			->joinInner(array('r' => 'dbt_roles'), 'u.role = r.id', array('id as roleid','title','status as rolestatus'))
			->where('r.status = 1');
			if($ministry == 6){
			$select->where("u.role = ?",4);
		}else if($ministry == 4){
				
			$select->where("u.role = ?",12);
			$select->where("u.created_by = ?",$userid->userid);
			
		}
			
			$select->order('u.firstname DESC');			
			$count_org = $count_table->fetchAll($select);
	        return count($count_org); 
		}



			public function edituserclient($id)
	        {
                $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
			public function edituserinfoclient($id)
	        {
                $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
		
			public function checkuserinfoEdit($username,$id)
	        {
				
				$select_table = new Zend_Db_Table('dbt_users');

				$rowselect = $select_table->fetchAll($select_table->select()->where('username = ?',trim(($username)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}
			
			public function edituserinfodetails($editdataform,$id,$filename)
	        {
				// echo "<pre>"; print_r($editdataform);
				// die;
				$date=date("Y-m-d H:i:s");
				

				$updatedetails_selecttable = new Zend_Db_Table('dbt_users');

						$data="";
						$where="";


					if ($filename != 0){
						$data = array(
								// 'username'=> $editdataform['username'],
								'firstname'=> $editdataform['firstname'],
								'lastname'=> $editdataform['lastname'],
								'mobile'=> $editdataform['mobile'],
								'email'=> $editdataform['email'],
								'upload'=> $filename,
								//'role'=> $showdetails['role'],	
								//'comment'=> $editdataform['comment'],	
								'dateModify'=>$date,		  
						);
					} else {
						$data = array(
								'firstname'=> $editdataform['firstname'],
								'lastname'=> $editdataform['lastname'],
								'mobile'=> $editdataform['mobile'],
								'email'=> $editdataform['email'],
								'dateModify'=>$date,
						);

					}
						
					$where = array(
						
						'id = ?'      => $id
								  );

						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function edituserdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				$date=date("Y-m-d H:i:s");

				if($editdataform['name']==3)
				{
					$address = 	 $editdataform['address'];
					$telephone = $editdataform['telephone'];
				}
				else
				{
					$address="";
					$telephone="";
				}
				
				$updatedetails_selecttable = new Zend_Db_Table('dbt_users');

						$data="";
						$where="";
						$data = array(
								 //'username'=> $editdataform['username'],
								 'firstname'=> $editdataform['firstname'],
								'lastname'=> $editdataform['lastname'],	
								'organisation'=> $editdataform['organisation'],	
								'address'=> $address,
								'telephone'=> $telephone,
								'mobile'=> $editdataform['mobile'],
								'email'=> $editdataform['email'],
								//'comment'=> $editdataform['comment'],
									//'role'=> $editdataform['name'],					
									'dateModify'=>$date,		  
						);

						
					$where = array(
						
						'id = ?'      => $id
								  );

						$update_values = $updatedetails_selecttable->update($data, $where);
			}

public function deleteuser($id)
{
    $delete_user = new Zend_Db_Table('dbt_users');
    $where="";
    $where = array('id = ?' => $id );
    $delete_values = $delete_user->delete($where);

}

public function checkuserclient($username)
{

    $select_table = new Zend_Db_Table('dbt_users');

    $rowselect = $select_table->fetchAll($select_table->select()->where('username = ?',trim(($username))));
    return count($rowselect); 

}

/**************************************************/
                        
    public function checkemail($email)
    {
        $select_table = new Zend_Db_Table('dbt_users');
        $rowselect = $select_table->fetchAll($select_table->select()->where('email = ?',trim(($email))));
        return count($rowselect); 
    }
    
    public function checkemailEdit($email,$id)
    {

        $select_table = new Zend_Db_Table('dbt_users');

        $rowselect = $select_table->fetchAll($select_table->select()->where('email = ?',trim(($email)))->where('id <> ?',trim(intval($id))));
        return count($rowselect); 
    }
                        
/**********************************************/                        

			public function checkuserclientEdit($username,$id)
	        {
				
				$select_table = new Zend_Db_Table('dbt_users');

				$rowselect = $select_table->fetchAll($select_table->select()->where('username = ?',trim(($username)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}
			public function inactiveuser($userIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_users');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $userIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function getUserName($userId){
				$role = new Zend_Session_Namespace("role");
				if($role->role == 12){
					$select = new Zend_Db_Table('dbt_users');
					$select_table = $select->select();
					$select_table->setIntegrityCheck(false);
					$select_table->from(array("users" => "dbt_users"),array("*"));
					$select_table->join(array("state" => "dbt_state"),"state.state_code=users.state",array("state.state_name as state"));
					$select_table->where('users.id = ?',trim(intval($userId)));
					$rowselect = $select->fetchRow($select_table);
					return $rowselect;
				}else{
					$select_table = new Zend_Db_Table('dbt_users');
					$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($userId))));
					return $rowselect;     
				}

	
			}



/**********Updating image in user table under upload column************/

	public function updateimage($editdataform,$userid)
	        {
				//echo "aa";
				//die;
				//echo $userid->userid;
				//die;
				$updatedetails_selecttable = new Zend_Db_Table('dbt_users');
			    //echo $userid->userid;
				//die;

						$data = array(
								'upload'=>$editdataform
						);
						
						$where = array(

						'id = ?'      => $userid

								  );
				//echo $userid->userid;
				//die;

					$update_values = $updatedetails_selecttable->update($data, $where);
					return $update_values;
			}

/**********Updating image in user table under upload column ends**************/





public function changepassword($dataform)
				{
					$oldpassword = $this->findMd5Value($dataform['oldpassword']);
					$company_passtable = new Zend_Db_Table('dbt_users');
					$userid = new Zend_Session_Namespace('userid');
					$question_answer = $company_passtable->fetchRow($company_passtable->select()->where('password = ?', $oldpassword)->where('id = ?', $userid->userid));
					 $row=count($question_answer);
					//echo $session->clientuserid;
					//die;
					if($row>0)
						{
							//$md5NewPassword = md5($dataform['newpassword']);
							//$md5OldPassword = md5($dataform['oldpassword']);
								$md5NewPassword = $this->findMd5Value($dataform['newpassword']);
								$md5OldPassword = $this->findMd5Value($dataform['oldpassword']);
							
									//update new password 
										$nm1 = new Zend_Db_Table('dbt_users');
										//$data1="";
										//$where1="";
										$data1 = array('password' => $md5NewPassword);
										$where1 = array('id = ?'  => $userid->userid , 'password = ?'      => $md5OldPassword );
										$n1 = $nm1->update($data1, $where1);

							
																				
										//if($n1==1){
										return 1;		
									}else{
										return 2;
								}
				}


/**********Last seven days public documents**************/


							public function oneweekpublicdocs()
							{
								$late = date('Y-m-d H:i:s',time()-(7*86400)); // 7 days ago
								$select_table = new Zend_Db_Table('documents');
								$select = $select_table->select();
								$select->setIntegrityCheck(false);
								$select->from(array('d' => 'documents'), array( 'did as docid','created','owner as oid','folderid as foldid'))
								->joinInner(array('t' => 'temp_file_upload'), 'd.did = t.documentid', array('docname','documentid as fileid','version'))
								->joinInner(array('u' => 'dbt_users'), 'd.owner = u.id', array('id as usersid','username','firstname','lastname'))
								->joinInner(array('f' => 'folders'), 'd.folderid = f.fid', array('fid as ffid','pub_pri','display_status','name as fname','folder_path'))
								->where("f.pub_pri=1")
									->where("d.created >DATE_SUB(CURDATE(), INTERVAL 1 WEEK)")
								
									//->where("t.docname LIKE '%$name%' or d.name LIKE '%$name%'")
								->where("f.display_status=1")
								->order('d.created desc');
								
								$select_org = $select_table->fetchAll($select);
								return $select_org->toArray();

							}

								/**********Last seven days public documents ends here**************/
								/**********Last seven days public documents ends here**************/
//below funtion is add by the Upendra Yadav	05-08-2016							
	public function Getministry(){
		$user = new Zend_Db_Table("dbt_ministry");
		$select = $user->select();
		$select->from(array("ministry" => "dbt_ministry"),array("id","ministry_name"));
		$select->where("status = ?",'1');
		$select->where("translation_id != ?",'1');
		$select->order("ministry_name");
		$ministryDatat = $user->fetchAll($select);
		$data = $ministryDatat->toArray();
		//echo "<pre>";
		//print_r($data);
		//exit;
		return $data;
	}
	
	
	
	
	public function GetministryByUser($userid = null){
		$user = new Zend_Db_Table("dbt_users");
		$select = $user->select();
		$select->setIntegrityCheck(false);
		$select->from(array("ministry" => "dbt_ministry"),array("id","ministry_name"));
		$select->joinLeft(array("users" => "dbt_users"),"users.ministry_name=ministry.id",array("id","ministry_name as ministry_id"));
		$select->where("users.id = ?",$userid);
		$select->where("users.status = ?",'1');
		$select->where("ministry.status = ?",'1');
		$select->where("ministry.translation_id != ?",'1');
		//echo $select;exit;
		$ministryDatat = $user->fetchAll($select);
		$data = $ministryDatat->toArray();
		//echo $select;exit;
		// echo "<pre>";
		// print_r($data);
		// exit;
		return array_filter($data[0]);
	}
//Above funtion is add by the Upendra Yadav

		/**audit log start**/
			public function getuserrolename($id)
			{
				$select_tablerole = new Zend_Db_Table('dbt_roles');
				$rowselectrole= $select_tablerole->fetchRow($select_tablerole->select()->where('id = ?', $id));		
				$role_name = $rowselectrole->toArray();
				return $role_name['title'];
			}
			public function getuserstatename($id)
			{
				$select_table = new Zend_Db_Table('dbt_state');
				$getstate= $select_table->fetchRow($select_table->select()->where('state_code = ?', $id));		
				$state_name = $getstate->toArray();
				return $state_name['state_name'];
			}
			public function getdistrictname($id)
			{
				$select_table = new Zend_Db_Table('dbt_district');
				$getdistrict= $select_table->fetchRow($select_table->select()->where('district_code = ?', $id));		
				$district_name = $getdistrict->toArray();
				return $district_name['district_name'];
			}
			public function Getministryname($id){
				$user = new Zend_Db_Table("dbt_ministry");
				$select = $user->select();
				$select->from(array("ministry" => "dbt_ministry"),array("id","ministry_name"));
				$select->where("status = ?",'1');
				$select->where("id = ?",$id);
				$select->where("translation_id != ?",'1');
				$select->order("ministry_name");
				$ministryDatat = $user->fetchAll($select);
				$data = $ministryDatat->toArray();
				return $data[0]['ministry_name'];
			}
		/**audit log end**/






	public function checkscheme($userid=null){
		$select_table = new Zend_Db_Table('dbt_assign_manager');
		$select = $select_table->select();
		$select->from(array("am" => "dbt_assign_manager"),array("count(id) as counted"));
		$select->where('am.pm_id =?',trim(intval($userid)));
		$select->where("am.scheme_id !=''");
		$rowselect = $select_table->fetchAll($select);
		$data=$rowselect->toArray();
		return $data[0]['counted'];
		
		
	}
}