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

$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

require_once(e_ADMIN."auth.php");

$ns -> tablerender(PAGE_NAME, getAdmText());

require_once(e_ADMIN."footer.php");

function getAdmText() {
	return '' .
			'<h3>'.PAGE_NAME.'</h3>'.
			'<p>'.CBASE_L0002.'</p>'.
			'<div style="text-align:center; margin-top:20px;">'.CBASE_L0003.'</div>'. 
			'';
}
?>