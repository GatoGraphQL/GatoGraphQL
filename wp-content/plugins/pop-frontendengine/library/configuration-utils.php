<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ConfigurationUtils {

	public static function get_allowed_urls() {

		$homeurl = get_site_url();
		return array_values(array_unique(apply_filters(
			'gd_templatemanager:allowed_urls',
			array(
				$homeurl,
			)
		)));
	}

	public static function get_multilayout_labels() {

		return apply_filters('gd_templatemanager:multilayout_labels', array());
	}

	public static function get_multilayout_keyfields() {

		return apply_filters('gd_templatemanager:multilayout_keyfields', array(
			'posts' => array('post-type', 'cat'),
			'locations' => array('post-type', 'cat'),
			'users' => array('role'),
			// 'notifications' => POP_MULTILAYOUT_TYPE_NOTIFICATION,
		));
	}

	public static function get_ondate_string() {

		return apply_filters(
			'gd_templatemanager:ondate', 
			__('<small>on</small> %s', 'pop-frontendengine')
		);
	}

	public static function get_status_settings() {

		$status = array(
			'class' => array(
				'draft' => 'label-info',
				'pending' => 'label-warning',
				'publish' => 'label-success'
			),
			'text' => array(
				'draft' => __('Draft', 'pop-frontendengine'),
				'pending' => __('Pending to be published', 'pop-frontendengine'),
				'publish' => __('Published', 'pop-frontendengine')
			)
		);
		// Allow to override: allow URE to add its Member Status
		return apply_filters('gd_templatemanager:status_settings', $status);
	}

	public static function get_labelize_classes() {

		$labelize_classes = array(
			__('(None)', 'pop-frontendengine') => 'label-none',
		);
		return apply_filters('gd_templatemanager:labelize_classes', $labelize_classes);	
	}
}