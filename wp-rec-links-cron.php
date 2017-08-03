<?php 
$sendmail=false;
if (! defined('ABSPATH')) {
	$dir=str_replace("\\","/",dirname(__FILE__));
	$dir=preg_replace("/wp-content\/plugins*.*/","",$dir);
	require_once($dir.'/wp-config.php');
	$sendmail=true;
}
$tutti=BARL_get_all_links();
BARL_load_locales();
$towrite="";
foreach ($tutti as $entry) {
	if($tmp=BARL_check_back_link($entry->recurl, get_option('siteurl')) ) 
		$ok.="<tr bgcolor=\"#c0c0c0\"><td>$entry->id</td><td>$entry->url</td><td>$entry->recurl</td><td>".htmlentities($tmp)."</td></tr>";
	else
		$no.="<tr bgcolor=\"#c0c0c0\"><td>$entry->id</td><td>$entry->url</td><td>$entry->recurl</td></tr>";
}
$towrite.='<h1>'.__('Valid Reciprocal Links',$BARL_domain).'</h1>
<table><tr bgcolor="#c0c0c0"><td>Id</td><td>url</td><td>'.__('Reciprocal Url',$BARL_domain).'</td></tr>'.$ok.'</table><br>
<br>
<h1>'.__('Invalid Reciprocal Links',$BARL_domain).'</h1>
<table cellpadding="2" cellspacing="2" ><tr bgcolor="#c0c0c0"><td>Id</td><td>url</td><td>'.__('Reciprocal Url',$BARL_domain).'</td></tr>'.$no.'</table><br>';


//invio e-mail
if ($sendmail) {
	$intestazioni  = "MIME-Version: 1.0\r\n";
	$intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
	echo "Sending mail to ".get_option('admin_email');
	echo wp_mail(get_option('admin_email'), 'Report Backlinks' , "<html><body>".$towrite.".</body></html>", $intestazioni);
}else 
	echo $towrite;