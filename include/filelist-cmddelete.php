<?php
	$arrFile=ReturnAllFilesInDir($reg_dir,$reg_ext);
	if ($arrFile==false)
		{$page_body_text.='������ ������ �� �������� '.$reg_dir;}
		else
		{
			DeletaFileS($reg_dir,$arrFile);		
		}
?>
