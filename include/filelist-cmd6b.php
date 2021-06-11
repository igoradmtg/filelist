<?php
		$file_name_arr=file('filelist.txt');
		$file_template=file_get_contents('templ.html');
		$fileNum=$startUrokNum;
		foreach($file_name_arr as $file_name)
			{	
				$f_arr=file(trim($file_name));
				if ($f_arr==false)
					{
						echo 'Error open file';
					}
					else
					{
						$new_file0=$file_name_start.AddZero($fileNum,2).'.html';
						$new_file=$reg_dir2.'/'.$new_file0;
						$text_name='Администрирование Windows XP';
						$txt1='';	
						$strStart=0;$strEnd=0;
						foreach($f_arr as $key=>$row)
							{
								if ((strpos($row,'<!-- TEXTBODYSTART -->')===0) && ($strStart==0))
									{
										$strStart=$key;
									}
								if (strpos($row,'<!-- TEXTBODYEND -->')===0)
									{
										$strEnd=$key;
									}
							}
						for($a=$strStart;$a<=$strEnd;$a++)
							{$txt1.=trim($f_arr[$a])."\r\n";}
						$file_template1=str_replace('{TITLE}',$text_name,$file_template);	
						$file_template1=str_replace('{TEXTBODY}',$txt1,$file_template1);	
						$fh=fopen($new_file,'wb');
						fwrite($fh,$file_template1);	
						fclose($fh);
						$page_body_text.='<p><a href="'.$new_file0.'" >'.$text_name.'</a>'."</p>\r\n";
						//echo 'Создан файл '.$new_file."<br>\r\n";
						$fileNum++;
					}
				
			}
?>
