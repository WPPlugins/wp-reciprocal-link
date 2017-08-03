<?php


echo '
	jQuery.noConflict();
	jQuery(document).ready(function(){
	jQuery.get(preValRecLinks.wpu+"/wp-reciprocal-link/secform/token.php",function(txt){
			  jQuery("#BARLsecure").append('."'<input type=\"hidden\" name=\"ts\" value=\"'+txt+'\" />'".');
	});
	jQuery("#title").fadeTo(\'1\',\'0\');
	jQuery("#url").fadeTo(\'1\',\'0\');
	jQuery("#recurl").fadeTo(\'1\',\'0\');
	jQuery("#email").fadeTo(\'1\',\'0\');
	jQuery("#desc").fadeTo(\'1\',\'0\');
	});
	function BARL_formCheck(forrm) {
	var validated=true;
		jQuery.noConflict();

		
		
		
		// Testing title
			var tmp= forrm.title;
			if ( tmp.value.length < 5)  {
				jQuery("#title").html("<strong style=\'color:red;\'>"+ preValRecLinks.tts +"</strong>").fadeTo(\'200\',\'1\');
				validated=false;
			}else if ( tmp.value.length > 100){
				validated=false;
				jQuery("#title").html("<strong style=\'color:red;\'>"+ preValRecLinks.ttb +"</strong>").fadeTo(\'200\',\'1\');
			} else
				jQuery("#title").fadeTo(\'100\',\'0\');
		
		// Testing Url
			tmp = forrm.url;
			var re =new RegExp( "(http\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(/\S*)?)", "g" );
			if (!(re.test(tmp.value))) {
				validated=false;
				jQuery("#url").html("<strong style=\'color:red;\'>"+ preValRecLinks.nvu +"</strong>").fadeTo(\'200\',\'1\');
			} else if (tmp.value.length > 100) {
				validated=false;
				jQuery("#url").html("<strong style=\'color:red;\'>"+ preValRecLinks.uftbs +"</strong>").fadeTo(\'200\',\'1\');
			} else
				jQuery("#url").fadeTo(\'100\',\'0\');
		
		// Testin Reciprocal Url
			tmp = forrm.reciprocal_url;
			re =new RegExp( "(http\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(/\S*)?)", "g" );
			if (!(re.test(tmp.value))){
				validated=false;
				jQuery("#recurl").html("<strong style=\'color:red;\'>"+ preValRecLinks.nvru +"</strong>").fadeTo(\'200\',\'1\');
			} else if (tmp.value.length > 100) {
				jQuery("#recurl").html("<strong style=\'color:red;\'>"+ preValRecLinks.ruftb +"</strong>").fadeTo(\'200\',\'1\');
				validated=false;
			} else
				jQuery("#recurl").fadeTo(\'100\',\'0\');
			
		// Testing E-mail
			tmp= forrm.email;
			if (!(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(tmp.value))) {
				validated=false;
				jQuery("#email").html("<strong style=\'color:red;\'>"+ preValRecLinks.iem +"</strong>").fadeTo(\'200\',\'1\');
			} else
				jQuery("#email").fadeTo(\'100\',\'0\');

		// Testing Description
			tmp= forrm.desc;
			if (tmp.value.length > 200 || tmp.value.length <50) {
				validated=false;
				jQuery("#desc").html("<strong style=\'color:red;\'>"+ preValRecLinks.tdfsbft +"</strong>").fadeTo(\'200\',\'1\');
			} else
				jQuery("#desc").fadeTo(\'100\',\'0\');
		
		return validated;

	}
	';
// Done :)