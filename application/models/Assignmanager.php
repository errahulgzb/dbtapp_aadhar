<?php
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Assignmanager extends Zend_Db_Table_Abstract 
{
    public function insertData($dataform)
    {
        $user_table = new Zend_Db_Table('dbt_assign_manager');
        if($dataform['projectmanager']){
            $tempmanager=$dataform['projectmanager'];
        }else{
            $tempmanager="";
        }
        
        if($dataform['projectname']){
            $pid=implode(",",$dataform['projectname']);
        }else{
            $pid="";
        }
        
        
        $datainsert = array(
            'pm_id'=> $tempmanager,
            'scheme_id'=> $pid,
            'created'=> time(),
            'status'=> '1'
        );
        $insertdata=$user_table->insert($datainsert);
        return $insertdata;
    }
	
	// changes for resassign  projects
	
	
    public function updateData($editdataform,$id)
    {    
         
		 $userid = new Zend_Session_Namespace('userid');
		 $scheme_name = new Zend_Session_Namespace('scheme_name');
		 $admin_id = $userid->userid;
		// check if project assign to same  project manager	
			$projectman_id = $id;
			$select_table = new Zend_Db_Table('dbt_assign_manager');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p' => 'dbt_assign_manager'), array('id','pm_id','scheme_id','status'));				
            
            $select->where('p.pm_id = ?',trim(intval($id)));
            $rowselect = $select_table->fetchAll($select);
            $pids=$rowselect->toArray();
			//storing project count previous, before updation
			$last_pr = $pids[0]['scheme_id'];
			
			$projectArray=explode(",",$pids[0]['scheme_id']);
			$count = count($projectArray);
			
			
      if($pids[0]['pm_id']){//if scheme owner already exist in the dbt_assign_manager table
//******** new assigned scheme check*******************
			$have_user = $this->checkuser(intval($id));
			if($have_user > 0){
			$allassigschemeowner= $this->get_all_assign_scheme_owner(intval($id));// get scheme owner id assign by administrator 
				//die("skdfcs");
			$allassigschemestate= $this->get_all_assign_scheme_state(intval($id));// get scheme assign id array by state user
		 
			$scheme_assign_id	= implode(",",$allassigschemestate);
			$scheme_name->scheme_name='';
			
			if($editdataform['projectname']!=''){
				$exist_scheme_name=array();
			//echo "<pre>";print_r($allassigschemestate);die("sdskld");
			for($i=0;$i<sizeof($allassigschemestate);$i++){
					if(!in_array($allassigschemestate[$i],$editdataform['projectname'])){
					$get_scheme_name= $this->get_scheme_name($allassigschemestate[$i]);
					$exist_scheme_name=array_merge($exist_scheme_name,$get_scheme_name);
				}
			}
				
				$exist_scheme="";
				for($i=0;$i<sizeof($exist_scheme_name);$i++){
				$exist_scheme .=$exist_scheme_name[$i]['scheme_name'].",";
				}
				
				$total_scheme_name=substr($exist_scheme,0,-1);
				$scheme_name->scheme_name=$total_scheme_name;
			}else{
				
				$exist_scheme_name=array();
				for($i=0;$i<sizeof($allassigschemestate);$i++){
					if(!in_array($allassigschemestate[$i],$editdataform['projectname'])){
						$get_scheme_name= $this->get_scheme_name($allassigschemestate[$i]);
						$exist_scheme_name=array_merge($exist_scheme_name,$get_scheme_name);
					 }
					}
				//echo "<pre>";print_r($exist_scheme_name);die;
				$exist_scheme="";
				for($i=0;$i<sizeof($exist_scheme_name);$i++){
				//$exist_scheme_name[$i]['scheme_name'];
				$exist_scheme .=$exist_scheme_name[$i]['scheme_name'].",";
				}
				$total_scheme_name=substr($exist_scheme,0,-1);
				$scheme_name->scheme_name=$total_scheme_name;
				$editdataform['projectname']=array();
			  }				 
				
		$array_scheme_final_update=array_unique(array_merge($editdataform['projectname'],$allassigschemestate));
		}else{
				$array_scheme_final_update=$editdataform['projectname'];
		}

            if($array_scheme_final_update){
				$pid = implode(",",$array_scheme_final_update);
            }
			else{
				$pid="";
            }
            $projectArray1=explode(",",$pid);
			$pid_cont = count($projectArray1);
//if admin remove any project from dbt_assign_manager
                    $date =time();
                    $updatedetails_selecttable = new Zend_Db_Table('dbt_assign_manager');
                    $data = array(
                            'scheme_id'=> $pid,	
                            'changed'=> $date,
                            'status'=> 1
                            );
               
                    $pmid=$editdataform['projectmanager'];
                    $where = array('pm_id = ?'=>$pmid) ;
                    $update_values = $updatedetails_selecttable->update($data, $where);

                $select_tablep1 = new Zend_Db_Table('dbt_assign_manager');
				$selectp = $select_tablep1->select();
				$selectp->setIntegrityCheck(false);
				$selectp->from(array('p' => 'dbt_assign_manager'), array('id','pm_id','scheme_id','status'));				

				$selectp->where('p.pm_id = ?',trim(intval($id)));
				$rowselectp1 = $select_tablep1->fetchAll($selectp);
				$pidsp1=$rowselectp1->toArray();
				$projectids1 =explode(",",$pidsp1[0]['scheme_id']);
                
				// print_r($projectArray);echo"<br />";
                 //print_r($projectids1);echo"<br />";
                 $updated_diff = array_diff($projectids1,$projectArray);
				 $oldval_diff = array_diff($projectArray,$projectids1);
				
				 if(empty($updated_diff) && empty($oldval_diff)){
				       //not required any action regarding mail
				 }
		if(count($updated_diff)>0 && array_values($updated_diff)[0]!='')
				 {
					 //echo "<pre>";print_r($updated_diff);
				     //echo "new assign mail";
              foreach($updated_diff as $pro_id)
			   {
				  $select_projecttable1 = new Zend_Db_Table('dbt_scheme');
				  $select = $select_projecttable1->select();
				  $select->setIntegrityCheck(false);
				 
				  $select->from(array('p' => 'dbt_scheme'),array('id','scheme_name as projectname')); 		 

				  $select->joinLeft(array('u'=>'dbt_ministry'),'u.id=p.ministry_id',array('ministry_name'));                  				  
				  $select->where('p.id = ?',trim($pro_id));                  
				  $datareturn = $select_projecttable1->fetchAll($select);
				  $return_data[] = $datareturn->toArray();
				  }

				  /*Finding here project manager name and email*/
                  $select_projecttable3 = new Zend_Db_Table('dbt_users');
				  $select1 = $select_projecttable3->select();

				  $select1->from(array('u'=>'dbt_users'),array('id','firstname as pm_name','lastname as pm_last','email'));

				  $select1->where('u.id = ?',trim($projectman_id));
                  $datareturn1 = $select_projecttable3->fetchRow($select1);
				  $return_data1 = $datareturn1->toArray();
				  
		/*MAIL OBJECT*/
				  
				     $mailObj = new Zend_Mail();
                     $username = $return_data1['pm_name']." ".$return_data1['pm_last'];
                     $weblink = WEB_LINK;
					 $body =  ASSIGN_PROJECT_MESSAGE_BODY;
					 $body = str_replace('{user_name}',$username,$body);
					 $body = str_replace('{web_link}',$weblink,$body);
                  foreach($return_data as $maildata1){
				      foreach($maildata1 as $maildata){
					     $body.='Ministry: <strong>'.trim($maildata['ministry_name']).'</strong><br />';
						 $body.='Scheme Name: <strong>'.trim($maildata['projectname']).'</strong><br />';
						  $body.='<br/>';
						 }
					}
					//echo $body.$return_data1['email'];exit;
					        $Second =   ASSIGN_PROJECT_MESSAGE_BODY_SECOND;
							$Second = str_replace('{web_link}',$weblink,$Second);
							$concat = $body.$Second;
							//echo $concat;
                            $subject= ASSIGN_PROJECT_MAIL_SUBJECT;
                            $to=$return_data1['email'];
							//$to='upendra.yadav@velocis.co.in';
							//echo $concat;exit;
                            $from=MAIL_FROM;
                            $name = MAIL_NAME_ASSIGN_PROJECT;
                            $mailObj->setSubject($subject);
                            $mailObj->setBodyHtml($concat);
                            $mailObj->addTo($to, $to);
                            $mailObj->setFrom($from, "DBT APP");
                            $mailObj->send();
				 }
				
			if(count($oldval_diff)>0  && array_values($oldval_diff)[0]!=''){
				//echo "aaaa";exit;
					//this email will send for the withdraw
				      //echo "<pre>";print_r($oldval_diff);
                      //echo "withdraw mail";
                      //print_r($oldval_diff);exit;
			//if($oldval_diff[0]!= ""){
				//echo "aaaabbbb";exit;
			 	foreach($oldval_diff as $pro_id1){
					//print_r($oldval_diff);die;
				  $select_projecttable12 = new Zend_Db_Table('dbt_scheme');
				  $select1 = $select_projecttable12->select();
				  $select1->setIntegrityCheck(false);
				 
				  $select1->from(array('p' => 'dbt_scheme'),array('id','scheme_name as projectname1','ministry_id'));				 
				  $select1->joinLeft(array('u'=>'dbt_ministry'),'u.id=p.ministry_id',array('ministry_name'));
                  				  
				  $select1->where('p.id = ?',trim($pro_id1));
                  //echo $select1;exit;
				  $datareturn = $select_projecttable12->fetchAll($select1);
				  $return_data1[] = $datareturn->toArray();
				  }
				  /*Finding here project manager name and email*/
                  $select_projecttable31 = new Zend_Db_Table('dbt_users');
				  $select12 = $select_projecttable31->select();
				  $select12->from(array('u'=>'dbt_users'),array('id','firstname as pm_name','lastname as pm_last','email'));
				  $select12->where('u.id = ?',trim($projectman_id));
                  $datareturn12 = $select_projecttable31->fetchRow($select12);
				  $return_data12 = $datareturn12->toArray();
				      
							$mailObj = new Zend_Mail();
                            $username = $return_data12['pm_name']." ".$return_data12['pm_last'];
                            $weblink = WEB_LINK;
                            $body =   DROP_PROJECT;
							$body = str_replace('{user_name}',$username,$body);
                      foreach($return_data1 as $maildata12){
					     foreach($maildata12 as $maildata1){
							  $body.='Ministry Name: <strong>'.trim($maildata1['ministry_name']).'</strong><br />';
							  $body.='Scheme Name: <strong>'.trim($maildata1['projectname1']).'</strong><br />';
							  $body.='<br/>';
						   } 
						}
				//echo $return_data12['email'].$body;exit;
							$Second =   DROP_PROJECT_SECOND;
							$Second = str_replace('{web_link}',$weblink,$Second);
							$concat = $body.$Second;
							//echo "<br />".$concat; exit;
                            $subject= DROP_PROJECT_MAIL_SUBJECT;
                            $to=$return_data12['email'];
							//$to='upendra.yadav@velocis.co.in';
							//echo $concat;exit;
                            $from=MAIL_FROM;
                            $name = MAIL_NAME_DROP_PROJECT;
                            $mailObj->setSubject($subject);
                            $mailObj->setBodyHtml($concat);
                            $mailObj->addTo($to, $to);
                            $mailObj->setFrom($from, $name);
                           $mailObj->send(); 
					//}
					//echo "aasssssaa";exit;
        
		 }
 



                 /*******************END****************/
                    return $update_values;
        
            }else{
				//echo "aaaaa";exit;
                
                $first_insert = $id;
                $user_table = new Zend_Db_Table('dbt_assign_manager');
                   if($dataform['projectmanager']){
                       $tempmanager=$editdataform['projectmanager'];
                   }else{
                       $tempmanager="";
                   }

                   if($editdataform['projectname']){
                       $pid=implode(",",$editdataform['projectname']);
                   }else{
                       $pid="";
                   }
				   
                    $datainsert = array(
                       'pm_id'=> $editdataform['projectmanager'],
                       'scheme_id'=> $pid,
                       'created'=> time(),
                       'status'=> '1'
                   );
                   $insertdata=$user_table->insert($datainsert);

   /****When user new and not entry regarding in database then send mail on first time*****/
            $exe_arr = explode(",",$pid);
             //print_r($exe_arr);die;
	    
           foreach($exe_arr as $pro_id)
				  {
					//echo $pro_id1;die;
				   $select_projecttable1 = new Zend_Db_Table('dbt_scheme');
				  $select = $select_projecttable1->select();
				  $select->setIntegrityCheck(false);
				 
				  $select->from(array('p' => 'dbt_scheme'),array('id','scheme_name as projectname','ministry_id')); 		 

				  $select->joinLeft(array('u'=>'dbt_ministry'),'u.id=p.ministry_id',array('ministry_name'));                  				  
				  $select->where('p.id = ?',trim($pro_id));                  
				  $datareturn = $select_projecttable1->fetchAll($select);
				  $return_data[] = $datareturn->toArray();
				  }

				  /*Finding here project manager name and email*/
                  $select_projecttable3 = new Zend_Db_Table('dbt_users');
				  $select1 = $select_projecttable3->select();
				  $select1->from(array('u'=>'dbt_users'),array('id','firstname as pm_name','lastname as pm_last','email'));
				  $select1->where('u.id = ?',trim($projectman_id));
                  $datareturn1 = $select_projecttable3->fetchRow($select1);
				  $return_data1 = $datareturn1->toArray();
				  
		/*MAIL OBJECT*/
				  
				     $mailObj = new Zend_Mail();
                     $username = $return_data1['pm_name']." ".$return_data1['pm_last'];
                     $weblink = WEB_LINK;
					 $body =   ASSIGN_PROJECT_MESSAGE_BODY;
					 $body = str_replace('{web_link}',$weblink,$body);
					 $body = str_replace('{user_name}',$username,$body);
                  foreach($return_data as $maildata1){
				      foreach($maildata1 as $maildata){
					     $body.='Ministry Name: <strong>'.trim($maildata['ministry_name']).'</strong><br />';
						 $body.='Scheme Name: <strong>'.trim($maildata['projectname']).'</strong><br />';

						  $body.='<br/>';
						 }
					}
					//echo $return_data1['email'].$body;exit;
					        $Second =   ASSIGN_PROJECT_MESSAGE_BODY_SECOND;
							$Second = str_replace('{web_link}',$weblink,$Second);
							$concat = $body.$Second;
							//echo $concat; exit;
                            $subject= ASSIGN_PROJECT_MAIL_SUBJECT;
                            //$to=$return_data1['email'];
							$to='upendra.yadav@velocis.co.in';
                            $from=MAIL_FROM;
                            $name = MAIL_NAME_ASSIGN_PROJECT;
                            $mailObj->setSubject($subject);
                            $mailObj->setBodyHtml($concat);
                            $mailObj->addTo($to, $to);
                            $mailObj->setFrom($from, $name);
                          $mailObj->send();

   /*******MAIL END*******/
                   return $insertdata;
            }
    }


	
    public function deleteData($id)
    {
        $delete_project = new Zend_Db_Table('dbt_assign_manager');
        $where="";
        $where = array('id = ?' => $id);
        $delete_values = $delete_project->delete($where);

    }
    
    
    public function userList($id='')
    {
        $select_table = new Zend_Db_Table('dbt_users');
        //$row = $select_table->fetchAll($select_table->select()->where('status =1 and role=4')->order('id DESC'));
        
        $select = $select_table->select();
        $select->where('status = 1 and role=4');
        if(!empty($id)){
            $select->where('id = ?',trim(intval($id)));
        }
         
        $select->order('id DESC');
        //echo $select;die;
        $row=$select_table->fetchAll($select);

        return $row; 
    }
    
    //////////////////ddddddddddddddddddddddddd////////////////
    
     public function userListAdd()
    {
        $select_table = new Zend_Db_Table('dbt_users');
        $select = $select_table->select();
        $select->setIntegrityCheck(false);
        $select->from(array('u' => 'dbt_users'), array('id','firstname','lastname','status','role'));				
        //$select->joinLeft(array('am' => 'dbt_assign_manager'),  'u.id=am.pm_id' , array('pm_id as pmid','scheme_id'));
        
        $select->where('u.status = 1 and u.role=4');
       // $select->where('am.pm_id IS NULL');
         
        $select->order('u.id DESC');
     
        $row=$select_table->fetchAll($select);
        return $row; 
    }
    
    
    public function userListEdit($id)
    {
        
		$select_table = new Zend_Db_Table('dbt_users');
        $select = $select_table->select();
        $select->setIntegrityCheck(false);
        $select->from(array('u' => 'dbt_users'), array('id','firstname','lastname','status','role'));
		if(intval($id)){
			$select->where('u.id = ?',$id);
		}
		$select->where('u.role in(4)');
        $select->order('u.firstname ASC');
        $row=$select_table->fetchAll($select);
        return $row; 
    }


	 public function assignedmanager($id){
        
		$select_table = new Zend_Db_Table('dbt_users');
        $select = $select_table->select();
        $select->setIntegrityCheck(false);
        $select->from(array('u' => 'dbt_users'), array('id','firstname','lastname','status','role'));				
        //$select->joinLeft(array('am' => 'dbt_assign_manager'),  'u.id=am.pm_id' , array('pm_id as pmid','scheme_id'));
        $select->where('u.id=?',trim(intval($id)));
        //$select->where('u.role=4');
		//$select->where('u.role in (4,6)');
		$select->where('u.role in ("4","6")');
		//echo $select;//exit;
        $row=$select_table->fetchRow($select);
        return $row; 
    }
    
    ////////////////ddddddddddddddddddddddddd///////////////////

    public function projectList($id='')
    {
        $select_table = new Zend_Db_Table('projects');
        //$row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC'));
        $select = $select_table->select();
        $select->where('status = ? ','1');
        if(!empty($id)){
            $select->ORwhere('id = ?',trim(intval($id)));
        }
        $select->order('title DESC');
        $row=$select_table->fetchAll($select);
        
        return $row; 
    }

	
	//below function is use for the display assigned schemes to the managers
	//$start and $limit is using for the limit with pagination of the listing
    public function assignManagerList($start,$limit){
			$userid = new Zend_Session_Namespace('userid');
			$role = new Zend_Session_Namespace('role');
            $select_table = new Zend_Db_Table('dbt_assign_manager');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $search = trim($_GET['search']);
            $ids=$this->findProjectsId($search);
            $idsarray=  explode("~", $ids);
			$select->from(array('u' => 'dbt_users'), array('id','username','firstname','lastname','email','role'));
			$select->joinLeft(array('amanager' => 'dbt_assign_manager'), 'u.id = amanager.pm_id and u.role=4', array('id as asid','pm_id','scheme_id','created','changed','status'));
			$select->joinLeft(array('p' => 'dbt_scheme'), 'p.id in(amanager.scheme_id)', array('p.scheme_name as projectname'));
			
			$select->joinLeft(array('roles' => 'dbt_roles'), 'u.role = roles.id', array('title as role_name'));
			$select->where('u.status = 1 and u.role in(4)');
			//$select->where('u.role =?',4);
			
			//$select->where("u.created_by = ?",$userid->userid);
			
			
			# This code is commented by Abhishek on 4 Feb 2015 because we are displaying all the user who is project nanager and then assign the project by using Assign Project Link.
			# We need to add search from first name and last name on second priority.			
		    $select->where('u.firstname LIKE ? or  u.lastname LIKE ?','%'.$search.'%','%'.$search.'%');
            for($i=0; $i< (count($idsarray)-1); $i++){
               $select->ORwhere('find_in_set(?,amanager.scheme_id)',$idsarray[$i]);
            }
			
            $select->order('amanager.id DESC')->limit($limit,$start);
            ///echo $select;die;
            $select_org = $select_table->fetchAll($select);
            $arrayVal=$select_org->toArray();
            foreach($arrayVal as $key=>$val){
                $projectTitle=$this->showProjectsTitle($val['scheme_id']);
                array_push($arrayVal[$key],$projectTitle);
            }
            return $arrayVal;
    }
    
    
    public function findProjectsId($search=NULL)
    {   
       
            $select_table = new Zend_Db_Table('dbt_scheme');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p' => 'dbt_scheme'), array('id','scheme_name','status'))				
            
            ->where('p.scheme_name LIKE ?','%'.$search.'%');
            //->order('p.title DESC');
            $select_org = $select_table->fetchAll($select);
            $arrayVal=$select_org->toArray();
            foreach($arrayVal as $val){
                $ids.=$val['id']."~";
            }
            
       return $ids;       
    }

	 public function allProjectsId()
    {   
       
            $select_table = new Zend_Db_Table('projects');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p' => 'projects'), array('id','title','created','changed','status'));
            //->order('p.title DESC');
            $select_org = $select_table->fetchAll($select);
            $arrayVal=$select_org->toArray();
            foreach($arrayVal as $val){
                $ids.=$val['id']."~";
            }
            
       return $ids;       
    }
    
    
    
    
    public function showProjectsTitle($pid,$value='')
    {   
         $arraypid=explode(",",$pid);
         foreach($arraypid as $val){
            $select_table = new Zend_Db_Table('dbt_scheme');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p' => 'dbt_scheme'), array('id','scheme_name','status'))            
            ->where('p.id=?',$val)
            ->order('p.scheme_name DESC');
            $select_org = $select_table->fetchAll($select);
            $arrayVal=$select_org->toArray();
            $titleArray.=$arrayVal[0]['scheme_name']."~";
         }
        if(empty($value))
        {
            return $titleArray;
        }else{
            return $arraypid; 
        }        
    }

    public function countAssignManagerList(){  
			$userid = new Zend_Session_Namespace('userid');
             $select_table = new Zend_Db_Table('dbt_assign_manager');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            ///////////Searching /////////
            $search = trim($_GET['search']);
            $ids=$this->findProjectsId($search);
            $idsarray=  explode("~", $ids);
            $select->from(array('u' => 'dbt_users'), array('firstname','lastname'))
                ->joinLeft(array('amanager' => 'dbt_assign_manager'), 'u.id = amanager.pm_id and u.role=4', array('id','pm_id','scheme_id','created','changed','status'))
                ->joinLeft(array('p' => 'dbt_scheme'), 'p.id in(amanager.scheme_id)', array('p.scheme_name as projectname'))
				->joinLeft(array('roles' => 'dbt_roles'), 'u.role = roles.id', array('title as role_name'))
                //->where('u.status = 1 and u.role=4');
				//->where('u.status = 1 and u.role in(4,6)');
				->where('u.status = 1 and u.role in(4)');
                # This code is commented by Abhishek on 4 Feb 2015 because we are displaying all the user who is project nanager and then assign the project by using Assign Project Link.
                # We need to add search from first name and last name on second priority.
            //$select->ORwhere('u.firstname LIKE ?', '%'.$search.'%')
                   //->ORwhere('u.lastname LIKE ?', '%'.$search.'%');
            $select->where('u.firstname LIKE ? or  u.lastname LIKE ?','%'.$search.'%','%'.$search.'%');  
            for($i=0; $i< (count($idsarray)-1); $i++){
               $select->ORwhere('find_in_set(?,amanager.scheme_id)',$idsarray[$i]);
            }
			//$select->where("u.created_by = ?",$userid->userid);
            $select->order('amanager.id DESC')->limit($limit,$start);
			//echo "new query ".$select;exit;
            $select_org = $select_table->fetchAll($select);
			$arrayVal=$select_org->toArray();
			foreach($arrayVal as $key=>$val){
                $projectTitle=$this->showProjectsTitle($val['scheme_id']);
                array_push($arrayVal[$key],$projectTitle);
            }
            return count($arrayVal); 
    }
	
    //geeting here that which schemes are assign to getting pm id and will display
    public function showAssignManager($id=NULL, $value=NULL){
            $select_table = new Zend_Db_Table('dbt_assign_manager');
            $select = $select_table->select();
            $select->setIntegrityCheck(false);
            $select->from(array('p' => 'dbt_assign_manager'), array('id','pm_id','scheme_id','status'));				
            if(!empty($id)){
                $select->where('p.pm_id = ?',trim(intval($id)));
            }
            if(!empty($value)){
                $select->where('p.pm_id != ?',trim(intval($value)));
            }
			//echo $select;exit;
            $rowselect = $select_table->fetchAll($select);
           //print_r($rowselect);exit;
			return $rowselect;
    }
   public function pidList($pid){   
            /*************select all project id from dbt_assign_manager table****** Starts *****/
            $all_project=$this->showAssignManager($id=NULL, $pid);//this will give that what schemes are assign to the manager as comma seperated
            $project_array=$all_project->toArray();
            $arraymerge=array();
            $tempval='';
            foreach($project_array as $key=>$val){
                $tempval.=str_replace(",", " ", $val['scheme_id'])." ";
            }
            $arraymerge= array_unique(explode(" ", $tempval));
            /*************select all project id from dbt_assign_manager table****** Ends *****/
            $select_table = new Zend_Db_Table('dbt_scheme');//this would be changed as scheme
            $select = $select_table->select();
            $select->where('status = ? ','1');
            if(count($arraymerge)){
                $select->where('id not in(?)',$arraymerge);
            }
			//echo $select;exit;
            $rowarray=$select_table->fetchAll($select);
            //print_r($rowarray);exit;
            return $rowarray;
    }
	
	
	//the below function used into the edit assign manager section form and display all the scheme for the scheme owner
	//$pid is the project manager id
	public function selectpidList($pid){
            $select_table = new Zend_Db_Table('dbt_scheme');
            $select = $select_table->select();
            $select->where('status = ? ','1');
            $rowarray=$select_table->fetchAll($select);
            return $rowarray;
    }
                        
    public function edituserclient($id)
	        {
                 $select_table = new Zend_Db_Table('dbt_scheme');
				$rowselect = $select_table->fetchRow($select_table->select()->where('id = ?',trim(intval($id))));
				 return $rowselect;      
			
			}                    
                        
			
			public function locationuser($start,$limit)
			{
				  $select_table = new Zend_Db_Table('locations');
				  $row = $select_table->fetchAll($select_table->select()->where('status = ? ','1')->order('title DESC')->limit($limit,$start));
				// echo "<pre>";
				//	 print_r($row);
					//	echo "</pre>";
					//	die;
				  return $row; 
			
			}

			

			public function projectuseredit($id)
			{	
				
				  $site_table = new Zend_Db_Table('locations');
				  $projectrow = $site_table->fetchRow($site_table->select()->where('status = 1 and id='.$id));	
				 // echo $projectrow['scheme_id'];
				//echo "<pre>";print_r($projectrow); exit;
				  $select_table = new Zend_Db_Table('projects');
				  $row = $select_table->fetchAll($select_table->select()->where('status = 1 and customer_id='.$projectrow['customer_id'])->order('title DESC'));
				  return $row; 
			}
			
			



			
			
			
			
			

			public function editlocationdetails($editdataform,$id)
	        {
				//echo $editdataform['user_id'];
				//die;
				$date =time();
				$updatedetails_selecttable = new Zend_Db_Table('locations');

						$data="";
						$where="";						
						$data = array(
							'title'=> $editdataform['title'] ,
							'scheme_id'=> $editdataform['projectname'] ,
							'customer_id'=> $editdataform['customer'] ,	
							'changed'=> $date,
							'status'=> 1
							
							);
						
						
						$where = array('id = ?'=> $id);
						$update_values = $updatedetails_selecttable->update($data, $where);
			}

			

			public function inactivelocation($projectIds,$sttaus)
			{
					$updatedetails_selecttable = new Zend_Db_Table('locations');
					$data="";
					$where="";
					$data = array('status'=> $sttaus);
					$where = array('id IN (?)'=> $projectIds);
					$update_values = $updatedetails_selecttable->update($data, $where);
			}

			public function checklocationclient($name)
	        {	
			
				
				
				
				$select_table = new Zend_Db_Table('locations');
				
				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name))));
				
				
				return count($rowselect); 
			
			}

			public function checklocationclientEdit($name,$id)
	        {
				
				$select_table = new Zend_Db_Table('locations');

				$rowselect = $select_table->fetchAll($select_table->select()->where('title = ?',trim(($name)))->where('id <> ?',trim(intval($id))));
				return count($rowselect); 
			
			}

// function for ajax project
	
		public function projectlocationlist($customer_id)
			{  
				  $select_table = new Zend_Db_Table('projects');
				  $row = $select_table->fetchAll($select_table->select()->where('customer_id ='.$customer_id)->order('title DESC'));
				  
				
				 return $row; 
			}

// function customer project information 



public function customerproject_Info($pids){ 
			//$pids is the all scheme id in to the array
		        $nm22 = new Zend_Db_Table('dbt_scheme');
				$select = $nm22->select();
				$select->from(array('l' => 'dbt_scheme'), array('ministry_id'));
				$select->distinct();
				$select->where('l.id IN (?)', $pids);		
               $select->where('l.translation_id!=1');						
				$row22 = $nm22->fetchAll($select);				
				$cids =  $row22->toArray();
				//print_r($cids); exit;
				$data = $this->customerprojects($cids,$pids);
				return $data;
	}	

 public function customerprojects($cids,$pids)
	{ 
                 $record = array();
		        foreach($cids as $key=>$val)
				{
					$nm22 = new Zend_Db_Table('dbt_scheme');
					$select = $nm22->select();
					$select->from(array('l' => 'dbt_scheme'), array('id','ministry_id','scheme_name'));						
					$select->where('l.id IN (?)', $pids);	
					$select->where('ministry_id =?', $val);	
					$row22 = $nm22->fetchAll($select);				
					
					$record[$val['ministry_id']] =  $row22->toArray();
					
					
				}
				 //echo "<pre>";print_r($record);
				return $record ;
	}
	
	public function customerInformation($key)
		{
			//echo $key;exit;
		$ministry = new Zend_Db_Table('dbt_ministry'); //this would be ministry
		$getministry = $ministry->fetchRow($ministry->select()->where('id = ?',trim(intval($key))));
		return $getministry;
		}
//getting here the scheme for the display to managers
	public function schemeInfo($key)
		{
		$nm35 = new Zend_Db_Table('projects');
		$row35 = $nm35->fetchRow($nm35->select()->where('id = ?',trim(intval($key))));
		return $row35;
		}
//getting here the scheme for the display to managers	

//count top the pm that asssined for any scheme or not
	public function AssignedSchemeCount($schemeOwner = null){
		//echo $schemeOwner;exit;
		$objTbSc = new Zend_Db_Table("dbt_assign_manager");
			$resultSc = $objTbSc->fetchRow($objTbSc->select()->where("pm_id = ?",trim(intval($schemeOwner))));

			return count($resultSc);
	}
//count assigned scheme for the schme owner


//getting here all scheme name which are assign to scheme owner
		public function AssignedScheme($soId = null, $roleid = null){
		$ministryid = new Zend_Session_Namespace("ministryid");
		$minid = $ministryid->ministryid;
		//if(($roleid == 4)||($roleid == 6)){
		if(($roleid == 4 or $roleid == 12)){
			$objTb = new Zend_Db_Table("dbt_assign_manager");
			$select = $objTb->select();
			$select->where("pm_id = ?",trim(intval($soId)));
			$result = $objTb->fetchRow($select);
		//calling here a function which return schemename passing by the assigned scheme id which is assigned to current pm	
			if(count($result['scheme_id']) > 0){
				//$schemes = $this->getScheme($result['scheme_id']);
				$sche = new Zend_Db_Table('dbt_scheme');
				$select = $sche->select();
				$select->setIntegrityCheck(false);
				$select->from(array('l' => 'dbt_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type','pfms_scheme_code'))
				->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'))
				->where('l.id in (?)', explode(",",$result['scheme_id']))
				->where('l.status = 1');
				$select->order("l.created DESC");
				//echo $select;exit;
				$schemes = $sche->fetchAll($select);
				
				return $schemes->toArray();
			}else{
				return "No record found!";
			}
			//print_r($result);exit;
			} else if($roleid == 1){
					$objTb = new Zend_Db_Table("dbt_scheme");
					$select = $objTb->select();
					$select->setIntegrityCheck(false);
					$select->from(array('l' => 'dbt_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type','pfms_scheme_code'));
					//echo $select;exit;
					$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'));
					$select->where('l.status = ?', 1);
					$select->order("l.created DESC");
						//echo $select;exit;
					$schemes = $objTb->fetchAll($select);
					return $schemes->toArray();
				}
				else if($roleid == 6){
					$objTb = new Zend_Db_Table("dbt_scheme");
					$select = $objTb->select();
					$select->setIntegrityCheck(false);
					$select->from(array('l' => 'dbt_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type','pfms_scheme_code'));
					//echo $select;exit;
					$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'));
					$select->where('l.ministry_id= ?', $minid);
					$select->where('l.status= ?', 1);
					$select->order("l.created DESC");
					
						//echo $select;exit;
					$schemes = $objTb->fetchAll($select);
					return $schemes->toArray();
				}
				
		}
//getting scheme owner scheme end here

//getting all scheme which is assigned t scheme owner
		public function getScheme($schemeids)
		{
			//echo $schemeids;exit;
		$sche = new Zend_Db_Table('locations');
		$allScheme = $sche->fetchAll($sche->select()->where('id IN (?)',explode(",",$schemeids)));
		//echo "<pre>";print_r($allScheme->toArray());exit;
		return $allScheme->toArray();
		}
//getting scheme end here



//uts scheme owners ============================================
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
				$select->from(array('l' => 'dbt_'.$utname.'_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type'))
				->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'))
				->where('l.id in (?)', explode(",",$result['scheme_id']))
				->where('l.translation_id !=1')
				->where('l.status =1')
				->order('sid DESC');
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
					$select->from(array('l' => 'dbt_'.$utname.'_scheme'), array('id as sid','scheme_name as scheme','ministry_id as mid','scheme_type'));
					//echo $select;exit;
					$select->joinLeft(array('p' => 'dbt_ministry'), 'l.ministry_id = p.id', array('ministry_name as ministry'));
					$select->where('l.translation_id != ?', 1);
					$select->where('l.status = ?', 1)
					->order('sid DESC');
						//echo $select;exit;
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


public function getusername($userid = null)
			{
				
				$select_table = new Zend_Db_Table('dbt_users');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('user' => 'dbt_users'), array('username','id'));
				$select->where('user.id  = ?', $userid);
				//$select->where('schm.status=1');
				$select_user= $select_table->fetchAll($select);
				
				return $select_user->toArray();
				//echo $select_user;
				return $select_user;
				
			}

			
			public function getschemenamenn($schemeid = null)
			{
				$select_table = new Zend_Db_Table('dbt_scheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('schm' => 'dbt_scheme'), array('scheme_name','id'));
				$select->where('schm.id  = ?', $schemeid);
				//$select->where('schm.status=1');
				//echo $select;die;
				$select_schme = $select_table->fetchAll($select);
				return $select_schme->toArray();
				//return $select;
				
			}


//********************** get all scheme by scheme owner function

public function get_all_assign_scheme_owner($schemeownerid=null)
    {
        $select_table = new Zend_Db_Table('dbt_assign_manager');
        $select = $select_table->select();
		$select->from(array('schm' => 'dbt_assign_manager'), array('schm.scheme_id'));
        $select->where('schm.pm_id = ?',trim(intval($schemeownerid)));
        $row=$select_table->fetchAll($select)->toArray();
		$scheme_owner_id=explode(",",$row[0]['scheme_id']);
		//print_r($scheme_owner_id);die;
        return $scheme_owner_id; 
    }
    
   
//********************** get all scheme by scheme state user function

public function get_all_assign_scheme_state($schemeownerid=null)
    {

        $select_table = new Zend_Db_Table('dbt_users');
        $select = $select_table->select();
		$select->from(array('u' => 'dbt_users'),array("GROUP_CONCAT(u.id SEPARATOR ',') AS user_id"));
        $select->where('u.created_by = ?',trim(intval($schemeownerid)));
//echo $select;die;
        $row=$select_table->fetchAll($select)->toArray();
		//echo $row[0]['user_id'];die;
		$select_table = new Zend_Db_Table('dbt_assign_manager');
        $select = $select_table->select();
		$select->from(array('asschmana' => 'dbt_assign_manager'), array('asschmana.scheme_id'));
        $select->where("asschmana.pm_id IN (".$row[0]['user_id'].")");
		$row=$select_table->fetchAll($select)->toArray();
		
		for($i=0;$i<sizeof($row);$i++){
				if($row[$i]['scheme_id']!=''){
				$scheme_owner_state_id[$i] = explode(",",$row[$i]['scheme_id']);
				}
			}
		$mergeArr = array();
			//echo "<pre>";print_r($scheme_owner_state_id);
		for($k = 0; $k<sizeof($scheme_owner_state_id); $k++){
						if($scheme_owner_state_id[$k]==''){
								$scheme_owner_state_id[$k]=array();
							}
					if($scheme_owner_state_id[$k]!=''){
			$mergeArr = array_values(array_unique(array_merge($mergeArr,$scheme_owner_state_id[$k])));
			}
		}
		//$idstr = implode(",",$mergeArr);
		//echo $idstr;exit;
			//$idsofarray = array_unique($scheme_owner_state_id);
			//echo "<pre>";
		//echo "<pre>";print_r($mergeArr);die;
		return $mergeArr; 
    }

//***************** get scheme assign name by state user

public function get_scheme_name($schemeownerid=null)
    {
        $select_table = new Zend_Db_Table('dbt_scheme');
        $select = $select_table->select();
		$select->from(array('schm' => 'dbt_scheme'), array("GROUP_CONCAT(schm.scheme_name SEPARATOR ',') AS scheme_name"));
        $select->where("schm.id =?",$schemeownerid);
		//echo $select;die;
        $row=$select_table->fetchAll($select)->toArray();
		//print_r($row);die;
        return $row; 
    }

	public function checkuser($userid = null){
                $select_table = new Zend_Db_Table('dbt_users');
					$rowselect = $select_table->fetchAll($select_table->select()->where('created_by = ?',$userid));
				return count($rowselect);
	}		

}
