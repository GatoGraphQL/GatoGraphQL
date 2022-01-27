<?php
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class QTX_PoP_Multilingual_LooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();
		$loosecontract_manager = LooseContractManagerFacade::getInstance();

		// Filters
		$hooksapi->addFilter('i18n_content_translation_not_available', function($output, $lang, $language_list, $alt_lang, $alt_content, $msg) use($hooksapi) {
			return $hooksapi->applyFilters('popcomponent:multilingual:notavailablecontenttranslation', $output, $lang, $language_list, $alt_lang, $alt_content, $msg);
		}, 10, 6);

		$loosecontract_manager->implementHooks([
			'popcomponent:multilingual:notavailablecontenttranslation',
		]);
	}
}

/**
 * Initialize
 */
new QTX_PoP_Multilingual_LooseContractImplementations();

