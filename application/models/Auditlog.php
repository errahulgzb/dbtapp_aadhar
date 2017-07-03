<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Auditlog extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';

	public function getutname($statecode = null){
			if ($statecode == '01') {
				$utname = 'andaman_nicobar';
			} else if ($statecode == '06') {
				$utname = 'delhi';
			} else if ($statecode == '07') {
				$utname = 'dadar_nagar_haveli';
			} else if ($statecode == '08') {
				$utname = 'daman_diu';
			} else if ($statecode == '09') {
				$utname = 'chandigarh';
			} else if ($statecode == '19') {
				$utname = 'lakshadweep';
			} else if ($statecode == '25') {
				$utname = 'puducherry';
			}
			return $utname;
		}
		
	public function getstatename($statecode){
		$select_table = new Zend_Db_Table('dbt_state');
		$row = $select_table->fetchAll($select_table->select()->where('state_code = ? ',$statecode));
		$statename = $row->toArray();
		$statename = $statename[0];
		$statename = $statename['state_name'];
		return $statename;
	}
	
	public function getschemename($scheme_id, $statecode){
		$utname = $this->getutname($statecode);
		$select_table = new Zend_Db_Table('dbt_'.$utname.'_scheme');
		$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
		return $row;
	}
	
	public function schemename($scheme_id){
		$select_table = new Zend_Db_Table('dbt_scheme');
		$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
		return $row;
	}
	
		
	public function insertutschememanualdata($auditlog) //insert scheme manual data
	{
		
		$select_table = new Zend_Db_Table('dbt_audit_log');
		$date = date('Y-m-d H:i:s');				
		$datainsert = array(
					'application_type'=> $auditlog['application_type'],
					'userid'=> $auditlog['uid'],
					'scheme_id'=> $auditlog['scheme_id'],
					'scheme_name'=> $auditlog['scheme_name'],
					'no_of_transactions'=> $auditlog['no_of_transactions'],
					'no_of_transactions_with_aadhaar_seeded'=> $auditlog['no_of_transactions_with_aadhaar_seeded'],
					'total_fund_transfer'=> $auditlog['total_fund_transfer'],
					'total_fund_transfer_using_apb'=> $auditlog['total_fund_transfer_using_apb'],
					'saving'=> $auditlog['saving'],
					'month'=> $auditlog['month'],
					'year'=> $auditlog['year'],
					'created' => $date		
				);
					//print_r($datainsert); exit;		
			
		$insertdata=$select_table->insert($datainsert);
		 
		return $insertdata;
	}
	
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
	
	public function countauditlog()
	{
		$application_type = $_GET['application_type'];
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$select_table = new Zend_Db_Table('dbt_audit_log');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);

		$select->from(array('smd' => 'dbt_audit_log'));	
		if($application_type){
			$select->where('application_type = ?', $application_type);
		}
		if($fromdate && $todate){
			$select->where("smd.created between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'");
		} else if($fromdate){
			$select->where('created >= ?', $fromdate);
		} else if($todate){
			$select->where('created <= ?', $todate." 23:59:59");
		}
		
		$row = $select_table->fetchAll($select);
		$rowarr = $row->toArray();
		return count($rowarr);
	}
	public function auditloglist($start,$limit)
	{	
		$application_type = $_GET['application_type'];
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'] ;
		
		$select_table = new Zend_Db_Table('dbt_audit_log');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);

		$select->from(array('smd' => 'dbt_audit_log'));	
		
		$select->joinLeft(array('u' => 'dbt_users'), 'smd.userid = u.id', array('firstname', 'lastname', 'id'));
		
		if($application_type){
			$select->where('smd.application_type = ?', $application_type);
		}
		
		if($fromdate && $todate){
			$select->where("smd.created between '".$fromdate." 00:00:00' and '".$todate." 23:59:59'");
		} else if($fromdate){
			$select->where('created >= ?', $fromdate);
		} else if($todate){
			$select->where('created <= ?', $todate." 23:59:59");
		}
		$select->order('smd.created DESC');
		$select->limit($limit,$start);
		//echo $select; exit;
		$row = $select_table->fetchAll($select);
		$rowarr = $row->toArray();
		return $rowarr;
	}
	
	public function getapplicationtype()
	{	
		$select_table = new Zend_Db_Table('dbt_audit_log');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);

		$select->from(array('smd' => 'dbt_audit_log'), array('application_type'));
		$select->distinct();
		$select->order('application_type ASC');
		$row = $select_table->fetchAll($select);
		$rowarr = $row->toArray();
		return $rowarr;

	}
}
