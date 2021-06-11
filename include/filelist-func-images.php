<?php

// Сделать из двух картинок одну по размеру
// Первая картинка сверху, вторая снизу
function MakeMini2to1($fnameimg1,$fnameimg2,$new_file,$imageWidth=160,$imageHeight=120,$image_res=85,$show_message=true)
{
$img_big=false;
if (strpos(strtolower($fnameimg1),'.gif')!==false) {$img_big=@imagecreatefromgif($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefrompng($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefromjpeg($fnameimg1);}
	}
elseif (strpos(strtolower($fnameimg1),'.png')!==false)
	{$img_big=@imagecreatefrompng($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefromgif($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefromjpeg($fnameimg1);}
	}
else
	{$img_big=@imagecreatefromjpeg($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefromgif($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefrompng($fnameimg1);}
	}
if ($img_big==false)
	{
		echo 'Error open file '.$fnameimg1."<br>\r\n";
		return false;
	}

$img_big2=false;
if (strpos(strtolower($fnameimg2),'.gif')!==false) 
	{
		$img_big2=@imagecreatefromgif($fnameimg2);
		if ($img_big2==false) {$img_big2=@imagecreatefrompng($fnameimg2);}
		if ($img_big2==false) {$img_big2=@imagecreatefromjpeg($fnameimg2);}
	}
elseif (strpos(strtolower($fnameimg2),'.png')!==false)
	{$img_big2=@imagecreatefrompng($fnameimg2);
	if ($img_big2==false) {$img_big2=@imagecreatefromgif($fnameimg2);}
	if ($img_big2==false) {$img_big2=@imagecreatefromjpeg($fnameimg2);}
	}
else
	{$img_big2=@imagecreatefromjpeg($fnameimg2);
	if ($img_big2==false) {$img_big2=@imagecreatefromgif($fnameimg2);}
	if ($img_big2==false) {$img_big2=@imagecreatefrompng($fnameimg2);}
	}
if ($img_big2==false)
	{
		echo 'Error open file '.$fnameimg2."<br>\r\n";
		return false;
	}
$width_orig=imagesx($img_big);$height_orig=imagesy($img_big);
$width_orig2=imagesx($img_big2);$height_orig2=imagesy($img_big2);
$k0n1=$imageWidth / $height_orig;
$k0n2=$imageHeight / $width_orig;
if ($k0n1<$k0n2) {$k0tmp=$k0n1;} else {$k0tmp=$k0n2;}
$width_small1 = intval($k0tmp * $width_orig); // Получаем новый размер по ширине
$height_small1 = intval($k0tmp * $height_orig); // Получаем новый размер по высоте

$k2n1=$imageWidth / $height_orig2;
$k2n2=$imageHeight / $width_orig2;
if ($k2n1<$k2n2) {$k2tmp=$k2n1;} else {$k2tmp=$k2n2;}
$width_small2 = intval($k2tmp * $width_orig2); // Получаем новый размер по ширине
$height_small2 = intval($k2tmp * $height_orig2); // Получаем новый размер по высоте
if ($width_small1>$width_small2) {$width_small0=$width_small1;} // Находим максимальную ширину из двух картинок
else {$width_small0=$width_small2;} 
$image_p = imagecreatetruecolor($width_small0,$height_small1+$height_small2);

if (($width_orig>$width_small0) || ($height_orig>$height_small1))
	{
		imagecopyresampled($image_p, $img_big, 0, 0, 0, 0, $width_small1, $height_small1, $width_orig, $height_orig);
	}
	else
	{
		imagecopy($image_p, $img_big, 0, 0, 0, 0, $width_orig, $height_orig);
	}
	
if (($width_orig2>$width_small0) || ($height_orig2>$height_small2))
	{
		imagecopyresampled($image_p, $img_big2, 0, $height_small1, 0, 0, $width_small2, $height_small2, $width_orig2, $height_orig2);
	}
	else
	{
		imagecopy($image_p, $img_big2, 0, $height_small1, 0, 0, $width_orig2, $height_orig2);
	}


$res=imagejpeg($image_p,$new_file,$image_res);
if ($res==true) {if ($show_message) {echo "Записан файл $new_file <br>";} return true;}
	else {if ($show_message) {echo "Ошибка при записи файла $new_file <br>";} return false;}
}

// Сделать из двух картинок одну по размеру
// Первая картинка сверху, вторая снизу
function MakeMini3to1($fnameimg1,$fnameimg2,$fnameimg3,$new_file,$imageWidth=160,$imageHeight=120,$image_res=85,$show_message=true)
{
$img_big=false;
if (strpos(strtolower($fnameimg1),'.gif')!==false) {$img_big=@imagecreatefromgif($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefrompng($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefromjpeg($fnameimg1);}
	}
elseif (strpos(strtolower($fnameimg1),'.png')!==false)
	{$img_big=@imagecreatefrompng($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefromgif($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefromjpeg($fnameimg1);}
	}
else
	{$img_big=@imagecreatefromjpeg($fnameimg1);
	if ($img_big==false) {$img_big=@imagecreatefromgif($fnameimg1);}
	if ($img_big==false) {$img_big=@imagecreatefrompng($fnameimg1);}
	}
if ($img_big==false)
	{
		echo 'Error open file '.$fnameimg1."<br>\r\n";
		return false;
	}

$img_big2=false;
if (strpos(strtolower($fnameimg2),'.gif')!==false) 
	{
		$img_big2=@imagecreatefromgif($fnameimg2);
		if ($img_big2==false) {$img_big2=@imagecreatefrompng($fnameimg2);}
		if ($img_big2==false) {$img_big2=@imagecreatefromjpeg($fnameimg2);}
	}
elseif (strpos(strtolower($fnameimg2),'.png')!==false)
	{$img_big2=@imagecreatefrompng($fnameimg2);
	if ($img_big2==false) {$img_big2=@imagecreatefromgif($fnameimg2);}
	if ($img_big2==false) {$img_big2=@imagecreatefromjpeg($fnameimg2);}
	}
else
	{$img_big2=@imagecreatefromjpeg($fnameimg2);
	if ($img_big2==false) {$img_big2=@imagecreatefromgif($fnameimg2);}
	if ($img_big2==false) {$img_big2=@imagecreatefrompng($fnameimg2);}
	}
if ($img_big2==false)
	{
		echo 'Error open file '.$fnameimg2."<br>\r\n";
		return false;
	}

$img_big3=false;
if (strpos(strtolower($fnameimg3),'.gif')!==false) 
	{
		$img_big3=@imagecreatefromgif($fnameimg3);
		if ($img_big3==false) {$img_big3=@imagecreatefrompng($fnameimg3);}
		if ($img_big3==false) {$img_big3=@imagecreatefromjpeg($fnameimg3);}
	}
elseif (strpos(strtolower($fnameimg3),'.png')!==false)
	{$img_big3=@imagecreatefrompng($fnameimg3);
	if ($img_big3==false) {$img_big3=@imagecreatefromgif($fnameimg3);}
	if ($img_big3==false) {$img_big3=@imagecreatefromjpeg($fnameimg3);}
	}
else
	{$img_big3=@imagecreatefromjpeg($fnameimg3);
	if ($img_big3==false) {$img_big3=@imagecreatefromgif($fnameimg3);}
	if ($img_big3==false) {$img_big3=@imagecreatefrompng($fnameimg3);}
	}
if ($img_big3==false)
	{
		echo 'Error open file '.$fnameimg3."<br>\r\n";
		return false;
	}

$width_orig=imagesx($img_big);$height_orig=imagesy($img_big);
$width_orig2=imagesx($img_big2);$height_orig2=imagesy($img_big2);
$width_orig3=imagesx($img_big3);$height_orig3=imagesy($img_big3);

$k0n1=$imageWidth / $height_orig;
$k0n2=$imageHeight / $width_orig;
if ($k0n1<$k0n2) {$k0tmp=$k0n1;} else {$k0tmp=$k0n2;}
$width_small1 = intval($k0tmp * $width_orig); // Получаем новый размер по ширине
$height_small1 = intval($k0tmp * $height_orig); // Получаем новый размер по высоте

$k2n1=$imageWidth / $height_orig2;
$k2n2=$imageHeight / $width_orig2;
if ($k2n1<$k2n2) {$k2tmp=$k2n1;} else {$k2tmp=$k2n2;}
$width_small2 = intval($k2tmp * $width_orig2); // Получаем новый размер по ширине
$height_small2 = intval($k2tmp * $height_orig2); // Получаем новый размер по высоте

$k3n1=$imageWidth / $height_orig3;
$k3n2=$imageHeight / $width_orig3;
if ($k3n1<$k3n2) {$k3tmp=$k3n1;} else {$k3tmp=$k3n2;}
$width_small3 = intval($k3tmp * $width_orig3); // Получаем новый размер по ширине
$height_small3 = intval($k3tmp * $height_orig3); // Получаем новый размер по высоте

$width_small0=max($width_small1,$width_small2,$width_small3); // Находим максимальную ширину из трех картинок
$image_p = imagecreatetruecolor($width_small0,$height_small1+$height_small2+$height_small3);

if (($width_orig>$width_small0) || ($height_orig>$height_small1))
	{
		imagecopyresampled($image_p, $img_big, 0, 0, 0, 0, $width_small1, $height_small1, $width_orig, $height_orig);
	}
	else
	{
		imagecopy($image_p, $img_big, 0, 0, 0, 0, $width_orig, $height_orig);
	}
	
if (($width_orig2>$width_small0) || ($height_orig2>$height_small2))
	{
		imagecopyresampled($image_p, $img_big2, 0, $height_small1, 0, 0, $width_small2, $height_small2, $width_orig2, $height_orig2);
	}
	else
	{
		imagecopy($image_p, $img_big2, 0, $height_small1, 0, 0, $width_orig2, $height_orig2);
	}

if (($width_orig3>$width_small0) || ($height_orig3>$height_small3))
	{
		imagecopyresampled($image_p, $img_big3, 0, $height_small1+$height_small2, 0, 0, $width_small3, $height_small3, $width_orig3, $height_orig3);
	}
	else
	{
		imagecopy($image_p, $img_big3, 0, $height_small1+$height_small2, 0, 0, $width_orig3, $height_orig3);
	}


$res=imagejpeg($image_p,$new_file,$image_res);
if ($res==true) {if ($show_message) {echo "Записан файл $new_file <br>";} return true;}
	else {if ($show_message) {echo "Ошибка при записи файла $new_file <br>";} return false;}
}

?>
