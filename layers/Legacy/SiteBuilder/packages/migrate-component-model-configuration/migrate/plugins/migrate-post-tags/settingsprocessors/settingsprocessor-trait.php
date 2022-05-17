<?php
namespace PoPCMSSchema\PostTags;

use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsComponentConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                PostTagsComponentConfiguration::getPostTagsRoute(),
            )
        );
    }
}
