<?php
//require_once("/models/DbTable/Table.php");
//require_once("/models/Admin.php");
//__autoloadDB('Db');
//include("Db.php");
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Forgot extends Zend_Db_Table_Abstract 
{
	public function encryptpassword($username = null){
		$temp = hash_hmac('sha256', strtotime(date("Y-m-d H:i:s")).md5($username).strtotime(date("Y-m-d H:i:s")),"");
		//echo $temp;exit;
		$up = new Zend_Db_Table('dbt_users');
		$data="";
		$where="";
		$data = array(
					'tmp_password'=> $temp,
					'dateModify'=> date("Y-m-d H:i:s"),
						);
			    $where = array(
				'username = ?'=> $username
				);
				$update_values = $up->update($data, $where);
				return $temp;
		//exit;
	}
	 public function password($username){
                $select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('username = ?',safexss($username)));
				return $rowselect;
			}


			public function checkuser($userid = null,$temp_pass = null){
                $select_table = new Zend_Db_Table('dbt_users');
				if($temp_pass != ""){
					$rowselect = $select_table->fetchAll($select_table->select()->where('id = ?',$userid)->where('tmp_password = ?',safexss($temp_pass)))->toArray();
				}else{
					$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',$userid));
				}
				return $rowselect;
			}			

		public function updateuser($dataform = null,$userid = null){
				$updatedetails_selecttable = new Zend_Db_Table('dbt_users');
				$data="";
			    $where="";
			    $data = array(
						 'password'=> hash_hmac('sha256', $dataform['conpass'], ''),
						 'tmp_password' => ''
						);
			    $where = array(
				'id = ?'=> $userid
				);
				$update_values = $updatedetails_selecttable->update($data, $where);
				return $update_values;
			}


		function createcode($length)
	           {
		       $chars="233422y567ab3cd3ef74676g44575h7ij59klm86no46357pq345rst77766541uv63ef74676g44575h7ij59klm86no67wx88yz";
		       $code="";
			   for($i=0; $i<$length; $i++)
				{
					$code.=$chars{mt_rand(0, strlen($chars)-1)};
				}
				return $code;
	           }


		public function updatedetails($pass,$username)
	        {
                $updatedetails_selecttable = new Zend_Db_Table('dbt_users');
				   $data="";
			       $where="";
			    $data = array(
				         //'password'=> md5($pass),
						 'password'=> hash_hmac('sha256', $pass, '')
						);
			    $where = array(
				'username = ?'=> $username
				);
				$update_values = $updatedetails_selecttable->update($data, $where);
				return $update_values;
			}
		public function generateCode($username){
				$pass = md5(uniqid(rand(), true)).substr(md5($username),2,10);
				$userpassUpdate = new Zend_Db_Table('dbt_users');
				$data="";
			    $where="";
			    $data = array('tmp_password' => $pass);
			    $where = array('username = ?'=> $username);
				$update_values = $userpassUpdate->update($data, $where);
				return $pass;
			}
		public function passwordChange($tmp_pass=null, $username=null){
                $selectTB = new Zend_Db_Table('dbt_users');
				$rowselect = $selectTB->fetchRow($selectTB->select()->where('username = ?',$username)->where('tmp_password = ?',$tmp_pass));
				return $rowselect;
			}	
}

