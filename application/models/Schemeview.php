<?php
require_once "Zend/Db/Table/Abstract.php";
Class Application_Model_Schemeview extends Zend_Db_Table_Abstract {
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
        public function schmefinddata($year = null,$ministry=null,$scheme=null,$state=null,$district=null,$block=null,$panchayat=null,$village=null){
            //echo "aaaaa";exit;

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
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
            if($ministry != 0){
                $select->where("sch.ministry_id =?",$ministry);
            }
            if($ministry != 0 && $scheme != 0){
                $select->where("sch.id =?",$scheme);
            }
            if($ministry == 0 && $scheme != 0){
                $select->where("sch.id =?",$scheme);
            }
            // echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            //echo $select;exit;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            //$dd=$schemeRow->toArray();
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
                    //$schemetable = "dbt_".str_replace(" ", "_",preg_replace('/[^A-Za-z0-9]/', '',strtolower($value['scheme_name'])))."_".$value['id']."_".$financial_year;//dynamic table creation
                    //calling the table of schemes

                    //$schemetablecheck = $this->findSchemeTableList($financial_year);

                    //if(in_array($schemetable, $schemetablecheck)){
                    $scemeTb = new Zend_Db_Table($schemetable);
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_type as type","ministry_id"));
			//if village listing want to display then remove group by and count query
                    //$selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(scheme.amount) as total_transfer","count(scheme.id) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', 1, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', scheme.amount, 0)) AS total_abp_amount"));
                    $selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(amount) as total_transfer","SUM(scheme.no_of_beneficiries) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', no_of_beneficiries, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', amount, 0)) AS total_abp_amount","SUM(scheme.no_of_abp_beneficiries) AS total_abp_seeded","scheme.state_name","scheme.district_name"));

					$selectsc->joinLeft(array("manual" => "dbt_scheme_manual_data"),'scmdata.id = manual.scheme_id', array("SUM(IF(manual.financial_year_from = $start, no_of_beneficiries_in_scheme,0)) as beneficiery_man","SUM(IF(manual.financial_year_from = $start,no_of_beneficiries_with_aadhar,0)) as adhar_beneficiery_man","SUM(IF(manual.financial_year_from = $start,total_fund_transfer,0)) as total_amount_tr_man","SUM(IF(manual.financial_year_from = $start,using_aadhar_bridge_payment,0)) as abp_beneficiery_man","SUM(IF(manual.financial_year_from = $start,without_aadhar_bridge_payment,0)) as non_beneficiery_man"));

                    $selectsc->where("scmdata.id = ?", $value['id']);
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                 // }
                  $k +=1;
                } 
            }
            
            // echo "<pre>";
            // print_r($returnData);
            // echo $select;
            // exit;
            return $returnData;
        }
        public function schemefunddetails($scheme_id = null, $ministry = null){
        	//$scheme_id = 60;
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
            $select->from(array("sch" => "dbt_scheme"),array("id","scheme_name","scheme_type","ministry_id"));
            if($ministry != 0 && $ministry !=''){
                $select->where("sch.ministry_id =?",$ministry);
            }
             $select->where("sch.id =?",$scheme_id);
           
            //echo $select;exit;
            $select->where("sch.translation_id !=?","1");
            $select->where("sch.status =?","1");
            $select->order("sch.scheme_name");
            // echo $select;exit;
            $schemeRow='';
            $schemeRow = $newscm->fetchAll($select);
            //$dd=$schemeRow->toArray();
           // print_r($dd);die;
		   $returnData = array();
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
                    //$schemetable = "dbt_".str_replace(" ", "_",preg_replace('/[^A-Za-z0-9]/', '',strtolower($value['scheme_name'])))."_".$value['id']."_".$financial_year;//dynamic table creation
                    //calling the table of schemes
                    //if(in_array($schemetable, $schemetablecheck)){

                    $scemeTb = new Zend_Db_Table("dbt_scheme");
                    //echo $select."<br />".$schemetable;exit;
					//echo "AAaa--a".$schemetable;exit;
                    $selectsc = $scemeTb->select();
                    $selectsc->setIntegrityCheck(false);
                    $selectsc->from(array("scmdata" => "dbt_scheme"),array("id","scheme_name","scheme_type as type","ministry_id"));
			//if village listing want to display then remove group by and count query
                    //$selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(scheme.amount) as total_transfer","count(scheme.id) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', 1, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', scheme.amount, 0)) AS total_abp_amount"));

                    $selectsc->joinLeft(array("scheme" => $schemetable),'scmdata.id = scheme.scheme_id',array("SUM(amount) as total_transfer","SUM(scheme.no_of_beneficiries) as total_beneficiery","SUM(IF(scheme.fund_transfer='APB', no_of_beneficiries, 0)) AS total_abp_beneficiary","SUM(IF(scheme.fund_transfer='APB', amount, 0)) AS total_abp_amount","SUM(scheme.no_of_abp_beneficiries) AS total_abp_seeded","scheme.state_name","scheme.district_name"));

                    $selectsc->joinLeft(array("manual" => "dbt_scheme_manual_data"),'scmdata.id = manual.scheme_id', array("SUM(IF(manual.financial_year_from = $start, no_of_beneficiries_in_scheme,0)) as beneficiery_man","SUM(IF(manual.financial_year_from = $start,no_of_beneficiries_with_aadhar,0)) as adhar_beneficiery_man","SUM(IF(manual.financial_year_from = $start,total_fund_transfer,0)) as total_amount_tr_man","SUM(IF(manual.financial_year_from = $start,using_aadhar_bridge_payment,0)) as abp_beneficiery_man","SUM(IF(manual.financial_year_from = $start,without_aadhar_bridge_payment,0)) as non_beneficiery_man"));

                    $selectsc->where("scmdata.id = ?", $value['id']);
                    //echo $selectsc;exit;
                   $scReturn = $scemeTb->fetchAll($selectsc);
                   $returnData[$k] = $scReturn->toArray();
                  //}
                  $k +=1;
                } 
            }
            //echo $selectsc;
            // echo "<pre>";
            // print_r($returnData);
            //exit;
            return $returnData;
        }

}
?>