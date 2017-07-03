<?php 
// db connection file include here
include_once("db_conn.php");

//  alter query for scheme table start now

	$alterquery="ALTER TABLE dbt_scheme
	ADD COLUMN pfms_scheme_code varchar(30) AFTER `scheme_code`";
	$res4 = $conn->query($alterquery);

//  alter query for scheme end now

$query = "SELECT * FROM dbt_scheme";
$result = $conn->query($query);

if ($result->num_rows > 0) {
$res2=0;
while($row = $result->fetch_assoc()){
 $beneficiarytbname='dbt_'.$row['scheme_table'];
 $transactiontbname='dbt_'.$row['scheme_table'].'_transaction';
//echo $transactiontbname;die;
		$alterquery='';
	// alter query for beneficiary table start now  
	$alterquery="ALTER TABLE $beneficiarytbname
	ADD COLUMN error_remark varchar(200) AFTER `status`,
	ADD COLUMN aadhar_validate int(1) default '0' AFTER `error_remark`,
	ADD COLUMN uidai_error_remark varchar(200) AFTER `aadhar_validate`,
	ADD COLUMN uidai_aadhar_validate int(1) default '0' AFTER `uidai_error_remark`,
    ADD COLUMN `purp_cd` varchar(25) comment 'Purpose of Beneficiary Record. A=Add/U=Update/D=Delete.' AFTER `uidai_aadhar_validate`,
    ADD COLUMN `pfmsbeneficiary_code` VARCHAR(25) comment 'Beneficiary Code in PFMS - PFMS Beneficiary Code provided in response file,Mandatory when Purpose is U or D' AFTER `purp_cd`,
    ADD COLUMN `beneficiary_title` varchar(10) comment 'Beneficiary Title. Eg. Mr, Mrs, Ms, Dr, etc….' AFTER `pfmsbeneficiary_code`,
	ADD COLUMN `beneficiary_regional_lang` varchar(10) comment 'Beneficiary Name in Regional Language' AFTER `beneficiary_title`,
	ADD COLUMN beneficiary_type varchar(50) comment 'Beneficiary Type' AFTER `beneficiary_regional_lang`,
	ADD COLUMN pfms_request_id varchar(10) comment 'Unique Message Identifier. Source System Id given by PFMS(DBTBENREQDDMMYYYYN).'  AFTER `beneficiary_type`,
	ADD COLUMN request_generated_time timestamp AFTER `pfms_request_id`,
	ADD COLUMN nbOf_txs varchar(10) comment 'Should be grater than 0. Count of CstmrTxInf tag.' AFTER `request_generated_time`,
	ADD COLUMN initg_pty_nm varchar(10) comment 'Owner Agency of Data i.e. District Level DDO.' AFTER `nbOf_txs`,
	ADD COLUMN `Initgpty_prTry_id` varchar(10) comment 'Data Owner Agency Unique Code i.e. for District Level DDO in PFMS. Reference Key of agency / PFMS Unique Code.' AFTER `initg_pty_nm`,
	ADD COLUMN cstmr_inf_id varchar(10) comment 'Batch Number- Source System Id given by PFMS.' AFTER `Initgpty_prTry_id`,
	ADD COLUMN cstmr_inf_dt datetime comment 'YYYY-MM-DD format Batch Date' AFTER `cstmr_inf_id`,
	ADD COLUMN pfms_xml_status int(1) default '0' comment 'PFMS Status for xml generated' AFTER `cstmr_inf_dt`,
	ADD COLUMN bank_name varchar(100) comment 'Bank Name of beneficiary' AFTER `pfms_xml_status`,
	ADD COLUMN beneficiary_pfms_status int(1) default '0' comment 'beneficiary status from pfms response' AFTER `bank_name`,
	ADD COLUMN pfms_rejection_code varchar(20) comment 'Error Code return by pfms' AFTER `beneficiary_pfms_status`,
	ADD COLUMN pfms_error_remark varchar(100) comment 'beneficiary status from pfms response' AFTER `pfms_rejection_code`";
//echo $alterquery;die;
	$res1 = $conn->query($alterquery);
	//  alter query for beneficiary end now 

	// alter query for transaction start now
	$alterquery="";
	 
	$alterquery="ALTER TABLE $transactiontbname
    ADD COLUMN `txn_id` varchar(50) AFTER `uniq_user_id`,
    ADD COLUMN `pfms_request_id` VARCHAR(25) AFTER `request_id`,
    ADD COLUMN `remarked` varchar(300) AFTER `pfms_request_id`,
	ADD COLUMN `pfms_status` int(1) default '0' AFTER `remarked`,
	ADD COLUMN `transaction_status` int(1) default '0' AFTER `pfms_status`,
	ADD COLUMN `pfms_xml_status` int(1) default '0' AFTER `transaction_status`,
	ADD COLUMN `from_payment_date` date AFTER `pfms_xml_status`,
	ADD COLUMN `to_payment_date` date AFTER `from_payment_date`,
	ADD COLUMN `purpose` varchar(100) AFTER `to_payment_date`,
	ADD COLUMN `pfms_transaction_error_code` varchar(50) AFTER `purpose`,
	ADD COLUMN `pfms_transaction_remark_code` varchar(300) AFTER `pfms_transaction_error_code`,
	ADD COLUMN `payment_mode_by` int(1) default '1' AFTER `pfms_transaction_remark_code`,
	ADD COLUMN `approval_transaction_date` date AFTER `payment_mode_by`";
	$res = $conn->query($alterquery);

// create table for pfms request log count start now 
$createtable="CREATE TABLE IF NOT EXISTS dbt_pfms_reuest_log_count(
			id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			benrequest_no int(11),
			payrequest_no int(11),
			request_date varchar(50),
			updated timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			created timestamp
		    )";
$res3 = $conn->query($createtable);
// create table for pfms request log count end now 

// create table for pfms transaction request start now 
$createtable='';
$createtable="CREATE TABLE IF NOT EXISTS dbt_tr__log_pfms_request(
			id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			tablename varchar(100),
			msgId varchar(100),
			created timestamp
		    )";
$res4 = $conn->query($createtable);
// create table for pfms transaction request end now 

	if($res && $res1){
		$res2+=1;
		}
	// alter query for transaction end now
	}
	if($res2>0){
		echo "True";
		}else{
		echo "false";
		}
 } 
 
?>

