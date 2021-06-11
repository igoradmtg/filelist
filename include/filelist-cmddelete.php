<?php
	$arrFile=ReturnAllFilesInDir($reg_dir,$reg_ext);
	if ($arrFile==false)
		{$page_body_text.='Ошибка чтения из каталога '.$reg_dir;}
		else
		{
			DeletaFileS($reg_dir,$arrFile);		
		}
?>
