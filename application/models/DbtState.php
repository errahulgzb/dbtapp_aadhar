<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_DbtState extends Zend_Db_Table_Abstract{
			public function states(){
				  $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('state_name ASC'));
                  //echo "<pre>";
                 //print_r($select_table->select());
                  //echo "</pre>";
                  //exit;
				  return $row; 
			}
			public function statesget(){
					//die("djed");
				  $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->select();
				  $row->from(array("state"=>"dbt_state"),array("state_code","state_name"));
				 // $row->where("state.isstate = ?","yes");
				  $row->order("state.state_name ASC");
				  //echo $row;exit;
				  $datarow = $select_table->fetchAll($row);
				  return $datarow->toArray(); 
			}
			public function statesgetbystatecode($state_code = null){
				  
				  //$state_code=new Zend_Session_Namespace('state_code');
				  $select_table = new Zend_Db_Table('dbt_state');
				  $row = $select_table->select();
				  $row->from(array("state"=>"dbt_state"),array("state_code","state_name"));
				  
				  $row->where("state.state_code = ?",trim($state_code));
					
				  //$row->order("state.state_name ASC");
				  //echo $row;exit;
				  $datarow = $select_table->fetchAll($row);
				  return $datarow->toArray(); 
			}
			public function statewisedistrict($statecode = null){
//echo $statecode;die;
                $newtb = new Zend_Db_Table("dbt_district");
                $select = $newtb->select();
                $select->from(array("dist" => "dbt_district"),array('district_name as district','district_code as distcode'));
                $select->where("dist.state_code =?", $statecode);
                $select->where("dist.status = ? ", "1");
                $select->order("dist.district_name");
				//echo $select;die;
				//return $select;exit;
                $district_name = $newtb->fetchAll($select);
                return $district_name->toArray();
			}
			/*
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
			}*/
			public function districtwiseblock($district = null){
					$newtb = new Zend_Db_Table("dbt_block");
					$select = $newtb->select();
					$select->from(array("block" => "dbt_block"),array('title as subdistrict_name','block_code as subdistrict_code'));
					$select->where("block.district_id =?", $district);
					$select->where("block.status = ? ", "1");
					$select->order("block.title");
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
			public function blockwisevillage($block = null){
					$newtb = new Zend_Db_Table("dbt_village");
					$select = $newtb->select();
					$select->from(array("vill" => "dbt_village"),array('village_name','village_code'));
					$select->where("vill.subdistrict_code =?", $block);
					$select->where("vill.status = ? ", "1");
					$select->order("vill.village_name");
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
//BElow function is displaying State to the annonymous user and all			
	public function getAllState($stcode = null){
		$newtb = new Zend_Db_Table("dbt_state");
		$select = $newtb->select();
		$select->from(array("st" => "dbt_state"),array('st.state_name','st.state_code'));
		if($stcode != ""){
			$select->where("state_code =? ",$stcode);
		}
		$data = $newtb->fetchAll($select)->toArray();
		return $data;
	}
//BElow function is displaying district to the annonymous user and all			
	public function getAllDist($state_name = null){
		$newtb = new Zend_Db_Table("dbt_district");
		$select = $newtb->select();
		$select->from(array("dt" => "dbt_district"),array('dt.district_name','dt.district_code'));
		if($state_name != ""){
			$select->where("state_code =? ",$state_name);
		}
		$data = $newtb->fetchAll($select)->toArray();
		return $data;
	}
//BElow function is displaying block to the annonymous user and all			
	public function getAllBlock($state = null, $dist = null){
		$newtb = new Zend_Db_Table("dbt_block");
		$select = $newtb->select();
		$select->from(array("bl" => "dbt_block"),array('bl.title','bl.block_code'));
		$data = $newtb->fetchAll($select)->toArray();
		return $data;
	}
//BElow function is displaying village to the annonymous user and all			
	public function getAllVill($state = null, $dist = null, $block = null){
		$newtb = new Zend_Db_Table("dbt_village");
		$select = $newtb->select();
		$select->from(array("vi" => "dbt_village"),array('vi.village_name','vi.village_code'));
		$data = $newtb->fetchAll($select)->toArray();
		return $data;
	}	


}