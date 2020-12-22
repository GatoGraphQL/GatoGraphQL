<?php
namespace PoPSchema\Posts;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTS_ROUTE_POSTS,
            )
        );
    }
}
