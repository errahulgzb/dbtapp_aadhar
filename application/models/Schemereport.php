<?php 
require_once "Zend/Db/Table/Abstract.php";
require_once 'Zend/Db/Table/Exception.php';
Class Application_Model_Schemereport extends Zend_Db_Table_Abstract {
    
    public function getTable($scmid = null){
                $newscm = new Zend_Db_Table("dbt_scheme");
                $select = $newscm->select();
                $select->from(array("sch" => "dbt_scheme"),array("id","scheme_table"));
                $select->where("sch.id =?",$scmid);
                $select->where("translation_id !=?",'1');
                //echo $select;exit;
                $scheme_record = $newscm->fetchRow($select);
                $data = $scheme_record->toArray();
                //print_r($data);exit;
                return $data['scheme_table'];
    }
    /*public function selectministry(){
        $newtb = new Zend_Db_Table("dbt_ministry");
        $select = $newtb->select();
        $select->from(array("dbt_ministry"),array('id','ministry_name as ministry'));
        $select->where("translation_id != ? ","1");
        $select->where("status = ? ","1");
        $select->order("ministry_name");
        $rows = $newtb->fetchAll($select);
        //print_r($rows);exit;
        return $rows; 
    }
	*/
	public function selectministry(){
        $newtb = new Zend_Db_Table("dbt_ministry");
        $select = $newtb->select();
		$select->setIntegrityCheck(false);
        $select->from(array('ministry' => "dbt_ministry"),array('id','ministry_name as ministry'));
		$select->join(array('scheme' => 'dbt_scheme'), 'ministry.id=scheme.ministry_id', array('ministry_id'));
        $select->where("ministry.translation_id != ? ","1");
        $select->where("ministry.status = ? ","1");
        $select->order("ministry.ministry_name");
		//echo $select;die;
        $rows = $newtb->fetchAll($select);
        //print_r($rows);exit;
        return $rows; 
    }
    public function selectstate(){
        //echo "Aaaa";exit;
        $newtb = new Zend_Db_Table("dbt_state");
        $select = $newtb->select();
        $select->from(array("dbt_state"),array('state_code as id','state_name as state'));
        $select->where("status = ? ","1");
        $select->order("state_name");
        //echo $select;exit;
        $rows = $newtb->fetchAll($select);
        //print_r($rows);exit;
        return $rows; 
    }
    public function selectblock($distId = null){
        $newtb = new Zend_Db_Table("dbt_subdistrict");
        $select = $newtb->select();
        $select->from(array("dbt_subdistrict"),array('subdistrict_code as blockcode','subdistrict_name as blockname'));
        $select->where("district_code =? ",$distId);
        $select->where("status = ? ","1");
        $select->order("subdistrict_name");
        // echo "<pre>";
        // echo $select;exit;
        $rows = $newtb->fetchAll($select);
        return $rows;
    }
    public function selectdistrict($dist){
        //echo $dist;exit;
        $newtb = new Zend_Db_Table("dbt_district");
        $select = $newtb->select();
        $select->from(array("dbt_district"),array('district_code as distritctcode','district_name as district'));
        $select->where("state_code =? ",$dist);
        $select->where("status = ? ","1");
        $select->order("district_name");
        // echo "<pre>";
        // echo $select;exit;
        $rows = $newtb->fetchAll($select);
        //  echo "<pre>";
        // print_r($rows);
        //exit;
        return $rows;
    }
    public function selectUserstate($userid = null ){
        $tbstate = new Zend_Db_Table("dbt_users");
        $sel_tb = $tbstate->select();
        $sel_tb->from(array("user"=>"dbt_users"), array("state","cityname"));
        $sel_tb->where("user.id =?",$userid);
        $sel_tb->where("user.status =?","1");
        //echo $sel_tb;exit;
        $getdata = $tbstate->fetchAll($sel_tb);
        return $getdata;
    }



    public function ministrywisescheme($ministry_id = null){
        $newtb = new Zend_Db_Table("dbt_scheme");
        $select = $newtb->select();
        $select->from(array("scheme" => "dbt_scheme"),array('id','scheme_name as scheme'));
        $select->where("scheme.translation_id != ? ","1");
        $select->where("scheme.ministry_id = ? ", $ministry_id);
         $select->where("scheme.status = ? ", "1");
         $select->order("scheme.scheme_name");
        //echo $select;
        //echo $ministry_id."aaaa";exit;
        $rows = $newtb->fetchAll($select);
        //echo "<pre>";
        //print_r($rows->toArray());exit;
        return $rows->toArray(); 
    }
	public function reportList()
        {
            //Aadhar Seeded Beneficiaries:fto_type =APB
            $select_table = new Zend_Db_Table('dbt_mnrega_60_2016_2017');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('mnrega' => 'dbt_mnrega_60_2016_2017'), array('sum(mnrega.cr_amount) as total_fund_transfer','fto_type as abp','count(mnrega.id) as total_beneficiaries','count(mnrega.fto_type) as aadhar_seeded_beneficiaries'));				
                $select->join(array('scheme' => 'dbt_scheme'), 'scheme.id=mnrega.scheme_id', array('id as schemeid','scheme_name','scheme_type'));
                //$select->where('scheme.status = 1');
                $select->order('scheme.scheme_name ASC')->limit($limit,$start);
                $select->group('scheme_id');
                //$select->having('smd.financial_year_from > 2014');
                //echo $select;die;
                $rows = $select_table->fetchAll($select);
            return $rows; 
        }
        public function schemewisestate($schemeid = null){
                $newtb = new Zend_Db_Table("dbt_state");
                $select = $newtb->select();
                $select->from(array("state" => "dbt_state"),array('state_name as state','state_code as stcode'));
                $select->where("state.status = ? ", "1");
                $select->order("state.state_name");
                $state_name = $newtb->fetchAll($select);
                return $state_name->toArray();
                // echo "<pre>";
                // print_r($state_name->toArray());
                // exit;
               
        }
        public function statewisedistrict($statecode){
                $newtb = new Zend_Db_Table("dbt_district");
                $select = $newtb->select();
                $select->from(array("dist" => "dbt_district"),array('district_name as district','district_code as distcode'));
                $select->where("dist.state_code =?", $statecode);
                $select->where("dist.status = ? ", "1");
                $select->order("dist.district_name");
                $district_name = $newtb->fetchAll($select);
                return $district_name->toArray();
        }
        public function districtwiseblock($district = null){
                $newtb = new Zend_Db_Table("dbt_subdistrict");
                $select = $newtb->select();
                $select->from(array("block" => "dbt_subdistrict"),array('subdistrict_name','subdistrict_code'));
                $select->where("block.district_code =?", $district);
                $select->where("block.status = ? ", "1");
                $select->order("block.subdistrict_name");
                //echo $select;exit;
                $block_name = $newtb->fetchAll($select);
                //  echo "<pre>";
                // print_r($block_name->toArray());
                // exit;
                return $block_name->toArray();
        }
        public function blockwisepanchayat($block = null){
                $newtb = new Zend_Db_Table("dbt_panchayat");
                $select = $newtb->select();
                $select->from(array("panchayat" => "dbt_panchayat"),array('panchayat_name','panchayat_code'));
                $select->where("panchayat.block_code =?", $block);
                $select->where("panchayat.status = ? ", "1");
                $select->order("panchayat.panchayat_name");
                //echo $select;exit;
                $panchayat_name = $newtb->fetchAll($select);
                //  echo "<pre>";
                // print_r($panchayat_name->toArray());
                // exit;
                return $panchayat_name->toArray();
        }
        public function panchayatwisevillage($panchayt = null){
                $newtb = new Zend_Db_Table("dbt_village");
                $select = $newtb->select();
                $select->from(array("village" => "dbt_village"),array('village_name','village_code'));
                $select->where("village.panchayat_code =?", $panchayt);
                $select->where("village.status = ? ", "1");
                $select->order("village.village_name");
                //echo $select;exit;
                $panchayat_name = $newtb->fetchAll($select);
                //  echo "<pre>";
                // print_r($panchayat_name->toArray());
                // exit;
                return $panchayat_name->toArray();
        }

        
         
          //Generate Table dynamically according to the title of scheme table 
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
                     $tablename = $this->getTable($scheme_val['id']);

                    //$scheme_table[]= "dbt_".  str_replace(" ", "_", preg_replace('/[^A-Za-z0-9]/', '',strtolower(trim($scheme_val['scheme_name']))))."_".$scheme_val['id']."_".$financial_year;
                    $scheme_table[]= "dbt_".$tablename."_".$financial_year;
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
        


    //======================================================================    
        public function dateGet(){
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
                //$arr = array("start"=> $start,"end"=>$end);
                $dateadded = $start."_".$end;
                return $dateadded;
        }
	//checking the record exist or not
		public function recordcheck($schemetb,$scmid = null){
                $newscm = new Zend_Db_Table($schemetb);
                $select = $newscm->select();
                $select->from(array("sch" => $schemetb),array("count(id) as countrow"));
                $select->where("sch.scheme_id =?",$scmid);
                $scheme_record = $newscm->fetchAll($select);
                $data = $scheme_record->toArray();
                return $data[0]['countrow'];
            }
	//record exist check	
		
	//this function is for the get scheme_state id from the scheme table to include in where clause
		public function GetSchemeStateCode($schemeid = null,$schemetb = null,$master_state = null){
			//echo $master_state;exit;
			$counting = $this->recordcheck($schemetb,$schemeid);
			//echo $counting.$schemeid;exit;
			if($counting > 0){
                $newtb = new Zend_Db_Table($schemetb);
                $select = $newtb->select();
                $select->from(array("mapping" => $schemetb),array('mapping.scheme_state_code'));
                $select->where("mapping.scheme_id =?", $schemeid);
                $select->where("mapping.state_code = ? ", $master_state);
                $st_code = $newtb->fetchRow($select);
				$st_arr = $st_code->toArray();
				//$st_arr$st_arr['scheme_state_code'];
				//echo "<pre>";
               //print_r($st_arr);
                //exit;
                return $st_code["scheme_state_code"];
			}
        }
	//	The function is for the 
        public function schmefinddata($year = null,$ministry=null,$scheme=null,$state=null,$district=null,$block=null,$panchayat=null,$village=null,$lstart = null, $llimit = null){
            //financial year from get
                $curre_year = strtotime(date("d-m-Y"));          
                $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
                if($curre_year > $fixedyear){
                    $start = date("Y");
                }else if($curre_year <= $fixedyear){
                    $dataa = date("Y")-1;
                    $start = $dataa;
                }
            //financial from get end here



            $newscm = new Zend_Db_Table("dbt_scheme");
            $select = $newscm->select();
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
            if($ministry != 0 && $ministry != ''){
                $select->where("sch.ministry_id =?",$ministry);
            }
            if($ministry != '0' && $scheme != '0'){
                $select->where("sch.id =?",$scheme);
            }
            if($ministry == '0' && $scheme != '0'){
                $select->where("sch.id =?",$scheme);
            }
            //echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            //echo $select;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            if(count($schemeRow->toArray()) > 0){
                $schemeval = array();
                $scReturn = array();
                $arr = array();
                $k=1;
                $schemes = $schemeRow->toArray();
                $financial_year = $this->dateGet();//this is returning the current financial year
                 //$reporttb = new Application_Model_Report;
                foreach($schemes as $key => $value){
                    $tablename = $this->getTable($value['id']);
                    //$schemetable = "dbt_".str_replace(" ", "_",preg_replace('/[^A-Za-z0-9]/', '',strtolower($value['scheme_name'])))."_".$value['id']."_".$financial_year;//dynamic table creation

                    $schemetable = "dbt_".$tablename."_".$financial_year;//dynamic table creation

                    //$schemetable = "dbt_mnrega_60_2016_2017";
                    
                    //calling the table of schemes
                    //$schemetablecheck = $this->findSchemeTableList($financial_year);
                

                    //if(in_array($schemetable, $schemetablecheck)){
                    $scemeTb = new Zend_Db_Table("dbt_scheme");
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_type as type","ministry_id"));
//if village listing want to display then remove group by and count query
            if($village != 0){
                        $selectsc->join(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("scheme.amount as total_transfer","name","gender","aadhar_num","scheme_specific_unique_num","scheme_specific_family_num","transaction_date","mobile_num"));    
                        if($llimit != 0){
                            $selectsc->limit($llimit,$lstart);
                        } 
                    }else{
                    //$selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(scheme.amount) as total_transfer","count(scheme.id) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', 1, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', scheme.amount, 0)) AS total_abp_amount","name","gender","scheme.district_name","scheme.block_name as subdistrict_name","panchayat_name","village_name"));
                    //echo $selectsc;exit;

                    $selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(amount) as total_transfer","SUM(scheme.no_of_beneficiries) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', no_of_beneficiries, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', amount, 0)) AS total_abp_amount","SUM(scheme.no_of_abp_beneficiries) AS total_abp_seeded","scheme.state_name","scheme.district_name"));
            //this will comming from scheme manual data for display the record which entered manually--        
                     $selectsc->joinLeft(array("manual" => "dbt_scheme_manual_data"),'scmdata.id = manual.scheme_id', array("SUM(IF(manual.financial_year_from = $start, no_of_beneficiries_in_scheme,0)) as beneficiery_man","SUM(IF(manual.financial_year_from = $start,no_of_beneficiries_with_aadhar,0)) as adhar_beneficiery_man","SUM(IF(manual.financial_year_from = $start,total_fund_transfer,0)) as total_amount_tr_man","SUM(IF(manual.financial_year_from = $start,using_aadhar_bridge_payment,0)) as abp_beneficiery_man","SUM(IF(manual.financial_year_from = $start,without_aadhar_bridge_payment,0)) as non_beneficiery_man"));
                     //echo $selectsc;exit;
                    }
            //this will comming from scheme manual data for display the record which entered manually--
                    
                   //echo $selectsc;exit;
                    if($scheme != 0 && $state == 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme, state level content display
                        $selectsc->join(array("mapp" => "dbt_state_scheme_mapping"), 'scheme.state_code = mapp.scheme_state_code', array('mapp.state_code'));
                        //echo $selectsc;exit;
                        $selectsc->join(array("state" => "dbt_state"), 'mapp.state_code = state.state_code', array('state.state_name'));
            //this is use to mapping with id in state mapping table.. filter with scheme id
                        $selectsc->where("mapp.scheme_id = ?", $value['id']);
                        $selectsc->order("scheme.state_name");
            //this is use to mapping with id in state mapping table.. filter with scheme id
                        $selectsc->group("mapp.state_code");
                    }//this will find without scheme record
                    

                    else if($scheme == 0 && $state != 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme and state, district level content display
                         $selectsc->join(array("mapp" =>"dbt_district_scheme_mapping"), 'scheme.district_code=mapp.scheme_district_code', array('mapp.district_code as district_code'));
                         
                        $selectsc->join(array("district" =>"dbt_district"), 'mapp.district_code=district.district_code', array('mapp.district_code as district_code','district.district_name'));
                          $selectsc->where("mapp.state_code=?", $state);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                        
						$selectsc->where("mapp.scheme_id = ?", $value['id']);
                         
						 //$selectsc->order("scheme.district_name");
				//this is district data record then table name should have "dbt_state_scheme_mapping"
						//echo $value['id'],"dbt_state_scheme_mapping",$state;exit;
					$scheme_state = $this->GetSchemeStateCode($value['id'],"dbt_state_scheme_mapping",$state);
						if($scheme_state != "" && $scheme_state != null && !empty($scheme_state)){
							$selectsc->where("scheme.state_code = ?", $scheme_state);
						}				
				//this is district data record then table name should have "dbt_state_scheme_mapping"
				
                         $selectsc->order("district.district_name");
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.district_code");
                          //echo  $selectsc;exit;
                    } 

                    //fidn without scheme record
                    else if($scheme != 0 && $state != 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme and state, district level content display
                         $selectsc->join(array("mapp" =>"dbt_district_scheme_mapping"), 'scheme.district_code=mapp.scheme_district_code', array('mapp.district_code as district_code'));
                         
                         $selectsc->join(array("district" =>"dbt_district"), 'mapp.district_code=district.district_code', array('mapp.district_code as district_code','district.district_name'));
                          $selectsc->where("mapp.state_code=?", $state);
             //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
						

			//this is district data record then table name should have "dbt_state_scheme_mapping"
						//echo $state." ".$value['id']." dbt_state_scheme_mapping";exit;
						//$schemeid = $value['id'];
						////$tbname = "dbt_state_scheme_mapping";
						$scheme_state = $this->GetSchemeStateCode($value['id'],"dbt_state_scheme_mapping",$state);
						
						if($scheme_state != "" || $scheme_state != null){
							$selectsc->where("scheme.state_code = ?", $scheme_state);
						}					
			//this is district data record then table name should have "dbt_state_scheme_mapping"
						
						
                         $selectsc->order("scheme.district_name");
            //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.district_code");
                          //echo  $selectsc;exit;
                    } 

        //current scope of work queries are mentioned above. Below queries is written for the drill down of the district level

                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block == 0 && $panchayat == 0 && $village == 0) {
                        $selectsc->join(array("mapp" =>"dbt_block_scheme_mapping"), 'scheme.block_code=mapp.scheme_block_code', array('mapp.block_code as block_code'));
                         
                         $selectsc->join(array("block" =>"dbt_subdistrict"), 'mapp.block_code=block.subdistrict_code', array('mapp.block_code as block_code','block.subdistrict_name'));
                          $selectsc->where("mapp.district_code=?", $district);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.block_code");
                          //echo  $selectsc;exit;
                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block == 0 && $panchayat == 0 && $village == 0) {
                        $selectsc->join(array("mapp" =>"dbt_block_scheme_mapping"), 'scheme.block_code=mapp.scheme_block_code', array('mapp.block_code as block_code'));
                         
                         $selectsc->join(array("block" =>"dbt_subdistrict"), 'mapp.block_code=block.subdistrict_code', array('mapp.block_code as block_code','block.subdistrict_name'));
                          $selectsc->where("mapp.district_code=?", $district);
            //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.block_code");
                          //echo  $selectsc;exit;
                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat == 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_panchayat_scheme_mapping"), 'scheme.panchayat_code=mapp.scheme_panchayat_code', array('mapp.panchayat_code as panchayat_code'));                  
                         $selectsc->join(array("panchayat" =>"dbt_panchayat"), 'mapp.panchayat_code=panchayat.panchayat_code', array('mapp.panchayat_code as panchayat_code','panchayat.panchayat_name'));
                          $selectsc->where("mapp.block_code=?", $block);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.panchayat_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat == 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_panchayat_scheme_mapping"), 'scheme.panchayat_code=mapp.scheme_panchayat_code', array('mapp.panchayat_code as panchayat_code'));                  
                         

                         $selectsc->join(array("panchayat" =>"dbt_panchayat"), 'mapp.panchayat_code=panchayat.panchayat_code', array('mapp.panchayat_code as panchayat_code','panchayat.panchayat_name'));
                          $selectsc->where("mapp.block_code=?", $block);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.panchayat_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));

                          $selectsc->where("mapp.panchayat_code=?", $panchayat);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                        
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.panchayat_code=?", $panchayat);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          $selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village != 0) {
                        
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                        $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.village_code=?", $village);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          //$selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village != 0) {
                        
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.village_code=?", $village);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                          //$selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
//dril down action //select ministry and scheme
//dril down action
                    //echo $start;exit;
                    $selectsc->where("scmdata.id = ?", $value['id']);
                    //$selectsc->order("scmdata.state_name");
                    //$selectsc->order("scmdata.district_name");
                    //$selectsc->where("manual.id = ?", $start);
                    //echo $selectsc;exit;
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                   // echo "<pre>";
                   // print_r($returnData);
                   // echo "<pre>";
                  //}
                  $k +=1;
                }
                //return $returnData;
            }
            //exit;
            // echo "<pre>";
            // print_r($returnData);
            // echo $select;
            // exit;
            return array_filter($returnData);
        }

      
          
           public function schmefinddatacount($year = null,$ministry=null,$scheme=null,$state=null,$district=null,$block=null,$panchayat=null,$village=null,$lstart = null, $llimit = null){
            $newscm = new Zend_Db_Table("dbt_scheme");
            $select = $newscm->select();
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
            if($ministry != 0){
                $select->where("sch.ministry_id =?",$ministry);
            }
            if($ministry != '0' && $scheme != '0'){
                $select->where("sch.id =?",$scheme);
            }
            //echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            //echo $select;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            $dd=$schemeRow->toArray();
           // print_r($dd);die;
            if(count($schemeRow->toArray()) > 0){
                $schemeval = array();
                $scReturn = array();
                $arr = array();
                $k=1;
                $schemes = $schemeRow->toArray();
                $financial_year = $this->dateGet();//this is returning the current financial year

                 //$reporttb = new Application_Model_Report;
                foreach($schemes as $key => $value){
                    $tablename = $this->getTable($value['id']);
                    $schemetable = "dbt_".$tablename."_".$financial_year;//dynamic table creation
                    //calling the table of schemes
                    //$schemetablecheck = $this->findSchemeTableList($financial_year);
                

                   // if(in_array($schemetable, $schemetablecheck)){
                    $scemeTb = new Zend_Db_Table("dbt_scheme");
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array());


    //if village listing want to display then remove group by and count query
            if($village!=0){
                        $selectsc->join(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("count(scheme.id) as total_num")); 
                    }                    
            if ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village != 0) {
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array());                  
                        // $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.village_code=?", $village);
                //this is use to mapping with id in state mapping table.. filter with scheme id
                         $selectsc->where("mapp.scheme_id = ?", $value['id']);
            //this is use to mapping with id in state mapping table.. filter with scheme id
                          //$selectsc->group("mapp.village_code");
                          //echo  $selectsc."<br />";//exit;
                    }
                    $selectsc->where("scmdata.id = ?", $value['id']);
                    //echo $selectsc;exit;
                   $scReturn = $scemeTb->fetchRow($selectsc);
                   $returnData = $scReturn->toArray();
                 // }
                  $k +=1;
                }//loop
            }
            return $returnData["total_num"];
        }
          
      

	public function getministryname($ministry)
	{
		$select_table = new Zend_Db_Table('dbt_ministry');
		$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($ministry))));
		$ministry_arr = $rowselect->toArray();
		return $ministry_arr['ministry_name'];     
	}
	public function getschemename($scheme)
	{
		$select_table = new Zend_Db_Table('dbt_scheme');
		$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($scheme))));
		$ministry_arr =$rowselect->toArray();
		return $ministry_arr['scheme_name'];     
	}
	public function getstatename($state)
	{
		$select_table = new Zend_Db_Table('dbt_state');
		$rowselect = $select_table->fetchRow($select_table->select()->where('state_code = ?',$state));
		//return $rowselect->toArray()['state_name']; 
		$ministry_arr =$rowselect->toArray();
		return $ministry_arr['state_name'];     
		//$ministry_arr = $rowselect->toArray();		
	}
	public function getdistrictname($district)
	{
		$select_table = new Zend_Db_Table('dbt_district');
		$rowselect = $select_table->fetchRow($select_table->select()->where('district_code = ?',trim(intval($district))));
		//return $rowselect->toArray()['district_name'];  
		$ministry_arr =$rowselect->toArray();
		return $ministry_arr['district_name']; 		
	}
	public function getblockname($block)
	{
		$select_table = new Zend_Db_Table('dbt_subdistrict');
		$rowselect = $select_table->fetchRow($select_table->select()->where('subdistrict_code = ?',trim(intval($block))));
		//return $rowselect->toArray()['subdistrict_name'];  
		$ministry_arr =$rowselect->toArray();
		return $ministry_arr['subdistrict_name']; 			
	}
	public function getpanchayatname($panchayat)
	{
		$select_table = new Zend_Db_Table('dbt_panchayat');
		$rowselect = $select_table->fetchRow($select_table->select()->where('panchayat_code = ?',trim(intval($panchayat))));
		//return $rowselect->toArray()['panchayat_name'];  
			$ministry_arr =$rowselect->toArray();
		return $ministry_arr['panchayat_name']; 				
	}
	public function getvillagename($village)
	{
		$select_table = new Zend_Db_Table('dbt_village');
		$rowselect = $select_table->fetchRow($select_table->select()->where('village_code = ?',trim(intval($village))));
		//return $rowselect->toArray()['village_name'];  
		$ministry_arr =$rowselect->toArray();
		return $ministry_arr['village_name']; 		
	}        
        

      /*  
        
        //Generate Table dynamically according to the title of scheme table 
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
                $i=0;
                foreach($result as $val)
                {
                    //$table_list[]= $val['Tables_in_dbt']; 
                    
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
                //echo "<pre>";
               // print_r($table_list);die;
                return array_intersect($table_list, $scheme_table);  
        }
        


    //======================================================================    
        public function dateGet(){
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
                //$arr = array("start"=> $start,"end"=>$end);
                $dateadded = $start."_".$end;
                return $dateadded;
        }
        public function schmefinddata($year = null,$ministry=null,$scheme=null,$state=null,$district=null,$block=null,$panchayat=null,$village=null){
            //financial year from get
                $curre_year = strtotime(date("d-m-Y"));          
                $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
                if($curre_year > $fixedyear){
                    $start = date("Y");
                }else if($curre_year <= $fixedyear){
                    $dataa = date("Y")-1;
                    $start = $dataa;
                }
            //financial from get end here



            $newscm = new Zend_Db_Table("dbt_scheme");
            $select = $newscm->select();
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
            if($ministry != 0 && $ministry != ''){
                $select->where("sch.ministry_id =?",$ministry);
            }
            if($ministry != '0' && $scheme != '0'){
                $select->where("sch.id =?",$scheme);
            }
            if($ministry == '0' && $scheme != '0'){
                $select->where("sch.id =?",$scheme);
            }
            //echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            //echo $select;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            if(count($schemeRow->toArray()) > 0){
                $schemeval = array();
                $scReturn = array();
                $arr = array();
                $k=1;
                $schemes = $schemeRow->toArray();
                $financial_year = $this->dateGet();//this is returning the current financial year

                 //$reporttb = new Application_Model_Report;
                foreach($schemes as $key => $value){
                    $schemetable = "dbt_".str_replace(" ", "_",preg_replace('/[^A-Za-z0-9]/', '',strtolower($value['scheme_name'])))."_".$value['id']."_".$financial_year;//dynamic table creation

                    //$schemetable = "dbt_mnrega_60_2016_2017";
                    
                    //calling the table of schemes
                    $schemetablecheck = $this->findSchemeTableList($financial_year);
                    //print_r($schemetablecheck);die;

                    //if(in_array($schemetable, $schemetablecheck)){
                    $scemeTb = new Zend_Db_Table('dbt_scheme');
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_type as type","ministry_id"));
//if village listing want to display then remove group by and count query
            if($village != 0){
                        $selectsc->join(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("scheme.amount as total_transfer","name","gender"));    
                    }else{
                    $selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(scheme.amount) as total_transfer","count(scheme.id) as total_beneficiery","SUM(IF(scheme.aadhar_num!='', 1, 0)) AS total_abp_beneficiary","SUM(IF(scheme.aadhar_num!='', scheme.amount, 0)) AS total_abp_amount","name","gender"));
                    //echo $selectsc;exit;
            //this will comming from scheme manual data for display the record which entered manually--        
                     $selectsc->joinLeft(array("manual" => "dbt_scheme_manual_data"),'scmdata.id = manual.scheme_id', array("IF(manual.financial_year_from = $start, no_of_beneficiries_in_scheme,0) as beneficiery_man","SUM(IF(manual.financial_year_from = $start,no_of_beneficiries_with_aadhar,0)) as adhar_beneficiery_man","SUM(IF(manual.financial_year_from = $start,total_fund_transfer,0)) as total_amount_tr_man","SUM(IF(manual.financial_year_from = $start,using_aadhar_bridge_payment,0)) as abp_beneficiery_man","SUM(IF(manual.financial_year_from = $start,without_aadhar_bridge_payment,0)) as non_beneficiery_man"));
                     //echo $selectsc;exit;
                    }
            //this will comming from scheme manual data for display the record which entered manually--
                    
                   //echo $selectsc;exit;
                    if($scheme != 0 && $state == 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme, state level content display
                        $selectsc->join(array("mapp" => "dbt_state_scheme_mapping"), 'scheme.state_code = mapp.scheme_state_code', array('mapp.state_code'));
                        //echo $selectsc;exit;
                        $selectsc->join(array("state" => "dbt_state"), 'mapp.state_code = state.state_code', array('state.state_name'));
                        $selectsc->group("mapp.state_code");
                    }//this will find without scheme record
                    


                    else if($scheme == 0 && $state != 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme and state, district level content display
                         $selectsc->join(array("mapp" =>"dbt_district_scheme_mapping"), 'scheme.district_code=mapp.scheme_district_code', array('mapp.district_code as district_code'));
                         
                         $selectsc->join(array("district" =>"dbt_district"), 'mapp.district_code=district.district_code', array('mapp.district_code as district_code','district.district_name'));
                          $selectsc->where("mapp.state_code=?", $state);
                          $selectsc->group("mapp.district_code");
                          //echo  $selectsc;exit;
                    } 

                    //fidn without scheme record
                    else if($scheme != 0 && $state != 0 && $district == 0 && $block == 0 && $panchayat == 0 && $village == 0){//by the scheme and state, district level content display
                         $selectsc->join(array("mapp" =>"dbt_district_scheme_mapping"), 'scheme.district_code=mapp.scheme_district_code', array('mapp.district_code as district_code'));
                         
                         $selectsc->join(array("district" =>"dbt_district"), 'mapp.district_code=district.district_code', array('mapp.district_code as district_code','district.district_name'));
                          $selectsc->where("mapp.state_code=?", $state);
                          $selectsc->group("mapp.district_code");
                          //echo  $selectsc;exit;
                    } 
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block == 0 && $panchayat == 0 && $village == 0) {
                        $selectsc->join(array("mapp" =>"dbt_block_scheme_mapping"), 'scheme.block_code=mapp.scheme_block_code', array('mapp.block_code as block_code'));
                         
                         $selectsc->join(array("block" =>"dbt_subdistrict"), 'mapp.block_code=block.subdistrict_code', array('mapp.block_code as block_code','block.subdistrict_name'));
                          $selectsc->where("mapp.district_code=?", $district);
                          $selectsc->group("mapp.block_code");
                          //echo  $selectsc;exit;
                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block == 0 && $panchayat == 0 && $village == 0) {
                        $selectsc->join(array("mapp" =>"dbt_block_scheme_mapping"), 'scheme.block_code=mapp.scheme_block_code', array('mapp.block_code as block_code'));
                         
                         $selectsc->join(array("block" =>"dbt_subdistrict"), 'mapp.block_code=block.subdistrict_code', array('mapp.block_code as block_code','block.subdistrict_name'));
                          $selectsc->where("mapp.district_code=?", $district);
                          $selectsc->group("mapp.block_code");
                          //echo  $selectsc;exit;
                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat == 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_panchayat_scheme_mapping"), 'scheme.panchayat_code=mapp.scheme_panchayat_code', array('mapp.panchayat_code as panchayat_code'));                  
                         $selectsc->join(array("panchayat" =>"dbt_panchayat"), 'mapp.panchayat_code=panchayat.panchayat_code', array('mapp.panchayat_code as panchayat_code','panchayat.panchayat_name'));
                          $selectsc->where("mapp.block_code=?", $block);
                          $selectsc->group("mapp.panchayat_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat == 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_panchayat_scheme_mapping"), 'scheme.panchayat_code=mapp.scheme_panchayat_code', array('mapp.panchayat_code as panchayat_code'));                  
                         $selectsc->join(array("panchayat" =>"dbt_panchayat"), 'mapp.panchayat_code=panchayat.panchayat_code', array('mapp.panchayat_code as panchayat_code','panchayat.panchayat_name'));
                          $selectsc->where("mapp.block_code=?", $block);
                          $selectsc->group("mapp.panchayat_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.panchayat_code=?", $panchayat);
                          $selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village == 0){
                       
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.panchayat_code=?", $panchayat);
                          $selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme != 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village != 0) {
                        
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.village_code=?", $village);
                          //$selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
                    elseif ($scheme == 0 && $state != 0 && $district != 0 && $block != 0 && $panchayat != 0 && $village != 0) {
                        
                        $selectsc->join(array("mapp" =>"dbt_village_scheme_mapping"), 'scheme.village_code=mapp.scheme_village_code', array('mapp.village_code as village_code'));                  
                         $selectsc->join(array("village" =>"dbt_village"), 'mapp.village_code=village.village_code', array('mapp.village_code as village_code','village.village_name'));
                          $selectsc->where("mapp.village_code=?", $village);
                          //$selectsc->group("mapp.village_code");
                          //echo  $selectsc;exit;

                    }
//dril down action //select ministry and scheme
//dril down action
                    //echo $start;exit;
                    $selectsc->where("scmdata.id = ?", $value['id']);
                    //$selectsc->where("manual.id = ?", $start);
                    //echo $selectsc;exit;
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                   // echo "<pre>";
                   // print_r($returnData);
                   // echo "<pre>";
                  //}
                  $k +=1;
                }
                //return $returnData;
            }
            //exit;
            // echo "<pre>";
            // print_r($returnData);
            // echo $select;
            // exit;
            return $returnData;
        }
*/



/********************PMO Report **********************/
 public function schmeReport($year = null,$month=null){
            //financial year from get
                $curre_year = strtotime(date("d-m-Y"));          
                $fixedyear = strtotime(date("d-m-Y", strtotime("31-03-".date("Y"))));
                if($curre_year > $fixedyear){
                    $start = date("Y");
                }else if($curre_year <= $fixedyear){
                    $dataa = date("Y")-1;
                    $start = $dataa;
                }
            //financial from get end here

			if($month){
				$month="'".$month."'";
			}else{
				$month='00';
			}

            $newscm = new Zend_Db_Table("dbt_scheme");
            $select = $newscm->select();
			$select->setIntegrityCheck(false);
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
			$select->joinLeft(array("ministry" => "dbt_ministry"),'sch.ministry_id = ministry.id', array("ministry_name"));
           
            //echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            //$select->order("sch.scheme_name");
			$select->order("ministry.ministry_name");
            //echo $select;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            if(count($schemeRow->toArray()) > 0){
                $schemeval = array();
                $scReturn = array();
                $arr = array();
                $k=1;
                $schemes = $schemeRow->toArray();
                $financial_year = $this->dateGet();//this is returning the current financial year
                 //$reporttb = new Application_Model_Report;
                foreach($schemes as $key => $value){
                    $tablename = $this->getTable($value['id']);
                    
                    $schemetable = "dbt_".$tablename."_".$financial_year;//dynamic table creation

                    //if(in_array($schemetable, $schemetablecheck)){
                    $scemeTb = new Zend_Db_Table("dbt_scheme");
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_codification","scheme_type as type","ministry_id"));
					
					$selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(IF((scheme.year = $start and scheme.month = $month),amount,0)) as total_transfer","SUM(IF((scheme.year = $start and scheme.month = $month),scheme.no_of_beneficiries,0)) as total_beneficiery","SUM(IF((scheme.fund_transfer='APB' and scheme.year = $start and scheme.month = $month), no_of_beneficiries, 0)) AS total_abp_beneficiary","SUM(IF((scheme.fund_transfer='APB' and scheme.year = $start and scheme.month = $month), amount, 0)) AS total_abp_amount","SUM(IF((scheme.fund_transfer!='APB' and scheme.year = $start and scheme.month = $month), amount, 0)) AS total_non_abp_amount","SUM(scheme.no_of_abp_beneficiries) AS total_abp_seeded","scheme.state_name","scheme.district_name","max(scheme.updated) as last_updated_main"));      
                   
					$selectsc->joinLeft(array("ministry" => "dbt_ministry"),'scmdata.ministry_id = ministry.id', array("ministry_name"));
	  
                    $selectsc->where("scmdata.id = ?", $value['id']);
                    //$selectsc->order("scmdata.state_name");
                    //$selectsc->order("scmdata.district_name");
                    //$selectsc->where("manual.id = ?", $start);
                    //echo $selectsc;exit;
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                    //echo "<pre>";
                    //print_r($scReturn->toArray());
					//print_r($returnData);
                    //echo "<pre>";
                  //}
                  $k +=1;
                }
                //return $returnData;
            }
            //exit;
            // echo "<pre>";
            // print_r($returnData);
            // echo $select;
             //exit;
            return array_filter($returnData);
        }

/*******************PMO Report Ends *************************/


public function getBeneficiary($scheme_id=null,$month=null,$year=null){
        $newtb = new Zend_Db_Table("dbt_beneficaryscheme");
        $select = $newtb->select();
		$select->setIntegrityCheck(false);
        $select->from(array('beneficaryscheme' => "dbt_beneficaryscheme"),array("id","scheme_id as schemeid","if(beneficaryscheme.totalnoofbeneficiaries>=0,beneficaryscheme.totalnoofbeneficiaries,'--') as totalnoofbeneficiaries","if(beneficaryscheme.totalnoofbeneficiarieswithbankac>=0,beneficaryscheme.totalnoofbeneficiarieswithbankac,'--') as totalnoofbeneficiarieswithbankac","if(beneficaryscheme.totalnoofbeneficiarieswithaadhaar>=0,beneficaryscheme.totalnoofbeneficiarieswithaadhaar,'--') as totalnoofbeneficiarieswithaadhaar","if(beneficaryscheme.totalnoofbeneficiarieswithseededbankac>=0,beneficaryscheme.totalnoofbeneficiarieswithseededbankac,'--') as totalnoofbeneficiarieswithseededbankac","beneficaryscheme.updated as last_updated_beneficaryscheme"));
		//$select->join(array('scheme' => 'dbt_scheme'), 'ministry.id=scheme.ministry_id', array('ministry_id'));
         $select->where("beneficaryscheme.financial_year_from = ?",$year);
		$select->where("beneficaryscheme.month = ? ",$month);
		$select->where("beneficaryscheme.scheme_id = ? ",$scheme_id);
        //$select->where("beneficaryscheme.status = ? ","1");
		//echo $select;die;
        $rows = $newtb->fetchAll($select);
        //print_r($rows);exit;
        //return $rows->toArray();
		$total=count($rows->toArray());		
		if($total==0){
			return array('notfound'); 
		}else{
			return $rows->toArray(); 
		}
    }

	public function getSchemeManualDetail($scheme_id=null,$month=null,$year=null){
        $newtb = new Zend_Db_Table("dbt_beneficaryscheme");
        $select = $newtb->select();
		$select->setIntegrityCheck(false);
        $select->from(array("manual" => "dbt_scheme_manual_data"),array("if(no_of_beneficiries_in_scheme>=0,no_of_beneficiries_in_scheme,'--') as beneficiery_man_monthwise)","if(no_of_beneficiries_in_scheme>=0,no_of_beneficiries_in_scheme,'--') as beneficiery_man","if(no_of_beneficiries_with_aadhar>=0,no_of_beneficiries_with_aadhar,'--') as adhar_beneficiery_man","if(total_fund_transfer>=0,total_fund_transfer,'--') as total_amount_tr_man","if(using_aadhar_bridge_payment>=0,using_aadhar_bridge_payment,'--') as abp_amount_manual","if(without_aadhar_bridge_payment>=0,without_aadhar_bridge_payment,'--') as non_apb_amount_manual","manual.created as last_updated_manual"));
		//$select->join(array('scheme' => 'dbt_scheme'), 'ministry.id=scheme.ministry_id', array('ministry_id'));
         $select->where("manual.financial_year_from = ?",$year);
		$select->where("manual.month = ? ",$month);
		$select->where("manual.scheme_id = ? ",$scheme_id);
        //$select->where("beneficaryscheme.status = ? ","1");
		//echo $select;die;
        $rows = $newtb->fetchAll($select);
		$total=count($rows->toArray());
        //print_r($rows);exit;
		if($total==0){
			return array('notfound'); 
		}else{
			return $rows->toArray(); 
		}
        
    }
	
	
	/******************disclaimer methods***************************/
public function getDisclamer($year = null,$month  = null)
		{
			
			$yearval = explode("_", $year);
			//print_r($yearval);
			$financial_year_from = $yearval[0];
			$financial_year_to = $yearval[1];
			$select_table = new Zend_Db_Table('dbt_pmo_disclaimer');
			$select_query =  $select_table->select();
			$select_query->from(array('pmodisclaimer' => 'dbt_pmo_disclaimer'));		
			$select_query->where('pmodisclaimer.financial_year_from=?',$financial_year_from);	
			$select_query->where('pmodisclaimer.financial_year_to=?',$financial_year_to);	
			$select_query->where('pmodisclaimer.month=?',$month);	
			//echo $select_query;die;
			$rowlist = $select_table->fetchAll($select_query);
			//echo $select_query; die;
			return $rowlist->toArray(); 				
		}


public function insertDisclamerData($dataform = null)
		{
			
				$data_table = new Zend_Db_Table('dbt_pmo_disclaimer');
						$datainsert="";
						$year_val = $dataform['year'];
						$yearval = explode("_", $year_val);
						$financial_year_from = $yearval[0];
						$financial_year_to = $yearval[1];

						$datainsert = array(
									'disclaimer' => $dataform['disclaimer'],
									'month' => $dataform['month'],
									'financial_year_from' => $financial_year_from,
									'financial_year_to' => $financial_year_to,
									'status'=> 1							
											);
							 $insertdata=$data_table->insert($datainsert);
							return $insertdata;
		}
		

public function editDisclamerData($dataform,$month,$year)
{
	
		$selectable = new Zend_Db_Table('dbt_pmo_disclaimer');
			$data="";
			$where="";
			$year_val = $year;
			$yearval = explode("_", $year_val);
			$financial_year_from = $yearval[0];
			$financial_year_to = $yearval[1];

			$data = array(
			  'disclaimer' => $dataform['disclaimer']
				);
			$where = array('month = ?'=> $month,'financial_year_from = ?'=> $financial_year_from,'financial_year_to = ?'=> $financial_year_to);
			//print_r($data); die;
			$update_values = $selectable->update($data,$where);
			return $update_values;
	
}

public function countpmodisclaimer($year = null,$month  = null)
		{
			
			$yearval = explode("_", $year);
			$financial_year_from = $yearval[0];
			$financial_year_to = $yearval[1];
			$select_table = new Zend_Db_Table('dbt_pmo_disclaimer');
			$select_query =  $select_table->select();
			$select_query->from(array('pmodisclaimer' => 'dbt_pmo_disclaimer'));		
			$select_query->where('pmodisclaimer.financial_year_from=?',$financial_year_from);	
			$select_query->where('pmodisclaimer.financial_year_to=?',$financial_year_to);	
			$select_query->where('pmodisclaimer.month=?',$month);	
			$rowlist = $select_table->fetchAll($select_query);
			//echo $select_query; die;
			return count($rowlist); 				
		}

}////select scheme.scheme_name,sum(smd.total_fund_transfer) as total_fund from dbt_scheme scheme inner join dbt_scheme_manual_data smd on scheme.id=smd.scheme_id group by scheme.id

?>