<?php
include('functions_text.php');
echo $strHtmStartMain;

$reg_s=intval($reg_s);
$reg_e=intval($reg_e);
for($a=$reg_s;$a<=$reg_e;$a++)
	{
		$fname=$reg_dir.'/'.AddZero($a,3).'page.html';
		if (file_exists($fname))
			{
				$fcontent=file_get_contents($fname);
				$ftitle=FindTitle($fcontent);
				$ftitle=str_replace(' � �������� � ������.������','',$ftitle);
				echo $fname.' '.$ftitle;
				if (strpos($fcontent,'���������� ������')>0) {echo ' ���������� ������';}
				if (strpos($fcontent,'������� ���������')>0) {echo ' ������� ���������';}
				if (strpos($fcontent,'��������')>0) {echo ' ��������';}
				if (strpos($fcontent,'����� ������')>0) {echo ' ����� ������';}
				if (strpos($fcontent,'����� ������')>0) {echo ' ����� ������';}
				echo '<br>';
			}
			else
			{
				echo $fname.' ��� ����� <br>';
			}
	}

echo $strHtmEndMain;
?>