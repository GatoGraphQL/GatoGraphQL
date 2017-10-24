<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ConfigurationUtils {

	public static function get_backgroundload_urls() {

		// return apply_filters(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD, array());
		$url_targets = array();
		foreach (self::get_backgroundload_pages() as $page => $targets) {
			
			$url_targets[get_permalink($page)] = $targets;
		}

		return apply_filters('PoP_Frontend_ConfigurationUtils:backgroundload_urls', $url_targets);
	}

	public static function get_backgroundload_pages() {

		return apply_filters(POP_HOOK_POPFRONTEND_BACKGROUNDLOAD, array());
	}

	public static function get_allowed_domains() {

		$homeurl = get_site_url();
		return array_values(array_unique(apply_filters(
			'gd_templatemanager:allowed_domains',
			array(
				$homeurl,
			)
		)));
	}

	// public static function get_allowed_domains() {

	// 	$domains = array();
	// 	foreach (self::get_allowed_urls() as $url) {

	// 		$domain[] = get_domain($url);
	// 	}
	// 	return array_unique($domains);
	// }

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

	// public static function get_multidomain_websites() {

	// 	$domain_properties = array(
	// 		get_site_url() => array(
	// 			'name' => get_bloginfo('name'),
	// 			'description' => get_bloginfo('description'),
	// 		)
	// 	);
	// 	$domain_properties = apply_filters('PoP_MultiDomain_Utils:domain_properties', $domain_properties);	

	// 	// Add the ID to all domains
	// 	foreach ($domain_properties as $domain => &$properties) {
			
	// 		// Comment Leo 24/08/2017: no need for the pre-defined ID
	// 		// $properties['id'] = GD_TemplateManager_Utils::get_domain_id($domain);

	// 		// Allow to add the language, and then change the default language on a domain by domain basis
	// 		$properties['locale'] = apply_filters(
	// 			'gd_templatemanager:multidomain:locale',
	// 			$domain,
	// 			$domain
	// 		);
	// 	}
	// 	return $domain_properties;
	// }
}