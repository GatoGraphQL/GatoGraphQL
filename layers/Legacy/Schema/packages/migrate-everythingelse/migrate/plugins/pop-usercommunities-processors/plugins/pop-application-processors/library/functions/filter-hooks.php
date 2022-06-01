<?php
use PoP\ComponentModel\State\ApplicationState;

/**
 * Add the filtercomponents to all filters
 */
\PoP\Root\App::addFilter('Users:FilterInnerComponentProcessor:inputComponents', 'gdUreAddFiltercomponentCommunitiesUser', 10, 2);
function gdUreAddFiltercomponentCommunitiesUser($filterinputs, \PoP\ComponentModel\Component\Component $component)
{
    if (in_array($component, [
        [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_USERS],
    ])) {
        $pos = array_search(
            [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_NAME],
            $filterinputs
        );
        if ($pos !== false) {
            array_splice(
                $filterinputs,
                $pos+1,
                0,
                [
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                ]
            );
        }
    }
    return $filterinputs;
}
\PoP\Root\App::addFilter('SimpleFilterInners:inputComponents', 'gdUreAddSimpleFiltercomponentCommunitiesUser', 10, 2);
function gdUreAddSimpleFiltercomponentCommunitiesUser($filterinputs, \PoP\ComponentModel\Component\Component $component)
{
    if (in_array($component, [
        [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_USERS],
    ])) {
        $pos = array_search(
            [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
            $filterinputs
        );
        if ($pos !== false) {
            array_splice(
                $filterinputs,
                $pos+1,
                0,
                [
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                ]
            );
        }
    }
    return $filterinputs;
}

\PoP\Root\App::addFilter('FilterInnerComponentProcessor:inputComponents', 'gdUreAddFiltercomponentCommunitiesPost');
function gdUreAddFiltercomponentCommunitiesPost($filterinputs)
{
    // Place the 'communities' component before the 'profiles' one, so that we can use {{lastGeneratedId}} to reference it
    // Also replace the original 'profiles' component with the new one, so we can add the extra-templates attr into the selected typeahead module
    $pos = array_search(
        [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::COMPONENT_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
        $filterinputs
    );
    if ($pos !== false) {
        array_splice(
            $filterinputs,
            $pos,
            0,//1,
            [
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                // [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ]
        );
    }

    $pos = array_search(
        [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        $filterinputs
    );
    if ($pos !== false) {
        array_splice(
            $filterinputs,
            $pos,
            0,//1,
            [
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                // self::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES,
            ]
        );
    }
    return $filterinputs;
}

// Add the author users filtercomponent on the Community author page
\PoP\Root\App::addFilter('Blog:FilterInnerComponentProcessor:inputComponents', 'gdUreAddFiltercomponentCommunityusers', 10, 2);
function gdUreAddFiltercomponentCommunityusers($filterinputs, \PoP\ComponentModel\Component\Component $component)
{
    if (in_array($component, [
        [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORCONTENT],
    ])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Check if the user is showing the community. If showing user, then no need for this
        if (gdUreIsCommunity($author) && \PoP\Root\App::getState('source') == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {

            // Add it after the search
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FILTERINPUTGROUP_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::COMPONENT_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
                ]
            );
        }
    }
    return $filterinputs;
}
\PoP\Root\App::addFilter('SimpleFilterInners:inputComponents', 'gdUreAddSimpleFiltercomponentCommunityusers', 10, 2);
function gdUreAddSimpleFiltercomponentCommunityusers($filterinputs, \PoP\ComponentModel\Component\Component $component)
{
    if (in_array($component, [
        [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
    ])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Check if the user is showing the community. If showing user, then no need for this
        if (gdUreIsCommunity($author) && \PoP\Root\App::getState('source') == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {

            // Add it after the search
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
                ]
            );
        }
    }
    return $filterinputs;
}
