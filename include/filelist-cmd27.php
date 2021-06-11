<?php
include('functions_text.php');
echo $strHtmStartMain;
$file_name_arr=@file('files_list.txt');
if ($file_name_arr==false) {echo 'Ошибка чтения файла со списком ссылок';exit;}
echo "\r\n\r\n\r\n";
echo '<table>';
echo "\r\n\r\n\r\n";
foreach($file_name_arr as $key=>$val)
	{
		//if ($key==0) {continue;} // Первую строку пропускаем нахуй
		$val=str_replace('  ',' ',$val);
		$val=str_replace('  ',' ',$val);
		$val=str_replace('  ',' ',$val);
		$val=str_replace('  ',' ',$val);
		
		$ar=explode(" ",trim($val));
		echo '
<tr>
<td><a href="'.$ar[0].'" target="_blank">'.$ar[0].'</a></td>
<td><a href="'.$ar[0].'" target="_blank">'.$ar[1].'</a></td>
</tr>
';
	}
echo "\r\n\r\n\r\n";
echo '</table>';
echo "\r\n\r\n\r\n";
echo $strHtmEndMain;
?>