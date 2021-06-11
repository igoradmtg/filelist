<?php
	$arrDir=ReturnAllDirFilesInDir($reg_dir);
	if ($arrDir==false)
		{$page_body_text.='Ошибка чтения из каталога '.$reg_dir;}
		else
		{
		foreach ($arrDir as $dirname)
			{
				$arrFile=ReturnAllFilesInDir($reg_dir.'/'.$dirname,$reg_ext);
				if ($arrFile==false)
					{
					$page_body_text.= 'Error open dir '.$reg_dir.'/'.$dirname;
					}
					else
					{
						foreach ($arrFile as $filename)
							{
								$getsize=@getimagesize($reg_dir.'/'.$dirname.'/'.$filename);
								if ($getsize!=false)
									{
										rename($reg_dir.'/'.$dirname.'/'.$filename,$reg_dir.'/'.$dirname.'/'.str_replace('.mdt','.jpg',$filename));
										$page_body_text.= 'ren '.$filename.'<br>';
									}
									else
									{
										$page_body_text.= 'not ren '.$filename.'<br>';
									}
							}
					}
			}
		}
?>
