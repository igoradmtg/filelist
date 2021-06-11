<?php
include('functions_text.php');
function ReadFilesInDirTmp($dirname)
{
	$array_ret=array();
	if (is_dir($dirname))
		{
			if ($dh = opendir($dirname)) 
			{
				while (($file = readdir($dh)) !== false) 
				{
					if (($file=='.') || ($file=='..')) {continue;}
					if (is_file($dirname.'/'.$file))
						{$array_ret[]=$file;}
					//echo $file;
				}
				
			}
			else
			{echo 'Не могу открыть';return false;}
						
		}
		else {echo 'Не каталог';return false;}
	return $array_ret;	
}

echo $strHtmStartMain;
if (!isset($reg_dir1)) {echo 'Не указан каталог';exit;}
if (!isset($reg_dir2)) {echo 'Не указан каталог';exit;}
if (!is_dir($reg_dir2))
	{
		if (mkdir($reg_dir2)==false)
			{echo 'Ошибка при создании каталога '.$reg_dir2;exit;}
	}
$str_title=$reg_title;	
$f_ar=ReadFilesInDirTmp($reg_dir1);
if ($f_ar==false) {echo ' Ошибка чтения каталога '.$reg_dir1;exit;}
$array_name_dir=array();
foreach($f_ar as $fname)
	{
		$fbody=file($reg_dir1.'/'.$fname);
		if ($fbody==false)
			{echo 'Ошибка при чтении файла '.$fname;exit;}
		// Искать в начале эти строки	
		// <index></td>
		// <td width="64%" valign="top">
		$f1='<index></td>';
		$f2='<td width="64%" valign="top">';
		$f3='</index>';
		$f4='<!-- my-sape --><br>';
		$f5='<br><!-- my-sape -->';
		$s1=0;
		$s2=0;
		$s3=0;
		$s4=0;
		for($a=0;$a<count($fbody);$a++)
			{
				$row=trim($fbody[$a]);
				if (strpos($row,$f1)===0)
					{$s1=$a;}
				if (($s1>0)	&& (strpos($row,$f2)===0))
					{$s1=$a+1;}
				if (strpos($row,$f3)===0)	
					{$s2=$a-1;}
				if (strpos($row,$f4)===0) {$s3=$a;}
				if (strpos($row,$f5)===0) {$s4=$a;}
			}
		echo "Файл $fname $s1 $s2 <br>";
		if (($s1==0) || ($s2==0))
			{continue;}
		$str_body='';
		//filelist-cmd31-templ.html
		for($a=$s1;$a<=$s2;$a++)
			{
				if (($a>=$s3) && ($a<=$s4)) {continue;}
				$str_body.=trim($fbody[$a])."\r\n";
			}	
		$template_html=file_get_contents('include/filelist-cmd31-templ.html');	
		$template_html=str_replace('{TITLE}',$str_title,$template_html);
		$template_html=str_replace('{TEXTBODY}',$str_body,$template_html);
		$fh=fopen($reg_dir2.'/'.$fname,'wb');
		fwrite($fh,$template_html);
		fclose($fh);
	}
	
echo $strHtmEndMain;
?>