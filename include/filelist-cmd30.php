<?php
include('functions_text.php');
function ReadFilesInDirTmp($dirname)
{
	$array_ret=array();
	if (is_dir($dirname))
		{
			if ($dh = opendir($dirname)) 
			{
				while (($file = readdir($dh)) !== false) 
				{
					if (($file=='.') || ($file=='..')) {continue;}
					if (!is_dir($dirname.'/'.$file))
						{$array_ret[]=$file;}
					//echo $file;
				}
				
			}
			else
			{echo '�� ���� �������';return false;}
						
		}
		else {echo '�� �������';return false;}
	return $array_ret;	
}

echo $strHtmStartMain;
if (!isset($reg_dir1)) {echo '�� ������ �������';exit;}
if (!isset($reg_dir2)) {echo '�� ������ �������';exit;}
if (!isset($reg_num)) {echo '�� ������� ���������� �������� � �������� �����';exit;}
$reg_num=intval($reg_num);
if ($reg_num==0) {echo '�� ������ ���������� �������� � �������� �����';exit;}
if (!is_dir($reg_dir2))
	{
		if (mkdir($reg_dir2)==false)
			{echo '������ ��� �������� �������� '.$reg_dir2;exit;}
	}
$f_ar=ReadFilesInDirTmp($reg_dir1);
if ($f_ar==false) {echo ' ������ ������ ��������';exit;}
$array_name_dir=array();
foreach($f_ar as $fname)
	{
		$name1=substr($fname,0,$reg_num);
		if (!in_array($name1,$array_name_dir))
			{$array_name_dir[]=$name1;}
	}
foreach($array_name_dir as $dir_name1)	
	{
		$new_dir=$reg_dir2.'/'.$dir_name1;
		if (is_dir($new_dir))
			{
				echo '������� ��� ������ '.$new_dir.'<br>';
			}
		else
			{
				if (mkdir($new_dir)==false)
					{echo '������ ��� �������� �������� '.$new_dir;exit;}
					else
					{echo '������ ������� '.$new_dir.'<br>';}
			}
	}
foreach($f_ar as $fname)
	{
		$old_name=$reg_dir1.'/'.$fname;
		$name1=substr($fname,0,$reg_num);
		$new_name=$reg_dir2.'/'.$name1.'/'.$fname;
		if (rename($old_name,$new_name)==false)
			{echo "������ ��� �������������� ����� $old_name $new_name ";exit;}
			else
			{echo "������������ ���� $new_name <br>\r\n";}
	}
	
echo $strHtmEndMain;
?>