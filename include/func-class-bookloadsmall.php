<?php
//*******************************************************
// Класс для обработки данных о книгах для быстрой работы
//
//*******************************************************
class sql_db_bookloadsmall
{
	var $sql;
	var $table='bookloadsmall';
	var $primarykey='id';
	var $last_id=0;
	var $cur_user_id=0;
	var $res_select_book=0;
	var $count_find=0;
	var $count_rows=0;
	
	function sql_db_bookloadsmall($sql_db)
	{$this->sql=$sql_db;}
	
	function save_from_bookload($idstart,$idmax)
	{
		$idstart=intval($idstart);$idmax=intval($idmax);
		$sql="INSERT INTO bookloadsmall (id,bookrazdel,namebook) SELECT t1.id,t1.bookrazdel,t1.namebook FROM bookload as t1 WHERE (t1.id>=$idstart) AND (t1.id<=$idmax) AND (idlink=1 OR idlink=3 OR idlink=5) ON DUPLICATE KEY UPDATE bookrazdel=t1.bookrazdel,namebook=t1.namebook";
		$res=$this->sql->sql_query($sql);
		if ($res==false) 
			{AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		$num_rows=$this->sql->sql_affectedrows();
		return $num_rows;
	}
	
	function save_from_bookload2($idstart,$idmax)
	{
		$idstart=intval($idstart);$idmax=intval($idmax);
		$sql="INSERT INTO bookloadfind (id,namebook) 
		SELECT t1.id,LOWER(t1.namebook) FROM bookload as t1 
		WHERE (t1.id>=$idstart) AND (t1.id<=$idmax) AND (idlink=1 OR idlink=3 OR idlink=5) 
		ON DUPLICATE KEY UPDATE 
		namebook=LOWER(t1.namebook)";
		$res=$this->sql->sql_query($sql);
		if ($res==false) 
			{AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		$num_rows=$this->sql->sql_affectedrows();
		return $num_rows;
	}
	
	function save_from_bookload3($idstart,$idmax)
	{
		$idstart=intval($idstart);$idmax=intval($idmax);
		$sql="INSERT INTO bookloadopis (id,opis) 
		SELECT t1.id,LEFT(t1.body,255) FROM bookload as t1 
		WHERE (t1.id>=$idstart) AND (t1.id<=$idmax) AND (idlink=1 OR idlink=3 OR idlink=5) 
		ON DUPLICATE KEY UPDATE 
		opis=LEFT(t1.body,255)";
		$res=$this->sql->sql_query($sql);
		if ($res==false) 
			{AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		$num_rows=$this->sql->sql_affectedrows();
		return $num_rows;
	}
	
	function return_link_to_pages($curPage1,$maxPage1,$urlPage,$maxUrl=10,$sqlwhere='')
	{	
		// Узнаем количество страниц
		$curPage1=intval($curPage1);$maxPage1=intval($maxPage1);
		$txtzapr="SELECT COUNT(*) as kolvo FROM ".$this->table;
		if ($sqlwhere!='') $txtzapr.=" WHERE $sqlwhere";
		$res=$this->sql->sql_query($txtzapr);
		if ($res==false) {AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		//echo $txtzapr.'';
		$row=$this->sql->sql_fetch_assoc();
		$num_row=intval($row['kolvo']);
		$this->count_find=$num_row;
		//echo $num_row;
		$this->sql->sql_freeresult();
		// free result $this->sql_
		
		$maxRow=intval($num_row)-1;
		$maxPages=intval($maxRow / $maxPage1);
		$curPage=intval($curPage1 / $maxPage1);
		$linksPages='|';
		$arrPages=$this->sql->GetMinMaxPage($maxPages,$curPage,$maxUrl);
		//echo "$maxPages $curPage $maxUrl ".count($arrPages); 
		$prev_page='';
		$next_page='';
		if (count($arrPages)>1)
			{
			$num_count=0;$max_count=count($arrPages)-1;

			foreach($arrPages as $key=>$numPage)
				{
				if ($curPage!=$key)
					{
						$cur_url=str_replace('{PAGE}',strval($key*$maxPage1),$urlPage);
						$linksPages.=' <a href="'.$cur_url.'">'.$numPage.'</a> |';
					}
					else
					{
						if ($num_count>0)
							{
							$cur_url=str_replace('{PAGE}',strval(($key-1)*$maxPage1),$urlPage);
							$prev_page=' | <a href="'.$cur_url.'">Назад</a> ';
							}
						$linksPages.=' <b> '.$numPage.' </b> |';
						if ($num_count<$max_count)
							{
							$cur_url=str_replace('{PAGE}',strval(($key+1)*$maxPage1),$urlPage);
							$next_page=' <a href="'.$cur_url.'">Далее</a> |';
							}
					}
				$num_count++;
				}
			}
			else {$linksPages='';}
		return $prev_page.$linksPages.$next_page;
	}

	function return_link_to_pages_find($curPage1,$maxPage1,$urlPage,$maxUrl=10,$txt)
	{	
		// Узнаем количество страниц
		$curPage1=intval($curPage1);$maxPage1=intval($maxPage1);
		$txtzapr="SELECT COUNT(*) as kolvo FROM bookloadfind WHERE MATCH (namebook) AGAINST ('$txt')";
		$res=$this->sql->sql_query($txtzapr);
		if ($res==false) {AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		//echo $txtzapr.'';
		$row=$this->sql->sql_fetch_assoc();
		$num_row=intval($row['kolvo']);
		$this->count_find=$num_row;
		//echo $num_row;
		$this->sql->sql_freeresult();
		// free result $this->sql_
		
		$maxRow=intval($num_row)-1;
		$maxPages=intval($maxRow / $maxPage1);
		$curPage=intval($curPage1 / $maxPage1);
		$linksPages='|';
		$arrPages=$this->sql->GetMinMaxPage($maxPages,$curPage,$maxUrl);
		//echo "$maxPages $curPage $maxUrl ".count($arrPages); 
		$prev_page='';
		$next_page='';
		if (count($arrPages)>1)
			{
			$num_count=0;$max_count=count($arrPages)-1;

			foreach($arrPages as $key=>$numPage)
				{
				if ($curPage!=$key)
					{
						$cur_url=str_replace('{PAGE}',strval($key*$maxPage1),$urlPage);
						$linksPages.=' <a href="'.$cur_url.'">'.$numPage.'</a> |';
					}
					else
					{
						if ($num_count>0)
							{
							$cur_url=str_replace('{PAGE}',strval(($key-1)*$maxPage1),$urlPage);
							$prev_page=' | <a href="'.$cur_url.'">Назад</a> ';
							}
						$linksPages.=' <b> '.$numPage.' </b> |';
						if ($num_count<$max_count)
							{
							$cur_url=str_replace('{PAGE}',strval(($key+1)*$maxPage1),$urlPage);
							$next_page=' <a href="'.$cur_url.'">Далее</a> |';
							}
					}
				$num_count++;
				}
			}
			else {$linksPages='';}
		return $prev_page.$linksPages.$next_page;
	}

	function start_select_books($curPage1,$maxPage1,$sqlwhere='')
	{
		$curPage1=intval($curPage1);$maxPage1=intval($maxPage1);
		if ($sqlwhere!='') {$sqlwhere=" WHERE ".$sqlwhere;}
		$sql="SELECT * FROM ".$this->table." $sqlwhere ORDER BY id LIMIT $curPage1,$maxPage1";
		$this->res_select_book=$this->sql->sql_query($sql);
		if ($this->res_select_book==false) 
			{AddLog('Ошибка выполнения запроса '.$sql.' '.$this->sql->sql_error_message());return false;}
		$this->count_rows=$this->sql->sql_numrows($this->res_select_book);
		if ($this->count_rows==0) {$this->sql->sql_freeresult($this->res_select_book);return false;}
		return true;
	}
	function next_select_books()
	{return $this->sql->sql_fetch_assoc($this->res_select_book);}
	
	function free_result()
	{$this->sql->sql_freeresult($this->res_select_book);}
	
}
?>