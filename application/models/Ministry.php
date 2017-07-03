<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Ministery extends Zend_Db_Table_Abstract 
{
	
	public function ministrylist($start,$limit)
			{
				//echo "hi";
				$search = $_GET['search'];
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('p' => 'dbt_ministry'), array('id','title','details','status', 'created','updated'));

				//->joinLeft(array('u' => 'users'), 'p.customer_id = u.id', array('firstname','lastname','organisation'))
				//->where('p.title LIKE ?', '%'.$search.'%')
				//->ORwhere('p.plan_of_act LIKE ?', '%'.$search.'%')
				//->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				//->order('p.id DESC')->limit($limit,$start);
				//->order('p.title DESC')->limit($limit,$start);	

				$select_org = $select_table->fetchAll($select);
				return $select_org;
			}
			
			 public function countministery()
			{
				 $search = $_GET['search'];
				$select_table = new Zend_Db_Table('dbt_ministry');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('p' => 'dbt_ministry'), array('id','title','details','status', 'created','updated'));

				//->joinLeft(array('u' => 'users'), 'p.customer_id = u.id', array('firstname','lastname','organisation'))
				//->where('p.title LIKE ?', '%'.$search.'%')
				//->ORwhere('p.plan_of_act LIKE ?', '%'.$search.'%')
				//->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
				//->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
				//->order('p.id DESC');
				$select_org = $select_table->fetchAll($select);
			   return count($select_org); 
			}

}
?>