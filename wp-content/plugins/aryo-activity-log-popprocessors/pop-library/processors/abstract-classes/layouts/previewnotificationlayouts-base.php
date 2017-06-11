<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PreviewNotificationLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION;
	}

	function get_quicklinkgroup_top($template_id) {

		return GD_AAL_TEMPLATE_QUICKLINKGROUP_NOTIFICATION;
	}
	function get_quicklinkgroup_bottom($template_id) {

		return GD_AAL_TEMPLATE_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM;
	}
	function get_link_template($template_id) {

		return GD_AAL_TEMPLATE_BUTTON_NOTIFICATIONPREVIEWLINK;
	}

	// function add_url($template_id, $atts) {

	// 	return true;
	// }

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($link = $this->get_link_template($template_id)) {
			$ret[] = $link;
		}
		if ($quicklinkgroup_top = $this->get_quicklinkgroup_top($template_id)) {
			$ret[] = $quicklinkgroup_top;
		}
		if ($quicklinkgroup_bottom = $this->get_quicklinkgroup_bottom($template_id)) {
			$ret[] = $quicklinkgroup_bottom;
		}
		if ($bottom_templates = $this->get_bottom_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$bottom_templates
			);
		}
		if ($post_thumb = $this->get_post_thumb_template($template_id)) {
			$ret[] = $post_thumb;
		}

		// Different combinations of Object Types + Action can load extra info for their target objects (those under field "object-id")
		// To differentiate among them, we use different fields. Eg: 'post-object-id', 'comment-object-id', etc
		// Then, the fieldprocessor can retrieve these values only if they apply to that combination of Object Type + Action
		// $ret = array_merge(
		// 	$ret, 
		// 	$this->get_target_layouts($template_id)
		// );

		return $ret;
	}

	function get_subcomponent_modules($template_id) {

		$ret = parent::get_subcomponent_modules($template_id);

		$modules = array();

		// Show author avatar: only if no thumb template defined, and author avatar is defined
		if (!$this->get_post_thumb_template($template_id)) {

			if ($user_avatar = $this->get_user_avatar_template($template_id)) {

				$modules[] = $user_avatar;
			}
		}

		if ($modules) {

			$ret['user-id'] = array(
				'modules' => $modules,
				'dataloader' => GD_DATALOADER_USERLIST
			);
		}

		return $ret;
	}

	// protected function get_target_layouts($template_id) {

	// 	return apply_filters(
	// 		'GD_Template_Processor_PreviewNotificationLayoutsBase:target_layouts',
	// 		array(
	// 			GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT
	// 		)
	// 	);
	// }

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		// From the combination of object_type and action, we obtain the layout to use for the notification
		// $ret[] = 'object-type';
		// $ret[] = 'action';

		// $ret[] = 'url';
		$ret[] = 'status';
		$ret[] = 'message';
		
		return $ret;
	}

	function get_post_thumb_template($template_id) {

		return null;
	}
	function get_user_avatar_template($template_id) {

		return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR;
	}
	
	function get_bottom_layouts($template_id) {

		// GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
		// Different combinations of Object Types + Action can load extra info for their target objects (those under field "object-id")
		// To differentiate among them, we use different fields. Eg: 'post-object-id', 'comment-object-id', etc
		// Then, the fieldprocessor can retrieve these values only if they apply to that combination of Object Type + Action

		return apply_filters(
			'GD_Template_Processor_PreviewNotificationLayoutsBase:get_bottom_layouts',
			array(
				GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT,
				GD_TEMPLATE_LAYOUT_NOTIFICATIONICON,
				GD_TEMPLATE_LAYOUT_NOTIFICATIONTIME,
			),
			$template_id
		);
	}

	function horizontal_media_layout($template_id) {

		return true;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		global $gd_template_processor_manager;

		// if ($this->add_url($template_id, $atts)) {

		// 	$ret['add-url'] = true;
		// }

		// Classes
		$ret[GD_JS_CLASSES/*'classes'*/] = array();
		if ($this->horizontal_media_layout($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'media';// ' overflow-visible';
			$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] = 'pull-left';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'media-body clearfix';
		}

		if ($link = $this->get_link_template($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['link'] = $gd_template_processor_manager->get_processor($link)->get_settings_id($link);
		}
		if ($quicklinkgroup_top = $this->get_quicklinkgroup_top($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['quicklinkgroup-top'] = $gd_template_processor_manager->get_processor($quicklinkgroup_top)->get_settings_id($quicklinkgroup_top);
		}
		if ($quicklinkgroup_bottom = $this->get_quicklinkgroup_bottom($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['quicklinkgroup-bottom'] = $gd_template_processor_manager->get_processor($quicklinkgroup_bottom)->get_settings_id($quicklinkgroup_bottom);
		}
		if ($bottom_templates = $this->get_bottom_layouts($template_id)) {
			
			$ret[GD_JS_CLASSES/*'classes'*/]['bottom'] = 'clearfix';
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['bottom'] = $bottom_templates;
			foreach ($bottom_templates as $bottom_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$bottom_template] = $gd_template_processor_manager->get_processor($bottom_template)->get_settings_id($bottom_template);
			}
		}

		if ($post_thumb = $this->get_post_thumb_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['postthumb'] = $gd_template_processor_manager->get_processor($post_thumb)->get_settings_id($post_thumb);
		}
		else {

			if ($user_avatar = $this->get_user_avatar_template($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['user-avatar'] = $gd_template_processor_manager->get_processor($user_avatar)->get_settings_id($user_avatar);
			}
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
			
		if (in_array(GD_AAL_TEMPLATE_QUICKLINKGROUP_NOTIFICATION, $this->get_modules($template_id))) {

			//-----------------------------------
			// Whenever clicking on the link on the notification, also "click" on the `Mark as read` button
			//-----------------------------------
			if ($link = $this->get_link_template($template_id)) {

				$this->merge_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD, $atts, 'previoustemplates-ids', array(
					'data-triggertarget' => $link
				));
				$this->merge_block_jsmethod_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD, $atts, array('onActionThenClick'));
			}
			
			//-----------------------------------
			// Add class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
			//-----------------------------------
			$this->merge_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD, $atts, 'params', array(
				'data-mode' => 'add',
				'data-class' => AAL_POP_STATUS_READ,
			));
			$this->merge_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD, $atts, 'previoustemplates-ids', array(
				'data-target' => $template_id,
			));
			$this->merge_block_jsmethod_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASREAD, $atts, array('switchTargetClass'));

			//-----------------------------------
			// Remove class "read" to the notification layout to make it appear as read or not when clicking on the corresponding button
			//-----------------------------------
			$this->merge_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD, $atts, 'params', array(
				'data-mode' => 'remove',
				'data-class' => AAL_POP_STATUS_READ,
			));
			$this->merge_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD, $atts, 'previoustemplates-ids', array(
				'data-target' => $template_id,
			));
			$this->merge_block_jsmethod_att(GD_AAL_TEMPLATE_BUTTON_NOTIFICATION_MARKASUNREAD, $atts, array('switchTargetClass'));
		}
		
		return parent::init_atts($template_id, $atts);
	}
}