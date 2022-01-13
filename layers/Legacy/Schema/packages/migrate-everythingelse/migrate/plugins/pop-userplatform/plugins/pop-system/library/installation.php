<?php

class PoP_UserPlatform_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-install', array($this, 'install'));
    }

    public function install()
    {
        // Allow the library to add extra capabilities to below roles
        $capabilities = \PoP\Root\App::applyFilters(
            'PoP_UserPlatform_Installation:install:capabilities',
            array(
                'edit_posts' => true,
                'edit_published_posts' => true,
                'read' => true ,
                'upload_files' => true ,
                'level_0' => true ,
                'level_1' => true,
                'edit_published_pages' => true,
                'edit_others_pages' => true,
            )
        );

        $cmsuserrolesapi = \PoPSchema\UserRoles\FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRole(GD_ROLE_PROFILE, 'GD Profile', $capabilities);
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_Installation();
