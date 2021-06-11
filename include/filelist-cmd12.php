<?php
if (!isset($reg_dir)) {$reg_dir='';}
if (!isset($reg_showrazdel)) {$reg_showrazdel='';}
if (!isset($reg_showautor)) {$reg_showautor='';}
if (!isset($reg_showfname)) {$reg_showfname='';}
if (is_dir($reg_dir)==false)
	{echo "Не найден каталог $reg_dir ";exit;}
$arfile=ReadFilesInDir($reg_dir);
$ar1=array();
echo $strHtmStartMain;
foreach($arfile as $file_name)
	{
		$file=file($file_name);
		$str=$file[0].'|';
		if ($reg_showrazdel=='yes')
			{
				$razdel='';
				for($a=1;$a<=10;$a++)
					{
						if (strpos($file[$a],'Раздел')===0) {$razdel=$file[$a];}
					}
				$str.=$razdel.'|';
			}
		if ($reg_showautor=='yes')
			{
				$razdel='';
				for($a=1;$a<=10;$a++)
					{
						if (strpos($file[$a],'Автор')===0) {$razdel=$file[$a];}
					}
				$str.=$razdel.'|';
			}
		if ($reg_showfname=='yes')	
			{
				$str.=$file_name;
			}
		$ar1[]=$str;
	}
sort($ar1);
foreach($ar1 as $val)
	{echo $val."<br>\r\n";}
echo $strHtmEndMain;
exit;
?>