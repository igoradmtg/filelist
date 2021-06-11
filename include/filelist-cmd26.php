<?php
include('functions_text.php');
if (!isset($reg_dirname1)) {echo 'Не задан параметр dirname1';exit;} 
if (!isset($reg_dirname2)) {echo 'Не задан параметр dirname2';exit;} 
echo $strHtmStartMain;
$file_name_arr=ReturnAllFilesInDir($reg_dirname1,'',true,true);
if ($file_name_arr==false)	{echo 'Ошибка при чтении каталога '.$reg_dirname1;exit;}
$fname='rar.cmd';
$fh=fopen($fname,'wb');
if ($fh==false) {echo 'Ошибка при создании файла '.$fname.'<br>';exit;}
foreach($file_name_arr as $dirname)
	{
		$txt='"c:/Program Files/WinRAR/WinRAR.exe" m -m5 -ep "c:/html/filelist/'.$reg_dirname2.'/'.basename($dirname).'" '
		.'"c:/html/filelist/'.$dirname.'/*.*"';
		echo $txt.'<br>';
		fwrite($fh,$txt."\r\n");
	}
fclose($fh);
echo 'Записан файл '.'rar.cmd'.'<br>';
echo $strHtmEndMain;
?>