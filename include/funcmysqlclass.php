<?php
/***************************************************************************
 *                                 mysql.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: mysql.php,v 1.16.2.1 2005/09/18 16:17:20 acydburn Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

class sql_db
{

	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $color_head="#1A0082"; // ���� ���� ��������� �������
	var $color_text_head="#FFFFFF"; // ���� ������ ��������� �������
	var $color_td1="#FFE1FF"; // ���� ���� ������ �������
	var $color_td2="#FFE5FF"; // ���� ���� ������ �������
	var $show_rows=true; // �������� ���� ����� ����������� ������ ���� �����
	var $table;
	var $primarykey="id";
	var $db;
	var $data;
	var $datalist='';
	var $value;
	var $valuelist="'";
	var $order;
	var $orderlist;
	var $where;
	var $wherelist;
	var $limit='';

	// *******************************************************************************************************
	// ����������� ������
	// Constructor
	// 		sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	// ���������:
	//		$sqlserver - ��� ����� 
	//		$sqluser - ��� ������������ ������� �����
	//		$sqlpassword - ������ ������������ ������� �����
	//		$database - ��� ���� ������ ��� �������
	//		$persistency = true
	//
	// ������:
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// *******************************************************************************************************
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{
		//$this->$color_head="#FFFF00"; // ���� ���� ��������� �������
		//$this->$color_td1="#FFE1FF"; // ���� ���� ������ �������
		//$this->$color_td2="#FFE1FF"; // ���� ���� ������ �������
		//var $show_rows=false; // �������� ���� ����� ����������� ������ ���� �����

		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		if($this->persistency)
		{
			$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
		}
		else
		{
			$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
		}
		if($this->db_connect_id)
		{
			if($database != "")
			{
				$this->dbname = $database;
				$dbselect = @mysql_select_db($this->dbname);
				if(!$dbselect)
				{
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			return $this->db_connect_id;
		}
		else
		{
			return false;
		}
	}

	// *******************************************************************************************************
	// ������� ���������� � �������� �����
	// 		sql_close()
	// ���������:
	//
	// ������:
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->sql_close();
	// *******************************************************************************************************
	function sql_close()
	{
		if($this->db_connect_id)
		{
			if($this->query_result)
			{
				@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	// *******************************************************************************************************
	// ��������� ������ � ���� ������ �� ������ �����
	// 		sql_query($query = "", $transaction = FALSE)
	// ���������:
	//		$query - ����� ������� (�� ���������: $query = "")
	//		$transaction = FALSE
	//
	// ������:
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$txtzapr="SELECT * FROM $tableName $txtParam";
	//		$res=$sql->sql_query($txtzapr);
	//		$sql->sql_close();
	// *******************************************************************************************************
	function sql_query($query = "", $transaction = FALSE)
	{
		// Remove any pre-existing queries
		unset($this->query_result);
		if($query != "")
		{
			$this->num_queries++;
			$this->query_result = @mysql_query($query, $this->db_connect_id);
		}
		if($this->query_result)
		{
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		}
		else
		{
			return ( $transaction == END_TRANSACTION ) ? true : false;
		}
	}

	// *******************************************************************************************************
	// ���������� ���������� ����� ���������� � ���������� ������� � ���� ������ �����
	// 		sql_numrows($query_id = 0)
	// ���������:
	//		$query_id
	//
	// ������:
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$txtzapr="SELECT * FROM $tableName $txtParam";
	//		$res=$sql->sql_query($txtzapr);
	//		$num_row=$sql->sql_numrows();
	//		$sql->sql_close();
	// *******************************************************************************************************
	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	// *******************************************************************************************************
	// ���������� ����� ���������� ������� ��������� ����� 
	// ���������� ��������� INSERT, UPDATE, DELETE �������� � �������
	// 		sql_affectedrows()
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_affectedrows()
	{
		if($this->db_connect_id)
		{
			$result = @mysql_affected_rows($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	// *******************************************************************************************************
	// ������������ ��� ���������� ������� � ���������� ������������� ������
	// ���������� ������������� ������ � ���������� ��������, ��������������� ��������� ������� 
	// ��� FALSE ���� ����� ������ ���
	// 		sql_fetch_assoc()
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_fetch_assoc()
	{
		if($this->db_connect_id)
		{
			$result = @mysql_fetch_assoc ($this->query_result);
			return $result;
		}
		else
		{
			return false;
		}
	}
	// *******************************************************************************************************
	// ������������ ��� ���������� ������� � ���������� ��������������� ������
	// ���������� ��������������� ������ 
	// ��� FALSE ���� ����� ������ ���
	// 		sql_fetch_row()
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_fetch_row()
	{
		if($this->db_connect_id)
		{
			$result = @mysql_fetch_row ($this->query_result);
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	// *******************************************************************************************************
	// ���������� ���������� ����� ���������� �������
	// 		sql_numfields($query_id = 0)
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_fields($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	// *******************************************************************************************************
	// ���������� ���������� ����� ���������� �������
	// 		sql_fieldname($offset, $query_id = 0)
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	// *******************************************************************************************************
	// ���������� ���������� ����� ���������� �������
	// 		sql_fieldtype($offset, $query_id = 0)
	// ���������:
	//
	// ������:
	//
	// *******************************************************************************************************
	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrow($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id))
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][0][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_nextid(){
		if($this->db_connect_id)
		{
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_freeresult($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			@mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}
	function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);

		return $result;
	}
	
	function echo_error()
	{
		echo @mysql_error($this->db_connect_id);
	}
	function echo_info()
	{
		echo @mysql_info($this->db_connect_id);
	}
	function echo_affected_rows()
	{
		echo '���������� �����:'.mysql_affected_rows($this->db_connect_id);
	}
	// *******************************************************************************************************
	// ���������� ��������� ���������� � ��������� ������� � �������
	// INSERT INTO ... SELECT ...
	// String format: Records: 23 Duplicates: 0 Warnings: 0 
	// INSERT INTO ... VALUES (...),(...),(...)...
	// String format: Records: 37 Duplicates: 0 Warnings: 0 
	// LOAD DATA INFILE ...
	// String format: Records: 42 Deleted: 0 Skipped: 0 Warnings: 0 
	// ALTER TABLE
	// String format: Records: 60 Duplicates: 0 Warnings: 0 
	// UPDATE
	// String format: Rows matched: 65 Changed: 65 Warnings: 0
	// 		sql_info()
	// ���������:
	//
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->LoadFileInTable($fileName,$tableName)
	//		$sql->sql_info();
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function sql_info()
	{
		return @mysql_info($this->db_connect_id);
	}
	// *******************************************************************************************************
	// ��������� ������ �� ���������� ����� � ������� �������������� ������� ���������
	// 		LoadFileInTable($fileName,$tableName)
	// ���������:
	//		$fileName - ��� ���������� �����
	// 		$tableName - ��� ������� ���� ������
	//
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->LoadFileInTable($fileName,$tableName)
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function LoadFileInTable($fileName,$tableName)
	{
		if (!file_exists($fileName))
			{
			echo '����������� ���� ��� ��������:'.$fileName;
			return;
			}
		$txtzapr="DELETE FROM ".$tableName.";";
		$res=$this->sql_query($txtzapr);
		echo $this->sql_info();
		
		$txtzapr="LOAD DATA LOCAL INFILE '".$fileName."' INTO TABLE ".$tableName." FIELDS TERMINATED BY '|';";
		$res=$this->sql_query($txtzapr);
		if ($res==false)
			{
				$this->echo_error();
			}
			else
			{
				$this->echo_info();
			}
	}
	// *******************************************************************************************************
	// ��������� ������ �� ���������� ����� � ������� �������������� ������� ���������
	// 		LoadFileInTable($fileName,$tableName)
	// ���������:
	//		$fileName - ��� ���������� �����
	// 		$tableName - ��� ������� ���� ������
	//
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->LoadFileInTable($fileName,$tableName)
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function SaveTableToFile($fileName,$tableName)
	{
		$txtzapr="SELECT * INTO OUTFILE '$fileName' FIELDS TERMINATED BY '|' FROM $tableName;";
		$res=$this->sql_query($txtzapr);
		if ($res==false)
			{	
				$this->echo_error();
			}
			else
			{
				echo $this->sql_info();
			}
	}
	
	// *******************************************************************************************************
	// ��������� ������ �� ���������� ����� � �������
	// 		LoadFileInTableAdd($fileName,$tableName,$txtParam)
	// ���������:
	//		$fileName - ��� ���������� �����
	// 		$tableName - ��� ������� ���� ������
	//
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->LoadFileInTableAdd($fileName,$tableName,$txtParam)
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function LoadFileInTableAdd($fileName,$tableName,$txtParam)
	{
		if (!file_exists($fileName))
			{
			echo '����������� ���� ��� ��������:'.$fileName;
			return;
			}
			
		$txtzapr="LOAD DATA LOCAL INFILE '".$fileName."' $txtParam INTO TABLE ".$tableName." FIELDS TERMINATED BY '|';";
		$res=$this->sql_query($txtzapr);
		echo $this->sql_info();
		if ($res==false)
			{
				$this->echo_error();
			}
			else
			{
				$this->echo_info();
			}
	}
	
	// *******************************************************************************************************
	// ��������� ������ �� ���������� ����� � �������
	// 		LoadFileInTableAdd2($fileName,$tableName,$txtParam)
	// ���������:
	//		$fileName - ��� ���������� �����
	// 		$tableName - ��� ������� ���� ������
	//
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->DeleteAllRowFromTable($tableName);
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function LoadFileInTableAdd2($fileName,$tableName,$txtParam)
	{
		if (!file_exists($fileName))
			{
			echo '����������� ���� ��� ��������:'.$fileName;
			return;
			}
		$txtzapr="LOAD DATA LOCAL INFILE '".$fileName."' $txtParam INTO TABLE ".$tableName." FIELDS TERMINATED BY '|';";
		$res=$this->sql_query($txtzapr);
		if ($res==false)
			{
				$this->echo_error();
			}
			else
			{
				$this->echo_info();
			}
	}
	
	
	// *******************************************************************************************************
	// ������� ��� ������ � �������
	// 		DeleteAllRowFromTable($tableName)
	// ���������:
	// 		$tableName - ��� ������� ���� ������
	// ��������:
	// 		$tableName='loguserpass1'; 	
	//		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	//		$sql->DeleteAllRowFromTable($tableName);
	//		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function DeleteAllRowFromTable($tableName)
	{
		$txtzapr="DELETE FROM ".$tableName.";";
		$res=$this->sql_query($txtzapr);
		if ($res==false)
			{
				$this->echo_error();
				echo '<br>';
			}
			else
			{
				echo '������� ������ �� ������� '.$tableName.'<br>';
				$this->echo_affected_rows();
				echo '<br>';
			}
	}
	
	// *******************************************************************************************************
	// ����� � ������� ���������� �������
	// 		ShowTableResult($res)
	// ���������:
	// 		$res - ��������� �������
	// ��������:
	// 	
	// 
	// *******************************************************************************************************
	function ShowTableResult($res,$link_edit='',$column_edit='')
	{
		$result='<table border="0" cellspacing="2" cellpadding="3">';
		$str1=0;
		$cur_color=$this->color_td1;
		$i = 0;
		$result.= "<tr>";
		
		for ($i=0;$i<mysql_num_fields($res);$i++) 
			{
			//echo "Information for column $i:<br />\n";
			$meta = mysql_fetch_field($res, $i);
			if (!$meta) 
				{
				//echo "No information available<br />\n";
				}
				else
				{
					$result.= '<th bgcolor="'.$this->color_head.'"><font color="'.$this->color_text_head.'">'.$meta->name.'('.$meta->max_length.')'."</font></th>";
				}
			}
		if ($link_edit!='')
			{
			$result.= '<th bgcolor="'.$this->color_head.'">&nbsp;</th>';
			}
				
		$result.= "</tr>\r\n";

		while($strAr1=$this->sql_fetch_assoc())
			{
			$result.= "<tr>";
				
			if ($this->show_rows)
				{
					if ($cur_color==$this->color_td1) {$cur_color=$this->color_td2;} else {$cur_color=$this->color_td1;}
				}
				else
				{
					$cur_color=$this->color_td1;
				}	
				
			$column_val='';
		
			foreach($strAr1 as $key1=>$val)
				{
				$result.= '<td bgcolor="'.$cur_color.'">'.strval($val)."</td>";
				if ($column_edit!='')
					{
						if ($column_edit==$key1)
							{
								$column_val=$val;
							}
					}
				}
			if ($link_edit!='')
				{
					$link_edit_url=str_replace('{ID}',urlencode($column_val),$link_edit);
					$result.='<td bgcolor="'.$cur_color.'"><a href="'.$link_edit_url.'">Edit</a></td>';
				}
			$result.= "</tr>\r\n";
			}
		$result.= "</table";
		
		return $result;
	}
	
	// *******************************************************************************************************
	// �������� �������
	// 		ViewTable($tableName)
	// ���������:
	// 		$tableName - ��� ������� ���� ������
	// ��������:
	// 	
	// 		$tableName='testzakaz2';
	//		$tableLimit='100,50';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTable($tableName);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTable($tableName,$link_edit='',$column_id='')
	{
		$txtzapr="SELECT * FROM ".$tableName.";";
		$res=$this->sql_query($txtzapr);
		echo '�������� ������� MYSQL:'.$tableName."<br>\r\n";
		echo '���������� �������:'.strval($this->sql_numrows())."<br>\r\n";
		echo $this->ShowTableResult($res,$link_edit,$column_id);
	
	}
	
	// *******************************************************************************************************
	// �������� ������� � ������� ������
	// 		ViewTableLimit($tableName,$tableLimit)
	// ���������:
	// 		$tableName - ��� ������� ���� ������
	//		$tableLimit - ����� ������
	// ��������:
	// 	
	// 		$tableName='testzakaz2';
	//		$tableLimit='100,50';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableLimit($tableName,$tableLimit);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableLimit($tableName,$tableLimit)
	{
		$txtzapr="SELECT * FROM ".$tableName." LIMIT $tableLimit;";
		$res=$this->sql_query($txtzapr);
		echo '�������� ������� MYSQL:'.$tableName."<br>\r\n";
		echo '���������� �������:'.strval($this->sql_numrows())."<br>\r\n";
		echo $this->ShowTableResult($res);
	}
	
	// *******************************************************************************************************
	// �������� ������� 
	// 		ViewTableFunc($tableName,$tableAddString)
	// ���������:
	// 		$tableName - ��� ������� ���� ������
	//		$tableAddString - ����������� �������� � ������
	// ��������:
	// 	
	// 		$tableName='testzakaz2';
	//		$str_add='WHERE id=100';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableFunc($txt_zapros);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableFunc($tableName,$tableAddString)
	{
		$txtzapr="SELECT * FROM ".$tableName." ".$tableAddString.";";
		$res=$this->sql_query($txtzapr);
		//echo $txtzapr;
		echo '�������� ������� MYSQL:'.$tableName."<br>\r\n";
		echo '���������� �������:'.strval($this->sql_numrows())."<br>\r\n";
		echo $this->ShowTableResult($res);
	}
	
	// *******************************************************************************************************
	// �������� ������� �� ������ �������
	// 		ViewTableZapros($tableZapros)
	// ���������:
	// 		$tableZapros - �������� ����� �������
	// ��������:
	// 	
	// 		$txt_zapros="SELECT t1.id AS id,t1.userid, t2.name AS torgpred, t1.pokupid, t3.name AS pokupname, t1.summa AS summa FROM oformlenzakaz AS t1, testuser AS t2, testkont AS t3 WHERE t1.userid=t2.id AND t1.pokupid=t3.id ORDER BY t1.id";
	//		$str_where='WHERE id=100';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableZapros($txt_zapros);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableZapros($tableZapros)
	{
		$txtzapr=$tableZapros; // "SELECT * FROM ".$tableName." ".$tableAddString.";";
		$res=$this->sql_query($txtzapr);
		echo '����� �������:'.$tableZapros."<br>\r\n";
		echo '���������� �������:'.strval($this->sql_numrows())."<br>\r\n";
		echo $this->ShowTableResult($res);
	}
	
	// *******************************************************************************************************
	// �������� ����� �� ������� �������
	// 		DeleteRowTable($tableName,$txtAdd='')
	// ���������:
	//		$tableName - ��� ������� �� ���� ������
	// 
	// ��������:
	// 	
	// 		$tableName='testzakaz2';
	//		$str_where='WHERE id=100';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->DeleteRowTable($tableName,$str_where);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function DeleteRowTable($tableName,$txtAdd='')
	{
		$txtzapr="DELETE FROM ".$tableName." ".$txtAdd.";";
		$res=$this->sql_query($txtzapr);
		//echo mysql_info();
		if ($res==false)
			{
				$this->echo_error();
				echo '<br>';
			}
			else
			{
				echo '������� ������ �� ������� '.$tableName.'<br>';
				$this->echo_affected_rows();
				echo '<br>';
			}
	}
	
	function GetMinMaxPage($maxPages,$curPage,$numPages) // �������� � ���� ������� ����� ������ �������
	{
		$retArr=array();
		$simbol1='';
		$simbol2='';
		$minPage=$curPage-$numPages;
		$maxPage=$curPage+$numPages;
		if ($minPage<0) {$minPage=0;} else {$simbol1='&lt;&lt;';}
		if ($maxPage>$maxPages) {$maxPage=$maxPages;} else {$simbol2='&gt;&gt;';}
		for($a=$minPage;$a<=$maxPage;$a++)
			{
			if (($a==$minPage) && ($simbol1!='')) {$retArr[$a]=$simbol1;}
			elseif (($a==$maxPage) && ($simbol2!='')) {$retArr[$a]=$simbol2;}
			else
				{$retArr[$a]=strval($a+1);}
			}
		return $retArr;
	}
	// *******************************************************************************************************
	// �������� ������� � ������ �� �������
	// 		ViewTableAndPages($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='')
	// ���������:
	// 		$tableName - ��� ������� �� ���� ������
	//		$curPage1 - ����� ������� ������
	//		$maxPage1 - ������������ ���������� ����� �� ���� ��������
	//		$urlPage - ������ �� �������� ��� {PAGE} ���������� �� ����� ������
	//				������: $urlPage='admin.php?c=viewkontr&page={PAGE}';
	//
	//		$maxUrl - (�� ��������� 10)
	//		$txtParam - �������������� ��������� ��� ������� (�� ��������� ������ ������)		
	//
	// ��������:
	// 		$tableName='testzakaz2';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableAndPages($tableName,$reg_page,'100','admin.php?c=viewzak3&page={PAGE}',13);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableAndPages($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='')
	{	
		// ������ ���������� �������
		if (!isset($curPage1)) {$curPage1=0;}
		$txtzapr="SELECT * FROM $tableName $txtParam";
		$res=$this->sql_query($txtzapr);
		$num_row=$this->sql_numrows();
		$this->sql_freeresult();
		// free result $this->sql_
		
		$maxRow=intval($num_row);
		$maxPages=intval($maxRow / $maxPage1);
		$curPage=intval($curPage1 / $maxPage1);
		$linksPages='|';
		$arrPages=$this->GetMinMaxPage($maxPages,$curPage,$maxUrl);

		foreach($arrPages as $key=>$numPage)
			{
			if ($curPage!=$key)
				{
					$cur_url=str_replace('{PAGE}',strval($key*$maxPage1),$urlPage);
					$linksPages.=' <a href="'.$cur_url.'">'.$numPage.'</a> |';
				}
				else
				{
					$linksPages.=' <b> '.$numPage.' </b> |';
				}
			}
		echo $linksPages.'<br>';
		$this->ViewTableFunc($tableName,$txtParam.' LIMIT '.$curPage1.','.$maxPage1);
		
	}
	// *******************************************************************************************************
	// �������� ������� � ������ �� �������
	// 		ViewTableAndPages($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='')
	// ���������:
	// 		$tableName - ��� ������� �� ���� ������
	//		$curPage1 - ����� ������� ������
	//		$maxPage1 - ������������ ���������� ����� �� ���� ��������
	//		$urlPage - ������ �� �������� ��� {PAGE} ���������� �� ����� ������
	//				������: $urlPage='admin.php?c=viewkontr&page={PAGE}';
	//
	//		$maxUrl - (�� ��������� 10)
	//		$txtParam - �������������� ��������� ��� ������� (�� ��������� ������ ������)		
	//
	// ��������:
	// 		$tableName='testzakaz2';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableAndPages($tableName,$reg_page,'100','admin.php?c=viewzak3&page={PAGE}',13);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableZaprosAndPages($txtZapros,$curPage1,$maxPage1,$urlPage,$maxUrl=10)
	{	
		// ������ ���������� �������
		if (!isset($curPage1)) {$curPage1=0;}
		$txtzapr=$txtZapros;
		$res=$this->sql_query($txtzapr);
		$num_row=$this->sql_numrows();
		$this->sql_freeresult();
		// free result $this->sql_
		
		$maxRow=intval($num_row);
		$maxPages=intval($maxRow / $maxPage1);
		$curPage=intval($curPage1 / $maxPage1);
		$linksPages='|';
		$arrPages=$this->GetMinMaxPage($maxPages,$curPage,$maxUrl);

		foreach($arrPages as $key=>$numPage)
			{
			if ($curPage!=$key)
				{
					$cur_url=str_replace('{PAGE}',strval($key*$maxPage1),$urlPage);
					$linksPages.=' <a href="'.$cur_url.'">'.$numPage.'</a> |';
				}
				else
				{
					$linksPages.=' <b> '.$numPage.' </b> |';
				}
			}
		echo $linksPages.'<br>';
		$this->ViewTableZapros($txtZapros.' LIMIT '.$curPage1.','.$maxPage1);
		
	}
	
	// *******************************************************************************************************
	// ������������� ������� �� ������� � �������� �����������
	// 		ViewTableAndPagesGruppColumn($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='',$columnId,$showGrupp=true)
	// ���������:
	// 		$tableName - ��� ������� �� ���� ������
	//		$curPage1 - ����� ������� ������
	//		$maxPage1 - ������������ ���������� ����� �� ���� ��������
	//		$urlPage - ������ �� �������� ��� {PAGE} ���������� �� ����� ������
	//				������: $urlPage='admin.php?c=viewkontr&page={PAGE}';
	//
	//		$maxUrl - (�� ��������� 10)
	//		$txtParam - �������������� ��������� ��� ������� (�� ��������� ������ ������)		
	//		$columnId - �������� ������� �� ������� ���� �����������
	//		$showGrupp - ���������� ��� �� ���������� ������ �� ��������
	//
	// ��������:
	// 		$tableName='testzakaz2';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableAndPagesGruppColumn($tableName,$reg_page,'100','admin.php?c=viewzakazarh&page={PAGE}&r='.$rnd1,13,'','nomerzakaz',false);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function ViewTableAndPagesGruppColumn($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='',$columnId,$showGrupp=true)
	{	
		// ������ ���������� �������
		if (!isset($curPage1)) {$curPage1=0;}
		$txtzapr="SELECT $columnId FROM $tableName $txtParam GROUP BY $columnId ORDER BY $columnId";
		$res=$this->sql_query($txtzapr);
		$arrPages=array();
		while($row=$this->sql_fetch_row())
			{
				$arrPages[]=$row;
			}
		$num_row=$this->sql_numrows();
		$this->sql_freeresult();
		// free result $this->sql_
		
		//$maxRow=intval($num_row);
		//$maxPages=intval($maxRow / $maxPage1);
		//$curPage=intval($curPage1 / $maxPage1);
		$linksPages='|';
		//$arrPages=$this->GetMinMaxPage($maxPages,$curPage,$maxUrl);
		if (count($arrPages)>0) {$curColumnId=$arrPages[0][0];}
		foreach($arrPages as $key=>$numPage)
			{
			if (intval($curPage1)!=intval($key))
				{
					$cur_url=str_replace('{PAGE}',strval($key),$urlPage);
					$linksPages.=' <a href="'.$cur_url.'">'.$numPage[0].'</a> |';
				}
				else
				{
					$linksPages.=' <b> '.$numPage[0].' </b> |';
					$curColumnId=$numPage[0];
				}
			}
		if ($showGrupp) {echo $linksPages.'<br>';}
		
		if ($curColumnId=='') {$txtParam1='';} else {$txtParam1=' WHERE '.$columnId.'='.$curColumnId;}
		$this->ViewTableFunc($tableName,$txtParam.$txtParam1);
		
	}
	
	// *******************************************************************************************************
	// ���������� � ���� ������������������ ������� ������ ��������������� � ��������������� �� ������� $columnName
	// 		GetArrGruppRow($tableName,$columnName,$txtParam='')
	// ���������:
	// 		$tableName - ��� ������� �� ���� ������
	//		$curPage1 - ����� ������� ������
	//		$maxPage1 - ������������ ���������� ����� �� ���� ��������
	//		$urlPage - ������ �� �������� ��� {PAGE} ���������� �� ����� ������
	//				������: $urlPage='admin.php?c=viewkontr&page={PAGE}';
	//
	//		$maxUrl - (�� ��������� 10)
	//		$txtParam - �������������� ��������� ��� ������� (�� ��������� ������ ������)		
	//		$columnId - �������� ������� �� ������� ���� �����������
	//		$showGrupp - ���������� ��� �� ���������� ������ �� ��������
	//
	// ��������:
	// 		$tableName='testzakaz2';
	// 		$sql=new sql_db($sql_host,$sql_user,$sql_pass,$sql_db_name);
	// 		$sql->ViewTableAndPagesGruppColumn($tableName,$reg_page,'100','admin.php?c=viewzakazarh&page={PAGE}&r='.$rnd1,13,'','nomerzakaz',false);
	// 		$sql->sql_close();
	// 
	// *******************************************************************************************************
	function GetArrGruppRow($tableName,$columnName,$txtParam='')
	{
		$txtzapr="SELECT $columnName FROM $tableName $txtParam GROUP BY $columnName ORDER BY $columnName";
		$res=$this->sql_query($txtzapr);
		$arrPages=array();
		while($row=$this->sql_fetch_row())
			{
				$arrPages[]=$row[0];
			}
		$this->sql_freeresult();
		return $arrPages;
	}
	
	// *******************************************************************************************************
	// ���������� � ������ HTML ������ �� ������ ����� 
	// GetLinksGruppZapros($txtZapros,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$columnId,$columnPage,$columnTitle='')
	// ���������
	//		$txtZapros - ����� �������
	// 		$curPage1 - ������� ����� ������
	//		$maxPage1 - ������������ ���������� ������
	//		$urlPage - ����� ������ ��� {PAGE} ���������� �� ������ ������
	//		$maxUrl - ������������ ���������� ������
	//		$columnId - ������������ �������
	//		$columnPage - 
	//		$columnTitle - 
	//		
	// *******************************************************************************************************
	
	function GetLinksGruppZapros($txtZapros,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$columnId,$columnPage,$columnTitle='')
	{
		if (!isset($curPage1)) {$curPage1=0;}
		$txtzapr=$txtZapros;
		$res=$this->sql_query($txtzapr);
		$arrPages=array();
		while($row=$this->sql_fetch_assoc())
			{
				$arrPages[]=$row;
			}
		$num_row=$this->sql_numrows();
		$this->sql_freeresult();
		$linksPages='|';
		if (count($arrPages)>0) {$curColumnId=$arrPages[0][0];}
		foreach($arrPages as $key=>$numPage)
			{
			if ($curPage1!=$numPage[$columnPage])
				{
					$cur_url=str_replace('{PAGE}',urlencode($numPage[$columnPage]),$urlPage);
					
					$linksPages.=' <a href="'.$cur_url.'" ';
					if ($columnTitle!='') {$linksPages.='title="'.$numPage[$columnTitle].'"';}
					$linksPages.='>'.$numPage[$columnPage].'</a> |';
				}
				else
				{
					$linksPages.=' <b ';
					if ($columnTitle!='') {$linksPages.='title="'.$numPage[$columnTitle].'"';}
					$linksPages.='> '.$numPage[$columnPage].' </b> |';
					$curColumnId=$numPage[$columnPage];
				}
			}
		return $linksPages;
	}
	
	function GetLinksGrupp($tableName,$curPage1,$maxPage1,$urlPage,$maxUrl=10,$txtParam='',$columnId)
	{
		return $this->GetLinksGruppZapros("SELECT $columnId FROM $tableName $txtParam GROUP BY $columnId ORDER BY $columnId",
		$curPage1,$maxPage1,$urlPage,$maxUrl,$columnId,$columnId,$columnId);
	}
	
	
	function GetValueFromTable($columnNum,$tableName,$txtZaprosAdd)
	{
		$txtzapr="SELECT $columnNum FROM $tableName $txtZaprosAdd";
		$res=$this->sql_query($txtzapr);
		//echo $txtZapros;
		$row=$this->sql_fetch_assoc(); // ������� ������ ���� ������
		$this->sql_freeresult();
		if ($row==false) {return '';} else {return $row[$columnNum];}
	}
	
	function GetRowFromTable($tableName,$txtZaprosAdd)
	{
		$txtzapr="SELECT * FROM $tableName $txtZaprosAdd";
		$res=$this->sql_query($txtzapr);
		//echo $txtZapros;
		$row=$this->sql_fetch_assoc(); // ������� ������ ���� ������
		$this->sql_freeresult();
		if ($row==false) {return false;} else {return $row;}
	}
	
	function SaveArrayToTable($arr,$tableName,$varChar=true)
	{
		foreach($arr as $row)
			{
				$txtZapr='INSERT INTO '.$tableName.' VALUES(';
				foreach ($row as $key=>$col)
					{
						if ($varChar) {$txtZapr.="'";}
						$txtZapr.=$col;
						if ($varChar) {$txtZapr.="'";}
						if ($key<(count($row)-1))
							{
								$txtZapr.=',';
							}
							
					}
				$txtZapr.=');';
				//echo $txtZapr;
				$res=$this->sql_query($txtZapr);
				if ($res==false)
					{
						$this->echo_error();
					}
				
			}
		$this->sql_freeresult();
	}
	
	function add()
	// �������� �������� � ������� MySQL
	{
		$this->getdatalist();	$this->getvaluelist();
		$sql="insert into $this->table($this->datalist) values($this->valuelist)";
		$this->sql_query($sql);	
	}

	function update($id)
	// �������� �������� � ������� MySQL � �������� $this->primarykey='$id'
	{
		$this->getupdatelist();
		$sql="update $this->table set $this->updatelist where $this->primarykey='$id'";
		$this->sql_query($sql);
	}	

	function delete($id)
	// ������� �������� � ������� MySQL � �������� $this->primarykey='$id'
	{	
		$sql="delete from $this->table where $this->primarykey='$id'";
		$this->sql_query($sql);
	}
	
	function getList()
	// �������� ��������� ���������� ������� MySQL SELECT
	{
		$this->getdatalist();	$this->getwherelist();	$this->getorderlist();
		$sql="select $this->datalist from $this->table $this->wherelist $this->orderlist $this->limit";
		$result=$this->sql_query($sql);
		$this->wherelist='';
		$this->orderlist='';
		$this->limit='';
		return $result;			
	}
	
	function getArrayList()
	// �������� ������ �������� ����� ���������� ������� MySQL SELECT
	{
		$result=$this->getList();
		$resArray=array();
		if ($this->sql_numrows()>0)
			{
				while($myrow=$this->sql_fetch_row()) {$resArray[]=$myrow;}	
			}
		$this->sql_freeresult();
		return $resArray;
	}
	
	function getAssocArrayList()
	// �������� ��������������� ������ �������� ����� ���������� ������� MySQL SELECT
	{
		$result=$this->getList();
		$resArray=array();
		if (mysql_num_rows($result)>0)
			{
				while($myrow=$this->sql_fetch_assoc()) {$resArray[]=$myrow;}	
			}
		$this->sql_freeresult();
		return $resArray;
	}
	
	function getDetail($id)
	// �������� ��������� ���������� ������� MySQL SELECT � �������� $this->primarykey='$id'
	{
		$this->getdatalist();	
		$sql="select $this->datalist from $this->table where $this->primarykey='$id'";
		$result=$this->sql_query($sql);
		return $result;
	}
	
	function getdatalist()
	// �������� � ���� ������ ������ �� ������� $this->data ��� ������� � ������ MySQL
	{
		$this->datalist="";
		for($i=0;$i<count($this->data)-1;$i++){	$this->datalist.=$this->data[$i].",";	}
		$this->datalist.=$this->data[$i];
	}

	function getvaluelist()
	// �������� � ���� ������ ������ �� ������� $this->value ��� ������� � ������ MySQL
	{
		$this->valuelist="";$this->valuelist="'";
		for($i=0;$i<count($this->value)-1;$i++){ $this->valuelist.=$this->value[$i]."','";	}
		$this->valuelist.=$this->value[$i]."'";
	}
	
	function getupdatelist()
	// �������� � ���� ������ ������ �� ������� $this->data = $this->value ��� ������� � ������ MySQL
	{
		$this->updatelist="";
		for($i=0;$i<count($this->value)-1;$i++){ $this->updatelist.=$this->data[$i]."='".$this->value[$i]."',";	}
		$this->updatelist.=$this->data[$i]."='".$this->value[$i]."'";
	}
	
	function getwherelist()
	// �������� � ���� ������ ������ �� ������ ������� $this->where ��� ������� � ������ MySQL
	{
		if($this->where!=""){	$this->wherelist="where $this->where";	}else{ $this->wherelist=""; }
	}

	function getorderlist()
	// �������� � ���� ������ ������ �� ������ ���������� ����� $this->order ��� ������� � ������ MySQL
	{
		if($this->order!=""){	$this->orderlist="order by $this->order";	}else{ $this->orderlist=""; }
	}	

} // class sql_db

?>