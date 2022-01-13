<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_CreateUpdate_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'popcms:editPostLink',
            array($this, 'editPostLink'),
            100,
            2
        );
    }

    public function editPostLink($link, $post_id)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()) {
            // Hook to set the URL for other post types
            $url = HooksAPIFacade::getInstance()->applyFilters(
                'gd-createupdateutils:edit-url',
                '',
                $post_id
            );

            if ($url) {
                        $link = gdGetNonceUrl(GD_NONCE_EDITURL, $url, $post_id);
                $link = GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $post_id,
                ], $link);
            }
        }

        return $link;
    }
}

/**
 * Initialization
 */
new GD_CreateUpdate_Hooks();
