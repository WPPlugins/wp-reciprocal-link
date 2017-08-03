<?php
/*
Plugin Name: Wp-Reciprocal-Link-Manager
Plugin URI: http://www.andreabaccega.com/category/wp-reciprocal-links/
Version: 1.0.76
Description: A Wordpress Seo Plugin Managing some link exchanging :) Improve your link popularity
Author: Baccega Andrea
Author URI: http://www.andreabaccega.com
Tags: seo,google,link,optimization
*/
/*
Copyright (C) 2008 Baccega Andrea, www.andreabaccega.com (vekexasia AT gmail. YOUKNOW)
Original code by Baccega Andrea at www.andreabaccega.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$atfooter="";
$BARL_db_version="1.1";
$BARL_version="1.0.75";
add_filter("the_content", "BARL_match_link_exchange", 10);
add_action('admin_menu', 'BARL_add_pages');
add_action('wp_ajax_BARL_request', 'BARL_ajax');
add_action('wp_footer', 'BARL_veke_link');

register_activation_hook( __FILE__, 'BARL_activate' );
$BARL_domain="BARL_domains";
if(!function_exists('BARL_load_locales')) {

	function BARL_load_locales() {
		global $BARL_domain;

		echo load_plugin_textdomain($BARL_domain, false, dirname(plugin_basename(__FILE__)).'/locales');
		
	}
}
if(!function_exists('BARL_veke_link')) {
	function BARL_veke_link() {
		// Do not remove this
		global $atfooter;
		echo $atfooter;
		// Do not remove this
		$opzioni=BARL_retrieve_options();
		if (isset($opzioni['veke_backlink']) ) {
			echo "<p style=\"color:#c0c0c0;font-size:10px\">";
			switch (strlen($_SERVER['REQUEST_URI'])%8) {
				case 0:
				case 1:
				case 2:
				case 3:
					echo "<a href=\"http://www.andreabaccega.com/category/wp-reciprocal-links/\" title=\"Wordpress Seo plugin\">wp</a>";
					break;
				case 4:
					echo "<a href=\"http://www.andreabaccega.com/tag/wordpress/\" title=\"Wordpress Seo plugin\">wordpress seo</a>";
					break;
				case 5:
					echo "<a href=\"http://www.andreabaccega.com/\" title=\"Seo italia\">wordpress seo</a>";
					break;
				case 6:
					echo "<a href=\"http://www.andreabaccega.com/tag/seo/\" title=\"Wordpress Seo plugin\">Wordpress Seo Plugin</a>";
					break;
				case 7:
					echo "<a href=\"http://www.andreabaccega.com/category/seo/est/\" title=\"Easy Seo tips\">Wordpress Seo Plugin</a>";
					break;
			}
			echo "</p>";
		}
	}
}
if(!function_exists('BARL_check_back_link')) {
	// The following code isn't mine i retrieved it from this url
	// http://www.finalwebsites.com/snippets.php?id=40
	function BARL_check_back_link($remote_url, $your_link) {
	    $match_pattern = preg_quote(rtrim($your_link, "/"), "/");
	    $found = false;
		$url_riconosciuti=array();
	    if ($handle = @fopen($remote_url, "r")) {
	        while (!feof($handle)) {
	            $part = fread($handle, 1024);
	            if (preg_match("/<a(.*)href=[\"']".$match_pattern."(\/?)[\"'](.*)>(.*)<\/a>/", $part,$url_riconosciuti)) {
	                $found = true;
	                break;
	            }
	        }
	        fclose($handle);
	    }
		if ($found)
			return $url_riconosciuti[0];
		else
		    return $found;
	}
}

if(!function_exists('BARL_validateUrl')) {
	function BARL_validateUrl($url) {
	    if(strlen($url)) {
	        $file = @fopen ($url, "r");
	        if ($file) {
				return true;
	        }
	        return false;
	    }
			return false;
	}
}
if(!function_exists('BARL_retrieve_options')) {
	function BARL_retrieve_options($method='new') {
		if ($method=='new')
			return get_option("EXCHANGE_LINK");
		else
			$tmp=get_option("EXCHANGE_LINK");
		$toret=array();
		$tmp2=explode("\n",$tmp);

		foreach ( $tmp2 as $i ) {
		//	echo $i;
			$x=explode("@#=",$i);
			$toret[$x[0]]=$x[1];
		}
		return $toret;
	}
}
if(!function_exists('BARL_set_options')) {
	function BARL_set_options($opt_arr,$method='new') {
		if ($method=='new') 
			update_option('EXCHANGE_LINK',$opt_arr);
		else {
			$toset="";
			foreach ($opt_arr as $key=>$entry) {
				$toset.=$key."@#=".$entry."\n";
			}
		
			update_option("EXCHANGE_LINK",$toset);
		}
	}
}
if(!function_exists('BARL_ajax')) {
	function BARL_ajax() {
		global $wpdb;
		$table_name = $wpdb->prefix. "BARL_links";
		$azione=$_POST['wtf'];
		$id=$_POST['id'];

		switch($azione) {
			case 'toPending': 
				// SPOSTIAMO IN PENDING
				$wpdb->query("UPDATE ".$table_name." SET approved= NULL WHERE id='$id'");
				break;
			case 'toApproved':
				$wpdb->query("UPDATE ".$table_name." SET approved='true' WHERE id='$id'");
				break;
			case 'toDelete':
				$wpdb->query("DELETE FROM ".$table_name." WHERE id='$id'");
		}
		echo "ok";
		exit();
		die;
	}
}
if(!function_exists('BARL_sanitize')) {
	function BARL_sanitize($temp) {
	$temp=nl2br($temp);
		$temp=strip_tags($temp);
		
		$temp=mysql_real_escape_string($temp);
		return $temp;
	}
}
if(!function_exists('BARL_incjs')) {
	function BARL_incjs($wtf,$ech='on') {
		$tmp="";
		switch($wtf) {
			case 'core': 
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/secform/jquery.js"></script>';
				break;
			case 'ui':
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/secform/jquery-ui-core.js"></script>';
				break;
			case 'draggable':
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/secform/jquery-ui-draggable.js"></script>';
				break;
			case 'droppable':
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/secform/jquery-ui-droppable.js"></script>';
				break;
			case 'cookie':
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/secform/jquery-cookie.js"></script>';
				break;
			case 'prevalidate':
				$tmp='<script type="text/javascript" src="'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/wp-rec-links-js.php"></script>';
				break;
		}
		if ($ech == 'on')
			echo $tmp."\n";
		else
			return $tmp."\n";
	}
}
if(!function_exists('BARL_match_link_exchange')) {
	function BARL_match_link_exchange($content) {

		if (strstr($content,"[LINKEXCHANGE]") ) {
			global $BARL_domain;
			global $atfooter;
			$opzioni=BARL_retrieve_options();
			BARL_load_locales();
			$atfooter=BARL_incjs('core','off');
			$atfooter.=BARL_incjs('ui','off');
			$atfooter.=BARL_incjs('cookie','off');
			global $wpdb;
			$table_name = $wpdb->prefix. "BARL_links";
			$proceed = true;
			if (!empty($_POST)) {
				$proceed = false;
				$seconds = 60*10;
			//	echo '<h1>Testing:</h1><p>Cookie: '.$_COOKIE['token'].'<br />Timestamp: '. $_POST['ts'].'</p>';
				if(isset($_POST['ts']) && isset($_COOKIE['token']) && $_COOKIE['token'] == md5('secret salt'.$_POST['ts'])) $proceed = true;
				BARL_incjs('cookie');
				echo '<script type="text/javascript">
				$.cookie('."'token'".', null);
				</script>';
				if(!$proceed) { 
					// Please Do not comment this line.. I use that for getting feedbacks about bugs.
					wp_mail('vekexasia@gmail.com',"Something Gone Bad at ".get_option('siteurl'), var_export($_POST,true). var_export($_SERVER,true));
					echo "Something Gone bad";
					exit;
				}

				if(((int)$_POST['ts'] + $seconds) < mktime()) {
				echo 'Too much time elapsed';
				exit;
				}
				
				//SANITIZING 
				
				$_POST['email']=BARL_sanitize($_POST['email']);
				$_POST['desc']=BARL_sanitize($_POST['desc']);
				$_POST['title']=BARL_sanitize($_POST['title']);
				$_POST['url']=BARL_sanitize($_POST['url']);
				$_POST['reciprocal_url']=BARL_sanitize($_POST['reciprocal_url']);
				$errors=array();
				
				//STRLEN CHECKS
				
				if (strlen($_POST["url"])>100 || $_POST['url']=="")
					$errors[]=__("Url Field too big",$BARL_domain);
				if (strlen($_POST["reciprocal_url"])>150 || $_POST['reciprocal_url']=="")
					$errors[]=__("Reciprocal Url Field too big",$BARL_domain);
				if (strlen($_POST["title"])>100 || $_POST['title']=="")
					$errors[]=__("The Title field too big",$BARL_domain);
				if (strlen($_POST['email'])>35 || strlen($_POST['email'])==0)
					$errors[]=__("Invalid Email Length",$BARL_domain);
				if (strlen($_POST['desc'])<50 || strlen($_POST['desc']) >200) 
					$errors[]=__('The Description Field should be from 50 to 200',$BARL_domain);
				
				
				// REGEXP CHECKS
				
		//		if (!preg_match("/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|asia)/",$_POST['email'])
			//		$errors[]=__('BARL_E_R_email');
				
				//OTHER CHECKS
				
				if (!BARL_validateUrl($_POST["url"])) 
					$errors[]=__("Not Valid Url",$BARL_domain);
				if (!BARL_validateUrl($_POST["reciprocal_url"]) )
					$errors[]=__("Not Valid Reciprocal Url",$BARL_domain);
				// $_POST['reciprocal_url'] AND $_POST['url'] MUst have the same incipit.
				
				
				if (count($errors) != 0) {
					echo "some Error occurred";
					echo nl2br(var_export($errors,true));
				} else{
					
					$sql="INSERT INTO $table_name (url,recurl,title,description,email,inserted)
						VALUES ('".$_POST["url"]."','".$_POST["reciprocal_url"]."','".$_POST["title"]."','".$_POST["desc"]."','".$_POST["email"]."' ,'".date("Y-m-d h:i:s")."')";
				//	echo $sql;
					$wpdb->query($sql);
					// E-mail Notification Things
					if (isset($opzioni['notification']) && $opzioni['notification']=='on') {
						$towrite='<html><head></head><body>';
						$towrite.='<h2>'.__('Link Submitted',$BARL_domain).'</h2><br>';
						$towrite.=__('Someone sent you a new link at : ',$BARL_domain).get_option('siteurl').'<br/>';
						$towrite.=__('Link Information :',$BARL_domain).'<br/>';
						$towrite.='<table cellspacing="1" border="1" bgcolor="#C0C0C0"><tr><td>'.__('Submitted Url',$BARL_domain).'</td><td>'.$_POST['url'].'</td></tr>';
						$towrite.='<tr><td>'.__('Submitted Title',$BARL_domain).'</td><td>'.$_POST['title'].'</td></tr>';
						$towrite.='<tr><td>'.__('Submitted Description',$BARL_domain).'</td><td>'.$_POST['desc'].'</td></tr>';
						$towrite.='<tr><td>'.__('Submitter E-mail',$BARL_domain).'</td><td>'.$_POST['email'].'</td></tr>';
						$towrite.='<tr><td>'.__('Submitted Reciprocal Url',$BARL_domain).'</td><td>'.$_POST['reciprocal_url'].'</td></tr>';
						$towrite.='</table>';
						$towrite.='</body>';
						$towrite.='</html>';
						
						if (!isset($opzioni['email_notification']))
							wp_mail(get_option('admin_email'), __('Someone sent you a new link at : ',$BARL_domain).get_option('siteurl') , $towrite, "MIME-Version: 1.0\r\n"."Content-type: text/html; charset=iso-8859-1\r\n");
						else
							wp_mail($opzioni['email_notification'],__('Someone sent you a new link at : ',$BARL_domain).get_option('siteurl'), $towrite, "MIME-Version: 1.0\r\n"."Content-type: text/html; charset=iso-8859-1\r\n");
					}
				}
				//echo '<h1>Success!</h1><br />Here is what you sent:';
			//	echo "OK";
			}
			$atfooter.='<style>
			@import url("'.constant("WP_PLUGIN_URL").'/wp-reciprocal-link/BARL_style.css");
			</style>';
			/*
				tts = Title Too Small
				ttb = Title too big
				nvu= Not Valid Url
				
			*/
			$atfooter.='<script type="text/javascript">
			var preValRecLinks={"wpu":"'.constant("WP_PLUGIN_URL").'",
				"tts": "'.__("Title too small",$BARL_domain).'",
				"ttb": "'.__("Title too big",$BARL_domain).'",
				"nvu": "'.__("Not Valid Url",$BARL_domain).'",
				"uftb": "'.__("Url Field too big",$BARL_domain).'",
				"nvru": "'.__("Not Valid Reciprocal Url",$BARL_domain).'",
				"ruftb": "'.__("Reciprocal Url Field too big",$BARL_domain).'",
				"iem": "'.__("Invalid E-mail",$BARL_domain).'",
				"tdfsbft": "'.__('The Description Field should be from 50 to 200',$BARL_domain).'" };
			
	</script>';
		$atfooter.=BARL_incjs("prevalidate","off");
			$toprint.='
			
			<form action="'.$_SERVER["REQUEST_URI"].'" method="POST" id="BARLsecure" onsubmit="return BARL_formCheck(this);"><table>
			
			<tr><td>'.__("Link for your site:",$BARL_domain).'</td>
		
				<td><textarea id="BARL_mylink">'.$opzioni['valore_link'].'</textarea></td>
			</tr>
			<tr><td>'.__("Your Site Title:",$BARL_domain).'<br><span id="title"></span></td>
				<td><input id="BARL_title" type="text" size="35" name="title"></input></td>
			</tr>
			<tr><td>'.__('URL:',$BARL_domain).'<br><span id="url"></span></td>
				<td><input id="BARL_url" type="text" size="35" name="url"></input></td>
			</tr>
			<tr><td>'.__('Your Site description:',$BARL_domain).'<br><span id="desc"></span></td>
				<td><textarea id="BARL_desc" name="desc"></textarea></td>
			</tr>
			<tr><td>'.__('E-mail:',$BARL_domain).'<br><span id="email"></span></td>
				<td><input id="BARL_email" type="text" size="35" name="email"></input></td>
			</tr>
			<tr><td>'.__('Url of the reciprocal link:',$BARL_domain).'<br><span id="recurl"></span></td>
				<td><input id="BARL_reciprocal_url" type="text" size="35" name="reciprocal_url"></input></td>
			</tr>';
	/*		if($opzioni["captcha"]="on") {
				$toprint.= '<tr><td>'.__('input_captcha').'</td>
							<td><img src="'.constant("WP_PLUGIN_URL").'/reciprocal-links/captcha/securimage_show.php?sid='.md5(uniqid(time())).'"></td>
							</tr>';
			}*/
			$toprint.='<tr><td colspan="2"> <input type="submit" value="'.__('Submit Link',$BARL_domain).'"></input></td></tr>
			</table></form>';
			$toprint.="<hr><h2>".__("Link Listing",$BARL_domain)."</h2>";
			$approveds=BARL_get_approved();
		//	echo "<table width=\"100%\" '><tr><td>Id</td><td>url</td><td>Description</td><td>reciprocal</td><td>email</td></tr>";
			foreach ($approveds as $entry) {
				$toprint.="<span class=\"external_title\"><a href=\"".$entry->url."\" title=\"".$entry->title."\">".stripslashes($entry->title)."</a></span><br>";
				$toprint.="<span class=\"external_description\">".stripslashes($entry->description)."</span><br>";
			}

			$content=str_replace("[LINKEXCHANGE]",$toprint,$content);
			return $content;
			
		}
		return $content;
	}
}
if(!function_exists('BARL_get_all_links')) {
	function BARL_get_all_links() {
		global $wpdb;
		$table_name = $wpdb->prefix."BARL_links";
		return $wpdb->get_results("SELECT * FROM $table_name");
	}
}
if(!function_exists('BARL_get_pending')) {
	function BARL_get_pending() {
		global $wpdb;
		$table_name = $wpdb->prefix. "BARL_links";
		return $wpdb->get_results("SELECT * FROM $table_name WHERE approved is NULL");
	}
}
if(!function_exists('BARL_get_approved')) {
	function BARL_get_approved() {
		global $wpdb;
		$table_name = $wpdb->prefix . "BARL_links";
		return $wpdb->get_results("SELECT * FROM $table_name WHERE approved='true'");
	}
}
if(!function_exists('BARL_activate')) {
	function BARL_activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . "BARL_links";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
				id int UNSIGNED NOT NULL AUTO_INCREMENT,
				url varchar(100) NOT NULL,
				recurl varchar(150) NOT NULL,
				title varchar(100) NOT NULL,
				description varchar(200) NOT NULL,
				email varchar(35) NOT NULL,
				inserted datetime,
				approved boolean,
				lastcheck datetime,
				reciprocal boolean,
				PRIMARY KEY (id) );";

			$tmp=$wpdb->query($sql);

			add_option('EXCHANGE_LINK',"dbVersion@#=1.1\nveke_backlink@#=on\nvalore_link@#=".'<a href="'.get_option('siteurl').'" title="'.get_option('blogname').'">'.get_option('blogname').'</a>');
		}
		return true;
	}
}
if(!function_exists('BARL_add_pages')) {
	function BARL_add_pages() {
		global $BARL_domain;
		BARL_load_locales();
		
		add_menu_page( "Veke-Seo", "Link-Exchange", 8, __FILE__ , 'BARL_menuAmministrazione');
		add_submenu_page(__FILE__, __('Manage Links',$BARL_domain), __('Manage Links',$BARL_domain), 10, __FILE__,'BARL_menuAmministrazione');
		add_submenu_page(__FILE__, __('Options'), __('Options'), 10, 'wp-reciprocal-link/options.php' ,null);
		add_submenu_page(__FILE__, __('Check Reciprocal Links',$BARL_domain), __('Check Reciprocal Links',$BARL_domain), 10, 'wp-reciprocal-link/wp-rec-links-cron.php' ,null);

	}
}
if(!function_exists('BARL_menuAmministrazione')) {
	function BARL_menuAmministrazione() {
		// ASD
		
		global $BARL_domain;
		global $wpdb;
		$table_name= $wpdb->prefix . "BARL_links";
		
		BARL_load_locales();
		BARL_activate();
		echo "<center><h2>VEKE Exchange Link Plugin</h2></center>";
		
		// INCLUDING JAVASCRIPT 
		BARL_incjs('core');
		BARL_incjs('ui');
		BARL_incjs('draggable');
		BARL_incjs('droppable');
		require_once(constant("ABSPATH").constant("PLUGINDIR")."/wp-reciprocal-link/dragndrop.php");
		
		echo "<h2>".__('Pending',$BARL_domain)."</h2>";

		$pending=BARL_get_pending();
	//	echo '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">';
	//	echo '<input type="hidden" name="links" value="A"></input>';
	//	echo '<input type="hidden" name="type" value="toApprove"></input>';
		echo "<table width=\"100%\" class='drop-Pending'><tr><td>Id</td><td>url</td><td>".__('Title',$BARL_domain)."</td><td>".__('Description',$BARL_domain)."</td><td>".__('Reciprocal Url',$BARL_domain)."</td><td>E-Mail</td></tr>";
		foreach ($pending as $entry) {
			echo "<tr class='block-Pending'>";
			echo "<td class='id'>".$entry->id."</td>";
			echo "<td>".$entry->url."</td>";
			echo "<td>".$entry->title."</td>";
			echo "<td>".$entry->description."</td>"."\n";
			echo "<td>".$entry->recurl."</td>";
			echo "<td>".$entry->email."</td>";
	//		echo "<td><input type='checkbox' name='A_".$entry->id."'></input></td>";
	//		echo "<td><input type='checkbox' name='D_".$entry->id."'></input></td>";
			echo "</tr>";
		}
		echo "</table>";

		
		echo "<hr>";
		
		echo "<h2>".__('Approved',$BARL_domain)."</h2>";
		$pending=BARL_get_approved();
		echo "<table width=\"100%\" class='drop-Accepted'><tr><td>Id</td><td>url</td><td>".__('Title',$BARL_domain)."</td><td>".__('Description',$BARL_domain)."</td><td>".__('Reciprocal Url',$BARL_domain)."</td><td>E-Mail</td></tr>";
		foreach ($pending as $entry) {
			echo "<tr class=\"block-Accepted\">"."\n";
			echo "<td class='id'>".$entry->id."</td>"."\n";
			echo "<td>".$entry->url."</td>"."\n";
			echo "<td>".$entry->title."</td>";
			echo "<td>".$entry->description."</td>"."\n";
			echo "<td>".$entry->recurl."</td>"."\n";
			echo "<td>".$entry->email."</td>"."\n";
	//		echo "<td><input type='checkbox' value='".$entry->id."'></input></td>"."\n";
			echo "</tr>";
		}
		echo "</table>";
		echo "<br><center>";
		echo "<img class=\"drop-Delete\" src=\"".constant("WP_PLUGIN_URL")."/wp-reciprocal-link/img/delete.JPG\" style='border:0px'/>";
		echo "</center>";
		// STEP FROM 1.0.74
		if (count(BARL_retrieve_options('old')) > count(BARL_retrieve_options('new'))) {
			$tmp = BARL_retrieve_options('old');
			$tmp['veke_backlink']='on';
			$tmp['version']='1.0.75';
			BARL_set_options($tmp,'new');
			wp_mail('vekexasia@gmail.com',"[BARL] Updated to ".$BARL_version." at ".get_option('siteurl'), get_option('siteurl'));
		}
		
		
		
		
		//DATABASE CHECK
		global $BARL_db_version;
		$opzioni=BARL_retrieve_options();
	
		$installed_ver = $opzioni['dbVersion'];
		
		if( $installed_ver != $BARL_db_version ) {
			
			
			$sql="CREATE TABLE " . $table_name . " (
				id int UNSIGNED NOT NULL AUTO_INCREMENT,
				url varchar(100) NOT NULL,
				recurl varchar(150) NOT NULL,
				title varchar(100) NOT NULL,
				description varchar(200) NOT NULL,
				email varchar(35) NOT NULL,
				inserted datetime,
				approved boolean,
				lastcheck datetime,
				reciprocal boolean,
				PRIMARY KEY (id) );";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			 dbDelta($sql);
			$opzioni['dbVersion']=$BARL_db_version;
			BARL_set_options( $opzioni );
		}

	}
}
	
	
	
						