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
if (!getperms("P")) {
      header("location:".e_HTTP."index.php");
      exit;
    }

require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."userclass_class.php");

$plugin_folder_name = 'ecallcentre';

$lan_file = e_PLUGIN."".$plugin_folder_name."/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."".$plugin_folder_name."/languages/English.php"));

//=========== Update settings script =================
if(IsSet($_POST['updatesettings'])) {
    $pref['girl_class'] = $_POST['girl_class'];
	$pref['supergirl_class'] = $_POST['supergirl_class'];
    save_prefs();
    $message .= CBASE_A02;
}

//================ Options Form ===========================
$text = "
<form name='setings' action='".e_SELF."' method='post'>
	<table style='width:90%' class='fborder'>
		<tr>
		  <td class='forumheader4'>".CBASE_A03."</td>
		  <td class='forumheader4'>
			".r_userclass("girl_class", $pref['girl_class'],"off","member,admin,classes")."
		  </td>
		</tr>
		<tr>
		  <td class='forumheader4'>".CBASE_A04."</td>
		  <td class='forumheader4'>
			".r_userclass("supergirl_class", $pref['supergirl_class'],"off","member,admin,classes")."
		  </td>
		</tr>
		<tr>
		  <td class='forumheader4' colspan='2'>
		    <div align='center'>
		      <input type='submit' class='button' name='updatesettings' value='".CBASE_A05."'>
		    </div>
		  </td>
		</tr>
	</table>
</form>
";

if ($message != "") $ns->tablerender("", $message);
$captions = CBASE_A01;
$ns -> tablerender($captions, $text);
require_once(e_ADMIN."footer.php");

?>