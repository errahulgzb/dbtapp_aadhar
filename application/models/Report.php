<?php 
require_once "Zend/Db/Table/Abstract.php";
Class Application_Model_Report extends Zend_Db_Table_Abstract {
	
        public function commonData(){
		//this will dissplay for the home page content
		$selectTb = new Zend_DB_Table("dbt_home_page_master_data_current_year");
		$select = $selectTb->select();
		$data = $selectTb->fetchRow($select);
		// echo "<pre>";
		// 	print_r($data->toArray());
		// echo "<pre>";
		// exit;
			return $data->toArray();
	}
	//this is use for the shoe first chart of adhar and non adhar based fund transfer
	public function paymentWise(){
		$selectTb = new Zend_DB_Table("dbt_report_month_wise_fund_transfer");
		$select = $selectTb->select();
		//echo $select;exit;
		$data = $selectTb->fetchRow($select);
		// echo "<pre>";
		// 	print_r($data->toArray());
		// echo "<pre>";
		// exit;
		return $data;
	}
	//this function will return seeded beneficiery
	public function paymentWiseseeded(){
		$selectTb = new Zend_DB_Table("dbt_report_month_wise_beneficiery_seeded");
		$select = $selectTb->select();
		$data = $selectTb->fetchRow($select);
		return $data;
	}
	//this function will return seeded beneficiery end
	
	//finding here all the state district and villages
		public function cummalativedata(){
			
			//find the district
			$selectTbdist = new Zend_DB_Table("dbt_district");
			$select = $selectTbdist->select();
			$datadist = $selectTbdist->fetchAll($select);
			//find the district
			//find the state
			$selectTbstate = new Zend_DB_Table("dbt_state");
			$select = $selectTbstate->select();
			$datastate = $selectTbstate->fetchAll($select);
			//find the state
			
			//find the state
			$selectTbvill = new Zend_DB_Table("dbt_village");
			$select = $selectTbvill->select();
			$datavill = $selectTbvill->fetchAll($select);
			//find the state
			$countreturn = array('state' => count($datastate), 'district' => count($datadist), 'village' => count($datavill));
			return $countreturn;
			
			// echo "<pre>";
			// print_r($countreturn);
			// echo "</pre>";
			// exit;
		}
	//find all the district, village and state
	function monthseededdata(){
		$selectTbdist = new Zend_DB_Table("dbt_report_month_wise_beneficary_category");
			$select = $selectTbdist->select();
			$datadist = $selectTbdist->fetchRow($select);
			return $datadist;
	}
	public function geteAllData($state_code = null){
		//echo "aaaa";exit;
		$schemeObj = new Zend_DB_Table("dbt_scheme");
		$objSele = $schemeObj->select();
		$objSele->from(array("dbt_scheme"),array("id","scheme_name"));
		$objSele->where("translation_id !=?","1");
		//$objSele->ORwhere("translation_id =?","0");
		$data = $schemeObj->fetchAll($objSele);
		$pureArray = $data->toArray();
		
		$curre_year = strtotime(date("d-m-Y"));          
		$fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
		if($curre_year > $fixedyear){
			$start = date("Y");
			$getend = $start + 1;
			$end_date = $getend;
		}
		if(count($pureArray) > 0){
				foreach($pureArray as $k=>$v){
					$tb_name = str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower($v['scheme_name'])))."_".$v['id']."_".$start."_".$end_date;
					//$tb_name = "mnrega_60_2016_2017";
					//echo $data1;exit;
					$schemeObj1 = new Zend_DB_Table($tb_name);
					$objSele1 = $schemeObj1->select();
					$objSele1->from(array($tb_name),array("cr_ammount"));
					$data1 = $schemeObj1->fetchAll($objSele1);
					$pureArray1 = $data1->toArray();
					//print_r($pureArray1);
				}
				//exit;
		}
		
		
		// echo "<pre>";
		// print_r($pureArray);
		// echo "<pre>";exit;
		
		//echo $objSele;exit;
	}
        
        
        //Get total transaction amount of state wise
        public function getReportStateWise(){
		//$search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_mnrega_60_2016_2017');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => 'dbt_mnrega_60_2016_2017'), array('sum(scheme.amount) as total_transfer','count(scheme.id) as total_beneficiary'));				
                $select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                $select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                //$select->where('district.status = ?', '1');
                //$select->order('district.id DESC')->limit($limit,$start);
                $select->group('mapp.state_code');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org;
	}
        
        /*
        //Get total transaction amount of district wise
        public function getReportDistrictWise($state_code){
		//$search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_mnrega_60_2016_2017');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => 'dbt_mnrega_60_2016_2017'), array('sum(scheme.cr_amount) as total_transfer'));				
                $select->join(array('state_mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = state_mapp.scheme_state_code', array('state_code as state_code'));
                 $select->join(array('state' => 'dbt_state'), 'state_mapp.state_code = state.state_code', array('state_name'));
                $select->where('state_mapp.state_code = ?', $state_code);
                //$select->order('district.id DESC')->limit($limit,$start);
                $select->group('state_mapp.state_code');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org;
	}
        */
        
        //Get total transaction amount of state wise
        public function getReportStateWiseByAjax($state_code){
		//$search = $_GET['search'];
                $select_table = new Zend_Db_Table('dbt_mnrega_60_2016_2017');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => 'dbt_mnrega_60_2016_2017'), array('sum(scheme.cr_amount) as total_transfer','count(scheme.id) as total_beneficiary'));				
                $select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                 $select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                 $select->where('mapp.state_code = ?', $state_code);
                 $select->group('mapp.state_code');
                $select_org = $select_table->fetchAll($select);
                return $select_org;
	}
	

	
	//Below function is use for the display state wise data on to the home page india map
	public function AllScheme(){
		 $select_table = new Zend_Db_Table('dbt_scheme');
         $select = $select_table->select();
		 $select->from(array("schmedata"=>"dbt_scheme"),array("id","scheme_table"));
		 $select->where("schmedata.translation_id != ?",'1');
		 $select->where("schmedata.status = ?",'1');
		 $returnData = $select_table->fetchAll($select);
		 $pureArr = $returnData->toArray();
		 return $pureArr;
	}
	
	 public function getReportStateWiseByAjaxData($state_code){
				$totalScheme = $this->AllScheme();
				$current_time = $this->getCurrentFinancialYear();
				$i = 0;
				foreach($totalScheme as $key => $value){
					$tbname = "dbt_".$value['scheme_table']."_".$current_time;
					$schemeid = $value['id'];
                                        //$query = "SELECT SUM(scheme.amount) As total_transfer, SUM(scheme.no_of_beneficiries) As total_beneficiaries FROM $tbname As scheme INNER JOIN dbt_state_scheme_mapping As mapping on mapping.scheme_state_code = scheme.state_code WHERE mapping.state_code='$val' and mapping.scheme_id=$schemeid";

                                        $arr=array();		
                                        $select_table = new Zend_Db_Table($tbname);
                                        $select = $select_table->select();
                                        $select->setIntegrityCheck(false);
                                        $select->from(array('scheme' => $tbname), array('sum(scheme.amount) as total_transfer','sum(scheme.no_of_beneficiries) as total_beneficiary'));				
                                        $select->join(array('mapping' => 'dbt_state_scheme_mapping'), 'mapping.scheme_state_code = scheme.state_code');
                                        $select->where('mapping.state_code = ?', $state_code);
                                                        $select->where('mapping.scheme_id = ?', $schemeid);

                                        $select_org = $select_table->fetchAll($select);
                                        $arr = $select_org->toArray();
                                        //print_r($arr);die;
                                        $total_transfer+=$arr[0]['total_transfer'];
                                        $total_beneficiary+=$arr[0]['total_beneficiary'];
                                        //$arr[$state_code]['total_fund'] += $arr[0]['total_transfer']; 
                                        //$arr[$state_code]['total_beneficiary'] += $arr[0]['total_beneficiary']; 
                                }
                                //echo "---".$total_transfer;die;
                                $fund=array('total_transfer'=>$this->no_to_words($total_transfer),'total_beneficiary'=>$this->no_to_words($total_beneficiary));
                                
                //print_r($arr);die;
               return $fund;                
	}
	
	
//Below function is use for the display state wise data on to the home page india map	
	
	
	
        
        
        //Generate Table dynamically according to the title of scheme table 
        public function findSchemeTableList($financial_year){
                $db = Zend_Db_Table::getDefaultAdapter();
                //$result = $db->fetchAll('show tables');
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_table","scheme_type"));
                $select->where("sch.status =?","1");
                $select->where("sch.language =?","2");//only english
                $scheme_record = $newscm->fetchAll($select);
                $scheme_table=array();
                foreach($scheme_record->toArray() as $scheme_val)
                {
                    //$scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;
                    $scheme_table[]= "dbt_".$scheme_val['scheme_table']."_".$financial_year;
                }
                
                $db = Zend_Db_Table::getDefaultAdapter();
                $result = $db->fetchAll('show tables');
                $table_list=array();
                $i=0;
                foreach($result as $val)
                {
                    $table_name="";                  
                    $table_name=$val['Tables_in_dbt'];
                    if($table_name){
                        $result = $db->fetchAll("SELECT count(*) as countrow FROM ".$table_name);
                        //print_r($result);die;
                        $totalrec=$result[0]['countrow'];
                        if($totalrec > 0){
                            $table_list[$i++]= $table_name; 
                        }
                    }
                }
                //print_r($table_list);
                //die;
                return array_intersect($table_list, $scheme_table);  
	}
        
        
        //Get total transaction amount of state wise
        public function calculateReportWithScheme($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => $scheme_table_name), array('sum(scheme.amount) as total_transfer','sum(no_of_beneficiries) as total_beneficiary'));				
                $select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                $select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                $select->group('mapp.state_code');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}
        
        /****************************************/
        //used only to get for map
        //Get total transaction amount of state wise
        public function stateWiseFund($financial_year){
           /* $db = Zend_Db_Table::getDefaultAdapter();
                //$result = $db->fetchAll('show tables');
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_table","scheme_type"));
                $select->where("sch.status =?","1");
                $select->where("sch.language =?","2");//only english
                $scheme_record = $newscm->fetchAll($select);
                $select_org=array();
                
                
                foreach($scheme_record->toArray() as $scheme_val)
                {
                    //$scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;
                    //$scheme_table[]= "dbt_".$scheme_val['scheme_table']."_".$financial_year;
                //}
            
                    $scheme_table= "dbt_".$scheme_val['scheme_table']."_".$financial_year;
            
                    $select_table = new Zend_Db_Table($scheme_table);
                    $select = $select_table->select();
                    $select->setIntegrityCheck(false);
                    $select->from(array('scheme' => $scheme_table), array('sum(scheme.amount) as total_transfer','sum(no_of_beneficiries) as total_beneficiary'));				
                    $select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                    $select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                    $select->where("scheme.scheme_id=?", $scheme_val['id']);
                    $select->group('mapp.state_code');
                    //echo "<br>---".$select;
                    $select_org[] = $select_table->fetchAll($select);
                    //return $select_org;
                }
            * 
            */
	}
        /*********************************************/
        public function getStates(){
                $newtb = new Zend_Db_Table("dbt_state");
                $select = $newtb->select();
                $select->from(array("state" => "dbt_state"),array('state_name as state','state_code as stcode'));
                $select->order("state_name");
                $state_name = $newtb->fetchAll($select);
                $stateArray=array();
                foreach($state_name->toArray() as $val){
                  $stateArray[$val['stcode']]= ucwords(strtolower(trim($val['state'])));
                }
                return $stateArray;
        }
        
        
        public function getCurrentFinancialYear()
        {
            $curre_year = strtotime(date("d-m-Y"));          
            $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
            if($curre_year > $fixedyear){
                $start = date("Y");
            }else if($curre_year <= $fixedyear){
                $dataa = date("Y")-1;
                $start = $dataa;
            }
                $dateend = $start+1;
                $end = $dateend;
                $dateadded = $start."_".$end;
                return $dateadded;
        }
        
        //Count Total number of state and district and sub-district and villeges
        //Count Total number of state
        public function countTotalStates($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => $scheme_table_name), array('count(scheme.state_code) as total_states'));				
                $select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                $select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                $select->group('mapp.state_code');
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}
        
        //Count Total number of D
        public function countTotalDistricts($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                /*$select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => $scheme_table_name), array('count(scheme.state_code) as total_states'));				
                //$select->join(array('mapp' => 'dbt_state_scheme_mapping'), 'scheme.state_code = mapp.scheme_state_code', array('state_code as state_code'));
                $select->join(array("mapp" =>"dbt_district_scheme_mapping"), 'scheme.district_code=mapp.scheme_district_code', array('mapp.district_code as district_code'));
                //$select->join(array('state' => 'dbt_state'), 'mapp.state_code = state.state_code', array('state_name'));
                $select->join(array("district" =>"dbt_district"), 'mapp.district_code=district.district_code', array('mapp.district_code as district_code'));
                //$select->group('mapp.state_code');
                $select->where("mapp.state_code=?", $state);
                $selectsc->group("mapp.district_code");
                $select = $select_table->fetchAll($select);
                return $select_org->toArray();
                 */
                
                
                $db = Zend_Db_Table::getDefaultAdapter();
                $query="SELECT count(scheme.id) as total_districts
                        FROM ".$scheme_table_name." scheme 
                        inner join dbt_district_scheme_mapping mapp on scheme.district_code=mapp.scheme_district_code
                        inner join dbt_district district on mapp.district_code=district.district_code
                        group by mapp.district_code";
                $result = $db->fetchAll($query);
                //print_r($result);
                return $result;
                

            }
	}
        
        
        
        //Count Total number of Sub District/Block
        public function countTotalSubDistrict($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $db = Zend_Db_Table::getDefaultAdapter();
                $query="SELECT block_mapping.block_code as subdistrict_code,count(scheme.id) as total_subdistricts
                        FROM ".$scheme_table_name." scheme 
                        inner join dbt_block_scheme_mapping block_mapping on scheme.block_code=block_mapping.scheme_block_code
                        inner join dbt_subdistrict subdistrict on block_mapping.block_code=subdistrict.subdistrict_code
                        group by block_mapping.block_code";
                $result = $db->fetchAll($query);
                return $result;
                

            }
	}
        
        //Count Total number of Sub District/Block
        public function countTotalVillage($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $db = Zend_Db_Table::getDefaultAdapter();
                $query="SELECT village_mapping.village_code as village_code,count(scheme.id) as total_subdistricts
                        FROM ".$scheme_table_name." scheme 
                        inner join dbt_village_scheme_mapping village_mapping on scheme.village_code=village_mapping.scheme_village_code
                        inner join dbt_village village on village_mapping.village_code=village.village_code
                        group by village_mapping.village_code";
                $result = $db->fetchAll($query);
                return $result;
                

            }
	}
        
         //Get total transaction amount of state wise
       /* public function countTotalBeneficiary($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => $scheme_table_name), array("scheme.fund_transfer as fund_type","SUM(scheme.amount) AS total_amount", "count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_amount_of_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar", "SUM(IF(scheme.aadhar_num='', scheme.amount, 0)) AS total_amount_of_beneficiary_without_aadhar"));				
                $select->join(array('schm' => 'dbt_scheme'), 'scheme.scheme_id = schm.id', array('id','scheme_type','scheme_name','ministry_id'));
                $select->where("schm.status =?","1");                
                //$select->group('scheme.fund_transfer');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}*/
        
     public function countTotalBeneficiary($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                //$select->from(array('scheme' => $scheme_table_name), array("count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar",  "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar"));
                $select->from(array('scheme' => $scheme_table_name), array("SUM(IF(scheme.no_of_beneficiries!='', scheme.no_of_beneficiries, 0)) AS total_beneficiery", "SUM(IF(scheme.no_of_abp_beneficiries!='', scheme.no_of_abp_beneficiries, 0)) AS total_beneficiary_with_aadhar_seeded"));
                
                $select->join(array('schm' => 'dbt_scheme'), 'scheme.scheme_id = schm.id', array('id','scheme_type','scheme_name','ministry_id'));
                $select->where("schm.status =?","1");                
                //$select->group('scheme.fund_transfer');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}   
        
        
        
        public function countTotalBeneficiaryBySchemeType($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => $scheme_table_name), array("scheme.fund_transfer as fund_type","SUM(scheme.amount) AS total_amount", "count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_amount_of_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar", "SUM(IF(scheme.aadhar_num='', scheme.amount, 0)) AS total_amount_of_beneficiary_without_aadhar"));				
                $select->join(array('schm' => 'dbt_scheme'), 'scheme.scheme_id = schm.id', array('id','scheme_type','scheme_name','ministry_id'));
                $select->where("schm.status =?","1");                
                //$select->group('scheme.fund_transfer');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}
        
        
        
        
        
        
        
       /* 
        
        
        //Get total transaction amount of state wise
        public function countTotalBeneficiary($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table($scheme_table_name);
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                //$select->from(array('scheme' => $scheme_table_name), array("scheme.fund_transfer as fund_type","SUM(scheme.amount) AS total_amount", "count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_amount_of_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar", "SUM(IF(scheme.aadhar_num='', scheme.amount, 0)) AS total_amount_of_beneficiary_without_aadhar"));				
                //$select->join(array('schm' => 'dbt_scheme'), 'scheme.scheme_id = schm.id', array('id','scheme_type','scheme_name','ministry_id'));
                //$select->where("schm.status =?","1");  
                
                 $select->from(array('schm' => 'dbt_scheme'), array('id','scheme_type','scheme_name','ministry_id'));
                $select->joinLeft(array('scheme' => $scheme_table_name), 'scheme.scheme_id = schm.id', array("scheme.fund_transfer as fund_type","SUM(scheme.amount) AS total_amount", "count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_amount_of_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar", "SUM(IF(scheme.aadhar_num='', scheme.amount, 0)) AS total_amount_of_beneficiary_without_aadhar"));				
                $select->joinLeft(array('smd' => 'dbt_scheme_manual_data'), 'scheme.scheme_id = smd.scheme_id', array("SUM(smd.no_of_beneficiries_in_scheme) AS total_beneficiary_with_aadhar_manual","SUM(IF(smd.no_of_beneficiries_with_aadhar!='', smd.no_of_beneficiries_with_aadhar, 0)) as no_of_beneficiries_with_aadhar_manual"));
                $select->where("schm.status =?","1");
                $select->group('schm.scheme_type');
                //echo $select;die;
                
                //$select->group('scheme.fund_transfer');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
               // print_r($select_org->toArray());die;
                return $select_org->toArray();
            }
	}
        */
        
        /*
        //Get total transaction amount of state wise
        public function countTotalBeneficiary($scheme_table_name){
            if($scheme_table_name == " "){
                return 0;
            }else{
                $select_table = new Zend_Db_Table('dbt_scheme');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('schm' => 'dbt_scheme'), array('id','scheme_type','scheme_name'));
                $select->joinLeft(array('scheme' => $scheme_table_name), 'scheme.scheme_id = schm.id', array("scheme.fund_transfer as fund_type","SUM(scheme.amount) AS total_amount", "count(scheme.id) AS total_beneficiery", "SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_amount_of_beneficiary_with_aadhar", "SUM(IF(scheme.aadhar_num='', 1, 0)) AS total_beneficiary_without_aadhar", "SUM(IF(scheme.aadhar_num='', scheme.amount, 0)) AS total_amount_of_beneficiary_without_aadhar"));
                //$select->from(array('scheme' => $scheme_table_name), array("scheme.fund_transfer"));
                
                $select->joinLeft(array("smd" => "dbt_scheme_manual_data"), 'scheme.scheme_id = smd.scheme_id', array("no_of_beneficiries_in_scheme as no_of_beneficiries_in_manual","no_of_beneficiries_with_aadhar as no_of_beneficiries_with_aadhar_manual"));
                //$select->group('scheme.fund_transfer');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
                return $select_org->toArray();
            }
	}
        */
        
        
        public function getScheme(){
            $db = Zend_Db_Table::getDefaultAdapter();
            $query="SELECT * FROM dbt_scheme where language!=1 and status=1";
            $result = $db->fetchAll($query);       
        }
        
        
        
         
        public function getSchemeManualDataTable($scheme_id,$from_year,$to_year){
            //echo "ddddddd";die;    
            $newscm = new Zend_Db_Table("dbt_scheme_manual_data");
                $select = $newscm->select();
                $select->setIntegrityCheck(false);
                $select->from(array("smd" => "dbt_scheme_manual_data"),array("scheme_id","total_fund_transfer","SUM(IF(smd.no_of_beneficiries_in_scheme!='', smd.no_of_beneficiries_in_scheme, 0)) as no_of_beneficiries_in_scheme_manual","SUM(IF(smd.no_of_beneficiries_with_aadhar!='', smd.no_of_beneficiries_with_aadhar, 0)) as no_of_beneficiries_with_aadhar_manual"));
                //$select->from(array("smd" => "dbt_scheme_manual_data"),array("scheme_id","SUM(IF(smd.no_of_beneficiries_in_scheme!='', smd.no_of_beneficiries_in_scheme, 0)) as no_of_beneficiries_in_scheme_manual","SUM(IF(smd.no_of_beneficiries_with_aadhar!='', smd.no_of_beneficiries_with_aadhar, 0)) as no_of_beneficiries_with_aadhar_manual"));
                $select->join(array('scheme' => 'dbt_scheme'), 'scheme.id=smd.scheme_id', array('id','scheme_type','scheme_name','ministry_id'));
                $select->where("smd.status =?","1");
                //$select->where("scheme.status =?","1");
                //$select->where("scheme.language =?","2");
                $select->where("smd.financial_year_from =?",$from_year);
                $select->where("smd.financial_year_to =?",$to_year);
                $select->group('scheme.scheme_type');
                //echo $select;die;
                $scheme_record = $newscm->fetchAll($select);
                $array=array();
                foreach($scheme_record->toArray() as $key=>$val){
                  $array[$val['scheme_type']]['total_fund_transfer']= $val['total_fund_transfer'];
                  $array[$val['scheme_type']]['no_of_beneficiries_in_scheme_manual']= $val['no_of_beneficiries_in_scheme_manual'];
                  $array[$val['scheme_type']]['no_of_beneficiries_with_aadhar_manual']= $val['no_of_beneficiries_with_aadhar_manual'];
                  //$array[$val['scheme_type']]['ministry_id']= $val['ministry_id'];
                }
                return $array;  
	}
        /***************************************************************************/
        //Generate Table dynamically according to the title of scheme table 
        public function getSchemeTableListBySchemeType($financial_year,$scheme_type){
                $db = Zend_Db_Table::getDefaultAdapter();
                //$result = $db->fetchAll('show tables');
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_table","scheme_type"));
                $select->where("sch.status =?","1");
                $select->where("sch.scheme_type= ?",$scheme_type);
                $select->where("sch.language =?","2");//only english
                $scheme_record = $newscm->fetchAll($select);
                $scheme_table=array();
                foreach($scheme_record->toArray() as $scheme_val)
                {
                    //$scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;
                    $scheme_table[]= "dbt_".$scheme_val['scheme_table']."_".$financial_year;
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
        public function getSchemeManualDataTableByScheme($scheme_id,$from_year,$to_year,$scheme_type){
            //echo "ddddddd";die;    
            $newscm = new Zend_Db_Table("dbt_scheme_manual_data");
                $select = $newscm->select();
                $select->setIntegrityCheck(false);
                $select->from(array("smd" => "dbt_scheme_manual_data"),array("scheme_id","total_fund_transfer","SUM(IF(smd.no_of_beneficiries_in_scheme!='', smd.no_of_beneficiries_in_scheme, 0)) as no_of_beneficiries_in_scheme_manual","SUM(IF(smd.no_of_beneficiries_with_aadhar!='', smd.no_of_beneficiries_with_aadhar, 0)) as no_of_beneficiries_with_aadhar_manual"));
                $select->join(array('scheme' => 'dbt_scheme'), 'scheme.id=smd.scheme_id', array('id','scheme_type','scheme_name'));
                $select->where("smd.status =?","1");
                //$select->where("scheme.status =?","1");
                //$select->where("scheme.language =?","2");
                $select->where("smd.financial_year_from =?",$from_year);
                $select->where("smd.financial_year_to =?",$to_year);
                $select->group('scheme.scheme_type');
                //echo $select;die;
                $scheme_record = $newscm->fetchAll($select);
                $array=array();
                foreach($scheme_record->toArray() as $key=>$val){
                  $array[$val['scheme_type']]['total_fund_transfer']= $val['total_fund_transfer'];
                  $array[$val['scheme_type']]['no_of_beneficiries_in_scheme_manual']= $val['no_of_beneficiries_in_scheme_manual'];
                  $array[$val['scheme_type']]['no_of_beneficiries_with_aadhar_manual']= $val['no_of_beneficiries_with_aadhar_manual'];
                 
                }
                return $array;  
	}
        
        public function getSchemeManualDataTableByType($scheme_id,$from_year,$to_year,$scheme_type){
                //echo "ddddddd".$scheme_type;die;    
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->setIntegrityCheck(false);
                $select->from(array('scheme' => 'dbt_scheme'), array('id','scheme_type','scheme_name','ministry_id'));
                $select->join(array("smd" => "dbt_scheme_manual_data"), 'scheme.id=smd.scheme_id',array("scheme_id","sum(no_of_beneficiries_in_scheme) as no_of_beneficiries_in_scheme","sum(no_of_beneficiries_with_aadhar) as no_of_beneficiries_with_aadhar"));
                //$select->joinLeft(array('scheme' => 'dbt_scheme'), 'scheme.id=smd.scheme_id', array('id','scheme_type','scheme_name'));
                //$select->where("smd.status =?","1");
                $select->where("scheme.status =?","1");
                //$select->where("scheme.language =?","2");
                $select->where("smd.financial_year_from =?",$from_year);
                $select->where("smd.financial_year_to =?",$to_year);
                $select->where("scheme.scheme_type =?",$scheme_type);
                $select->group('smd.scheme_id');
				$select->order("scheme.scheme_name");
                //echo $select;die;
                $scheme_record = $newscm->fetchAll($select);
                //echo "<pre>";
                //print_r($scheme_record->toArray());
                //echo "</pre>";
                //die;
                $array=array();
                foreach($scheme_record->toArray() as $key=>$val){
                  $array[$val['scheme_id']]['scheme_name']= $val['scheme_name'];
                  //$array[$val['scheme_id']]['total_fund_transfer']= $val['total_fund_transfer'];
                  $array[$val['scheme_id']]['no_of_beneficiries_in_scheme_manual']= $val['no_of_beneficiries_in_scheme'];
                  $array[$val['scheme_id']]['no_of_beneficiries_with_aadhar_manual']= $val['no_of_beneficiries_with_aadhar'];
                  $array[$val['scheme_id']]['ministry_id']= $val['ministry_id'];
                }
                return $array;  
	} 
        
        
        
         /*******************Amount format****************/
    public function currencyData1($rs = null){
	setlocale(LC_MONETARY, 'en_IN');
	$amount = money_format('%!i', $rs);
	//$amount=explode('.',$amount); //Comment this if you want amount value to be 1,00,000.00
	return $amount;
        }
        public function currencyData($rs = null){
                setlocale(LC_MONETARY, 'en_IN');
                $amount = money_format('%!i', $rs);
                $amount=explode('.',$amount); //Comment this if you want amount value to be 1,00,000.00
                return $amount[0];
        }
        public function amountFormat($amount = null){
                if ($amount > 9999999){
                        $formatedAmount = currencyData(round($amount / 10000000, 2)).' Cr+';
                } else {
                        $formatedAmount = currencyData($amount);
                }
                return $formatedAmount;
        }

        public function no_to_words($no = null)
        {
            //echo $no;exit;
            if($no == 0 || $no == '') {
                        $no = 0;
                return $no;
            }else {
                        $n =  strlen($no); // 7
                        //$pow = pow(10,$n);
                switch ($n) {
                                case 1:
                        $finalval =  $this->currencyData($no);
                        break;
                                case 2:
                        $finalval =  $this->currencyData($no);
                        break;
                    case 3:
                        // $val = $no/100;
                        // $val = round($val, 2);
                        $finalval =  $this->currencyData($no);
                        break;
                    case 4:
                        // $val = $no/1000;
                        // $val = round($val, 2);
                        $finalval =  $this->currencyData($no);
                        break;
                    case 5:
                        // $val = $no/1000;
                        // $val = round($val, 2);
                        $finalval =  $this->currencyData($no);
                        break;
                    case 6:
                        $val = $no/100000;
                        $val = round($val, 2);
                        $finalval =  $this->currencyData1($val) ." Lakh";
                        break;
                    case 7:
                        $val = $no/100000;
                        $val = round($val, 2);
                        $finalval =  $this->currencyData1($val) ." Lakh";
                        break;
                    case 8:
                        $val = $no/10000000;
                        $val = round($val, 2);
                        $finalval =  $this->currencyData1($val) ." Cr+";
                        break;

                    default:
                       $val = $no/10000000;
                       $val = round($val, 2);
                       $finalval =  $this->currencyData1($val) ." Cr+";
                       break;
                }
                return $finalval;

            }
        }
    /**********************************/
      public function getTable($scmid = null){
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_table"));
                $select->where("sch.id =?",$scmid);
                $select->where("translation_id !=?",'1');
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
                return $data['scheme_table'];
        }   
      public function schmefinddata(){
            $curre_year = strtotime(date("d-m-Y"));          
            $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
            if($curre_year > $fixedyear){
                $start = date("Y");
            }else if($curre_year <= $fixedyear){
                $dataa = date("Y")-1;
                $start = $dataa;
            }
            //echo $start;exit;
			$newscm = new Zend_Db_Table("dbt_scheme");
            $select = $newscm->select();
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_table","scheme_type","ministry_id"));
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            if(count($schemeRow->toArray()) > 0){
                $schemeval = array();
                $scReturn = array();
                $arr = array();
                $k=1;
                $schemes = $schemeRow->toArray();
                //$financial_year = $this->dateGet();//this is returning the current financial year
                foreach($schemes as $key => $value){
                    $tablename = $this->getTable($value['id']);
                    //$schemetable = "dbt_".$tablename."_".$financial_year;
                    $schemetable = "dbt_".$tablename."_2016_2017";
                    $scemeTb = new Zend_Db_Table("dbt_scheme");
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_type as type","ministry_id"));
			 
                    $selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(amount) as total_transfer"));
					
					//$selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(amount) as total_transfer","SUM(scheme.no_of_beneficiries) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', no_of_beneficiries, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', amount, 0)) AS total_abp_amount","SUM(scheme.no_of_abp_beneficiries) AS total_abp_seeded","scheme.state_name","scheme.district_name"));

					//$selectsc->joinLeft(array("manual" => "dbt_scheme_manual_data"),'scmdata.id = manual.scheme_id', array("SUM(IF(manual.financial_year_from = $start, no_of_beneficiries_in_scheme,0)) as beneficiery_man","SUM(IF(manual.financial_year_from = $start,no_of_beneficiries_with_aadhar,0)) as adhar_beneficiery_man","SUM(IF(manual.financial_year_from = $start,total_fund_transfer,0)) as total_amount_tr_man","SUM(IF(manual.financial_year_from = $start,using_aadhar_bridge_payment,0)) as abp_beneficiery_man","SUM(IF(manual.financial_year_from = $start,without_aadhar_bridge_payment,0)) as non_beneficiery_man"));
                    $selectsc->where("scmdata.id = ?", $value['id']);
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                  $k +=1;
                } 
            }
			//echo "<pre>";
			//print_r($returnData);exit;
            return array_filter($returnData);
        }
     
	 /**********************************/
      public function getSaving(){
          define('SAVING_AMOUNT','1081181600000'); //amount= 108118.16 Cr   
          $newscm = new Zend_Db_Table("dbt_home_page_master_data_current_year");
                $select = $newscm->select();
                $select->from(array("master_data" => "dbt_home_page_master_data_current_year"),array("total_fund_transfer"));
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
                $sum=$data['total_fund_transfer'] + SAVING_AMOUNT;
                //echo $sum;die;
                return $sum;
        }   	  
	  
}
?>