<?php
require_once 'Zend/Db/Table/Abstract.php';

class Application_Model_Statedistrict extends Zend_Db_Table_Abstract 
{

			
			// Ajax function start from here 
			
			
		public function statedistrictlist($statename_id)
			{
				  $select_table = new Zend_Db_Table('dbt_district');
				//  $row = $select_table->fetchAll($select_table->select()->where('status =1 and state_code ='.$statename_id)->order('district_name DESC'));
				  echo "ssdjsdjjsdj"; exit;
				  return $row; 
			}
			
			
		
			
			
			
			
			
}