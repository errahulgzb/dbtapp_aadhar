<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Ministryowner extends Zend_Db_Table_Abstract 
{
	
	
	/**************** select all the records from the feedback *********/
			public function showphasetwodata($id)
	        {
                $select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				$rowselectarr = $rowselect->toArray();
				 return $rowselectarr;     
			
			}
			/************* end *********************/
	
	// protected $_name = 'roles';
	public function listministry()
	{
		
		$sessid = session_id();
		$select_table = new Zend_Db_Table('dbt_temp_ministry_owner');
		$rowselect = $select_table->fetchAll($select_table->select()->where('session_id = ?',trim($sessid)));
		$rowselectarr = $rowselect->toArray();
		return  $rowselectarr;
		
	}
	/* public function checkcontentschemename($name)
		{	
		$select_table = new Zend_Db_Table('dbt_ministry_owner');
		$rowselect = $select_table->fetchAll($select_table->select()->where('scheme_name  = ?',trim(($name))));
		return count($rowselect); 
		} */
		
		public function checkcontentschemename($name=null,$ministryid=null)
		{	
		$select_table = new Zend_Db_Table('dbt_ministry_owner');
		$rowselect = $select_table->fetchAll($select_table->select()->where('scheme_name  = ?',trim(($name)))->where('ministry_id  = ?',trim(intval(($ministryid)))));
		return count($rowselect); 
		}
	
	public function getministryname($ministryid=null)
	{
		$select_table = new Zend_Db_Table('dbt_ministry');
		$rowselect = $select_table->fetchAll($select_table->select()->where('id = ?',trim($ministryid)));
		$rowselectarr = $rowselect->toArray();
		return  $rowselectarr;
		
	}
	
	/*public function ministryowneredit($dataform,$id)
	{
		
		// 'dbt_eligibility_type' => implode(",",$dataform['eligibility-type']),
		    //return $dataform; die;
						$datanew="";
						$wherenew="";
                       $updatedetails_ministryowner = new Zend_Db_Table('dbt_ministry_owner');	$schemename = $dataform['schemename'];		
                       $dbteligible = $dataform['dbt-eligible'];		
                       $benefittype = $dataform['benefit-type'];	
                       $eligibilitytype = $dataform['eligibility-type'];	
                       $specificreason = $dataform['specific-reason'];	
					    $dbt_eligibility_type = implode(",",$dataform['eligibility-type']);
         					   
						$datanew = array(
						  'scheme_name' => $schemename,
                          'dbt_eligibility' => $dbteligible,
						   'benefit_type' => "$benefittype",
						   'dbt_eligibility_type' => "$dbt_eligibility_type",
						   'specific_reason'=>  "$specificreason"
							);
							
							//print_r($id);
							//print_r($datanew); die;
						$wherenew= array('id = ?'=> $id);
						$update_valuesnew = $updatedetails_ministryowner->update($datanew,$wherenew);
						return $update_valuesnew;
	}
	*/
	
	
public function ministryowneredit($dataform,$id)
	{
		
		// 'dbt_eligibility_type' => implode(",",$dataform['eligibility-type']),
		    //return $dataform; die;
						$datanew="";
						$wherenew="";
                       $updatedetails_ministryowner = new Zend_Db_Table('dbt_ministry_owner');	
					   /*************new code  on 23th sept******/	
					   $schemename = $dataform['schemenamenew'];
					   /***************end*********************/
                       /*************comment on 23th sept******/					   
					   //$schemename = $dataform['schemename'];		
					   /****************end*********************/
                       $dbteligible = $dataform['dbt-eligible'];		
                       $benefittype = $dataform['benefit-type'];	
                       $eligibilitytype = $dataform['eligibility-type'];	
                       $specificreason = $dataform['specific-reason'];	
					    $dbt_eligibility_type = implode(",",$dataform['eligibility-type']);
         				if($dbteligible == 1)
						{   
						$datanew = array(
						  'scheme_name' => $schemename,
                          'dbt_eligibility' => $dbteligible,
						   'benefit_type' => "$benefittype",
						   'dbt_eligibility_type' => "$dbt_eligibility_type",
						   'specific_reason'=>  ""
							);
						}
						else if($dbteligible == 2)
						{
							$datanew = array(
						  'scheme_name' => $schemename,
                          'dbt_eligibility' => $dbteligible,
						   'benefit_type' => "",
						   'dbt_eligibility_type' => "",
						   'specific_reason'=>  "$specificreason"
							);
						}
							//print_r($id);
							//print_r($datanew); die;
						$wherenew= array('id = ?'=> $id);
						$update_valuesnew = $updatedetails_ministryowner->update($datanew,$wherenew);
						/** Update dbt_onboarding_monitoring_system_weightage_master **/
							if($dbteligible == 1){
								$schemesid  = array($id);
								if($benefittype == 3){$benefittype = 1;}
								$benefittype = $benefittype;
								$dataobj = new Application_Model_OnboardingMonitoring;
								$data =  $dataobj->datamigration($schemesid,$benefittype);
							}
						/***************************************************************/
						return $update_valuesnew;
	}
	
	public function listministryowner($id)
	{
		$select_table = new Zend_Db_Table('dbt_ministry_owner');
		$rowselect = $select_table->fetchAll($select_table->select()->where('id = ?',trim($id)));
		$rowselectarr = $rowselect->toArray();
		return  $rowselectarr;
		
	}
	
	public function instempowner($scheme_name,$dbt_eligible,$benefit_type,$dbt_eligible_type,$dbt_specificreason,$ministryid)
			{
               $sessid = session_id();
			   $user_table = new Zend_Db_Table('dbt_temp_ministry_owner');
				$datainsert="";
				$schemename = trim($scheme_name);
				$specificreason = trim($dbt_specificreason);
				//$dbtspecificreason = trim(mysql_real_escape_string($dbt_specificreason));
				$datainsert = array(
				           'ministry_id'=> $ministryid,
				            'scheme_name'=> "$schemename",
							'dbt_eligibility'=> "$dbt_eligible",
							'benefit_type'=> "$benefit_type",
							'dbt_eligibility_type'=> "$dbt_eligible_type",
							'specific_reason'=> "$specificreason",
							'session_id'=> "$sessid",
                            'status' => 1							
									);	
									
									//print_r($datainsert); exit;
					
					 $insertdata=$user_table->insert($datainsert);
					return $insertdata;					 
			}
			
	public function insministryowner($dataform,$ministryid)
			{
				
				//return $dataform; exit;
			   //return $dataform['uniquid']; exit;
				//return $len = $dataform['uniquid']; exit;
				 $len = $dataform['uniquidnew'];
				//return $len; exit;
				 $sessid = session_id();
				 for ($x = 1; $x <= $len; $x++) {
					  if(isset($dataform['scheme-name-'.$x])) { 
					$scheme_name = trim($dataform['scheme-name-'.$x]);
					$dbt_eligible = $dataform['dbt-eligible-'.$x];
					$benefit_type = $dataform['benefit-type-'.$x];
					$eligibility_type =  implode(",",$dataform['eligibility-type-'.$x]);
					$specific_reason = trim($dataform['specific-reason-'.$x]);
					  
					$user_table = new Zend_Db_Table('dbt_ministry_owner');
					$datainsert="";
					$datainsert = array(
					'ministry_id'=> $ministryid,
					'scheme_name'=> "$scheme_name",
					'dbt_eligibility'=> "$dbt_eligible",
					'benefit_type'=> "$benefit_type",
					'dbt_eligibility_type'=> "$eligibility_type",
					'specific_reason'=> "$specific_reason",
					'session_id'=> "$sessid",
					'status' => 1							
					);	
           
					//print_r($datainsert); exit;

					$insertdata=$user_table->insert($datainsert);
					$delete_tempminowner = new Zend_Db_Table('dbt_temp_ministry_owner');
					$where="";
					$where = array('session_id = ?' => $sessid);
					$delete_values = $delete_tempminowner->delete($where);
					//echo $scheme_name."<br/>";
					// echo $x."<br/>";
					/** Update dbt_onboarding_monitoring_system_weightage_master **/
						if($dbt_eligible == 1){
							$schemesid  = array($insertdata);
							if($benefit_type == 3){$benefit_type = 1;}
							$benefittype = $benefit_type;
							$dataobj = new Application_Model_OnboardingMonitoring;
							$data =  $dataobj->datamigration($schemesid,$benefittype);
						}
					/***************************************************************/
				   }
                 }
				return $insertdata;
			 
               				 
			}
			
	
	/** $id is the current row id *********/
	public function getmenutype($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
		
				$menu_type = $rowselect['menu_type'];
				return $menu_type;
				
			}
			
	/************* end **************/		
	
	
	
	
	public function gettranslationid($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));

				
				
				 
				 
				$translation_id = $rowselect['translation_id'];
				return $translation_id;
				
			}
	
		/** $id is the current row id *********/
	public function getsortorder($id)
			{
				$select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));

				
				
				 
				 
				$sort_order = $rowselect['sort_order'];
				return $sort_order;
				
			}
			
	/************* end **************/	
		public function insertContentManagementdetails($dataform)
			{
			
				$user_table = new Zend_Db_Table('dbt_content_management');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				          'menu_type'=> $dataform['menu_type'],
				            'sort_order'=> $dataform['sort_order'],
							'title'=> $dataform['title'],
							'language'=> $dataform['lang'],
							'description'=> $dataform['description'],
							'status'=> 1,
                            'translation_id' => 0							
									);
							 // print_r($datainsert); exit;		
					
					 $insertdata=$user_table->insert($datainsert);
					 
					return $insertdata;
					 
				}
				
				
			public function insertContentManagementTranslationdetails($dataform,$menutyp,$sortorder,$rowid)
			{
			  
				$user_table = new Zend_Db_Table('dbt_content_management');
				  
				$date= time();
				$datainsert="";
				$datainsert = array(
				             'menu_type' => $menutyp,
							'sort_order' => $sortorder,
							'title'=> $dataform['title'],
							'language'=> $dataform['lang'],
							'description'=> $dataform['description'],
							'status'=> 1,
                            'translation_id' => 1							
									);
							 // print_r($datainsert); exit;		
					
					 $insertdata=$user_table->insert($datainsert);
					 
					    $data="";
						$where="";						
						$data = array(
					
							'translation_id'=> $insertdata						
							);
						$where = array('id = ?'=> $rowid);
						//print_r($rowid);
						//print_r($data);
						//exit;
						$update_values = $user_table->update($data, $where);
					 
					return $insertdata;
					 
				}
			
			public function contentmanagementlist($start,$limit,$search)
			{   
			
			  //echo "model".$search;
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				
				
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_content_management'), array('title','menu_type','sort_order','translation_id','id','description','language','status','created','updated'));
				
				
				
			
				$select->joinLeft(array('langu' => 'dbt_language'), 'l.language = langu.id', array('langu.title as langname'));
				
				//$select->joinLeft(array('w' => 'dbt_language'),'w.language = l.id');
				//if($user_role==4){          // check role for admin 
					//$select->where('l.project_id IN (?)', $projectids);
				//}
				$select->where('l.translation_id!=1');
				$select->where('l.language LIKE ?','%'.$search.'%')
				->order('l.id DESC')->limit($limit,$start);
						//echo $select; exit;
            
				$select_org = $select_table->fetchAll($select);
			
				//echo $select;
				//die;
				return $select_org;
			}

			public function countContentmanagement()
			{
				
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_content_management'), array('title','menu_type','sort_order','id','description','language','status','created','updated'));	
               $select->where('l.translation_id!=1');
				
				//$select->joinLeft(array('u' => 'dbt_users'), 'l.customer_id = u.id', array('firstname','lastname','organisation'));
				//$select->joinLeft(array('p' => 'projects'), 'l.project_id = p.id', array('p.title as projectname'));
				
				//$select->where('l.title LIKE ? OR u.organisation LIKE ?', '%'.$search.'%', '%'.$search.'%', '%'.$search.'%')
				//$select->ORwhere('p.title LIKE ?', '%'.$search.'%');
				//$select->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				//->order('l.id DESC')->limit($limit,$start);
					
                //echo $select; exit;
				$select_org = $select_table->fetchAll($select);
				return count($select_org); 
			}

			//Delete fielname 
			public function deletesavedfile($fileid)
			{
				$deletefilename = new Zend_Db_Table('dbt_scheme_eligbility_assessment');

				$data="";
				$where="";
				$data = array(
						'filename'=> '',
					);
				$where = array('id = ?'=> $fileid);
				$update_values = $deletefilename->update($data, $where);
			}
			
			/*public function locationuser($start,$limit)
			{
				  $select_table = new Zend_Db_Table('locations');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
				  
				  
				  
			
			
			
			
			}*/

		/*	public function customeruser()
			{
				 // $select_table = new Zend_Db_Table('dbt_users');
				//  $row = $select_table->fetchAll($select_table->select()->where('status =1 and role=3')->order('id DESC'));
				
				 // return $row; 
				  
				  
				  
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				$user_role = $role->role;
				// select distinct customer from projects table 				
				
				$nm22 = new Zend_Db_Table('projects');
				$select = $nm22->select();
				$select->from(array('p' => 'projects'), array( 'customer_id'));	
				if($user_role==4){          // check role for admin 
					$select->where('p.id IN (?)', $projectids);
				}		
				$select->where('status=1');
				$row22 = $nm22->fetchAll($select);
				
				$ids =  $row22->toArray();
				$pids = array(); 
				$arr  = array();
				foreach($ids as $key=>$val)
				{
				   $pids[] = $val[customer_id];
				  
				   
				}
				//echo "<pre>"; print_r($pids); exit;
				
				$select_table = new Zend_Db_Table('dbt_users');
				$select_query =  $select_table->select();
				$select_query->from(array('u' => 'dbt_users'), array( 'id','organisation','firstname','lastname','username'));	

				$select_query->where('role =3');

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $pids);	
				}	
				$select_query->where('status=1');
				$select_query->order('organisation ASC');
				
				$rowlist = $select_table->fetchAll($select_query);

				return $rowlist; 
			}
			*/
			
			
			
			/*****************Assigned Installation Engineer List*************/
			public function assign_projectids($pmid=null)
			{
				$nm22 = new Zend_Db_Table('dbt_assign_manager');
				$select = $nm22->select();
				$select->from(array('am' => 'dbt_assign_manager'), array( 'project_id'));	
				$select->where('pm_id=?',trim(intval($pmid)));			
				$select->where('status=1');
				$row22 = $nm22->fetchAll($select);

				$ids =  $row22->toArray();
				
				
				$pids = array(); 
				$arr  = array();
				foreach($ids as $key=>$val)
				{
				   $pids[] = $val[project_id];
				  
				   
				}
				$arr[] = implode(',',$pids);
				$abc = end($arr);
				$str = explode(',',$abc);
				 return $str;


			}
			
			
			public function language()
			{
				  $select_table = new Zend_Db_Table('dbt_language');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC'));
				
				  return $row; 
			}

			/*public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('locations');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('id='.$id));	
				  
				  $userid = new Zend_Session_Namespace('userid');
				$role = new Zend_Session_Namespace('role');
				$user_role = $role->role;
				$projectids =  $this->assign_projectids($userid->userid);// chk assign project ids for login dbt_users 
				
				$select_table = new Zend_Db_Table('projects');
				$select_query =  $select_table->select();
				$select_query->from(array('p' => 'projects'), array( 'id','title'));		

				if($user_role==4){          // check role for admin firstname
					$select_query->where('id IN (?)', $projectids);	
				}	
				$select_query->where('status=1');
				//$select_query->where('customer_id=?',$projectrow['customer_id']);
				$select_query->order('title ASC');				
				$rowlist = $select_table->fetchAll($select_query);
				  
				return $rowlist; 
			}
			*/
			


			public function editcontentmanagement($id)
	        {
                $select_table = new Zend_Db_Table('dbt_content_management');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}

			
			/*public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('locations');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}*/
			
			

			public function editcontentmanagementdetails($editdataform,$id,$translationid)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');

						$data="";
						$where="";						
						$data = array(
						   'menu_type'=> $editdataform['menu_type'] ,
						    'sort_order'=> $editdataform['sort_order'] ,
							'title'=> $editdataform['title'] ,
							'description'=> $editdataform['description']						
							);					
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
							$updatedetails_selecttable_menu = new Zend_Db_Table('dbt_content_management');
						
						$datanew="";
						$wherenew="";						
						$datanew = array(
					
							'menu_type'=> $editdataform['menu_type'],
                           'sort_order'=> $editdataform['sort_order'] 							
							);
						$wherenew= array('id = ?'=> $translationid);
						//print_r($rowid);
						//print_r($data);
						//exit;
						$update_valuesnew = $updatedetails_selecttable_menu->update($datanew, $wherenew);
			}
			
			
		public function editcontentmanagementtranslationdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');

						$data="";
						$where="";						
						$data = array(
						  
							'title'=> $editdataform['title'] ,

							'description'=> $editdataform['description'],
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			/*public function deletelocation($id)
			{
					$delete_project = new Zend_Db_Table('locations');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_project->delete($where);

			}*/

			public function inactivecontentmanagement($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('dbt_content_management');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checkcontent($name)
	        {	
			$select_table = new Zend_Db_Table('dbt_content_management');
			$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name))));
				return count($rowselect); 
			}
			
	public function checkValueExist($ministryid = null,$phaseid = null){
		$elobj = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $elobj->select();
		$select->from(array("dses"=>"dbt_scheme_eligbility_assessment"),array("count(dses.id) as total_rows"));
		$select->where("dses.ministry_id =?",$ministryid);
		$select->where("dses.phase_id =?",$phaseid);
		$select->where("dses.save = ?","0");
		$dataset = $elobj->fetchAll($select);
		$returnSet = $dataset->toArray();
		return $returnSet[0]['total_rows'];
		// echo "<pre>";
		// print_r($returnSet);
		// exit;
	}
	public function SaveRecord($dataform = null, $action = null,$filename=null){
		//echo "Your record has been saved";
		$ministryid = new Zend_Session_Namespace('ministryid');
		$userid = new Zend_Session_Namespace('userid');
		$ministryid = $ministryid->ministryid;
		$userid = $userid->userid;
		$phase_ids=base64_decode($dataform['phase_id']);
		
		$counting = $this->checkValueExist($ministryid,base64_decode($dataform['phase_id']));
		//echo $counting;exit;
		
		if($action == "save"){
			$formfill = '0';//this is showing that form is saved but not submitted
		}else if($action == "submit"){
			$formfill = '1';//this is showing that form is submitted
		}
		//echo $formfill;exit;
		$datainsert = array();
		
                 if($filename){
                    $datainsert = array( 
			"ministry_id" => $ministryid,
			"phase_id" => base64_decode($dataform['phase_id']),
			"userid" => $userid,
			"name_of_scheme" => $dataform['name_of_scheme'],
			"type_of_scheme" => $dataform['type_of_scheme'],
			"fund_allocation" => $dataform['fund_allocation'],
			"implemeting_agency" => $dataform['implemeting_agency'],
			"target_beneficiary" => $dataform['target_beneficiary'],
			"total_eligble_beneficiary" => $dataform['total_eligble_beneficiary'],
			"digitized_beneficiary_status" => $dataform['digitized_beneficiary_status'],
			"digitized_details_of_act" => $dataform['digitized_details_of_act'],
			"mis_portal_status" => $dataform['mis_portal_status'],
			"details_of_actions_init" => $dataform['details_of_actions_init'],
			"aadhar_seeding_bd" => $dataform['aadhar_seeding_bd'],
			"bank_account_bd" => $dataform['bank_account_bd'],
			"mobile_number_bd" => $dataform['mobile_number_bd'],
			"aadhar_linkage_account" => $dataform['aadhar_linkage_account'],
			"scheme_description" => $dataform['scheme_description'],
			"type_of_benefit" => $dataform['type_of_benefit'],
			"details_of_benefit" => $dataform['details_of_benefit'],
			"process_flow_description" => $dataform['process_flow_description'],
			"pfms_payment" => $dataform['pfms_payment'],
			"mode_of_payment" => $dataform['mode_of_payment'],
			"fund_disburse_description" => $dataform['fund_disburse_description'],
			"filename" => $dataform['filename'],
			"save" => $formfill,
			"status" => "1",
			"created" => date("Y-m-d H:i:s")
                        );
                 }else{
                     
                     $datainsert = array( 
			"ministry_id" => $ministryid,
			"phase_id" => base64_decode($dataform['phase_id']),
			"userid" => $userid,
			"name_of_scheme" => $dataform['name_of_scheme'],
			"type_of_scheme" => $dataform['type_of_scheme'],
			"fund_allocation" => $dataform['fund_allocation'],
			"implemeting_agency" => $dataform['implemeting_agency'],
			"target_beneficiary" => $dataform['target_beneficiary'],
			"total_eligble_beneficiary" => $dataform['total_eligble_beneficiary'],
			"digitized_beneficiary_status" => $dataform['digitized_beneficiary_status'],
			"digitized_details_of_act" => $dataform['digitized_details_of_act'],
			"mis_portal_status" => $dataform['mis_portal_status'],
			"details_of_actions_init" => $dataform['details_of_actions_init'],
			"aadhar_seeding_bd" => $dataform['aadhar_seeding_bd'],
			"bank_account_bd" => $dataform['bank_account_bd'],
			"mobile_number_bd" => $dataform['mobile_number_bd'],
			"aadhar_linkage_account" => $dataform['aadhar_linkage_account'],
			"scheme_description" => $dataform['scheme_description'],
			"type_of_benefit" => $dataform['type_of_benefit'],
			"details_of_benefit" => $dataform['details_of_benefit'],
			"process_flow_description" => $dataform['process_flow_description'],
			"pfms_payment" => $dataform['pfms_payment'],
			"mode_of_payment" => $dataform['mode_of_payment'],
			"fund_disburse_description" => $dataform['fund_disburse_description'],
			"save" => $formfill,
			"status" => "1",
			"created" => date("Y-m-d H:i:s")
                    );
                 }
                 
             
		//echo "<pre>";
		//print_r($datainsert);
		//exit;
		$modelobj = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		if($counting > 0){
				$where = array(
						"ministry_id = ? " => $ministryid,
						"phase_id = ? " => $phase_ids,
						"save = ? " => "0",
				);
				
					// echo "<pre>";
					// print_r($datainsert);exit;
					$modelobj->update($datainsert,$where);
				}else{
					//echo "aaaa";exit;
					$modelobj->insert($datainsert);
				}
		}
	/*public function PhaseoneReport($ministry_id = null, $userid = null,$search){
            //echo "-----".$ministry_id;die;
		$role = new Zend_Session_Namespace('role');
		$dmo = new Zend_Db_Table("dbt_ministry_owner");
		$select = $dmo->select();
                if($role->role == 6){
                    $select->where("ministry_id = ?" ,$ministry_id);
		}elseif($search){
                 $select->where("ministry_id = ?" ,$search);   
                }
		$select->order("id DESC");
		//$select->where("user_id = ?" ,$userid);
		$select->where("status = ?" ,"1");
		$select->order("scheme_name")->limit($limit,$start);
		//echo $select;exit;
		$dataset = $dmo->fetchAll($select);
		$resultant = $dataset->toArray();
		return array_filter($resultant);
	}*/
	
	public function PhaseoneReport($ministry_id = null, $userid = null,$search,$eligible_type)
	{
		$role = new Zend_Session_Namespace('role');
		$dmo = new Zend_Db_Table("dbt_ministry_owner");
		$select = $dmo->select();
		$select->setIntegrityCheck(false);
		$select->from(array('ministry1' => 'dbt_ministry_owner'), array('id','ministry_id','scheme_name','dbt_eligibility','benefit_type','dbt_eligibility_type','specific_reason','status','created','updated'));	
		$select->joinLeft(array('ministry' => 'dbt_ministry'), 'ministry1.ministry_id = ministry.id', array('ministry.ministry_name as ministryname'));
		if($role->role == 6){
            $select->where("ministry1.ministry_id = ?" ,$ministry_id);
		}elseif($search){
			$select->where("ministry1.ministry_id = ?" ,$search);   
		}
		
		if($eligible_type)
		{
			$select->where("ministry1.dbt_eligibility  = ?" ,$eligible_type); 
		}
		$select->order("ministry1.updated DESC");
		$select->where("ministry1.status = ?" ,"1");
		$select->order("ministry1.scheme_name")->limit($limit,$start);
		$dataset = $dmo->fetchAll($select);
		$resultant = $dataset->toArray();
		return array_filter($resultant);
	}

	public function countPhaseoneReport($ministry_id = null, $userid = null,$search,$eligible_type){
            //echo "-----".$ministry_id;die;
		$role = new Zend_Session_Namespace('role');
		$dmo = new Zend_Db_Table("dbt_ministry_owner");
		$select = $dmo->select();
                if($role->role == 6){
                    $select->where("ministry_id = ?" ,$ministry_id);
		}elseif($search){
                 $select->where("ministry_id = ?" ,$search);   
                }
				
	   if($eligible_type)
		{
			$select->where("dbt_eligibility  = ?" ,$eligible_type);  
		}
		
		
		$select->order("id DESC");
		//$select->where("user_id = ?" ,$userid);
		$select->where("status = ?" ,"1");
		$select->order("scheme_name")->limit($limit,$start);
		//echo $select;exit;
		$dataset = $dmo->fetchAll($select);
		$resultant = $dataset->toArray();
		return count($resultant);
	}
	
			public function PhaseoneReportcsv($search=null,$eligible_type=null)
			{
				
				$select_table = new Zend_Db_Table('dbt_ministry_owner');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('ministry1' => 'dbt_ministry_owner'), array('id','ministry_id','scheme_name','dbt_eligibility','benefit_type','dbt_eligibility_type','specific_reason','status','created','updated'));	
				 $select->joinLeft(array('ministry' => 'dbt_ministry'), 'ministry1.ministry_id = ministry.id', array('ministry.ministry_name as ministryname'));
				 //echo $select; exit;
				 $select->order("ministry1.updated DESC");
				 //$select->order("ministry.id DESC");
				 $select->where("ministry1.status = ?" ,"1");
                                 if($search){
                                  $select->where("ministry1.ministry_id = ?" ,$search);   
                                 }
								 
								   if($eligible_type){
                                  $select->where("ministry1.dbt_eligibility = ?" ,$eligible_type);   
                                 }
				 $select_org = $select_table->fetchAll($select);
				 $resultant = $select_org->toArray();
				 //print_r($resultant);
		          return array_filter($resultant);
			}

			
			public function PhaseoneReportcsvphasetwo($search,$savetype)
			{
				
				$select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('scheme_eligbility_assessment' => 'dbt_scheme_eligbility_assessment'), array('id','ministry_id','phase_id','userid','name_of_scheme','type_of_scheme','fund_allocation','implemeting_agency','target_beneficiary','total_eligble_beneficiary','digitized_beneficiary_status','digitized_details_of_act','mis_portal_status','details_of_actions_init','aadhar_seeding_bd','bank_account_bd','mobile_number_bd','aadhar_linkage_account','scheme_description','type_of_benefit','details_of_benefit','process_flow_description','pfms_payment','mode_of_payment','fund_disburse_description','filename','status','created','updated'));	
				 $select->joinLeft(array('ministry' => 'dbt_ministry'), 'scheme_eligbility_assessment.ministry_id = ministry.id', array('ministry.ministry_name as ministryname'));
				 //echo $select; exit;
				// $select->order("scheme_eligbility_assessment.id DESC");
				//$select->order("ministry.id DESC");
				$select->order("scheme_eligbility_assessment.updated DESC");
				 $select->where("scheme_eligbility_assessment.status = ?" ,"1");
				  $select->where("scheme_eligbility_assessment.save = ?" ,"1");
                                 if($search){
                                  $select->where("scheme_eligbility_assessment.ministry_id = ?" ,$search);   
                                 }
					if($savetype == '0'){ 

					$select->where("scheme_eligbility_assessment.save = ?" ,$savetype);
					}
					else
					{
					$select->where("scheme_eligbility_assessment.save=1"); 
					}
				 $select_org = $select_table->fetchAll($select);
				 $resultant = $select_org->toArray();
				 //print_r($resultant);
		          return array_filter($resultant);
			}
			
			

	public function selectSchemeName($decodePhaseid = null,$decodeMinistryid = null){
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_ministry_owner");
		$select = $dbtable->select();
		$select->where("id = ?" ,$decodePhaseid);
		if($role->role == 6){
			$select->where("ministry_id = ?" ,$decodeMinistryid);
		}
		$dataset = $dbtable->fetchAll($select);
		$resultant = $dataset->toArray();
		return array_filter($resultant);
	}
	public function schemeaddeddetailcsv(){
		$role = new Zend_Session_Namespace('role');
		$select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
		$row = $select_table->fetchAll($select_table->select()->where('status =1')->where('save =1')->order('id ASC'));
		$resultant = $row->toArray();
		return $resultant;
	}
	
	
   public function countschemeaddeddetail($search=null,$savetype = null){
		/*$role = new Zend_Session_Namespace('role');
		$select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
		$row = $select_table->fetchAll($select_table->select());
		return count($row); */
       
       
                $role = new Zend_Session_Namespace('role');
            $select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
            $select = $select_table->select();
            $select->from(array('m_assessment'=>'dbt_scheme_eligbility_assessment'));	
            //$select->where("m_assessment.status=1"); 
			/*****code to show only submit records*****/
			$select->where("m_assessment.status=1"); 
			//$select->where("m_assessment.save=1"); 
			/**************end***************************/
            if($search){
                $select->where("m_assessment.ministry_id=$search"); 
            }
			
			 if($savetype == '0'){ 
				$select->where("m_assessment.save = ?" ,$savetype);
            }
			else
			{
			  $select->where("m_assessment.save=1"); 
			}
            //echo $select;die;
            $dataset = $select_table->fetchAll($select);   
            $resultant = $dataset->toArray();
            //echo count($row);die;
            return count($resultant);
	  }
	
	
	public function schemeaddeddetail($start,$limit,$search,$savetype){
            /*$role = new Zend_Session_Namespace('role');
            $select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
            $row = $select_table->fetchAll($select_table->select()->where('status =1')->order('id DESC')->limit($limit,$start));
            $resultant = $row->toArray();
            */
            $role = new Zend_Session_Namespace('role');
            $select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
            $select = $select_table->select();
            $select->from(array('m_assessment'=>'dbt_scheme_eligbility_assessment'));	
            //$select->join(array('ministry' => 'dbt_ministry'), 'm_assessment.ministry_id = ministry.id', array('id','ministry.ministry_name as ministryname'));
            $select->where("m_assessment.status=1"); 
			/*****code to show only submit records*****/
			//$select->where("m_assessment.save=1"); 
			/**************end***************************/
            $select->order("m_assessment.id desc"); 
            $select->limit($limit,$start);
            if($search){
                $select->where("m_assessment.ministry_id=$search"); 
            }
			
			if($savetype == '0'){ 
				
				$select->where("m_assessment.save = ?" ,$savetype);
            }
			else
			{
			  $select->where("m_assessment.save=1"); 
			}
			//echo $select;
            //echo $select;die;
            $dataset = $select_table->fetchAll($select);   
            $resultant = $dataset->toArray();
            return $resultant; 
	}
	
	
        
        
              
        
        
        
        
        
        
        
        
        
        
        
        
	
	public function SelectExistance($decodePhaseid = null,$decodeMinistryid = null){
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $dbtable->select();
		$select->from(array("dbtelig" => "dbt_scheme_eligbility_assessment"),array('count(id) as counting'));
		$select->where("dbtelig.phase_id = ?" ,$decodePhaseid);
		//echo "asas".$decodePhaseid;exit;
		if($role->role == 6){
			$select->where("dbtelig.ministry_id = ?" ,$decodeMinistryid);
		}
		$dataset = $dbtable->fetchAll($select);
		
		$resultant = $dataset->toArray();
		return $resultant[0]['counting'];
	}
	public function selectSchemeNamePop($decodePhaseid = null,$decodeMinistryid = null){
		$data = $this->SelectExistance($decodePhaseid,$decodeMinistryid);
		//echo "asas".$data;exit;
		if($data > 0){
				$role = new Zend_Session_Namespace('role');
				$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
				$select = $dbtable->select();
				$select->where("phase_id = ?" ,$decodePhaseid);
				if($role->role == 6){
					$select->where("ministry_id = ?" ,$decodeMinistryid);
				}
				$dataset = $dbtable->fetchRow($select);
				$resultant = $dataset->toArray();
				return array_filter($resultant);
		}else{
			//echo "aaaa";exit;
			//return 0;
		}
		
	}
	public function SelectForView($pahseid = null,$ministryid = null){
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $dbtable->select();
		$select->from(array("dbtelig" => "dbt_scheme_eligbility_assessment"),array('count(id) as counting'));
		$select->where("dbtelig.phase_id = ?" ,$pahseid);
		$select->where("dbtelig.save = ?" ,"1");
		//echo "asas".$decodePhaseid;exit;
		if($role->role == 6){
			$select->where("dbtelig.ministry_id = ?" ,$ministryid);
		}
		$dataset = $dbtable->fetchAll($select);
		
		$resultant = $dataset->toArray();
		return $resultant[0]['counting'];
	}
	
//checking here if form is submitted	
	public function SelectForViewSubmit($pahseid = null,$ministryid = null){
		//echo "Aaaa";exit;
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $dbtable->select();
		
		$select->from(array("dbtelig" => "dbt_scheme_eligbility_assessment"),array('count(id) as counting'));
		$select->where("dbtelig.phase_id = ?" ,$pahseid);
		$select->where("dbtelig.save = ?" ,"1");
		
		//echo "asas".$decodePhaseid;exit;
		if($role->role == 6){
			$select->where("dbtelig.ministry_id = ?" ,$ministryid);
		}
		$dataset = $dbtable->fetchAll($select);
		
		$resultant = $dataset->toArray();
		return $resultant[0]['counting'];
	}
	public function viewDetailsofScheme($phaseid = null,$ministry_id = null){
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $dbtable->select();
		$select->where("phase_id = ?" ,$phaseid);
		$select->where("save = ?" ,"1");
		if($role->role == 6){
			$select->where("ministry_id = ?" ,$ministry_id);
		}
                echo $select;die;
		$dataset = $dbtable->fetchAll($select);
		
		$resultant = $dataset->toArray();
		return $resultant;
	}
        
        
        public function viewUploadDetailsOfScheme($phaseid = null,$ministry_id = null){
		$role = new Zend_Session_Namespace('role');
		$dbtable = new Zend_Db_Table("dbt_scheme_eligbility_assessment");
		$select = $dbtable->select();
		$select->where("phase_id = ?" ,$phaseid);
		$select->where("save = ?" ,"0");
		if($role->role == 6){
			$select->where("ministry_id = ?" ,$ministry_id);
		}
                //echo $select;die;
		$dataset = $dbtable->fetchAll($select);
		
		$resultant = $dataset->toArray();
		return $resultant;
	}
        
        
        public function getMinistry($id,$table)
        {
                $table_name=$table;
                $select_table = new Zend_Db_Table($table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('m_owner'=>$table_name), array('ministry_id'));	
                 $select->join(array('ministry' => 'dbt_ministry'), 'm_owner.ministry_id = ministry.id', array('id','ministry.ministry_name as ministryname'));
                 //echo $select; exit;
                // $select->order("scheme_eligbility_assessment.id DESC");
                $select->order("ministry.ministry_name asc");
                 //$select->where("scheme_eligbility_assessment.status = ?" ,"1");
                $select->group("ministry.ministry_name");
                //echo $select; exit;
                 $select_org = $select_table->fetchAll($select);
                 $resultant = $select_org->toArray();
                 //print_r($resultant);
          return array_filter($resultant);
        }
		
		/*************new method added on 23th sept*******************/
public function PhasetwoReport($ministry_id = null, $userid = null,$search)
	{
		$role = new Zend_Session_Namespace('role');
		$dmo = new Zend_Db_Table("dbt_ministry_owner");
		$select = $dmo->select();
		$select->setIntegrityCheck(false);
		$select->from(array('ministry1' => 'dbt_ministry_owner'), array('id','ministry_id','scheme_name','dbt_eligibility','benefit_type','dbt_eligibility_type','specific_reason','status','created','updated'));	
		$select->joinLeft(array('ministry' => 'dbt_ministry'), 'ministry1.ministry_id = ministry.id', array('ministry.ministry_name as ministryname'));
		if($role->role == 6){
            $select->where("ministry1.ministry_id = ?" ,$ministry_id);
		}elseif($search){
			$select->where("ministry1.ministry_id = ?" ,$search);   
		}
		$select->where("ministry1.dbt_eligibility = ?" ,"1");
		$select->order("ministry1.id DESC");
		$select->where("ministry1.status = ?" ,"1");
		$select->order("ministry1.scheme_name")->limit($limit,$start);
		$dataset = $dmo->fetchAll($select);
		$resultant = $dataset->toArray();
		return array_filter($resultant);
	}
		/**************************end********************************/
		
		public function changedateformat($last_updated)
		{
	               if($last_updated!='--'){
						$date_time_array=explode(" ",$last_updated);
						$date_array=explode("-",$date_time_array[0]);
						$last_updated=$date_array[2]."/".$date_array[1]."/".$date_array[0]." ".$date_time_array[1];
						return $last_updated;
					}
					else
					{
						return 0;
					}
		}

	public function getschemesdata(){

		$role = new Zend_Session_Namespace('role');
		$select_table = new Zend_Db_Table('dbt_scheme_eligbility_assessment');
		$select = $select_table->select();
		$select->from(array('m_assessment'=>'dbt_scheme_eligbility_assessment'), array('id','name_of_scheme','target_beneficiary','digitized_beneficiary_status','mis_portal_status','aadhar_seeding_bd','bank_account_bd','mobile_number_bd','pfms_payment'));	
		$select->where("m_assessment.status=1"); 
		$select->order("m_assessment.id desc"); 
		$dataset = $select_table->fetchAll($select);   
		$result = $dataset->toArray();
		// print '<pre>';
		// print_r($result);
		// exit;
		return $result; 
		
	}

	public function getPhaseOneData($ministryid=null)
        {	
                $table_obj = new Zend_Db_Table("dbt_ministry_owner");
		$select = $table_obj->select();
		$select->setIntegrityCheck(false);
		$select->from(array('ministry' => 'dbt_ministry_owner'), array('id','ministry_id','scheme_name','created','updated'));	
		//$select->joinLeft(array('ministry' => 'dbt_ministry'), 'ministry1.ministry_id = ministry.id', array('ministry.ministry_name as ministryname'));
		if($ministryid){
			$select->where("ministry.ministry_id = ?" ,$ministryid);   
		}
		$select->order("ministry.scheme_name ASC");
		$select->where("ministry.status = ?" ,"1");
		$dataset = $dmo->fetchAll($select);
		$resultant = $dataset->toArray();
		return array_filter($resultant);
        }
	
	public function insertscoredata($dataform)
    {
		print '<pre>';
		print_r($dataform);
		exit;
	}
		
        
}
