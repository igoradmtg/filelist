<?php
// Команда 17 Сделать множество повторяющихся ссылок
	echo $strHtmStartMain;
	for ($a=1;$a<258;$a++)
		{
			echo '<a href=http://www.midi-karaoke.info/"http://fxmag.ru/get.php?id='.$a.'">'.$a.'</a><br>';
		}
	echo $strHtmEndMain;
	exit;
?>