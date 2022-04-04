<?php
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Definitions\Constants\Params as DefinitionsParams;

class PoP_SPA_RequestMeta_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            '\PoP\ComponentModel\Engine:site-meta',
            $this->getSiteMeta(...)
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
                DefinitionsParams::MANGLED,
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
            if (\PoP\Root\App::getState('stratum') && !\PoP\Root\App::getState('stratum-isdefault')) {
                $pushurlprops[\PoP\ConfigurationComponentModel\Constants\Params::STRATUM] = \PoP\Root\App::getState('stratum');
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
