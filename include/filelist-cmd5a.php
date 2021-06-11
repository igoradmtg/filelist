<?php
echo $strHtmStartMain;

if (!file_exists($reg_fname)) 
	{echo 'Не найден файл со списком '.$reg_fname;}
	else
		{
			$file_name_arr=file($reg_fname);	
			foreach($file_name_arr as $file_name)
				{
					$file_name_del=$reg_dir.'/'.trim($file_name);
					if (@unlink($file_name_del))	
						{echo 'Delete '.$file_name_del."<br>\r\n";}
						else
						{echo 'Error delete '.$file_name_del."<br>\r\n";}
				}
		}
echo $strHtmEndMain;
?>
