<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Beneficiaryscheme extends Zend_Db_Table_Abstract 
{
	
	/******************pmo report model methods***************************/

/******************disclaimer methods***************************/
public function countpmodisclaimer($year = null,$month  = null){
			$yearval = explode("-", $year);
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
		
		public function getdatadisclaimer($year = null,$month  = null){
			
			$yearval = explode("-", $year);
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
			return $rowlist; 	
			
		}
		public function insrcrdpmobeneficiary($dataform = null)
		{
			
				$data_table = new Zend_Db_Table('dbt_pmo_disclaimer');
						$datainsert="";
						$year_val = $dataform['year'];
						$yearval = explode("-", $year_val);
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
	public function disclaimereditmnth($editdataform,$month)
	        {
				
					$selectable = new Zend_Db_Table('dbt_pmo_disclaimer');
						$data="";
						$where="";		
						$data = array(
						  'disclaimer' => $editdataform['disclaimer']
							);
						$where = array('month = ?'=> $month);
						//print_r($data); die;
						$update_values = $selectable->update($data,$where);
						return $update_values;
				
			}
			
	/*******************end*************************/
	
	
	/****************pmo report methods*****************/
		public function getpmodataschememanual($year = null,$month  = null)
	        {
				if($year!=0 || $month!=0){
					$yearval = explode("-", $year);
					$financial_year_from = $yearval[0];
					$financial_year_to = $yearval[1];
					$select_table = new Zend_Db_Table('dbt_scheme_manual_data');
					$select_query =  $select_table->select();
					$select_query->from(array('schememanual' => 'dbt_scheme_manual_data'));					
					$select_query->where('financial_year_from=?',$financial_year_from);	
					$select_query->where('financial_year_to=?',$financial_year_to);	
					$select_query->where('month=?',$month);	
					$select_query->order('schememanual.scheme_id');
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray(); 
				}
			   else
			   {
				   return 0;
			   }			   
			}
			
			public function getpmodatapahaal($year = null,$month  = null,$table = null)
	        {
				//echo "test"; die;
				if($year!=0 || $month!=0)
				{
					$yearval = explode("-", $year);
					$financial_year_from = $yearval[0];
					$financial_year_to = $yearval[1];
					$select_table = new Zend_Db_Table($table);
					$select_query =  $select_table->select();
					$select_query->from(array('pahal' => $table),array('month','scheme_id',"SUM(IF(pahal.fund_transfer='APB', pahal.amount, 0)) as totalamountapb","SUM(IF(pahal.fund_transfer!='APB', pahal.amount, 0)) as nonapbamount","sum(amount) as totalamount","sum(no_of_beneficiries) as totalnumbeneficiaries","sum(no_of_abp_beneficiries) as totalnumadharbasedbeneficiaries"));						
					$select_query->where('month=?',$month);	
					//$select_query->where('fund_transfer=?','APB');	
					//echo $select_query; die;
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray(); 
				}
			   else
			   {
				   return 0;
			   }			   
			}
			
			public function getpmodatamnregas($year = null,$month  = null,$table = null)
	        {
				//echo "test"; die;
				if($year!=0 || $month!=0)
				{
					$yearval = explode("-", $year);
					$financial_year_from = $yearval[0];
					$financial_year_to = $yearval[1];
					$select_table = new Zend_Db_Table($table);
					$select_query =  $select_table->select();					
					$select_query->from(array('pahal' => $table),array('month','scheme_id',"SUM(IF(pahal.fund_transfer='APB', pahal.amount, 0)) as totalamountapb","SUM(IF(pahal.fund_transfer!='APB', pahal.amount, 0)) as nonapbamount","sum(amount) as totalamount","sum(no_of_beneficiries) as totalnumbeneficiaries","sum(no_of_abp_beneficiries) as totalnumadharbasedbeneficiaries"));						
					$select_query->where('month=?',$month);	
					//$select_query->where('fund_transfer=?','APB');	
					//echo $select_query; die;
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray(); 
				}
			   else
			   {
				   return 0;
			   }			   
			}
			
			
			public function getpmodatabeneficairay($year = null,$month  = null)
	        {
				if($year!=0 || $month!=0)
				{
					$yearval = explode("-", $year);
					$financial_year_from = $yearval[0];
					$financial_year_to = $yearval[1];
					$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
					$select_query =  $select_table->select();
					$select_query->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'));					
					$select_query->where('financial_year_from=?',$financial_year_from);	
					$select_query->where('financial_year_to=?',$financial_year_to);	
					$select_query->where('month=?',$month);	
					$select_query->order('beneficiaryscheme.scheme_id');
					$rowlist = $select_table->fetchAll($select_query);
					return $rowlist->toArray(); 
				}
			   else
			   {
				   return 0;
			   }			   
			}
			
			public function getschememinid($schemeid)
	        {
				
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select_query =  $select_table->select();
				$select_query->from(array('scheme' => 'dbt_scheme'));					
				$select_query->where('id=?',$schemeid);	
				//$select_query->order('scheme.scheme_name');
				$rowlist = $select_table->fetchAll($select_query);
                return $rowlist->toArray(); 				
			}
			
	public function getmindata($minid)
		{
			
			$select_table = new Zend_Db_Table('dbt_ministry');
			$select_query =  $select_table->select();
			$select_query->from(array('ministry' => 'dbt_ministry'));					
			$select_query->where('id=?',$minid);	
			$select_query->order('ministry.ministry_name');
			$rowlist = $select_table->fetchAll($select_query);
			return $rowlist->toArray(); 				
		}
		
		public function getbeneficiarydata($month,$financial_year_from,$financial_year_to,$scheme_id)
		{
			
			$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
			$select_query =  $select_table->select();
			$select_query->from(array('beneficaryscheme' => 'dbt_beneficaryscheme'));					
			$select_query->where('scheme_id=?',$scheme_id);	
			$select_query->where('month=?',$month);	
			$rowlist = $select_table->fetchAll($select_query);
			return $rowlist->toArray(); 				
		}
		public function getschememanualdatareport($month,$financial_year_from,$financial_year_to,$scheme_id)
		{
			
			$select_table = new Zend_Db_Table('dbt_scheme_manual_data');
			$select_query =  $select_table->select();
			$select_query->from(array('beneficaryscheme' => 'dbt_scheme_manual_data'));					
			$select_query->where('scheme_id=?',$scheme_id);	
			$select_query->where('month=?',$month);	
			$rowlist = $select_table->fetchAll($select_query);
			return $rowlist->toArray(); 				
		}	

	
	/*****************end pmo report ******************************/
	
public function editbeneficiarydataclient($id)
	        {
                $select_table = new Zend_Db_Table('dbt_beneficaryscheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;     
			
			}
public function getschemename($scheme_id){
				$select_table = new Zend_Db_Table('dbt_scheme');
				$row = $select_table->fetchAll($select_table->select()->where('id = ? ',$scheme_id));
				return $row;
		}
/*************count the record from the beneficiary table based on the schmeme_id,month and year****/
		public function countbeneficiarydatamonthyearwise($scheme_id = null,$year = null,$month  = null){
			$yearval = explode("-", $year);
			$financial_year_from = $yearval[0];
			$financial_year_to = $yearval[1];
			$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
			$select_query =  $select_table->select();
			$select_query->from(array('beneficiary' => 'dbt_beneficaryscheme'));		
			$select_query->where('scheme_id=?',$scheme_id);	
			$select_query->where('financial_year_from=?',$financial_year_from);	
			$select_query->where('financial_year_to=?',$financial_year_to);	
			$select_query->where('month=?',$month);	
			$rowlist = $select_table->fetchAll($select_query);
			//echo $select_query; die;
			return count($rowlist); 				
		}
	/******************end**********************************/
		public function beneficiarydatalist($start,$limit,$scheme_id){   
			    $role = new Zend_Session_Namespace('role');
				$userid = new Zend_Session_Namespace('userid'); 
				$user_role = $role->role;
				
				$month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];

				$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);

				$select->from(array('smd' => 'dbt_beneficaryscheme'), array('id','scheme_grouping','totalnoofbeneficiaries','totalnoofbeneficiarieswithbankac','totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiarieswithseededbankac','month','financial_year_from','financial_year_to','status'));			
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
			

			
			/*************count the record from the beneficiary table****/

		public function countbeneficiarydata($scheme_id){
                $month = $_GET['month'];
				$year = $_GET['year'];
				$yearval = explode("-", $year);
				$financial_year_from = $yearval[0];
				$financial_year_to = $yearval[1];
				$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
				$select_query =  $select_table->select();
				$select_query->from(array('beneficiary' => 'dbt_beneficaryscheme'));		
				$select_query->where('scheme_id=?',$scheme_id);	
               if($month && $month != 0){
					$select_query->where('month = ?', $month);
				}				
				if($financial_year_from && $financial_year_from != 0){
					$select_query->where('financial_year_from = ?', $financial_year_from);
				}
				if($financial_year_to && $financial_year_to != 0){
					$select_query->where('financial_year_to = ?', $financial_year_to);
				}
				$rowlist = $select_table->fetchAll($select_query);
				//echo $select_query; die;
                return count($rowlist); 				
			}
	/******************end**********************************/
		public function insertschemebeneficarydata($dataform = null,$scheme_id = null,$min_id  = null){
			$data_table = new Zend_Db_Table('dbt_beneficaryscheme');
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
public function checkasignedschemeid($userid,$scheme_id){
		$select_table = new Zend_Db_Table('dbt_assign_manager');
		$row = $select_table->fetchAll($select_table->select()->where("find_in_set(".$scheme_id.", scheme_id) and pm_id=".$userid));
		return count($row);
}
			
/**************edit record based in the beneficiary table*******/
	public function editbeneficary($editdataform = null,$id = null)
	{
		// print '<pre>';
		// print_r($editdataform);
		// exit;
				$selectable = new Zend_Db_Table('dbt_beneficaryscheme');

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
	
	
		public function editbeneficarybackup($editdataform = null,$id = null)
	{
		// print '<pre>';
		// print_r($editdataform);
		// exit;
				$selectable = new Zend_Db_Table('dbt_beneficaryscheme');

						$data="";
						$where="";		
						$totalnoofbeneficiaries = $editdataform['totalnoofbeneficiaries'];
						$totalnoofbeneficiarieswithbankac = $editdataform['totalnoofbeneficiarieswithbankac'];	
						$totalnoofbeneficiarieswithaadhaar = $editdataform['totalnoofbeneficiarieswithaadhaar'];	
						$totalnoofbeneficiarieswithseededbankac = $editdataform['totalnoofbeneficiarieswithseededbankac'];	
	
						
						$data = array(
						  'totalnoofbeneficiaries' => $totalnoofbeneficiaries,
						  'totalnoofbeneficiarieswithbankac' => $totalnoofbeneficiarieswithbankac,
						  'totalnoofbeneficiarieswithaadhaar' => $totalnoofbeneficiarieswithaadhaar,
						  'totalnoofbeneficiarieswithseededbankac' => $totalnoofbeneficiarieswithseededbankac

							);
						
						$where = array('id = ?'=> $id);
						$update_values = $selectable->update($data,$where);
						
						return $update_values;
	}


}
?>