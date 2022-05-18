<?php
use PoP\ComponentModel\State\ApplicationState;

/**
 * Add the filtercomponents to all filters
 */
\PoP\Root\App::addFilter('Users:FilterInnerComponentProcessor:inputmodules', 'gdUreAddFiltercomponentCommunitiesUser', 10, 2);
function gdUreAddFiltercomponentCommunitiesUser($filterinputs, array $componentVariation)
{
    if (in_array($componentVariation, [
        [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_USERS],
    ])) {
        $pos = array_search(
            [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_NAME],
            $filterinputs
        );
        if ($pos !== false) {
            array_splice(
                $filterinputs,
                $pos+1,
                0,
                [
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                ]
            );
        }
    }
    return $filterinputs;
}
\PoP\Root\App::addFilter('SimpleFilterInners:inputmodules', 'gdUreAddSimpleFiltercomponentCommunitiesUser', 10, 2);
function gdUreAddSimpleFiltercomponentCommunitiesUser($filterinputs, array $componentVariation)
{
    if (in_array($componentVariation, [
        [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_USERS],
    ])) {
        $pos = array_search(
            [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
            $filterinputs
        );
        if ($pos !== false) {
            array_splice(
                $filterinputs,
                $pos+1,
                0,
                [
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
                ]
            );
        }
    }
    return $filterinputs;
}

\PoP\Root\App::addFilter('FilterInnerComponentProcessor:inputmodules', 'gdUreAddFiltercomponentCommunitiesPost');
function gdUreAddFiltercomponentCommunitiesPost($filterinputs)
{
    // Place the 'communities' component before the 'profiles' one, so that we can use {{lastGeneratedId}} to reference it
    // Also replace the original 'profiles' component with the new one, so we can add the extra-templates attr into the selected typeahead module
    $pos = array_search(
        [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
        $filterinputs
    );
    if ($pos !== false) {
        array_splice(
            $filterinputs,
            $pos,
            0,//1,
            [
                [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                // [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
            ]
        );
    }

    $pos = array_search(
        [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        $filterinputs
    );
    if ($pos !== false) {
        array_splice(
            $filterinputs,
            $pos,
            0,//1,
            [
                [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                // self::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES,
            ]
        );
    }
    return $filterinputs;
}

// Add the author users filtercomponent on the Community author page
\PoP\Root\App::addFilter('Blog:FilterInnerComponentProcessor:inputmodules', 'gdUreAddFiltercomponentCommunityusers', 10, 2);
function gdUreAddFiltercomponentCommunityusers($filterinputs, array $componentVariation)
{
    if (in_array($componentVariation, [
        [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT],
    ])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Check if the user is showing the community. If showing user, then no need for this
        if (gdUreIsCommunity($author) && \PoP\Root\App::getState('source') == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {

            // Add it after the search
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
                ]
            );
        }
    }
    return $filterinputs;
}
\PoP\Root\App::addFilter('SimpleFilterInners:inputmodules', 'gdUreAddSimpleFiltercomponentCommunityusers', 10, 2);
function gdUreAddSimpleFiltercomponentCommunityusers($filterinputs, array $componentVariation)
{
    if (in_array($componentVariation, [
        [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
    ])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

        // Check if the user is showing the community. If showing user, then no need for this
        if (gdUreIsCommunity($author) && \PoP\Root\App::getState('source') == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {

            // Add it after the search
            array_splice(
                $filterinputs,
                array_search(
                    [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                    $filterinputs
                )+1,
                0,
                [
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
                    [GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::class, GD_URE_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
                ]
            );
        }
    }
    return $filterinputs;
}
