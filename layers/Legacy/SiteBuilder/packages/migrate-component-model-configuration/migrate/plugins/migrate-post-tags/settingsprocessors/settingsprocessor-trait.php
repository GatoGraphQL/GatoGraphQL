<?php
namespace PoPCMSSchema\PostTags;

use PoPCMSSchema\PostTags\ModuleConfiguration as PostTagsModuleConfiguration;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                PostTagsModuleConfiguration::getPostTagsRoute(),
            )
        );
    }
}
