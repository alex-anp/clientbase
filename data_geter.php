<?php
/*
+ ----------------------------------------------------------------------------------------------+
|     e107 website system  : http://e107.org.ru
|     Released under the terms and conditions of the GNU General Public License (http://gnu.org).
|
|     Plugin "clientbase"
|     Author: Alex ANP alex-anp@ya.ru
|     Home page: http://code.google.com/p/clientbase/
+-----------------------------------------------------------------------------------------------+
*/

require_once("../../class2.php");

$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

$users = '';
$revs  = '';
$limit = '';

if(e_QUERY) {
	$qs = explode(".", e_QUERY);
	if($qs[0]) {
		$action = array_shift($qs);
	} else {
		$action = "Init";
	}
}

function getData_1($form_data) {
	global $sql, $limit;


	$dtext = "";
	if(!is_object($sql)){ $sql = new db; }
	$wtext = getWhereStr($form_data);
	$total = getTotal($form_data);
	//echo '<!-- SQL WHERE '.$wtext.' -->';
	if (!$sql->db_Select("clientbase", "*", $wtext)) {
		$dtext .= '<tr><td class="forumheader3" colspan="10" style="text-align:center;">'.CBASE_L0031.'</td></tr>';
	} else {
	    while($row = $sql->db_Fetch()) {
	        $dtext .= '<tr>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['rate'].'</td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'"><a href="?Edit.'.$row['id'].'" title="'.CBASE_L0030.'">'.($row['name'] ? $row['name'] : CBASE_L0013).'</a></td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['phone'].'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['contact_person'].'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'"><a href="http://'.$row['website'].'" target="_blank" title="'.CBASE_L0028.' '.$row['website'].'">'.cutText($row['website'],13).'</a></td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'"><a href="mailto:'.$row['email'].'" title="'.CBASE_L0029.' '.$row['email'].'">'.cutText($row['email'], 13).'</a></td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.getUserName($row['owner_id']).($form_data['sn'] ? '<br/>'.$row['ip_addr'].'': '').'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.getSTime($row['run_time']).'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['memo'].'</td>';
	        if ($form_data['sn']) {
	        	$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">r'.($total - $i).'</td>';
	        } else {
	        	$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'"><a href="?Diff.'.$row['sn'].'" title="'.CBASE_L0027.'">r'.getRev($row['sn']).'</a></td>';
	        }
	        $dtext .= '</tr>';
	        $i++;
		}
		if (!$form_data['sn']) {
			$dtext .= '<tr><td class="fcaption" colspan="10">' .
					'<div style="float:right;">'.CBASE_L0003.'</div>' .
					'<div style="text-align:left;">'.CBASE_L0034.': '.$total.' ('.$limit.')</div>' .
					'</td></tr>';
		}
	}
	//$dtext .= '<tr><td class="fcaption" colspan="10">'.$wtext.'</td></tr>';
	return $dtext;
}

function getData($form_data) {
	global $sql, $limit;

	echo var_dump($form_data);


	$dtext = "";
	if(!is_object($sql)){ $sql = new db; }
	$wtext = getWhereStr($form_data);
	$total = getTotal($form_data);
	//echo '<!-- SQL WHERE '.$wtext.' -->';
	if (!$sql->db_Select("clientbase", "*", $wtext)) {
		$dtext .= '<tr><td class="forumheader3" colspan="11" style="text-align:center;">'.CBASE_L0031.'</td></tr>';
	} else {
	    while($row = $sql->db_Fetch()) {
	        $dtext .= '<tr>';
			$dtext .= '<td class="forumheader3" style="text-align:right; background-color:'.getColor($row['rate']).'">'.$row['sn'].'</td>';
			$dtext .= '<td class="forumheader3" style="text-align:center; background-color:'.getColor($row['rate']).'"><a href="?Edit.'.$row['id'].'" title="'.CBASE_L0030.'">'.$row['serial_no'].'</a></td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['sale_date'].'</td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['contact_person'].'</td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['address'].'</td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['phone'].'</td>';
			$dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['dealer_name'].'</td>';
			$dtext .= '<td class="forumheader3" style="text-align:center; background-color:'.getColor($row['rate']).'">'.$row['rate'].'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.$row['memo'].'</td>';
	        $dtext .= '<td class="forumheader3" style="background-color:'.getColor($row['rate']).'">'.getSTime($row['run_time']).'</td>';
	        if ($form_data['sn']) {
	        	$dtext .= '<td class="forumheader3" style="text-align:center; background-color:'.getColor($row['rate']).'">r'.($total - $i).'</td>';
	        } else {
	        	$dtext .= '<td class="forumheader3" style="text-align:center; background-color:'.getColor($row['rate']).'"><a href="?Diff.'.$row['sn'].'" title="'.CBASE_L0027.'">r'.getRev($row['sn']).'</a></td>';
	        }
	        $dtext .= '</tr>';
	        $i++;
		}
		if (!$form_data['sn']) {
			$dtext .= '<tr><td class="fcaption" colspan="11">' .
					'<div style="float:right;">'.CBASE_L0003.'</div>' .
					'<div style="text-align:left;">'.CBASE_L0034.': '.$total.' ('.$limit.')</div>' .
					'</td></tr>';
		}
	}
	//$dtext .= '<tr><td class="fcaption" colspan="10">'.$wtext.'</td></tr>';
	return $dtext;
}

function getTotal($form_data) {
	global $sql;
	$where_str = getWhereStr($form_data);
	list($where_str, $limit) =  explode("LIMIT", getWhereStr($form_data));
	return $sql->db_Count("clientbase", "(*)", "WHERE ".$where_str );
}

function setUsers() {
	global $users;
	$users = array();
	if(!is_object($mysql)){ $mysql = new db; }
	$mysql->db_Select('user', 'user_id, user_name', '1');
	while($row = $mysql->db_Fetch()) {
		$users[$row['user_id']] = $row['user_name'];
	}
}

function getUserName($id) {
	global $users;
	if (!is_array($users)) {
		setUsers();
	}
	return $users[$id];
}
if ($action == 'Get') {
	echo getData();
}

function getSTime($time) {
	//$format = '%y.%m.%d';
	$format = '%d.%m.%y';
	return strftime($format, strtotime($time));
}

function getRev($sn) {
	global $revs;
	if(!is_array($revs)){
		setRevs();
	}
	return $revs[$sn];
}

function setRevs() {
	global $revs;
	$users = array();
	if(!is_object($mysql)){ $mysql = new db; }
	$mysql->db_Select_gen("SELECT sn, count(id) rev FROM #clientbase GROUP BY sn");
	while($row = $mysql->db_Fetch()) {
		$revs[$row['sn']] = $row['rev'];
	}
}
function getWhereStr($form_data) {
	global $sort_fld, $page, $tp, $limit;
	$flds = array(
		'rate'        => 'rate DESC',
		'name'        => 'name',
		'phone'       => 'phone',
		'contact'     => 'contact_person',
		'www'         => 'website',
		'email'       => 'email',
		'owner'       => 'owner_id',
		'time'        => 'run_time DESC',
		'memo'        => 'memo',
		'rev'         => 'rev',
		'sn'          => 'sn',
		'serial_no'   => 'serial_no',
		'sale_date'   => 'sale_date',
		'dealer_name' => 'dealer_name',
	);
	$flds[$sort_fld] ? $fld = $flds[$sort_fld] : $fld = "run_time DESC";
	$page > 1 ? $limit = "".(CBASE_LIMIT * ($page-1)).", ".CBASE_LIMIT."" : $limit = CBASE_LIMIT;
	$wtext = '';
	if ($form_data['sn']) {
		$wtext = "sn = ".$form_data['sn']."";
		if (!$form_data['diff']) {
			$wtext .= " AND run_time <= '".date("Y-m-d H:i:s")."' AND '".date("Y-m-d H:i:s")."' < die_time";
		}
		return $wtext;
	} else {
		foreach ($form_data as $k => $v) {
			if ($k == 'rate' || $k == 'owner_id' || $k == 'sn' ) {
				$wtext .= "".$k." = ".$v." AND ";
			} elseif ($k == 'time_from') {
				$wtext .= "run_time >= '".$v." 00:00:00' AND ";
			} elseif ($k == 'time_to') {
				$wtext .= "run_time <= '".$v." 23:59:59' AND ";
			} else {
				$wtext .= "LOCATE('".$tp->toDB($v)."', ".$k.") AND ";
			}
		}
		return $wtext."run_time <= '".date("Y-m-d H:i:s")."' AND '".date("Y-m-d H:i:s")."' < die_time ORDER BY ".$fld." LIMIT ".$limit."";
	}
}

function cutText($text, $size) {
	if (strlen($text) > $size) {
		$text = substr($text, 0, $size).'|';
	}
	return $text;
}

function getColor($id) {
	$HOT_COLOR = array(
		'0e8a07', //Страсти накаляются
		'208008', //  от зеленого
		'377208',
		'516408',
		'6f5308',
		'8d4308',
		'a93307',
		'c32307',
		'db1708',
		'ee0c08', //  до красного
	);
	/*$HOT_COLOR = array(
		'FFFFFF', //  от Вадима
		'FFFFFF', //  почти все белое
		'FFFFFF',
		'FFFFFF',
		'FFFFFF',
		'FFFFFF',
		'FFFF99', //  децл желтенькое
		'FFFF99',
		'FFCCCC',
		'FFCCCC', //  и немного красное
	);*/
	return '#'.$HOT_COLOR[$id].';';
}

?>
