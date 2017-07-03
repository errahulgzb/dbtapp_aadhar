<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Page extends Zend_Db_Table_Abstract 
{
	
	
	
	
	
/**********get page title************/
public function getpagetitle($pageid)
{
	
	          $select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('ctm' => 'dbt_content_management'), array('title'));
				$select->where('ctm.id  = ?', $pageid);
						//echo $select;die;
				$select_cnttilte = $select_table->fetchAll($select);
			
				//print_r($select_feedbackrec);
			
				
				return $select_cnttilte->toArray();
	
	
}



/*************end *****************/



/**********get page title************/
public function getpagetitlehindi($pageid)
{
	
	          $select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('ctm' => 'dbt_content_management'), array('translation_id'));
				$select->where('ctm.id  = ?', $pageid);
						//echo $select;die;
				$select_cnttilte = $select_table->fetchAll($select);
			
				$translation_id  = $select_cnttilte[0][translation_id];
				//echo $translation_id;
				
			 $select->from(array('ctmnew' => 'dbt_content_management'), array('title'));
				$select->where('ctmnew.id  = ?', $translation_id);
						//echo $select;die;
			 $select_cnttiltenew = $select_table->fetchAll($select);
			
				
			return $select_cnttiltenew->toArray();
	
	
}



/*************end *****************/


public function frontpageContentViewhindi($contentId)
			{
					
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				$select->from(array('ctm' => 'dbt_content_management'), array('translation_id'));
				$select->where('ctm.id  = ?', $contentId);
						//echo $select;die;
				$select_cnttilte = $select_table->fetchAll($select);
			
				$translation_id  = $select_cnttilte[0][translation_id];
				
				
				$select->from(array('cms' => 'dbt_content_management'), array('title', 'description'));
				//$select->where('l.language LIKE ?','%'.$lang.'%')
				$select->where('cms.id =?',$translation_id);
				//echo $select; exit;
				$fetch_content = $select_table->fetchRow($select);
	
				 return $fetch_content;     
			}
	

/***************** fetch records from the feedback table ********/
	public function pagelist($lang)
			{   
			
			  //echo "model".$search;
			   // echo $lang; 
			    $search = '';
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_content_management'), array('title', 'id','language','description','status','created','updated','translation_id'));
				//$select->where('l.language LIKE ?','%'.$lang.'%')
				$select->where('l.language =2')
				->order('l.id DESC');
						//echo $select; exit;
				$select_feedbackrec = $select_table->fetchAll($select);
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				$title = array();
				foreach($select_feedbackrec as $k=>$v)
					{
				
					//print_r($select_feedbackrec);
					    if($lang == 1 && $v['translation_id']!=0)
						{
							$tid = $v['translation_id'];
							//echo $tid;
							$obj = new 	Application_Model_Page();
							$valwithtranslation  = $obj->pagelistByLang($tid, $lang);
							//return $valwithtranslation;
							foreach($valwithtranslation  as $k=>$v)
							{
							  echo " | ".$title  = $v['title'];
							  //echo " | ".$title  = $v['title'];
							}
							//$val = 'abc';
						}
						else
						{
							$val = 'def';
							//echo " | ".$title = $v['title'];
							echo " | ".$title = $v['title'];
						
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
			  	$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('l' => 'dbt_content_management'), array('title', 'id','language','description','status','created','updated','translation_id'));
				
				$select->where('l.language  = ?', $lang);
				$select->where('l.id  = ?', $tid)
				->order('l.id DESC');
						
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

			public function frontpageContentView($contentId)
			{
					
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('cms' => 'dbt_content_management'), array('title', 'description'));
				//$select->where('l.language LIKE ?','%'.$lang.'%')
				$select->where('cms.id =?',$contentId);
				//echo $select; exit;
				$fetch_content = $select_table->fetchRow($select);
	
				 return $fetch_content;     
			}

			
}