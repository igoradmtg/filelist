<?php
		$file_name_start=$reg_file_name_start;
		$title1 = $reg_title1;
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
						$new_file=$reg_dir.'/'.$new_file0;
						
						$strStart=0;$strEnd=0;
						foreach($f_arr as $key=>$row)
							{
								if ((strpos($row,'<h3')===0) && ($strStart==0))
									{
										$strStart=$key;
									}
								if ($strStart>0)	
									{
										if (strpos($row,'<!-- REKLAMA -->')===0)
											{
												$strEnd=$key-1;
											}
									}
							}
						$txt1='';	
						if (($strEnd==0) || ($strStart==0))
							{
								echo 'Ошибка в файле '.$file_name;
								exit;
							}
						for($a=$strStart;$a<=$strEnd;$a++)
							{$txt1.=$f_arr[$a];}
						$title1a=str_replace('{NOMERUROKA}',$fileNum,$title1);
						$file_template1=str_replace('{TITLE}',$title1a,$file_template);	
						$file_template1=str_replace('{TEXTBODY}',$txt1,$file_template1);	
						$fh=fopen($new_file,'wb');
						fwrite($fh,$file_template1);	
						fclose($fh);
						$page_body_text.='<tr><td class="td1"><a href="'.$new_file0.'" target="mainFrame">Урок '.$fileNum.'</a></td></tr>'."\r\n";
						//echo 'Создан файл '.$new_file."<br>\r\n";
						$fileNum++;
					}
				
			}
?>
