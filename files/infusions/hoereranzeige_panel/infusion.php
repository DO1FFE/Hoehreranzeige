<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Erik Schauer
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."hoereranzeige_panel/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."hoereranzeige_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."hoereranzeige_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."hoereranzeige_panel/locale/German.php";
}

// Infusion general information
$inf_title = $locale['HAZ_title'];
$inf_description = $locale['HAZ_desc'];
$inf_version = $locale['HAZ_version'];
$inf_developer = "Erik Schauer";
$inf_email = "cb0esn@web.de";
$inf_weburl = "http://www.facebook.com/HoererAnzeige";

$inf_folder = "hoereranzeige_panel"; // The folder in which the infusion resides.

// Delete any items not required below.
$inf_newtable[1] = DB_HAZ." (
settings_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
mod_gruppe TEXT,
dj_erkennung TEXT,
avatar TEXT,
server_name TEXT,
server_ip TEXT,
server_port TEXT,
server_pw TEXT,
telestream_ip TEXT,
PRIMARY KEY (settings_id)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_HAZ." SET settings_id='1', mod_gruppe='', dj_erkennung='OFF', avatar='OFF', server_name='Hauptstream', server_ip='127.0.0.1', server_port='8000', server_pw='changeme', telestream_ip='85.114.132.132' ";

$inf_insertdbrow[2] = DB_PANELS." SET panel_name='".$locale['HAZ_title']."', panel_filename='".$inf_folder."', panel_side=2, panel_order='1', panel_type='file', panel_access='0', panel_display='1', panel_status='1' ";

$inf_droptable[1] = DB_HAZ;

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";

$inf_adminpanel[1] = array(
	"title" => $locale['HAZ_admin'],
	"image" => "image.gif",
	"panel" => "hoereranzeige_panel_admin.php",
	"rights" => "HAZ"
);

?>