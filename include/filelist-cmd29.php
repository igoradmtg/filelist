<?php
include('functions_text.php');
echo $strHtmStartMain;
if (!isset($reg_file1)) {echo '�� ������ ���� � ���������';exit;}
if (is_file($reg_file1)==false) {echo '�� ������ ���� � ���������';exit;}
$text1=file($reg_file1);
$start_vopros=false;
$start_otvet=false;
$povtora_net=true;
$ar_vopros=array();
$num_vopros=0;
foreach($text1 as $row)
	{
		if (strlen(trim($row))==0) {continue;} // ���� ������ ������ ����� ���������� 
		if (strpos($row,'������')===0)
			{
				$start_vopros=true;$start_otvet=false;
				//echo '<b>'.trim($row).'</b><br>';
			}
		elseif ($start_vopros==true)
			{
				$start_vopros=false;
				if (!in_array(trim($row),$ar_vopros))
					{
						$ar_vopros[]=trim($row);
						$povtora_net=true;
						$num_vopros++;
						echo '<b>������ '.$num_vopros.'</b><br>';
					}
					else
					{$povtora_net=false;}
			}
		
		if (($povtora_net==true) && ($start_vopros==false))
			{
				echo trim($row).'<br>';
			}
	}

echo $strHtmEndMain;
?>