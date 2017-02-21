<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: hoereranzeige_panel_admin.php
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
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

include INFUSIONS."hoereranzeige_panel/infusion_db.php";

if (!checkrights("HAZ") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect(BASEDIR."index.php"); }

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."hoereranzeige_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."hoereranzeige_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."hoereranzeige_panel/locale/German.php";
}

$id = $_GET['id'];

if (isset($_POST['einstellungen_speichern'])) { // Speichern-Button wurde gedrückt
	// hier folgt die Verarbeitung der Eingaben
	$haz_settings = dbarray(dbquery("SELECT * FROM ".DB_HAZ." WHERE settings_id='".$_GET['id']."'")); // Laden der Einstellungen in ein Array
	
	# Eingaben werden geprüft: Wenn Text vorhanden, dann wähle Eingabe sonst wähle gespeicherte Werte.
	# Alternativ könnte man jede Eingabe prüfen und wenn leer, Fehlermeldung.
	$mod_gruppe = (isset($_POST['mod_gruppe']) ? stripinput($_POST['mod_gruppe']) : $haz_settings['mod_gruppe']);
	$dj_erkennung = (isset($_POST['dj_erkennung']) ? stripinput($_POST['dj_erkennung']) : $haz_settings['dj_erkennung']);
	$avatar = (isset($_POST['avatar']) ? stripinput($_POST['avatar']) : $haz_settings['avatar']);
	$server_name = (isset($_POST['server_name']) ? stripinput($_POST['server_name']) : $haz_settings['server_name']);
	$server_ip = (isset($_POST['server_ip']) ? stripinput($_POST['server_ip']) : $haz_settings['server_ip']);
	$server_port = (isset($_POST['server_port']) ? stripinput($_POST['server_port']) : $haz_settings['server_port']);
	$server_pw = (isset($_POST['server_pw']) ? stripinput($_POST['server_pw']) : $haz_settings['server_pw']);
	$telestream_ip = (isset($_POST['telestream_ip']) ? stripinput($_POST['telestream_ip']) : $haz_settings['telestream_ip']);
	
	# Speichern der Eingaben in die Datenbank:
	$result = dbquery("UPDATE ".DB_HAZ." SET
			mod_gruppe = '".$mod_gruppe."',
			dj_erkennung = '".$dj_erkennung."',
			avatar = '".$avatar."',
			server_name = '".$server_name."',
			server_ip = '".$server_ip."',
			server_port = '".$server_port."',
			server_pw = '".$server_pw."',
			telestream_ip = '".$telestream_ip."'
			WHERE settings_id='".$id."'");
	redirect(FUSION_SELF.$aidlink."&amp;id=".$id."&amp;erfolg=true"); // Weiterleiten und Erfolgsmeldung anzeigen, gleichzeitig werden die geänderten Einstellungen geladen und angezeigt
} else {

	if (isset($_GET['erfolg'])) {
		echo "<div id='erfolg' style='color:green;font-weight:bold;text-align:center;font-size: 16px;'><br />".$locale['HAZ_a_success']."<br /></div>"; // Anzeige einer Erfolgs-Meldung
	}
	
// Wenn delete ausgewählt, datenbank $id löschen
	if (isset($_GET['del'])) {
	mysql_query("DELETE FROM ".DB_HAZ." WHERE settings_id='".$_GET['id']."'") or die(mysql_error());
	redirect(FUSION_SELF.$aidlink."&amp;erfolg=true"); // Weiterleiten und Erfolgsmeldung anzeigen, gleichzeitig werden die geänderten Einstellungen geladen und angezeigt
	}
 
 	if (isset($_GET['add'])) {
	mysql_query("INSERT INTO ".DB_HAZ." () VALUES();") or die(mysql_error());
		redirect(FUSION_SELF.$aidlink."&amp;erfolg=true"); // Weiterleiten und Erfolgsmeldung anzeigen, gleichzeitig werden die geänderten Einstellungen geladen und angezeigt
	}
	
	if (isset($_GET['optimize'])) {
	mysql_query("OPTIMIZE TABLE ".DB_HAZ) or die(mysql_error());
		redirect(FUSION_SELF.$aidlink."&amp;erfolg=true"); // Weiterleiten und Erfolgsmeldung anzeigen, gleichzeitig werden die geänderten Einstellungen geladen und angezeigt
	}
		
	openside($locale['HAZ_admin']);
	// das Formular
	$haz_settings = dbarray(dbquery("SELECT * FROM ".DB_HAZ." WHERE settings_id='".$_GET['id']."'")); // Laden der Einstellungen in ein Array

//	include_once INCLUDES."bbcode_include.php"; // benötigt für die Texte mit BBCodes


// Bestehende Einstellungen aus Datenbank ausgeben

$query = "SELECT * FROM ".DB_HAZ." ORDER BY settings_id";

$result = mysql_query($query);
echo "<center><table border='1'>";
echo "<tr>
<td><b>".$locale['HAZ_a_server_name']."</b></td>
<td><b>".$locale['HAZ_a_server_ip']."</b></td>
<td><b>".$locale['HAZ_a_server_port']."</b></td>
<td><b>".$locale['HAZ_a_modgruppe']."</b></td>
<td><b>".$locale['HAZ_a_dj_erkennung']."</b></td>
<td><b>".$locale['HAZ_a_avatar']."</b></td>
<td><b>".$locale['HAZ_a_server_telestream']."</b></td>
<td><b>".$locale['HAZ_a_options']."</b></td>
</tr>";
while ($line = mysql_fetch_array($result)) { ?>

 <tr>
 <td><?PHP echo $line[server_name];?></td>
 <td><?PHP echo $line[server_ip];?></td>
 <td><?PHP echo $line[server_port];?></td>
 <td><?PHP echo $line[mod_gruppe];?></td>
 <td><?PHP echo $line[dj_erkennung];?></td>
 <td><?PHP echo $line[avatar];?></td>
 <td><?PHP echo $line[telestream_ip];?></td>
 
 <td><a href="<?PHP echo FUSION_SELF.$aidlink."&amp;id=".$line[settings_id];?>&amp;edit=true"><?PHP echo $locale['HAZ_a_edit'];?></a><br> 
 <a href="<?PHP echo FUSION_SELF.$aidlink."&amp;id=".$line[settings_id];?>&amp;del=true"><?PHP echo $locale['HAZ_a_delete'];?></a></td>
 </tr>

 <?PHP

}
echo "<tr></tr>";
echo '<tr><td colspan="8" align="center"><a href="'.FUSION_SELF.$aidlink.'&amp;add=true">'.$locale['HAZ_a_add'].'</a></td></tr>';
// echo '<tr><td colspan="8" align="center"><a href="'.FUSION_SELF.$aidlink.'&amp;optimize=1">'.$locale['HAZ_a_optimize'].'</a></td></tr>';
echo "</table></center><br><br>";
mysql_free_result($result);


// Formular zum editieren anzeigen
	if (isset($_GET['edit'])) {
	echo "<div id='formular' name='formular'>\n";
	} else {
	echo "<div id='formular' name='formular' style='display:none;'>\n";
	}
	echo "<form name='hoereranzeige_settings_form' action='".FUSION_SELF.$aidlink."&amp;id=".$id."' method='post'>"; // Wichtig: immer $aidlink verwenden bei Links im Adminbereich!
	echo "<table class='tbl-border center' cellpadding='3' cellspacing='0' width='600px'>";
	echo "<tr style='font-size:bigger;font-weight:bold;text-align:center;'>
			<td class='tbl2' colspan='2'>".$locale['HAZ_title']." ".$locale['HAZ_a_settings']."</td>
		</tr>";
		
	$user_groups = getusergroups(); $access_opts = ""; $sel = ""; // Laden der User-Gruppen und erzeugen eines Dropdown-Feldes
	while(list($key, $user_group) = each($user_groups)) {
		$sel = ($haz_settings['mod_gruppe'] == $user_group['0'] ? " selected" : ""); // aktuelle Einstellung wird hervorgehoben
		$access_opts .= "<option value='".$user_group['0']."'".$sel.">".$user_group['1']."</option>\n";
	}
	
	// Auswahloptionen für DJ-Erkennung setzen
	$dj_daten[0] = array('OFF',$locale['HAZ_a_off']);  
	$dj_daten[1] = array('IRC','IRC');
	$dj_daten[2] = array('AIM','AIM');
	$dj_daten[3] = array('ICQ','ICQ');
	$dj_daten[4] = array('TITLE','ServerTitle');
	
	// Laden der DJ-Erkennungen und erzeugen eines Dropdown-Feldes
	$access_opts1 = ""; $sel1 = "";
	while(list($key1, $dj_data) = each($dj_daten)) {
		$sel1 = ($haz_settings['dj_erkennung'] == $dj_data['0'] ? " selected" : ""); //aktuelle Einstellung wird hervorgehoben
		$access_opts1 .= "<option value='".$dj_data['0']."'".$sel1.">".$dj_data['1']."</option>\n";
	}
	// Auswahloptionen für Avatar setzen
	$avatar_daten[0] = array('OFF',$locale['HAZ_a_off']);  
	$avatar_daten[1] = array('ON',$locale['HAZ_a_on']);
	
	
	// Laden der Avatar-Enstellungen und erzeugen eines Dropdown-Feldes
	$access_opts2 = ""; $sel2 = "";
	while(list($key2, $avatar_data) = each($avatar_daten)) {
		$sel2 = ($haz_settings['avatar'] == $avatar_data['0'] ? " selected" : ""); //aktuelle Einstellung wird hervorgehoben
		$access_opts2 .= "<option value='".$avatar_data['0']."'".$sel2.">".$avatar_data['1']."</option>\n";
	}
	
/*	echo "<tr>
			<td class='tbl1' style='text-align:right; white-space:nowrap; width:10%;'>".$locale['HAZ_a_dbid'].":
			</td>
			<td class='tbl1' style='text-align:left;'><b>".$haz_settings['settings_id']."</b>
			</td>
		</tr>"; 
*/
	echo "<tr>
			<td class='tbl1' style='text-align:right; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_modgruppe'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<select name='mod_gruppe' id='mod_gruppe' class='textbox'>".$access_opts."</select>
				&nbsp;(".$locale['HAZ_a_modgruppe1'].")
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_dj_erkennung'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<select name='dj_erkennung' id='dj_erkennung' class='textbox'>".$access_opts1."</select>
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_avatar'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<select name='avatar' id='avatar' class='textbox'>".$access_opts2."</select>
				&nbsp;(".$locale['HAZ_a_avatar1'].")
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; vertical-align:top; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_server_name'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<input name='server_name' id='server_name' value='".$haz_settings['server_name']."' class='textbox' type='text'>
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; vertical-align:top; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_server_ip'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<input name='server_ip' id='server_ip' value='".$haz_settings['server_ip']."' class='textbox' type='text'>
			</td>
		</tr>";
		
	echo "<tr>
			<td class='tbl1' style='text-align:right; vertical-align:top; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_server_port'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<input name='server_port' id='server_port' value='".$haz_settings['server_port']."' class='textbox' type='text'>
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; vertical-align:top; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_server_pw'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<input name='server_pw' id='server_pw' value='".$haz_settings['server_pw']."' class='textbox' type='password'>
			</td>
		</tr>";
	echo "<tr>
			<td class='tbl1' style='text-align:right; vertical-align:top; white-space:nowrap; width:10%;'>
				".$locale['HAZ_a_server_telestream'].":
			</td>
			<td class='tbl1' style='text-align:left;'>
				<input name='telestream_ip' id='telestream_ip' value='".$haz_settings['telestream_ip']."' class='textbox' type='text'>
			</td>
		</tr>";
		
	echo "<tr>
			<td class='tbl2' style='text-align:center;' colspan='2'>
				<input type='submit' class='button' name='einstellungen_speichern' value='".$locale['HAZ_a_save']."' />
			</td>
		</tr>";	
	echo "</table>";
	echo "</form>";
	echo "</div>\n";
	echo "<br><center>".$locale['HAZ_title']." v".$locale['HAZ_version']." - &copy; 2010-".date('Y')." by Erik Schauer / <a href='http://www.schauer-hosting.de' target='_blank'>Schauer-Hosting</a></center>";
	closeside();
}
echo "
<script type='text/javascript'>
	$(document).ready(function(){
	$('#erfolg').fadeOut(10000);
	});
</script>";

require_once THEMES."templates/footer.php";
?>