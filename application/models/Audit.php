<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Audit extends Zend_Db_Table_Abstract 
{
	
	/*********Insert record in the feedback table**********************/
		public function insertFeedbackdetails($dataform)
			{
			
				$user_table = new Zend_Db_Table('dbt_feedback');
				$datainsert="";
				$datainsert = array(
							'type'=> $dataform['type'],
							'name'=> $dataform['name'],
							'email'=> $dataform['email'],
							'mobile'=> $dataform['mobile'],
							'details'=> $dataform['details'],
							'status'=> 1	   						
							);
					 $insertdata=$user_table->insert($datainsert);
					return $insertdata;

		    }
		/********************* end **************************/	


/***************** fetch records from the feedback table ********/
	public function auditlist($start,$limit)
			{   
			
			  //echo "model".$search;
			    $search = '';
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_audit');
				$select = $select_table->select();
				$search = @$_GET['search'];
				$searchtype = @$_GET['searchtype'];
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_audit'), array('id','userid','scheme_name','year','month','total_funds_transferred','aadhar_bridge_payment','without_aadhar_bridge_payment','saving','total_num_beneficieries','aadhar_seeded_total_num_beneficieries','status','created','updated'));
				
			   // $select->where('l.username=?',$search)
			       $select->where('l.status=1')
				 ->order('l.created DESC')->limit($limit,$start);
						//echo $select; exit;
				$select_feedbackrec = $select_table->fetchAll($select);
			
				//echo $select;
				//die;
				return $select_feedbackrec;
			}
		
	/**************** end ***************************/	
	
	
	/************* fetch records from the email reciever table*********/
	
	public function feedbackemailrecievr()
			{ 
			
		
				$select_table = new Zend_Db_Table('dbt_feedback_alert_reciever');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_feedback_alert_reciever'), array('toemail', 'id','ccemail','status','created','updated'));
				$select->where('l.id = 1');

						//echo $select; exit;
				$select_fedbackalertreciever = $select_table->fetchAll($select);
			
				//echo $select;
				//die;
				return $select_fedbackalertreciever;
			
			
			}
	
	
	/*************** end *****************/
		
		
		/********* Count records from the feedback table**********************/
			public function countAudit()
			{
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_audit');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_audit'),array('id','userid','scheme_name','year','month','total_funds_transferred','aadhar_bridge_payment','without_aadhar_bridge_payment','saving','total_num_beneficieries','aadhar_seeded_total_num_beneficieries','status'));		
				$select_feedback = $select_table->fetchAll($select);
				return count($select_feedback); 
			}		
			/*************** end *****************************/
			
			/**************** select all the records from the feedback *********/
			public function editfeedbackmanagement($id)
	        {
                $select_table = new Zend_Db_Table('dbt_feedback');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
			/************* end *********************/
			
			public function editfeedbackmanagementdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_feedback');

						$data="";
						$where="";						
						$data = array(
							'type'=> $editdataform['type'] ,
							'name'=> $editdataform['name'],
							'email'=> $editdataform['email'],
							'mobile'=> $editdataform['mobile'],
							'details'=> $editdataform['details'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}
			
			
			public function inactivefeedbackmanagement($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_feedback');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			
}