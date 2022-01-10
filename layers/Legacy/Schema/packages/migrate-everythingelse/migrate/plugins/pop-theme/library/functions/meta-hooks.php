<?php
namespace PoP\Theme;

use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Theme_Meta_Hooks
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
        if (RequestUtils::fetchingSite()) {
            $vars = ApplicationState::getVars();

            // Send the current selected theme back
            if (\PoP\Root\App::getState('theme') ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEME] = \PoP\Root\App::getState('theme');
            }
            if (\PoP\Root\App::getState('thememode') ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEMEMODE] = \PoP\Root\App::getState('thememode');
            }
            if (\PoP\Root\App::getState('themestyle') ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEMESTYLE] = \PoP\Root\App::getState('themestyle');
            }

            $pushurlprops = array();

            // Theme: send only when it's not the default one (so the user can still see/copy/share the embed/print URL)
            if (isset(\PoP\Root\App::getState('theme')) && !\PoP\Root\App::getState('theme-isdefault')) {
                $pushurlprops[GD_URLPARAM_THEME] = \PoP\Root\App::getState('theme');
            }
            if (isset(\PoP\Root\App::getState('thememode')) && !\PoP\Root\App::getState('thememode-isdefault')) {
                $pushurlprops[GD_URLPARAM_THEMEMODE] = \PoP\Root\App::getState('thememode');
            }
            if (isset(\PoP\Root\App::getState('themestyle')) && !\PoP\Root\App::getState('themestyle-isdefault')) {
                $pushurlprops[GD_URLPARAM_THEMESTYLE] = \PoP\Root\App::getState('themestyle');
            }

            if ($pushurlprops) {
                $meta[ParamConstants::PUSHURLATTS] = array_merge(
                    $meta[ParamConstants::PUSHURLATTS] ?? array(),
                    $pushurlprops
                );
            }
        }

        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_Theme_Meta_Hooks();
