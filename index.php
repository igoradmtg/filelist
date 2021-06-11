<?php
//import_request_variables("gp", "reg_");
extract($_REQUEST, EXTR_PREFIX_ALL|EXTR_REFS, 'reg');
$title='';$title1='';
include('include/filelist-functions.php');
if (!isset($reg_size)) {$reg_size=1;}
if ($reg_size==1) {$windows_h1=900;$windows_h2=600;}
if ($reg_size==2) {$windows_h1=630;$windows_h2=300;}
if ($reg_size==3) {$windows_h1=430;$windows_h2=200;}
$fname='filelist.php';
$cur_img=0;$max_img=563;
if (file_exists($fname))
	{
		$fa=file($fname);
		$links='';$script_view_none='';
		$form_template='
<tr class="white" id="c{CMD}" style="display:none">
<td>
<form action="filelist.php" method="post" name="{NUMFORM}" target="myframe" id="{NUMFORM}" enctype="multipart/form-data">
<table border="0" cellspacing="1" cellpadding="3">
<tr>
<td colspan="2"><span class="b1">({CMD}) {NAME}</span><input name="c" type="hidden" value="{CMD}"></td>
</tr>
{ROW}
<tr>
<td colspan="2"><input type="submit" value="Run" /></td>
</tr>
</table>
</form>
</td>
</tr>';
		$row_template='
<tr>
<td align="right">{POLENAME}</td>
<td align="left"><input name="{POLE}" type="{TYPE}" id="{POLE}" value="{VAL}" size="100" maxlength="250" /></td>
</tr>';
		$row_template_file='
<tr>
<td align="right">{POLENAME}</td>
<td align="left"><input name="{POLE}" type="{TYPE}"/></td>
</tr>';
		$row_template_auto='
<tr>
<td align="right">{POLENAME}</td>
<td align="left">{LINK}</td>
</tr>';
		
		$table_row='';$table_form='';
		$cmd='';$name='';$form='';$countform=0;
		foreach($fa as $row)
			{
				if (strpos($row,'// {COMMAND} ')===0)
					{
						// Записываем предыдущую команду
						if (strlen($cmd)>0) 
							{
								
								$form=str_replace('{CMD}',$cmd,$form_template);
								$form=str_replace('{NAME}',$name,$form);
								$form=str_replace('{ROW}',$table_row,$form);
								$form=str_replace('{NUMFORM}','frm'.$countform,$form);
								$table_form.=$form;
								$table_row='';$form='';
							}
                        $cur_img=mt_rand(0,$max_img);    
						$countform++; //echo " $countform ";	
						$row=substr($row,13);
						$space_pos=strpos($row,' ');
						$cmd=substr($row,0,$space_pos);
						$name=substr($row,$space_pos+1);
						$links.="<tr class=\"td1\"><td valign=\"top\"><a href=\"javascript:View('c".$cmd."')\"><img src=\"include/filelist-images.php?i=".$cur_img."\" border=\"0\" title=\"{$cmd}) ".strip_tags($name)."\"></a></td>";
						$links.="<td valign=\"top\"><a href=\"javascript:View('c".$cmd."')\" title=\"{$cmd}) ".strip_tags($name)."\">$name</a></td><tr>\r\n"; 
						$script_view_none.="ViewNone('c".$cmd."');";
						
					}
				elseif (strpos($row,'// {FORM INPUT} ')===0)
					{
						$row=substr($row,16);
						$space_pos=strpos($row,' ');
						$polenameval=substr($row,0,$space_pos);
						list($var_type,$var_name,$default_val)=explode('|',$polenameval);
						if (strtolower($var_type)=='file')
							{
								$polename=substr($row,$space_pos+1);
								$ar_search=array('{POLENAME}','{POLE}','{VAL}','{TYPE}');
								$ar_replace=array($polename,$var_name,$default_val,$var_type);
								$table_row.=str_replace($ar_search,$ar_replace,$row_template_file);
							}
							else
							{
								$polename=substr($row,$space_pos+1);
								$ar_search=array('{POLENAME}','{POLE}','{VAL}','{TYPE}');
								$ar_replace=array($polename,$var_name,$default_val,$var_type);
								$table_row.=str_replace($ar_search,$ar_replace,$row_template);
							}
					}
				elseif (strpos($row,'// {AUTOSTART} ')===0)
					{
					 	//echo " $countform ";
						$row=substr($row,15);
						$space_pos=strpos($row,' ');
						$polenameval=substr($row,0,$space_pos);
						$polename=substr($row,$space_pos+1);
						$polelink='<a href="javascript:AutoSend(\'frm'.$countform.'\','.$polenameval.')">'.$polename.' '.$polenameval.' milisec</a>';	
						$ar_search=array('{POLENAME}','{LINK}');
						$ar_replace=array($polename,$polelink);
						$table_row.=str_replace($ar_search,$ar_replace,$row_template_auto);
					}
			}
		$form=str_replace('{CMD}',$cmd,$form_template);
		$form=str_replace('{NAME}',$name,$form);
		$form=str_replace('{ROW}',$table_row,$form);
		$table_form.=$form;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="ru">
<title>Групповая обработка файлов</title>
<style type="text/css">
<!--
.blue {
        background-color: #0000FF;
}
.white {
        background-color: #FFFFFF;
}
body,td,th {
	font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.td1 {
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #CCCCCC;
}
.b1 {
	font-size: 14px;
	color: #000099;
}
.bgyelow {
	background-color: #FFFF00;
}
a:link {
	color: #0000FF;
}
a {
	text-decoration: none;
}
a:visited {
	color: #0000FF;
}
a:hover {
	color: #0099FF;
}
a:active {
	color: #FF0000;
}
-->
</style>
<script language="javascript">
<!--
function ViewNone(name)
{
	d=document.getElementById(name);
	d.style.display='none';
}
function ViewBlock(name)
{
	d=document.getElementById(name);
	d.style.display='block';
}
function AllViewNone()
{
<?php echo $script_view_none; ?>
}
function View(name)
{
	AllViewNone();
	d=document.getElementById(name);
	if (d.style.display=='none') {d.style.display='block';} else {d.style.display='none';};
}
function SendForm(name)
{
	document.forms[name].submit();
}
function AutoSend(name,milisec)
{
	SI = setInterval('SendForm(\''+name+'\')',milisec); 
}
//-->
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" class="blue"><table width="100%" border="0" cellpadding="3" cellspacing="1">
        <tr class="white">
          <td width="180" valign="top"><div style="border:1px solid #0000FF;height:<?php echo $windows_h1; ?>px;width:180px;overflow:auto"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <?php echo $links; ?>
              <tr>
                <td>&#8226;</td>
                <td><a href="javascript:AllViewNone()">Скрыть все</a></td>
              </tr>
            </table></div></td>
          <td valign="top" class="white" ><iframe name="myframe" id="myframe" width="100%" height="<?php echo $windows_h2; ?>" src="" scrolling="auto"></iframe>
            <br />
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="blue"><table width="100%" border="0" cellpadding="3" cellspacing="1">
                    <?php 
									echo $table_form;
								?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td width="180" class="bgyelow">&nbsp;</td>
          <td class="bgyelow"><a href="index.php?size=1">900x600</a>&nbsp;<a href="index.php?size=2">630x300</a>&nbsp;<a href="index.php?size=3">430x200</a></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
