<?php
namespace PoP\CMSModel;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CMSMODEL_ROUTE_LOADERS_POSTS_FIELDS,
                POP_CMSMODEL_ROUTE_LOADERS_USERS_FIELDS,
                POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_FIELDS,
                POP_CMSMODEL_ROUTE_LOADERS_TAGS_FIELDS,
                POP_CMSMODEL_ROUTE_LOADERS_POSTS_LAYOUTS,
                POP_CMSMODEL_ROUTE_LOADERS_USERS_LAYOUTS,
                POP_CMSMODEL_ROUTE_LOADERS_COMMENTS_LAYOUTS,
                POP_CMSMODEL_ROUTE_LOADERS_TAGS_LAYOUTS,
            )
        );
    }
}
