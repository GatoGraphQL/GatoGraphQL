<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CrawlableDataPrinter_Utils {

	public static function strip_content_tags($value) {
	
		// Only allow the specified tags, strip all the rest, eg: iframe.
		return strip_tags($value, '<p><a><img><b><strong><br><table><tbody><th><tr><td><small><sub><sup><title><blockquote><h1><h2><h3><h4><h5><h6><abbr><acronym><address><caption><cite><dd><dt><dl><em><ul><ol><li><pre><strike><tfoot><tt><u>');
	}
}	