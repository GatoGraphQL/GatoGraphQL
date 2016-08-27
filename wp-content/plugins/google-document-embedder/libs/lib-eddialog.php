<?php

// make it work with localization (ie cyrillic languages)
header('Content-type: text/html; charset=UTF-8');

// access wp functions externally
require_once( dirname( __FILE__ ) . '/lib-bootstrap.php');

// no access if parent plugin is disabled
if ( ! function_exists( 'gde_do_shortcode' ) ) {
	wp_die( __('Access denied.', 'google-document-embedder') );
}

// get profiles
$profiles = gde_get_profiles();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Google Doc Embedder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/jquery/jquery.js"></script> 
	<script type="text/javascript" src="../js/dialog.js"></script>

    <style type="text/css">
	* {
		font-family: Arial, Helvetica, sans-serif !important;
		font-size: 12px;
	}
	td, input, textarea, select {
		font-size: 12px !important;
	}
	.note {
		font-size: 11px;
		color: #333;
	}
	.mceActionPanel {
		margin-top:20px;
	}
	.disabled {
		color: #666 !important;
	}
	.disabled:hover {
		border: 1px solid #BBB !important;
	}
    </style>
    
</head>
<body>

	<form onsubmit="GDEInsertDialog.insert();return false;" action="#" id="gdedialog">

	<p><strong><?php _e('Insert Google Doc Embedder Shortcode', 'google-document-embedder'); ?></strong></p>
  
	<fieldset>
		<legend class="gray dwl_gray"><?php _e('Required', 'google-document-embedder'); ?></legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td align="right" class="gray dwl_gray" style="width:40%;">
				<strong><?php _e('URL or Filename', 'google-document-embedder'); ?></strong><br />
				<span class="note"><?php _e('Full URL or filename to append to profile Base URL', 'google-document-embedder'); ?></span>
			</td>
			<td valign="top">
				<input name="url" type="text" class="opt dwl" id="url" style="width:220px;" /><br/>
				<span id="uri-note-base" style="display:none;color:#2B6FB6;">
					<?php _e('Profile Base URL will be prefixed', 'google-document-embedder'); ?>
				</span>
				<span id="uri-note-file" style="display:none;color:red;">
					<?php _e('Unsupported file type', 'google-document-embedder'); ?>
				</span>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" class="gray dwl_gray">
				<strong><?php _e('Profile', 'google-document-embedder'); ?></strong>
			</td>
			<td valign="top">
				<select name="profile" id="profile">
<?php
	foreach ( $profiles as $p ) {
		echo "\t\t\t\t<option value=\"".$p['profile_id']."\">".$p['profile_name']."</option>\n";
	}
?>
				</select>
				<br/>
				<span class="note"><?php _e('Select the GDE viewer profile to use', 'google-document-embedder'); ?></span>
			</td>
		</tr>
		</table>
	</fieldset>

	<br/>
	<fieldset>
		<legend class="gray dwl_gray"><?php _e('Optional (Override Profile Settings)', 'google-document-embedder'); ?></legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
		<!--tr class="switch">
			<td colspan="2" class="gray dwl_gray">
				<input name="use_defaults" type="checkbox" value="-1" class="use_defaults dwl opt" />
				<?php _e('Use selected profile settings', 'google-document-embedder'); ?>
			</td>
		</tr-->
		<tr class="ovrride">
			<td align="right" valign="top" class="gray dwl_gray" style="width:40%">
				<strong><?php _e('Height', 'google-document-embedder'); ?></strong>
			</td>
			<td valign="top">
				<input name="height" type="text" class="opt dwl" id="height" size="6" />
				&nbsp;&nbsp;&nbsp;
				<strong><?php _e('Width', 'google-document-embedder'); ?></strong> <input name="width" type="text" class="opt dwl" id="width" size="6" /><br />
				<span class="note"><?php _e('Format: 40% or 300px', 'google-document-embedder'); ?></span>
			</td>
		</tr>
		<!--tr class="ovrride">
			<td align="right" class="gray dwl_gray">
				<strong><?php _e('Start Page #', 'google-document-embedder'); ?></strong>
			</td>
			<td valign="top">
				<input name="page" type="text" class="opt dwl" id="page" size="6" value="1" />
			</td>
		</tr-->
		<tr class="ovrride">
			<td align="right" class="gray dwl_gray">
				<strong><?php _e('Show Download Link', 'google-document-embedder'); ?></strong>
			</td>
			<td valign="top" class="gray dwl_gray">
				<input name="save" type="radio" class="opt dwl save" value="1" /> <?php _e('Yes', 'google-document-embedder'); ?>
				<input name="save" type="radio" class="opt dwl save" value="0" /> <?php _e('No', 'google-document-embedder'); ?>
			</td>
		</tr>
		<tr class="ovrride">
			<td colspan="2" class="gray dwl_gray">
				<input name="disable_cache" type="checkbox" value="-1" class="disable_cache dwl opt" />
				<?php _e('Disable caching (this document is frequently overwritten)', 'google-document-embedder'); ?>
			</td>
		</tr>
		</span>
	</table>
	</fieldset>
   
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td colspan="2">
			<br />
			<strong><?php _e('Shortcode Preview', 'google-document-embedder'); ?></strong><br/>
			<textarea name="shortcode" style="width:100%" id="shortcode" readonly="readonly"></textarea>
		</td>
	</tr> 
	</table>
	
	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="insert" name="insert" value="<?php _e('Insert', 'google-document-embedder'); ?>" onclick="GDEInsertDialog.insert();"  style="color:#222;" class="disabled" disabled="disabled" />
		</div>
		
		<div style="float: right">
			<input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', 'google-document-embedder'); ?>" onclick="tinyMCEPopup.close();" />
		</div>
	</div>
</form>

</body>
</html>
