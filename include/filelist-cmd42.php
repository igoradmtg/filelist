<?php
include('functions_text.php');
echo $strHtmStartMain;
if (!isset($reg_dirname1)) {echo '�� ������ ������� � ������� <br>';exit;}
if (!isset($reg_dirname2)) {echo '�� ������ ������� � ������� 2 <br>';exit;}
if (!isset($reg_dirname3)) {echo '�� ������ ������� � ������� 3 <br>';exit;}
$file_hot=$reg_dirname1.'/hot.txt';
$file_depo=$reg_dirname1.'/depo.txt';
if (!file_exists($file_hot)) {echo '�� ������ ���� '.$file_hot.' <br>';exit;}
if (!file_exists($file_depo)) {echo '�� ������ ���� '.$file_depo.' <br>';exit;}
$file_hot_ar=file($file_hot); // ������ � �������� hotfile
if ($file_hot_ar==false) {echo '������ ��� �������� ����� '.$file_hot.' <br>';exit;}
$file_depo_ar=file($file_depo); // ������ � �������� depo
if ($file_depo_ar==false) {echo '������ ��� �������� ����� '.$file_depo.' <br>';exit;}
$name_files=array(); // ������ � ���������� ������
$url_depo=array(); // ������ � �������� depositfiles
$url_hot=array(); // ������ � ������� hotfile
$num_file=0;
foreach($file_depo_ar as $furl_depo)
	{
		$furl_depo=trim($furl_depo);
		if (empty($furl_depo)) {continue;}
		list($furl_depo2,$fname_depo)=explode("   ",$furl_depo);
		$fname_depo=trim($fname_depo);
		$furl_depo2=trim($furl_depo2);
		if (empty($fname_depo)) {echo '������ ������ ����� ����� '.$furl_depo.'<br>';continue;}
		if (empty($furl_depo2)) {echo '������ ������ ����� ����� '.$furl_depo.'<br>';continue;}
		if (in_array($fname_depo,$name_files)==false)
			{
				echo 'ID:'.$num_file.' '.$fname_depo.' '.$furl_depo2.'<br>';
				$name_files[$num_file]=$fname_depo;
				$url_depo[$num_file]=$furl_depo2;
				$num_file++;
			}
			else
			{
				echo '����� ���� ��������� ID:'.$num_file.' '.$fname_depo.' '.$furl_depo2.'<br>';
			}
	}
foreach($file_hot_ar as $furl_hot)
	{
		$furl_hot=trim($furl_hot);
		if (empty($furl_hot)) {continue;}
		$fname_hot=basename($furl_hot);
		$fname_hot=str_replace('.html','',$fname_hot);
		if (empty($fname_hot)) {echo '������ ������ ����� ����� '.$furl_hot.'<br>';continue;}
		if (in_array($fname_hot,$name_files))
			{
				$key_search=array_search($fname_hot,$name_files);
				$url_hot[$key_search]=$furl_hot;
				echo 'ID:'.$key_search.' '.$name_files[$key_search].' '.$furl_hot.' ������ ����:'.$fname_hot.'<br>';
			}
		else
			{
				echo 'ID:'.$num_file.' '.$fname_hot.' '.$furl_hot.' �������� ����:'.$fname_hot.'<br>';
				$name_files[$num_file]=$fname_hot;
				$url_hot[$num_file]=$furl_hot;
				$num_file++;
			}
	}
echo '����� ������:'.count($name_files).' ������ HotFile:'.count($url_hot).' ������ DepositFiles:'.count($url_depo).'<br>';	
$fnamesave=$reg_dirname1.'/filelinks.txt';
$fh=fopen($fnamesave,'wb');	
foreach($name_files as $key=>$fname)
	{
		$fnameimg=str_replace(array('.rar','.zip'),'.jpg',$fname);
		$fname2=$reg_dirname2.'/'.$fname;
		if (file_exists($fname2)==false) {echo '�� ������ ���� � �������� 2 '.$fname2.'<br>';exit;}
		if (file_exists($reg_dirname3.'/'.$fnameimg)==false) {echo '�� ������ ���� � �������� �������� '.$reg_dirname3.'/'.$fnameimg.'<br>';exit;}
		$fsize=filesize($fname2);
		if ($fsize==0) {echo '������ ������� ������ ����� '.$fname2.'<br>';}
		$fsize=intval($fsize / (1024*1024));
		$str=trim($fname)."\t".$fnameimg."\t".$fsize."\t";
		if (isset($url_depo[$key])) {$str.=trim($url_depo[$key]);}
		$str.="\t";
		if (isset($url_hot[$key])) {$str.=trim($url_hot[$key]);}
		fwrite($fh,$str."\r\n");
	}
fclose($fh);
echo '������� ���� '.$fnamesave.'<br>';

echo $strHtmEndMain;
?>