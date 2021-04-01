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
require_once("data_geter.php");

$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

/*============================ Prepare ==================================*/
$action = "Mine";
$id       = 0;
$error    = '';
$error_id = 0;
$sort_fld = 'time';
$page     = 1;


if(e_QUERY) {
	list($action, $id, $error, $error_id, $sort_fld, $page) = explode(".", e_QUERY);
	$action   = ($action   ? $action   : "Mine");
	$id       = ($id       ? $id       : 0);
	$error    = ($error    ? $error    : '');
	$error_id = ($error_id ? $error_id : 0);
	$sort_fld = ($sort_fld ? $sort_fld : 'time');
	$page     = ($page     ? $page     : 1);
}

session_start();
$bdt       = array();
$form_data = $_SESSION['_cbase_form_data_'] ? $_SESSION['_cbase_form_data_'] : array();
if (isset($_POST['search']) && USER) {
	unset($_SESSION['_cbase_form_data_']);
	foreach (array('rate','name','contact_person','phone','website','email','owner_id','memo','time_from','time_to') as $key) {
		if ($_POST[$key] || is_numeric($_POST[$key])) { 
			$form_data[$key] = $_POST[$key]; //$tp->toDB($_POST[$key]); 
		} else {
			unset($form_data[$key]);
		}
	}
	$_SESSION['_cbase_form_data_'] = $form_data;
}
//echo var_dump($_SESSION['_cbase_form_data_']);

/*============================ Add Sctipt ==================================*/
if (isset($_POST['add']) && USER) {
	if(!is_object($sql)){ $sql = new db; }
	$_POST['sn'] ? $sn = $_POST['sn'] : $sn = getNewSn(); 
	$owner_id = USERID;
	//$_POST['owner_id'] ? $owner_id = $_POST['owner_id'] : $owner_id = USERID;
	$values = array(
		"sn"             => $tp->toDB($sn),
		"name"           => $tp->toDB(checkPost($_POST['name'])),
		"contact_person" => $tp->toDB(checkPost($_POST['contact_person'])),
		"address"        => $tp->toDB(checkPost($_POST['address'])),
		"phone"          => $tp->toDB(checkPost($_POST['phone'])),
		"phone_ext"      => $tp->toDB(checkPost($_POST['phone_ext'])),
		"website"        => $tp->toDB(checkPost($_POST['website'])),
		"email"          => $tp->toDB(checkPost($_POST['email'])),
		"rate"           => $tp->toDB(checkPost($_POST['rate'])),
		"memo"           => $tp->toDB(checkPost($_POST['memo'])),
		"owner_id"       => $tp->toDB($owner_id),
		"ip_addr"        => $tp->toDB($_SERVER["REMOTE_ADDR"]),
		"run_time"       => $tp->toDB(date("Y-m-d H:i:s")),
		"die_time"       => $tp->toDB('2500-01-01 00:00:00'),
	);
	if ($_POST['name'] && $_POST['phone'] && is_numeric($_POST['rate'])) {
		$sql->db_Update("clientbase", "die_time='".date("Y-m-d H:i:s")."' WHERE sn='$sn'");
		$sql->db_Insert("clientbase", $values);
		$id = getLastId();
		$error_id = 0;
	} else {
		$error_id = 1;
	}
	header("location:".e_SELF."?View.0.Error.".$error_id."");
	//$action = 'View';
}
/*============================ Main Page ==================================*/
if ($action == 'Mine' && USER) {
	$form_data = array();
	$action = 'View';
}


/*============================ Add Form ==================================*/
if ($action == 'Add' && USER) {
	$form_text = '<h3>'.CBASE_LM003.'</h3>' .
			''.getEditForm(array());
}
/*============================ Edit Form ==================================*/
if ($action == 'Edit' && USER) {
	if(!is_object($sql)){ $sql = new db; }
	if (!$sql->db_Select("clientbase", "*", "id = ".$id."")) {
		$form_text .= '<div style="text-align:center;">'.CBASE_L0022.'</div>';
	} else {
	    while($row = $sql->db_Fetch()) {
			$item = $row;
			$form_text = '<h3>'.CBASE_L0030.'</h3>'.getEditForm($item);
		}	
		$r_count = $sql->db_Count("clientbase", "(*)", "WHERE sn = ".$item['sn']."");
	}	
	$form_text = '<div style="text-align:right;"><a href="?Diff.'.$item['sn'].'">'.CBASE_L0023.': '.$r_count.'</a></div>'.$form_text;
}
/*============================ Differents ==================================*/
if ($action == 'Diff' && USER) {
	$form_data['sn'] = $id;
	$form_text .= '<h3>'.CBASE_L0027.'</h3>';
	$bdt['hidden_search_form'] = 1;
	$action = 'View';
}

/*============================ ToDo List ==================================*/
if ($action == 'ToDo' && USER) {
	$form_data = array();
	$form_data['memo'] = ''.date('d.m.Y');
	$form_text .= '<h3>'.CBASE_L0042.'</h3>';
	//$bdt['hidden_search_form'] = 1;
	$action = 'View';
}

/*============================ Ext Search ==================================*/
if ($action == 'Search' && USER) {
	$form_text .= '<h3>'.CBASE_L0041.'</h3>' .
			''.getExtSearchForm().'';
}

/*============================ View ==================================*/
if ($action == 'View' && USER) {
	$form_text .= ''.($error_id != 0 ? ''.getErrorText($error_id) : '').'';
	$form_text .= '
	<form name="filtr" method="post" action="'.e_SELF.'?View">
		<table style="width: 100%;" class="fborder">
    		<colgroup>
	    		<col style="width: 2%;">
	    		<col style="width: 15%;">
	    		<col style="width: 8%;">
	    		<col style="width: 15%;">
	    		<col style="width: 12%;">
	    		<col style="width: 12%;">
	    		<col style="width: 8%;">
	    		<col style="width: 6%;">
	    		<col style="width: 20%;">
	    		<col style="width: 2%;">
    		</colgroup>
			'.($bdt['hidden_search_form'] ? '' : getSearchForm()).'
			<tr>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....rate" title="'.CBASE_L0037.' '.CBASE_L0033.'">'.CBASE_L0033.'</a>&nbsp;'.CBASE_LH001.'</td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....name" title="'.CBASE_L0037.' '.CBASE_L0013.'">'.CBASE_L0013.'</a>&nbsp;'.CBASE_LH001.'</td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....phone" title="'.CBASE_L0037.' '.CBASE_L0010.'">'.CBASE_L0010.'</a>&nbsp;'.CBASE_LH001.'</td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....contact" title="'.CBASE_L0037.' '.CBASE_L0014.'">'.CBASE_L0014.'</a></td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....www" title="'.CBASE_L0037.' '.CBASE_L0017.'">'.CBASE_L0017.'</a><br/>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....email" title="'.CBASE_L0037.' '.CBASE_L0016.'">'.CBASE_L0016.'</a><br/>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....owner" title="'.CBASE_L0037.' '.CBASE_L0018.'">'.CBASE_L0018.'</a></td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....time" title="'.CBASE_L0037.' '.CBASE_L0019.'">'.CBASE_L0019.'</a></td>
	    		<td class="fcaption" style="text-align: center;"><a href="?View....memo" title="'.CBASE_L0037.' '.CBASE_L0020.'">'.CBASE_L0020.'</a></td>
	    		<td class="fcaption" style="text-align: center;">'.CBASE_L0035.'</td>
	    	</tr>
				'.getData($form_data).'
		</table>
	</form>
	'.getPager($form_data).'
	';
}

/*============================ Stat ==================================*/
if ($action == 'Stat' && USER) {
	$form_text .= '<h3>'.CBASE_L0039.'</h3>
				<div align="center">
				  <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="800" height="400" >
				    <param name="movie" value="'.e_PLUGIN.'clientbase/charts/swf/FCF_MSArea2D.swf" />
				    <param name="FlashVars" value="&dataURL='.e_PLUGIN.'clientbase/xml_data_geter.php&chartWidth=800&chartHeight=400">
				    <param name="quality" value="high" />
				    <embed src="'.e_PLUGIN.'clientbase/charts/swf/FCF_MSArea2D.swf" width="800" height="400" flashVars="&dataURL='.e_PLUGIN.'clientbase/xml_data_geter.php&chartWidth=800&chartHeight=400" quality="high" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				  </object>
				</div>
			';
}

if (!$form_text) {
	$form_text = '<div style="text-align:center;">'.CBASE_L0026.'</div>';
}

require_once(HEADERF);

$ns -> tablerender(PAGE_NAME, $form_text);

require_once(FOOTERF);

function getEditForm($item){
	for ($i=0; $i<10; $i++) {
		$selector .= '<option value="'.$i.'" '.($item['rate'] == $i ? 'selected="selected"' : '').'>'.$i.'</option>';
	}
	$ftext = '
		<form name="add" method="post">
		<table style="width: 100%;" class="fborder">
		<tr>
			<td class="forumheader3">'.CBASE_L0013.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="name" name="name" value="'.$item['name'].'" size="80">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0014.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="contact_person" name="contact_person" value="'.$item['contact_person'].'"  size="60">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0015.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="address" name="address" value="'.$item['address'].'" size="80">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0010.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="phone" name="phone" value="'.$item['phone'].'" size="14" maxlength="10">
				* <input class="tbox" type="text" id="phone_ext" name="phone_ext" value="'.$item['phone_ext'].'" size="6" maxlength="4">
			</td>
		</tr>		
		<tr>
			<td class="forumheader3">'.CBASE_L0017.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="website" name="website" value="'.$item['website'].'" size="60">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0016.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="email" name="email" value="'.$item['email'].'" size="60">
			</td>
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0021.'</td>
			<td class="forumheader3">
				<select class="tbox" id="rate" name="rate">
					'.$selector.'
				</select>				
			</td>
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0020.'</td>
			<td class="forumheader3">
				<textarea class="tbox" rows="5" cols="80" id="memo" name="memo">'.$item['memo'].'</textarea>
			</td>
		</tr>	
		<tr>
			<td class="forumheader3" colspan="2" align="center">
				<input class="button" type="submit" name="add" value="'.CBASE_L0024.'">
				<input class="button" type="reset" onclick="history.back();" value="'.CBASE_L0036.'">
				<input type="hidden" name="sn" value="'.$item['sn'].'">
				<input type="hidden" name="owner_id" value="'.$item['owner_id'].'">
			</td>
		</tr>	
		</table>
	</form>
	';	
	return $ftext;	
}

function getSearchForm(){
	global $form_data;
	$ftext = '<tr>
		<td class="fcaption"><input class="tbox" type="text" id="rate" name="rate" value="'.$form_data['rate'].'" size="2" maxlength="1"></td>
		<td class="fcaption"><input class="tbox" type="text" id="name" name="name" value="'.$form_data['name'].'" size="22"></td>
		<td class="fcaption"><input class="tbox" type="text" id="phone" name="phone" value="'.$form_data['phone'].'" size="12" maxlength="10"></td>
		<td class="fcaption"><input class="tbox" type="text" id="contact_person" name="contact_person" value="'.$_POST['contact_person'].'" size="22"></td>
		<td class="fcaption"><input class="tbox" type="text" id="website" name="website" value="'.$form_data['website'].'" size="15"></td>
		<td class="fcaption"><input class="tbox" type="text" id="email" name="email" value="'.$form_data['email'].'" size="15"></td>
		<td class="fcaption">'.getUsersSelector().'</td>
		<td class="fcaption"><input class="tbox" class="button" type="submit" name="search" value="'.CBASE_L0025.'"></td>
		<td class="fcaption"><input class="tbox" type="text" id="memo" name="memo" value="'.$form_data['memo'].'" size="25"></td>
		<td class="fcaption"><input id="add" class="tbox" class="button" type="submit" name="add" value=" + "></td>
	</tr>';
	return $ftext;
}

function getNewSn() {
	global $sql;
	if(!is_object($sql)){ $sql = new db; }
	$values = array(
		'owner' => USER_ID,
	);
	$sql->db_Insert("sn_list", $values);
	return getLastId();
}

function getLastId() {
	global $sql;
	$sql->db_Select_gen('SELECT LAST_INSERT_ID() id');
	while($row = $sql->db_Fetch()) {
		$id = $row['id'];
	}	
	return $id;
}

function getUsersSelector() {
	global $form_data;
	if(!is_object($mysql)){ $mysql = new db; }
	$stext = '<select class="tbox" name="owner_id">';
	$mysql->db_Select('user', 'user_id, user_name', '1');
	$stext .= '<option></option>';
	while($row = $mysql->db_Fetch()) {
		$stext .= '<option value="'.$row['user_id'].'" '.($row['user_id'] == $form_data['owner_id'] ? 'selected' : '').'>'.$row['user_name'].'</option>';
	}	
	$stext .= '</select>';
	return $stext;
}

function getErrorText($id) {
	$error_text = array(
		'',
		CBASE_LE001,
		CBASE_LE002,
		CBASE_LE003,
	);
	return '<script>alert("'.$error_text[$id].'");history.back();</script>';
	//return '<div style="text-align:center; color: red; margin: 20px;">'.$error_text[$id].'</div>';	
}

function checkPost($str) {
	$pattern = array();
	$replace = array();
	return preg_replace($pattern, $replace, $str);
}

function getPager($form_data) {
	global $page, $sort_fld;
	$pager_text = '';
	$total = getTotal($form_data);
	if ($total > CBASE_LIMIT) {
		$page_count = $total / CBASE_LIMIT;
		$pager_text .= '<div class="pager" style="text-align:center;">'.CBASE_L0038.':';
		for ($i=0; $i<$page_count; $i++) {
			$tpage = $i + 1;
			if ($page == $tpage) {
				$pager_text .= ' '.$tpage.' ';
			} else {
				$pager_text .= ' <a href="?View....'.$sort_fld.'.'.$tpage.'">'.$tpage.'</a> ';
			}
		}
		$pager_text .= '</div>';
	}
	return $pager_text;
}

function getDaysTDs() {
	$ftext = '';
	for ($i=1;$i<=31;$i++) {
		$ftext .= '<td class="forumheader3">'.$i.'</td>';
	}
	return $ftext;
} 

function getExtSearchForm() {
	global $form_data;
	$selector = '<option></option>';
	for ($i=0; $i<10; $i++) {
		$selector .= '<option value="'.$i.'" '.(is_numeric(($form_data['rate']) && $form_data['rate'] == $i) ? 'selected="selected"' : '').'>'.$i.'</option>';
	}
	$ftext = '
		<script type="text/javascript">
			$(function() {
				var param = { dateFormat: "yy-mm-dd", firstDay: 1, dayNamesMin: ["Вс","Пн","Вт","Ср","Чт","Пт","Сб"]};
				$("#time_from").datepicker(param);
				$("#time_to").datepicker(param);
			});
		</script>' .
		'<form method="post" action="'.e_SELF.'?View">
		<table style="width: 100%;" class="fborder">
		<tr>
			<td class="forumheader3">'.CBASE_L0021.'</td>
			<td class="forumheader3">
				<select class="tbox" id="rate" name="rate">
					'.$selector.'
				</select>				
			</td>
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0013.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="name" name="name" value="'.$form_data['name'].'" size="80">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0014.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="contact_person" name="contact_person" value="'.$form_data['contact_person'].'"  size="60">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0015.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="address" name="address" value="'.$form_data['address'].'" size="80">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0010.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="phone" name="phone" value="'.$form_data['phone'].'" size="14" maxlength="10">
				* <input class="tbox" type="text" id="phone_ext" name="phone_ext" value="'.$form_data['phone_ext'].'" size="6" maxlength="4">
			</td>
		</tr>		
		<tr>
			<td class="forumheader3">'.CBASE_L0017.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="website" name="website" value="'.$form_data['website'].'" size="60">
			</td>
		</tr>
		<tr>
			<td class="forumheader3">'.CBASE_L0016.'</td>
			<td class="forumheader3">
				<input class="tbox" type="text" id="email" name="email" value="'.$form_data['email'].'" size="60">
			</td>
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0043.'</td>
			<td class="forumheader3">
				'.CBASE_L0044.' <input class="tbox" type="text" id="time_from" name="time_from" value="'.$form_data['time_from'].'" size="12">
				'.CBASE_L0045.' <input class="tbox" type="text" id="time_to" name="time_to" value="'.$form_data['time_to'].'" size="12">
			</td>
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0018.'</td>
			<td class="forumheader3">'.getUsersSelector().'</td>				
		</tr>	
		<tr>
			<td class="forumheader3">'.CBASE_L0020.'</td>
			<td class="forumheader3">
				<textarea class="tbox" rows="5" cols="80" id="memo" name="memo">'.$form_data['memo'].'</textarea>
			</td>
		</tr>	
		<tr>
			<td class="forumheader3" colspan="2" align="center">
				<input class="button" type="submit" name="search" value="'.CBASE_L0025.'">
				<input class="button" type="reset" onclick="history.back();" value="'.CBASE_L0036.'">
			</td>
		</tr>	
		</table>
	</form>';
	return $ftext;
}


?>