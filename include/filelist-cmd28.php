<?php
include('functions_text.php');
echo $strHtmStartMain;
$file_name_img=@file('filelist.txt');
if ($file_name_img==false) {echo 'Ошибка чтения файла со списком ссылок filelist.txt';exit;}
$file_name_arr=@file('files_list.txt');
if ($file_name_arr==false) {echo 'Ошибка чтения файла со списком ссылок files_list.txt';exit;}
echo "\r\n\r\n\r\n";
echo '<table>';
shuffle($file_name_img);
$cur_img=0;
echo '<tr>';
$num_col=0;
foreach($file_name_arr as $key=>$val)
	{
		if ($key==0) {continue;} // Первую строку пропускаем нахуй
		//$ar=explode(" ",trim($val));
		$img=trim($file_name_img[$cur_img]);
		$url_img=trim($val);
		$cur_img++;if ($cur_img>=count($file_name_img)) {$cur_img=0;}
		
		echo '<td align="center"><a href="'.$url_img.'" target="_blank"><img src="'.$img.'" alt="'.$url_img.'" border="0"></a><br><a href="'.$url_img.'" target="_blank">'.$url_img.'</a></td>';
		$num_col++;
		if ($num_col>=3)
			{$num_col=0;echo "</tr>\r\n<tr>";}
		echo "\r\n";
	}
echo '</tr>';	
echo '</table>';
echo "\r\n\r\n\r\n";
echo $strHtmEndMain;
?>