<?php
include('functions_text.php');
echo $strHtmStartMain;
$fh=fopen('filelist.txt','wb');
$dir_name_ar=ReturnAllFilesInDir($reg_dirname1,'',true,true);
foreach($dir_name_ar as $dir_name)
	{
		$dir_name=trim($dir_name);
		if (is_dir($dir_name))
			{
				echo '<b>'.$dir_name.'</b> ';
				$file_name_ar=ReturnAllFilesInDir($dir_name,'',true,false);
				foreach($file_name_ar as $file_name)
					{
						fwrite($fh,$file_name."\r\n");
					}
				echo 'files:'.count($file_name_ar).'<br>';
			}
	}
fclose($fh);
echo $strHtmEndMain;
?>