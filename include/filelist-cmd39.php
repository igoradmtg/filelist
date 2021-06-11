<?php
//*********************************************************************************************************************
// ��������� ��������� ������
// ������ ��������� ���� � ������� ������ �������� �����
// ����������� �������� ����� � �� ������ ��� ���� � ������ ���� ���������
//*********************************************************************************************************************
require('include/functions_text.php');
define('NUM_PARAGRAFS',1); // ������������ ���������� ���������
define('IS_SPACE_MULTI',true); // ���� ����� ��������� �������� ����� ��� ��� � ������ ��������� ��������
define('NUM_SPACE_MULTI',3); // ���������� �������� ������� ����� ������� ���� �������� IS_SPACE_MULTI ���������� true
if (!isset($reg_dirname1)) {echo '�� ����� �������� dirname1';exit;} 
if (!isset($reg_dirname2)) {echo '�� ����� �������� dirname2';exit;} 
if (!is_dir($reg_dirname1)) {echo '�� ������ ������� '.$reg_dirname1;exit;}
if (!is_dir($reg_dirname2))
	{
		// ������� ������� ������� ���� �� �� ����������
		if (mkdir($reg_dirname2)==false) {echo '������ ��� �������� �������� '.$reg_dirname2;exit;}
	}
echo $strHtmStartMain;
$file_name_arr=ReturnAllFilesInDir($reg_dirname1,'.txt',true,false);
if ($file_name_arr==false)	{echo '������ ��� ������ �������� '.$reg_dirname1;exit;}
$file_text_ar=array();
foreach($file_name_arr as $fkey=>$fname)
	{
		$file_text=file($fname);
		$fname2=$reg_dirname2.'/'.basename($fname);
		$fname2base=basename($fname);
		echo '�������� ����: '.$fname.'<br>';
		$file_text_save='';$new_para=NUM_PARAGRAFS;
		foreach($file_text as $row)
			{
				$find_tab=false;
				if (substr($row,0,1)=="\t") {$find_tab=true;}
				if (IS_SPACE_MULTI)
					{
						$str_space_multi=str_repeat(' ',NUM_SPACE_MULTI);
						if (substr($row,0,NUM_SPACE_MULTI)==$str_space_multi)
							{
								$find_tab=true;
							}
					}
				$row=trim($row);
				if (empty($row)) 
					{
						if ($new_para<NUM_PARAGRAFS)
							{
								$new_para++;
								$file_text_save.="\r\n";
							}
						continue;
					}
				if ($find_tab==false)
					{
						// ��������� ����� ��� ��������
						$file_text_save.=' '.$row;
					}
					else
					{
						// ��������� ����� c ���������
						if ($new_para<NUM_PARAGRAFS)
							{
								$new_para++;
								$file_text_save.="\r\n";
							}
						$file_text_save.=$row;
					}
				$new_para=0;	
			}
		$file_text_save=preg_replace('/  +/',' ',$file_text_save); // ������� ������������� �������
		$fh=fopen($fname2,'wb');
		if ($fh==false) {echo '������ ��� ������ ����� '.$fname2.'<br>';exit;}
		fwrite($fh,$file_text_save);
		fclose($fh);
		echo "������� ���� $fname2 <br>";
	}
echo $strHtmEndMain;
?>