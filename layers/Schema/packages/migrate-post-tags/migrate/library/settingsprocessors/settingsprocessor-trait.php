<?php
namespace PoPSchema\PostTags;

use PoPSchema\PostTags\ComponentConfiguration as PostTagsComponentConfiguration;

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
