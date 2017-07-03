<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Schemeimport extends Zend_Db_Table_Abstract {
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
	
	//************************ random number generate code start now**************************



function uniquecodegenerator($ifsc = null, $aadhar_num = null,$mobile = null,$bank_num = null) {
		$length = 6;
    $characters = $aadhar_num.$mobile.$bank_num;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
//echo substr($ifsc,0,4).$randomString;die;
    return substr($ifsc,0,4).$randomString;
}


//*************************** random number generate code start now *****************************






	//this function is use for the generate the unique code of the user specific
	public function uniquecodegenerator1($ifsc = null, $aadhar_num = null,$mobile = null,$bank_num = null){
		
		//$rando = rand().uniqid().rand().uniqid();
		//$dateday = date("d", strtotime(date("Y-m-d"));
		$idmix = rand(substr($ifsc,0,4)).substr(rand(rand(substr($aadhar_num,0,6)).rand(substr($mobile, 0,6)).rand(substr($bank_num, 0,6))),0,6);
echo $idmix;die;
		//$dataid = strtoupper($idmix.$rando);
		//$dataun = substr($dataid,1,4).substr($dataid,2,5);
		return $idmix;
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
	
	public function dataInsertForSchemeImport($data = null,$tablename = null,$schemeId = null){
		$user_unique_id = $this->uniquecodegenerator($data['ifsc'], $data['aadhar_num'],$data['mobile_num'],$data['bank_account']);
		//print_r($data);die;
		//$state_name = $this->sms_state($data['state_name']);
		//$district_name = $this->sms_district($data['district_name']);
		//$block_name = $this->sms_block($data['block_name']);
		//$village_name = $this->sms_village($data['village_name']);
		
		//echo $state_name." -- ".$district_name." -- ".$block_name." -- ".$village_name;exit;
		
		$datetm = date("Y-m-d H:i:s");
		//$this->sms_state($data['state_name']);
		$tableObj = new Zend_Db_Table($tablename);
		$datainsert="";
        $datainsert = array(
			'uniq_user_id' => $user_unique_id,
            'name'=> $data['name'],
            'dob'=> date("Y-m-d",strtotime($data['dob'])),
            'gender'=> $data['gender'],
            'aadhar_num'=> $data['aadhar_num'],
            'mobile_num'=> $data['mobile_num'],
			'bank_account' => $data['bank_account'],
			'ifsc' => $data['ifsc'],
			'aadhar_seeded'=> $data['aadhar_seeded'],
            'email_id'=> $data['email_id'],
            'scheme_specific_unique_num'=> $data['scheme_specific_unique_num'],
            'scheme_specific_family_num'=> $data['scheme_specific_family_num'],
            'home_address'=> $data['home_address'],
            'village_code'=> $data['village_code'],
            'village_name'=> $data['village_name'],
           // 'panchayat_code'=> $data['panchayat_code'],
            //'panchayat_name'=> "",
            'block_code'=> $data['block_code'],
            'block_name'=> $data['block_name'],
            'district_code'=> $data['district_code'],
            'district_name'=> $data['district_name'],
            'state_code'=> $data['state_code'],
            'state_name'=> $data['state_name'],
            'pincode'=> $data['pincode'],
            'ration_card_num'=> $data['ration_card_num'],
            'tin_family_id'=> $data['tin_family_id'],
            'scheme_id'=> $schemeId,
		    'status'=> 1,
			'purp_cd'=> safexss('A'),
			'created' => $datetm,
			'beneficiary_title' => $data['beneficiary_title'],
			'bank_name' =>$data['bank_name'],
			'beneficiary_regional_lang' => $data['beneficiary_regional_lang'],
			'beneficiary_type' => $data['beneficiary_type']
			
        );
			//echo "<pre>";print_r($datainsert);die;
			//$this->pr_man($datainsert,1);
			$dataret = $tableObj->insert($datainsert);
			return $dataret;
	}
	public function dataInsertForScheme($data = null,$tablename = null,$schemeId = null){
			//echo "<pre>";print_r($data);die;
		$user_unique_id = $this->uniquecodegenerator($data['ifsc'], $data['aadhar_num'],$data['mobile_num'],$data['bank_account']);
	//echo $user_unique_id;echo "<pre>";print_r($data);die;
		//print_r($data);
		$state_name = $this->sms_state($data['state_name']);
		$district_name = $this->sms_district($data['district_name']);
		$block_name = $this->sms_block($data['block_name']);
		$village_name = $this->sms_village($data['village_name']);
		
		//echo $state_name." -- ".$district_name." -- ".$block_name." -- ".$village_name;exit;
		
		$datetm = date("Y-m-d H:i:s");
		//$this->sms_state($data['state_name']);
		$tableObj = new Zend_Db_Table($tablename);
		$datainsert="";
        $datainsert = array(
			'uniq_user_id' => safexss($user_unique_id),
            'name'=> safexss($data['name']),
            'dob'=> date("Y-m-d",strtotime($data['dob'])),
            'gender'=> safexss($data['gender']),
            'aadhar_num'=> safexss($data['aadhar_num']),
            'mobile_num'=> safexss($data['mobile_num']),
			'bank_account' => safexss($data['bank_account']),
			'ifsc' => safexss($data['ifsc']),
			'aadhar_seeded'=> safexss($data['aadhar_seeded']),
            'email_id'=> ($data['email_id']),
            'scheme_specific_unique_num'=> safexss($data['scheme_specific_unique_num']),
            'scheme_specific_family_num'=> safexss($data['scheme_specific_family_num']),
            'home_address'=> safexss($data['home_address']),
            'village_code'=> safexss($data['village_name']),
            'village_name'=> safexss($village_name),
           // 'panchayat_code'=> $data['panchayat_code'],
            //'panchayat_name'=> "",
            'block_code'=> safexss($data['block_name']),
            'block_name'=> safexss($block_name),
            'district_code'=> safexss($data['district_name']),
            'district_name'=> safexss($district_name),
            'state_code'=> safexss($data['state_name']),
            'state_name'=> safexss($state_name),
            'pincode'=> safexss($data['pincode']),
            'ration_card_num'=> safexss($data['ration_card_num']),
            'tin_family_id'=> safexss($data['tin_family_id']),
            'scheme_id'=> safexss($schemeId),
            'beneficiary_title'=> safexss($data['beneficiary_title']),
            'beneficiary_regional_lang'=> safexss($data['beneficiary_regional_lang']),
            'purp_cd'=> safexss('A'),
			'bank_name'=>safexss($data['bank_name']),
			'beneficiary_type'=>safexss($data['beneficiary_type']),
		    'status'=> 1,
			'created' => $datetm
        );
			//$this->pr_man($datainsert,1);
			$dataret = $tableObj->insert($datainsert);
			return $dataret;
	}
	
	

	
	public function insertbeneficiaryrecord($data = null, $scheme_id = null, $uuid = null, $id = null, $scmtype= null){
		//echo "<pre>";print_r($data);echo $scheme_id.' '.$uuid.' '.$scmtype;
		$tbname = $this->getTable($scheme_id, "tr");
		$dataform = json_decode($data, true);
		//$date=date("Ymdhms");
		$rand=rand(99999999,10000000);
		$txn_id="DBTTXN".$rand;
		$dataupdate = '';
		if($scmtype == 1 || $scmtype == 3){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'amount' => $dataform['amount'],
				'fund_transfer' => $dataform['fund_transfer'],
				'remarked' => $dataform['remarked'],
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
				'created' => date("Y-m-d H:i:s")
			);
		}
//print_r($dataupdate);die;
		$tbobj = new Zend_Db_Table($tbname);
		$data = $tbobj->insert($dataupdate);
		return $data;
	}
	
// add transaction for old method model function start now

public function insertoldmethodtransaction($data = null, $scheme_id = null, $uuid = null, $id = null, $scmtype= null){
		//echo "<pre>";print_r($data);echo $scheme_id.' '.$uuid.' '.$scmtype;die;
		$tbname = $this->getTable($scheme_id, "tr");
		//echo $tbname;die;
		$dataform = json_decode($data, true);
		//$date=date("Ymdhms");
		$rand=rand(99999999,10000000);
		$txn_id="DBTTXN".$rand;
		$dataupdate = '';
		if($scmtype == 1 || $scmtype == 3){
			$dataupdate = array(
				//'user_id' => $id,
				'uniq_user_id' => $uuid,
				'txn_id'=>$txn_id,
				'amount' => $dataform['amount'],
				'fund_transfer' => $dataform['fund_transfer'],
				//'remarked' => $dataform['remarked'],
				'payment_mode_by' => 0,
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
				'payment_mode_by' => 0,
				'transaction_status' => 1,
				//'remarked' => $dataform['remarked'],
				'created' => date("Y-m-d H:i:s")
			);
		}
//print_r($dataupdate);die;
		$tbobj = new Zend_Db_Table($tbname);
		$data = $tbobj->insert($dataupdate);
		return $data;
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
	/*
	public function GetDataCount(){
		$select_table = new Zend_Db_Table("dbt_home_page_master_data_current_year");
		$select = $select_table->select();
        $row = $select_table->fetchAll($select);
        //echo "<pre>";print_r($row->toArray());exit;
            return $row->toArray(); 
	}*/
	
	/*
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
	}*/
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
				//echo $scmid;die;
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
		if(isset($_GET['filterstatus']) && $_GET['filterstatus'] !=""){
			$filterstatus = base64_decode(safexss($_GET['filterstatus']));
		}else{
			$filterstatus = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		
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
		if($filterstatus != '' && $filterstatus=='2'){
			$select->where('tbname.aadhar_validate = ?','1');
		}elseif($filterstatus != '' && $filterstatus=='3'){
		$select->where('tbname.uidai_aadhar_validate = ?', '1');
		}else{
			$select->where('tbname.aadhar_validate = ?', '1');
			$select->where('tbname.uidai_aadhar_validate = ?', '1');
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
	
	//count the beneficiaries record
	public function countbeneficiarydata($scheme_id){
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
		if(isset($_GET['filterstatus']) && $_GET['filterstatus'] !=""){
			$filterstatus = base64_decode(safexss($_GET['filterstatus']));
		}else{
			$filterstatus = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select_query =  $select_table->select();
		$select_query->from(array('beneficiary' => $tabname),array('count(id) as counting'));		
		$select_query->where('scheme_id=?',$scheme_id);	
		
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
		if($filterstatus != '' && $filterstatus=='2'){
			$select_query->where('aadhar_validate = ?','1');
		}elseif($filterstatus != '' && $filterstatus=='3'){
		$select_query->where('uidai_aadhar_validate = ?', '1');
		}else{
			$select_query->where('aadhar_validate = ?', '1');
			$select_query->where('uidai_aadhar_validate = ?', '1');
			}
		
		$rowlist = $select_table->fetchAll($select_query)->toArray();
		//echo $select_query; die;
		if(empty($rowlist)){
			return 0;
		}else{
			return $rowlist[0]['counting'];
		}
	}
// get all beneficiaries detail start now
public function allbeneficiarydatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		if(isset($_GET['filterstatus']) && $_GET['filterstatus'] !=""){
			$filterstatus = base64_decode(safexss($_GET['filterstatus']));
		}else{
			$filterstatus = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		
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
		if($filterstatus != '' && $filterstatus=='2'){
			$select->where('tbname.aadhar_validate = ?','1');
		}elseif($filterstatus != '' && $filterstatus=='3'){
		$select->where('tbname.uidai_aadhar_validate = ?', '1');
		 }
		elseif($filterstatus != '' && $filterstatus=='1'){
			 $select->where('tbname.aadhar_validate = ?', '1');
			 $select->where('tbname.uidai_aadhar_validate = ?', '1');
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
	
	//count the beneficiaries record
	public function countallbeneficiarydata($scheme_id){
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
		if(isset($_GET['filterstatus']) && $_GET['filterstatus'] !=""){
			$filterstatus = base64_decode(safexss($_GET['filterstatus']));
		}else{
			$filterstatus = "";
		}
		$select_table = new Zend_Db_Table($tabname);
		$select_query =  $select_table->select();
		$select_query->from(array('beneficiary' => $tabname),array('count(id) as counting'));		
		$select_query->where('scheme_id=?',$scheme_id);	
		
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
		if($filterstatus != '' && $filterstatus=='2'){
			$select_query->where('aadhar_validate = ?','1');
		}elseif($filterstatus != '' && $filterstatus=='3'){
		$select_query->where('uidai_aadhar_validate = ?', '1');
		}
			elseif($filterstatus != '' && $filterstatus=='1'){
			 $select_query->where('aadhar_validate = ?', '1');
			 $select_query->where('uidai_aadhar_validate = ?', '1');
			}
		
		$rowlist = $select_table->fetchAll($select_query)->toArray();
		//echo $select_query; die;
		if(empty($rowlist)){
			return 0;
		}else{
			return $rowlist[0]['counting'];
		}
	}

// get all beneficiaries detail end now

	//get the single beneficiaries full record
	//@params : scheme id, user id, and the uniquely generated id
	//Output : Record of the user from import table and transaction tabele
	public function beneficiarydatalistfullview($scheme_id = null, $id = null, $uuid = null){
		$tabname = $this->getTable($scheme_id);
		//$transaction = $this->getTable($scheme_id, "tr");
		//echo $transaction;exit;
		//$this->pr_man($tabname,1);
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('*'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id','scheme.scheme_name'));
		$select->join(array('min'=>'dbt_ministry'),"min.id=scheme.ministry_id",array('ministry_name'));
		/*$select->joinLeft(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id AND tr.user_id=tbname.id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));
		*/
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('tbname.uniq_user_id = ?', $uuid);
		$select->where('tbname.id = ?', $id);
		$select->where('scheme.id = ?', $scheme_id);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	


	
	//below function is use for the exporting csv file according to the record filtering and all
	public function csvexportmethoddb($schemeid = null,$minid = null,$month = null,$year = null,$exppageno=null){
		
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
		}if(isset($_GET['filterstatus']) && $_GET['filterstatus'] !=""){
				$filterstatus = safexss(base64_decode($_GET['filterstatus']));
				}else{
				$filterstatus='';
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
		}if($filterstatus==1){
			$select->where('tbname.aadhar_validate = ?',1);
			$select->where('tbname.uidai_aadhar_validate = ?',1);
		}if($filterstatus==2){
				$select->where('tbname.aadhar_validate = ?',1);
		}if($filterstatus==3){
				$select->where('tbname.uidai_aadhar_validate = ?',1);
		}
		if($exppageno==1){
			$select->where('tbname.aadhar_validate = ?',1);
			$select->where('tbname.uidai_aadhar_validate = ?',1);
		}
		if($exppageno==2){
			$select->where('tbname.aadhar_validate = 0 or tbname.aadhar_validate=2');
		}if($exppageno==3){
		$select->where('tbname.uidai_aadhar_validate = 0 or tbname.uidai_aadhar_validate=2');
		}
		$select->order('tbname.year DESC');
		$select->order('month ASC');
		//$select->limit($limit,$start);
		//echo $select;exit;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
	
	//getting single user details
	public function singleuserdb($schemeid = null,$id = null,$uuid = null){
		$scheme_id = base64_decode($schemeid);
		$id = base64_decode($id);
		$uuid = base64_decode($uuid);
		$tabname = $this->getTable($scheme_id);
		//$transaction = $this->getTable($scheme_id,"tr");
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array("uniq_user_id","beneficiary_title","name","dob","gender","aadhar_num","aadhar_seeded","mobile_num","email_id","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id","bank_account","ifsc","bank_name","amount","fund_transfer","transaction_date","beneficiary_regional_lang","beneficiary_type"));
		
		/*$select->joinLeft(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id AND tr.user_id=tbname.id",array('tr.amount','tr.fund_transfer','tr.transaction_date'));*/
		
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('tbname.uniq_user_id = ?', $uuid);
		$select->where('tbname.id = ?', $id);
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

//below function is using for the get scheme code existance in table	
	public function checkSchemecode($scheme_codification = null){
		
		$selecttb = new Zend_Db_Table('dbt_scheme');
		$select = $selecttb->select();
		$select->from(array("tbnm"=>'dbt_scheme'),array("tbnm.scheme_codification"));
		$select->where("tbnm.scheme_codification = ?",$scheme_codification);
		//return $select;
		$data = $selecttb->fetchAll($select)->toArray();
		return $data;
	}
//below function is using for the get scheme code existance in table end	

//below function is using for the get scheme name existance in table	
	public function checkSchemename($scheme_name = null){
		
		$selecttb = new Zend_Db_Table('dbt_scheme');
		$select = $selecttb->select();
		$select->from(array("tbnm"=>'dbt_scheme'),array("tbnm.scheme_name"));
		$select->where("tbnm.scheme_name = ?",$scheme_name);
		//return $select;
		$data = $selecttb->fetchAll($select)->toArray();
		return $data;
	}
//below function is using for the get scheme name existance in table end	'


// for validate aaddhaar number model start now --------------

public function aadharvalidate($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate','tbname.uidai_aadhar_validate'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_validate = 0 or tbname.uidai_aadhar_validate=0');
		$select->where('tbname.aadhar_num != ""');
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
// for validate aaddhaar number model e now --------------

// for pagination countaadharvalidate start now -----------------

public function countaadharvalidate($scheme_id){
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
		$select_query->where('aadhar_validate = 0 or uidai_aadhar_validate=0');
		$select_query->where('aadhar_num != ""');
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
		
		$rowlist = $select_table->fetchAll($select_query)->toArray();
		//echo $select_query; die;
		if(empty($rowlist)){
			return 0;
		}else{
			return $rowlist[0]['counting'];
		}
	}

// aaddhaar seeded with number start now

	public function beneficiaryaadharseededdatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->where('tbname.aadhar_validate = ?', '1');
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

	public function countbeneficiaryaadharseededdata($scheme_id){
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
		$select_query->where('aadhar_validate=?','1');	
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
		
		$rowlist = $select_table->fetchAll($select_query)->toArray();
		//echo $select_query; die;
		if(empty($rowlist)){
			return 0;
		}else{
			return $rowlist[0]['counting'];
		}
	}

// aaddhaar seeded with number end now

// no aaddhaar seeded with number start now

	public function noaadharseededvalidate($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.aadhar_validate','tbname.error_remark','tbname.district_code','tbname.block_code','tbname.village_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where("tbname.aadhar_validate = 2 OR tbname.aadhar_validate = 0");
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		
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
		// echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
// for validate aaddhaar number model e now --------------

// for pagination countaadharvalidate start now -----------------

public function countnoaadharseededvalidate($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select_query->where("aadhar_validate = 2 OR aadhar_validate = 0");
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


// no aaddhaar seeded with number end now


// no aaddhaar seeded with number start now

	public function noaadharuidaivalidate($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','name','dob','email_id','state_name','aadhar_seeded','aadhar_num','tbname.amount','tbname.transaction_date','tbname.fund_transfer','tbname.state_code','tbname.uidai_aadhar_validate','tbname.uidai_error_remark','tbname.district_code','tbname.block_code','tbname.village_code'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where("tbname.uidai_aadhar_validate = 2 OR tbname.uidai_aadhar_validate = 0");
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		
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
		// echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}

// for pagination countaadharvalidate start now -----------------

public function countnoaadharuidaivalidate($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select_query->where("uidai_aadhar_validate = 2 OR uidai_aadhar_validate = 0");
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



// pfms beneficiary show list start now 

	public function pfmsdatabeneficiaries($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->where('scheme.id = ?', $scheme_id);
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.pfms_xml_status = ?', 0);
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

public function countpfmsdatabeneficiaries($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select_query->where('pfms_xml_status = ?', 0);
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



// edit for no aaddhaar seeded with number start now

	public function editnonseededbeneficiary($scheme_id = null,$id = null){
		$tabname = $this->getTable($scheme_id);
		//$this->pr_man($tabname,1);
		$role = new Zend_Session_Namespace('role');
		$state_code = new Zend_Session_Namespace('state_code');
		$userid = new Zend_Session_Namespace('userid');
		$user_role = $role->role;
		
		$select_table = new Zend_Db_Table($tabname);
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('tbname' => $tabname),array('id','uniq_user_id','beneficiary_title','name','dob','email_id','state_code','aadhar_seeded','aadhar_num','gender','mobile_num','home_address','village_code','panchayat_name','block_code','district_code','pincode','ration_card_num','bank_account','ifsc','scheme_specific_family_num','scheme_specific_unique_num','tin_family_id','beneficiary_regional_lang','bank_name','beneficiary_type'));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array('scheme_type','id as scheme_id','ministry_id as min_id'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
		//$select->where('tbname.aadhar_validate = ?', '2');
		//$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.id = ?', $id);
		
		//echo $select;die;
		$select_org = $select_table->fetchAll($select)->toArray();
		//$this->pr_man($select_org,1);
		return $select_org;
	}
// edit for validate aaddhaar number model end now --------------

// upadate beneficiary *********************
public function updatebeneficiaryrecord($data = null,$schemeId = null,$beficiary_id=null){
			//echo "<pre>";print_r($data);die;
		$tabname = $this->getTable($schemeId);
		$state_name = $this->sms_state($data['state_name']);
		$district_name = $this->sms_district($data['district_name']);
		$block_name = $this->sms_block($data['block_name']);
		$village_name = $this->sms_village($data['village_name']);
		
		//echo $state_name." -- ".$district_name." -- ".$block_name." -- ".$village_name;exit;
		//print_r($tabname);die;
		$datetm = date("Y-m-d H:i:s");

		$tableObj = new Zend_Db_Table($tabname);
		$dataupdate="";
        $dataupdate = array(
			
            'beneficiary_title'=> safexss($data['beneficiary_title']),
            'name'=> safexss($data['name']),
            'dob'=> safexss(date("Y-m-d",strtotime($data['dob']))),
            'gender'=> safexss($data['gender']),
            'aadhar_num'=> safexss($data['aadhar_num']),
            'mobile_num'=> safexss($data['mobile_num']),
			'bank_account' => safexss($data['bank_account']),
			'ifsc' => safexss($data['ifsc']),
			
            'email_id'=> safexss($data['email_id']),
            'scheme_specific_unique_num'=> safexss($data['scheme_specific_unique_num']),
            'scheme_specific_family_num'=> safexss($data['scheme_specific_family_num']),
            'home_address'=> safexss($data['home_address']),
            'village_code'=> safexss($data['village_name']),
            'village_name'=> safexss($village_name),
           // 'panchayat_code'=> $data['panchayat_code'],
            //'panchayat_name'=> "",
            'block_code'=> safexss($data['block_name']),
            'block_name'=> safexss($block_name),
            'district_code'=> safexss($data['district_name']),
            'district_name'=> $district_name,
            'state_code'=> safexss($data['state_name']),
            'state_name'=> safexss($state_name),
            'pincode'=> safexss($data['pincode']),
            'ration_card_num'=> safexss($data['ration_card_num']),
            'tin_family_id'=> safexss($data['tin_family_id']),
            'beneficiary_regional_lang'=> safexss($data['beneficiary_regional_lang']),
            'bank_name'=> safexss($data['bank_name']),
            'beneficiary_type'=> safexss($data['beneficiary_type']),
            'scheme_id'=> safexss($schemeId),
		    'status'=> 1,
			'updated' => safexss($datetm)
        );
			$where = array(
						
						'id = ?'      => $beficiary_id
								  );
		//echo "<pre>";print_r($where);die;
		$update_values = $tableObj->update($dataupdate, $where);
			return $update_values;
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

//****************** get aadhar detail current user **********************



	public function getcurrentAadhaar($aaddhaar = null, $scheme_id = null,$beneficiary_id=null){
		$tbname = $this->getTable($scheme_id);
		$selecttb = new Zend_Db_Table($tbname);
		$select = $selecttb->select();
		$select->from(array("tbnm"=>$tbname),array("tbnm.aadhar_num"));
		$select->where("tbnm.aadhar_num = ?",$aaddhaar);
		$select->where("tbnm.id not IN(?)",$beneficiary_id);
		//return $select;die;
		$data = $selecttb->fetchAll($select)->toArray();
		return $data;
	}
// get current beneficiary district id 

public function currentdistrictid($statecode = null){
                $newtb = new Zend_Db_Table("dbt_district");
                $select = $newtb->select();
                $select->from(array("dist" => "dbt_district"),array('district_name as district','district_code as distcode'));
                $select->where("dist.state_code =?", $statecode);
                $select->where("dist.status = ? ", "1");
                $select->order("dist.district_name");
				//return $select;exit;
                $district_name = $newtb->fetchAll($select);
                return $district_name->toArray();
			}

public function pfmstransactiondatalist($start = null,$limit = null,$scheme_id = null,$uuid = null, $id = null){
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
		$select->join(array('tr'=>$transaction),"tr.uniq_user_id=tbname.uniq_user_id",array('tr.amount','tr.fund_transfer','tr.transaction_date','tr.pfms_request_id','tr.remarked','tr.pfms_status'));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);
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
	

public function csvexportmethodpfmsinitationdb($schemeid = null,$minid = null,$month = null,$year = null){
		
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
		$select->from(array('tbname' => $tabname),array("uniq_user_id","beneficiary_title","name","dob","gender","aadhar_num","aadhar_seeded","mobile_num","email_id","beneficiary_regional_lang","beneficiary_type","scheme_specific_unique_num","scheme_specific_family_num","home_address","village_code","village_name","panchayat_code","panchayat_name","block_code","block_name","district_code","district_name","state_code","state_name","pincode","ration_card_num","tin_family_id","bank_account","bank_name","ifsc","amount","fund_transfer","transaction_date"));
		$select->join(array('scheme'=>'dbt_scheme'),"tbname.scheme_id=scheme.id",array(''));
		$select->where('tbname.scheme_id = ?', $scheme_id);
		$select->where('scheme.id = ?', $scheme_id);

		$select->where("tbname.uidai_aadhar_validate =2 OR tbname.uidai_aadhar_validate =0");
		$select->where("tbname.aadhar_validate =2 OR tbname.aadhar_validate =0");
		
		$select->where('tbname.aadhar_num != ""');
		$select->where('tbname.pfms_xml_status = ?', 0);
		
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