<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * User Account Utils
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserAccountUtils {

	// public static function get_loggedinuserdata_urls() {

	// 	return apply_filters(
	// 		'gd_templatemanager:loggedinuserdata_urls',
	// 		array(
	// 			get_permalink(POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA),
	// 		)
	// 	);
	// }

	// public static function get_loggedinuserdata_domains() {

	// 	$domains = array();
	// 	if ($loggedinuserdata_urls = self::get_loggedinuserdata_urls()) {
		
	// 		foreach ($loggedinuserdata_urls as $url) {

	// 			$domains[] = get_domain($url);
	// 		}
	// 	}

	// 	return $domains;
	// }

	public static function get_loggedinuserdata_blocks() {

		// Allow Aryo to add the "Latest Notifications" block
		return apply_filters(
			'GD_Template_Processor_UserAccountUtils:loggedinuserdata:blocks',
			array(
				GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS,
				GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS,
				GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS,
				GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS,
				GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS,
			)
		);
	}

	public static function get_login_blocks() {

		// Allow WSL to add the FB/Twitter Login
		return apply_filters(
			'GD_Template_Processor_UserAccountUtils:login:blocks',
			array(
				GD_TEMPLATE_BLOCK_FOLLOWSUSERS,
				GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS,
				GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS,
				GD_TEMPLATE_BLOCK_UPVOTESPOSTS,
				GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS,
			)
		);
	}
}