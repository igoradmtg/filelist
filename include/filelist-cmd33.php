<?php
include('functions_text.php');
if (!isset($reg_delfiles)) {$reg_delfiles='';}
echo $strHtmStartMain;
$file1=file('filelist.txt');
sort($file1);
$ar_md5=array();
foreach($file1 as $row)
	{
		$fname=trim($row);
		if (file_exists($fname))
			{
				$cur_md5=md5_file($fname);
				if (in_array($cur_md5,$ar_md5))
					{
						echo "Дублирован файл $fname $cur_md5 <br>\r\n";
						if ($reg_delfiles=='yes')
							{
								if (unlink($fname))
									{echo "Файл был удален $fname <br>\r\n";}
									else
									{echo "Ошибка удаления файла $fname <br>\r\n";}
							}
					}
					else
					{$ar_md5[]=$cur_md5;}
			}
	}
echo $strHtmEndMain;
?>