<?php
// Команда 16 Создать каталоги и перенести файлы с фотками
// Поле ввода text|dir|data2 Каталог c файлами и описаниями файлов:
// Поле ввода text|dir2|data3 Каталог c файлами и описаниями файлов:
	include('functions_text.php');
	$num_char=3;
	echo $strHtmStartMain;
	$dir_array=ReadFilesInDir($reg_dir);
	if (!is_dir($reg_dir2)) {mkdir($reg_dir2);}
	for($a=1;$a<500;$a++)
		{
			$name=$reg_dir.'/'.AddZero($a,$num_char).'readme.txt';
			
			if (file_exists($name))
				{
					$file_text=file_get_contents($name);
					$catalog_name=str_replace(array('"','/'),' ',$file_text);
					if (@mkdir($reg_dir2.'/'.$catalog_name)) 
						{
							echo "Cоздан каталог $catalog_name <br>\r\n";
							@rename($reg_dir.'/'.AddZero($a,$num_char).'page.html',$reg_dir2.'/'.$catalog_name.'/index.html');
							for($b=1;$b<20;$b++)
								{
									@rename($reg_dir.'/'.AddZero($a,$num_char).'img'.AddZero($b,2).'.jpg',$reg_dir2.'/'.$catalog_name.'/'.AddZero($a,2).'img'.AddZero($b,2).'.jpg');
								}
						} else 
						{
							echo "Ошибка создания каталога $catalog_name <br>\r\n";
						}
					@unlink($name);
				}
				else
				{
					echo "Не найден файл $name <br>\r\n";
					exit;
				}
		}
	echo $strHtmEndMain;
	exit;
?>