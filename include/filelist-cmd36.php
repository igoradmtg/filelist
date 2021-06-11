<?php
require('include/functions_text.php');
define('IS_FIND_TAG_H3',true); // Выполнять поиск до первого вхождение <H3>
define('IS_DELETE_REKLAM',true); // Удалять рекламный текст в тегах <!-- REKLAMA IMG  START--> <!-- REKLAMA IMG  END-->
define('IS_DELETE_REKLAM2',true); // Удалять рекламный текст после тега <!-- REKLAMA -->
define('IS_DELETE_DIV_LECTURE',true); // Удалять тег <div  class="lecture_mark" id="mark_69"></div>
define('IS_FIND_KEY_WORDS',true); // Искать слова для добавления
define('IS_ADD_TEMPLATE',true); // Добавить текст в код templ.html
define('IS_SAVE_INDEX_LINK',true); // Записывайте файл index.html
define('TABLE_BG_COLOR1','#ffe8ff');
define('TABLE_BG_COLOR2','#fff8ff');
define('FILE_INDEX_LINKS','index-links.html'); // Имя файла с сылками на другие сайты 
if (!isset($reg_dirname1)) {echo 'Не задан параметр dirname1';exit;} 
if (!isset($reg_dirname2)) {echo 'Не задан параметр dirname2';exit;} 
if (!is_dir($reg_dirname1)) {echo 'Не найден каталог '.$reg_dirname1;exit;}
if (!is_dir($reg_dirname2))
	{
		// Попытка создать каталог если он не существует
		if (mkdir($reg_dirname2)==false) {echo 'Ошибка при создании каталога '.$reg_dirname2;exit;}
	}
$fname_param=$reg_dirname1.'/param.txt';
if (file_exists($fname_param))	
	{
		$param=parse_ini_file($fname_param);
		if ($param==false)
			{
				echo 'Ошибка при чтении параметров из файла с параметрами '.$fname_param;
				exit;
			}
		$main_page_title=$param['title'];
		$main_page_descr=$param['description'];
		$main_page_keyword=$param['keyword'];
		echo 'Прочитан файл с параметрами '.$fname_param.'<br>';
		echo 'title = '.$main_page_title.'<br>';
		echo 'descr = '.$main_page_descr.'<br>';
		echo 'keyword = '.$main_page_keyword.'<br>';
	}
	else
	{
		echo 'Не найден файл с параметрами '.$fname_param;
		exit;
	}

// Читаем ссылки из файла с нижними ссылками внизу страницы
$fname_downlink=$reg_dirname1.'/downlink.txt';	
if (file_exists($fname_downlink))	
	{
		$ar_downlink=file($fname_downlink);
		if ($ar_downlink==false)
			{
				echo 'Не найден файл с нижними ссылками '.$fname_downlink;
				exit;
			}
		echo 'Прочитан файл с нижними ссылками '.$fname_downlink.'<br>';
	}
	else
	{
		echo 'Не найден файл с нижними ссылками '.$fname_downlink;
		exit;
	}

// Читаем ссылки из файла со страницами для скачивания книг
$fname_booklink=$reg_dirname1.'/booklink.txt';	
if (file_exists($fname_booklink))	
	{
		$ar_booklink=file($fname_booklink);
		if ($ar_booklink==false)
			{
				echo 'Не найден файл с ссылками '.$fname_booklink;
				exit;
			}
		echo 'Прочитан файл с ссылками '.$fname_booklink.'<br>';
	}
	else
	{
		echo 'Не найден файл с ссылками '.$fname_booklink;
		exit;
	}

if (IS_ADD_TEMPLATE)
	{
		$text_templ=file_get_contents('templ.html');
		if ($text_templ==false)
			{echo 'Ошибка при чтении файла с шаблоном текста templ.html';exit;}		
	}

echo $strHtmStartMain;
function get_down_links($ar_downlink,$is_shuffle=true)
{
	$retstr='';
	if ($is_shuffle) {shuffle($ar_downlink);}
	foreach($ar_downlink as $key=>$dlink)
		{
			$dlink=trim($dlink);
			if (strlen($dlink)==0) {continue;}
			if ($retstr!='') {$retstr.=' &#8226; ';}
			$retstr.=$dlink;
		}
	return $retstr;
}

function get_book_links($ar_booklink,$num_link=4,$is_shuffle=true)
{
	$retstr='<table width="100" border="0" cellspacing="1" cellpadding="3" align="center"><tr valign="top">';
	if ($is_shuffle) {shuffle($ar_booklink);}
	$count=0;
	foreach($ar_booklink as $key=>$booklink)
		{
			$booklink=trim($booklink);
			if (strlen($booklink)==0) {continue;}
			$count++;
			if ($count>$num_link) {continue;}
			$retstr.='<td>'.$booklink.'</td>';
		}
	$retstr.='</tr></table>';
	return $retstr;
}

function get_link_from_array($file_text_ar,$is_random=false)
{
	$link='<table width="90%" border="0" cellpadding="5" cellspacing="0">'."\r\n";
	if ($is_random) {shuffle($file_text_ar);}
	foreach($file_text_ar as $file_text2)
		{
			$url=$file_text2['name2base'];$text=$file_text2['title'];
			$link.='<tr><td><a href="'.$url.'" class="a2">'.$text.'</a></td></tr>'."\r\n";
		}
	$link.='</table>';
	return $link;
}

function get_link_right_from_array($file_text_ar,$is_random=false,$is_random2=false)
{
	$link='<table width="90%" border="0" cellpadding="5" cellspacing="0">'."\r\n";
	if ($is_random) {shuffle($file_text_ar);}
	foreach($file_text_ar as $file_text2)
		{
			$url=$file_text2['name2base'];
			if (is_array($file_text2['allheadersar']))
				{
					$tmp_ar=$file_text2['allheadersar'];
					if ($is_random2) {shuffle($tmp_ar);}
					$text=$tmp_ar[0];
				}
				else {$text=$file_text2['title'];}
			$link.='<tr><td><a href="'.$url.'" class="a4">'.$text.'</a></td></tr>'."\r\n";
		}
	$link.='</table>';
	return $link;
}


$file_name_arr=ReturnAllFilesInDir($reg_dirname1,'.html',true,false);
if ($file_name_arr==false)	{echo 'Ошибка при чтении каталога '.$reg_dirname1;exit;}
$file_text_ar=array();
foreach($file_name_arr as $fkey=>$fname)
	{
		if (basename($fname)==FILE_INDEX_LINKS) {continue;}
		$file_text=file_get_contents($fname);
		// Вытаскиваем только тело		
		$pattern='/(?ims)<body[^>]*>(.*)<\/body>/U';
		$num_find=preg_match($pattern, $file_text, $matches); 
		if ($num_find==0) 
			{
				echo 'На найдено совпадений по шаблону '.$fname;
				exit;
			}
		$file_text=$matches[1];
		
		$fname2=$reg_dirname2.'/'.basename($fname);
		$fname2base=basename($fname);
		echo 'Прочитан файл: '.$fname.'<br>';
		if (IS_FIND_TAG_H3)
			{
				// Поиск первого вхождения h3
				$pattern = '/(?im)<h3[^>]*>/U'; 
				preg_match($pattern, $file_text, $matches, PREG_OFFSET_CAPTURE); 
				$str_pos_tag_h3=0; // Сохраняем номер позиции тега h3
				foreach($matches[0] as $key=>$val)
					{
						//echo $key.' '.htmlentities($val,ENT_QUOTES,'cp1251').'<br>';
						if ($key==1) {$str_pos_tag_h3=intval($val);}
					}
				//print_r($matches);echo '</pre>';
				$file_text=substr($file_text,$str_pos_tag_h3);
			}
		if (IS_DELETE_REKLAM)
			{
				// Замена по шаблону
				$pattern = '/(?ims)<!-- REKLAMA IMG  START-->(.*)<!-- REKLAMA IMG  END-->/U'; 
				$file_text=preg_replace($pattern,'',$file_text);
			}	
		if (IS_DELETE_REKLAM2)
			{
				// Поиск тега <!-- REKLAMA -->
				$pos=strrpos($file_text,'<!-- REKLAMA -->');
				if ($pos!==false)
					{
						$file_text=substr($file_text,0,$pos);
					}
			}
		if (IS_DELETE_DIV_LECTURE)
			{
				// Замена по шаблону
				$pattern = '/(?ims)<div +class="lecture_mark" +id="mark_[0-9]+"><\/div>/U'; 
				$file_text=preg_replace($pattern,'',$file_text);
			}
		$text_title='';
		$text_keyword=$main_page_keyword;
		$text_description=$main_page_descr;
		$text_all_headers=''; // Содержит все заголовки для сохранения на главной странице
		$text_all_headers_ar=array();
		if (IS_FIND_KEY_WORDS)
			{
				// Поиск текста в тегах <h3>
				$pattern = '/(?ims)<h3[^>]*>(.*)<\/h3>/U'; 
				preg_match_all($pattern, $file_text, $matches, PREG_PATTERN_ORDER );
				mb_internal_encoding("CP-1251"); 
				foreach($matches[1] as $key=>$val)
					{
						echo $key.' '.htmlentities($val,ENT_QUOTES,'cp1251').'<br>';
						//if ($key==1) {$str_pos_tag_h3=intval($val);}
						if ($key<=1) 
							{
								$text_title.=$val.'. ';
							} // Первый и второй заголовок добавляем в title
						$val=trim($val);
						if (strlen($text_description)<250)
							{
								if ($text_description!='') {$text_description.='. ';}
								$text_description.=$val;
							}	
						$val_small=mb_strtolower($val);
						$text_keyword.=tag_replace($val_small,0,4).' ';
					}
				$text_keyword_ar=split(' ',$text_keyword);
				$text_keyword='';
				shuffle($text_keyword_ar);
				foreach($text_keyword_ar as $word)
					{
						$word=trim($word);
						if ($word=='') {continue;}
						if ($text_keyword!='') {$text_keyword.=', ';}
						$text_keyword.=$word;
					}
				$text_keyword=trim($text_keyword);
				// Поиск текста в тегах <h1> <h2> <h3> <h4> <h5> <h6>
				$pattern = '/(?ims)<h[1-6][^>]*>(.*)<\/h[1-6]>/U'; 
				preg_match_all($pattern, $file_text, $matches, PREG_PATTERN_ORDER );
				mb_internal_encoding("CP-1251"); 
				foreach($matches[1] as $key=>$val)
					{
						$val=strip_tags2($val);
						$val=str_replace('&nbsp;',' ',$val);
						echo $key.' '.htmlentities($val,ENT_QUOTES,'cp1251').'<br>';
						if ($text_all_headers!='') {$text_all_headers.=', ';}
						$val=trim($val);
						$text_all_headers_ar[]=$val;
						$text_all_headers.=$val;
					}
			}
		echo "$fname2 <br>";
		$file_text_ar[$fkey]=array(
		'name2'=>$fname2,
		'name2base'=>$fname2base,
		'text'=>$file_text,
		'title'=>$text_title,
		'keyword'=>$text_keyword,
		'description'=>$text_description,
		'allheaders'=>$text_all_headers,
		'allheadersar'=>$text_all_headers_ar
		);
		unset($text_all_headers_ar);
	}

// Записываем все данные в файлы
foreach($file_name_arr as $fkey=>$fname)
	{
		if (basename($fname)==FILE_INDEX_LINKS) {continue;}
		$file_text=$file_text_ar[$fkey]['text'];
		$prev_link='';$next_link='';
		if (isset($file_text_ar[$fkey-1]))
			{
				$prev_link=' <a href="'.$file_text_ar[$fkey-1]['name2base'].'" class="a3">&lt;&lt;&nbsp;Предыдущий&nbsp;урок</a> ';
			}
		if (isset($file_text_ar[$fkey+1]))
			{
				$next_link=' <a href="'.$file_text_ar[$fkey+1]['name2base'].'" class="a3">Следующий&nbsp;урок&nbsp;&gt;&gt;</a> ';
			}
		$file_text.='<div style="padding-top: 3px;padding-right: 3px;	padding-bottom: 3px;padding-left: 3px;" align="center">'.$prev_link.$next_link.'</div>';
		$text_title=$file_text_ar[$fkey]['title'];
		$text_keyword=$file_text_ar[$fkey]['keyword'];
		$text_description=$file_text_ar[$fkey]['description'];
		$fname2=$file_text_ar[$fkey]['name2'];
		$text_left_links=get_link_from_array($file_text_ar,true);
		$text_right_links=get_link_right_from_array($file_text_ar,true,true);
		$text_down_links=get_down_links($ar_downlink);
		$text_book_links1=get_book_links($ar_booklink,4);
		$text_book_links2=get_book_links($ar_booklink,4);
		if (IS_ADD_TEMPLATE)
			{
				$text_templ2=str_replace('{TEXTBODY}',$file_text,$text_templ);
				$text_templ2=str_replace('{TITLE}',$text_title,$text_templ2);
				$text_templ2=str_replace('{PAGEDESCRIPTION}',$text_description,$text_templ2);
				$text_templ2=str_replace('{PAGEKEYWORD}',$text_keyword,$text_templ2);
				$text_templ2=str_replace('{LEFTLINKS}',$text_left_links,$text_templ2);
				$text_templ2=str_replace('{RIGHTLINKS}',$text_right_links,$text_templ2);
				$text_templ2=str_replace('{TITLEH1}',$main_page_title,$text_templ2);
				$text_templ2=str_replace('{DOWNLINK}',$text_down_links,$text_templ2);
				$text_templ2=str_replace('{BOOKSLINK1}',$text_book_links1,$text_templ2);
				$text_templ2=str_replace('{BOOKSLINK2}',$text_book_links2,$text_templ2);
				$file_text=$text_templ2;
			}
		$fh=fopen($fname2,'wb');
		if ($fh==false) {echo 'Ошибка при записи файла '.$fname2.'<br>';exit;}
		fwrite($fh,$file_text);
		fclose($fh);
		echo "Записан файл $fname2 <br>";
	}

if (IS_SAVE_INDEX_LINK)
	{
		// Записываем файл index.html
		$fname2=$reg_dirname2.'/index.html';
		$file_text='<div align="center"><a href="index.html"><img src="images/main-image.jpg" border="0" alt="'.$main_page_title.'"/></a></div><table border="0" cellspacing="1" cellpadding="3">';
		$num_urok=1;$table_bg_color=TABLE_BG_COLOR1;
		foreach($file_name_arr as $fkey=>$fname)
			{
				if (basename($fname)==FILE_INDEX_LINKS) {continue;}
				$text_all_headers=$file_text_ar[$fkey]['allheaders'];
				$fname2base=$file_text_ar[$fkey]['name2base'];
				$file_text.='<tr style="background: '.$table_bg_color.'" valign="top">
<td><p><b><a href="'.$fname2base.'"> Урок&nbsp;'.$num_urok.'</a></b></p></td>
<td><p align="justify">'.$text_all_headers.' <a href="'.$fname2base.'" style="font-size:8pt">Перейти&nbsp;к&nbsp;уроку&nbsp;&gt;&gt;&gt;</a></p></td>
</tr>
';

				$num_urok++;
				if ($table_bg_color==TABLE_BG_COLOR1) {$table_bg_color=TABLE_BG_COLOR2;} else {$table_bg_color=TABLE_BG_COLOR1;} 
			}
		$file_text.='</table>';
		$text_description=$main_page_descr;$text_keyword=$main_page_keyword;$text_title=$main_page_title;
		$text_left_links=get_link_from_array($file_text_ar,true);
		$text_right_links=get_link_right_from_array($file_text_ar,true,true);
		$text_down_links=get_down_links($ar_downlink);
		$text_book_links1=get_book_links($ar_booklink,4);
		$text_book_links2=get_book_links($ar_booklink,4);
		if (IS_ADD_TEMPLATE)
			{
				$text_templ2=str_replace('{TEXTBODY}',$file_text,$text_templ);
				$text_templ2=str_replace('{TITLE}',$text_title,$text_templ2);
				$text_templ2=str_replace('{PAGEDESCRIPTION}',$text_description,$text_templ2);
				$text_templ2=str_replace('{PAGEKEYWORD}',$text_keyword,$text_templ2);
				$text_templ2=str_replace('{LEFTLINKS}',$text_left_links,$text_templ2);
				$text_templ2=str_replace('{RIGHTLINKS}',$text_right_links,$text_templ2);
				$text_templ2=str_replace('{TITLEH1}',$main_page_title,$text_templ2);
				$text_templ2=str_replace('{DOWNLINK}',$text_down_links,$text_templ2);
				$text_templ2=str_replace('{BOOKSLINK1}',$text_book_links1,$text_templ2);
				$text_templ2=str_replace('{BOOKSLINK2}',$text_book_links2,$text_templ2);
				$file_text=$text_templ2;
			}
		$fh=fopen($fname2,'wb');
		if ($fh==false) {echo 'Ошибка при записи файла '.$fname2.'<br>';exit;}
		fwrite($fh,$file_text);
		fclose($fh);
		echo "Записан файл $fname2 <br>";
		// Записываем файл index-links.html
		$fname_index_links=$reg_dirname1.'/'.FILE_INDEX_LINKS;
		$fname2=$reg_dirname2.'/'.FILE_INDEX_LINKS;
		$text_down_links=get_down_links($ar_downlink);
		$text_book_links1=get_book_links($ar_booklink,4);
		$text_book_links2=get_book_links($ar_booklink,4);
		if (file_exists($fname_index_links))
			{
				$file_text=file_get_contents($fname_index_links);
				$text_description='';$text_keyword='';$text_title='';
				$text_left_links=get_link_from_array($file_text_ar,true);
				if (IS_ADD_TEMPLATE)
					{
						$text_templ2=str_replace('{TEXTBODY}',$file_text,$text_templ);
						$text_templ2=str_replace('{TITLE}',$text_title,$text_templ2);
						$text_templ2=str_replace('{PAGEDESCRIPTION}',$text_description,$text_templ2);
						$text_templ2=str_replace('{PAGEKEYWORD}',$text_keyword,$text_templ2);
						$text_templ2=str_replace('{LEFTLINKS}',$text_left_links,$text_templ2);
						$text_templ2=str_replace('{RIGHTLINKS}',$text_right_links,$text_templ2);
						$text_templ2=str_replace('{TITLEH1}',$main_page_title,$text_templ2);
						$text_templ2=str_replace('{DOWNLINK}',$text_down_links,$text_templ2);
						$text_templ2=str_replace('{BOOKSLINK1}',$text_book_links1,$text_templ2);
						$text_templ2=str_replace('{BOOKSLINK2}',$text_book_links2,$text_templ2);
						$file_text=$text_templ2;
					}
				$fh=fopen($fname2,'wb');
				if ($fh==false) {echo 'Ошибка при записи файла '.$fname2.'<br>';exit;}
				fwrite($fh,$file_text);
				fclose($fh);
				echo "Записан файл $fname2 <br>";
			}
	}
echo $strHtmEndMain;
?>