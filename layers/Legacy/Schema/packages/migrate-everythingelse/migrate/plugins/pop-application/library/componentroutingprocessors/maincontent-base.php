<?php
namespace PoP\Application;

abstract class AbstractMainContentComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {

        // If no group specified, then use the "Content Module" one (initially representing the entry module, and overridable)
        // Is it overridable, so the theme can also set group "Page Section Main Content" in addition
        return \PoP\Root\App::applyFilters(
            '\PoP\Application\AbstractMainContentComponentRoutingProcessor:maincontentgroups',
            array(
                POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT,
            )
        );
    }
}
