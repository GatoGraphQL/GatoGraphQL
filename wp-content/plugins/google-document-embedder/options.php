<?php

global $gdeoptions;
$import = false;

// which form are we submitting (uses nonce for security and identification)
if ( isset( $_POST['_general_default'] ) ) {
	// updating default profile
	$tabid = "gentab";
	
	if ( gde_form_to_profile( 1, $_POST ) ) {
		// update successful
		gde_show_msg( __('Default profile <strong>updated</strong>.', 'google-document-embedder') );
	} else {
		gde_show_msg( __('Unable to update profile.', 'google-document-embedder'), true );
	}
} elseif ( isset( $_POST['_profiles_new'] ) ) {
	// new profile creation
	global $wpdb;
	$tabid = "protab";
	
	if ( ! empty( $_POST['profile-name'] ) ) {
		$name = preg_replace( "/[^A-Za-z0-9 -]/", '', trim( $_POST['profile-name'] ) );
		$name = strtolower( str_replace( " ", "-", $name ) );
		
		if ( ! preg_match( '/[\pL]/u', $name ) ) {
			// profile name doesn't contain any letter - possible ID conflict
			gde_show_msg( __('Profile name must contain at least one letter.', 'google-document-embedder'), true );
		} elseif ( gde_profile_name_exists( $name ) !== -1 ) {
			// profile name is duplicate
			gde_show_msg( __('Profile name already exists. Please choose another name.', 'google-document-embedder'), true );
		} elseif ( gde_profile_to_profile( $_POST['parent'], $name, stripslashes( $_POST['description'] ) ) ) {
			// intercept and redirect to edit profile page
			$lastid = gde_profile_name_exists( $name );
			$_POST['action'] = "edit";
			$_POST['profile'] = $lastid;
			$noload = "gentab";
			gde_show_msg( __('New profile <strong>created</strong>.', 'google-document-embedder') );
		} else {
			gde_show_msg( __('Unable to create profile.', 'google-document-embedder'), true );
		}
	} else {
		gde_show_msg( __('Unable to create profile.', 'google-document-embedder'), true );
	}
} elseif ( isset( $_POST['_profile_edit'] ) ) {
	// profile edit
	$tabid = "protab";
	
	if ( gde_form_to_profile( $_POST['profile_id'], $_POST ) ) {
		// update successful
		gde_show_msg( __('Profile <strong>updated</strong>.', 'google-document-embedder') );
	} else {
		gde_show_msg( __('Unable to update profile.', 'google-document-embedder'), true );
	}
} elseif ( isset( $_POST['action'] ) && isset( $_POST['profile'] ) ) {
	// profile row action
	
	if ( $_POST['action'] == "delete" ) {
		$tabid = "protab";
		if ( gde_delete_profile( $_POST['profile'] ) ) {
			gde_show_msg( __('Profile <strong>deleted</strong>.', 'google-document-embedder') );
		} else {
			gde_show_msg( __('Unable to delete profile.', 'google-document-embedder'), true );
		}
	} elseif ( $_POST['action'] == "default" ) {
		$tabid = "gentab";
		if ( gde_overwrite_profile( $_POST['profile'] ) ) {
			gde_show_msg( __('Default profile <strong>updated</strong>.', 'google-document-embedder') );
		}
	} elseif ( $_POST['action'] == "edit" ) {
		$tabid = "protab";
		$noload = "gentab";
	}
} elseif ( isset( $_POST['_advanced'] ) ) {
	// updated advanced options (global)
	$tabid = "advtab";
	
	// keep old options for a moment
	$oldoptions = $gdeoptions;
	
	// initialize checkbox values (values if options unchecked)
	$gdeoptions['ed_disable'] = "no";
	$gdeoptions['ed_embed_sc'] = "no";
	$gdeoptions['ed_extend_upload'] = "no";
	$gdeoptions['error_display'] = "no";
	$gdeoptions['error_check'] = "no";
	$gdeoptions['error_log'] = "no";
	
	foreach ( $_POST as $k => $v ) {
		if ( $k == "ed_disable" ) {
			$gdeoptions[$k] = "yes";
		} elseif ( $k == "ed_embed_sc" ) {
			$gdeoptions[$k] = "yes";
		} elseif ( $k == "ed_extend_upload" ) {
			$gdeoptions[$k] = "yes";
		} elseif ( $k == "error_display" ) {
			$gdeoptions[$k] = "yes";
		} elseif ( $k == "error_check" ) {
			$gdeoptions[$k] = "yes";
		} elseif ( $k == "error_log" ) {
			$gdeoptions[$k] = "yes";
			if ( ! isset( $oldoptions['error_log'] ) || $oldoptions['error_log'] == "no" ) {
				if ( ! gde_dx_log("Diagnostic logging enabled") ) {
					// can't write to db - don't enable logging
					gde_show_msg( __('Unable to enable diagnostic logging.', 'google-document-embedder'), true );
					$gdeoptions[$k] = "no";
				}
			}
		} elseif ( array_key_exists( $k, $gdeoptions ) ) {
			// all fields where name == settings key
			$gdeoptions[$k] = stripslashes( $v );
		}
	}
	
	if ( update_option( 'gde_options', $gdeoptions ) ) {
		// update successful
		gde_show_msg( __('Settings <strong>updated</strong>.', 'google-document-embedder') );
	} else {
		gde_show_msg( __('Settings <strong>updated</strong>.', 'google-document-embedder') );	// not true, but avoids confusion in case where no changes were made
		gde_dx_log('Settings update failed - maybe no changes');
	}
} elseif ( isset( $_POST['_advanced_import'] ) ) {
	$valid = false;
	
	// check import file validity
	if ( isset( $_FILES['import'] ) && ! empty( $_FILES['import'] ) ) {
		if ( $_FILES['import']['size'] > 0  && is_uploaded_file( $_FILES['import']['tmp_name'] ) && preg_match( '/json$/i', $_FILES['import']['name'] ) ) {
			// file OK, check for json content
			$json = json_decode( file_get_contents( $_FILES['import']['tmp_name'] ), true );
			if ( $json !== null && is_array( $json ) ) {
				// check for supported content
				if ( isset( $json['profiles'] ) || isset( $json['settings'] ) || isset( $json[0]['profile_id'] ) || isset( $json['ed_disable'] ) ) {
					$valid = true;
				}
			}
		}
	}
	
	if ( ! $valid ) {
		$tabid = "advtab";
		gde_show_msg( __('Please select a valid export file to import.', 'google-document-embedder'), true );
	} else {
		// process and import
		$import = true;
		$noload = "gentab";
		gde_import( $json );
	}
}

// maintain tab on form submission
if ( isset( $tabid ) && ! isset( $noload ) ) {
?>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#<?php echo $tabid; ?>').click();
	});
</script>

<?php
}

if ( ! $import ) {
?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>Google Doc Embedder <?php _e('Settings', 'google-document-embedder'); ?></h2>
		
	<div id="gdeadmintabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
		<ul class="nav-tab-wrapper ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
<?php
	if ( ! isset( $noload ) ) {
?>
			<li id="gentab" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="#general" class="nav-tab">
					<span><?php _e('General', 'google-document-embedder'); ?></span>
				</a>
			</li>
			<li id="protab" class="ui-state-default ui-corner-top">
				<a href="#profiles" class="nav-tab">
					<span><?php _e('Profiles', 'google-document-embedder'); ?></span>
				</a>
			</li>
<?php
	} else {
?>
			<li id="gentab-reload" class="ui-state-default ui-corner-top">
				<a href="#general" class="nav-tab">
					<span><?php _e('General', 'google-document-embedder'); ?></span>
				</a>
			</li>
			<li id="protab" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
				<a href="#profiles" class="nav-tab">
					<span><?php _e('Profiles', 'google-document-embedder'); ?></span>
				</a>
			</li>
<?php
	}
?>
			<li id="advtab" class="ui-state-default ui-corner-top">
				<a href="#advanced" class="nav-tab">
					<span><?php _e('Advanced', 'google-document-embedder'); ?></span>
				</a>
			</li>
			<!--li id="suptab" class="ui-state-default ui-corner-top">
				<a href="#support" class="nav-tab">
					<span><?php _e('Support', 'google-document-embedder'); ?></span>
				</a>
			</li-->
		</ul>
	</div>
	
	<div id="gde-tabcontent">
<?php
	if ( ! isset( $noload ) ) {
?>
		<div id="gencontent" class="gde-tab gde-tab-active">
			<?php gde_show_tab('general'); ?>
		</div>
		
		<div id="procontent" class="gde-tab">
			<?php gde_show_tab('profiles'); ?>
		</div>
		
<?php
	} else {
		// don't load gentab content if this is a profile edit (avoid js conflicts)
?>
		<div id="gencontent" class="gde-tab"></div>
		
		<div id="procontent" class="gde-tab gde-tab-active">
			<?php gde_show_tab('profiles'); ?>
		</div>
<?php
	}
?>
		
		<div id="advcontent" class="gde-tab">
			<?php gde_show_tab('advanced'); ?>
		</div>

		<div id="supcontent" class="gde-tab">
			<?php //gde_show_tab('support'); ?>
		</div>
	</div>
	
</div>

<?php
}

function gde_opts_checkbox( $field, $label, $wrap = '', $br = '', $disabled = false ) {
	global $gdeoptions;
	
	if ( ! empty( $wrap ) ) {
		echo '<span id="'.esc_attr($wrap).'">';
	}
	echo '<input type="checkbox" id="'.esc_attr($field).'" name="'.esc_attr($field).'"';
	if ( ( isset( $gdeoptions[$field] ) && $gdeoptions[$field] == "yes" ) || ( $disabled ) ) {
		echo ' checked="checked"';
	}
	if ( $disabled ) {
		// used only for dx logging option due to global override in functions.php
		echo ' disabled="disabled"';
	}
	
	echo ' value="'.esc_attr($field).'"> <label for="'.esc_attr($field).'">'.htmlentities($label).'</label>';
	if ( ! empty( $br ) ) {
		echo '<br/>';
	}
	if ( ! empty( $wrap ) ) {
		echo '</span>';
	}
}

function gde_profile_option( $option, $value, $label, $helptext = '' ) {
	echo "<option value=\"".esc_attr($value)."\"";
	if ( ! empty( $helptext ) ) {
		echo " title=\"".esc_attr($helptext)."\"";
	}
	if ( $option == $value ) {
		echo ' selected="selected"';
	}
	echo ">$label &nbsp;</option>\n";
}

function gde_profile_checkbox( $option, $field, $label, $wrap = '', $br = '' ) {
	if ( ! empty( $wrap ) ) {
		echo '<span id="'.esc_attr($wrap).'">';
	}
	echo '<input type="checkbox" id="'.esc_attr($field).'" name="'.esc_attr($field).'"';
	
	// toolbar items
	if ( substr( $field, 0, 5 ) == "gdet_" ) {
		if ( $field == "gdet_h" && strstr( $option, str_replace( "gdet_", "", $field ) ) ) {
			echo ' checked="checked"';
		} elseif ( $field !== "gdet_h" && ! strstr( $option, str_replace( "gdet_", "", $field ) ) ) {
			echo ' checked="checked"';
		}
	// open in new window
	} elseif ( $field == "fs_win" && $option !== "same" ) {
		echo ' checked="checked"';
	// logged-in users only
	} elseif ( $field == "fs_user" && $option == "yes" ) {
		echo ' checked="checked"';
	// allow print
	} elseif ( $field == "fs_print" && $option !== "no" ) {
		echo ' checked="checked"';
	// content area options
	} elseif  ( substr( $field, 0, 5 ) == "gdev_" ) {
		if ( strstr( $option, str_replace( "gdev_", "", $field ) ) ) {
			echo ' checked="checked"';
		}
	// doc security options
	} elseif ( $field == "force" && $option !== "no" ) {
		echo ' checked="checked"';
	} elseif ( $field == "mask" && $option !== "no" ) {
		echo ' checked="checked"';
	} elseif ( $field == "block" && $option !== "no" ) {
		echo ' checked="checked"';
	}
	
	echo ' value="'.esc_attr($field).'"> <label for="'.esc_attr($field).'">'.htmlentities($label).'</label>';
	if ( ! empty( $br ) ) {
		echo '<br/>';
	}
	if ( ! empty( $wrap ) ) {
		echo '</span>';
	}
}

function gde_profile_text( $option, $field, $class = '', $size = '', $enabled = true ) {
	echo '<input type="text" id="'.esc_attr($field).'" name="'.esc_attr($field).'" value="'.esc_attr($option).'"';
	if ( ! empty( $class ) ) {
		echo ' class="'.esc_attr($class).'"';
	}
	if ( ! empty( $size ) ) {
		echo ' size="'.esc_attr($size).'"';
	}
	if ( $enabled === false ) {
		echo ' disabled="disabled"';
		echo ' style="color:#aaa;background-color:#eee;"';
	}
	echo ">";
}

function gde_help_link( $url, $float = '' ) {
	$title = __('Help', 'google-document-embedder');
	$img = GDE_PLUGIN_URL . "img/help.png";
	
	if ( ! empty( $float ) ) {
		echo '<div style="float:'.esc_attr($float).';">';
	}
	
	echo '<a href="'.esc_attr($url).'" target="_blank" title="'.esc_attr($title).'"><img src="'.esc_attr($img).'" alt="?"></a>';
	
	if ( ! empty( $float ) ) {
		echo "</div>\n";
	}
}

function gde_row_cb( $pid ) {
	// default profile
	if ( $pid == 1 ) {
		return " ";
	} else {
		return '<input type="checkbox" value="'.esc_attr($pid).'" name="delete_tags[]">';
	}
}

function gde_row_actions( $pid ) {
	$actions = array(
		// action name	=>	arr ( label, class )
		"edit"		=>	array( __('Edit', 'google-document-embedder'), 'edit' ),
		"delete"	=>	array( __('Delete', 'google-document-embedder'), 'delete' ),
		"default"	=>	array( __('Make Default', 'google-document-embedder'), 'default' )
	);
	
	// protect default profile
	if ( $pid == 1 ) {
		unset( $actions['delete'], $actions['default'] );
	}
	
	foreach ($actions as $k => $v) {
		$act[] = '<span class="'.esc_attr($v[1]).'" id="'.esc_attr($k).'-'.esc_attr($pid).'"><a href="options-general.php?page=gde-settings">'.htmlentities($v[0]).'</a></span>';
	}
	$acts = implode( " | ", $act );
	
	return $acts;
}

?>
