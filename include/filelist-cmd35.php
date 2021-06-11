<?php
//require('functions_text.php');
if (!isset($reg_valuename)) {echo 'Не задан параметр valuename';exit;} 
if (!isset($reg_fname)) {echo 'Не задан параметр fname';exit;} 
echo $strHtmStartMain;

$file_name_arr=file($filename_files);
if ($file_name_arr==false) {echo 'Ошибка чтения файла '.$filename_files;exit;}
$fh=fopen($reg_fname,'wb');
if ($fh==false) {echo 'Ошибка создания файла '.$reg_fname;exit;}
$str='<?php';
fwrite($fh,$str."\r\n");echo htmlentities($str,ENT_QUOTES,'cp1251').'<br>';
$str='$'.$reg_valuename.'=array();';
fwrite($fh,$str."\r\n");echo htmlentities($str,ENT_QUOTES,'cp1251').'<br>';
$fcount=0;
foreach($file_name_arr as $fname)
	{
		$fname=trim($fname);
		$file_text=file_get_contents($fname);
		$str='$'.$reg_valuename.'['.$fcount.'] = \''.base64_encode($file_text).'\';';
		fwrite($fh,$str."\r\n");echo htmlentities($str,ENT_QUOTES,'cp1251').'<br>';
		$fcount++;
	}
$str='?>';
fwrite($fh,$str."\r\n");echo htmlentities($str,ENT_QUOTES,'cp1251').'<br>';	
fclose($fh);
echo $strHtmEndMain;
