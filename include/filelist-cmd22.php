<?php
echo $strHtmStartMain;
if ($reg_dir!='')
	{
		if (!is_dir($reg_dir)) {@mkdir($reg_dir);}
		$uploadfile = $reg_dir.'/'.basename($_FILES['userfile']['name']);
	}
	else
	{
		$uploadfile = basename($_FILES['userfile']['name']);
	}
echo '<pre>';
echo $uploadfile."\n";
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
{
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    foreach($_FILES['userfile'] as $key=>$val)
			{
				echo "[$key] = $val \r\n";
			}
} else 
{
    echo "Error! Possible file upload attack!  Дополнительная отладочная информация:\n";
    print_r($_FILES);
}
echo '</pre>';
echo $strHtmEndMain;
?>