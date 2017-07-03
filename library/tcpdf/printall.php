
    
        
      

<?PHP
session_start ();
include ("includes/connect.php");
include ("includes/message.php");
//include ("commonValidation.php");
include ("includes/function.inc.php");
//include ("includes/csrf_magic.php");
//require_once 'Classes/PHPExcel.php';
//echo 'Anj';
//echo $_GET['a'];

$cond1 = $_SESSION['cond1'];
$cond2 = $_SESSION['cond2'];

if(isset($_GET['a'])){
$_SESSION ['a'] = $_GET['a'];
}
//echo "Anj"; echo $_SESSION ['a'];
 
if($_SESSION ['clientuserid'])
{
		$company_id_result = mysql_query ( "select id from cwi_company where organizationuserid='" . $_SESSION ['clientuserid'] . "'" );
		$company_id = mysql_fetch_array ( $company_id_result );
		$tableid=$company_id['id'];

}

$querystring_1 = "select * from cwi_lodgereport where tbId='$tableid' and deleteOptionByOrganization=0 and deleteOption=0 and manageByCompany=1 and actionStatus='1'";
//echo $querystring_1;
$result = mysql_query ( $querystring_1 );
mysql_error ();
$nume = mysql_num_rows ( $result );

//********** Code for Pagingnation **********
	//$tableName="login";		
	$targetpage = "printall.php"; 	
	$total_pages = mysql_num_rows ( $result );
	$limit = LIMIT; 
	$stages = PAGINATION_STAGES;
	$page = mysql_escape_string($_GET['page']);
	if($page){
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
$start = $_SESSION['srt_lmt'];
// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					

//********** End Code for Pagingnation **********
?>

    
  
    
	
<table style="width: 100%; border-collapse: collapse;" >
                                   
                                                      
                                      <tr nobr="true">
                                        <td width="5%"   style="border: solid 1px #000000; font-weight:bold; " ><div align="center">S.No.</div></td>
                                        <td width="11%"   style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Report No.</div></td>
                                        <td width="11%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Date</div></td>
                                        <td width="10%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Time</div></td>
                                        <td width="9%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Status</div></td>
                                        <td width="9%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Priority</div></td>
                                        <td width="15%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center"><?php echo displayMartixTable('subject'); ?></div></td>
										
                                        <td width="15%"  style="border: solid 1px #000000; font-weight:bold;" ><div align="center">Report Detail</div></td>
                                        <td width="16%"  style="border: solid 1px #000000; font-weight:bold;"   ><div align="center">Comment</div></td>
                                      </tr>
                                      <?PHP
																								
		
	if($_SESSION ['clientuserid'])
{
		$company_id_result = mysql_query ( "select id from cwi_company where organizationuserid='" . $_SESSION ['clientuserid'] . "'" );
		$company_id = mysql_fetch_array ( $company_id_result );
		$tableid=$company_id['id'];

}																						$report = mysql_query ( "select * from cwi_lodgereport where tbId='$tableid' and deleteOptionByOrganization=0 and deleteOption=0 and manageByCompany=1  and actionStatus=1 $cond1 $cond2 order by id desc,dateTime DESC" );
																								$rport_data = mysql_fetch_array ( $report );
																								$nume = mysql_num_rows ( $report );
																								if ($nume == 0) {
																									?>
                                      <tr >
                                        <td colspan="9" align="center"   >No
                                          Report(s) Found</td>
                                      </tr>
                                      <?PHP
																								} else
																									$report = mysql_query ( "select * from cwi_lodgereport where tbId='$tableid' and deleteOptionByOrganization=0 and deleteOption=0 and manageByCompany=1 and actionStatus=1 $cond1 $cond2  order by id desc,dateTime DESC limit $start,$limit" );
																								$i = 1;
																								while ( $report_data = mysql_fetch_array ( $report ) ) {
																									?>

                                      <tr nobr="true" height="15">
                                        <td  align="center" valign="top" style="border: solid 1px #000000;" ><?PHP
																									echo $i + $start;
																									?>.</td>
                                        <td  valign="top" style="border: solid 1px #000000; " >
                                          <?PHP	echo $report_data ['reportId']; ?>
																								<?php
																									// if report is lodged by operator then displays the * in red color. Added by dilip (25-08-2011)
																										if(($report_data['userType']=='1') or ($report_data['userType']=='2')){ ?>
																										<span ><img src="images/star_operator.gif" height="4" width="4" alt="<?php echo OPERATOR; ?>" title="<?php echo OPERATOR; ?>" /></span>
																										<?php } //end if($report_data['userType']=='1') ?>
																										</td>
                                        <td   valign="top" align="center" style="border: solid 1px #000000;" ><?PHP
																									echo $report_data ['dateAdded'];
																									?></td>
                                        <td   align="center" valign="top" style="border: solid 1px #000000;" ><?PHP
$sqlCT = mysql_query ( "select * from cwi_conversation where lodgereportId='" . $report_data['id'] . "' order by id ASC" );
$sqlCT_data = mysql_fetch_array ( $sqlCT );
	$timeCT = $sqlCT_data ['dateAdded'];
	$timestampCT = strtotime ( $timeCT );
	//echo date ( "l, M d, Y", $timestampCT ); echo " ";
	echo substr ( $sqlCT_data ['dateTime'], 11, 8 );
	/////echo substr($report_data['dateTime'],10,9);
?></td>
                                        <td  align="center" valign="top" style="border: solid 1px #000000;"  ><?PHP
																									if ($report_data ['status'] == '1') {
																										echo "Closed";
																									}
																									if ($report_data ['status'] == '0') {
																										echo "Pending";
																									}
																									?>
																									</td>
                                        <td   valign="top" style="border: solid 1px #000000;" ><?php   if($report_data ['priority']=='1'){
																									echo"Normal";
																								}else if($report_data ['priority']=='2'){
																								
																								echo"Low";
																								}else if($report_data ['priority']=='3'){
																								echo"Medium";
																								}else if($report_data ['priority']=='4'){
																								echo"High";
																								}
																										
																									?></td>
                                        <td   align="center" valign="top"  style="border: solid 1px #000000;"  ><?php  
																			if(($report_data ['subject']!='') || ($report_data ['subject']!=NULL)){	
																			$strSubject="";
																			$ST=$report_data ['subject'];
																			$sqlST = mysql_query ( "select * from cwi_subject_matrix where org_id='" .$_SESSION['organisation_id']."'" );
																			$sqlST_data = mysql_fetch_array($sqlST );
																												
																			//$strSubject = $sqlST_data[$ST];
																		echo $strSubject =$sqlST_data[$ST];
																		}else{
																		echo "--";
																		}
																		?></td>
											
                                        <td  valign="top"   style="border: solid 1px #000000;" <?php if($report_data['dis_feedback']==''){   ?> align="center" <?php }else{ ?> align="left"<?php  } ?>><?php //echo $report_data ['dis_feedback'];
										if($_SESSION ['a'] == 1)
										{
											echo $report_data ['dis_feedback'];
										}
										else if($_SESSION ['a'] == 2)
										{
											
										echo substr($report_data ['dis_feedback'], 0,8); 
										if(strlen($report_data ['dis_feedback'])>8)
											{     
											echo "...";
											}
										}
										?></td>
                                        <td valign="top" style="border: solid 1px #000000;"<?php if($report_data['comment']==''){   ?> align="center" <?php }else{ ?> align="left"<?php  } ?>><?php
											
										
										//echo $report_data ['dis_feedback'];

										if($report_data['comment']!='' || $report_data['comment']!=NULL){
											if($_SESSION ['a'] == 2)
											{
			
											$comment_report=$report_data['comment'];
											echo $comment_report=substr($report_data ['comment'], 0,8); 
											if(strlen($report_data ['comment'])>8)
												{     
											echo "...";
												} 
											}
											else if($_SESSION ['a'] == 1){
												//echo"Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar  Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar  Nrmal Kumar Nirmal Kumar  Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar  Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar Nirmal Kumar ";
												 //$comment_report=$report_data['comment'];
											}
										}else{
										echo $comment_report="--";
										}
										//echo $comment_report;
										?></td>
                                      </tr>
                                      
                                     
                                     
                                      <?PHP
																									$i ++;
																								}
																								?>
                                  
        
    </table>

