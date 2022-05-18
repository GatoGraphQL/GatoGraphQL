<?php

/**
 * Add filtercomponents
 */
\PoP\Root\App::addFilter('CommonUserRoles:FilterInnerComponentProcessor:inputmodules', 'gdUreAddFiltercomponent', 10, 2);
function gdUreAddFiltercomponent($filterinputs, array $component)
{
	if ($component == [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_INDIVIDUALS]) {
	    array_splice(
	    	$filterinputs,
	    	array_search(
	    		[GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
	    		$filterinputs
	    	)+1,
	    	0,
	    	[
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS],
	    	]
	    );
	} elseif ($component == [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_ORGANIZATIONS]) {
	    array_splice(
	    	$filterinputs,
	    	array_search(
	    		[GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
	    		$filterinputs
	    	)+1,
	    	0,
	    	[
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES],
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES],
	    	]
	    );
	}
    return $filterinputs;
}
\PoP\Root\App::addFilter('CommonUserRoles:SimpleFilterInners:inputmodules', 'gdUreAddSimpleFiltercomponent', 10, 2);
function gdUreAddSimpleFiltercomponent($filterinputs, array $component)
{
	if ($component == [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_INDIVIDUALS]) {
	    array_splice(
	    	$filterinputs,
	    	array_search(
	    		[GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
	    		$filterinputs
	    	)+1,
	    	0,
	    	[
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS],
	    	]
	    );
	} elseif ($component == [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_ORGANIZATIONS]) {
	    array_splice(
	    	$filterinputs,
	    	array_search(
	    		[GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
	    		$filterinputs
	    	)+1,
	    	0,
	    	[
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES],
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
	    	]
	    );
	}
    return $filterinputs;
}
