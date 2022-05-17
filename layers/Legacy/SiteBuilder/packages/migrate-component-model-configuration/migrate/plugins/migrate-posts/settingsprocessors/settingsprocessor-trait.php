<?php
namespace PoPCMSSchema\Posts;

use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                PostsModuleConfiguration::getPostsRoute(),
            )
        );
    }
}
