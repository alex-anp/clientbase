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

$lan_file = e_PLUGIN."clientbase/languages/".e_LANGUAGE.".php";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."clientbase/languages/English.php"));

$eplug_name = "clientbase";
$eplug_version = "1.0";
$eplug_author = "Alex ANP";
$eplug_logo = "button.png";
$eplug_url = "http://code.google.com/p/clientbase/";
$eplug_email = "alex-anp@ya.ru";
$eplug_description = CBASE_L0002."";
$eplug_compatible = "e107 v7.8+";
$eplug_readme = "readme.txt";
$eplug_folder = "clientbase";
$eplug_menu_name = "clientbase_menu";
$eplug_conffile = "admin_config.php";
$eplug_icon = $eplug_folder."/images/icon.png";
$eplug_icon_small = $eplug_folder."/images/icon_16.png";
$eplug_caption =  CBASE_L0001;

$eplug_prefs = array(
    "cbase_title" => "ClientBase",
    );


$eplug_table_names = array(
        "clientbase",
        "sn_list",
        );

$eplug_tables = array("
	CREATE TABLE ".MPREFIX."clientbase (                                                                
	id INT(9) NOT NULL AUTO_INCREMENT,
	sn INT(9) NOT NULL,                                                                                                                 
	state INT(1) DEFAULT '0',
	name VARCHAR(255) DEFAULT NULL,                                
	rate INT(1) DEFAULT '0',                                                               
	address VARCHAR(255) DEFAULT NULL,
	phone CHAR(10) DEFAULT NULL,                                                              
	phone_ext CHAR(4) DEFAULT NULL,                                                          
	email VARCHAR(255) DEFAULT NULL,                               
	website VARCHAR(255) DEFAULT NULL,                             
	contact_person VARCHAR(255) DEFAULT NULL,                      
	owner_id INT(9) DEFAULT NULL,
	ip_addr CHAR(15) DEFAULT NULL,                                                          
	memo TEXT,                                                     
	run_time DATETIME NOT NULL, 
	die_time DATETIME NOT NULL DEFAULT '2500-01-01 00:00:00',
	UNIQUE KEY id (id)                                                                   
	) ENGINE=MYISAM   
	",
	"
	CREATE TABLE ".MPREFIX."sn_list (                                      
	id INT(9) NOT NULL AUTO_INCREMENT,                        
	owner INT(9) DEFAULT NULL,                                
	UNIQUE KEY `id` (`id`)                                      
	) ENGINE=MYISAM
	",
);

$eplug_link = TRUE;
$eplug_link_name = CBASE_L0001;
$eplug_link_url = e_PLUGIN."clientbase/clientbase.php";

$eplug_done = "Installation Successful...";

$upgrade_add_prefs = "";

$upgrade_remove_prefs = "";

$upgrade_alter_tables = array();

$eplug_upgrade_done = "Upgrade Successful...";

?>