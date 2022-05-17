<?php

namespace PoPCMSSchema\PostCategories;

use PoPCMSSchema\PostCategories\ModuleConfiguration as PostCategoriesModuleConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                PostCategoriesModuleConfiguration::getPostCategoriesRoute(),
            )
        );
    }
}
