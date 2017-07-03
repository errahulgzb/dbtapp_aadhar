<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class Application_Model_Successstory extends Zend_Db_Table_Abstract 
{

	public function getuserinfo($userid)
	{
		  $select_table = new Zend_Db_Table('dbt_users');
		  $row = $select_table->fetchAll($select_table->select()->where('id = ?', $userid));
		  $rowarr = $row->toArray();
		  return $rowarr; 
	}

	public function insertstory($dataform,$userid,$status)
	{
		$insert_story = new Zend_Db_Table('dbt_success_story');
		$data="";
		$data = array(
				'title'=> $dataform['title'],
				'author'=> $dataform['author'],
				'description'=> $dataform['description'],
				'status'=> $status,
				'updated_by'=> $userid,
				'created_by'=> $userid,
				'created_on'=> date("Y/m/d h:i:s")
			);
		return $insertstory = $insert_story->insert($data);
	}
	public function storylist($start,$limit)
	{
		  // $select_table = new Zend_Db_Table('dbt_success_story');
		  // $row = $select_table->fetchAll($select_table->select()->where('status = ?', 2)->order('title DESC')->limit($limit,$start));
		  // $rowarr = $row->toArray();
		  // return $rowarr; 

				$select_table = new Zend_Db_Table('dbt_success_story');
				$select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('story' => 'dbt_success_story'));				
                $select->join(array('users' => 'dbt_users'), 'story.created_by = users.id', array('firstname', 'lastname', 'email', 'role'));
				$select->join(array('role' => 'dbt_roles'), 'users.role = role.id', array('title as rolename'));
				$select->where('story.status = ?', 2);
				$select->where('story.action = ?', 1);
				$select->order('updated_on DESC');
				$select->limit($limit,$start);
				$select_org = $select_table->fetchAll($select);
				$rowarr = $select_org->toArray();
                return $rowarr;

	}
	public function countstory()
	{
		  $count_table = new Zend_Db_Table('dbt_success_story');
		  $count_row = $count_table->fetchAll($count_table->select()->where('status = ?', 2)->where('action = ?', 1)->order('title DESC'));
		  return count($count_row); 
	}
	public function mystorylist($start,$limit,$userid)
	{
		  $select_table = new Zend_Db_Table('dbt_success_story');
		  $row = $select_table->fetchAll($select_table->select()->where('created_by = ?', $userid)->order('updated_on DESC')->limit($limit,$start));
		  $rowarr = $row->toArray();
		  return $rowarr; 
	}
	public function countmystory($userid)
	{
		  $count_table = new Zend_Db_Table('dbt_success_story');
		  $count_row = $count_table->fetchAll($count_table->select()->where('created_by = ?', $userid)->order('title DESC'));
		  return count($count_row); 
	}
	public function allstorylist($start,$limit)
	{
		  // $select_table = new Zend_Db_Table('dbt_success_story');
		  // $row = $select_table->fetchAll($select_table->select()->order('created_on DESC')->limit($limit,$start));
		  // $rowarr = $row->toArray();
		  // return $rowarr; 
				$select_table = new Zend_Db_Table('dbt_success_story');
				$select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('story' => 'dbt_success_story'));				
                $select->join(array('users' => 'dbt_users'), 'story.created_by = users.id', array('firstname', 'lastname', 'email'));
				$select->order('updated_on DESC');
				$select->limit($limit,$start);
				$select_org = $select_table->fetchAll($select);
				$rowarr = $select_org->toArray();
                return $rowarr;
	}
	public function countallstory()
	{
		  $count_table = new Zend_Db_Table('dbt_success_story');
		  $count_row = $count_table->fetchAll($count_table->select()->order('title DESC'));
		  return count($count_row); 
	}
	public function storyrequestslist($start,$limit)
	{
		  // $select_table = new Zend_Db_Table('dbt_success_story');
		  // $row = $select_table->fetchAll($select_table->select()->where('status = ?', 1)->order('title DESC')->limit($limit,$start));
		  // $rowarr = $row->toArray();
		  // return $rowarr; 



				$select_table = new Zend_Db_Table('dbt_success_story');
				$select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('story' => 'dbt_success_story'));				
                $select->join(array('users' => 'dbt_users'), 'story.created_by = users.id', array('firstname', 'lastname', 'email'));
				$select->where('story.status = ?', 1);
				$select->order('updated_on DESC');
				$select->limit($limit,$start);
				$select_org = $select_table->fetchAll($select);
				$rowarr = $select_org->toArray();
                return $rowarr;

	}
	public function countstoryrequests()
	{
		  $count_table = new Zend_Db_Table('dbt_success_story');
		  $count_row = $count_table->fetchAll($count_table->select()->where('status = ?', 1)->order('title DESC'));
		  return count($count_row); 
	}

	public function storydetail($id)
	{
		// $select_table = new Zend_Db_Table('dbt_success_story');
		// $rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
		// return $rowselect;     

		$select_table = new Zend_Db_Table('dbt_success_story');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('story' => 'dbt_success_story'));				
		$select->join(array('users' => 'dbt_users'), 'story.created_by = users.id', array('firstname', 'lastname', 'email'));
		$select->where('story.id = ?', trim(intval($id)));
		$select_org = $select_table->fetchrow($select);
		$rowarr = $select_org->toArray();
		return $rowarr;
	}

	public function storycomments($id)
	{
		$select_table = new Zend_Db_Table('dbt_story_comments');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('comment' => 'dbt_story_comments'));				
		$select->join(array('users' => 'dbt_users'), 'comment.created_by = users.id', array('firstname', 'lastname', 'email'));
		$select->where('comment.story_id = ?', trim(intval($id)));
		$select->order('updated_on DESC');
		$select_org = $select_table->fetchAll($select);
		$rowarr = $select_org->toArray();
		return $rowarr;
	}

	public function approvalcomments($id)
	{
		$select_table = new Zend_Db_Table('dbt_story_approval_comments');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		$select->from(array('comment' => 'dbt_story_approval_comments'));				
		$select->join(array('users' => 'dbt_users'), 'comment.created_by = users.id', array('firstname', 'lastname', 'email'));
		$select->where('comment.story_id = ?', trim(intval($id)));
		$select->order('updated_on DESC');
		$select_org = $select_table->fetchAll($select);
		$rowarr = $select_org->toArray();
		return $rowarr;
	}

	public function editstory($editdataform,$id,$userid,$status,$action)
	{	
		$update_story = new Zend_Db_Table('dbt_success_story');
		$data="";
		$where="";
		$data = array(
				'title'=> $editdataform['title'],
				'author'=> $editdataform['author'],
				'description'=> $editdataform['description'],
				'updated_by'=> $userid,
				'status'=> $status,
				'action'=> $action
			);
		$where = array('id = ?'=> $id);
		$update_values = $update_story->update($data, $where);
	}

	public function deletestory($id)
	{
			$delete_story = new Zend_Db_Table('dbt_success_story');
			$where="";
			$where = array('id = ?'      => $id);
			$delete_values = $delete_story->delete($where);
	}
	
	public function activestory($id)
	{	
		$update_story = new Zend_Db_Table('dbt_success_story');
		$data="";
		$where="";
		$data = array(
				'action'=> 1
			);
		$where = array('id = ?'=> $id);
		$update_values = $update_story->update($data, $where);
	}

	public function inactivestory($id)
	{	
		$update_story = new Zend_Db_Table('dbt_success_story');
		$data="";
		$where="";
		$data = array(
				'action'=> 0
			);
		$where = array('id = ?'=> $id);
		$update_values = $update_story->update($data, $where);
	}
	
	public function insertcomment($dataform,$userid,$storyid)
	{
		$insert_comment = new Zend_Db_Table('dbt_story_comments');
		$data="";
		$data = array(
				'description'=> $dataform['description'],
				'status'=> 1,
				'story_id'=> $storyid,
				'updated_by'=> $userid,
				'created_by'=> $userid,
				'created_on'=> date("Y/m/d h:i:s")
			);
		return $insertcomment = $insert_comment->insert($data);
	}

	public function deletecomment($id)
	{
			$delete_comment = new Zend_Db_Table('dbt_story_comments');
			$where="";
			$where = array('id = ?'      => $id);
			$delete_values = $delete_comment->delete($where);
	}

	public function storyapprovalcomment($approvalcomment,$storyid,$userid,$status)
	{
		$insert_approvalcomment = new Zend_Db_Table('dbt_story_approval_comments');
		$data="";
		$data = array(
				'approval_comment'=> $approvalcomment,
				'status'=> $status,
				'story_id'=> $storyid,
				'updated_by'=> $userid,
				'created_by'=> $userid,
				'created_on'=> date("Y/m/d h:i:s")
			);
		return $storyapprovalcomment = $insert_approvalcomment->insert($data);
	}
}
