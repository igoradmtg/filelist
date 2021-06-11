<?php
include('functions_text.php');
echo $strHtmStartMain;
if (!isset($reg_dir)) {echo 'Не указано имя каталога';exit;}
if (!is_dir($reg_dir)) 
	{
		if (@mkdir($reg_dir)==false) {echo 'Ошибка создания каталога '.$reg_dir;exit;}
	}
$file_name_arr=file('filelist.txt');
if ($file_name_arr==false) {echo 'Ошибка чтения файла filelist.txt';exit;}
foreach($file_name_arr as $fname)
	{
		$cur_file_name=trim($fname);
		$file_text=file_get_contents($cur_file_name);
		$new_file_name=$reg_dir.'/'.basename($cur_file_name);
		$fh=fopen($new_file_name,'wb');
		$file_text2=convert_cyr_string($file_text,$reg_kod1,$reg_kod2); 
		fwrite($fh,$file_text2);
		fclose($fh);
		echo 'Конвертирован текст из файла '.$cur_file_name.'<br>';
	}
echo $strHtmEndMain;
?>