<?php

trait PoP_RelatedPosts_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
            )
        );
    }

    public function isFunctional()
    {
        return array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => true,
        );
    }
}
