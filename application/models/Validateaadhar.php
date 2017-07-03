<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Validateaadhar extends Zend_Db_Table_Abstract {
	public function pr_man($data = null, $pm = null){
		echo "<pre>";print_r($data);echo "</pre>";
		if($pm == 1){
			exit;
		}
	}
	

//************* get scheme table name start now*****************

 public function getTable($scmid = null, $needle = null){
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_table"));
                $select->where("sch.id =?",$scmid);
                //echo $select;exit;
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
                //print_r($data);exit;
				if($needle === "tr"){
					return "dbt_".$data['scheme_table']."_transaction";
				}else{
					return "dbt_".$data['scheme_table'];
				}
                
            }
//************* get scheme table name start now*****************

	// for validate aaddhaar number by ncpi model start now --------------

public function aadharvalidate($scheme_id = null){
		$tabname = $this->getTable($scheme_id);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		$limit=100;
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate','tbname.mobile_num','tbname.created'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.aadhar_validate = ?', '0');
		// if($uuid != "" && $id != ""){
			// $select->where('tbname.id = ?', $id);
			// $select->where('tbname.uniq_user_id = ?', $uuid);
		// }
		
		if($user_role==12){
			$select->where('tbname.state_code = ?',trim($state_code->state_code));
		}
		$select->order('tbname.created DESC')->limit($limit,$start);
	//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//print_r($select_org);die;
		return $select_org;
	}
	
	public function update_aadhar_status($scheme_id=null,$aadhar_number=null,$validate_status=null,$aadhar_seeded=null,$error_remark=null){
				$tabname = $this->getTable($scheme_id);
		
				//$aadhar_number=$select_org[$i]['aadhar_num'];
				$update_table = new Zend_Db_Table($tabname);
				$data = array('aadhar_validate'=>$validate_status,
							  'aadhar_seeded'=>$aadhar_seeded,
							  'error_remark'=>$error_remark
							);
				$where = array(
						
						'aadhar_num = ?'      => $aadhar_number
								  );

				$update_values = $update_table->update($data, $where);
		}

// for validate aaddhaar number model start now --------------

	public function nonseededaadharvalidate($scheme_id = null){
		$tabname = $this->getTable($scheme_id);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		//$limit=800;
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate','tbname.mobile_num','tbname.created'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.aadhar_validate = 2 or tbname.aadhar_validate = 0');
		if($uuid != "" && $id != ""){
			$select->where('tbname.id = ?', $id);
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		
		if($user_role==12){
			$select->where('tbname.state_code = ?',trim($state_code->state_code));
		}
		$select->order('tbname.created DESC');
		//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//print_r($select_org);die;
		return $select_org;
	}
// ************* delete beneficiary start now **********************

public function deletebeneficiary($scheme_id=null,$beneficiary=null)
{
	
	$tabname = $this->getTable($scheme_id);
		
    $delete_benef = new Zend_Db_Table($tabname);
    $where="";
    $where = array('id = ?' => $beneficiary );
//$where;die;
    $delete_values = $delete_benef->delete($where);
	return $delete_values;

}

//*************** delete beneficiary end now ***********************


// for validate aaddhaar number by uidai model start now --------------

public function aadharvalidatebyuidai($scheme_id = null){
		$tabname = $this->getTable($scheme_id);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		//$limit=100;
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.pincode','tbname.gender','tbname.aadhar_validate','tbname.uidai_aadhar_validate','tbname.mobile_num','tbname.created'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.uidai_aadhar_validate = ?', '0');
		// if($uuid != "" && $id != ""){
			// $select->where('tbname.id = ?', $id);
			// $select->where('tbname.uniq_user_id = ?', $uuid);
		// }
		
		if($user_role==12){
			$select->where('tbname.state_code = ?',trim($state_code->state_code));
		}
		$select->order('tbname.created DESC');
//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//print_r($select_org);die;
		return $select_org;
	}

public function update_aadhar_byuidai($scheme_id=null,$aadhar_number=null,$validate_status=null,$error_remark=null){
				$tabname = $this->getTable($scheme_id);
		
				//$aadhar_number=$select_org[$i]['aadhar_num'];
				$update_table = new Zend_Db_Table($tabname);
				$data = array('uidai_aadhar_validate'=>$validate_status,
							  'uidai_error_remark'=>$error_remark
							);
				$where = array(
						
						'aadhar_num = ?'      => $aadhar_number
								  );

				$update_values = $update_table->update($data, $where);
		}

// for validate aadhaar number by uidai model start now --------------

	public function nonuidaiaadharvalidate($scheme_id = null){
		$tabname = $this->getTable($scheme_id);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		//$limit=100;
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.pincode','tbname.gender','tbname.uidai_aadhar_validate','tbname.mobile_num','tbname.created'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.uidai_aadhar_validate =2 or tbname.uidai_aadhar_validate =0');
		if($uuid != "" && $id != ""){
			$select->where('tbname.id = ?', $id);
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		
		if($user_role==12){
			$select->where('tbname.state_code = ?',trim($state_code->state_code));
		}
		$select->order('tbname.created DESC');
		//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//print_r($select_org);die;
		return $select_org;
	}

public function getschemename($scheme_id){
				$select_table = new Zend_Db_Table('dbt_scheme');
				$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
				return $row;
		}
		public function getschemecode($scheme_id){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
		return $row;
	}

public function getallpfmsbeneficiaries($scheme_id = null,$uuid = null, $id = null){
//echo $scheme_id;die("sdjcfsd");
		$tabname = $this->getTable($scheme_id);
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		if(isset($_GET['dob']) && $_GET['dob'] !=""){
			$dob = date("Y-m-d",strtotime($_GET['dob']));
		}else{
			$dob = "";
		}
		if(isset($_GET['name']) && $_GET['name'] !=""){
			$name = safexss($_GET['name']);
		}else{
			$name = "";
		}
		if(isset($_GET['benef_id']) && $_GET['benef_id'] !=""){
			$benef_id = safexss($_GET['benef_id']);
		}else{
			$benef_id = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('*'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.pfms_xml_status = ?','0');
		
		if($uuid != "" && $id != ""){
			$select->where('tbname.id = ?', $id);
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		 
		if($dob && $dob != 0){
			$select->where('tbname.dob = ?', $dob);
		}
		if($name != ""){
			$select->where("tbname.name LIKE '%$name%'");
		}		
		if($benef_id != ''){
			$select->where('tbname.uniq_user_id = ?', $benef_id);
		}
		if($user_role==12){
			$select->where('tbname.state_code = ?',trim($state_code->state_code));
		}
		
		$select->order('tbname.created DESC');
		 //echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	public function getallpfmstransaction($scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		
		
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','amount','transaction_date','fund_transfer','district_code','state_code','bank_name','ifsc','bank_account','pfmsbeneficiary_code','beneficiary_title','beneficiary_type'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.pfms_request_id','tr.txn_id','tr.remarked','tr.pfms_status','tr.transfer_by','tr.purpose','tr.from_payment_date','tr.to_payment_date'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.pfms_xml_status = ?', 0);
		
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		
		$select->order('tr.transaction_date DESC');
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//Beneficiary request count log day wise
	public function benreqcount($date){
		$select_table = new Zend_Db_Table('dbt_pfms_reuest_log_count');
		$result = $select_table->fetchAll($select_table->select()->where('request_date = ? ',$date));
		$benreqdata = $result->toArray();
		$benreqdata = $benreqdata['0'];
		if($benreqdata) {
			$requestno = $benreqdata['benrequest_no'];
		} else {
			$requestno = 0;
		}
		return $requestno;
	}
	//Update Beneficiary request count log
	public function updatebenreqcount($date,$sno){
		$select_table = new Zend_Db_Table('dbt_pfms_reuest_log_count');
		$result = $select_table->fetchAll($select_table->select()->where('request_date = ? ',$date));
		$benreqdata = $result->toArray();
		$benreqcount = count($benreqdata);
		if($benreqcount == 0){
			$countdatainsert="";
			$countdatainsert = array(
				'benrequest_no'=> $sno,
				'request_date'=> $date,
				'created'=> date('Y-m-d h:i:s')
			);
			$insertdata=$select_table->insert($countdatainsert);
			return $insertdata;
		} else {
			$dataupdate="";
			$dataupdate = array(
				'benrequest_no'=> $sno
			);
			$where = array(
				'request_date = ?' => $date
			);
			$update_values = $select_table->update($dataupdate, $where);
			return $update_values;
		}
	}
	
	//Update Xml Status
	public function updatexmlstatus($schemetablename,$beneficiariesids){
		$select_table = new Zend_Db_Table($schemetablename);
		$dataupdate="";
		$dataupdate = array(
			'pfms_xml_status'=> 1
		);
		$where = array(
			'uniq_user_id IN (?)' => $beneficiariesids
		);
		$update_values = $select_table->update($dataupdate, $where);
		return $update_values;
	}


//************* get scheme table name start now*****************

 public function getTablenameforbeneficiary($scmid = null, $needle = null){
				
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_table"));
                $select->where("sch.pfms_scheme_code=?",$scmid);
                //echo $select;exit;
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
                //print_r($data);exit;
				if($needle === "tr"){
					return "dbt_".$data['scheme_table']."_transaction";
				}else{
					return "dbt_".$data['scheme_table'];
				}
                
            }
//************* get scheme table name start now*****************

// update beneficiary pfms code after getting responce

public function updatebeneficiaryfrompfms($SchmeCd=null,$CPSMSId=null,$bankbranchcode=null,$bankname=null,$bankaccountnumber=null,$CstmrSts=null,$StsRsnInfCd=null,$StsRsnInfAddtlInf=null,$beneficiaryname=null){

		$tabname = $this->getTablenameforbeneficiary($SchmeCd);
		$update_table = new Zend_Db_Table($tabname);
			if($CstmrSts=='ACCP'){
				$CstmrSts=1;
				$updatedata = array('pfmsbeneficiary_code'=>$CPSMSId,
							  'beneficiary_pfms_status'=>$CstmrSts
							  //'pfms_rejection_code'=>$StsRsnInfCd,
							  //'pfms_error_remark'=>$StsRsnInfAddtlInf
							);
				}elseif($CstmrSts=='RJCT'){
				$CstmrSts=2;
				$updatedata = array('pfmsbeneficiary_code'=>$CPSMSId,
							  'beneficiary_pfms_status'=>$CstmrSts,
							  'pfms_rejection_code'=>$StsRsnInfCd,
							  'pfms_error_remark'=>$StsRsnInfAddtlInf
							);
				}else{
				$CstmrSts='';
				$updatedata = array(//'pfmsbeneficiary_code'=>$CPSMSId,
							  //'beneficiary_pfms_status'=>$CstmrSts,
							  //'pfms_rejection_code'=>$StsRsnInfCd,
							  //'pfms_error_remark'=>$StsRsnInfAddtlInf
							);
				}
		
		
		$where = array(
						'ifsc = ?' => $bankbranchcode,
						'bank_name = ?'  => $bankname,
						'bank_account = ?' => $bankaccountnumber,
						'name = ?' => $beneficiaryname
						  );
		//echo "<pre>";print_r($data);
		//echo "<pre>";print_r($where);
		//die;		

		$update_values = $update_table->update($updatedata, $where);
		return $update_values;
		}

	//Payment request count log day wise
	public function payreqcount($date){
		$select_table = new Zend_Db_Table('dbt_pfms_reuest_log_count');
		$result = $select_table->fetchAll($select_table->select()->where('request_date = ? ',$date));
		$payreqdata = $result->toArray();
		$payreqdata = $payreqdata['0'];
		if($payreqdata) {
			$requestno = $payreqdata['payrequest_no'];
		} else {
			$requestno = 0;
		}
		return $requestno;
	}
	//Update Payment request count log
	public function updatepayreqcount($date,$sno){
		$select_table = new Zend_Db_Table('dbt_pfms_reuest_log_count');
		$result = $select_table->fetchAll($select_table->select()->where('request_date = ? ',$date));
		$reqdata = $result->toArray();
		$reqcount = count($reqdata);
		if($reqcount == 0){
			$countdatainsert="";
			$countdatainsert = array(
				'payrequest_no'=> $sno,
				'request_date'=> $date,
				'created'=> date('Y-m-d h:i:s')
			);
			$insertdata=$select_table->insert($countdatainsert);
			return $insertdata;
		} else {
			$dataupdate="";
			$dataupdate = array(
				'payrequest_no'=> $sno
			);
			$where = array(
				'request_date = ?' => $date
			);
			$update_values = $select_table->update($dataupdate, $where);
			return $update_values;
		}
	}
	
	//Update Payment Xml Status
	public function updatepayxmlstatus($transactionTableName,$transactionids){
		$select_table = new Zend_Db_Table($transactionTableName);
		$dataupdate="";
		$dataupdate = array(
			'pfms_xml_status'=> 1
		);
		$where = array(
			'txn_id IN (?)' => $transactionids
		);
		$update_values = $select_table->update($dataupdate, $where);
		return $update_values;
	}


// check txn_id from transaction log table start now


public function checklogfortransaction($transactionids=null){
				$newscm = new Zend_Db_Table("dbt_tr__log_pfms_request");
                $select = $newscm->select();
                $select->from(array("log" => "dbt_tr__log_pfms_request"),array("count(*) as totalcount","log.tablename"));
                $select->where("log.msgId=?",$transactionids);
                //echo $select;exit;
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
				return $data;
	}

// insert log for transaction start now

public function insertlogfortransaction($transactionTableName=null,$transactionids=null){
		//echo $transactionTableName.' txn_id='.$transactionids;die;
		$user_table = new Zend_Db_Table('dbt_tr__log_pfms_request');
		
		$datainsert=array();
		$datainsert = array(
			'tablename'=>$transactionTableName ,
			'msgId'=>$transactionids
		);	
	
		$insertdata=$user_table->insert($datainsert);
		return $insertdata;
	}

// Update transaction status start now 


// update beneficiary pfms code after getting responce

public function updatetransactionfrompfms($OrgnlEndToEndId=null,$PFMSBeneficiaryId=null,$PaymentSts=null,$PaymentRjctCd=null,$PaymentRjctMsg=null,$transactiontable=null){
	//echo '1-'.$OrgnlEndToEndId.'2-'.$PFMSBeneficiaryId.'3-'.$PaymentSts.'4-'.$PaymentRjctCd.'5-'.$PaymentRjctMsg.'6-'.$transactiontable;die;
	$date=date('Y-m-d');
	$update_table = new Zend_Db_Table($transactiontable);
			if($PaymentSts=='ACCP'){
				$PaymentSts=1;
				
		$updatedata='';
		$updatedata = array('transaction_status'=>$PaymentSts,
							  'pfms_request_id'=>$PFMSBeneficiaryId,
							  'approval_transaction_date'=>$date
							  //'pfms_transaction_remark_code'=>$PaymentRjctMsg
							);

				}elseif($PaymentSts=='RJCT'){
				$PaymentSts=2;
				
		$updatedata='';
		$updatedata = array('transaction_status'=>$PaymentSts,
							  'pfms_request_id'=>$PFMSBeneficiaryId,
							  'pfms_transaction_error_code'=>$PaymentRjctCd,
							  'pfms_transaction_remark_code'=>$PaymentRjctMsg,
							  'approval_transaction_date'=>$date
							);
				}else{
				$PaymentSts='';
				}
			
		
		$where = array('txn_id=?'=>$OrgnlEndToEndId);
		// echo "<pre>";print_r($updatedata);
		// echo "<pre>";print_r($where);
		// die;		

		$update_values = $update_table->update($updatedata,$where);
		return $update_values;
		}
	
}
?>