<?php
// Команда 20
	//echo $reg_dir;
	echo $strHtmStartMain;
	if (!is_dir($reg_dir2)) {mkdir($reg_dir2);}
	$file_name_save_desc='filelist2.txt';
	$desc_array=array();
	$fname_array=array();
	$count=1;
	$dir_array=ReturnAllFilesInDir($reg_dir,'',false,true);
	foreach($dir_array as $dirname)
		{
			echo "$dirname<br>\r\n";
			$file_array=ReturnAllFilesInDir($reg_dir.'/'.$dirname,'.jpg',false,false);
			foreach($file_array as $fname)
				{
					echo "$fname<br>\r\n";
					$new_fname=$reg_dir2.'/'.AddZero($count,8).'.jpg';
					if (@copy($reg_dir.'/'.$dirname.'/'.$fname,$new_fname))
						{
							echo "OK<br>\r\n";
							$fname_array[]=$new_fname;
							$desc_array[]=$dirname;
							$count++;
						}
					
				}
		}
	SaveArrayToFile($fname_array,'filelist.txt');	
	SaveArrayToFile($desc_array,'filelist2.txt');	
	
	echo $strHtmEndMain;
	exit;
?>