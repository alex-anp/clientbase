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
if (!defined('e107_INIT')) { exit; }

?>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$("#time_from").datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'] });
		$("#time_to").datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'] });
		$("#sale_date").datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1, dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'] });
	});
</script>
