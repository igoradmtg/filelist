<?php
	function GetMinMaxPage($maxPages,$curPage,$numPages) // Получить в виде массива строк номера страниц
	{
		$retArr=array();
		$simbol1='';
		$simbol2='';
		$minPage=$curPage-$numPages;
		$maxPage=$curPage+$numPages;
		if ($minPage<0) {$minPage=0;} else {$simbol1='&lt;&lt;';}
		if ($maxPage>$maxPages) {$maxPage=$maxPages;} else {$simbol2='&gt;&gt;';}
		for($a=$minPage;$a<=$maxPage;$a++)
			{
			if (($a==$minPage) && ($simbol1!='')) {$retArr[$a]=$simbol1;}
			elseif (($a==$maxPage) && ($simbol2!='')) {$retArr[$a]=$simbol2;}
			else
				{$retArr[$a]=strval($a+1);}
			}
		return $retArr;
	}

	echo $strHtmStartMain;
	if (!isset($reg_imagegif)) {$reg_imagegif='';}		
	if (!isset($reg_filenamehtml)) {$reg_filenamehtml='index-dtops.html';}
	if (!isset($reg_miniw)) {$reg_miniw=160;}
	if (!isset($reg_minih)) {$reg_minih=120;}
	if (!isset($reg_quality)) {$reg_quality=85;} else {$reg_quality=intval($reg_quality);}
	if (!isset($reg_column)) {$reg_column=5;} else {$reg_column=intval($reg_column);}
	if (!isset($reg_createlink)) {$reg_createlink='';}
	if (!isset($reg_createimgname)) {$reg_createimgname='';}
	if (!isset($reg_linkpopup)) {$reg_linkpopup='';}
	if (!isset($reg_wintarget)) {$reg_wintarget='';}
	if (!isset($reg_numpiconpage)) {$reg_numpiconpage=20;} // Количество миниатюр на одной странице
	if (!isset($reg_createimgsize)) {$reg_createimgsize='';}
	if ($reg_createimgname=='yes')
		{
		if (file_exists('filelist2.txt'))	
			{
			$file_description=file('filelist2.txt');
			if ($file_description==false)
				{echo 'Ошибка открытия файла с описаниями фото'; exit;}
			}
		else
			{echo 'Не найден файл с описаниями фото filelist2.txt';exit;}
		}
	$file_name_arr=file('filelist.txt');
	$dir_to_save_files='output';
	if (!is_dir($dir_to_save_files)) {mkdir($dir_to_save_files);}

	if (!is_dir($dir_to_save_files.'/'.$reg_dir)) {mkdir($dir_to_save_files.'/'.$reg_dir);}
	
	$maxRow=count($file_name_arr);
	$maxPage1=$reg_numpiconpage; 
	$maxUrl=10;
	$maxPages=intval($maxRow / $maxPage1); // Получаем максимальное количество страниц
	echo "Количество страниц:$maxPages Количество миниатюр на одной странице:$maxPage1 <br>\r\n";
	for($a=0;$a<=$maxPages;$a++)
		{
			
			$cur_column=0;
			$page_body_text1='';
			$curPage=$a;
			
			$linksPages='';
			$arrPages=GetMinMaxPage($maxPages,$curPage,$maxUrl);
			$name_file_to_save=str_replace('{NN}',($curPage+1),$reg_filenamehtml);
		
			foreach($arrPages as $key=>$numPage)
				{
				if ($curPage!=$key)
					{
						$cur_url=str_replace('{NN}',strval($key+1),$reg_filenamehtml);
						$linksPages.=' <a href="'.$cur_url.'" class="lnk1"> '.$numPage.' </a> ';
					}
					else
					{$linksPages.=' <span class="lnk2"> '.$numPage.' </span> ';}
				}
			$page_body_text1.=$linksPages."<br><br>\r\n";
			
			$page_body_text1.="\r\n\r\n".'<table ';
			if ($tablewidth!='') {$page_body_text1.='width="'.$tablewidth;}
			$page_body_text1.=' border="0" cellspacing="1" cellpadding="3"><tr>';
				
			for($b=0;$b<$maxPage1;$b++)
				{	
					$key_file=$a*$maxPage1+$b;
					echo "a=$a b=$b key=$key_file ";
					if ($key_file>=$maxRow) {continue;}
					$file_name=$file_name_arr[$key_file];
					$cur_file_name=trim($file_name);
					$img_big=@imagecreatefromjpeg($cur_file_name);
					if ($img_big==false)
						{
							echo 'Error open file '.$file_name."<br>\r\n";
							continue;
						}
						else
						{
							list($oldimgwidth, $oldimgheight, $oldimgtype, $oldimgattr)=getimagesize($cur_file_name);
							$new_file=$reg_dir.'/'.'tn'.basename($cur_file_name);
							$img_mini=MakeMini($img_big,$reg_miniw,$reg_minih);
							if ($reg_imagegif=='yes') 
								{
									$new_file=str_replace('.jpg','.gif',$new_file);
									imagegif($img_mini,$dir_to_save_files.'/'.$new_file);
								}
							else {imagejpeg($img_mini,$dir_to_save_files.'/'.$new_file,$reg_quality);}
							
							list($newimgwidth, $newimgheight, $newimgtype, $newimgattr)=getimagesize($dir_to_save_files.'/'.$new_file);
							
							$page_body_text1.='<td align="center">';
							if ($reg_createlink=='yes')
								{
								if ($reg_linkpopup=='yes')
									{$page_body_text1.='<a href="javascript:popUpWindow(\''.$cur_file_name.'\','.($oldimgwidth+20).','.($oldimgheight+20).')">';}
									else
									{$page_body_text1.='<a href="'.$cur_file_name.'" ';
									if ($reg_wintarget=='yes') {$page_body_text1.=' target="_blank" ';}
									$page_body_text1.='>';}
								}
							$page_body_text1.='<img src="'.$new_file.'" border="0" width="'.$newimgwidth.'" height="'.$newimgheight.'">';
							if ($reg_createimgsize=='yes')
								{$page_body_text1.='<br>'.$oldimgwidth.'x'.$oldimgheight;}
								
							if ($reg_createimgname=='yes')
								{$page_body_text1.='<br>'.trim($file_description[$key_file]);}
								
							
							if ($reg_createlink=='yes')
								{$page_body_text1.='</a>';}
								
							$page_body_text1.='</td>'."\r\n";
							
							if ($page_body_sript1!='')
								{$page_body_sript1.=",";}
							$page_body_sript1.='"'.basename($cur_file_name).'"';
							echo $new_file;
							if ($reg_createimgname=='yes')
								{echo ' '.$key_file. ' '.$file_description[$key_file];}
							
							echo "<br>\r\n";
							$cur_column++;
							if ($cur_column>=$reg_column)
								{
									$cur_column=0;
									$page_body_text1.="</tr>\r\n<tr>\r\n";
								}
						}
				}
			$page_body_text1.='</table>'."<br>".$linksPages."<br><br>\r\n\r\n\r\n";
			$ftempl=file_get_contents('templ.html');
			$ftempl=str_replace('{TEXTBODY}',$page_body_text1,$ftempl);
			$ftempl=str_replace('{TITLE}',$reg_ptitle,$ftempl);
			$fh=fopen($dir_to_save_files.'/'.$name_file_to_save,'wb');
			fwrite($fh,$ftempl);
			fclose($fh);
		}
	echo $strHtmEndMain;
	exit;
?>