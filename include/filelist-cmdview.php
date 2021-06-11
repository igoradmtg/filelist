<?php
	$arrFile=ReturnAllFilesInDir($reg_dir,$reg_ext);
	if ($arrFile==false)
		{$page_body_text.= 'Error open dir '.$reg_dir;}
		else
		{
		foreach ($arrFile as $filename)
			{$page_body_text.=$filename."<br>\r\n";}
		}
?>