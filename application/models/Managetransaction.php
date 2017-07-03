<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Managetransaction extends Zend_Db_Table_Abstract {
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
	
	
	//this function is use for the generate the unique code of the user specific
	public function uniquecodegenerator($name = null, $dob = null,$gender = null,$ssun = null, $mob = null){
		$rando = rand().uniqid().rand().uniqid();
		$dateday = date("d", strtotime($dob));
		$idmix = md5($name.substr($name,1,2).$rando.$dateday.$gender.$ssun.substr($mob, 1,5));
		$dataid = strtoupper($idmix.$rando);
		$dataun = substr($dataid,1,4).substr($dataid,2,5);
		return $dataun;
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
	 
  public function InsertauditdataCSV($userid = null,$scheme_id = null,$filename = null){
        $user_table = new Zend_Db_Table('dbt_audit_import_csv_log');
        $date = date('Y-m-d H:i:s');
        $datainsert="";
        $datainsert = array(
              'userid'=> $userid,
              'scheme_id'=> $scheme_id,
              'filename'=> $filename,
              'status'=> 1,
              'created' => $date
            );
        // print_r($datainsert); exit;   
        $insertdata=$user_table->insert($datainsert);
        return $insertdata;
      }
	
	public function GetDataCount(){
		$select_table = new Zend_Db_Table("dbt_home_page_master_data_current_year");
		$select = $select_table->select();
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}
	
	
	public function GetDataCountMonthSeeded(){
		$select_table = new Zend_Db_Table("dbt_report_month_wise_beneficiery_seeded");
		$select = $select_table->select();
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}
	public function GetDataCountMonth(){
		$select_table = new Zend_Db_Table("dbt_report_month_wise_fund_transfer");
		$select = $select_table->select();
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}
    public function GetBeneficiaries($tablename = null, $scheId = null, $soId = null, $month = null, $year = null, $start, $limit){
        $select_table = new Zend_Db_Table($tablename);
        $select = $select_table->select();
        $select->from(array("tbname"=>$tablename),array('state_name','district_name','amount','fund_transfer','transaction_date'));
        if($month && $month != 0){
            $select->where('tbname.month = ?', $month);
        }    
        $select->order("tbname.state_name");
        $select->order("tbname.district_name");
		//echo $select;exit;
        $select->limit($limit,$start);
        $select_org = $select_table->fetchAll($select);
        return $select_org;
    }
    public function countschemedata($tablename = null){
                $month = 0;
                $year = 0; 
                // if(isset($_GET['year '])){
                //     $year = $_GET['year '];
                // }
                if(isset($_GET['month'])){
                    $month = $_GET['month'];
                }                
                
                $select_table = new Zend_Db_Table($tablename);
                $select = $select_table->select();
                $select->from(array("sch_tb" => $tablename),array("count(id) as counted"));
                if($month && $month != 0){
                    $select->where('month = ?', $month);
                }               
                // if($year && $year != 0){
                //     $select->where('year = ?', $year);
                // }
                //echo "$select";exit;
                $select_org = $select_table->fetchRow($select);
                $select_org1 = $select_org->toArray();
                  //return count($select_org1['counted']);
                  return $select_org1['counted'];  
            }
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
	//@Params : start and Limit use for the validation and Scheme Id for searching data and $uuid and id use for the searching behalf of user
	//@Output : transaction and beneficiaries details
	public function beneficiarydatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		//echo $_GET['benef_id'];exit;
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.transaction_status','tr.pfms_transaction_remark_code'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.payment_mode_by = ?', 0);
		// $select->where('tr.transaction_status = ?', 1);
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countbeneficiarydata($scheme_id){
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;

		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		$select_table = new Zend_Db_Table($tabname);
		$select_query = $select_table->select();
		$select_query->setIntegrityCheck(false);
		$select_query->from(array('beneficiary' => $tabname),array("*"));
		//echo $select_query; die;
		$select_query->join(array('tr'=>$transaction),"tr.uniq_user_id=beneficiary.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select_query->where('beneficiary.scheme_id=?',$scheme_id);
		$select_query->where('tr.payment_mode_by=?',0);
		//$select_query->where('tr.transaction_status=?',1);
		if($user_role==12){
			$select_query->where('beneficiary.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select_query->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select_query->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select_query->where('tr.transaction_date >= ?', $to);
			$select_query->where('tr.transaction_date <= ?', $from);
		}
		if($uuid !=""){
			$select_query->where('beneficiary.uniq_user_id = ?', $uuid);
		}
		$select_query->order('tr.transaction_date ASC')->limit($limit,$start);
		//echo $select_query; die;
		$rowlist = $select_table->fetchAll($select_query);
        return count($rowlist); 				
	}
	
// get all pending transaction records details

public function beneficiarypendingdatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		//echo $_GET['benef_id'];exit;
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','remarked'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.transaction_status = ?', 0);
		$select->where('tr.payment_mode_by = ?', 0);
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countbeneficiarypendingdata($scheme_id){
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;

		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		$select_table = new Zend_Db_Table($tabname);
		$select_query = $select_table->select();
		$select_query->setIntegrityCheck(false);
		$select_query->from(array('beneficiary' => $tabname),array("*"));
		//echo $select_query; die;
		$select_query->join(array('tr'=>$transaction),"tr.uniq_user_id=beneficiary.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select_query->where('beneficiary.scheme_id=?',$scheme_id);
		$select_query->where('tr.transaction_status=?',0);
		$select_query->where('tr.payment_mode_by=?',0);
		if($user_role==12){
			$select_query->where('beneficiary.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select_query->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select_query->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select_query->where('tr.transaction_date >= ?', $to);
			$select_query->where('tr.transaction_date <= ?', $from);
		}
		if($uuid !=""){
			$select_query->where('beneficiary.uniq_user_id = ?', $uuid);
		}
		$select_query->order('tr.transaction_date ASC')->limit($limit,$start);
		//echo $select_query; die;
		$rowlist = $select_table->fetchAll($select_query);
        return count($rowlist); 				
	}
 	


	//get the single beneficiaries full record
	//@params : scheme id, user id, and the uniquely generated id
	//Output : Record of the user from import table and transaction tabele
	public function beneficiarydatalistfullview($scheme_id = null, $id = null, $uuid = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		//echo $transaction;exit;
		//$this->pr_man($tabname,1);
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('*'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme.scheme_name'));
		$select->join(array('min'=>'dbt_ministry'),"min.id=scheme.ministry_id",array('ministry_name'));
		$select->joinLeft(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.transfer_by','tr.pfms_transaction_remark_code','tr.transaction_status','tr.approval_transaction_date'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('tbname.uniq_user_id = ?', $uuid);
		$select->where('scheme.id = ?', $scheme_id);
		$select->order('tr.transaction_date');
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	
	//below function is use for the exporting csv file according to the record filtering and all
	//@params : Scheme Id, Ministry Id, Month for the monthwise Year for Year wise and to - from for the in between search
	//For export all beneficiaries record
	
	public function csvexportmethoddb($schemeid = null,$minid = null,$month = null,$year = null,$to = null,$from = null){
		$state_code = new Zend_Session_Namespace('state_code');
		$role = new Zend_Session_Namespace('role');
		$user_role = $role->role;
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}if(($_GET['tr_status'] != "") && ($_GET['tr_status'] != "")){
			$tr_status = safexss(base64_decode($_GET['tr_status']));
		}else{
			$tr_status = null;
		}
		$scheme_id = base64_decode($schemeid);
		$min_id = base64_decode($minid);
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("name","dob","gender","aadhar_num","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id"));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array(''));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','IF(tr.id!="",1,0) AS NoOfTransaction','IF(tr.fund_transfer="APB" OR tr.fund_transfer="apb",1,0) AS AadharSeededTransaction','tr.transfer_by','tbname.aadhar_seeded','tbname.bank_account','tbname.ifsc'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		
		
			
		if($tr_status==2){
		$select->where('tr.transaction_status= 0 or tr.transaction_status= 2');
		$select->where('tr.payment_mode_by=1');
			}elseif($tr_status==1){
			$select->where('tr.payment_mode_by= ?', 1);
			$select->where('tr.transaction_status= ?', 1);
				}else{
					$select->where('tr.payment_mode_by= ?', 0);
					//$select->where('tr.transaction_status= ?', 1);
					}
		if($user_role==12){
			$select->where('tbname.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		$select->order('tr.transaction_date ASC');
		//$select->order('month ASC');
		//$select->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//csv export for bank validate transaction
	public function csvexportmethodforbank($schemeid = null,$minid = null,$month = null,$year = null,$to = null,$from = null){
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		$scheme_id = base64_decode($schemeid);
		$min_id = base64_decode($minid);
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("name","dob","gender","aadhar_num","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id"));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array(''));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','IF(tr.id!="",1,0) AS NoOfTransaction','IF(tr.fund_transfer="APB" OR tr.fund_transfer="apb",1,0) AS AadharSeededTransaction','tr.transfer_by','tbname.aadhar_seeded','tbname.bank_account','tbname.ifsc','tr.transaction_status','tr.uniq_user_id','tr.txn_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.transaction_status = ?', 0);
		$select->where('tr.payment_mode_by = ?', 0);
		
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		$select->order('tr.transaction_date ASC');
		//$select->order('month ASC');
		//$select->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		// print '<pre>';
		// print_r($select_org);
		// exit;
		$i = 0;
		$resultdata = array();
		foreach($select_org as $key=>$val){
			$resultdata[$i] = array(
				'uniq_user_id' => $val['uniq_user_id'],
				'name' => $val['name'],
				'dob' => $val['dob'],
				'gender' => $val['gender'],
				'aadhar_num' => $val['aadhar_num'],
				'aadhar_seeded' => $val['aadhar_seeded'],
				'mobile_num' => $val['mobile_num'],
				'email_id' => $val['email_id'],
				'scheme_specific_unique_num' => $val['scheme_specific_unique_num'],
				'scheme_specific_family_num' => $val['scheme_specific_family_num'],
				'home_address' => $val['home_address'],
				'village_code' => $val['village_code'],
				'village_name' => $val['village_name'],
				'panchayat_code' => $val['panchayat_code'],
				'panchayat_name' => $val['panchayat_name'],
				'block_code' => $val['block_code'],
				'block_name' => $val['block_name'],
				'district_code' => $val['district_code'],
				'district_name' => $val['district_name'],
				'state_code' => $val['state_code'],
				'state_name' => $val['state_name'],
				'pincode' => $val['pincode'],
				'ration_card_num' => $val['ration_card_num'],
				'tin_family_id' => $val['tin_family_id'],
				'bank_account' => $val['bank_account'],
				'ifsc' => $val['ifsc'],
				'amount' => $val['amount'],
				'fund_transfer' => $val['fund_transfer'],
				'transaction_date' => $val['transaction_date'],
				'transfer_by' => $val['transfer_by'],
				'transaction_status' => $val['transaction_status'],
				'txn_id' => $val['txn_id']
			);
			$i++;
		}
		// print '<pre>';
		// print_r($resultdata);
		// exit;
		
		//$this->pr_man($select_org,1);
		return $resultdata;
	} //csvexportmethodforbank
	
	//getting single user details
	public function singleuserdb($schemeid = null,$id = null,$uuid = null){
		$scheme_id = base64_decode($schemeid);
		$id = base64_decode($id);
		$uuid = base64_decode($uuid);
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("name","dob","gender","aadhar_num","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id"));
		$select->joinLeft(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','IF(tr.id!="",1,0) AS NoOfTransaction','IF(tr.fund_transfer="APB" OR tr.fund_transfer="apb",1,0) AS AadharSeededTransaction','tr.transfer_by','tbname.aadhar_seeded','tbname.bank_account','tbname.ifsc'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('tbname.uniq_user_id = ?', $uuid);
		//$select->where('tbname.id = ?', $id);
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
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

// pfms beneficiary show list start now 

	public function pfmsbeneficiaries($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
			$name = str_replace("'","''",safexss($_GET['name']));
		}else{
			$name = "";
		}
		if(isset($_GET['benef_id']) && $_GET['benef_id'] !=""){
			$benef_id = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$benef_id = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.uidai_aadhar_validate','tbname.aadhar_validate','tbname.error_remark','tbname.district_code','tbname.block_code','tbname.village_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where("tbname.uidai_aadhar_validate =2 OR tbname.uidai_aadhar_validate =0 OR tbname.uidai_aadhar_validate =1");
		$select->where("tbname.aadhar_validate =2 OR tbname.aadhar_validate =0 OR tbname.aadhar_validate =1");
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		//$select->where('tbname.pfms_xml_status = ?', 0);
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
		
		$select->order('tbname.created DESC')->limit($limit,$start);
		 //echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}

// for pagination pfms beneficiary count start now -----------------

public function countpfmsbeneficiaries($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$state_code = new Zend_Session_Namespace('state_code');
		$role = new Zend_Session_Namespace('role');
		$user_role = $role->role;
		//echo $user_role;die;
		$tabname = $this->getTable($scheme_id);	
		if(isset($_GET['dob']) && $_GET['dob'] !=""){
			$dob = date("Y-m-d",strtotime($_GET['dob']));
		}else{
			$dob = "";
		}
		if(isset($_GET['name']) && $_GET['name'] !=""){
			$name = str_replace("'","''",safexss($_GET['name']));
		}else{
			$name = "";
		}
		if(isset($_GET['benef_id']) && $_GET['benef_id'] !=""){
			$benef_id = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$benef_id = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select_query =  $select_table->select();
		$select_query->from(array('beneficiary' => $tabname),array('count(id) as counting'));		
		$select_query->where('scheme_id=?',$scheme_id);
		//$select_query->where('pfms_xml_status = ?', 0);
		$select_query->where('aadhar_num != ""');
		if($uuid != "" && $id != ""){
			$select->where('tbname.id = ?', $id);
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
        if($dob && $dob != 0){
			$select_query->where('beneficiary.dob = ?', $dob);
		}
		if($name!=''){
			$select_query->where("beneficiary.name LIKE '%$name%'");
		}		
		if($benef_id != ''){
			$select_query->where('beneficiary.uniq_user_id = ?', $benef_id);
		}
		if($user_role==12){
			$select_query->where('state_code = ?',trim($state_code->state_code));
		}
		$select_query->where("uidai_aadhar_validate = 2 OR uidai_aadhar_validate = 0 OR uidai_aadhar_validate = 1");
		$select_query->where("aadhar_validate = 2 OR aadhar_validate = 0 OR aadhar_validate = 1");
		// $select_query->where('aadhar_validate=?','2');
		// $select_query->ORwhere('aadhar_validate=?','0');
		$rowlist = $select_table->fetchAll($select_query)->toArray();
		//echo $select_query; die;
		if(empty($rowlist)){
			return 0;
		}else{
			return $rowlist[0]['counting'];
		}
	}

public function insertbeneficiaryrecord($data = null, $scheme_id = null, $uuid = null, $id = null, $scmtype= null){
		//echo "<pre>";print_r($data);echo "</pre>";
//echo $scheme_id;exit;
		$tbname = $this->getTable($scheme_id, "tr");
		$dataform = json_decode($data, true);
		$date=date("Ymdhms");
		$rand=rand(99999999,10000000);
		$txn_id="DBTTXN".$rand;
		$dataupdate = '';
//echo $scmtype;exit;
		if($scmtype == 1 || $scmtype == 3){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'amount' => $dataform['amount'],
				'fund_transfer' => $dataform['fund_transfer'],
				'remarked' => $dataform['remarked'],
				'from_payment_date' => date("Y-m-d",strtotime($dataform['from_payment_date'])),
				'to_payment_date' => date("Y-m-d",strtotime($dataform['to_payment_date'])),
				'purpose' => $dataform['purpose'],
				'transaction_date' => date("Y-m-d",strtotime($dataform['transaction_date'])),
				'created' => date("Y-m-d H:i:s")
			);
		}else if($scmtype == 2){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'transaction_date' => date("Y-m-d",strtotime($dataform['transaction_date'])),
				'transfer_by' => $dataform['transfer_by'],
				'remarked' => $dataform['remarked'],
				'from_payment_date' => date("Y-m-d",strtotime($dataform['from_payment_date'])),
				'to_payment_date' => date("Y-m-d",strtotime($dataform['to_payment_date'])),
				'purpose' => $dataform['purpose'],
				'created' => date("Y-m-d H:i:s")
			);
		}
//echo $scheme_id;exit;
//echo "<pre>";print_r($dataupdate);die;
		$tbobj = new Zend_Db_Table($tbname);
		$data = $tbobj->insert($dataupdate);
		return $data;
	}
	
	//Add payment by bank
	public function insertbeneficiarypaymentrecord($data = null, $scheme_id = null, $uuid = null, $id = null, $scmtype= null){
		//echo "<pre>";print_r($data);echo "</pre>";
//echo $scheme_id;exit;
		$tbname = $this->getTable($scheme_id, "tr");
		$dataform = json_decode($data, true);
		$date=date("Ymdhms");
		$rand=rand(99999999,10000000);
		$txn_id="DBTTXN".$rand;
		$dataupdate = '';
//echo $scmtype;exit;
		if($scmtype == 1 || $scmtype == 3){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'amount' => $dataform['amount'],
				'fund_transfer' => $dataform['fund_transfer'],
				'remarked' => $dataform['remarked'],
				'from_payment_date' => date("Y-m-d",strtotime($dataform['from_payment_date'])),
				'to_payment_date' => date("Y-m-d",strtotime($dataform['to_payment_date'])),
				'purpose' => $dataform['purpose'],
				'transaction_date' => date("Y-m-d",strtotime($dataform['transaction_date'])),
				'payment_mode_by' => 0,
				'created' => date("Y-m-d H:i:s")
			);
		}else if($scmtype == 2){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'transaction_date' => date("Y-m-d",strtotime($dataform['transaction_date'])),
				'transfer_by' => $dataform['transfer_by'],
				'remarked' => $dataform['remarked'],
				'from_payment_date' => date("Y-m-d",strtotime($dataform['from_payment_date'])),
				'to_payment_date' => date("Y-m-d",strtotime($dataform['to_payment_date'])),
				'purpose' => $dataform['purpose'],
				'created' => date("Y-m-d H:i:s")
			);
		}
//echo $scheme_id;exit;
//echo "<pre>";print_r($dataupdate);die;
		$tbobj = new Zend_Db_Table($tbname);
		$data = $tbobj->insert($dataupdate);
		return $data;
	}
	
	//Update Transaction Status By Bank
	public function updatestatusbybankcsvdata($scheme_id = null, $uuid = null, $id = null, $scm_type= null, $staus= null, $txnid= null, $remarks= null){
		$tbname = $this->getTable($scheme_id, "tr");
		$dataupdate = '';
		
		$dataupdate = array(
			'transaction_status' => $staus,
			'pfms_transaction_remark_code' => $remarks,
			'approval_transaction_date' => date('Y-m-d')
		);
		$where = array(						
			'txn_id = ?' => $txnid
		);
		$tbobj = new Zend_Db_Table($tbname);
		$update_values = $tbobj->update($dataupdate, $where);
		return $update_values;
	}
	////////End

public function pfmstransactiondatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
			//print_r($transaction);die;
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		//echo $_GET['benef_id'];exit;
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.pfms_request_id','tr.remarked','tr.pfms_status','tr.txn_id','tr.transaction_status'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.transaction_status = ?', 1);
		$select->where('tr.payment_mode_by = ?', 1);
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countpfmstransactiondatalist($scheme_id){
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;

		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		$select_table = new Zend_Db_Table($tabname);
		$select_query = $select_table->select();
		$select_query->setIntegrityCheck(false);
		$select_query->from(array('beneficiary' => $tabname),array("*"));
		//echo $select_query; die;
		$select_query->join(array('tr'=>$transaction),"tr.uniq_user_id=beneficiary.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select_query->where('beneficiary.scheme_id=?',$scheme_id);
		$select_query->where('tr.transaction_status=?',1);
		$select_query->where('tr.payment_mode_by=?',1);
		if($user_role==12){
			$select_query->where('beneficiary.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select_query->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select_query->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select_query->where('tr.transaction_date >= ?', $to);
			$select_query->where('tr.transaction_date <= ?', $from);
		}
		if($uuid !=""){
			$select_query->where('beneficiary.uniq_user_id = ?', $uuid);
		}
		$select_query->order('tr.transaction_date ASC')->limit($limit,$start);
		//echo $select_query; die;
		$rowlist = $select_table->fetchAll($select_query);
        return count($rowlist); 				
	}
	
// pending transaction history model start now 

public function pfmstransactionpendingdatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
			//print_r($transaction);die;
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		//echo $_GET['benef_id'];exit;
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.pfms_request_id','tr.remarked','tr.pfms_status','tr.txn_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.transaction_status = 0 or tr.transaction_status=2');
		$select->where('tr.payment_mode_by = 1');
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countpfmstransactionpendingdatalist($scheme_id){
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;

		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		$select_table = new Zend_Db_Table($tabname);
		$select_query = $select_table->select();
		$select_query->setIntegrityCheck(false);
		$select_query->from(array('beneficiary' => $tabname),array("*"));
		//echo $select_query; die;
		$select_query->join(array('tr'=>$transaction),"tr.uniq_user_id=beneficiary.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select_query->where('beneficiary.scheme_id=?',$scheme_id);
		$select_query->where('tr.transaction_status=0 or tr.transaction_status=2');
		$select_query->where('tr.payment_mode_by=1');
		
		if($user_role==12){
			$select_query->where('beneficiary.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select_query->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select_query->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select_query->where('tr.transaction_date >= ?', $to);
			$select_query->where('tr.transaction_date <= ?', $from);
		}
		if($uuid !=""){
			$select_query->where('beneficiary.uniq_user_id = ?', $uuid);
		}
		$select_query->order('tr.transaction_date ASC')->limit($limit,$start);
		//echo $select_query; die;
		$rowlist = $select_table->fetchAll($select_query);
        return count($rowlist); 				
	}
	

	//********************** download csv transaction history ****************

public function csvexportmethoddbpfms($schemeid = null,$minid = null,$month = null,$year = null,$to = null,$from = null){
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		$scheme_id = base64_decode($schemeid);
		$min_id = base64_decode($minid);
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("name","dob","gender","aadhar_num","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id"));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array(''));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','IF(tr.id!="",1,0) AS NoOfTransaction','IF(tr.fund_transfer="APB" OR tr.fund_transfer="apb",1,0) AS AadharSeededTransaction','tr.transfer_by','tbname.aadhar_seeded','tbname.bank_account','tbname.ifsc'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		$select->order('tr.transaction_date ASC');
		//$select->order('month ASC');
		//$select->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}

// ******************** add transaction detail model ****************
public function beneficiaryaddtransdatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
			$name = str_replace("'","''",safexss($_GET['name']));
		}else{
			$name = "";
		}
		if(isset($_GET['benef_id']) && $_GET['benef_id'] !=""){
			$benef_id = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$benef_id = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		//$select->where('tbname.aadhar_validate = ?', '1');
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
		$select->order('tbname.created DESC')->limit($limit,$start);
		//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}

public function showpfmstransactionxml($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id, "tr");
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		//echo $_GET['benef_id'];exit;
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme_codification'));
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.pfms_request_id','tr.txn_id','tr.remarked','tr.pfms_status','tr.transfer_by'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tr.pfms_xml_status = ?', 0);
		$select->where('tr.payment_mode_by = ?', 1);
		if($uuid != ""){
			$select->where('tbname.uniq_user_id = ?', $uuid);
		}
		if($user_role == 12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select->where('tr.transaction_date >= ?', $to);
			$select->where('tr.transaction_date <= ?', $from);
		}
		
		$select->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//count the beneficiaries record
	public function countshowpfmstransactionxml($start = null,$limit = null,$scheme_id){
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
//echo $scheme_id;die("sdcfjsd");
		$tabname = $this->getTable($scheme_id);
		$transaction = $this->getTable($scheme_id,"tr");
		
		if((isset($_GET['to'])) && ($_GET['to'] != "") && ($_GET['to'] != 0) && ($_GET['to'] != null)){
			$to = date("Y-m-d",strtotime($_GET['to']));
		}else{
			$to = null;
		}
		if((isset($_GET['from'])) && ($_GET['from'] != "") && ($_GET['from'] != 0) && ($_GET['from'] != null)){
			$from = date("Y-m-d",strtotime($_GET['from']));
		}else{
			$from = null;
		}
		if(($_GET['benef_id'] != "") && ($_GET['benef_id'] != "")){
			$uuid = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$uuid = null;
		}
		//echo $uuid;exit;
		$select_table = new Zend_Db_Table($tabname);
		$select_query = $select_table->select();
		$select_query->setIntegrityCheck(false);
		$select_query->from(array('beneficiary' => $tabname),array("*"));
		//echo $select_query; die;
		$select_query->join(array('tr'=>$transaction),"tr.uniq_user_id=beneficiary.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		$select_query->where('beneficiary.scheme_id=?',$scheme_id);
		$select_query->where('tr.pfms_xml_status = ?', 0);
		$select_query->where('tr.payment_mode_by = ?', 1);
		if($user_role==12){
			$select_query->where('beneficiary.state_code=?',trim($state_code->state_code));
		}
		if($to != null && $from == null){
			$select_query->where('tr.transaction_date = ?', $to);
		}else if($to == null && $from != null){
			$select_query->where('tr.transaction_date = ?', $from);
		}else if($to != null && $from != null){
			$select_query->where('tr.transaction_date >= ?', $to);
			$select_query->where('tr.transaction_date <= ?', $from);
		}
		if($uuid !=""){
			$select_query->where('beneficiary.uniq_user_id = ?', $uuid);
		}
		$select_query->order('tr.transaction_date DESC')->limit($limit,$start);
		//echo $select_query; die;
		$rowlist = $select_table->fetchAll($select_query);
        return count($rowlist); 				
	}
// export beneficiaries for transaction 
public function csvexportforbeneficiarytransaction($schemeid = null,$minid = null,$month = null,$year = null,$exppageno=null){
		
		if(isset($_GET['dob']) && $_GET['dob'] !=""){
			$dob = date("Y-m-d",strtotime($_GET['dob']));
		}else{
			$dob = "";
		}
		if(isset($_GET['name']) && $_GET['name'] !=""){
			$name = str_replace("'","''",safexss($_GET['name']));
		}else{
			$name = "";
		}
		if(isset($_GET['benef_id']) && $_GET['benef_id'] !=""){
			$benef_id = str_replace("'","''",safexss($_GET['benef_id']));
		}else{
			$benef_id = "";
		}
		$role = new Zend_Session_Namespace("role");
		$state_code = new Zend_Session_Namespace("state_code");
		$scheme_id = base64_decode($schemeid);
		$min_id = base64_decode($minid);
		$tabname = $this->getTable($scheme_id);
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("uniq_user_id","name","dob","gender","aadhar_num","aadhar_seeded","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id","bank_account","ifsc","amount","fund_transfer","transaction_date"));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array(''));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		
		if($dob && $dob != 0){
			$select->where('tbname.dob = ?', $dob);
		}
		if($name != ""){
			$select->where("tbname.name LIKE '%$name%'");
		}		
		if($benef_id != ''){
			$select->where('tbname.uniq_user_id = ?', $benef_id);
		}
		if($role->role==12){
			$select->where('tbname.state_code = ?', trim($state_code->state_code));
		}
		if($exppageno==1){
			$select->where('tbname.aadhar_validate = 1 or tbname.aadhar_validate = 2  or tbname.aadhar_validate = 0');
			$select->where('tbname.uidai_aadhar_validate = 1 or tbname.uidai_aadhar_validate = 2 or tbname.uidai_aadhar_validate = 1');
		}
		
		$select->order('tbname.year DESC');
		$select->order('month ASC');
		//$select->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	
}
?>