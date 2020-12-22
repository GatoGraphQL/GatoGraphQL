<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\RequestUtils;

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
                GD_URLPARAM_VERSION,
                GD_URLPARAM_DATAOUTPUTMODE,
                GD_URLPARAM_DATABASESOUTPUTMODE,
                GD_URLPARAM_SETTINGSFORMAT,
                Request::URLPARAM_MANGLED,
                POP_URLPARAM_CONFIG,
                GD_URLPARAM_STRATUM,
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
                $pushurlprops[GD_URLPARAM_STRATUM] = $vars['stratum'];
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
