<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Add the language to the links to PoP and Verticals
 */
\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_CustomPageSections:footer:poweredby-links', 'gdQtransxFooterlinks');
function gdQtransxFooterlinks($link)
{

    // Because both PoP and Verticals are in EN and ES languages, add the corresponding language if the current website supports them
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        $current = $pluginapi->getCurrentLanguage();
    if (in_array($current, POPTHEME_WASSUP_QTRANS_LANG_POWEREDBYWEBSITES)) {
        if ($pluginapi->getUrlModificationMode() == POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH) {
            $link = GeneralUtils::maybeAddTrailingSlash($link).$current.'/';
        }
    }

    return $link;
}

\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_HTMLCodes:homewelcometitle', 'gdQtransxWelcomeLanguagelinks');
function gdQtransxWelcomeLanguagelinks($title)
{
    if ($items = getMultilingualLanguageitems()) {
        $title .= sprintf(
            '<span class="language-links">%s</span>',
            implode('&nbsp;', $items)
        );
    }

    return $title;
}
