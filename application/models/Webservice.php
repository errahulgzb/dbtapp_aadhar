<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Webservice extends Zend_Db_Table_Abstract {


//***************** get transaction_id detail is exist or not start now************************
	public function check_transcation_id($transaction_id = null){
		$select_table = new Zend_Db_Table('dbt_service_manage');
		$select = $select_table->select();
		$select->from(array("servicemanage"=> "dbt_service_manage"), array('count(id) as counted','request_id','state_code','scheme_id'));
		$select->where('servicemanage.request_id  =?' ,$transaction_id);

		$row = $select_table->fetchAll($select)->toArray();
		return $row[0]['counted'];
	}
	public function check_transcation($transaction_id = null){
		$select_table = new Zend_Db_Table('dbt_service_manage');
		$select = $select_table->select();
		$select->from(array("servicemanage"=> "dbt_service_manage"), array('count(id) as counted','request_id','state_code','scheme_id','transaction_date'));
		$select->where('servicemanage.request_id  =?' ,$transaction_id);

		$row = $select_table->fetchAll($select)->toArray();

		return $row;
	}
//***************** get transaction_id detail is exist or not end now************************

//***************** get scheme name start now************************
	public function get_scheme_table($scheme_id = null){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->from(array("sc"=> "dbt_scheme"), array('scheme_table'));
		$select->where('sc.id  =?' ,$scheme_id);
		//echo $select;die;
		$row = $select_table->fetchAll($select)->toArray();
		return $row[0]['scheme_table'];
	}

//***************** get scheme name start now************************

//****************** Delete transaction_id from dbt_service_manage start now*************
public function delete_txn_id($transaction_id = null)
{
    $delete_txn = new Zend_Db_Table('dbt_service_manage');
    $where="";
    $where = array('request_id = ?' => $transaction_id );
    $delete_values = $delete_txn->delete($where);
		return $delete_values;
}

//****************** Delete transaction_id from dbt_service_manage end now******************

//***************** update data transaction_id detail start now************************
	public function update_transction_data($transaction_id = null){

		$row=$this->check_transcation($transaction_id);
		//print_r($row);die("hello");
		if($row[0]['counted']>0){
			$scheme=$this->get_scheme_table($row[0]['scheme_id']);
			$benef_tb='dbt_'.$scheme;
			$benef_transc_tb='dbt_'.$scheme."_transaction";
			$select_obj=new Zend_Db_Table($benef_transc_tb);
			$select=$select_obj->select();
			$select->setIntegrityCheck(false);
			$select->from(array("tr"=> $benef_transc_tb), array('GROUP_CONCAT(tr.id) as tr_id'));
			$select->join(array('benef'=>$benef_tb),"benef.uniq_user_id=tr.uniq_user_id",array());
			$select->where("benef.state_code = ?",$row[0]['state_code']);
			$select->where("benef.scheme_id = ?",$row[0]['scheme_id']);
			$select->where("tr.transaction_date = ?",$row[0]['transaction_date']);
			$select->where("tr.service_status =?",1);
			//echo $select;exit;
			$rowdata = $select_obj->fetchAll($select)->toArray();
			//print_r($rowdata);die;
			if($rowdata[0]['tr_id']!=''){
				$update_obj = new Zend_Db_Table($benef_transc_tb);
				$data = array(
					'service_status' =>0
				);
				$where[] ="id IN (".$rowdata[0]['tr_id'].")";
				$update_stats = $update_obj->update($data,$where);
				$this->delete_txn_id($transaction_id);
				if($update_stats>0){
					return 1;
				}else{
					return 0;
					}
			}else{
					return 2;
					}
		}else{
			//die("return 3");
			return 3;
		}
}

//***************** update data transaction_id detail end now************************

//***************** get scheme name start now************************
	public function get_scheme_name($scheme_id = null){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$select = $select_table->select();
		$select->from(array("sc"=> "dbt_scheme"), array('scheme_name'));
		$select->where('sc.id  =?' ,$scheme_id);
		//echo $select;die;
		$row = $select_table->fetchAll($select)->toArray();
		return $row[0]['scheme_name'];
	}

//***************** get scheme name end now************************

//***************** get scheme name start now************************
	public function get_state_name($state_id = null){
		$select_table = new Zend_Db_Table('dbt_state');
		$select = $select_table->select();
		$select->from(array("s"=> "dbt_state"), array('state_name'));
		$select->where('s.state_code  =?' ,$state_id);
		//echo $select;die;
		$row = $select_table->fetchAll($select)->toArray();
		return $row[0]['state_name'];
	}

//***************** get scheme name end now************************

//****************** insert audit log function start now*****************

public function insertauditlog($auditlog) //insert scheme manual data
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		 $ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';


	$select_table = new Zend_Db_Table('dbt_audit_log');
	$date = date('Y-m-d H:i:s');
	$datainsert = array(
				'application_type'=> $auditlog['application_type'],
				'userid'=> $auditlog['uid'],
				'ipaddress'=> $ipaddress,
				'description'=> $auditlog['description'],
				'created' => $date
			);
				//print_r($datainsert); exit;

	$insertdata=$select_table->insert($datainsert);

	return $insertdata;
}
//****************** insert audit log function end now*****************

}
?>
