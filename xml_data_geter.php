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

//$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
//include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

$param = array();

echo getXmlData($param);

/*==============================================================================================*/
function getData($param) {
	global $sql;
	$data = array();
	$user = array();
	$days = array();
	//select count(id), date_format(run_time, '%m.%d') day, owner_id from e107_clientbase group by day, owner_id order by day, owner_id
	if ($sql->db_Select_gen("SELECT count(c.id) call_count, date_format(c.run_time, '%m.%d') day, u.user_name owner_id FROM #clientbase c, #user u WHERE c.run_time >= SUBDATE(NOW(), INTERVAL 3 MONTH) AND c.owner_id = u.user_id GROUP BY day, owner_id ORDER BY day, owner_id")) {
		while($row = $sql->db_Fetch()) {
			$user[$row['owner_id']] = 1;
			$days[$row['day']] = 1;
			$data[$row['day']][$row['owner_id']] = $row['call_count'];
		}
	} else {
		echo "GetData Error!!!";
	}
	
	return array($user, $days, $data);
}


function getXmlData($param) {
	$tt = 1;
	$color = array(
		'AFD8F8',
		'F6BD0F',
		'8BBA00',
		'FF8E46',
		'008E8E',
		'D64646',
		'8E468E',
		'588526',
		'B3AA00',
		'008ED6',
		'9D080D',
		'A186BE',
	);
	list($user, $days, $data) = getData($param);
	$xml_txt = '<?xml version="1.0" encoding="UTF-8" ?>'.
"
<graph showValues='0' decimalPrecision='0' bgcolor='F3f3f3' bgAlpha='70' numdivlines='9'
  showColumnShadow='1' divlinecolor='c5c5c5' divLineAlpha='60' showAlternateHGridColor='1'
  alternateHGridColor='f8f8f8' alternateHGridAlpha='60' rotateNames='1' baseFontSize='12'>
";
	$xml_txt .= '<categories>';
	foreach ($days as $k => $v) {
		$xml_txt .= "<category name='".$k."' />\n";
	}
	$xml_txt .= '</categories>';

	foreach ($user as $uk => $uv) {
		$xml_txt .= "<dataset seriesName='".$uk."' color='".$color[$tt]."' areaAlpha='50' areaBorderColor='".$color[$tt]."'>";
		$tt++;
		if ( $tt > 11 ) { $tt = 1; }
		foreach ($days as $dk => $dv) {
			$xml_txt .= "<set value='".($data[$dk][$uk] ? $data[$dk][$uk] : '0')."' />\n";
		}
		$xml_txt .= '</dataset>';
	}
	$xml_txt .= "
  <trendlines>
    <line startValue='50' color='91C728' displayValue='50' showOnTop='1'/>
  </trendlines>

</graph>
			";
	return $xml_txt;
}

?>