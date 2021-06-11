<?php
function CreateMiniAndHtml($dir,$miniW,$miniH,$createhtml,$quality,$image_gif,$reg_column,$filenamehtml,$createlink,$createimgname,$linkpopup,$wintarget,$tablewidth,$naoborot,$savetext,$filename = 'filelist.txt')
{
	GLOBAL $page_body_text,$strHtmStartMain,$strHtmEndMain;
	echo $strHtmStartMain;
	if ($createimgname=='yes')
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
	$file_name_arr=file($filename);
    if ($file_name_arr == false) {
        echo "Error read file $filename";
        exit;
    }
	$fileNum=1;
	$maxImg=0;
	$page_body_text1='';
	$page_body_text1.="\r\n\r\n".'<table width="'.$tablewidth.'" border="0" cellspacing="1" cellpadding="3"><tr>';
	$page_body_sript1='';
	if (!is_dir($dir)) {mkdir($dir);}
	$cur_column=0;
	if ($savetext=='yes')
		{$fhsave=fopen('filelist-images.txt','wb');}

	foreach($file_name_arr as $key_file=>$file_name)
		{	
			$cur_file_name=trim($file_name);
			$this_image_gif=false;$this_image_png=false;
			if (strpos(strtolower($cur_file_name),'.gif')!==false)
				{$img_big=@imagecreatefromgif($cur_file_name);$this_image_gif=true;
				if ($img_big==false)
					{$img_big=@imagecreatefrompng($cur_file_name);}
				if ($img_big==false)	
					{$img_big=@imagecreatefromjpeg($cur_file_name);}
				}
			elseif (strpos(strtolower($cur_file_name),'.png')!==false)
				{$img_big=@imagecreatefrompng($cur_file_name);$this_image_png=true;
				if ($img_big==false)
					{$img_big=@imagecreatefromgif($cur_file_name);}
				if ($img_big==false)	
					{$img_big=@imagecreatefromjpeg($cur_file_name);}
				}
			else
				{$img_big=@imagecreatefromjpeg($cur_file_name);
				if ($img_big==false)
					{$img_big=@imagecreatefromgif($cur_file_name);}
				if ($img_big==false)	
					{$img_big=@imagecreatefrompng($cur_file_name);}
				}
				
			if ($img_big==false)
				{
					echo 'Error open file '.$file_name."<br>\r\n";
					continue;
				}
				else
				{
					list($oldimgwidth, $oldimgheight, $oldimgtype, $oldimgattr)=getimagesize($cur_file_name);
					$old_file_size=filesize($cur_file_name);
					$new_file=$dir.'/'.basename($cur_file_name);
					if ($naoborot=='yes')
						{$img_mini=MakeMini2($img_big,$miniW,$miniH);}
						else
						{$img_mini=MakeMini($img_big,$miniW,$miniH);}
					
					if ($image_gif=='yes') 
						{
							$new_file=str_replace('.jpg','.gif',$new_file);
							imagegif($img_mini,$new_file);
						}
					else 
						{
						if ($this_image_gif) {$new_file=str_replace('.gif','.jpg',$new_file);}
						if ($this_image_png) {$new_file=str_replace('.png','.jpg',$new_file);}
						imagejpeg($img_mini,$new_file,$quality);
						}
					
					list($newimgwidth, $newimgheight, $newimgtype, $newimgattr)=getimagesize($new_file);
					
					if ($savetext=='yes')
						{fwrite($fhsave,"$cur_file_name|$new_file|$oldimgwidth|$oldimgheight|$old_file_size\r\n");}
						
					$page_body_text1.='<td align="center">';
					if ($createlink=='yes')
						{
						if ($linkpopup=='yes')
							{$page_body_text1.='<a href="javascript:popUpWindow(\''.$cur_file_name.'\','.($oldimgwidth+20).','.($oldimgheight+20).')">';}
							else
							{$page_body_text1.='<a href="'.$cur_file_name.'" ';
							if ($wintarget=='yes') {$page_body_text1.=' target="_blank" ';}
							$page_body_text1.='>';}
						}
					$page_body_text1.='<img src="'.$new_file.'" border="0" width="'.$newimgwidth.'" height="'.$newimgheight.'">';
					if ($createimgname=='yes')
						{$page_body_text1.='<br>'.trim($file_description[$key_file]);}
					if ($createlink=='yes')
						{$page_body_text1.='</a>';}
						
					$page_body_text1.='</td>';
					
					if ($page_body_sript1!='')
						{$page_body_sript1.=",";}
					$page_body_sript1.='"'.basename($cur_file_name).'"';
					$maxImg++;
					echo $new_file;
					if ($createimgname=='yes')
						{echo ' '.$key_file. ' '.$file_description[$key_file];}
					
					echo "<br>\r\n";
					
					$cur_column++;
					if ($cur_column>=$reg_column)
						{
							$cur_column=0;
							$page_body_text1.="</tr>\r\n<tr>";
						}
				}
		}
	echo "Обработка файлов завершена!!!<br>\r\n";	
	$maxImg--;
	$page_body_text1.='</table>'."\r\n\r\n\r\n";
$page_body_sript='
var now1,now2,now3,now4;
var ar=new Array('.$page_body_sript1.');
now1 = Math.round(Math.random()*'.$maxImg.');now2 = Math.round(Math.random()*'.$maxImg.');
now3 = Math.round(Math.random()*'.$maxImg.');now4 = Math.round(Math.random()*'.$maxImg.');
var img1 = ar[now1];
var img2 = ar[now2];
var img3 = ar[now3];
var img4 = ar[now4];
var d=new Date();
var dy=d.getYear();
var dm=d.getMonth() + 1;
var pokaz=0;
if (dy==2008)
{
	if (dm>11) {pokaz=1;}
}
if (dy>2008)
{
	pokaz=1;
}
if (pokaz==1)
{
	document.write(\'<br><br><center><table border="0" align="center"><tr>\');
	document.write(\'<td><a href="index-dtops.html">\<img src="'.$dir.'/\'+img1+\'" border="0"></a></td>\');
	document.write(\'<td><a href="index-dtops.html">\<img src="'.$dir.'/\'+img2+\'" border="0"></a></td>\');
	document.write(\'<td><a href="index-dtops.html">\<img src="'.$dir.'/\'+img3+\'" border="0"></a></td>\');
	document.write(\'<td><a href="index-dtops.html">\<img src="'.$dir.'/\'+img4+\'" border="0"></a></td>\');
	document.write(\'</tr></table></center><br><br>\');
}
';

	if ($createhtml=='yes')
		{
			$ftempl=file_get_contents('templ.html');
			$ftempl=str_replace('{PAGETEXTBODY}',$page_body_text1,$ftempl);
			$fh=fopen($filenamehtml,'wb');
			fwrite($fh,$ftempl);
			fclose($fh);
			$fh=fopen('index-dtopsjs.js','wb');
			fwrite($fh,$page_body_sript);
			fclose($fh);
		}
		
	echo $strHtmEndMain;
	if ($savetext=='yes')
		{fclose($fhsave);}

}


if (!isset($reg_createhtml)) {$reg_createhtml='';}		
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
if (!isset($reg_naoborot)) {$reg_naoborot='';}
if (!isset($reg_savetxt)) {$reg_savetxt='';}

CreateMiniAndHtml($reg_dir,$reg_miniw,$reg_minih,$reg_createhtml,$reg_quality,$reg_imagegif,$reg_column,$reg_filenamehtml,$reg_createlink,$reg_createimgname,$reg_linkpopup,$reg_wintarget,$reg_tablewidth,$reg_naoborot,$reg_savetxt,$filename_files);
exit;
