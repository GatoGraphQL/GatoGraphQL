<?php

namespace PoPCMSSchema\PostCategories;

use PoPCMSSchema\PostCategories\ModuleConfiguration as PostCategoriesComponentConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                PostCategoriesComponentConfiguration::getPostCategoriesRoute(),
            )
        );
    }
}
