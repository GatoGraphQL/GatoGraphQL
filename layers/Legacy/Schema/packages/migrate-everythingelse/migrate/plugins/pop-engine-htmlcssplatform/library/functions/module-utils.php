<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_HTMLCSSPlatformEngine_Module_Utils
{
    public static function getMainHtml()
    {

        // Allow PoP SSR to inject the server-side rendered HTML
        return HooksAPIFacade::getInstance()->applyFilters(
            'htmlcssplatform-engine:main_html',
            ''
        );
    }
}
