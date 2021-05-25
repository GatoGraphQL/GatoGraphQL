<?php

namespace PoPSchema\PostCategories;

use PoPSchema\PostCategories\ComponentConfiguration as PostCategoriesComponentConfiguration;

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
