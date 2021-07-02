<?php
namespace PoP\Theme;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Application\QueryInputOutputHandlers\ParamConstants;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\RequestUtils;

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
            if ($vars['theme'] ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEME] = $vars['theme'];
            }
            if ($vars['thememode'] ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEMEMODE] = $vars['thememode'];
            }
            if ($vars['themestyle'] ?? null) {
                $meta[ParamConstants::PARAMS][GD_URLPARAM_THEMESTYLE] = $vars['themestyle'];
            }

            $pushurlprops = array();

            // Theme: send only when it's not the default one (so the user can still see/copy/share the embed/print URL)
            if (isset($vars['theme']) && !$vars['theme-isdefault']) {
                $pushurlprops[GD_URLPARAM_THEME] = $vars['theme'];
            }
            if (isset($vars['thememode']) && !$vars['thememode-isdefault']) {
                $pushurlprops[GD_URLPARAM_THEMEMODE] = $vars['thememode'];
            }
            if (isset($vars['themestyle']) && !$vars['themestyle-isdefault']) {
                $pushurlprops[GD_URLPARAM_THEMESTYLE] = $vars['themestyle'];
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
