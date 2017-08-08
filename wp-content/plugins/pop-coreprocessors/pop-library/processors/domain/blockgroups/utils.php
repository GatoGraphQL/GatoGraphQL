<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * User Account Utils
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DomainUtils {

	public static function get_domain_from_request() {

		$domain = $_REQUEST[POP_URLPARAM_DOMAIN];
		return $domain ? urldecode($domain) : '';
	}

	public static function get_initializedomain_blocks() {

		return apply_filters(
			'GD_Template_Processor_DomainUtils:initializedomain:blocks',
			array(
				GD_TEMPLATE_BLOCK_DOMAINSTYLES,
			)
		);
	}
}