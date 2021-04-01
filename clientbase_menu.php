<?php

if (!defined('e107_INIT')) { exit; }

$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

$mtext = '
<table class="cont">
	<tbody>
		<tr>
			<td class="menu_content non_default">
				<img src="/e107_themes/jayya/images/bullet2.gif" alt="bullet" style="vertical-align: middle;">
				<a class="login_menu_link" href="'.e_SELF.'?Mine">'.CBASE_LM002.'</a><br>
				<img src="/e107_themes/jayya/images/bullet2.gif" alt="bullet" style="vertical-align: middle;">
				<a class="login_menu_link" href="'.e_SELF.'?Add">'.CBASE_LM003.'</a><br>
				<img src="/e107_themes/jayya/images/bullet2.gif" alt="bullet" style="vertical-align: middle;">
				<a class="login_menu_link" href="'.e_SELF.'?Search">'.CBASE_LM004.'</a><br>
				<img src="/e107_themes/jayya/images/bullet2.gif" alt="bullet" style="vertical-align: middle;">
				<a class="login_menu_link" href="'.e_SELF.'?ToDo">'.CBASE_LM005.'</a><br>
				<img src="/e107_themes/jayya/images/bullet2.gif" alt="bullet" style="vertical-align: middle;">
				<a class="login_menu_link" href="'.e_SELF.'?Stat">'.CBASE_LM006.'</a><br>
			</td>
		</tr>
	</tbody>
</table>
';

$ns -> tablerender(CBASE_LM001, $mtext);

/*============================ ToDo List Menu ==================================*/
//$todo_count = $sql->db_Count("clientbase", "(*)", "WHERE " );

//$ns -> tablerender(CBASE_L0042, $mtext);
?>

