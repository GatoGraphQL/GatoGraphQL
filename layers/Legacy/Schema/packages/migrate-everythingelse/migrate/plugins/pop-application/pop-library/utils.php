<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_Application_Utils
{
    public static function getContentpostsectionCats()
    {
        return \PoP\Root\App::applyFilters('wassup_contentpostsection_cats', array());
    }

    public static function getRequestDomain()
    {
        // Allow PoP Multidomain to override this value with the domain in the $_REQUEST
        $cmsService = CMSServiceFacade::getInstance();
        return \PoP\Root\App::applyFilters(
            'PoP_Application_Utils:request-domain',
            $cmsService->getSiteURL()
        );
    }

    public static function getDefaultformatByScreen($screen)
    {
        $format = '';
        switch ($screen) {
         // The screens below need no formatting, since they have only 1 available format anyway
         // case POP_SCREEN_ABOUTUS:
         // case POP_SCREEN_ACCOUNT:
         // case POP_SCREEN_INFORMATIONPAGE:
         // case POP_SCREEN_MYPROFILE:
         // case POP_SCREEN_SINGLE:
         // case POP_SCREEN_AUTHOR:
            case POP_SCREEN_SECTION:
            case POP_SCREEN_AUTHORSECTION:
            case POP_SCREEN_SINGLESECTION:
            case POP_SCREEN_TAGSECTION:
            case POP_SCREEN_HOMESECTION:
                $format = POP_FORMAT_SIMPLEVIEW;
                break;

            case POP_SCREEN_MYCONTENT:
            case POP_SCREEN_MYHIGHLIGHTS:
                $format = POP_FORMAT_TABLE;
                break;
                
            case POP_SCREEN_NOTIFICATIONS:
            case POP_SCREEN_HIGHLIGHTS:
            case POP_SCREEN_SINGLEHIGHLIGHTS:
                $format = POP_FORMAT_LIST;
                break;
                
            case POP_SCREEN_USERS:
            case POP_SCREEN_AUTHORUSERS:
            case POP_SCREEN_SINGLEUSERS:
            case POP_SCREEN_TAGUSERS:
            case POP_SCREEN_TAGS:
            case POP_SCREEN_AUTHORTAGS:
                $format = POP_FORMAT_DETAILS;
                break;
        }

        return \PoP\Root\App::applyFilters(
            'PoP_Application_Utils:defaultformat_by_screen',
            $format,
            $screen
        );
    }

    public static function getPreviewTarget()
    {

        // By default, create new content in the Addons pageSection
        return \PoP\Root\App::applyFilters('PoP_Application_Utils:preview_target', POP_TARGET_QUICKVIEW);
    }

    public static function getAddcontentTarget()
    {

        // By default, create new content in the Addons pageSection
        return \PoP\Root\App::applyFilters('PoP_Application_Utils:addcontent_target', POP_TARGET_ADDONS);
    }
}
