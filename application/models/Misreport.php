<?php
require_once 'Zend/Db/Table/Abstract.php';
//require_once 'Zend/Db/Sql/Sql;';
class Application_Model_Misreport extends Zend_Db_Table_Abstract {
	public function pr_man($data = null, $pm = null){
		echo "<pre>";print_r($data);echo "</pre>";
		if($pm == 1){
			exit;
		}
	}
	public function findSchemeName($schemeId = null){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->from(array("scheme"=> "dbt_scheme"), array('id','scheme_name','scheme_table','scheme_type'));
		$select->where('id  =?' ,$schemeId);
		$select->where('status = 1');
		//$select->order('id DESC');
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}
	public function findSchemeType($schemeNo = null){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->from(array("scheme"=> "dbt_scheme_manual_data"), array('type'));
		$select->where('scheme_no  =?' ,$schemeNo);
		//$select->where('status = 1');
		//$select->order('id DESC');
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}
	
	//below function is using for the insert the State Name to the Database
	public function sms_state($state_code = null){
		$selectobj = new Zend_Db_Table("dbt_state");
		$select = $selectobj->select();
		$select->from(array("st"=>"dbt_state"),array("st.state_name"));
		$select->where("state_code = ?",$state_code);
		$data = $selectobj->fetchAll($select)->toArray();
		//$this->pr_man($data,1);
		return $data[0]['state_name'];
	}
	
	//below function is using for the insert the District Name to the Database
	public function sms_district($district = null){
		$selectobj = new Zend_Db_Table("dbt_district");
		$select = $selectobj->select();
		$select->from(array("dt"=>"dbt_district"),array("dt.district_name"));
		$select->where("dt.district_code = ?",$district);
		$data = $selectobj->fetchAll($select)->toArray();
		//$this->pr_man($data,1);
		return $data[0]['district_name'];
	}
	public function sms_block($block_code = null){
		$selectobj = new Zend_Db_Table("dbt_block");
		$select = $selectobj->select();
		$select->from(array("bl"=>"dbt_block"),array("bl.title"));
		$select->where("bl.block_code = ?",$block_code);
		$data = $selectobj->fetchAll($select)->toArray();
		//$this->pr_man($data,1);
		return $data[0]['title'];
	}
	public function sms_village($village_code = null){
		$selectobj = new Zend_Db_Table("dbt_village");
		$select = $selectobj->select();
		$select->from(array("vi"=>"dbt_village"),array("vi.village_name"));
		$select->where("vi.village_code = ?",$village_code);
		$data = $selectobj->fetchAll($select)->toArray();
		//$this->pr_man($data,1);
		return $data[0]['village_name'];
	}
	
	public function insertbeneficiaryrecord($data = null, $scheme_id = null, $uuid = null, $id = null, $scmtype= null){}
	
  public function InsertauditdataCSV($userid = null,$scheme_id = null,$filename = null){}
	
    public function GetBeneficiaries($tablename = null, $scheId = null, $soId = null, $month = null, $year = null, $start, $limit){}
	
	//Function is using for get the table for inserting and updating
	//@params : Scheme Id and Needle->send tr for transaction table or leave blank for the scheme data table
	//Transaction table and beneficiaries table
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
	public function checkasignedschemeid($userid,$scheme_id){
				$select_table = new Zend_Db_Table('dbt_assign_manager');
				$row = $select_table->fetchAll($select_table->select()->where("find_in_set(".$scheme_id.", scheme_id) and pm_id=".$userid));
				return count($row);
			}
	//find the all beneficiaries record who added in to the scheme
	public function beneficiarydatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		if(isset($_GET['month'])){
			$month = $_GET['month'];
		}else{
			$month = "";
		}
		if(isset($_GET['year'])){
			$year = $_GET['year'];
		}else{
			$year = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		if($uuid != "" && $id != ""){
			$select->where('tbname.id = ?', $id);
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($month && $month != 0){
			$select->where('tbname.month = ?', $month);
		}				
		if($year && $year != 0){
			$select->where('tbname.year = ?', $year);
		}
		$select->order('tbname.year DESC')->order('month ASC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countbeneficiarydata($scheme_id){
		$tabname = $this->getTable($scheme_id);	
		if(isset($_GET['month'])){
			$month = $_GET['month'];
		}else{
			$month = "";
		}
		if(isset($_GET['year'])){
			$year = $_GET['year'];
		}else{
			$year = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select_query =  $select_table->select();
		$select_query->from(array('beneficiary' => $tabname));		
		$select_query->where('scheme_id=?',$scheme_id);	
        if($month && $month != 0){
				$select_query->where('month = ?', $month);
		}				
		if($year && $year != 0){
			$select_query->where('year = ?', $year);
		}
		$rowlist = $select_table->fetchAll($select_query);
		//echo $select_query; die;
        return count($rowlist); 				
	}
	//get the single beneficiaries full record
	//@params : scheme id, user id, and the uniquely generated id
	//Output : Record of the user from import table and transaction tabele
	public function beneficiarydatalistfullview($scheme_id = null, $id = null, $uuid = null){}
	
	
	//below function is use for the exporting csv file according to the record filtering and all
	public function csvexportmethoddb($schemeid = null,$minid = null,$month = null,$year = null){}
	
	//getting single user details
	public function singleuserdb($schemeid = null,$id = null,$uuid = null){}
	
	/***** get the ministry name method ***************/
	
	public function getministryname($min_id){
				$select_table = new Zend_Db_Table('dbt_ministry');
				$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$min_id));
				return $row->toArray();
		}
	
	/****************end********************************/
	//below function is using for the get aadhar number existance in table	
	public function getAadhaar($aaddhaar = null, $scheme_id = null){
		$tbname = $this->getTable($scheme_id);
		$selecttb = new Zend_Db_Table($tbname);
		$select = $selecttb->select();
		$select->from(array("tbnm"=>$tbname),array("tbnm.aadhar_num"));
		$select->where("tbnm.aadhar_num = ?",$aaddhaar);
		//return $select;
		$data = $selecttb->fetchAll($select)->toArray();
		return $data;
	}
//below function is using for the get aadhar number existance in table end	



	//Below function is using for the get the ministry by user id
	//@params: Parameter as current user id
	//@Output: resultant of the ministry name and ministry id 
	public function Getministry($userid = null, $min_id = null, $role = null){
		//echo $role;exit;
		if($userid!="" && $min_id == null && $role == 4 || $role == 12){//for the scheme owner
			$am_user = new Zend_Db_Table("dbt_assign_manager");
			$am_select = $am_user->select();
			$am_select->from(array("am"=>"dbt_assign_manager"),array('scheme_id'));
			$am_select->where("pm_id = ?",$userid);
			//echo $am_select;exit;
			$data = $am_user->fetchAll($am_select)->toArray();
			if(count($data) > 0){
				if(trim($data[0]['scheme_id']) == ""){
					$scm_id = 0;
				}else{
					$scm_id = trim($data[0]['scheme_id']);
				}
			}else{
				$scm_id = 0;
			}
			
			$user = new Zend_Db_Table("dbt_ministry");
			$select = $user->select();
			$select->setIntegrityCheck(false);
			$select->from(array("min" => "dbt_ministry"),array("min.id as min_id","min.ministry_name as min_name"));
			$select->join(array("scm" => "dbt_scheme"),"scm.ministry_id = min.id ",array());
			$select->where("scm.id IN ($scm_id)");
			//echo $select;exit;
			$select->where("min.status = ?",'1');
			$select->order("min.ministry_name");
			$ministryDatat = $user->fetchAll($select);
			$data = $ministryDatat->toArray();
			return $data;
		}
		else if($userid!="" && $min_id != "" && $role == 6){
			$user = new Zend_Db_Table("dbt_ministry");
			$select = $user->select();
			$select->setIntegrityCheck(false);
			$select->from(array("min" => "dbt_ministry"),array("min.id as min_id","min.ministry_name as min_name"));
			$select->join(array("scm" => "dbt_scheme"),"scm.ministry_id = min.id ",array());
			$select->where("min.id = ?", $min_id);
			$select->where("min.status = ?",'1');
			$ministryDatat = $user->fetchAll($select);
			$data = $ministryDatat->toArray();
			return $data;
		}else if($userid=="" && $min_id == null && $role == null){
			$user = new Zend_Db_Table("dbt_ministry");
			$select = $user->select();
			$select->setIntegrityCheck(false);
			$select->from(array("ministry" => "dbt_ministry"),array("ministry.id as min_id","ministry.ministry_name as min_name"));
			if($userid != null){
				$select->join(array("user" => "dbt_users"),"user.ministry_name=ministry.id",array());
				$select->where("user.id = ?",$userid);
			}
			$select->where("ministry.status = ?",'1');
			$select->order("ministry.ministry_name");
			$ministryDatat = $user->fetchAll($select);
			$data = $ministryDatat->toArray();
			return $data;
		}
	}
//Below function is using for the get the scheme by user id from the assign manager tb
//@params: Parameter as current scheme owner user id
//@Output: resultant of the scheme id as comma seprated
	public function GetOwnerId($userid = null){
		//if(isset($ministry_id) && $ministry_id != ""){
			$user = new Zend_Db_Table("dbt_assign_manager");
			$select = $user->select();
			//$select->setIntegrityCheck(false);
			$select->from(array("am" => "dbt_assign_manager"),array("scheme_id"));
			$select->where("am.pm_id = ?", $userid);
			$ministryDatat = $user->fetchAll($select);
			$data = $ministryDatat->toArray();
			if(count(array_filter($data)) > 0){
				if($data[0]['scheme_id'] == 0){
					$scm_id = 0;
				}else{
					$scm_id = $data[0]['scheme_id'];
				}
			}else{
				$scm_id = 0;
			}
			return trim($scm_id);
	}
	
	
//Below function is using for the get the scheme by user id
//@params: Parameter as current user id
//@Output: resultant of the scheme name and scheme id 
	public function Getscheme($userid = null,$ministry_id = null, $currentMin = null,$state_name = null, $role = null){
		//echo $userid;exit;
			$rolen = new Zend_Session_Namespace('role');
			$role = $rolen->role;
			if($role == 4 || $role == 12){
				$idsUsed = $this->GetOwnerId($userid);//assign manager scheme_id
			}
			$user = new Zend_Db_Table("dbt_scheme");
			$select = $user->select();
			$select->setIntegrityCheck(false);
			$select->from(array("scm" => "dbt_scheme"),array("scm.id as scm_id","scm.scheme_name as scm_name"));
			if($ministry_id){
				$select->where("scm.ministry_id = ?", $ministry_id);
			}
			if($currentMin){
				$select->where("scm.ministry_id = ?", $currentMin);
			}
			if($role == 4 || $role == 12){
				$select->where("scm.id IN ($idsUsed)");
			}
			$select->where("scm.status = ?",'1');
			$select->order("scm.scheme_name");
			//echo $select;exit;
			$ministryDatat = $user->fetchAll($select);
			$data = $ministryDatat->toArray();
			return $data;
		//}
	}
	
    /*-------- Get current scheme detail start now--------------*/

public function getcurrentschemeType($curent_scheme_id = null){
		//echo $userid;exit;
			
			$user = new Zend_Db_Table("dbt_scheme");
			$select = $user->select();
			$select->from(array("scm"=>"dbt_scheme"),array("scheme_type"));
			if($curent_scheme_id!=null){
				$select->where("scm.id = ?", $curent_scheme_id);
			}
			//echo $select;exit;
			$currentschemeData = $user->fetchAll($select);
			$data = $currentschemeData->toArray();
			return $data;
		//}
	}

	/*-------- Get current scheme detail end now--------------*/
	
//This function is using for the get Scheme related cummalative record for the scheme header set
//@Params: Ministry Id and Scheme Id
//@Output : Return the Schem Total beneficiaries, aadhaar seeded and 2 some more record
	public function GetMainSchemeData($min_id = null, $scm_id = null){
		//echo "aaa".$scm_id;exit;
		$tbname = $this->getTable($scm_id);
		$transaction = $this->getTable($scm_id,"tr");
		
		
		$tbobj = new Zend_Db_Table($tbname);
		$select = $tbobj->select();
		//$select->setIntegrityCheck(false);
		$select->from(array("tbname" => $tbname), array("COUNT('tbname.id') AS total_beneficiaries","SUM(IF(tbname.aadhar_seeded='Y',1,0)) AS aadhaar_seeded"));
		$dataarr = $tbobj->fetchAll($select)->toArray();
		if(count($dataarr) > 0){
			if($dataarr[0]['total_beneficiaries'] == ""){
				$dataarr[0]['total_beneficiaries'] = 0;
			}
			if($dataarr[0]['aadhaar_seeded'] == ""){
				$dataarr[0]['aadhaar_seeded'] = 0;
			}
		}else{
			$dataarr[0]['total_beneficiaries'] = 0;
			$dataarr[0]['aadhaar_seeded'] = 0;
		}
		
//Get the Transactions value for the scheme report		
		$trobj = new Zend_Db_Table($transaction);
		$selectr = $trobj->select();
		$selectr->setIntegrityCheck(false);
		$selectr->from(array("transaction"=>$transaction),array("SUM(transaction.amount) as total_amount","SUM(IF(transaction.fund_transfer='APB',transaction.amount,0)) as aadhaar_payment"));
		$selectr->join(array("tbname1" => $tbname),"tbname1.uniq_user_id = transaction.uniq_user_id",(''));
		$selectr->where("transaction.transaction_status = ?", 1);
		//echo $selectr;die;
		$dataar = $trobj->fetchAll($selectr)->toArray();
		if(count($dataar) > 0){
			if($dataar[0]['total_amount'] == ""){
				$dataar[0]['total_amount'] = 0;
			}
			if($dataar[0]['aadhaar_payment'] == ""){
				$dataar[0]['aadhaar_payment'] = 0;
			}
		}else{
			$dataar[0]['total_amount'] = 0;
			$dataar[0]['aadhaar_payment'] = 0;
		}
		$data = array();
		$data = array_merge($dataarr,$dataar);
		return $data;
		//echo $select;exit;
	}
	
	
	
	
//This function is using for the get Scheme all record
//@Params: Ministry Id,Scheme Id,State Name, District Name, Block Name, Village Name, Gender, APB/NEFT/NACH/CASH, Seeded With Aadhar or Not etc
//@Output : Return the Schem all record to the screen
	public function GetSchemeData($min_id = null, $scm_id = null,$st = null,$dt = null,$bl = null,$vl = null,$gender = null,$ft = null,$tb = null,$todate = null,$fromdate = null,$ans=null,$bas=null,$start=null,$limit = null){
		
		$role = new Zend_Session_Namespace("role");
		if($role->role == 12){
			$state_code = new Zend_Session_Namespace('state_code');
			$st = $state_code->state_code;//Assigning state code for leave redundancy
		}
		//$start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null
		$tabname = $this->getTable($scm_id);
		$transaction = $this->getTable($scm_id,"tr");
		
		if($todate != 0){
			$to = date("Y-m-d",strtotime($todate));
		}else{
			$to = null;
		}
		if($fromdate != 0){
			$from = date("Y-m-d",strtotime($fromdate));
		}else{
			$from = null;
		}
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('tbname.uniq_user_id','tbname.name','tbname.dob','tbname.gender','tbname.aadhar_num','tbname.mobile_num','email_id','tbname.scheme_specific_unique_num','tbname.scheme_specific_family_num','tbname.home_address','tbname.village_name','tbname.block_name','tbname.district_name','tbname.state_name','tbname.ration_card_num','tbname.tin_family_id','tbname.bank_account','tbname.ifsc','tbname.aadhar_seeded','tbname.amount','tbname.fund_transfer','tbname.transaction_date'));
		//$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.transfer_by'));
		$select->where('tbname.scheme_id = ?', $scm_id);
		$select->where('tr.transaction_status = ?', 1);
		if($st != 0){
			$select->where('tbname.state_code = ?', $st);
		}
		if($dt != 0){
			$select->where('tbname.district_code = ?', $dt);
		}
		if($bl != 0){
			$select->where('tbname.block_code = ?', $bl);
		}
		if($vl != 0){
			$select->where('tbname.village_code = ?', $vl);
		}
		if($gender == "M" || $gender == "F"){
			$select->where('tbname.gender = ?', $gender);
		}
		if($ft == "APB" || $ft == "NEFT" || $ft == "NACH" || $ft == "CASH" || $ft == "RTGS"){
			$select->where('tr.fund_transfer = ?', $ft);
		}
		if($tb == "Bio Authentication" || $tb == "Demographic Authentication" || $tb == "Manual Validation"){
			$select->where('tr.transfer_by = ?', $tb);
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($ans == "1"){
			$select->where('tbname.aadhar_num != ?', "");
		}
		if($ans == "2"){
			$select->where('tbname.aadhar_num = ?', "");
		}
		if($bas == "1"){
			$select->where('tbname.aadhar_seeded = ?', "Y");
		}
		if($bas == "2"){
			$select->where('tbname.aadhar_seeded = ?', "N");
		}
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	
	
	public function CountSchemeDataJoin($min_id = null, $scm_id = null,$st = null,$dt = null,$bl = null,$vl = null,$gender = null,$ft = null,$tb = null,$todate = null,$fromdate = null,$ans=null,$bas=null){
		//$start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null
		$tabname = $this->getTable($scm_id);
		$transaction = $this->getTable($scm_id,"tr");
		
		if($todate != 0){
			$to = date("Y-m-d",strtotime($todate));
		}else{
			$to = null;
		}
		if($fromdate != 0){
			$from = date("Y-m-d",strtotime($fromdate));
		}else{
			$from = null;
		}
		$role = new Zend_Session_Namespace("role");
		if($role->role == 12){
			$state_code = new Zend_Session_Namespace('state_code');
			$st = $state_code->state_code;//Assigning state code for leave redundancy
		}
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('tbname.uniq_user_id','tbname.name','tbname.dob','tbname.gender','tbname.aadhar_num','tbname.mobile_num','email_id','tbname.scheme_specific_unique_num','tbname.scheme_specific_family_num','tbname.home_address','tbname.village_name','tbname.block_name','tbname.district_name','tbname.state_name','tbname.ration_card_num','tbname.tin_family_id','tbname.bank_account','tbname.ifsc','tbname.aadhar_seeded','tbname.amount','tbname.fund_transfer','tbname.transaction_date'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select->where('tr.transaction_status = ?', 1);
		if($st != 0){
			$select->where('tbname.state_code = ?', $st);
		}
		if($dt != 0){
			$select->where('tbname.district_code = ?', $dt);
		}
		if($bl != 0){
			$select->where('tbname.block_code = ?', $bl);
		}
		if($vl != 0){
			$select->where('tbname.village_code = ?', $vl);
		}
		if($gender == "M" || $gender == "F"){
			$select->where('tbname.gender = ?', $gender);
		}
		if($ft == "APB" || $ft == "NEFT" || $ft == "NACH" || $ft == "CASH" || $ft == "RTGS"){
			$select->where('tr.fund_transfer = ?', $ft);
		}
		if($tb == "Bio Authentication" || $tb == "Demographic Authentication" || $tb == "Manual Validation"){
			$select->where('tr.transfer_by = ?', $tb);
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($ans == "1"){
			$select->where('tbname.aadhar_num != ?', "");
		}
		if($ans == "2"){
			$select->where('tbname.aadhar_num = ?', "");
		}
		if($bas == "1"){
			$select->where('tbname.aadhar_seeded = ?', "Y");
		}
		if($bas == "2"){
			$select->where('tbname.aadhar_seeded = ?', "N");
		}
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return count(array_filter($select_org));
	}
	
	
	//This function is using for the export Scheme all record in xml
//@Params: Ministry Id,Scheme Id,State Name, District Name, Block Name, Village Name, Gender, APB/NEFT/NACH/CASH, Seeded With Aadhar or Not etc
//@Output : Return the Schem all record to the screen
	public function GetSchemeDataExport($min_id = null, $scm_id = null,$st = null,$dt = null,$bl = null,$vl = null,$gender = null,$ft = null,$tb = null,$todate = null,$fromdate = null,$ans=null,$bas=null,$start=null,$limit = null){
		//$start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null
 //echo $ans.' '.$bas;die;
		$tabname = $this->getTable($scm_id);
		$transaction = $this->getTable($scm_id,"tr");
		$schemedetail = $this->getschemecode($scm_id);
		$schmetype=$schemedetail[0]['scheme_type'];
		if(isset($_GET['todate']) && $_GET['todate'] != 0){
			$to = date("Y-m-d",strtotime($_GET['todate']));
		}else{
			$to = null;
		}
		if(isset($_GET['fromdate']) && $_GET['fromdate'] != 0){
			$from = date("Y-m-d",strtotime($_GET['fromdate']));
		}else{
			$from = null;
		}
		$role = new Zend_Session_Namespace("role");
		if($role->role == 12){
			$state_code = new Zend_Session_Namespace('state_code');
			$st = $state_code->state_code;//Assigning state code for leave redundancy
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('tbname.village_code','tbname.village_name','tbname.panchayat_code','tbname.panchayat_name','tbname.block_code','tbname.block_name','tbname.district_code','tbname.district_name','tbname.state_code','tbname.state_name'));
		//$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array("IF('$schmetype'=2,tr.transfer_by,tr.fund_transfer) AS fund_transfer",'SUM(tr.amount) AS amount','COUNT(IF(tr.id!="",1,0)) AS NoOfTransaction','IF(tr.fund_transfer="APB" OR tr.fund_transfer="apb",1,0) AS AadharSeededTransaction','DATE_FORMAT(tr.transaction_date,"%d/%m/%Y") as transaction_date','DATE_FORMAT(tr.transaction_date,"%d/%m/%Y") as payment_date'));
		$select->where('tbname.scheme_id = ?', $scm_id);
		$select->where('tr.transaction_status = ?', 1);
		$select->group('tbname.state_code');
		if($st != 0){
			$select->where('tbname.state_code = ?', $st);
			//$select->group('tbname.state_code');
		}
		if($dt != 0){
			$select->where('tbname.district_code = ?', $dt);
			$select->group('tbname.district_code');
		}
		if($bl != 0){
			$select->where('tbname.block_code = ?', $bl);
			$select->group('tbname.block_code');
		}
		if($vl != 0){
			$select->where('tbname.village_code = ?', $vl);
			$select->group('tbname.village_code');
		}
		if($gender == "M" || $gender == "F"){
			$select->where('tbname.gender = ?', $gender);
		}
		if($ft == "APB" || $ft == "NEFT" || $ft == "NACH" || $ft == "CASH" || $ft == "RTGS"){
			$select->where('tr.fund_transfer = ?', $ft);
			$select->group('tbname.fund_transfer');
		}
		// if($tb == "Bio Authentication" || $tb == "Demographic Authentication" || $tb == "Manual Validation"){
			// $select->where('tr.transfer_by = ?', $tb);
		// }
		if($tb == "1"){
			//$tb == "Bio Authentication";
			$select->where('tr.transfer_by = ?', "Bio Authentication");
			$select->group('tbname.transfer_by');
		}
		else if($tb == "2"){
			//$tb == "Demographic Authentication";
			$select->where('tr.transfer_by = ?', "Demographic Authentication");
			$select->group('tbname.transfer_by');
		}else if($tb == "3"){
			//$tb == "Manual Validation";
			$select->where('tr.transfer_by = ?', "Manual Validation");
			$select->group('tbname.transfer_by');
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($ans == "1"){
			$select->where('tbname.aadhar_num != ?', "");
		}
		if($ans == "2"){
			$select->where('tbname.aadhar_num = ?', "");
		}
		if($bas == "1"){
			$select->where('tbname.aadhar_seeded = ?', "Y");
		}
		if($bas == "2"){
			$select->where('tbname.aadhar_seeded = ?', "N");
		}
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	
	public function GetSchemeDataExportxml($min_id = null, $scm_id = null,$st = null,$dt = null,$bl = null,$vl = null,$gender = null,$ft = null,$tb = null,$todate = null,$fromdate = null,$ans=null,$bas=null,$start=null,$limit = null){
		//$start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null
 //echo $ans.' '.$bas;die;
		$tabname = $this->getTable($scm_id);
		$transaction = $this->getTable($scm_id,"tr");
		$schemedetail = $this->getschemecode($scm_id);
		$schmetype=$schemedetail[0]['scheme_type'];
		if(isset($_GET['todate']) && $_GET['todate'] != 0){
			$to = date("Y-m-d",strtotime($_GET['todate']));
		}else{
			$to = null;
		}
		if(isset($_GET['fromdate']) && $_GET['fromdate'] != 0){
			$from = date("Y-m-d",strtotime($_GET['fromdate']));
		}else{
			$from = null;
		}
		$role = new Zend_Session_Namespace("role");
		if($role->role == 12){
			$state_code = new Zend_Session_Namespace('state_code');
			$st = $state_code->state_code;//Assigning state code for leave redundancy
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('tbname.name','tbname.dob','tbname.gender','tbname.aadhar_num','tbname.mobile_num','tbname.email_id','tbname.scheme_specific_unique_num','tbname.scheme_specific_family_num','tbname.home_address','tbname.village_code','tbname.village_name','tbname.panchayat_code','tbname.panchayat_name','tbname.block_code','tbname.block_name','tbname.district_code','tbname.district_name','tbname.state_code','tbname.state_name','tbname.pincode','tbname.ration_card_num','tbname.tin_family_id'));
		//$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('SUM(tr.amount) AS amount','tr.transfer_by','tbname.aadhar_seeded','tbname.bank_account','tbname.ifsc','IF(tbname.aadhar_seeded="" OR tbname.aadhar_seeded="N" OR tbname.aadhar_seeded="n",0,1) AS AadharSeededBeneficiaries',"IF('$schmetype'=2,tr.transfer_by,tr.fund_transfer) AS fund_transfer","count(IF(tr.id!='',1,0)) AS no_of_beneficiries","IF('$schmetype'=2,tr.transaction_date,tr.approval_transaction_date) AS transaction_date"));
		
		$select->where('tbname.scheme_id = ?', $scm_id);
		$select->where('tr.transaction_status = ?', 1);
		$select->group('tbname.state_code');
		if($st != 0){
			$select->where('tbname.state_code = ?', $st);
		}
		if($dt != 0){
			$select->where('tbname.district_code = ?', $dt);
			$select->group('tbname.district_code');
		}
		if($bl != 0){
			$select->where('tbname.block_code = ?', $bl);
			$select->group('tbname.block_code');
		}
		if($vl != 0){
			$select->where('tbname.village_code = ?', $vl);
			$select->group('tbname.village_code');
		}
		if($gender == "M" || $gender == "F"){
			$select->where('tbname.gender = ?', $gender);
		}
		if($ft == "APB" || $ft == "NEFT" || $ft == "NACH" || $ft == "CASH" || $ft == "RTGS"){
			$select->where('tr.fund_transfer = ?', $ft);
			$select->group('tr.fund_transfer');
		}
		// if($tb == "Bio Authentication" || $tb == "Demographic Authentication" || $tb == "Manual Validation"){
			// $select->where('tr.transfer_by = ?', $tb);
		// }
		if($tb == "1"){
			//$tb == "Bio Authentication";
			$select->where('tr.transfer_by = ?', "Bio Authentication");
			$select->group('tr.transfer_by');
		}
		else if($tb == "2"){
			//$tb == "Demographic Authentication";
			$select->where('tr.transfer_by = ?', "Demographic Authentication");
			$select->group('tr.transfer_by');
		}else if($tb == "3"){
			//$tb == "Manual Validation";
			$select->where('tr.transfer_by = ?', "Manual Validation");
			$select->group('tr.transfer_by');
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($ans == "1"){
			$select->where('tbname.aadhar_num != ?', "");
		}
		if($ans == "2"){
			$select->where('tbname.aadhar_num = ?', "");
		}
		if($bas == "1"){
			$select->where('tbname.aadhar_seeded = ?', "Y");
		}
		if($bas == "2"){
			$select->where('tbname.aadhar_seeded = ?', "N");
		}
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}



}
?>