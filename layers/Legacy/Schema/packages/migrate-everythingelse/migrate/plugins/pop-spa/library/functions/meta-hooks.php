<?php
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Configuration\Request;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_SPA_RequestMeta_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine:site-meta',
            array($this, 'getSiteMeta')
        );
    }

    public function getSiteMeta($meta)
    {
        // Comment Leo 05/04/2017: Create the params array only in the fetchingSite()
        // Before it was outside, and calling the initial-frames page brought params=[],
        // and this was overriding the params in the topLevelFeedback removing all info there
        if (RequestUtils::fetchingSite()) {
            // Send params back to the server
            $meta[ParamConstants::PARAMS] = array();
            $elemKeys = [
                \PoP\ComponentModel\Constants\Params::VERSION,
                \PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE,
                \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE,
                \PoP\ConfigurationComponentModel\Constants\Params::SETTINGSFORMAT,
                Request::URLPARAM_MANGLED,
                \PoP\ConfigurationComponentModel\Constants\Params::STRATUM,
            ];
            foreach ($elemKeys as $elemKey) {
                if ($elemValue = $meta[$elemKey] ?? null) {
                    $meta[ParamConstants::PARAMS][$elemKey] = $elemValue;
                }
            }

            // Push atts
            $pushurlprops = [];

            // Platform: send only when it's not the default one (so the user can still see/copy/share the embed/print URL)
            $vars = ApplicationState::getVars();
            if ($vars['stratum'] && !$vars['stratum-isdefault']) {
                $pushurlprops[\PoP\ConfigurationComponentModel\Constants\Params::STRATUM] = $vars['stratum'];
            }

            if ($pushurlprops) {
                $meta[ParamConstants::PUSHURLATTS] = $pushurlprops;
            }
        }
        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_SPA_RequestMeta_Hooks();
