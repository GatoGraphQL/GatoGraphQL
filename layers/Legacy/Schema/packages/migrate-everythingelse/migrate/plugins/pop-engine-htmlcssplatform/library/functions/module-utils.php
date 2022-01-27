<?php

class PoP_HTMLCSSPlatformEngine_Module_Utils
{
    public static function getMainHtml()
    {

        // Allow PoP SSR to inject the server-side rendered HTML
        return \PoP\Root\App::applyFilters(
            'htmlcssplatform-engine:main_html',
            ''
        );
    }
}
