<?php
include('functions_text.php');
echo $strHtmStartMain;
if (!isset($reg_dir)) {echo 'Не указано имя каталога';exit;}
if (!isset($reg_name)) {echo 'Не указано имя файла';exit;}
if (!isset($reg_numfiles)) {$reg_numfiles=0;}
if (!isset($reg_startnorazdel)) {$reg_startnorazdel='';}
if (!isset($reg_razdel)) {$reg_razdel='';}
if (!isset($reg_shufflefile)) {$reg_shufflefile='';}
if (!isset($reg_addperenos)) {$reg_addperenos='';}
if (!isset($reg_numaddzero)) {$reg_numaddzero=3;} else {$reg_numaddzero=intval($reg_numaddzero);}
if (!isset($reg_numstartfile)) {$reg_numstartfile=1;} else {$reg_numstartfile=intval($reg_numstartfile);}
if (!is_dir($reg_dir)) 
	{
		if (@mkdir($reg_dir)==false) {echo 'Ошибка создания каталога '.$reg_dir;exit;}
	}
$file_name_arr=file('filelist.txt');
if ($file_name_arr==false) {echo 'Ошибка чтения файла filelist.txt';exit;}
if ($reg_shufflefile=='yes') {shuffle($file_name_arr);}
if ($reg_numfiles==0)
	{
		$fh=fopen($reg_dir.'/'.$reg_name,'wb');
		foreach($file_name_arr as $fname)
			{
				$cur_file_name=trim($fname);
				$file_text=file($cur_file_name);
				foreach($file_text as $row)
					{
						fwrite($fh,trim($row)."\r\n");
					}
				echo 'Записан файл '.$cur_file_name.'<br>';
			}
		fclose($fh);
	}
	else
	{
		$max_files=count($file_name_arr);
		$reg_numfiles=intval($reg_numfiles);
		$max_count=intval(($max_files-1) / $reg_numfiles);
		echo 'Записаны фйлы: <br>'."\r\n";
		for($a=0;$a<=$max_count;$a++)
			{
				$new_file_name=$reg_dir.'/'.str_replace('NNN',AddZero(($a+$reg_numstartfile),$reg_numaddzero),$reg_name);
				$fh=fopen($new_file_name,'wb');
				$start_razdel=false;
				for($b=($a*$reg_numfiles);$b<(($a+1)*$reg_numfiles);$b++)
					{
						//echo $b.' '.$max_files.'<br>';
						if ($b>($max_files-1)) {continue;}
						$cur_file_name=trim($file_name_arr[$b]);
						$file_text=file($cur_file_name);
						if ($reg_razdel=='yes')
							{
								$write_razdel_bool=true;
								if ($reg_startnorazdel=='yes')
									{
										if ($start_razdel==false)
											{
												$write_razdel_bool=false;
												$start_razdel=true;
											}
									}
								if ($write_razdel_bool) 
									{
										if ($reg_addperenos=='yes') {fwrite($fh,"\r\n");}
										fwrite($fh,trim($reg_razdeltxt)."\r\n");
										if ($reg_addperenos=='yes') {fwrite($fh,"\r\n");}
									}
							}
						foreach($file_text as $row)
							{
								fwrite($fh,trim($row)."\r\n");
							}
						echo $cur_file_name.' -> '.$new_file_name.'<br>'."\r\n";
					}
	
				fclose($fh);
			}
	}
echo $strHtmEndMain;
?>