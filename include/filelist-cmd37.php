<?php
require('include/functions_text.php');
if (!isset($reg_dirname1)) {echo 'Не задан параметр dirname1';exit;} 
if (!isset($reg_dirname2)) {echo 'Не задан параметр dirname2';exit;} 
if (!is_dir($reg_dirname1)) {echo 'Не найден каталог '.$reg_dirname1;exit;}
if (!is_dir($reg_dirname2))
	{
		// Попытка создать каталог если он не существует
		if (mkdir($reg_dirname2)==false) {echo 'Ошибка при создании каталога '.$reg_dirname2;exit;}
	}
echo $strHtmStartMain;
$pattern='/(?ims)<!-- '.'InstanceBeginEditable name="EditRegion3"'.' -->(.*)'.'<!-- '.'InstanceEndEditable'.' -->/U';

$file_name_arr=ReturnAllFilesInDir($reg_dirname1,'.html',true,false);
if ($file_name_arr==false)	{echo 'Ошибка при чтении каталога '.$reg_dirname1;exit;}
$file_text_ar=array();
foreach($file_name_arr as $fkey=>$fname)
	{
		$file_text=file_get_contents($fname);
		$fname2=$reg_dirname2.'/'.basename($fname);
		$fname2base=basename($fname);
		echo 'Прочитан файл: '.$fname.'<br>';
		// Поиск первого вхождения h3
		$num_find=preg_match($pattern, $file_text, $matches); 
		if ($num_find==0) {echo 'На найдено совпадений по шаблону';exit;}
		//echo '<pre>';print_r($matches);echo '</pre>';
		$file_body=$matches[1];
		$file_body=preg_replace('/(?ims)<div\s+class="lecture_mark"\s+id="mark_[0-9]+"><\/div>/U','',$file_body);
		
		$file_text='<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head><body>'."\r\n".$file_body."\r\n".'</body></html>';
		// Записываем все данные в файлу
		$fh=fopen($fname2,'wb');
		if ($fh==false) {echo 'Ошибка при записи файла '.$fname2.'<br>';exit;}
		fwrite($fh,$file_text);
		fclose($fh);
		echo "Записан файл $fname2 <br>";
	}
echo $strHtmEndMain;
?>