<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\RequestUtils;

class PoP_MultiDomain_Engine_Utils
{
    /**
     * @param array<array> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        $vars['domain'] = $_REQUEST[POP_URLPARAM_DOMAIN] ?? null;

        // Add the external URL's domain, only if we are on the External Page
        if ($vars['routing-state']['is-standard'] && $vars['route'] == POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
            if ($external_url = $_REQUEST[\PoP\ComponentModel\Constants\Response::URL] ?? null) {
                $vars['external-url-domain'] = getDomain($external_url);
            }
        }
    }

    public static function addModuleInstanceComponents($components)
    {
        $vars = ApplicationState::getVars();
        if ($domain = $vars['domain']) {
            $components[] = TranslationAPIFacade::getInstance()->__('domain:', 'pop-multidomain').RequestUtils::getDomainId($domain);
        }
        // External domain different configuration: needed for the resourceLoader config.js file to load, cached in the list under pop-cache/resources/,
        // which is different for different domains
        if ($external_url_domain = $vars['external-url-domain']) {
            $components[] = TranslationAPIFacade::getInstance()->__('external url domain:', 'pop-multidomain').RequestUtils::getDomainId($external_url_domain);
        }

        return $components;
    }
}

/**
 * Initialization
 */
HooksAPIFacade::getInstance()->addAction('ApplicationState:addVars', array(PoP_MultiDomain_Engine_Utils::class, 'addVars'), 10, 1);
HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, array(PoP_MultiDomain_Engine_Utils::class, 'addModuleInstanceComponents'));
