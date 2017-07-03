<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Utbeneficiarydata extends Zend_Db_Table_Abstract 
{
	// protected $_name = 'roles';
		//Generate Table dynamically according to the title of scheme table 
		
		
		/***********report methods for uts*****************/
		
		public function getcountschemecategorywise($state_code = null,$schmecategory = null)
	        {
				//echo $schmecategory; die;
				
				 if ($state_code == '01') {
						$utname = 'andaman_nicobar';
					} else if ($state_code == '06') {
						$utname = 'delhi';
					} else if ($state_code == '07') {
						$utname = 'dadar_nagar_haveli';
					} else if ($state_code == '08') {
						$utname = 'daman_diu';
					} else if ($state_code == '09') {
						$utname = 'chandigarh';
					} else if ($state_code == '19') {
						$utname = 'lakshadweep';
					} else if ($state_code == '25') {
						$utname = 'puducherry';
					}
				$tablename = 'dbt_'.$utname.'_scheme';
				//echo $tablename; die;
				$select_table = new Zend_Db_Table($tablename);
				$select_query =  $select_table->select();
				$select_query->from(array('utfileupcount' => $tablename));		
				$select_query->where('utfileupcount.scheme_category=?',$schmecategory);	
				$select_query->where('utfileupcount.status=?',1);
				$rowlist = $select_table->fetchAll($select_query);
				//echo $select_query; die;
				return count($rowlist); 
				

				
				
			}
			
		
		public function getutlist()
	        {
				$select_table = new Zend_Db_Table('dbt_state');

				$rowselect = $select_table->fetchAll($select_table->select()->where('isstate = ?','no')->order('state_name ASC'));
				return $rowselect; 
			
			}
		
		public function getutlistbasedstate($statecode = null)
	        {
				$select_table = new Zend_Db_Table('dbt_state');

				$rowselect = $select_table->fetchAll($select_table->select()->where('isstate = ?','no')->order('state_name ASC'));
				return $rowselect; 
			
			}
	
		public function getutfileupdata($schemefiletable = null,$statecode = null,$month = null,$year = null)
	        {
				//echo "test"; die;
				$select_table = new Zend_Db_Table($schemefiletable);
				$select_query =  $select_table->select();
				$select_query->from(array('utfileupcount' => $schemefiletable));
				if($month != 0 && $year != 0){
					$select_query->where('utfileupcount.month=?',$month);
					$select_query->where('utfileupcount.year=?',$year);
				}
				$rowlist = $select_table->fetchAll($select_query);
				//echo $select_query; die;
				return count($rowlist); 
				
				
			}
			
			
			public function getutfilecontnt($schemefiletable = null,$statecode = null)
	        {
				//echo "test"; die;
				$select_table = new Zend_Db_Table($schemefiletable);
				$select_query =  $select_table->select();
				$select_query->from(array('utfileupcount' => $schemefiletable),array('month','scheme_id',"SUM(IF(utfileupcount.fund_transfer='APB', utfileupcount.amount, 0)) as totalamountapb","SUM(IF(utfileupcount.fund_transfer!='APB', utfileupcount.amount, 0)) as nonapbamount","sum(amount) as totalamount","sum(no_of_beneficiries) as totalnumbeneficiaries","sum(no_of_abp_beneficiries) as totalnumadharbasedbeneficiaries"));				
				$rowlist = $select_table->fetchAll($select_query);
				//echo $select_query; die;
				return $rowlist->toArray(); 
				
				
			}
			public function getdatamanuaalschemebased($schemeid = null,$state_code = null,$month = null,$year = null)
	        {
				   if ($state_code == '01') {
						$utname = 'andaman_nicobar';
					} else if ($state_code == '06') {
						$utname = 'delhi';
					} else if ($state_code == '07') {
						$utname = 'dadar_nagar_haveli';
					} else if ($state_code == '08') {
						$utname = 'daman_diu';
					} else if ($state_code == '09') {
						$utname = 'chandigarh';
					} else if ($state_code == '19') {
						$utname = 'lakshadweep';
					} else if ($state_code == '25') {
						$utname = 'puducherry';
					}
				    $tablename = 'dbt_scheme_manual_data_'.$utname;
				    $select_table = new Zend_Db_Table($tablename);
					$select_query =  $select_table->select();
					$select_query->from(array('schememanual' => $tablename),array("sum(total_fund_transfer ) as totalfundtransferschmmanual","sum(using_aadhar_bridge_payment) as totalamountapb","sum( without_aadhar_bridge_payment) as totalamountnonapb","sum( no_of_beneficiries_in_scheme) as totalnumoftransaction","sum( no_of_beneficiries_with_aadhar) as totalnumoftransactionaadhar"));					
					$select_query->where('schememanual.scheme_id=?',$schemeid);	
					if($month != 0 && $year != 0){
						$select_query->where('schememanual.month=?',$month);
						$select_query->where('schememanual.financial_year_from=?',$year);
					}
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray(); 
			}
			public function getuttablenm($schemeid = null,$state_code = null)
	        {
				
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				$tablename = 'dbt_'.$utname.'_scheme';
				$select_table = new Zend_Db_Table($tablename);
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($schemeid))));
				 return $rowselect->toArray(); 
			
			}
		
		//getting here all scheme name which are assign to scheme owner
		public function UTAssignedScheme($soId = null, $roleid = null, $state_code = null){
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
		if(($roleid == 4)||($roleid == 6)){
			$objTb = new Zend_Db_Table("dbt_assign_manager");
			$select = $objTb->select();
			$select->where("pm_id = ?",trim(intval($soId)));
			$result = $objTb->fetchRow($select);
		//calling here a function which return schemename passing by the assigned scheme id which is assigned to current pm	
			if(count($result['scheme_id']) > 0){
				//$schemes = $this->getScheme($result['scheme_id']);
				$sche = new Zend_Db_Table('dbt_'.$utname.'_scheme');
				$select = $sche->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_'.$utname.'_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type','scheme_category','pfms'))
				->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'))
				->where('l.id in (?)', explode(",",$result['scheme_id']))
				->where('l.translation_id !=1')
				->where('l.status = 1');
			     $select->order('l.scheme_category');
				 $select->order('l.scheme_name');
				//echo $select;exit;
				$schemes = $sche->fetchAll($select);
				
				return $schemes->toArray();
			}else{
				return "No record found!";
			}
			//print_r($result);exit;
			} else if($roleid == 1 || $roleid == 2){
					$objTb = new Zend_Db_Table("dbt_".$utname."_scheme");
					$select = $objTb->select();
					$select->setIntegrityCheck(false);
					$select->from(array('l' => 'dbt_'.$utname.'_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type','scheme_category','pfms'));
					//echo $select;exit;
					$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'));
					$select->where('l.translation_id != ?', 1);
					$select->where('l.status = ?', 1);
						//echo $select;exit;
					$select->order('l.scheme_category');
					$select->order('l.scheme_name');
					$schemes = $objTb->fetchAll($select);
					$schemecount = count($schemes->toArray());
					if ($schemecount != 0){
						return $schemes->toArray();
					} else {
						return "No record found!";
					}
				}
		}
//getting scheme owner scheme end here




/*********** get the ut beneficiary data from the ut beneficiary table****************/


			public function getbeneficiarydatautstatebased($schemeid = null,$state_code = null,$month = null,$year = null)
			{
				 
				  if ($state_code == '01') {
						$utname = 'andaman_nicobar';
					} else if ($state_code == '06') {
					    $utname = 'delhi';
					} else if ($state_code == '07') {
					    $utname = 'dadar_nagar_haveli';
					} else if ($state_code == '08') {
					    $utname = 'daman_diu';
					} else if ($state_code == '09') {
					   $utname = 'chandigarh';
					} else if ($state_code == '19') {
					   $utname = 'lakshadweep';
					} else if ($state_code == '25') {
					   $utname = 'puducherry';
					}
					$table = 'dbt_beneficaryscheme_'.$utname;
					$select_table = new Zend_Db_Table($table);
					$select_query =  $select_table->select();
					$select_query->from(array('ut' => $table),array('max(totalnoofbeneficiaries) as totalutbeneficiares','max(totalnoofbeneficiarieswithaadhaar) as totalutbeneficiaresaadhar'));
					$select_query->where('ut.scheme_id=?',$schemeid);
					if($month != 0 && $year != 0){
						$select_query->where('ut.month=?',$month);
						$select_query->where('ut.financial_year_from=?',$year);
					}
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray();
					
				
				
				
					/* if ($state_code == '01') {
						$utname = 'andaman_nicobar';
					} else if ($state_code == '06') {
					    $utname = 'delhi';
					} else if ($state_code == '07') {
					    $utname = 'dadar_nagar_haveli';
					} else if ($state_code == '08') {
					    $utname = 'daman_diu';
					} else if ($state_code == '09') {
					   $utname = 'chandigarh';
					} else if ($state_code == '19') {
					   $utname = 'lakshadweep';
					} else if ($state_code == '25') {
					   $utname = 'puducherry';
					}
					$table = 'dbt_beneficaryscheme_'.$utname;
					echo $table;
					$select_table = new Zend_Db_Table($table);
					$select_query =  $select_table->select();
					$select_query->from(array('ut' => $table),array('sum(totalnoofbeneficiaries) as totalutbeneficiares','sum(totalnoofbeneficiarieswithaadhaar) as totalutbeneficiaresaadhar'));	
					$select_query->where('scheme_id=?',$schemeid);	
					//$select_query->where('fund_transfer=?','APB');	
					echo $select_query; die;
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray() */



			}



/**********************end************************************************************/
		
		/****************end**********************/
		
		
		
		
		public function getministryid($schemeid)
	        {
                $select_table = new Zend_Db_Table('dbt_scheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($schemeid))));
				 return $rowselect->toArray();     
			
			}
			
			
			/**************edit record based in the beneficiary table*******/
	public function editbeneficary($editdataform = null,$id = null,$state_code = null)
	{
		
		
			 if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				$selectable = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);

						$data="";
						$where="";		
						$totalnoofbeneficiaries = $editdataform['totalnoofbeneficiaries'];
						$totalnoofbeneficiarieswithbankac = $editdataform['totalnoofbeneficiarieswithbankac'];	
						$totalnoofbeneficiarieswithaadhaar = $editdataform['totalnoofbeneficiarieswithaadhaar'];	
						$totalnoofbeneficiarieswithseededbankac = $editdataform['totalnoofbeneficiarieswithseededbankac'];
						$month = $editdataform['month'];						
						$data = array(
						  'totalnoofbeneficiaries' => $totalnoofbeneficiaries,
						  'totalnoofbeneficiarieswithbankac' => $totalnoofbeneficiarieswithbankac,
						  'totalnoofbeneficiarieswithaadhaar' => $totalnoofbeneficiarieswithaadhaar,
						  'totalnoofbeneficiarieswithseededbankac' => $totalnoofbeneficiarieswithseededbankac,
						    'month' => $month
						  

							);
						
						$where = array('id = ?'=> $id);
						$update_values = $selectable->update($data,$where);
						
						return $update_values;
	}
   
	/**********************end************************************/
	
			
			public function insertschemebeneficarydata($dataform = null,$scheme_id = null,$min_id  = null,$state_code = null) //insert scheme manual data
			{
			 
			 
			 
			 if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
			 
						$data_table = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);
						$datainsert="";
						$year_val = $dataform['year'];
						$yearval = explode("-", $year_val);
						$financial_year_from = $yearval[0];
						$financial_year_to = $yearval[1];
					

						$datainsert = array(
									'scheme_id' => $scheme_id,
									'ministryid' => $min_id,
									'scheme_grouping' => '',
									'totalnoofbeneficiaries' => $dataform['total_num_of_beneficary'],
									'totalnoofbeneficiarieswithbankac' => $dataform['total_num_of_beneficary_with_bank_ac'],
									'totalnoofbeneficiarieswithaadhaar' => $dataform['total_num_of_beneficary_with_aadhaar'],
									'totalnoofbeneficiarieswithseededbankac' => $dataform['total_num_of_beneficary_with_with_seeded_bankac'],
									'month'=> $dataform['month'],
									'financial_year_from'=> $financial_year_from,
									'financial_year_to'=> $financial_year_to,
									'status'=> 1							
											);
							 $insertdata=$data_table->insert($datainsert);
							return $insertdata;
			}
			
			/*************count the record from the beneficiary table based on the schmeme_id,month and year****/
public function countbeneficiarydatamonthyearwise($scheme_id = null,$year = null,$month  = null,$statecode = null)
		{
		
			
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
			
			$yearval = explode("-", $year);
			$financial_year_from = $yearval[0];
			$financial_year_to = $yearval[1];
			$select_table = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);
			$select_query =  $select_table->select();
			$select_query->from(array('beneficiary' => 'dbt_beneficaryscheme_'.$utname));		
			$select_query->where('scheme_id=?',$scheme_id);	
			$select_query->where('financial_year_from=?',$financial_year_from);	
			$select_query->where('financial_year_to=?',$financial_year_to);	
			$select_query->where('month=?',$month);	
			$rowlist = $select_table->fetchAll($select_query);
			//echo $select_query; die;
			return count($rowlist); 				
		}
	/******************end**********************************/
        public function findSchemeTableList($financial_year){
                $db = Zend_Db_Table::getDefaultAdapter();
                //$result = $db->fetchAll('show tables');
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type"));
                $select->where("sch.status =?","1");
                $select->where("sch.language =?","2");//only english
                $scheme_record = $newscm->fetchAll($select);
                $scheme_table=array();
                foreach($scheme_record->toArray() as $scheme_val)
                {
                    $scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;
                }
                
                $db = Zend_Db_Table::getDefaultAdapter();
                $result = $db->fetchAll('show tables');
                $table_list=array();
                foreach($result as $val)
                {
                    $table_list[]= $val['Tables_in_dbt'];                  
                }
                return array_intersect($table_list, $scheme_table);  
        }
		public function getmonthname($month) //insert scheme manual data
			{
				if ($month == '01') {$monthtitle = "january";}
				else if ($month == '02') {$monthtitle = "february";}
				else if ($month == '03') {$monthtitle = "march";}
				else if ($month == '04') {$monthtitle = "april";}
				else if ($month == '05') {$monthtitle = "may";}
				else if ($month == '06') {$monthtitle = "june";}
				else if ($month == '07') {$monthtitle = "july";}
				else if ($month == '08') {$monthtitle = "august";}
				else if ($month == '09') {$monthtitle = "september";}
				else if ($month == '10') {$monthtitle = "october";}
				else if ($month == '11') {$monthtitle = "november";}
				else if ($month == '12') {$monthtitle = "december";}	
				return $monthtitle;
			}
		public function insertschememanualdata($dataform,$state_code = null) //insert scheme manual data
			{
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}

				$data_table = new Zend_Db_Table('dbt_scheme_manual_data_'.$utname);
				$datainsert="";
				$year_val = $dataform['year'];
				$yearval = explode("-", $year_val);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];
				
				$select = $data_table->select();
				$select->where('financial_year_from = ?', $financial_year_from);
				$select->where('financial_year_to = ?', $financial_year_to);
				$select->where('month = ?', $dataform['month']);
				$select->where('scheme_id = ?', $dataform['scheme_id']);
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				
				if ($recordcount > 0) {
						return 'alreadyexist';
					} else {
						$datainsert = array(
									'scheme_id'=> $dataform['scheme_id'],
									'no_of_beneficiries_in_scheme'=> $dataform['no_of_beneficiries_in_scheme'],
									'no_of_beneficiries_with_aadhar'=> $dataform['no_of_beneficiries_with_aadhar'],
									'total_fund_transfer'=> $dataform['total_fund_transfer'],
									'using_aadhar_bridge_payment'=> $dataform['using_aadhar_bridge_payment'],
									'without_aadhar_bridge_payment'=> $dataform['total_fund_transfer'] - $dataform['using_aadhar_bridge_payment'],
									'saving'=> $dataform['saving'],
									'saving_prev'=> $dataform['saving'],
									'month'=> $dataform['month'],
									'financial_year_from'=> $financial_year_from,
									'financial_year_to'=> $financial_year_to,
									'status'=> 1							
											);
							 $insertdata=$data_table->insert($datainsert);
							return $insertdata;
					}
				}
		public function insertmasterdatafinancialyear($total_fund_transfer_data,$no_of_beneficiries,$aadhar_based_payment,$without_aadhar_based_payment,$savingdata,$scheme_type) //insert scheme saving data in masterdata financial year
			{

				$data_table = new Zend_Db_Table('dbt_home_page_master_data_current_year');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				
				if ($recordcount == 0) {
					if ($scheme_type == 1) {
						$total_beneficiary_in_cash = $no_of_beneficiries;	
					} else {
						$total_beneficiary_in_cash = 0;
					}
					if ($scheme_type == 2) {
						$total_beneficiary_in_kind = $no_of_beneficiries;	
					} else {
						$total_beneficiary_in_kind = 0;
					}
					if ($scheme_type == 3) {
						$total_beneficiary_other = $no_of_beneficiries;	
					} else {
						$total_beneficiary_other = 0;
					}

					if($total_fund_transfer_data == ''){$total_fund_transfer_data = 0;}
					if($no_of_beneficiries == ''){$no_of_beneficiries = 0;}
					if($aadhar_based_payment == ''){$aadhar_based_payment = 0;}
					if($without_aadhar_based_payment == ''){$without_aadhar_based_payment = 0;}
					if($savingdata == ''){$savingdata = 0;}

					$datainsert="";
					$datainsert = array(
						'total_fund_transfer'=> $total_fund_transfer_data,
						'total_benificary'=> $no_of_beneficiries,
						'aadhar_based_fund'=> $aadhar_based_payment,
						'non_aadhar_based_fund'=> $without_aadhar_based_payment,
						'beneficiary_in_cash'=> $total_beneficiary_in_cash,
						'beneficiary_in_kind'=> $total_beneficiary_in_kind,
						'beneficiary_other'=> $total_beneficiary_other,
						'total_saving'=> $savingdata,
						'status'=> 1
					);
					$insertdata=$data_table->insert($datainsert);
					return $insertdata;
				} else {
					$data = $select_org->toArray();
					$total_fund_transfer_data_val = $data[0]['total_fund_transfer'] + $total_fund_transfer_data;
					$total_no_of_beneficiries = $data[0]['total_benificary'] + $no_of_beneficiries;
					$total_aadhar_based_payment = $data[0]['aadhar_based_fund'] + $aadhar_based_payment;
					$total_without_aadhar_based_payment = $data[0]['non_aadhar_based_fund'] + $without_aadhar_based_payment;
					$total_saving_data = $data[0]['total_saving'] + $savingdata;
					if ($scheme_type == 1) {
						$total_beneficiary_in_cash = $data[0]['beneficiary_in_cash'] + $no_of_beneficiries;	
					} else {
						$total_beneficiary_in_cash = $data[0]['beneficiary_in_cash'];
					}
					if ($scheme_type == 2) {
						$total_beneficiary_in_kind = $data[0]['beneficiary_in_kind'] + $no_of_beneficiries;	
					} else {
						$total_beneficiary_in_kind = $data[0]['beneficiary_in_kind'];
					}
					if ($scheme_type == 3) {
						$total_beneficiary_other = $data[0]['beneficiary_other'] + $no_of_beneficiries;	
					} else {
						$total_beneficiary_other = $data[0]['beneficiary_other'];
					}

					if($total_fund_transfer_data_val == ''){$total_fund_transfer_data_val = 0;}
					if($total_no_of_beneficiries == ''){$total_no_of_beneficiries = 0;}
					if($total_aadhar_based_payment == ''){$total_aadhar_based_payment = 0;}
					if($total_without_aadhar_based_payment == ''){$total_without_aadhar_based_payment = 0;}
					if($total_beneficiary_in_cash == ''){$total_beneficiary_in_cash = 0;}
					if($total_beneficiary_in_kind == ''){$total_beneficiary_in_kind = 0;}
					if($total_beneficiary_other == ''){$total_beneficiary_other = 0;}
					if($total_saving_data == ''){$total_saving_data = 0;}

					$data="";
					$data = array('total_fund_transfer'=> $total_fund_transfer_data_val,'total_benificary'=> $total_no_of_beneficiries,'aadhar_based_fund'=> $total_aadhar_based_payment,'non_aadhar_based_fund'=> $total_without_aadhar_based_payment,'beneficiary_in_cash'=> $total_beneficiary_in_cash,'beneficiary_in_kind'=> $total_beneficiary_in_kind,'beneficiary_other'=> $total_beneficiary_other,'total_saving'=> $total_saving_data);
					$update_values = $data_table->update($data);
					return $data;
				}
			}

		public function updatemasterdatafinancialyear($total_fund_transfer_data,$total_benificary_data,$total_aadhar_payment_data,$total_wothout_aadhar_data,$savingdata,$updated_total_fund_transfer,$updated_benificary_data,$updated_aadhar_payment_data,$updated_wothout_aadhar_data,$updatedsavingdata,$scheme_type,$month) //insert scheme saving data in masterdata financial year
			{
				$data_table = new Zend_Db_Table('dbt_home_page_master_data_current_year');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
								
				$data = $select_org->toArray();

				$total_fund_transfer_data_val = $data[0]['total_fund_transfer'] + $updated_total_fund_transfer;
				$total_fund_transfer_data_val = $total_fund_transfer_data_val - $total_fund_transfer_data;

				$total_benificary_data_val = $data[0]['total_benificary'] + $updated_benificary_data;
				$total_benificary_data_val = $total_benificary_data_val - $total_benificary_data;

				$total_aadhar_payment_data_val = $data[0]['aadhar_based_fund'] + $updated_aadhar_payment_data;
				$total_aadhar_payment_data_val = $total_aadhar_payment_data_val - $total_aadhar_payment_data;

				$total_wothout_aadhar_data_val = $data[0]['non_aadhar_based_fund'] + $updated_wothout_aadhar_data;
				$total_wothout_aadhar_data_val = $total_wothout_aadhar_data_val - $total_wothout_aadhar_data;

				$total_saving_data = $data[0]['total_saving'] + $updatedsavingdata;
				$total_saving_data = $total_saving_data - $savingdata;

				if ($scheme_type == 1) {
					$total_beneficiary_in_cash = $data[0]['beneficiary_in_cash'] + $updated_benificary_data;
					$total_beneficiary_in_cash = $total_beneficiary_in_cash - $total_benificary_data;	
				} else {
					$total_beneficiary_in_cash = $data[0]['beneficiary_in_cash'];
				}
				if ($scheme_type == 2) {
					$total_beneficiary_in_kind = $data[0]['beneficiary_in_kind'] + $updated_benificary_data;
					$total_beneficiary_in_kind = $total_beneficiary_in_kind - $total_benificary_data;	
				} else {
					$total_beneficiary_in_kind = $data[0]['beneficiary_in_kind'];
				}
				if ($scheme_type == 3) {
					$total_beneficiary_other = $data[0]['beneficiary_other'] + $updated_benificary_data;
					$total_beneficiary_other = $total_beneficiary_other - $total_benificary_data;	
				} else {
					$total_beneficiary_other = $data[0]['beneficiary_other'];
				}

					if($total_fund_transfer_data_val == ''){$total_fund_transfer_data_val = 0;}
					if($total_benificary_data_val == ''){$total_benificary_data_val = 0;}
					if($total_aadhar_payment_data_val == ''){$total_aadhar_payment_data_val = 0;}
					if($total_wothout_aadhar_data_val == ''){$total_wothout_aadhar_data_val = 0;}
					if($total_saving_data == ''){$total_saving_data = 0;}
					if($total_beneficiary_in_cash == ''){$total_beneficiary_in_cash = 0;}
					if($total_beneficiary_in_kind == ''){$total_beneficiary_in_kind = 0;}
					if($total_beneficiary_other == ''){$total_beneficiary_other = 0;}

				$data="";
				$data = array('total_fund_transfer'=> $total_fund_transfer_data_val,'total_benificary'=> $total_benificary_data_val,'aadhar_based_fund'=> $total_aadhar_payment_data_val,'non_aadhar_based_fund'=> $total_wothout_aadhar_data_val,'total_saving'=> $total_saving_data,'beneficiary_in_cash'=> $total_beneficiary_in_cash,'beneficiary_in_kind'=> $total_beneficiary_in_kind,'beneficiary_other'=> $total_beneficiary_other);
				$update_values = $data_table->update($data);
				return $data;
			}

		//Insert month wise fund transfer data (dbt_report_month_wise_fund_transfer)
		public function insertMonthWiseFundTransfer($aadhar_based_payment,$without_aadhar_based_payment,$month) 
			{
				$monthtitle = $this->getmonthname($month);
				$aadharbasedfundfield = 'aadhar_based_fund_'.$monthtitle;
				$nonaadharbasedfundfield = 'non_aadhar_based_fund_'.$monthtitle;

				$data_table = new Zend_Db_Table('dbt_report_month_wise_fund_transfer');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				
				if ($recordcount == 0) {

					if($aadhar_based_payment == ''){$aadhar_based_payment = 0;}
					if($without_aadhar_based_payment == ''){$without_aadhar_based_payment = 0;}

					$datainsert="";
					$datainsert = array(
						$aadharbasedfundfield => $aadhar_based_payment,
						$nonaadharbasedfundfield => $without_aadhar_based_payment
					);
					$insertdata=$data_table->insert($datainsert);
					return $insertdata;
				} else {
					$data = $select_org->toArray();
					$aadhar_based_payment_val = $data[0][$aadharbasedfundfield] + $aadhar_based_payment;
					$without_aadhar_based_payment_val = $data[0][$nonaadharbasedfundfield] + $without_aadhar_based_payment;

					if($aadhar_based_payment_val == ''){$aadhar_based_payment_val = 0;}
					if($without_aadhar_based_payment_val == ''){$without_aadhar_based_payment_val = 0;}

					$data="";
					$data = array($aadharbasedfundfield => $aadhar_based_payment_val, $nonaadharbasedfundfield => $without_aadhar_based_payment_val);
					$update_values = $data_table->update($data);
					return $data;
				}
			}

		//Update month wise fund transfer data (dbt_report_month_wise_fund_transfer)
		public function updateMonthWiseFundTransfer($total_aadhar_payment_data,$total_wothout_aadhar_data,$updated_aadhar_payment_data,$updated_wothout_aadhar_data,$month) 
			{
				$monthtitle = $this->getmonthname($month);
				$aadharbasedfundfield = 'aadhar_based_fund_'.$monthtitle;
				$nonaadharbasedfundfield = 'non_aadhar_based_fund_'.$monthtitle;

				$data_table = new Zend_Db_Table('dbt_report_month_wise_fund_transfer');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
				$data = $select_org->toArray();

				$total_aadhar_payment_data_val = $data[0][$aadharbasedfundfield] + $updated_aadhar_payment_data;
				$total_aadhar_payment_data_val = $total_aadhar_payment_data_val - $total_aadhar_payment_data;

				$total_wothout_aadhar_data_val = $data[0][$nonaadharbasedfundfield] + $updated_wothout_aadhar_data;
				$total_wothout_aadhar_data_val = $total_wothout_aadhar_data_val - $total_wothout_aadhar_data;

					if($total_aadhar_payment_data_val == ''){$total_aadhar_payment_data_val = 0;}
					if($total_wothout_aadhar_data_val == ''){$total_wothout_aadhar_data_val = 0;}

				$data="";
				$data = array($aadharbasedfundfield => $total_aadhar_payment_data_val,$nonaadharbasedfundfield => $total_wothout_aadhar_data_val);
				$update_values = $data_table->update($data);
				return $data;
			}

		//Insert month wise beneficiary seeded with aadhar data (dbt_report_month_wise_beneficiery_seeded)
		public function insertMonthWiseBeneficiarySeeded($no_of_beneficiries,$no_of_beneficiries_with_aadhar_data,$month) 
			{
				$monthtitle = $this->getmonthname($month);
				$aadharseededfield = 'aadhar_seeded_'.$monthtitle;
				$withoutaadharseededfield = 'without_aadhar_seeded_'.$monthtitle;
				
				$aadhar_seeded_data = $no_of_beneficiries_with_aadhar_data;
				$without_aadhar_seeded_data = $no_of_beneficiries - $no_of_beneficiries_with_aadhar_data;

				$data_table = new Zend_Db_Table('dbt_report_month_wise_beneficiery_seeded');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				
				if ($recordcount == 0) {

					if($aadhar_seeded_data == ''){$aadhar_seeded_data = 0;}
					if($without_aadhar_seeded_data == ''){$without_aadhar_seeded_data = 0;}

					$datainsert="";
					$datainsert = array(
						$aadharseededfield => $aadhar_seeded_data,
						$withoutaadharseededfield => $without_aadhar_seeded_data
					);
					$insertdata=$data_table->insert($datainsert);
					return $insertdata;
				} else {
					$data = $select_org->toArray();
					$aadhar_seeded_data_val = $data[0][$aadharseededfield] + $aadhar_seeded_data;
					$without_aadhar_seeded_data_val = $data[0][$withoutaadharseededfield] + $without_aadhar_seeded_data;

					if($aadhar_seeded_data_val == ''){$aadhar_seeded_data_val = 0;}
					if($without_aadhar_seeded_data_val == ''){$without_aadhar_seeded_data_val = 0;}

					$data="";
					$data = array($aadharseededfield => $aadhar_seeded_data_val, $withoutaadharseededfield => $without_aadhar_seeded_data_val);
					$update_values = $data_table->update($data);
					return $data;
				}
			}

		//Update month wise beneficiary seeded with aadhar data (dbt_report_month_wise_beneficiery_seeded)
		 public function updateMonthWiseBeneficiarySeeded($total_benificary_data,$total_benificary_with_aadhar_data,$updated_benificary_data,$updated_beneficiries_with_aadhar_data,$month) 
			 {
				 $monthtitle = $this->getmonthname($month);
				 $aadharseededfield = 'aadhar_seeded_'.$monthtitle;
				 $withoutaadharseededfield = 'without_aadhar_seeded_'.$monthtitle;

				 $data_table = new Zend_Db_Table('dbt_report_month_wise_beneficiery_seeded');
				 $select = $data_table->select();
				 $select_org = $data_table->fetchAll($select);
				 $data = $select_org->toArray();

				 $aadhar_seeded_data = $total_benificary_with_aadhar_data;
				 $without_aadhar_seeded_data = $total_benificary_data - $total_benificary_with_aadhar_data;
				 $updated_aadhar_seeded_data = $updated_beneficiries_with_aadhar_data;
				 $updated_without_aadhar_seeded_data = $updated_benificary_data - $updated_beneficiries_with_aadhar_data;
				 
				 $aadhar_seeded_data_val = $data[0][$aadharseededfield] + $updated_aadhar_seeded_data;
				 $aadhar_seeded_data_val = $aadhar_seeded_data_val - $aadhar_seeded_data;

				 $without_aadhar_seeded_data_val = $data[0][$withoutaadharseededfield] + $updated_without_aadhar_seeded_data;
				 $without_aadhar_seeded_data_val = $without_aadhar_seeded_data_val - $without_aadhar_seeded_data;

					if($aadhar_seeded_data_val == ''){$aadhar_seeded_data_val = 0;}
					if($without_aadhar_seeded_data_val == ''){$without_aadhar_seeded_data_val = 0;}

				 $data="";
				 $data = array($aadharseededfield => $aadhar_seeded_data_val,$withoutaadharseededfield => $without_aadhar_seeded_data_val);
				 $update_values = $data_table->update($data);
				 return $data;
			 }

		//Insert month wise beneficiary category data (dbt_report_month_wise_beneficary_category)
		public function insertMonthWiseCategory($no_of_beneficiries,$month,$scheme_type) 
			{
				 $monthtitle = $this->getmonthname($month);
				 if ($scheme_type == 1) {
					$beneficiary_field = 'beneficiary_in_cash_'.$monthtitle;	
				 } else if ($scheme_type == 2) {
					$beneficiary_field = 'beneficiary_in_kind_'.$monthtitle;	
				 } else if ($scheme_type == 3) {
					$beneficiary_field = 'beneficiary_others_'.$monthtitle;	
				 }

				$data_table = new Zend_Db_Table('dbt_report_month_wise_beneficary_category');
				$select = $data_table->select();
				$select_org = $data_table->fetchAll($select);
				$recordcount = count($select_org);
				
				if ($recordcount == 0) {

					if($no_of_beneficiries == ''){$no_of_beneficiries = 0;}

					$datainsert="";
					$datainsert = array(
						$beneficiary_field => $no_of_beneficiries
					);
					$insertdata=$data_table->insert($datainsert);
					return $insertdata;
				} else {
					$data = $select_org->toArray();
					$no_of_beneficiries_val = $data[0][$beneficiary_field] + $no_of_beneficiries;

					if($no_of_beneficiries_val == ''){$no_of_beneficiries_val = 0;}

					$data="";
					$data = array($beneficiary_field => $no_of_beneficiries_val);
					$update_values = $data_table->update($data);
					return $data;
				}
			}
		//Update month wise beneficiary category data (dbt_report_month_wise_beneficary_category)
		 public function updateMonthWiseBeneficiaryCategory($total_benificary_data,$updated_benificary_data,$scheme_type,$month) 
			 {
				 $monthtitle = $this->getmonthname($month);
				 if ($scheme_type == 1) {
					$beneficiary_field = 'beneficiary_in_cash_'.$monthtitle;	
				 } else if ($scheme_type == 2) {
					$beneficiary_field = 'beneficiary_in_kind_'.$monthtitle;	
				 } else if ($scheme_type == 3) {
					$beneficiary_field = 'beneficiary_others_'.$monthtitle;	
				 }

				 $data_table = new Zend_Db_Table('dbt_report_month_wise_beneficary_category');
				 $select = $data_table->select();
				 $select_org = $data_table->fetchAll($select);
				 $data = $select_org->toArray();

				 $total_benificary_data_val = $data[0][$beneficiary_field] + $updated_benificary_data;
				 $total_benificary_data_val = $total_benificary_data_val - $total_benificary_data;

					if($total_benificary_data_val == ''){$total_benificary_data_val = 0;}

				 $data="";
				 $data = array($beneficiary_field => $total_benificary_data_val);
				 $update_values = $data_table->update($data);
				 return $data;
			 }

		public function editschememanualdatadetails($editdataform,$id,$savingdata,$state_code = null)
	        {
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				
				$updatedetails_selecttable = new Zend_Db_Table('dbt_scheme_manual_data_'.$utname);

						$yearval = explode("-", $editdataform['year']);
						$financial_year_from = $yearval[0];
						$financial_year_to = $yearval[1];

						$select = $updatedetails_selecttable->select();
						$select->where('financial_year_from = ?', $financial_year_from);
						$select->where('financial_year_to = ?', $financial_year_to);
						$select->where('month = ?', $editdataform['month']);
						$select->where('scheme_id = ?', $editdataform['scheme_id']);
						$select->where('id != ?', $id);
						$select_org = $updatedetails_selecttable->fetchAll($select);
						$recordcount = count($select_org);

						if ($recordcount > 0) {
							return 'alreadyexist';
						} else {
							$data="";
							$where="";
							$data = array('financial_year_from'=> $financial_year_from, 'financial_year_to'=> $financial_year_to, 'month'=> $editdataform['month'], 'no_of_beneficiries_in_scheme'=> $editdataform['no_of_beneficiries_in_scheme'], 'no_of_beneficiries_with_aadhar'=> $editdataform['no_of_beneficiries_with_aadhar'], 'total_fund_transfer'=> $editdataform['total_fund_transfer'], 'using_aadhar_bridge_payment'=> $editdataform['using_aadhar_bridge_payment'], 'without_aadhar_bridge_payment'=> $editdataform['total_fund_transfer'] - $editdataform['using_aadhar_bridge_payment'], 'saving'=> $editdataform['saving'], 'saving_prev'=> $savingdata, 'status'=> $editdataform['status']);
							$where = array('id = ?'=> $id);
							$update_values = $updatedetails_selecttable->update($data, $where);
						}
		}
		public function getschemename($scheme_id, $state_code = null){
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				
				$select_table = new Zend_Db_Table('dbt_'.$utname.'_scheme');
				$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
				return $row;
		}

		public function schememanualdatalist($start,$limit)
			{   
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid'); 
				$user_role = $role->role;
				
				$month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];

				$select_table = new Zend_Db_Table('dbt_scheme_manual_data');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);

				$select->from(array('smd' => 'dbt_scheme_manual_data'), array('id','scheme_id','no_of_beneficiries_in_scheme','no_of_beneficiries_with_aadhar','total_fund_transfer','using_aadhar_bridge_payment','without_aadhar_bridge_payment','saving','month','financial_year_from','financial_year_to','status'));			
				$select->joinLeft(array('sn' => 'dbt_scheme'), 'smd.scheme_id = sn.id', array('scheme_name'));
				if($month && $month != 0){
					$select->where('month = ?', $month);
				}				
				if($financial_year_from && $financial_year_from != 0){
					$select->where('financial_year_from = ?', $financial_year_from);
				}
				if($financial_year_to && $financial_year_to != 0){
					$select->where('financial_year_to = ?', $financial_year_to);
				}
				$select->limit($limit,$start);
				$select_org = $select_table->fetchAll($select);
				return $select_org;
			}

			public function schemedatalist($start,$limit,$scheme_id,$state_code)
			{   
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid'); 
				$user_role = $role->role;
				
				$month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];

				$select_table = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);
				$select = $select_table->select();
				$select->setIntegrityCheck(false);

				$select->from(array('smd' => 'dbt_beneficaryscheme_'.$utname), array('id','scheme_id','ministryid','scheme_grouping','totalnoofbeneficiaries','totalnoofbeneficiarieswithbankac','totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiarieswithseededbankac','financial_year_from','financial_year_to','status','month'));			
				$select->joinLeft(array('sn' => 'dbt_scheme'), 'smd.scheme_id = sn.id', array('scheme_name'));
				$select->where('scheme_id = ?', $scheme_id);
				if($month && $month != 0){
					$select->where('month = ?', $month);
				}				
				if($financial_year_from && $financial_year_from != 0){
					$select->where('financial_year_from = ?', $financial_year_from);
				}
				if($financial_year_to && $financial_year_to != 0){
					$select->where('financial_year_to = ?', $financial_year_to);
				}
				$select->order('financial_year_from DESC')->order('month ASC')->limit($limit,$start);
				$select_org = $select_table->fetchAll($select);
				return $select_org;
			}


			public function countschememanualdata()
			{
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid');
				$user_role = $role->role;
				
				$month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];

				$select_table = new Zend_Db_Table('dbt_scheme_manual_data');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('smd' => 'dbt_scheme_manual_data'), array('id','no_of_beneficiries_in_scheme','no_of_beneficiries_with_aadhar','total_fund_transfer','using_aadhar_bridge_payment','without_aadhar_bridge_payment','month','financial_year_from','financial_year_to','status'));
				$select->joinLeft(array('sn' => 'dbt_scheme'), 'smd.scheme_id = sn.id', array('scheme_name'));
				if($month && $month != 0){
					$select->where('month = ?', $month);
				}				
				if($financial_year_from && $financial_year_from != 0){
					$select->where('financial_year_from = ?', $financial_year_from);
				}
				if($financial_year_to && $financial_year_to != 0){
					$select->where('financial_year_to = ?', $financial_year_to);
				}
				$select_org = $select_table->fetchAll($select);

				  return count($select_org); 
			}
			
			public function countschemedata($scheme_id,$state_code)
			{
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				
				$role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid'); 
				$user_role = $role->role;
				
				$month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];

				$select_table = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('smd' => 'dbt_beneficaryscheme_'.$utname),array('id','scheme_id','ministryid','scheme_grouping','totalnoofbeneficiaries','totalnoofbeneficiarieswithbankac','totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiarieswithseededbankac','financial_year_from','financial_year_to','status','month'));			
				$select->where('scheme_id = ?', $scheme_id);
				
				if($month && $month != 0){
					$select->where('month = ?', $month);
				}				
				if($financial_year_from && $financial_year_from != 0){
					$select->where('financial_year_from = ?', $financial_year_from);
				}
				if($financial_year_to && $financial_year_to != 0){
					$select->where('financial_year_to = ?', $financial_year_to);
				} 

				$select_org = $select_table->fetchAll($select);

				  return count($select_org); 
			}
			
			public function editschememanualdataclient($id, $state_code)
	        {
				if ($state_code == '01') {
					$utname = 'andaman_nicobar';
				} else if ($state_code == '06') {
					$utname = 'delhi';
				} else if ($state_code == '07') {
					$utname = 'dadar_nagar_haveli';
				} else if ($state_code == '08') {
					$utname = 'daman_diu';
				} else if ($state_code == '09') {
					$utname = 'chandigarh';
				} else if ($state_code == '19') {
					$utname = 'lakshadweep';
				} else if ($state_code == '25') {
					$utname = 'puducherry';
				}
				
                $select_table = new Zend_Db_Table('dbt_beneficaryscheme_'.$utname);
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
			public function deleteschememanualdata($id)
			{
					$delete_data = new Zend_Db_Table('dbt_scheme_manual_data');
					$where="";
					$where = array('id = ?'      => $id);
					$delete_values = $delete_data->delete($where);

			}
			public function inactiveschemedata($schemedataIds,$sttaus)
			{ 
					$updatedetails_selecttable = new Zend_Db_Table('dbt_scheme_manual_data');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $schemedataIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}
			public function tablecheckindb($tablename = null, $financial_year)
			{
				$schemetablecheck = $this->findSchemeTableList($financial_year);
				if (in_array($tablename, $schemetablecheck))
					  {
						//$scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9 ]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;

							$newscm = new Zend_Db_Table($tablename);
							$select = $newscm->select();
							$select->from(array("sch" => $tablename),array("count(id) as countrow"));

							$scheme_record = $newscm->fetchAll($select);

							$data = $scheme_record->toArray();

							return $data[0]['countrow'];
						
					  } else {
							return 0;
						}
                
			}
			public function checkasignedschemeid($userid,$scheme_id){

				$select_table = new Zend_Db_Table('dbt_assign_manager');
				$row = $select_table->fetchAll($select_table->select()->where("find_in_set(".$scheme_id.", scheme_id) and pm_id=".$userid));
				return count($row);
			}
}
