<?php
// Команда 18
	echo $strHtmStartMain;
	$dir_array=ReadFilesInDir($reg_dir);
	echo "\r\n\r\n\r\n".'<table border="0" cellspacing="1" cellpadding="3">';
	$count=1;
	$bgcolor='#fff8ff';
	foreach($dir_array as $file_name)
		{
			if ($bgcolor=='#fff8ff') {$bgcolor='#ffe8ff';} else {$bgcolor='#fff8ff';}
		
			$fc=file_get_contents($file_name);
			if ($fc==false) {echo 'Error open '.$file_name;}
			// Найти текст, заключенный в какой-то тег, например <TITLE> ... </TITLE> из HTML-файла 
			
			if (preg_match_all("!<h[1,2,3,4,5,6][^>]+>(.*?)</h[^>]+>!si",$fc,$ok)) 
				{
					echo '<tr style="background: '.$bgcolor.'"><td valign="top"><b><a href="'.basename($file_name).'"> Урок&nbsp;'.$count. '</a></b></td><td align="justify">';
					foreach($ok[1] as $val)
						{
							echo '<a href="'.basename($file_name).'" style="font-size:8pt">'.$val."</a>, ";
						}
					echo "<a href=\"".basename($file_name)."\">Перейти&nbsp;к&nbsp;уроку&nbsp;&gt;&gt;&gt;</a></td></tr>\r\n";
					$count++;
				}
			 else 
			 	{
				echo "$file_name Тег не найден <br>";
				}
		}
	echo '</table>'."\r\n\r\n\r\n";
	echo $strHtmEndMain;
	exit;
?>