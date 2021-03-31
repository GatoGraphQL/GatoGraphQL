<?php

namespace PoPSchema\PostCategories;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTCATEGORIES_ROUTE_POSTCATEGORIES,
            )
        );
    }
}
