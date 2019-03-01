<?php
namespace PoP\Engine;

abstract class MainContentPageModuleProcessorBase extends PageModuleProcessorBase
{
    public function getGroups()
    {

        // If no group specified, then use the "Content Module" one (initially representing the entry module, and overridable)
        // Is it overridable, so the theme can also set group "Page Section Main Content" in addition
        return apply_filters(
            '\PoP\Engine\MainContentPageModuleProcessorBase:maincontentgroups',
            array(
                POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE,
            )
        );
    }
}
