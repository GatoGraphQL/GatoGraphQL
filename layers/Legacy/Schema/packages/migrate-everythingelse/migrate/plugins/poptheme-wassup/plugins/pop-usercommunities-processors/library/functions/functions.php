<?php

\PoP\Root\App::addFilter('pop_component:sidebar_author:components', 'gdUreAuthorsidebarsComponents', 10, 2);
function gdUreAuthorsidebarsComponents($components, $section)
{
    if (PoP_ApplicationProcessors_Utils::addAuthorWidgetDetails()) {
        // Embed the extra components
        array_splice(
        	$components, 
        	array_search(
        		[PoP_Module_Processor_SidebarComponentWrappers::class, PoP_Module_Processor_SidebarComponentWrappers::MODULE_WIDGETWRAPPER_AUTHOR_CONTACT], 
        		$components)
        	, 
        	0, 
        	[
	        	[GD_URE_Module_Processor_SidebarComponentsWrappers::class, GD_URE_Module_Processor_SidebarComponentsWrappers::MODULE_URE_WIDGETWRAPPER_COMMUNITIES],
	        ]
        );
    }

    return $components;
}
