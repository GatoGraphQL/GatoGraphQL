<?php
namespace PoP\EngineWebPlatform\WP;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class WebPlatformCMSLooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();

		// Actions
		$hooksapi->addAction('wp_enqueue_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:enqueueScripts');
		});
		$hooksapi->addAction('wp_print_footer_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printFooterScripts');
		});
		$hooksapi->addAction('wp_print_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printScripts');
		});

		$loosecontract_manager = LooseContractManagerFacade::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:printScripts',
		]);

		// Filters
		$hooksapi->addFilter('script_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('script_loader_tag', function($tag, $handle, $src) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptTag', $tag, $handle, $src);
		}, 10, 3);
		
		$loosecontract_manager->implementHooks([
			'popcms:scriptSrc',
			'popcms:scriptTag',
		]);
	}
}

/**
 * Initialize
 */
new WebPlatformCMSLooseContractImplementations();

