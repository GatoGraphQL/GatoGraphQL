<?php

trait PoP_CommonPages_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONPAGES_ROUTE_ABOUT,
                POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES,
                POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
                POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
                POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
            )
        );
    }
}
