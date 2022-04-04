<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::addFilter('gd_catname', getpopdemoCategorypostsCatname(...), 10, 3);
function getpopdemoCategorypostsCatname($name, $cat_id, $format)
{
    switch ($cat_id) {
        case GETPOPDEMO_PAGES_CATPLACEHOLDER_ARTICLES:
        case GETPOPDEMO_PAGES_CATPLACEHOLDER_ANNOUNCEMENTS:
        case GETPOPDEMO_PAGES_CATPLACEHOLDER_RESOURCES:
        case GETPOPDEMO_PAGES_CATPLACEHOLDER_BLOG:
            $plurals = array(
                GETPOPDEMO_PAGES_CATPLACEHOLDER_ARTICLES => TranslationAPIFacade::getInstance()->__('Articles', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_ANNOUNCEMENTS => TranslationAPIFacade::getInstance()->__('Announcements', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_RESOURCES => TranslationAPIFacade::getInstance()->__('Resources', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_BLOG => TranslationAPIFacade::getInstance()->__('Blog', 'getpop-demo-pages'),
            );
            $singulars = array(
                GETPOPDEMO_PAGES_CATPLACEHOLDER_ARTICLES => TranslationAPIFacade::getInstance()->__('Article', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_ANNOUNCEMENTS => TranslationAPIFacade::getInstance()->__('Announcement', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_RESOURCES => TranslationAPIFacade::getInstance()->__('Resource', 'getpop-demo-pages'),
                GETPOPDEMO_PAGES_CATPLACEHOLDER_BLOG => TranslationAPIFacade::getInstance()->__('Blog', 'getpop-demo-pages'),
            );
            return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
    }

    return $name;
}
