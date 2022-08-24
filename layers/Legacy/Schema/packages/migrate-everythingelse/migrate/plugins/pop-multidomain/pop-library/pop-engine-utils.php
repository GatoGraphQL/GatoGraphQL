<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_MultiDomain_Engine_Utils
{
    /**
     * @todo Migrate to AppStateProvider
     * @param array<mixed[]> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        $vars['domain'] = \PoP\Root\App::query(POP_URLPARAM_DOMAIN);

        // Add the external URL's domain, only if we are on the External Page
        if (\PoP\Root\App::getState(['routing', 'is-generic']) && $vars['route'] == POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
            if ($external_url = \PoP\Root\App::query(\PoP\ComponentModel\Constants\Response::URL)) {
                $vars['external-url-domain'] = GeneralUtils::getDomain($external_url);
            }
        }
    }

    public static function addModuleInstanceComponents($components)
    {
        if ($domain = App::getState('domain')) {
            $components[] = TranslationAPIFacade::getInstance()->__('domain:', 'pop-multidomain').RequestUtils::getDomainID($domain);
        }
        // External domain different configuration: needed for the resourceLoader config.js file to load, cached in the list under pop-cache/resources/,
        // which is different for different domains
        if ($external_url_domain = App::getState('external-url-domain')) {
            $components[] = TranslationAPIFacade::getInstance()->__('external url domain:', 'pop-multidomain').RequestUtils::getDomainID($external_url_domain);
        }

        return $components;
    }
}

/**
 * Initialization
 */
\PoP\Root\App::addAction('ApplicationState:addVars', array(PoP_MultiDomain_Engine_Utils::class, 'addVars'), 10, 1);
\PoP\Root\App::addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_ELEMENTS_RESULT, array(PoP_MultiDomain_Engine_Utils::class, 'addModuleInstanceComponents'));
