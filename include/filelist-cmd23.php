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
				$ftitle=str_replace(' – Описание – Яндекс.Маркет','',$ftitle);
				echo $fname.' '.$ftitle;
				if (strpos($fcontent,'Стиральные машины')>0) {echo ' Стиральные машины';}
				if (strpos($fcontent,'Игровые приставки')>0) {echo ' Игровые приставки';}
				if (strpos($fcontent,'Мониторы')>0) {echo ' Мониторы';}
				if (strpos($fcontent,'Карты памяти')>0) {echo ' Карты памяти';}
				if (strpos($fcontent,'Карты памяти')>0) {echo ' Карты памяти';}
				echo '<br>';
			}
			else
			{
				echo $fname.' Нет файла <br>';
			}
	}

echo $strHtmEndMain;
?>