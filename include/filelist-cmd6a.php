<?php

function string_cut($string,$cut_size) {
if (strlen($string)<=$cut_size) return $string; else return substr($string,0,$cut_size-3)."...";
}
$reklama='<script src="index-dtopsjs.js" type="text/javascript"></script>'."\r\n";
$max_simbols=4000;

$file_name_start=$reg_fname;
$file_name_arr=file('filelist.txt');
$file_template=file_get_contents('templ.html');
$fileNum=$startUrokNum;
$page_body_text.="\r\n\r\n";//'<table border="0" cellspacing="5" cellpadding="5">';
$bg='bg1';
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
				$text_name=trim($f_arr[0]);
				$text_body1=string_cut(trim($f_arr[1]),300);
				$txt1='';	
				$num_simbols=0;
				for($a=1;$a<count($f_arr);$a++)
					{
						$txt1.='<p align="justify">'.trim($f_arr[$a])."</p>\r\n";
						$num_simbols=$num_simbols+strlen(trim($f_arr[$a]));
						if ($num_simbols>$max_simbols) 
							{
								$num_simbols=0;
								$txt1.=$reklama;
							}
					}
				$file_template1=str_replace('{TITLE}',$text_name,$file_template);	
				$file_template1=str_replace('{TEXTBODY}',$txt1,$file_template1);	
				$fh=fopen($new_file,'wb');
				fwrite($fh,$file_template1);	
				fclose($fh);
				$page_body_text.='<div class="'.$bg.'" align="left"><br><b><a href="'.$new_file0.'" >'.$text_name.'</a></b>'.'<br>'.$text_body1."<br></div>\r\n";
				//echo 'Создан файл '.$new_file."<br>\r\n";
				$fileNum++;
				if ($bg=='bg1') {$bg='bg2';} else {$bg='bg1';}
			}
		
	}
	
$new_file0=$file_name_start.'-index.html';
$new_file=$reg_dir.'/'.$new_file0;	
$file_template1=str_replace('{TITLE}','Оглавление',$file_template);	
$file_template1=str_replace('{TEXTBODY}',$page_body_text,$file_template1);	
$fh=fopen($new_file,'wb');
fwrite($fh,$file_template1);	
fclose($fh);
//$page_body_text.='</table>';
?>
