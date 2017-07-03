<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Feedback extends Zend_Db_Table_Abstract 
{
	
	/*********Insert record in the feedback table**********************/
		public function insertFeedbackdetails($dataform)
			{
               $userid = new Zend_Session_Namespace('userid');
			   //$userid = $userid->userid;
			   if($userid->userid!='')
			   {
				   $userid = $userid->userid;
			   }
			   else
			   {
				    $userid = 0;
			   }
				$user_table = new Zend_Db_Table('dbt_feedback');
				$datainsert="";
				$datainsert = array(
							'type'=> $dataform['type'],
							'name'=> $dataform['name'],
							'email'=> $dataform['email'],
							'mobile'=> $dataform['mobile'],
							'details'=> $dataform['details'],
							'status'=> 1, 
							'created_by' => $userid   	   						
							);
					 $insertdata=$user_table->insert($datainsert);
					return $insertdata;

		    }
		/********************* end **************************/	


/***************** fetch records from the feedback table ********/
	public function feedbacklist($start,$limit)
			{   
			
			  //echo "model".$search;
			    $search = '';
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_feedback');
				$select = $select_table->select();
				$search = @$_GET['search'];
				$searchtype = @$_GET['searchtype'];
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_feedback'), array('type', 'id','name','email','mobile','details','status','created','updated'));
				$select->ORwhere('l.name LIKE ? ','%'.$search.'%');
				$select->ORwhere('l.email LIKE ? ','%'.$search.'%');
				
				$select->ORwhere('l.type=?',trim(intval($searchtype)))
			    //$select->ORwhere('l.type=?',$search)
			     //$select->ORwhere('l.name LIKE ? OR 1.email LIKE ?', '%'.$search.'%', '%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
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
			public function countFeedback()
			{
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_feedback');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_feedback'), array('type', 'id','name','email','mobile','details','status','created','updated'));				
				$select_feedback = $select_table->fetchAll($select);
				return count($select_feedback); 
			}		
			/*************** end *****************************/
			
			
			/********* Count records from the feedback table**********************/
			public function countFeedbacksearch()
			{
				$search = $_GET['search'];
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_feedback');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_feedback'), array('type', 'id','name','email','mobile','details','status','created','updated'));	
               $select->ORwhere('l.name LIKE ? ','%'.$search.'%');
				$select->ORwhere('l.email LIKE ? ','%'.$search.'%');				
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
         public function getusrinfo($userid=null)
			{
				$select_table = new Zend_Db_Table('dbt_users');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim($userid)));
				$rowselectarr = $rowselect->toArray();
				return  $rowselectarr;

			}

			
}