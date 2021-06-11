<?php
	
	$arfile=ReadFilesInDir($reg_dir);
	echo $strHtmStartMain;
	echo '<table>';
	foreach($arfile as $file_name)
		{
			$file=file($file_name);
			echo '<tr><td>'.$file_name.'</td><td>'.$file[0].'</td>';
			echo '<td>'.$file[1].'</td>';
			echo '<td>'.$file[2].'</td>';
			echo '</tr>';
		}
	echo '</table>';
	echo $strHtmEndMain;
	exit;
?>