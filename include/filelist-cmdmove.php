<?php
	$arrFile=ReturnAllFilesInDir($reg_dir,$reg_ext);
	if ($arrFile==false)
		{$page_body_text.='������ ������ �� �������� '.$reg_dir;}
		else
		{
		if (is_dir($reg_dir2)==false)
			{
				if (@mkdir($reg_dir2)==false)
					{$page_body_text.= '������ �������� �������� '.$reg_dir2;}
			}
		if (is_dir($reg_dir2))
			{
				RenameFilesArray($reg_dir,$reg_dir2,$arrFile);
			}
		}
?>