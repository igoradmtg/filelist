<?php
		$dirname='../1';
		$dir_ar=ReadFilesDirInDir($dirname);
		if ($dir_ar==false) {$page_body_text.='Ошибка чтения каталога '.$dirname;}
		else
			{foreach($dir_ar as $dir_name_cur)
				{$page_body_text.= 'Каталог '.$dir_name_cur.'<br>';
				$file_name_list=$dir_name_cur.'/'.'filelist.lst';
				if (file_exists($file_name_list)) 
					{
					
					$file_ar=file($file_name_list);
					foreach($file_ar as $row)
						{
							list($file_name,$file_size,$file_md5,$file_url)=explode('|',$row);
							$file_name_cur=$dir_name_cur.'/'.$file_name;
							$file_name_new=$dir_name_cur.'/'.str_replace('.mdt',GetFileExtFromUrl(trim($file_url),'.mdt'),$file_name);
							if (file_exists($file_name_cur)) {$page_body_text.=$file_name_cur.' - '.$file_name_new.'<br>';} else 
							{$page_body_text.='Не найден файл '.$file_name_cur.'<br>';}
						}
					}
					else
					{
						$page_body_text.='Не найден файл '.$file_name_list.'<br>';
					}
				}
			}
?>