<?php

trait PoP_Blog_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_BLOG_ROUTE_CONTENT,
                POP_BLOG_ROUTE_COMMENTS,
                POP_BLOG_ROUTE_SEARCHCONTENT,
                POP_BLOG_ROUTE_SEARCHUSERS,
            )
        );
    }
}
