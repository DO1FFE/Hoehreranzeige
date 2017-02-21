<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: hoereranzeige_panel.php
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
 

// Funktionen für Shoutcast-Abfrage
class AskSHOUTcast {
	var $SHOUTcastData;
	var $error;
	
	function GetStatus($ip, $port, $pw) {
		error_reporting(0);
		$fp = fsockopen($ip, $port, $errno, $errstr, 1);
		if (!$fp) {
			error_reporting(E_ALL);
			$this->error = "$errstr ($errno)";
			return(0);
		} else {
			error_reporting(E_ALL);
			socket_set_timeout($fp, 2);
			fputs($fp, "GET /admin.cgi?pass=".$pw."&mode=viewxml HTTP/1.0\r\n");
			fputs($fp, "User-Agent: Mozilla\r\n\r\n");
			while (!feof($fp)) {
				$this->SHOUTcastData .= fgets($fp, 512);
			}
			fclose($fp);
			if (stristr($this->SHOUTcastData, "HTTP/1.0 200 OK") == true) {
				$this->SHOUTcastData = trim(substr($this->SHOUTcastData, 42));
			} else {
				$this->error = "Bad login";
				return(0);
			}
			$xmlparser = xml_parser_create();
			if (!xml_parse_into_struct($xmlparser, $this->SHOUTcastData, $this->values, $this->indexes)) {
				$this->error = "Unparsable XML";
				return(0);
			}
			xml_parser_free($xmlparser);
			return(1);
		}
	}
	
	function GetCurrentListeners() {
		return($this->values[$this->indexes["CURRENTLISTENERS"][0]]["value"]);
	}

	function GetPeakListeners() {
		return($this->values[$this->indexes["PEAKLISTENERS"][0]]["value"]);
	}

	function GetMaxListeners() {
		return($this->values[$this->indexes["MAXLISTENERS"][0]]["value"]);
	}

	function GetServerGenre() {
		return($this->values[$this->indexes["SERVERGENRE"][0]]["value"]);
	}
	
	function GetServerURL() {
		return($this->values[$this->indexes["SERVERURL"][0]]["value"]);
	}
	
	function GetServerTitle() {
		return($this->values[$this->indexes["SERVERTITLE"][0]]["value"]);
	}
	
	function GetCurrentSongTitle() {
		return($this->values[$this->indexes["SONGTITLE"][0]]["value"]);
	}
	
	function GetIRC() {
		return($this->values[$this->indexes["IRC"][0]]["value"]);
	}
	
	function GetAIM() {
		return($this->values[$this->indexes["AIM"][0]]["value"]);
	}
	
	function GetICQ() {
		return($this->values[$this->indexes["ICQ"][0]]["value"]);
	}

	function GetStreamStatus() {
		return($this->values[$this->indexes["STREAMSTATUS"][0]]["value"]);
	}
	
	function GetBitRate() {
		return($this->values[$this->indexes["BITRATE"][0]]["value"]);
	}
	
	function GetAverage() {
		return($this->values[$this->indexes["AVERAGETIME"][0]]["value"]);
	}
	
	function GetSongHistory() {
		$arrhistory=""; // Sonst gibts Fehlermeldung
		for($i=1;$i<sizeof($this->indexes['TITLE']);$i++) {
			$arrhistory[$i-1] = array(
				"playedat"=>$this->values[$this->indexes['PLAYEDAT'][$i]]['value'],
				"title"=>$this->values[$this->indexes['TITLE'][$i]]['value']
			);
		}
		return($arrhistory);
	}
	
	function GetListener() {
		$arrlistener=""; // Sonst gibts Fehlermeldung
		for($i=0;$i<sizeof($this->indexes['HOSTNAME']);$i++) {
			$arrlistener[$i] = array(
				"hostname"=>$this->values[$this->indexes['HOSTNAME'][$i]]['value'],
				"useragent"=>$this->values[$this->indexes['USERAGENT'][$i]]['value'],
				"connecttime"=>$this->values[$this->indexes['CONNECTTIME'][$i]]['value']
			);
		}
		return($arrlistener);
	}

	function GetError() { return($this->error); }
}


function zeitformat($Sekundenzahl)
{
  $Sekundenzahl = abs($Sekundenzahl); // Ganzzahlwert bilden

  return sprintf("%d Tag(e) %02dh %02dm %02ds",
                $Sekundenzahl/60/60/24,($Sekundenzahl/60/60)%24,($Sekundenzahl/60)%60,$Sekundenzahl%60);
}


// Hauptscript mit Ausgabe
openside($locale['HAZ_title']);

//Prüfen ob User eingeloggt ist, anderenfalls auffordern zum einloggen.
if (iMEMBER) {

$haz_settings_q = dbquery("SELECT * FROM ".DB_HAZ);
while($haz_settings = mysql_fetch_array($haz_settings_q)){ // Laden der Einstellungen in ein Array

	$server = new AskSHOUTcast();

	if ($server->GetStatus($haz_settings['server_ip'], $haz_settings['server_port'], $haz_settings['server_pw'])) {

			if ($server->GetStreamStatus()) {
				$akttitel = $server->GetCurrentSongTitle();
				echo "<br><center>";			
				if ($haz_settings['dj_erkennung'] == "OFF") { $dj_name = ""; } else {
					if ($haz_settings['dj_erkennung'] == "IRC") { $dj_name = $server->GetIRC(); }
					elseif ($haz_settings['dj_erkennung'] == "AIM") { $dj_name = $server->GetAIM(); }
					elseif ($haz_settings['dj_erkennung'] == "ICQ") { $dj_name = $server->GetICQ(); }
					elseif ($haz_settings['dj_erkennung'] == "TITLE") { $dj_name = $server->GetServerTitle(); }
	
					// Profil des DJ und Avatar laden
					$db_dj = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".$dj_name."'");
					if (dbrows($db_dj)) {
						$dj_profil = dbarray($db_dj);
						if($dj_profil['user_avatar'] && file_exists(IMAGES."avatars/".$dj_profil['user_avatar'])) {
							$dj_avatar = "<img src='".IMAGES."avatars/".$dj_profil['user_avatar']."' width='100px' alt='' border='0'>";
						} else {
						$dj_avatar = "<img src='".INFUSIONS."hoereranzeige_panel/images/noavatar100.png' alt='' border='0'>";
						}
					}
					if ($haz_settings['avatar'] == "OFF") {
					} else {
						echo $dj_avatar; 
					}

					echo "<h3><a href='".BASEDIR."profile.php?lookup=".$dj_profil['user_id']."'>".$dj_name."</a> ".$locale['HAZ_djonair']."</h3>\n";
					}
				echo "<table border='1' align='center'><tr><td><b>".$locale['HAZ_name']."&nbsp;(".$locale['HAZ_bitrate'].")</b></td><td><b>".$locale['HAZ_listener']."</b></td></tr>\n";
				$aktdj	 = $dj_name;
				$ausgabe ="<td>";
				echo "<td align='right'>".$haz_settings['server_name']."&nbsp;(".$server->GetBitRate()."&nbsp;kbit/s)</td>";
				if ($server->GetCurrentListeners()==0) {
					echo "<td>".$locale['HAZ_nolistener']."</td></tr>\n";
				} else {	
					$listener = $server->GetListener();
        			if (is_array($listener)) {
            			for ($i=0;$i<sizeof($listener);$i++) {
            				$nr = $i+1;	
            				$host	  = gethostbyaddr($listener[$i]["hostname"]);
            				$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_ip='".$listener[$i]["hostname"]."'");
							if (dbrows($result)) {
								$data = dbarray($result); 
								$hoerername = "<a href='/profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>";
							} else {
								$hoerername = $locale['HAZ_guestlistener'];
								if ($listener[$i]["hostname"]== $haz_settings['telestream_ip']) { $hoerername = $locale['HAZ_telestream']; } 
							} 
							if ($i+1==sizeof($listener)) {
								$ausgabe .= $hoerername;
							} else {
								$ausgabe .= $hoerername.", ";
							}
            			}
        			} else {
	            		$ausgabe .= $locale['HAZ_nolistener']."\n";
    	    		}
        			$ausgabe .= "</td></tr>\n";
					echo $ausgabe;
				}
			}
			
	} else {
	echo $haz_settings['server_ip'].":".$haz_settings['server_port']." (".$haz_settings['server_name'].") - ".$locale['HAZ_offline']."<br>";
	}
echo "</table>\n";
}
} else {
	echo "<center><br><h3>".$locale['HAZ_noguest']."</h3></center>";	// Aufforderung zum einloggen
}

// Streamdata & Moddata
$haz_settings_q = dbquery("SELECT * FROM ".DB_HAZ);
while($haz_settings = mysql_fetch_array($haz_settings_q)){ // Laden der Einstellungen in ein Array

if (iADMIN) {
echo "<script language=\"javascript\">
function OnOff()
{
if(document.getElementById('streamdata".$haz_settings['settings_id']."').style.display == 'block')
{
document.getElementById('streamdata".$haz_settings['settings_id']."').style.display = 'none';
}
else
{
document.getElementById('streamdata".$haz_settings['settings_id']."').style.display = 'block';
}
}
</script>";

echo"<center><h3><a href='javascript:;' onclick='OnOff()'>Stream-Data (".$haz_settings['server_name'].") ".$locale['HAZ_foradmin']." ".$locale['HAZ_onoff']."</a></h3></center>"; 
echo"<div name='streamdata".$haz_settings['settings_id']."' id='streamdata".$haz_settings['settings_id']."' style='display:none;'>";

if ($server->GetStreamStatus()) {
			$akttitel = $server->GetCurrentSongTitle();
			$ausgabe ="<br>";
			echo "<br>".$haz_settings['server_ip'].":".$haz_settings['server_port']." (".$server->GetBitRate()." kbit/s)<br><br>";
			echo $aktdj." on Air mit ".$akttitel."<br>";
			echo "<br>";
			echo "CurrentListeners: ".$server->GetCurrentListeners()."<br>";
			echo "PeakListeners: ".$server->GetPeakListeners()."<br>";
			echo "MaxListeners: ".$server->GetMaxListeners()."<br>";
			echo "ServerGenre: ".$server->GetServerGenre()."<br>";
			echo "ServerURL: ".$server->GetServerURL()."<br>";
			echo "ServerTitle: ".$server->GetServerTitle()."<br>";
			echo "AIM: ".$server->GetAIM()."<br>";
			echo "ICQ: ".$server->GetICQ()."<br>";
			echo "IRC: ".$server->GetIRC()."<br>";
			echo "<br><br><br>";

		if ($server->GetCurrentListeners()==0) {
			
		} else {	

			echo "<b><u>".$locale['HAZ_listener']."</u></b><br>";
			$ausgabe .= "<table border='1'><tr><td><b>".$locale['HAZ_nr']."</b></td><td><b>".$locale['HAZ_ip']."</b></td><td><b>".$locale['HAZ_host']."</b></td><td><b>".$locale['HAZ_nick']."</b></td><td><b>".$locale['HAZ_hseit']."</b></td><td><b>".$locale['HAZ_programm']."</b></td></tr>\n";
			$listener = $server->GetListener();
        	if (is_array($listener)) {
            	for ($i=0;$i<sizeof($listener);$i++) {
            		$nr = $i+1;	
            		$host	  = gethostbyaddr($listener[$i]["hostname"]);
            		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_ip='".$listener[$i]["hostname"]."'");
					$connt = $listener[$i]["connecttime"];
					$ctime = zeitformat($connt);
			if (dbrows($result)) {
				$data = dbarray($result); 
				$hoerername = $data['user_name'];
			} else {
				$hoerername = $locale['HAZ_guestlistener'];
				if ($listener[$i]["hostname"]== $haz_settings['telestream_ip']) { $hoerername = $locale['HAZ_telestream']; } 
			} 
				       	$ausgabe .= "<tr><td>".$nr.".</td><td><a target=\"_blank\" href=\"http://www.utrace.de/?query=".$listener[$i]["hostname"]."\">".$listener[$i]["hostname"]."</a></td><td>".$host."</td><td>".$hoerername."</td><td>".$ctime."</td><td>".$listener[$i]["useragent"]."</td></tr>\n";
            	}
        	} else {
            	$ausgabe .= "<tr><td colspan='4' align='center'>".$locale['HAZ_nolistener']."</td></tr>\n";
        	}
			$ausgabe .= "</table><br>\n";			
			echo $ausgabe;
		}
			echo "<br>\n";
		}

echo "</div>\n";
}

elseif (checkgroup($haz_settings['mod_gruppe'])) {
echo "<script language=\"javascript\">
function OnOff()
{
if(document.getElementById('moddata".$haz_settings['settings_id']."').style.display == 'block')
{
document.getElementById('moddata".$haz_settings['settings_id']."').style.display = 'none';
}
else
{
document.getElementById('moddata".$haz_settings['settings_id']."').style.display = 'block';
}
}
</script>";

echo"<center><h3><a href='javascript:;' onclick='OnOff()'>Mod-Data (".$haz_settings['server_name'].") ".$locale['HAZ_formod']." ".$locale['HAZ_onoff']."</a></h3></center>"; 
echo"<div name='moddata".$haz_settings['settings_id']."' id='moddata".$haz_settings['settings_id']."' style='display:none;'>";

if ($server->GetStreamStatus()) {
			$akttitel = $server->GetCurrentSongTitle();
			$aktdj	 = $server->GetIRC();
			$ausgabe ="<br>";
			echo "<br>".$haz_settings['server_ip'].":".$haz_settings['server_port']." (".$server->GetBitRate()." kbit/s)<br><br>";
			echo $aktdj." on Air mit ".$akttitel."<br>";
			echo "<br>";
			echo "CurrentListeners: ".$server->GetCurrentListeners()."<br>";
			echo "PeakListeners: ".$server->GetPeakListeners()."<br>";
			echo "MaxListeners: ".$server->GetMaxListeners()."<br>";
			echo "ServerGenre: ".$server->GetServerGenre()."<br>";
			echo "ServerURL: ".$server->GetServerURL()."<br>";
			echo "ServerTitle: ".$server->GetServerTitle()."<br>";
			echo "AIM: ".$server->GetAIM()."<br>";
			echo "ICQ: ".$server->GetICQ()."<br>";
			echo "IRC: ".$server->GetIRC()."<br>";
			echo "<br><br><br>";

		if ($server->GetCurrentListeners()==0) {
			
		} else {	

			echo "<b><u>".$locale['HAZ_listener']."</u></b><br>";
			$ausgabe .= "<table border='1'><tr><td><b>".$locale['HAZ_nr']."</b></td><td><b>".$locale['HAZ_nick']."</b></td><td><b>".$locale['HAZ_hseit']."</b></td><td><b>".$locale['HAZ_programm']."</b></td></tr>\n";
			$listener = $server->GetListener();
        	if (is_array($listener)) {
            	for ($i=0;$i<sizeof($listener);$i++) {
            		$nr = $i+1;	
            		$host	  = gethostbyaddr($listener[$i]["hostname"]);
            		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_ip='".$listener[$i]["hostname"]."'");
					$connt = $listener[$i]["connecttime"];
					$ctime = zeitformat($connt);
			if (dbrows($result)) {
				$data = dbarray($result); 
				$hoerername = $data['user_name'];
			} else {
				$hoerername = $locale['HAZ_guestlistener'];
				if ($listener[$i]["hostname"]== $haz_settings['telestream_ip']) { $hoerername = $locale['HAZ_telestream']; } 
			} 
				       	$ausgabe .= "<tr><td>".$nr.".</td><td>".$hoerername."</td><td>".$ctime."</td><td>".$listener[$i]["useragent"]."</td></tr>\n";
            	}
        	} else {
            	$ausgabe .= "<tr><td colspan='4' align='center'>".$locale['HAZ_nolistener']."</td></tr>\n";
        	}
			$ausgabe .= "</table><br>\n";			
			echo $ausgabe;
		}
			echo "<br>\n";
		}

echo "</div>\n";
} 
}
echo "<br><center>".$locale['HAZ_title']." v".$locale['HAZ_version']." - &copy; 2010-".date('Y')." by Erik Schauer / <a href='http://www.schauer-hosting.de' target='_blank'>Schauer-Hosting</a></center>\n";
closeside();
?>