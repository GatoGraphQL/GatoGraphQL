<?php
namespace PoPSchema\Categories;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CATEGORIES_ROUTE_CATEGORIES,
            )
        );
    }
}
