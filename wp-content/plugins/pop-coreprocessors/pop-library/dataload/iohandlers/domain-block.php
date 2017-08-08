<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_DOMAINBLOCK', 'domain-block');

class GD_DataLoad_IOHandler_DomainBlock extends GD_DataLoad_BlockIOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_DOMAINBLOCK;
	}

	function get_backgroundurls($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {

		$backgroundload_urls = array();
		if ($domain = GD_Template_Processor_DomainUtils::get_domain_from_request()) {

			$homedomain = get_site_url();
			$bloginfo_url = get_bloginfo('url');

			// Add the loggedinuser-data page for that domain
			$url = get_permalink(POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA);
			// Also send the path along (without language information)
			$path = substr($url, strlen($bloginfo_url));
			$url = $domain.substr($url, strlen($homedomain));
			// Allow to override (eg: for a given domain, the page slug may be different)
			$url = apply_filters(
				'GD_DataLoad_IOHandler_DomainBlock:backgroundurls:loggedinuser_data',
				$url,
				$domain,
				$path
			);
			$backgroundload_urls[$url] = array(GD_URLPARAM_TARGET_MAIN);
	
			// Load all the Initial Frame loader pages for the domain
			foreach (PoP_Frontend_ConfigurationUtils::get_backgroundload_urls() as $url => $targets) {

				// Also send the path along (without language information)
				$path = substr($url, strlen($bloginfo_url));
				// By default, the URL simply changes from the home domain to the requested domain
				$url = $domain.substr($url, strlen($homedomain));
				// Allow to override (eg: for a given domain, the page slug may be different)
				$url = apply_filters(
					'GD_DataLoad_IOHandler_DomainBlock:backgroundurls:backgroundurl',
					$url,
					$domain,
					$path
				);
				$backgroundload_urls[$url] = $targets;
			}
		}

		// Allow to override these values
		return apply_filters('GD_DataLoad_IOHandler_DomainBlock:backgroundurls', $backgroundload_urls, $domain, $checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_DomainBlock();
