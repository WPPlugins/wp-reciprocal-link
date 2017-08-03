<?php
	BARL_load_locales();
	if (!empty($_POST)) {
		$_POST['valore_link']=stripslashes($_POST['valore_link']);
		BARL_set_options($_POST);
	}
	echo "<br>".__('Fill here the configuration',$BARL_domain)."</br>";
	$option=BARL_retrieve_options();
	echo '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">';
	echo '<table ><tr>
	<td >&gt;&gt;</td><td><textarea cols="60" name="valore_link">'.htmlentities($option['valore_link']).'</textarea></td></tr>';
	echo '<tr><td>'.__('E-mail Notification Each Link Submitted',$BARL_domain).'</td><td><input type="checkbox" name="notification" '.($option['notification']=='on'?'checked':'').'></td></tr>';
	echo '<tr><td>'.__('E-mail For The notification',$BARL_domain).'</td><td><input type="text" name="email_notification" value="'.(isset($option['email_notification'])?$option['email_notification']:'').'"></td></tr>'; 
	echo '<tr><td>'.__('Tell The world you are using this awesome plugin',$BARL_domain).'</td><td><input type="checkbox" name="veke_backlink" '.($option['veke_backlink']=='on'?'checked':'').'></td></tr>';
	echo '</table>';
	echo "<input type=\"submit\" value=\"".__('Save')."\"></input></form>";
	echo "<hr>";
?>
