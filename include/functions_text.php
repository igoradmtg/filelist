<?php

function copy_http($url,$cookie_array)
{
	$req =& new HTTP_Request($url);
	 
	$req->addHeader("ACCEPT",'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'); 
	$req->addHeader("USER_AGENT", 'Mozilla/5.0 (Windows; U; Windows NT 5.2; ru; rv:1.9.0.1) Gecko/2008070208 AdCentriaIM/1.7 MRA 5.3 (build 02560) Firefox/3.0.1');
	foreach ($cookie_array as $key=>$val)
		{$req->addCookie($key,$val);}
	if (!PEAR::isError($req->sendRequest())) 
		{return $req->getResponseBody();} else {return false;}
}


function SaveParamToFile($param,$filename)
{
	$fh=fopen($filename,'wb');
	fwrite($fh,$param."\r\n");
	fclose($fh);
}


function GetFileAndPostToEmail($file_name,$user1,$my_adress,$subj_start,$file_url)
{

	$image=@file_get_contents($file_name);
	
	if ($image==false)
		{
		echo 'Error load file '.$file_name;
		return;
		}
	
	$to  =  $user1; //. ', '.$user2; // note the comma
	
	// subject
	$subject = $subj_start.basename($file_url).'.zdt';
	$image_base64=base64_encode($image); 
	
	$headers ='';
	// To send HTML mail, the Content-type header must be set
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: application/octet-stream;name="'.basename($file_url).'.zdt"'."\r\n";
	$headers .= 'Content-transfer-encoding: base64' . "\r\n";
	
	// Additional headers
	$headers .= 'To: '.$user1."\r\n";
	$headers .= 'From: Agent_007 <'.$my_adress.'>' . "\r\n";
	// message
	$message = $image_base64;
	//$headers .= $image_base64;
	
	// Mail it
	if (@mail($to, $subject, $message, $headers)){ 
			echo 'SendFile='.basename($file_url).' Size='.strlen($image).' '; 							
		} else {
			echo 'ErrorSend='.basename($file_url).' ';
		}
}


function GetAbsoluteLink($curlink,$curFindLink)
{
 $ret_link='';
 $arrParseUrl=parse_url($curlink);
 $arrParseFindLink=parse_url($curFindLink);
 $new_scheme='';
 if (isset($arrParseFindLink['scheme'])) $new_scheme=$arrParseFindLink['scheme'];

 if ($new_scheme=='mailto')
     {
             $ret_link='';
     }
 elseif (isset($arrParseFindLink['host']))
     {
     // Это внешняя ссылка делаем проверку и заносим в список
     $ret_link=$curFindLink;
     }
     else
     {
     // Это внутренняя ссылка делаем совмещение с исходным файлом
     if (isset($arrParseFindLink['path']))
         {
                 //Находим путь
                 $curPath=$arrParseFindLink['path'];
                 $pathMain=$arrParseUrl['path'];
                 $pos0=strrpos($pathMain,'/');// Ищем последний слеш
                 if ($pos0>0)
                     {       // Обрезаем все символы после последнего слеша
                             $pathMain2=substr($pathMain,0,$pos0+1);
                     }
                     else
                     {
                         $pathMain2=$pathMain;
                     }
                 $pos1=strpos($curPath,'../');
                 $pos2=strpos($curPath,'/');
								 $pos3=strpos($curPath,'./');
                 if ($pos1===0)
                     {
                             // Надены две точки в начале
                             // Из главного урла удаляем все строки после последнего слеша
                             while($pos1===0)
                                   {
                                           $curPath=substr($curPath,3);
                                           $pathMain2=substr($pathMain2,0,strlen($pathMain2)-1); // Удаляем последний слеш
                                           $pos0=strrpos($pathMain2,'/');
                                           if (($pos0>0) || ($pos0===0))
                                               {
                                               $pathMain2=substr($pathMain2,0,$pos0+1);
                                               }
                                           $pos1=strpos($curPath,'../');
                                   }
                             $curqueri='';
                             if (isset($arrParseFindLink['query']))
                                 {
                                         $curqueri='?'.$arrParseFindLink['query'];
                                 }
                             $ret_link=$arrParseUrl['scheme'].'://'.$arrParseUrl['host'].$pathMain2.$curPath.$curqueri;
                     }
	               elseif ($pos3===0)
                     {
							 
												// Не найдены две точки в начале найден точка и слеш (./) в начале
											 $curqueri='';
											 if (isset($arrParseFindLink['query']))
													 {
																	 $curqueri='?'.$arrParseFindLink['query'];
													 }
											 if (isset($arrParseUrl['host']))
													 {
													 $ret_link=$arrParseUrl['scheme'].'://'.$arrParseUrl['host'].substr($curPath,1).$curqueri;
													 }
													 else
													 {
													 $ret_link='http://localhost'.$curPath.$curqueri;
													 }

										 }
                 elseif ($pos2===0)
                     {
                             // Не найдены две точки в начале найден слеш в начале
                             $curqueri='';
                             if (isset($arrParseFindLink['query']))
                                 {
                                         $curqueri='?'.$arrParseFindLink['query'];
                                 }
                             if (isset($arrParseUrl['host']))
                                 {
                                 $ret_link=$arrParseUrl['scheme'].'://'.$arrParseUrl['host'].$curPath.$curqueri;
                                 }
                                 else
                                 {
                                 $ret_link='http://localhost'.$curPath.$curqueri;
                                 }


                     }
                     else
                     {
                             // Не найдены ни точки ни слешы
                             $curqueri='';
                             if (isset($arrParseFindLink['query']))
                                 {
                                         $curqueri='?'.$arrParseFindLink['query'];
                                 }
                             if (isset($arrParseUrl['host']))
                                 {
                                 $ret_link=$arrParseUrl['scheme'].'://'.$arrParseUrl['host'].$pathMain2.$curPath.$curqueri;
                                 }
                                 else
                                 {
                                 $ret_link='http://localhost'.$pathMain2.$curPath.$curqueri;
                                 }
                             }
         }
     }
     return $ret_link;
}

function findLinkInText($txt1,$arrayLinks=array())
{
GLOBAL $arrayLinksTeg,$countlink;
// Копируем все найденные ссылки
$patern="|(?i)<a[^>]+>|U";
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);

foreach($out[0] as $val)
		{
		  $pos=strpos(strtolower($val),'href=');
		  if ($pos>0)
				{
					$pos2=strpos($val," ",$pos+5);if ($pos2===false) $pos2=strlen($val);
					$pos3=strpos($val,"'",$pos+6);if ($pos3===false) $pos3=strlen($val);
					$pos4=strpos($val,'"',$pos+6);if ($pos4===false) $pos4=strlen($val);
					$pos5=strpos($val,'>',$pos+6);if ($pos5===false) $pos5=strlen($val);
					$posmin=min($pos2,$pos3,$pos4,$pos5);$val2=substr($val,$pos+5,$posmin-$pos-5);
					$val2=str_replace('"',"",$val2);
					$val2=str_replace("'","",$val2);
					$pos3=strpos($val2,"#");
					$val2=trim($val2);
					$naiden=0;$posLink=0;
					if (count($arrayLinks)>0)
					{
						foreach($arrayLinks as $row)
							{
								$posLink=strpos(strtolower($val2),strtolower(trim($row)));
								if ($posLink>0)
									{
										$naiden=1;
										//echo 'P'.$posLink;
									}
							}
					}
					else
					{
						$naiden=1;
					}
					if (strlen($val2)>0)
						{
						 if (($naiden==1) && (!in_array($val2,$arrayLinksTeg)))
							 {
								$arrayLinksTeg[]=$val2; // Добавляем ссылку если ее нет
								$countlink++;
							 }
						}
					
				}
		}
}

function FindBase($txt1,$link1)
{
GLOBAL $arrayLinksTeg,$countlink;
// Копируем все найденные ссылки
$patern="|(?i)<base[^>]+>|U";
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);
$baseRet=$link1; // Ссылка по умолчанию
foreach($out[0] as $val)
		{
		  $pos=strpos(strtolower($val),'href=');
		  if ($pos>0)
				{
					 $pos2=strpos($val," ",$pos+5);if ($pos2===false) $pos2=strlen($val);
					 $pos3=strpos($val,"'",$pos+6);if ($pos3===false) $pos3=strlen($val);
					 $pos4=strpos($val,'"',$pos+6);if ($pos4===false) $pos4=strlen($val);
					 $pos5=strpos($val,'>',$pos+6);if ($pos5===false) $pos5=strlen($val);
					 $posmin=min($pos2,$pos3,$pos4,$pos5);$val2=substr($val,$pos+5,$posmin-$pos-5);
					 $val2=str_replace('"',"",$val2);
					 $val2=str_replace("'","",$val2);
					 $baseRet=$val2;
				}
		}
						 
return $baseRet;						
}

function FindTitle($txt1,$default_str='')
{
// Найти заголовок страницы HTML
//$patern="|<title[^>]+>(.*)</title[^>]+>|U";
$patern="|<title>(.*)</[^>]+>|U";
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);
if (isset($out[1][0])) {return $out[1][0];} else {return $default_str;}
}

function FindHeader1($txt1,$default_str='')
{
// Найти текст HTML в теге <h1 style="margin-bottom:0px">Samsung R510</h1>
// 
$patern="|<h1[^>]+>(.*)</[^>]+>|U";
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);
if (isset($out[1][0])) {return $out[1][0];} else {return $default_str;}
}



function FindTagsParam($txt1,$baseLink,$tagName,$paramName,$img_findtext='')
{
GLOBAL $arrayLinksImg,$arrayLinksImgInHtmlFile,$curNumFileImg;
// Копируем все найденные ссылки
$patern="|(?i)<".$tagName."[^>]+>|U";
//$paramName='src=';
//$patern="|(?i)<img[^>]+>|U";$paramName='src='; Для images

$paramCount=strlen($paramName);
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);
$imgLinkRet=''; // Ссылка по умолчанию
foreach($out[0] as $val)
		{
		  $pos=strpos(strtolower($val),$paramName);
		  if ($pos>0)
				{
					 $pos2=strpos($val," ",$pos+$paramCount);if ($pos2===false) $pos2=strlen($val);
					 $pos3=strpos($val,"'",$pos+$paramCount+1);if ($pos3===false) $pos3=strlen($val);
					 $pos4=strpos($val,'"',$pos+$paramCount+1);if ($pos4===false) $pos4=strlen($val);
					 $pos5=strpos($val,'>',$pos+$paramCount+1);if ($pos5===false) $pos5=strlen($val);
					 $posmin=min($pos2,$pos3,$pos4,$pos5);$val2=substr($val,$pos+$paramCount+1,$posmin-$pos-$paramCount);
					 $val2=str_replace('"',"",$val2);
					 $val2=str_replace("'","",$val2);
					 $imgLinkRet=trim($val2);
					 $proverka=0;
					 if (strlen($imgLinkRet)>0) {$proverka=1;}
					 if ($proverka==1) 
					 	{
							$absLink=GetAbsoluteLink($baseLink,$imgLinkRet);
							if (in_array($absLink,$arrayLinksImg)) {$proverka=0;}
						}
					if (($proverka==1) && (strlen($img_findtext)>0))	
						{
							if (strpos($absLink,$img_findtext)===false) {$proverka=0;}
						}
					if ($proverka==1) 
						{
							$arrayLinksImg[]=$absLink;
							$curFileExt=GetFileExtFromUrl($absLink,'.mdt');	
							$local_file_name_media='media'.AddZero($curNumFileImg,6).$curFileExt;
							$arrayLinksImgInHtmlFile[]=array('url'=>$imgLinkRet, 'absurl'=>$absLink,'file'=>$local_file_name_media);
							$curNumFileImg++;
						}
				}
		}
}

function ProverkaLinks($curFindLink)
{
$result='';

if (strpos(strtolower($curFindLink),'http://')===0)
	{
		$pos1=strpos($curFindLink,'http://refer.ccbill.com/cgi-bin/clicks.cgi');
		if ($pos1===0)
			{
				$link1=trim($curFindLink);
				$pos2=strpos($link1,'&HTML=');
				if ($pos2>0)
					{
						$link1=substr($link1,$pos2+6);
						$pos3=strpos($link1,'&');
						if ($pos3===false) {$pos3=strlen($link1);}
						$link1=substr($link1,0,$pos3);
						$result=trim($link1);
					}
			}
			else
			{
				$result=trim($curFindLink);
			}
	}
elseif (strpos($curFindLink,'/cgi-bin/ucj/c.cgi')===0)
	{
		// Удаляем лишние параметры и добавляем
		$link1=trim($curFindLink);
		$pos1=strpos($link1,'?u=');
		$pos3=strpos($link1,'?url=');
		$pos4=strpos($link1,'&u=');
		$pos5=strpos($link1,'&url=');
		$numFind=0;
		if (($pos1===false) && ($pos3>0))
			{
				$pos1=$pos3+1;
				$numFind=1;
			}
		elseif ($pos4>0)	
			{
				$pos1=$pos4+1;
				$numFind=2;
			}
		elseif ($pos5>0)	
			{
				$pos1=$pos5+1;
				$numFind=1;
			}
		elseif ($pos1>0)
			{
				$pos1=$pos1+1;
				$numFind=2;
			}
		//echo 'pos1 = '.$pos1."<br>\r\n";
		//echo 'pos2 = '.$pos2."<br>\r\n";
		//echo 'pos3 = '.$pos3."<br>\r\n";
		//echo 'pos4 = '.$pos4."<br>\r\n";
		$link1=substr($link1,$pos1);	
		//echo 'link1 = '.$link1."<br>\r\n";
		
		$str_arr=array();
		parse_str($link1,$str_arr); 
		//print_r($str_arr);
		if ($numFind==1)
			{
				$result=trim($str_arr['url']);
			}
		if ($numFind==2)
			{
				$result=trim($str_arr['u']);
			}
		$result=urldecode($result);
	}
elseif  (strpos($curFindLink,'/cgi-bin/o.cgi')===0)
	{
		$link1=html_entity_decode(trim($curFindLink));
		$pos1=strpos($link1,'?u=');
		$pos3=strpos($link1,'?url=');
		$pos4=strpos($link1,'&u=');
		$pos5=strpos($link1,'&url=');
		$numFind=0;
		if (($pos1===false) && ($pos3>0))
			{
				$pos1=$pos3+1;
				$numFind=1;
			}
		elseif ($pos4>0)	
			{
				$pos1=$pos4+1;
				$numFind=2;
			}
		elseif ($pos5>0)	
			{
				$pos1=$pos5+1;
				$numFind=1;
			}
		elseif ($pos1>0)
			{
				$pos1=$pos1+1;
				$numFind=2;
			}
		//echo 'pos1 = '.$pos1."<br>\r\n";
		//echo 'pos2 = '.$pos2."<br>\r\n";
		//echo 'pos3 = '.$pos3."<br>\r\n";
		//echo 'pos4 = '.$pos4."<br>\r\n";
		$link1=substr($link1,$pos1);	
		//echo 'link1 = '.$link1."<br>\r\n";
		
		$str_arr=array();
		parse_str($link1,$str_arr); 
		//print_r($str_arr);
		if ($numFind==1)
			{
				$result=trim($str_arr['url']);
			}
		if ($numFind==2)
			{
				$result=trim($str_arr['u']);
			}
	}
	else
	{
		$result=$curFindLink;
	}
return $result;
}

function FindStrInArray($arrayLinksImg,$img_findtext)
{
$arrayTmp=array();
for($a=0;$a<count($arrayLinksImg);$a++)
	{
		$curImgLink=$arrayLinksImg[$a];
		if (strpos($curImgLink,$img_findtext)!==false) {$arrayTmp[]=$curImgLink;}
	}
return $arrayTmp;
}

function ReplaceMediaInFile($fileName)
{
GLOBAL $arrayLinksImgInHtmlFile;
$old_url=array();$new_url=array();

	foreach($arrayLinksImgInHtmlFile as $val)
		{$old_url[]=$val['url'];$new_url[]=$val['file'];}
	$txt1=file_get_contents($fileName);
	$txt1=str_replace($old_url,$new_url,$txt1);
	$fh=fopen($fileName,'wb');
	fwrite($fh,$txt1);
	fclose($fh);
}

function FindCssLink($txt1,$baseLink)
{
GLOBAL $arrayLinksCss,$arrayLinksCssInHtmlFile,$curNumFileCss;
// Копируем все найденные ссылки
$patern="|(?i)<link[^>]+>|U";
$out=array();
preg_match_all($patern,$txt1,$out,PREG_PATTERN_ORDER);
$imgLinkRet='';
foreach($out[0] as $val)
		{
		  $pos=strpos(strtolower($val),'href=');
		  if ($pos>0)
				{
					 $pos2=strpos($val," ",$pos+5);if ($pos2===false) $pos2=strlen($val);
					 $pos3=strpos($val,"'",$pos+6);if ($pos3===false) $pos3=strlen($val);
					 $pos4=strpos($val,'"',$pos+6);if ($pos4===false) $pos4=strlen($val);
					 $pos5=strpos($val,'>',$pos+6);if ($pos5===false) $pos5=strlen($val);
					 $posmin=min($pos2,$pos3,$pos4,$pos5);$val2=substr($val,$pos+5,$posmin-$pos-5);
					 $val2=str_replace('"',"",$val2);
					 $val2=str_replace("'","",$val2);
					 $imgLinkRet=trim($val2);
					 if (strlen($imgLinkRet)>0)
					 	{
							 $absLink=GetAbsoluteLink($baseLink,$imgLinkRet);
							 if (!in_array($absLink,$arrayLinksCss))
								{
									$arrayLinksCss[]=$absLink;
									$curFileExt=GetFileExtFromUrl($absLink,'.css');
									$local_file_name_media='linkcss'.AddZero($curNumFileCss,6).$curFileExt;
									$arrayLinksCssInHtmlFile[]=array('url'=>$imgLinkRet, 'absurl'=>$absLink,'file'=>$local_file_name_media);
									$curNumFileCss++;
								}
						}
				}
		}
}
function CreateThumb($fileName)
{
// Создать миниатюру размер 160 х 120
$img_size=@getimagesize($fileName);
if ($img_size==false) {return false;}
list($width_orig, $height_orig, $type, $attr) = $img_size;

$widthS = 160;
$heightS = 120;
$width = $widthS;
$height = $heightS;
$k0n1=$height / $height_orig;
$k0n2=$width / $width_orig;
if ($k0n1<$k0n2) {$k0tmp=$k0n1;} else {$k0tmp=$k0n2;}
$width = intval($k0tmp * $width_orig);
$height = intval($k0tmp * $height_orig);
$image = @imagecreatefromjpeg($fileName);
if ($image==false) {return false;}
$image_p = imagecreatetruecolor($width,$height);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
return $image_p;


}

function GetImgAndShowMini()
{
	GLOBAL $arrayFindInLinks,$arrayFindLinks,$arraylinks,$file_start,$file_end,$img_start,$img_count,$file_name,$filelocal,$arrayLinksTeg;
	$arrayFindLinks=array();
	$arraylinks=array();
	$array_local_file=array();
	for($a=$file_start;$a<=$file_start+$file_end;$a++)
		{
			for ($b=$img_start;$b<=$img_start+$img_count;$b++)
				{
					$cur_file_name=str_replace('(nn)',AddZero($a,$numchar),$file_name);
					$cur_file_name=str_replace('(mm)',AddZero($b,$img_numchar),$cur_file_name);
					$arraylinks[]=$cur_file_name;
				}
		}
	//print_r($arraylinks);
	$strError=''; // Строка содержит список ошибок
	$strEcho='';
	$linkCount=0;
	$linkError=0;
	$newsize=0;
	$numAllFiles=0;
  
	for ($b=0;$b<count($arraylinks);$b++)
		{
			if ($b>count($arraylinks)) break;
			$filenamestr=$arraylinks[$b];
			$filename=trim($filenamestr);
			$arraylinks[$b]=$filename;
			$curlink=$filename; // Сохраняем текущюю ссылку
			$arrayLinksTeg=array();
			$arrayLinksTeg2=array();
			$fileLinkCur='';
		
			if (!@copy($filename,$filelocal))
				{ // Ошибка копирования
					$strError.='E'.$b;
					$linkCount++;
					$linkError++;
					$strEcho.='Error load '.$filename.' '.$filelocal.'<br>';
				}
				else
				{
					$linkCount++;
					// Узнаем размер файла
					$cursize=filesize($filelocal);
					// Читаем файл в строку
					$txt1=file_get_contents($filelocal);
					$countlink=0;
					findLinkInText($txt1,$arrayFindInLinks);
					$baseLink=FindBase($txt1,$filename);
					$strEcho.=$filename.' '.strlen($txt1);
					$fileLinkCur=$filename;
					//echo 'Load '.$filename.' BASE='.$baseLink.'<br>';
				}
		
			@unlink($filelocal); // Удаляем локальный файл
			//print_r($arrayLinksTeg);
			
			foreach($arrayLinksTeg as $curFindLink)
				{
				//echo '1:'.$curFindLink." | ";
				//$curFindLink2=ProverkaLinks($curFindLink);
				//echo '2:'.$curFindLink2." | ";
				$linkImg=GetAbsoluteLink($baseLink,$curFindLink);
				
				if (strlen($linkImg)>0)
					{
						if (!in_array($linkImg,$arrayLinksTeg2))
							{$arrayLinksTeg2[]=$linkImg;}
					}
				}
				
			if (count($arrayLinksTeg2)>0) 
				{
					$countFileImg=1;
					$txtEcho='';
					$file_stat_povtor=0;
					$file_stat_error_load=0;
					foreach($arrayLinksTeg2 as $curFindLink)
						{
						$linkImg=GetAbsoluteLink($baseLink,$curFindLink);
						//echo $linkImg.'<br>';
						$fileExt=GetFileExtFromUrl($curFindLink,'.mdt');
						$filelocalTmp='file'.AddZero($linkCount,5).'n'.AddZero($countFileImg,2).$fileExt;
						if (@copy($linkImg,$filelocalTmp))
							{
								$new_md=md5_file($filelocalTmp);
								$file_tmp_size=filesize($filelocalTmp);
								$kontrol=0;
								$countFileImg++;
								$numAllFiles++;
								$array_local_file[]=$filelocalTmp;
							}
						$strEcho.= 'LinkCount='.strval(count($arrayLinksTeg2)).' ';
						if ($countFileImg>1) {$strEcho.= 'LoadOk='.strval($countFileImg-1).' ';}
						if ($file_stat_error_load>0) {$strEcho.= 'ErrorLoad='.$file_stat_error_load.' ';}	
						if ($file_stat_povtor>0) {$strEcho.= 'Povtor='.$file_stat_povtor.' ';}	
						$strEcho.= "<br>\r\n";
						}
				}
				else
				{
					$strEcho.= 'LinkCount=0'."<br>\r\n";
				}
			if (count($array_local_file)>0)
				{
					$img=CreateThumb($array_local_file[0]);
					if ($img==false) {echo 'Error image';}
						else {header('Content-type: image/jpeg');imagejpeg($img, null, 95);}
					foreach($array_local_file as $filelocalTmp)
						{@unlink($filelocalTmp);}
						
				}
				else
				{
					echo $strEcho;
					echo 'NotFindImg ';
				}
		}
	print_r($arrayFindInLinks);
	print_r($array_local_file);
}
function FindVal($val)
{
	$find_lnk=array('http://www.tiavastube.com','http://www.gigagalleries.com');
	$ne_naiden=true;
	foreach($find_lnk as $find_me)
		{
			if (strpos($val,$find_me)===0) {$ne_naiden=false;}
		}
	return $ne_naiden;
}

function DelGallsLink($arrayLink)
{
	$ret_array=array();
	$ret_array=array_filter($arrayLink,'FindVal');
	return $ret_array;
}

function strip_tags2($str,$new_str=' ') // Удаление тегов правильное <br> заменяется на пробел
{
	$patern="/(?im)<br[^>]+>|(?im)<br>/U";
	$str=preg_replace($patern,$new_str,$str); 
	$patern="/(?im)<tr[^>]+>|(?im)<tr>/U";
	$str=preg_replace($patern,$new_str,$str); 
	$str=strip_tags($str);
	return $str;
}

?>