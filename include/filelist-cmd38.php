<?php
//*********************************************************************************************************************
// Читаем текстовый файл и убираем лишние переносы строк
//*********************************************************************************************************************
require('include/functions_text.php');
define('NUM_PARAGRAFS',1); // Максимальное количество переносов
if (!isset($reg_dirname1)) {echo 'Не задан параметр dirname1';exit;} 
if (!isset($reg_dirname2)) {echo 'Не задан параметр dirname2';exit;} 
if (!is_dir($reg_dirname1)) {echo 'Не найден каталог '.$reg_dirname1;exit;}
if (!is_dir($reg_dirname2))
	{
		// Попытка создать каталог если он не существует
		if (mkdir($reg_dirname2)==false) {echo 'Ошибка при создании каталога '.$reg_dirname2;exit;}
	}
echo $strHtmStartMain;
$file_name_arr=ReturnAllFilesInDir($reg_dirname1,'.txt',true,false);
if ($file_name_arr==false)	{echo 'Ошибка при чтении каталога '.$reg_dirname1;exit;}
$file_text_ar=array();
mb_internal_encoding('CP-1251');
foreach($file_name_arr as $fkey=>$fname)
	{
		$file_text=file($fname);
		$fname2=$reg_dirname2.'/'.basename($fname);
		$fname2base=basename($fname);
		echo 'Прочитан файл: '.$fname.'<br>';
		$file_text_save='';$new_para=NUM_PARAGRAFS;
		foreach($file_text as $row)
			{
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
				$leter1=substr($row,0,1);
				$leter1_low=mb_strtolower($leter1);
				if (substr($row,0,2)=='--')	
					{
						// Добавляем текст c переносом
						if ($new_para<NUM_PARAGRAFS)
							{
								$new_para++;
								$file_text_save.="\r\n";
							}
						$file_text_save.=$row;
					}
				elseif ($leter1==$leter1_low)
					{
						// Добавляем текст без переноса
						$file_text_save.=' '.$row;
					}
					else
					{
						// Добавляем текст c переносом
						if ($new_para<NUM_PARAGRAFS)
							{
								$new_para++;
								$file_text_save.="\r\n";
							}
						$file_text_save.=$row;
					}
				$new_para=0;	
			}
		
		$file_text_save=preg_replace('/  +/',' ',$file_text_save); // Удалить повторяющиеся пробелы
		$fh=fopen($fname2,'wb');
		if ($fh==false) {echo 'Ошибка при записи файла '.$fname2.'<br>';exit;}
		fwrite($fh,$file_text_save);
		fclose($fh);
		echo "Записан файл $fname2 <br>";
	}
echo $strHtmEndMain;
?>