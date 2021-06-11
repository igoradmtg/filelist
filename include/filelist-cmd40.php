<?php
//*********************************************************************************************************************
// <b>Сделать миниатюры 2 в 1</b> первая миниатюра сверху вторая снизу
// Поле ввода text|dirname1|data Каталог с исходными каталогами:
// Поле ввода text|dirname2|data2 Каталог для сохранения данных:
//*********************************************************************************************************************
require('include/functions_text.php');
require('include/filelist-func-images.php');

if (!isset($reg_dirname1)) {echo 'Не задан параметр dirname1';exit;} 
if (!isset($reg_dirname2)) {echo 'Не задан параметр dirname2';exit;} 
if (!is_dir($reg_dirname1)) {echo 'Не найден каталог '.$reg_dirname1;exit;}
if (!is_dir($reg_dirname2))
	{
		// Попытка создать каталог если он не существует
		if (mkdir($reg_dirname2)==false) {echo 'Ошибка при создании каталога '.$reg_dirname2;exit;}
	}
echo $strHtmStartMain;

$dir_name_arr=ReturnAllFilesInDir($reg_dirname1,'',true,true);
if ($dir_name_arr==false)	{echo 'Ошибка при чтении каталога '.$reg_dirname1;exit;}
foreach($dir_name_arr as $fkey=>$dirname2)
	{
		$file_name_ar=ReturnAllFilesInDir($dirname2,'.jpg',true,false);
		if ($file_name_ar!=false)
			{
				echo 'Прочитан каталог '.$dirname2.' Найдено '.count($file_name_ar).' файлов<br>';
				if (count($file_name_ar)>2)
					{
						shuffle($file_name_ar);
						$fimg1=array_pop($file_name_ar);
						$fimg2=array_pop($file_name_ar);
						//$fimg3=array_pop($file_name_ar);
						$fimg_out=$reg_dirname2.'/'.basename($dirname2).'.jpg';
						echo $fimg1.' '.$fimg2.' '.$fimg_out.'<br>';
						MakeMini2to1($fimg1,$fimg2,$fimg_out,200,600,95,true);
						
					}
					else
					{
						echo 'Ошибка! Меньше двух файлов не обрабатываем<br>';
					}
			}
	}
echo $strHtmEndMain;
?>