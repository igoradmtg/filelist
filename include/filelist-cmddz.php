<?php
	$arrDir=ReturnAllFilesInDir($reg_dir,'.dz');
	if ($arrDir==false)
		{$page_body_text.='Ошибка чтения из каталога '.$reg_dir;}
		else
		{
			DecodeDzFiles($reg_dir,$arrDir);
		}
?>
