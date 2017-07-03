<?php
//require_once("/models/DbTable/Table.php");
//require_once("/models/Admin.php");
//__autoloadDB('Db');
//include("Db.php");
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Auth extends Zend_Db_Table_Abstract 
{
	  protected $_name = 'dbt_users'; 

public	function findMd5Value($mixValue)
	{
		//$password1=trim($_POST['password']);
		$password = substr($mixValue, 0, 12);
		$password.= substr($mixValue, 22, 10);
		$password.= substr($mixValue, 37);
		return $password;
	}

	public function ckhval($name,$pass){
		//if($name == 'admin')
		//{ 
			//echo $name;
			//echo "<br/>";
			//echo $pass;
//echo "<br/>";
		//	die;
			$nm = new Zend_Db_Table('dbt_users');
			//$pass_or = $nm->find(7)->current()->password;
			//$role = $nm->find(7)->current()->role;
			 $count_row = $nm->fetchRow($nm->select()->where('username = ?',trim($name))->where('password = ?',trim($pass)));
				//print_r($count_row->toArray()); die;
				$ministry_name=  $count_row['ministry_name'];

				$role = $count_row['role'];
				$userid = $count_row['id'];
				$ministryid = $ministry_name;
				$status = $count_row['status'];
				$login_status = $count_row['login_status'];
				$state_code = $count_row['state'];
				//echo $role;die;
		  // die;
			$count= count($count_row);
			//return $count;
			//echo $count;die;
			if($count!=0)
			{
				
				if($status==0)

				{
					
				$data = array(2,$role,$userid,0,$ministryid,$state_code);
				return $data;
				}
				else{
				
				$data = array(1,$role,$userid,$login_status,$ministryid,$state_code);
				return $data;
				}
				}
			
			
			
		
				else{
					
				$data = array(0,$role,$userid);
				return $data;
				}
			
			
		//}
	}
	public function updateloginstatus($userid,$login_status)
	{
		$user_table = new Zend_Db_Table('dbt_users');
		$data="";
		$where="";
		$data = array(
				'login_status'=> $login_status
			);
		$where = array('id = ?'=> $userid);
		$update_values = $user_table->update($data, $where);
	}
	/* This function is used to find out the md5 value of the password in the login page. This code is a substitute of RC4 function */


public function titleListByLang($translation_id, $cmsid)
	{
		
		    //echo $lang;
		        $search = '';
				
			  	$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('cms' => 'dbt_content_management'), array('title', 'id'));
				$select->where('cms.id  = ?', $translation_id);
						//echo $select;
				$select_feedbackrec = $select_table->fetchRow($select);
			
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				return $select_feedbackrec;
	}
	
	
	
	public function titleListByLanghindi($translation_id, $cmsid)
	{
		
		    //echo $lang;
		        $search = '';
				
			  	$select_table = new Zend_Db_Table('dbt_content_management');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				
				
				$select->from(array('cms' => 'dbt_content_management'), array('title', 'id'));
				$select->where('cms.translation_id  = ?', $translation_id);
						//echo $select;
				$select_feedbackrec = $select_table->fetchRow($select);
			
				//print_r($select_feedbackrec);
			
				//echo $select;
				//die;
				return $select_feedbackrec;
	}
	 function getHeaderMenu()
	{
		/*id            
		menu_type       
		sort_order    
		title          
		language       
		description    
		status    
		*/

		$select_table = new Zend_Db_Table('dbt_content_management');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		/*if(!empty($_SESSION["LANGUAGE_ID"]))
		{
			$select->from(array('cms' => 'dbt_content_management'), array('title', 'id as cmsid','translation_id'))->where('cms.language = 2')->where('cms.menu_type = 1')->where('cms.status = 1')->order('cms.sort_order ASC');
		}
		else*/
			$select->from(array('cms' => 'dbt_content_management'), array('title', 'id as cmsid','translation_id'))->where('cms.language = 2')->where('cms.menu_type = 1')->where('cms.status = 1')->order('cms.sort_order ASC');
		//echo $select;
		$select_menu = $select_table->fetchAll($select);
	//	echo "<pre>";
	//print_r($select_menu);
	//echo "</pre>";
		//return $select_menu;
		return $select_menu->toArray();

		
	}

	 function getFooterMenu()
	{
		/*id            
		menu_type       
		sort_order    
		title          
		language       
		description    
		status    
		*/

		$select_table = new Zend_Db_Table('dbt_content_management');
		$select = $select_table->select();
		$select->setIntegrityCheck(false);
		/*if(!empty($_SESSION["LANGUAGE_ID"]))
		{
			$select->from(array('cms' => 'dbt_content_management'), array('title', 'id as cmsid','translation_id'))->where('cms.language = 2')->where('cms.menu_type = 1')->where('cms.status = 1')->order('cms.sort_order ASC');
		}
		else*/
			$select->from(array('cms' => 'dbt_content_management'), array('title', 'id as cmsid','translation_id'))->where('cms.language = 2')->where('cms.menu_type = 2')->where('cms.status = 1')->order('cms.sort_order ASC');
		//echo $select;
		$select_menu = $select_table->fetchAll($select);
	//	echo "<pre>";
	//print_r($select_menu);
	//echo "</pre>";
		//return $select_menu;
		return $select_menu->toArray();

		
	}
	//fetch banner images
	public function imagelistview($langid)
	{
		  $select_table = new Zend_Db_Table('dbt_photogallery');
		  $row = $select_table->fetchAll($select_table->select()->where('type = ? ',2)->where('status = ? ',1)->where('language = ? ',$langid));
		  return $row; 
	}
	public function countimageview($langid)
	{
		  $count_table = new Zend_Db_Table('dbt_photogallery');
		  $count_row = $count_table->fetchAll($count_table->select()->where('type = ? ',2)->where('status = ? ',1)->where('language = ? ',$langid));
		  return count($count_row); 
	}
	
	//fetch home page chart data
	public function homePgeChartData()
	{
		  $select_table = new Zend_Db_Table('dbt_home_page_chart_data');
		  $row = $select_table->fetchAll($select_table->select());
		  //$chartdata = $row->toArray();
		  return $row;
	} 
        
        public function countCurrentFinancialYearMinistery()
        {

                $select_table = new Zend_Db_Table('dbt_ministry');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                //$select->from(array('p' => 'dbt_ministry'), array('id'));
				$select->from(array('p' => 'dbt_ministry'), array('DISTINCT(p.id) as counting'));
				$select->join(array('s' => 'dbt_scheme'), 'p.id = s.ministry_id', array(''));
                //->joinLeft(array('u' => 'users'), 'p.customer_id = u.id', array('firstname','lastname','organisation'))
                //->where('p.title LIKE ?', '%'.$search.'%')
                //->ORwhere('p.plan_of_act LIKE ?', '%'.$search.'%')
                //->ORwhere('u.organisation LIKE ?', '%'.$search.'%')
                //->ORwhere('u.lastname LIKE ?', '%'.$search.'%')
                //->order('p.id DESC');
                //$select->where('p.created LIKE ?', '%'.date("Y").'%');
				$startYear = date("Y");
				$endYear = date("Y",strtotime("1 year"));
				
				$select->where("DATE_FORMAT(p.created, '%Y-%m-%d') >= ?",  $startYear."-04-01");
				$select->where("DATE_FORMAT(p.created, '%Y-%m-%d') <= ?",  $endYear."-03-31");
                $select->where('p.status = ?', '1');
                $select->where('p.language = ?', '2');
               //echo $select;die;
                $select_org = $select_table->fetchAll($select);
           return count($select_org); 
        }

            public function countCurrentFinancialYearSchme()
        {

                $select_table = new Zend_Db_Table('dbt_scheme');
                $select = $select_table->select();
                $select->setIntegrityCheck(false);
                $select->from(array('p' => 'dbt_scheme'), array('id'));
               // $select->where('p.created LIKE ?', '%'.date("Y").'%');
			   $startYear = date("Y");
				$endYear = date("Y",strtotime("1 year"));
				
				$select->where("DATE_FORMAT(p.created, '%Y-%m-%d') >= ?",  $startYear."-04-01");
				$select->where("DATE_FORMAT(p.created, '%Y-%m-%d') <= ?",  $endYear."-03-31");
                $select->where('p.status = ?', '1');
                $select->where('p.language = ?', '2');
                //echo $select;die;
                $select_org = $select_table->fetchAll($select);
           return count($select_org); 
        }
		
		
 public function getbeneficiarydata()
        {
			// echo "test"; die;
			    /*****get the pahal data*****/
				$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
				$select = $select_table->select();
				$select->setIntegrityCheck(false);
				$select->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthpahal'));
	            $select->where('beneficiaryscheme.scheme_id = ?', '1');
				$select_org = $select_table->fetchRow($select);
				$pahaldata = $select_org->toArray();
				
				/*******get the pahal aadharbased beneficiraydata*********/
				if($pahaldata['monthpahal']!='')
				{
				$select_tablepahal = new Zend_Db_Table('dbt_beneficaryscheme');
				$selectpahal = $select_table->select();
				$selectpahal->setIntegrityCheck(false);
				$selectpahal->from(array('pahalbeneficiary' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
	            $selectpahal->where('pahalbeneficiary.month = ?', $pahaldata['monthpahal']);
			    $selectpahal->where('pahalbeneficiary.scheme_id = ?', '1');
				//echo $selectpahal;
				$selectpahal = $select_table->fetchRow($selectpahal);
				$pahalaadharnonaadhardata = $selectpahal->toArray();
				$pahaltotalbeneficiaries = $pahalaadharnonaadhardata['totalnoofbeneficiaries'];
				$pahalabbbeneficiaries  = $pahalaadharnonaadhardata['totalnoofbeneficiarieswithaadhaar'];
				$pahalnonabbbeneficiaries = $pahaltotalbeneficiaries - $pahalabbbeneficiaries; 
				}
				  /*****get the mgnregs data*****/
				$select_table = new Zend_Db_Table('dbt_beneficaryscheme');
				$selectmgnregs = $select_table->select();
				$selectmgnregs->setIntegrityCheck(false);
				$selectmgnregs->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthmgnregs'));
	            $selectmgnregs->where('beneficiaryscheme.scheme_id = ?', '3');
				$select_orgselectmgnregs = $select_table->fetchRow($selectmgnregs);
				$mgnregsdata = $select_orgselectmgnregs->toArray();
				$monthmnrega = $mgnregsdata['monthmgnregs'];
				//echo $monthmnrega;
				/*******get the mgnrega aadharbased beneficiraydata*********/
				if($monthmnrega!='')
				{
			    $select_tablemgnregs = new Zend_Db_Table('dbt_beneficaryscheme');
				$selectmgnregsdata = $select_tablemgnregs->select();
				$selectmgnregsdata->setIntegrityCheck(false);
				$selectmgnregsdata->from(array('mgnregsbeneficiary' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
	            $selectmgnregsdata->where('mgnregsbeneficiary.month = ?', $monthmnrega);
			    $selectmgnregsdata->where('mgnregsbeneficiary.scheme_id = ?', '3');
				$selectmgnregsdatann = $select_tablemgnregs->fetchRow($selectmgnregsdata);
				 //print_r($selectmgnregsdatann);
				$mgnregsdatann = $selectmgnregsdatann->toArray();
				$mgnregstotalbeneficiaries = $selectmgnregsdatann['totalnoofbeneficiaries'];
				$mgnregsabbbeneficiaries  = $selectmgnregsdatann['totalnoofbeneficiarieswithaadhaar'];
				$mgnregslnonabbbeneficiaries = $mgnregstotalbeneficiaries - $mgnregsabbbeneficiaries; 
              // echo $mgnregsabbbeneficiaries;
				}
				
			    /*****get the nsapdata*****/
				$select_tablensap = new Zend_Db_Table('dbt_beneficaryscheme');
				$selectnsap = $select_tablensap->select();
				$selectnsap->setIntegrityCheck(false);
				$selectnsap->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthnsap'));
	            $selectnsap->where('beneficiaryscheme.scheme_id = ?', '5');
				$select_orgselectnsap= $select_tablensap->fetchRow($selectnsap);
				$nsapdata = $select_orgselectnsap->toArray();
				$monthnsap = $nsapdata['monthnsap']; 
				/*******get the mgnrega aadharbased beneficiraydata*********/
				if($monthnsap!='')
				{
		        $select_tablensaps = new Zend_Db_Table('dbt_beneficaryscheme');
				$selectnsapdata = $select_tablensaps->select();
				$selectnsapdata->setIntegrityCheck(false);
				$selectnsapdata->from(array('mgnregsbeneficiary' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
	            $selectnsapdata->where('mgnregsbeneficiary.month = ?', $monthnsap);
			    $selectnsapdata->where('mgnregsbeneficiary.scheme_id = ?', '5');
				$selectnsapdatann = $select_tablensaps->fetchRow($selectnsapdata);
				$nsapdatann = $selectnsapdatann->toArray();
				$nsapdatatotalbeneficiaries = $nsapdatann['totalnoofbeneficiaries'];
				$nsapdataabbbeneficiaries  = $nsapdatann['totalnoofbeneficiarieswithaadhaar'];
				$nsapdatanonabbbeneficiaries = $nsapdatatotalbeneficiaries - $nsapdataabbbeneficiaries; 
                }
	           // $totalbeneficiaries = array('pahalabbbeneficiaries' => $pahalabbbeneficiaries,'pahalnonabbbeneficiaries' => $pahalnonabbbeneficiaries);
			
			/********get all the scheme id where group is 4***********/
				$select_tablescheme = new Zend_Db_Table('dbt_scheme');
				$selectscheme = $select_tablescheme->select();
				$selectscheme->setIntegrityCheck(false);
				$selectscheme->from(array('scheme' => 'dbt_scheme'), array('id'));
	            $selectscheme->where('scheme.scheme_group = ?', '4');
				$select_org = $select_tablescheme->fetchAll($selectscheme);
				$schemedata = $select_org->toArray();
				//echo "<pre>";
				//print_r($schemedata);
				$scholarshiptotalnoofbeneficiarieswithaadhaar = '';
				$scholarshiptotalnoofbeneficiaries = '';
				foreach($schemedata  as $k=>$v)
				{
					//echo $v['id'];
					$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
					$selectqq = $select_tableqq->select();
					$selectqq->setIntegrityCheck(false);
					$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
					$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
					//echo $selectqq;
					$select_orgqq = $select_tableqq->fetchRow($selectqq);
					$schmdata = $select_orgqq->toArray();
					if($schmdata['monthscheme']!='')
					{
					$select_tableschmww= new Zend_Db_Table('dbt_beneficaryscheme');
					$selectschm = $select_tableschmww->select();
					$selectschm->setIntegrityCheck(false);
					$selectschm->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
					$selectschm->where('beneficiaryscheme.scheme_id = ?', $v['id']);
					$selectschm->where('beneficiaryscheme.month = ?', $schmdata['monthscheme']);
                    $select_orgschm = $select_tableschmww->fetchRow($selectschm);
					$schmdatamm = $select_orgschm->toArray();
					$scholarshiptotalnoofbeneficiarieswithaadhaar+= $schmdatamm['totalnoofbeneficiarieswithaadhaar'];
					$scholarshiptotalnoofbeneficiaries+= $schmdatamm['totalnoofbeneficiaries'];
					}
				}
	
				$scholartotalnumofbeneficiareswithnonaadhar = $scholarshiptotalnoofbeneficiaries - $scholarshiptotalnoofbeneficiarieswithaadhaar;
				
		    /******get all the scheme id where group is 5*********/
		    	$select_tableschemeother = new Zend_Db_Table('dbt_scheme');
				$selectschemeother = $select_tableschemeother->select();
				$selectschemeother->setIntegrityCheck(false);
				$selectschemeother->from(array('scheme' => 'dbt_scheme'), array('id'));
	            $selectschemeother->where('scheme.scheme_group = ?', '5');
				$select_orgother = $select_tableschemeother->fetchAll($selectschemeother);
				$schemedataother = $select_orgother->toArray();
				//print_r($schemedataother);
				$othertotalnoofbeneficiarieswithaadhaar = '';
				$othertotalnoofbeneficiaries = '';
				foreach($schemedataother  as $k=>$v)
				{
					//echo $v['id'];
					$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
					$selectqq = $select_tableqq->select();
					$selectqq->setIntegrityCheck(false);
					$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
					$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
					//echo $selectqq;
					$select_orgqq = $select_tableqq->fetchRow($selectqq);
					$schmdata = $select_orgqq->toArray();
					//echo $schmdata['monthscheme'];
				    
					if($schmdata['monthscheme']!='')
					{
						$select_tableschmww= new Zend_Db_Table('dbt_beneficaryscheme');
						$selectschm = $select_tableschmww->select();
						$selectschm->setIntegrityCheck(false);
						$selectschm->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
						$selectschm->where('beneficiaryscheme.scheme_id = ?', $v['id']);
						$selectschm->where('beneficiaryscheme.month = ?', $schmdata['monthscheme']);
						//echo $selectschm;
						$select_orgschm = $select_tableschmww->fetchRow($selectschm);
						$schmdatamm = $select_orgschm->toArray();
						//print_r($schmdatamm);
						$othertotalnoofbeneficiarieswithaadhaar+= $schmdatamm['totalnoofbeneficiarieswithaadhaar'];
						$othertotalnoofbeneficiaries+= $schmdatamm['totalnoofbeneficiaries'];
					}
					
				}
				
		$otherbenficiarywithnonaadhar = $othertotalnoofbeneficiaries - $othertotalnoofbeneficiarieswithaadhaar;
		$totalbeneficiaries = array('pahalabbbeneficiaries' => $pahalabbbeneficiaries,'pahaltotalbeneficiaries' => $pahaltotalbeneficiaries,'nsapdatatotalbeneficiaries' => $nsapdatatotalbeneficiaries,'mgnregstotalbeneficiaries' => $mgnregstotalbeneficiaries,'scholarshiptotalnoofbeneficiaries' => $scholarshiptotalnoofbeneficiaries,'othertotalnoofbeneficiaries' => $othertotalnoofbeneficiaries,'pahalnonabbbeneficiaries' => $pahalnonabbbeneficiaries,'mgnregsabbbeneficiaries' => $mgnregsabbbeneficiaries,'mgnregslnonabbbeneficiaries'=> $mgnregslnonabbbeneficiaries,'nsapdataabbbeneficiaries' => $nsapdataabbbeneficiaries,'nsapdatanonabbbeneficiaries' => $nsapdatanonabbbeneficiaries,'scholarshiptotalnoofbeneficiarieswithaadhaar' => $scholarshiptotalnoofbeneficiarieswithaadhaar,'scholartotalnumofbeneficiareswithnonaadhar' => $scholartotalnumofbeneficiareswithnonaadhar,'othertotalnoofbeneficiarieswithaadhaar' => $othertotalnoofbeneficiarieswithaadhaar,'otherbenficiarywithnonaadhar' => $otherbenficiarywithnonaadhar); 
		return $totalbeneficiaries;		 
		   /**************end***************************************/
			/***********end******************************************/
		}
	public function getbeneficiarydatanew()
        {
			$schemegroup = array('1','2','3','4','5');
			$financialyearfrom = date('Y');
			$financialyearto = date('Y') + 1;
			$currentmonth = date('m');
			if ($currentmonth <= 3) {$financialyearfrom = date('Y') - 1;$financialyearto = date('y');}
			$financialyear = $financialyearfrom.'-'.$financialyearto;
			//echo $financialyearfrom;
			//echo $financialyearto;
			$output=array();
			foreach($schemegroup as $v1)
			{
				//echo "test";
				/******get all the scheme id where group is 5*********/
		    	$select_tableschemeother = new Zend_Db_Table('dbt_scheme');
				$selectschemeother = $select_tableschemeother->select();
				$selectschemeother->setIntegrityCheck(false);
				$selectschemeother->from(array('scheme' => 'dbt_scheme'), array('id'));
	            $selectschemeother->where('scheme.scheme_group = ?', $v1);
				$select_orgother = $select_tableschemeother->fetchAll($selectschemeother);
				$schemedataother = $select_orgother->toArray();
				//print_r($schemedataother);
				$othertotalnoofbeneficiarieswithaadhaar = '';
				$othertotalnoofbeneficiaries = '';
				foreach($schemedataother  as $k=>$v)
				{
					//echo $v['id'];
					$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
					$selectqq = $select_tableqq->select();
					$selectqq->setIntegrityCheck(false);
					$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
					$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
					$selectqq->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
			         $selectqq->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
					//echo $selectqq;
					$select_orgqq = $select_tableqq->fetchRow($selectqq);
					$schmdata = $select_orgqq->toArray();
					//echo $schmdata['monthscheme'];
				    
					if($schmdata['monthscheme']!='')
					{
						$select_tableschmww= new Zend_Db_Table('dbt_beneficaryscheme');
						$selectschm = $select_tableschmww->select();
						$selectschm->setIntegrityCheck(false);
						$selectschm->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
						$selectschm->where('beneficiaryscheme.scheme_id = ?', $v['id']);
						$selectschm->where('beneficiaryscheme.month = ?', $schmdata['monthscheme']);
			           $selectschm->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
			            $selectschm->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
						//echo $selectschm;
						$select_orgschm = $select_tableschmww->fetchRow($selectschm);
						$schmdatamm = $select_orgschm->toArray();
						//print_r($schmdatamm);
						$othertotalnoofbeneficiarieswithaadhaar+= $schmdatamm['totalnoofbeneficiarieswithaadhaar'];
						$othertotalnoofbeneficiaries+= $schmdatamm['totalnoofbeneficiaries'];
					}
					
				}
				//die;
				$otherbenficiarywithnonaadhar = $othertotalnoofbeneficiaries - $othertotalnoofbeneficiarieswithaadhaar;
				$output[$v1]['total_no_of_beneficiaries']=$othertotalnoofbeneficiaries;
				$output[$v1]['total_no_of_beneficiaries_with_aadhaar']=$othertotalnoofbeneficiarieswithaadhaar;
				$output[$v1]['benficiary_with_non_aadhar']=$otherbenficiarywithnonaadhar;
				unset($otherbenficiarywithnonaadhar,$othertotalnoofbeneficiaries,$othertotalnoofbeneficiarieswithaadhaar);
				
			}
			/*$totalbeneficiaries = array('pahalabbbeneficiaries' => $pahalabbbeneficiaries,'pahaltotalbeneficiaries' => $pahaltotalbeneficiaries,'nsapdatatotalbeneficiaries' => $nsapdatatotalbeneficiaries,'mgnregstotalbeneficiaries' => $mgnregstotalbeneficiaries,'scholarshiptotalnoofbeneficiaries' => $scholarshiptotalnoofbeneficiaries,'othertotalnoofbeneficiaries' => $othertotalnoofbeneficiaries,'pahalnonabbbeneficiaries' => $pahalnonabbbeneficiaries,'mgnregsabbbeneficiaries' => $mgnregsabbbeneficiaries,'mgnregslnonabbbeneficiaries'=> $mgnregslnonabbbeneficiaries,'nsapdataabbbeneficiaries' => $nsapdataabbbeneficiaries,'nsapdatanonabbbeneficiaries' => $nsapdatanonabbbeneficiaries,'scholarshiptotalnoofbeneficiarieswithaadhaar' => $scholarshiptotalnoofbeneficiarieswithaadhaar,'scholartotalnumofbeneficiareswithnonaadhar' => $scholartotalnumofbeneficiareswithnonaadhar,'othertotalnoofbeneficiarieswithaadhaar' => $othertotalnoofbeneficiarieswithaadhaar,'otherbenficiarywithnonaadhar' => $otherbenficiarywithnonaadhar); */
			//echo "<pre>";
			//print_r($output);
			//echo "</pre>";die;
		  return $output;
		}
		
		public function getbeneficiarydatanewone()
        {
			 //echo "asas"; die;			 
				$financialyearfrom = date('Y');
				$financialyearto = date('Y') + 1;
				$currentmonth = date('m');
				if ($currentmonth <= 3) {$financialyearfrom = date('Y') - 1; $financialyearto = date('Y');}
			   $schemegroup = array('1','2','3','4','5');
				// $schemegroup = array('1');
				$output=array();
				  if ($currentmonth <= 3) {
					  //echo "asas";
					  foreach($schemegroup as $v1)
			           {
							$select_tableschemeother = new Zend_Db_Table('dbt_scheme');
							$selectschemeother = $select_tableschemeother->select();
							$selectschemeother->setIntegrityCheck(false);
							$selectschemeother->from(array('scheme' => 'dbt_scheme'), array('id'));
							$selectschemeother->where('scheme.scheme_group = ?', $v1);
							$select_orgother = $select_tableschemeother->fetchAll($selectschemeother);
							$schemedataother = $select_orgother->toArray();
							$othertotalnoofbeneficiarieswithaadhaar = '';
							$othertotalnoofbeneficiaries = '';
							foreach($schemedataother  as $k=>$v)
							{
								$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
								$selectqq = $select_tableqq->select();
								$selectqq->setIntegrityCheck(false);
								$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
								$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
								$selectqq->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
								$selectqq->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
								$selectqq->where("beneficiaryscheme.month in '(01,02,03)'");
								echo $selectqq;
								$select_orgqq = $select_tableqq->fetchRow($selectqq);
								$schmdata = $select_orgqq->toArray();
								if(empty($schmdata))
								{
									$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
									$selectqq = $select_tableqq->select();
									$selectqq->setIntegrityCheck(false);
									$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
									$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
									$selectqq->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
									$selectqq->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
									//echo $selectqq;
									$select_orgqq = $select_tableqq->fetchRow($selectqq);
									$schmdatanew = $select_orgqq->toArray();
								}
								//print_r($schmdata);
									if($schmdata['monthscheme']!='' || $schmdatannew['monthscheme']!='')
								{
									$select_tableschmww= new Zend_Db_Table('dbt_beneficaryscheme');
									$selectschm = $select_tableschmww->select();
									$selectschm->setIntegrityCheck(false);
									$selectschm->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
									$selectschm->where('beneficiaryscheme.scheme_id = ?', $v['id']);
									$selectschm->where('beneficiaryscheme.month = ?', $schmdata['monthscheme']);
									$selectschm->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
									$selectschm->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
									//echo $selectschm;
									$select_orgschm = $select_tableschmww->fetchRow($selectschm);
									$schmdatamm = $select_orgschm->toArray();
									//print_r($schmdatamm);
									$othertotalnoofbeneficiarieswithaadhaar+= $schmdatamm['totalnoofbeneficiarieswithaadhaar'];
									$othertotalnoofbeneficiaries+= $schmdatamm['totalnoofbeneficiaries'];
								}
								
							}
                        $otherbenficiarywithnonaadhar = $othertotalnoofbeneficiaries - $othertotalnoofbeneficiarieswithaadhaar;
						$output[$v1]['total_no_of_beneficiaries']=$othertotalnoofbeneficiaries;
						$output[$v1]['total_no_of_beneficiaries_with_aadhaar']=$othertotalnoofbeneficiarieswithaadhaar;
						$output[$v1]['benficiary_with_non_aadhar']=$otherbenficiarywithnonaadhar;
						unset($otherbenficiarywithnonaadhar,$othertotalnoofbeneficiaries,$othertotalnoofbeneficiarieswithaadhaar);	
					   }   
				  }
				  else
				  {
                    foreach($schemegroup as $v1)
			           {
							$select_tableschemeother = new Zend_Db_Table('dbt_scheme');
							$selectschemeother = $select_tableschemeother->select();
							$selectschemeother->setIntegrityCheck(false);
							$selectschemeother->from(array('scheme' => 'dbt_scheme'), array('id'));
							$selectschemeother->where('scheme.scheme_group = ?', $v1);
							$select_orgother = $select_tableschemeother->fetchAll($selectschemeother);
							$schemedataother = $select_orgother->toArray();
							//print_r($schemedataother);
							$othertotalnoofbeneficiarieswithaadhaar = '';
							$othertotalnoofbeneficiaries = '';
                       $othertotalnoofbeneficiarieswithaadhaar = '';
						$othertotalnoofbeneficiaries = '';
						foreach($schemedataother  as $k=>$v)
						{
						//echo $v['id'];
						$select_tableqq = new Zend_Db_Table('dbt_beneficaryscheme');
						$selectqq = $select_tableqq->select();
						$selectqq->setIntegrityCheck(false);
						$selectqq->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('max(month) as monthscheme'));
						$selectqq->where('beneficiaryscheme.scheme_id = ?', $v['id']);
						$selectqq->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
						$selectqq->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
						//echo $selectqq; die;
						$select_orgqq = $select_tableqq->fetchRow($selectqq);
						$schmdata = $select_orgqq->toArray();
					    /* echo $schmdata['monthscheme'];
                        die; */
						if($schmdata['monthscheme']!='')
						{
						$select_tableschmww= new Zend_Db_Table('dbt_beneficaryscheme');
						$selectschm = $select_tableschmww->select();
						$selectschm->setIntegrityCheck(false);
						$selectschm->from(array('beneficiaryscheme' => 'dbt_beneficaryscheme'), array('totalnoofbeneficiarieswithaadhaar','totalnoofbeneficiaries'));
						$selectschm->where('beneficiaryscheme.scheme_id = ?', $v['id']);
						$selectschm->where('beneficiaryscheme.month = ?', $schmdata['monthscheme']);
						$selectschm->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
						$selectschm->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
						//$selectschm->where('beneficiaryscheme.financial_year_from  = ?',$financialyearfrom);
						//$selectschm->where('beneficiaryscheme.financial_year_to = ?',$financialyearto);
						//echo $selectschm;
						$select_orgschm = $select_tableschmww->fetchRow($selectschm);
						$schmdatamm = $select_orgschm->toArray();
						//print_r($schmdatamm);
						$othertotalnoofbeneficiarieswithaadhaar+= $schmdatamm['totalnoofbeneficiarieswithaadhaar'];
						$othertotalnoofbeneficiaries+= $schmdatamm['totalnoofbeneficiaries'];
						}

						}
						$otherbenficiarywithnonaadhar = $othertotalnoofbeneficiaries - $othertotalnoofbeneficiarieswithaadhaar;
						$output[$v1]['total_no_of_beneficiaries']=$othertotalnoofbeneficiaries;
						$output[$v1]['total_no_of_beneficiaries_with_aadhaar']=$othertotalnoofbeneficiarieswithaadhaar;
						$output[$v1]['benficiary_with_non_aadhar']=$otherbenficiarywithnonaadhar;
						unset($otherbenficiarywithnonaadhar,$othertotalnoofbeneficiaries,$othertotalnoofbeneficiarieswithaadhaar);
					}
				  }
				 //print_r($output);
			 return $output;
			 
		}
}

