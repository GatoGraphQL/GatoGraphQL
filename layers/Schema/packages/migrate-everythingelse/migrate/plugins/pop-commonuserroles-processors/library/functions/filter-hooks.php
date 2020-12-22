<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Add filtercomponents
 */
HooksAPIFacade::getInstance()->addFilter('CommonUserRoles:FilterInners:inputmodules', 'gdUreAddFiltercomponent', 10, 2);
function gdUreAddFiltercomponent($filterinputs, array $module)
{
	if ($module == [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_INDIVIDUALS]) {
	    array_splice(
	    	$filterinputs, 
	    	array_search(
	    		[GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER], 
	    		$filterinputs
	    	)+1, 
	    	0, 
	    	[
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS],
	    	]
	    );
	} elseif ($module == [PoP_CommonUserRoles_Module_Processor_CustomFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_ORGANIZATIONS]) {
	    array_splice(
	    	$filterinputs, 
	    	array_search(
	    		[GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER], 
	    		$filterinputs
	    	)+1, 
	    	0, 
	    	[
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES], 
	    		[GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES],
	    	]
	    );
	}
    return $filterinputs;
}
HooksAPIFacade::getInstance()->addFilter('CommonUserRoles:SimpleFilterInners:inputmodules', 'gdUreAddSimpleFiltercomponent', 10, 2);
function gdUreAddSimpleFiltercomponent($filterinputs, array $module)
{
	if ($module == [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_INDIVIDUALS]) {
	    array_splice(
	    	$filterinputs, 
	    	array_search(
	    		[GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER], 
	    		$filterinputs
	    	)+1, 
	    	0, 
	    	[
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS],
	    	]
	    );
	} elseif ($module == [PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::class, PoP_CommonUserRoles_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_ORGANIZATIONS]) {
	    array_splice(
	    	$filterinputs, 
	    	array_search(
	    		[GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER], 
	    		$filterinputs
	    	)+1, 
	    	0, 
	    	[
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES], 
	    		[GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
	    	]
	    );
	}
    return $filterinputs;
}
