<?php
require('functions_text.php');
if (!isset($reg_numstart)) {$reg_numstart=1;} else {$reg_numstart=intval($reg_numstart);}
if (!isset($reg_numend)) {$reg_numend=2;} else {$reg_numend=intval($reg_numend);}
if (!isset($reg_numzero)) {$reg_numzero=0;} else {$reg_numzero=intval($reg_numzero);}
if (!isset($reg_dir)) {echo 'Не задан параметр dir';exit;} 
echo $strHtmStartMain;
for($a=$reg_numstart;$a<=$reg_numend;$a++)
	{
		$num_str=AddZero($a,$reg_numzero);
		$fname1='t'.$num_str;
		//echo $a.'<br>';
		if (file_exists($reg_dir.'/'.$fname1.'.pdf'))
			{
				if (is_dir($reg_dir.'/'.$fname1)==false)
					{
						if (mkdir($reg_dir.'/'.$fname1)==false) {echo "Ошибка про создании каталога";exit;}
					} 
				if (rename($reg_dir.'/'.$fname1.'.pdf',$reg_dir.'/'.$fname1.'/'.$fname1.'.pdf'))
					{echo 'Перенесен файл: '.$fname1.'.pdf';}
					else
					{echo 'Ошибка при переносе файла: '.$fname1.'.pdf';}	
				echo ' ';	
				if (rename($reg_dir.'/'.$fname1.'.jpg',$reg_dir.'/'.$fname1.'/'.$fname1.'.jpg'))
					{echo 'Перенесен файл: '.$fname1.'.jpg';}
					else
					{echo 'Ошибка при переносе файла: '.$fname1.'.jpg';}
				echo ' ';	
				if (copy($reg_dir.'/readme.html',$reg_dir.'/'.$fname1.'/readme.html'))
					{echo 'Скопирован файл: readme.html';}
					else
					{echo 'Ошибка при копировании файла: readme.html';}
				echo "<br>\r\n";	
			} else {echo 'Не найден файл '.$reg_dir.'/'.$fname1.'.pdf'.'<br>';}
	}
echo $strHtmEndMain;
?>