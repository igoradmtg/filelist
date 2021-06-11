<?php
	// Удаление дублирующихся строк в файле filelist.txt
	include('functions_text.php');
	echo $strHtmStartMain;
	$file1=file('filelist.txt');
	echo 'Начальное количество строк '.count($file1)."<br>\r\n";
	$arr=array();
	foreach($file1 as $row)
		{
			$lnk1=trim($row);
			if (!in_array($lnk1,$arr)) {$arr[]=$lnk1;}
		}
	echo 'Количество строк после обработки '.count($arr)."<br>\r\n";
	sort($arr);
	$fh=fopen('filelist.txt','wb');
	$str_array=implode("\r\n",$arr);
	fwrite($fh,$str_array."\r\n");
	fclose($fh);
	
	$file1=file('filelist2.txt');
	echo 'Начальное количество строк '.count($file1)."<br>\r\n";
	$arr=array();
	foreach($file1 as $row)
		{
			$lnk1=trim($row);
			if (!in_array($lnk1,$arr)) {$arr[]=$lnk1;}
		}
	echo 'Количество строк после обработки '.count($arr)."<br>\r\n";
	sort($arr);
	$fh=fopen('filelist2.txt','wb');
	$str_array=implode("\r\n",$arr);
	fwrite($fh,$str_array."\r\n");
	fclose($fh);
	
	echo $strHtmEndMain;
	exit;
?>