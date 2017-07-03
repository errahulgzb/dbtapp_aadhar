<?php require_once "include_phpuploader.php" ?>
<?php

set_time_limit(3600);

$uploader=new PhpUploader();

$uploader->PreProcessRequest();

$guid = $uploader->GetCurrentFileGuid();

$mvcfile=$uploader->GetValidatingFile();

if($mvcfile->FileName=="thisisanotvalidfile")
{
	$uploader->WriteValidationError("My custom error : Invalid file name. ");
	exit(200);
}


if( $uploader->SaveDirectory )
{
	if(!$uploader->AllowedFileExtensions)
	{
		$uploader->WriteValidationError("When using SaveDirectory property, you must specify AllowedFileExtensions for security purpose.");
		exit(200);
	}

	$cwd=getcwd();
	chdir( dirname($uploader->_SourceFileName) );
	if( ! is_dir($uploader->SaveDirectory) )
	{
		$uploader->WriteValidationError("Invalid SaveDirectory ! not exists.");
		exit(200);
	}
	chdir( $uploader->SaveDirectory );
	$wd=getcwd();
	chdir($cwd);
	$fTemp = $mvcfile->FileName;
		if (strpos ( $fTemp, "#" )) {
				$fTemp = str_replace ( "#", "!!", $fTemp );
			}	
			if (strpos ( $fTemp, "+" )) {
				$fTemp = str_replace ( "+", "!!", $fTemp );
			}		
			if (strpos ( $fTemp, "@" )) {
				$fTemp = str_replace ( "@", "!!", $fTemp );
			}		
			if (strpos ( $fTemp, "&" )) {
				$fTemp = str_replace ( "&", "!!", $fTemp );
			}
			if (strpos ( $fTemp, "-" )) {
				$fTemp = str_replace ( "-", "!!", $fTemp );
			}
		
		
			if (strpos ( $fTemp, " " )) {
				$fTemp = str_replace ( " ", "!!", $fTemp );
			}
			if (strpos ( $fTemp, "(" )) {
				$fTemp = str_replace ( "(", "!!", $fTemp );
			}		
			if (strpos ( $fTemp, "'" )) {
				$fTemp = str_replace ( "'", "!!", $fTemp );
			}		
			if (strpos ( $fTemp, ")" )) {
				$fTemp = str_replace ( ")", "!!", $fTemp );
			}
			if (strpos ( $fTemp, "%" )) {
				$fTemp = str_replace ( "%", "!!", $fTemp );
			}
		$length=6; 
		$type=0;
		for($i=1; $i<=$length; $i++)
		{
			if($type==0)
				$number=mt_rand(1,3);
			else
				$number=$type;
			if($number==1)
			{
				$min=65;
				$max=89;
			}
			elseif($number==2)
			{
				$min=48;
				$max=57;
			}
			else
			{
				$min=97;
				$max=122;
			}

			
		}
		$gud = explode("-",$guid);
		$newFileName = addslashes($gud[0].$fTemp);
	//$targetfilepath=  "$wd/" . $mvcfile->FileName;
	$targetfilepath=  "$wd/" . $newFileName;
	if( file_exists ($targetfilepath) )
		unlink($targetfilepath);

	$mvcfile->CopyTo( $targetfilepath );
}

$uploader->WriteValidationOK("");

?>