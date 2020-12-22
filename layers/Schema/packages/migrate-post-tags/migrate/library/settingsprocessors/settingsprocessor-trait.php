<?php
namespace PoPSchema\PostTags;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTTAGS_ROUTE_POSTTAGS,
            )
        );
    }
}
