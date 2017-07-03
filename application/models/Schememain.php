<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Schememain extends Zend_Db_Table_Abstract 
{
	
/***************** fetch records from the feedback table ********/
	public function schemenew($lang)
			{   
			
			  //echo "model".$search;
			 
			    $search = '';
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('dbtmininistry' => 'dbt_ministry'), array('ministry_name', 'id','language','status','created','updated','translation_id'));
				//$select->where('l.language LIKE ?','%'.$lang.'%')
				$select->where('dbtmininistry.language =2')
				->order('dbtmininistry.id DESC');
						
				$select_feedbackrec = $select_table->fetchAll($select);
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				$title = array();
				foreach($select_feedbackrec as $k=>$v)
					{
				
					
					    if($lang == 1 && $v['translation_id']!=0)
						{
							$tid = $v['translation_id'];
							//echo $tid;
							$obj = new 	Application_Model_Schememain();
							$valwithtranslation  = $obj->pagelistByLang($tid, $lang);
							//return $valwithtranslation;
							foreach($valwithtranslation  as $k=>$v)
							{
								
							
								
							  echo " | ".$ministry_name  = $v['ministry_name']."id=".$v['id'];
							    $ministry_id = $v['id'];
						
							$select_table = new Zend_Db_Table('dbt_scheme');
				            $rowscheme = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->where('ministry_id = ?',$ministry_id)->order('id DESC'));
				             foreach($rowscheme as $k=>$v)
							 {
								 $schemename = $v['scheme_name'];
								 echo "scheme list".$schemename;
								 
							 }
				
								
								
								
								
							}	
							
						}
						else
						{
							
							echo " | ".$ministry_name = $v['ministry_name']."id=".$v['id'];
							
							            $ministry_id = $v['id'];
                          /******** get the scheme based upon the ministry id **/
						  $select_table = new Zend_Db_Table('dbt_scheme');
				            $rowscheme = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->where('ministry_id = ?',$ministry_id)->order('id DESC'));
							 foreach($rowscheme as $k=>$v)
							 {
								 $schemename = $v['scheme_name'];
								 echo "scheme list".$schemename;
								 
							 }
				
						  /************* end **********/
							
						
						}
				}
				//return $valwithtranslation;
			}
		


/***************** fetch records from the feedback table ********/
	public function schemelist($lang)
			{   
			
			  //echo "model".$search;
			  echo "test"; exit;
			    $search = '';
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('dbtmininistry' => 'dbt_ministry'), array('ministry_name', 'id','language','status','created','updated','translation_id'));
				//$select->where('l.language LIKE ?','%'.$lang.'%')
				$select->where('dbtmininistry.language =2')
				->order('dbtmininistry.id DESC');
						
				$select_feedbackrec = $select_table->fetchAll($select);
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				$title = array();
				foreach($select_feedbackrec as $k=>$v)
					{
				
					print_r($select_feedbackrec);
					    if($lang == 1 && $v['translation_id']!=0)
						{
							$tid = $v['translation_id'];
							//echo $tid;
							$obj = new 	Application_Model_Schememain();
							$valwithtranslation  = $obj->pagelistByLang($tid, $lang);
							//return $valwithtranslation;
							foreach($valwithtranslation  as $k=>$v)
							{
								
							
								print_r($valwithtranslation); die;
							  echo " | ".$ministry_name  = $v['ministry_name']."id=".$v['id'];
							    $ministry_id = $v['id'];
							}
								
								
							
						}
						else
						{
							
							echo " | ".$ministry_name = $v['ministry_name']."id=".$v['id'];
							
							            $ministry_id = $v['id'];
                          /******** get the scheme based upon the ministry id **/
						  
						  /************* end **********/
							
						
						}
				          }
				//return $valwithtranslation;
			}
		
	/**************** end ***************************/	
	
	/*********** Return title based on the single record**************/
	
	public function pagelistByLang($tid, $lang)
	{
		
		    //echo $lang;
		        $search = '';
			  	$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('dbtmininistry' => 'dbt_ministry'), array('ministry_name', 'id','language','status','created','updated','translation_id'));
				
				$select->where('dbtmininistry.language  = ?', $lang);
				$select->where('dbtmininistry.id  = ?', $tid)
				->order('dbtmininistry.id DESC');
						
				$select_feedbackrec = $select_table->fetchAll($select);
			
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				return $select_feedbackrec;
	}

	
	/***************************** end *******************************/
		
		
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