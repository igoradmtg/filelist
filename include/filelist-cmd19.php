<?php
// Команда 19
	echo $strHtmStartMain;
	$dir_array=ReturnAllFilesInDir($reg_dir,'',false,true);
	$cmd_line='d:\"Program Files"\7-Zip\7z.exe a -tzip c:\doc.zip c:\1.txt';
/*	$output='';
	exec($cmd_line,$output);
	foreach ($output as $row)
		{
			if (trim($row)=='') {continue;}
			echo $row."<br>\r\n";
		}
	exit;*/
	foreach($dir_array as $dirname)
		{
			//echo "$fname<br>\r\n";
				
			$zip_array=ReturnAllFilesInDir($reg_dir.'/'.$dirname,'.zip',false);
			if (count($zip_array)>0)
				{
					$dname0='l:/1/'.$dirname;
					if (@mkdir($dname0))
						{echo "Make dir $dname0 <br>\r\n";}
						else
						{echo "Error! Make dir $dname0 <br>\r\n";}
				}
			foreach($zip_array as $zname)
				{
					echo "$zname<br>\r\n";
					$dname='l:/1/'.$dirname.'/'.str_replace('.zip','',$zname);
					if (@mkdir($dname))
						{echo "Make dir $dname <br>\r\n";}
						else
						{echo "Error! Make dir $dname <br>\r\n";}
					$cmd_line='d:\"Program Files"\7-Zip\7z.exe x '.$reg_dir.'/"'.$dirname.'"/'.$zname.' -o"'.$dname.'"';
					$output='';
					exec($cmd_line,$output);
					echo "$cmd_line<br>\r\n";
					foreach ($output as $row)
						{
							if (trim($row)=='') {continue;}
							echo $row."<br>\r\n";
						}
				}
		}
		
	echo $strHtmEndMain;
	exit;
?>