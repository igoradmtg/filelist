<?php
include('functions_text.php');
echo $strHtmStartMain;
if (!isset($reg_tablerows)) {$reg_tablerows='';}
$file1=file('filelist.txt');
if ($reg_tablerows=='yes') {echo "<table>\r\n";}
foreach($file1 as $row)
	{
		$row=trim($row);
		if ($reg_tablerows=='yes') 
			{
				echo '
<tr>
<td><a href="'.$row.'" target="_blank">'.$row.'</a></td>
</tr>
';
			}
			else {echo "<a href=\"$row\" target=\"_blank\">$row</a><br>\r\n";}
	}
if ($reg_tablerows=='yes') {echo "<table>\r\n";}
echo $strHtmEndMain;
?>