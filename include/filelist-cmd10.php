<?php
$dirname=$reg_dir1;$dirname2=$reg_dir2;
$find1=true; // Если нужно искать второе слово

$arfile=ReadFilesInDir($dirname);
if (!is_dir($dirname2))
	{
		if (@mkdir($dirname2)==false) {echo 'Ошибка при создании каталога '.$dirname2;exit;}
	}
echo $strHtmStartMain;
foreach($arfile as $file_name)
	{
		$file=file($file_name);
		$str_find='Раздел:';
		$str_find2='Группа';
		$num_str=0;
		for($a=0;$a<9;$a++)
			{	
			
			if ($find1)
				{
				if ((strpos($file[$a],$str_find)===0) && (strpos($file[$a],$str_find2)!==false))
					{
					$num_str=$a;
					}
				}
				else
				{
				if (strpos($file[$a],$str_find)===0)
					{
					$num_str=$a;
					}
				}
			}
		if ($num_str>0)
			{
				echo $file_name.' <b>'.$file[0].'</b> '.$file[$num_str].'<br>';
				$new_fname=str_replace($dirname,$dirname2,$file_name);
				$fh=fopen($new_fname,'wb');
				fwrite($fh,$file[0]);
				for($a=$num_str+1;$a<count($file);$a++)
					{
						fwrite($fh,$file[$a]);
					}
				fclose($fh);
			}
	}
echo $strHtmEndMain;
exit;
?>
