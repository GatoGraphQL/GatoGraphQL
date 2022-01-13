<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * Replace the "no alternate language available" message
 * Otherwise it prints:
 * Sorry, this entry is only available in Malay and English. For the sake of viewer convenience, the content is shown below in this site default language. You may click one of the links to switch the site language to another available language.
 */
HooksAPIFacade::getInstance()->addFilter('popcomponent:multilingual:notavailablecontenttranslation', 'multilingualUseBlockAltlang', 10, 6);
function multilingualUseBlockAltlang($output, $lang, $language_list, $alt_lang, $alt_content, $msg)
{
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    return sprintf(
        '<p class="text-warning bg-warning">%s</p><hr/>%s',
        sprintf(
            TranslationAPIFacade::getInstance()->__('Oops, it seems this content is not available in <strong>%s</strong>. <a href="%s">Would you like to help us translate it?</a>', 'pop-multilingual'),
            $pluginapi->getLanguageName($lang),
            RouteUtils::getRouteURL(POP_CONTACTUS_ROUTE_CONTACTUS)
        ),
        $alt_content
    );
}
