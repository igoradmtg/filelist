<?php
	include('functions_text.php');
	echo $strHtmStartMain;
	if (!is_dir($reg_dir)) {mkdir($reg_dir);} // ���� �������� ���� ����� �� ��� �������
	$arrayLinksTeg2=file('filelist2.txt');
	$fnameini='filelist.ini';
	if (file_exists($fnameini))
		{
			$filearrayini=file($fnameini);
			$cur_key=intval(trim($filearrayini[0]));
		}
		else
		{
			$cur_key=0;
		}
	if ($cur_key>=count($arrayLinksTeg2)) {echo '��������� ���������� �����';exit;}
	
	$yurl=$arrayLinksTeg2[$cur_key];
	$cur_key++;
	SaveParamToFile($cur_key,$fnameini);
	
	$reg_yurl=str_replace('&amp;','&',trim($yurl));
	$name_first=AddZero($cur_key,3);
	$tmp_file=$reg_dir.'/'.$name_first.'page.html';
	if (@copy($reg_yurl,$tmp_file)==false) 
		{echo "�� ���� ����������� ���� $reg_yurl $tmp_file <br>\r\n";continue;}
	$file_data=file_get_contents($tmp_file);
	if ($file_data==false) 
			{echo "�� ���� ��������� ���� $tmp_file  <br>\r\n";continue;}
	//unlink($tmp_file);
	$arrayLinksTeg=array();
	$countlink=0;
	findLinkInText($file_data,array('mdata.yandex.ru'));
	$count=0;
	echo $reg_yurl.' ������� �������� '.count($arrayLinksTeg)."<br>\r\n";
	foreach($arrayLinksTeg as $row)
		{
			$count++;
			echo "$row";
			if (copy($row,$reg_dir.'/'.$name_first.'img'.AddZero($count,2).'.jpg')==true) {echo ' OK ';} else {echo ' Error ';}
			echo "<br>\r\n";
		}
	$ttitle=FindHeader1($file_data);	
	
	$ttitle=trim(str_replace('� �������� � ������.������','',trim($ttitle))); 
	echo $ttitle."<br>\r\n";
	echo "��������� ������ $cur_key �� ".count($arrayLinksTeg2)." ������ <br>\r\n";
	$fh=fopen($reg_dir.'/'.$name_first.'readme.txt','wb');
	fwrite($fh,$ttitle);
	fclose($fh);
			
	echo $strHtmEndMain;
	exit;
?>
