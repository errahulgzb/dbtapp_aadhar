<?php
/* Role Definition:
 * 1 => Administrator
 * 2 => Survey User [Installation Manager]
 * 3 => Customer
 * 4 => Project Manager
 */
?>
<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Validate.php';

class AuditlogController extends Zend_Controller_Action
{
   protected $rolearray=array('1','6');
    public function init()
    {
        /* Initialize action controller here */
	        $role = new Zend_Session_Namespace('role');
                $admname = new Zend_Session_Namespace('adminMname'); 
                $this->controller_name = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                $this->method_name =  Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
                if($admname->adminname==''){
                   $this->_helper->layout->setLayout('sites/layout');       
               }elseif(($admname->adminname!='')&&($role->role!=1)&&($this->method_name=='schemedatalist')){
                    $this->_helper->layout->setLayout('layout');
               }elseif($role->role==1 || $role->role==6){
                   $this->_helper->layout->setLayout('admin/layout');
               }else{
                  $this->_helper->layout->setLayout('layout'); 
               }
        
        
        
        
    }

	public function indexAction()
	{
		
		$admname = new Zend_Session_Namespace('adminMname'); 
		$userid = new Zend_Session_Namespace('userid');
			if($admname->adminname==''){
				 $this->_redirect('');
			}
			$role = new Zend_Session_Namespace('role');	
			if(!in_array($role->role,$this->rolearray)){
				$this->_redirect('');
			}	

		$request = $this->getRequest();
		$auditobj = new Application_Model_Auditlog;
		
		if(isset($start))
			{                         // This variable is set to zero for the first page
				$start = 0;
			}
			else
			{
				$start=$request->getParam('start');
			}
			$page=0;
			$limit=30;

			$auditloglist = $auditobj->auditloglist($start,$limit);
			
			//die;
			$countauditlog = $auditobj->countauditlog();
			$getapplicationtype = $auditobj->getapplicationtype();
		   // print_r($getapplicationtype); exit;
			$this->view->assign('auditlogdata', $auditloglist);
			$this->view->assign('applicationtype', $getapplicationtype);

			$page_name = '';
			$pagination1=$this->pagination_search($countauditlog,$start,$limit,$page_name);
			$this->view->assign('pagination', $pagination1);
			$this->view->assign('start', $start);
			
			$this->view->assign('counttotalauditlog', $countauditlog);
	}
	
	public function pagination_search($nume,$start,$limit,$page_name=null)
	{

		if($nume > $limit)
		{
		$page_name = $page_name.'?application_type='.$_GET['search'];
		$this1 = $start + $limit; 
		$back = $start - $limit; 
		$next = $start + $limit;
		
		$paginate="";
		$paginate.='<ul class="pagination">';

		if($back >=0)
		{
			$paginate.='<li><a href="'.$page_name.'&start=0" class="head2">&lt; FIRST</a></li>';
			$paginate.='<li><a href="'.$page_name.'&start='.$back.'" class="head2">&lt; PREV</a></li>';
		}
		//if($nume <= 100){$start = 0;}
		$i=$start;
		$l=$start/10;
		if($l < 1) {$l = 1;} else {$l = $l+1;}
		$countnum = $l + 10;
		if($nume > 100){
			for($i=$start;$i < $nume;$i=$i+$limit)
			{
				if($countnum > $l) {
					if($i <> $start)
					{
						$paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
					}
					else
					{
						$paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
					}
				}
				$l=$l+1;
			
			}
		} else {
			$l = 1;
			for($i=0;$i < $nume;$i=$i+$limit)
			{
				if($i <> $start)
				{
					$paginate.='<li><a href="'.$page_name.'&start='.$i.'" class="text">'.$l.'</a></li>';
				}
				else
				{
					$paginate.='<li><a href="#" class="text active">'.$l.'</a></li>';
				}
				$l=$l+1;
			
			}
		}
		$last = $nume - $nume%10;
		if($this1 < $nume)
		{ 
			$paginate.='<li><a href="'.$page_name.'&start='.$next.'" class="head2">NEXT &gt;</a></li>';
			$paginate.='<li><a href="'.$page_name.'&start='.$last.'" class="head2">LAST &gt;</a></li>';
		}
		$paginate.='</ul>';

		//echo $paginate;
		$this->view->assign('paginate', $paginate);
		}	
			
	}

}
