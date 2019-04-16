<?php
namespace PoP\CMS\WP;

class FrontendCMSLooseContractImplementations
{
	function __construct() {
		
		$hooksapi = \PoP\CMS\HooksAPI_Factory::getInstance();

		// Actions
		$hooksapi->addAction('wp_footer', function() use($hooksapi) {
			$hooksapi->doAction('popcms:footer');
		});
		$hooksapi->addAction('wp_enqueue_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:enqueueScripts');
		});
		$hooksapi->addAction('wp_print_footer_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printFooterScripts');
		});
		$hooksapi->addAction('wp_head', function() use($hooksapi) {
			$hooksapi->doAction('popcms:head');
		});
		$hooksapi->addAction('wp_print_styles', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printStyles');
		});
		$hooksapi->addAction('wp_print_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printScripts');
		});

		$loosecontract_manager = \PoP\CMS\CMSLooseContract_Manager_Factory::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:footer',
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:head',
			'popcms:printStyles',
			'popcms:printScripts',
		]);

		// Filters
		$hooksapi->addFilter('style_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('script_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('script_loader_tag', function($tag, $handle, $src) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptTag', $tag, $handle, $src);
		}, 10, 3);
		$hooksapi->addFilter('style_loader_tag', function($tag, $handle, $href, $media) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleTag', $tag, $handle, $href, $media);
		}, 10, 4);

		$loosecontract_manager->implementHooks([
			'popcms:styleSrc',
			'popcms:scriptSrc',
			'popcms:scriptTag',
			'popcms:styleTag',
		]);
	}
}

/**
 * Initialize
 */
new FrontendCMSLooseContractImplementations();

