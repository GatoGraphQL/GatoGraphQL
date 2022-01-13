<?php
namespace PoP\EngineHTMLCSSPlatform\WP;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class HTMLCSSPlatformCMSLooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();

		// Actions
		$hooksapi->addAction('wp_footer', function() use($hooksapi) {
			$hooksapi->doAction('popcms:footer');
		});
		$hooksapi->addAction('wp_head', function() use($hooksapi) {
			$hooksapi->doAction('popcms:head');
		});
		$hooksapi->addAction('wp_print_styles', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printStyles');
		});

		$loosecontract_manager = LooseContractManagerFacade::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:footer',
			'popcms:head',
			'popcms:printStyles',
		]);

		// Filters
		$hooksapi->addFilter('style_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('style_loader_tag', function($tag, $handle, $href, $media) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleTag', $tag, $handle, $href, $media);
		}, 10, 4);

		$loosecontract_manager->implementHooks([
			'popcms:styleSrc',
			'popcms:styleTag',
		]);
	}
}

/**
 * Initialize
 */
new HTMLCSSPlatformCMSLooseContractImplementations();

