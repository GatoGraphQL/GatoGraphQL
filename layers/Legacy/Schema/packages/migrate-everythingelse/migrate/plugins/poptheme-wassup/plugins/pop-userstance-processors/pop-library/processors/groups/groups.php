<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_USERSTANCE_GROUP_HOMETOP = 'group-userstance-hometop';
    public final const COMPONENT_USERSTANCE_GROUP_HOME_WIDGETAREA = 'group-userstance-home-widgetarea';
    public final const COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES = 'group-userstance-home-stanceslides';
    public final const COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE = 'group-userstance-home-rightpane';
    public final const COMPONENT_USERSTANCE_GROUP_AUTHORTOP = 'group-userstance-author-top';
    public final const COMPONENT_USERSTANCE_GROUP_AUTHOR_WIDGETAREA = 'group-userstance-author-widgetarea';
    public final const COMPONENT_USERSTANCE_GROUP_AUTHOR_THOUGHTSLIDES = 'group-userstance-author-stanceslides';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_USERSTANCE_GROUP_HOMETOP],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_WIDGETAREA],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_AUTHORTOP],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_AUTHOR_WIDGETAREA],
            [self::class, self::COMPONENT_USERSTANCE_GROUP_AUTHOR_THOUGHTSLIDES],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_USERSTANCE_GROUP_HOMETOP:
                $ret[] = [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_HOME_COMPACTWELCOME];
                $ret[] = [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_WIDGETAREA];

                if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTS_CAROUSEL];
                }

                // Allow TPPDebate to add the Featured Block
                if ($layouts = \PoP\Root\App::applyFilters(
                    'UserStance_Module_Processor_CustomGroups:components:hometop',
                    array(),
                    $component
                )) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;

            case self::COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES:
                $ret[] = [UserStance_Module_Processor_Codes::class, UserStance_Module_Processor_Codes::COMPONENT_USERSTANCE_HTMLCODE_STANCESLIDESTITLE];
                $ret[] = [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYINDIVIDUALS_CAROUSEL];
                $ret[] = [UserStance_URE_Module_Processor_CustomSectionBlocks::class, UserStance_URE_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_STANCES_BYORGANIZATIONS_CAROUSEL];
                break;

            case self::COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_CREATEORUPDATE];
                $ret[] = [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_STANCESTATS];
                break;

            case self::COMPONENT_USERSTANCE_GROUP_AUTHOR_THOUGHTSLIDES:
                $ret[] = [UserStance_Module_Processor_CustomSectionBlocks::class, UserStance_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORSTANCES_CAROUSEL];
                break;

            case self::COMPONENT_USERSTANCE_GROUP_HOME_WIDGETAREA:
                $ret[] = [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES];
                $ret[] = [self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE];
                break;

            case self::COMPONENT_USERSTANCE_GROUP_AUTHOR_WIDGETAREA:
                $ret[] = [self::class, self::COMPONENT_USERSTANCE_GROUP_AUTHOR_THOUGHTSLIDES];
                break;

            case self::COMPONENT_USERSTANCE_GROUP_AUTHORTOP:
                $ret[] = [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_AUTHOR_DESCRIPTION];
                $ret[] = [self::class, self::COMPONENT_USERSTANCE_GROUP_AUTHOR_WIDGETAREA];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_USERSTANCE_GROUP_HOMETOP:
                // Hide if no events
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTS_CAROUSEL]], $props, 'do-not-render-if-no-results', true);

                // Set the grid as 1x2
                $grid = array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 2
                );
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTS_CAROUSEL], [PoP_Events_Module_Processor_CustomSectionDataloads::class, PoP_Events_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_EVENTS_CAROUSEL], [GD_EM_Module_Processor_CustomCarousels::class, GD_EM_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_EVENTS], [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_EVENTS]], $props, 'layout-grid', $grid);
                break;

            case self::COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE:
                $this->appendProp($component, $props, 'class', 'row');

                $this->appendProp([[UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_CREATEORUPDATE]], $props, 'class', 'col-sm-8 pop-widget');
                $this->setProp(
                    [
                        [UserStance_Module_Processor_CreateUpdatePostBlocks::class, UserStance_Module_Processor_CreateUpdatePostBlocks::COMPONENT_BLOCK_STANCE_CREATEORUPDATE],
                    ],
                    $props,
                    'title',
                    sprintf(
                        '<small>%s</small>',
                        getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).sprintf(TranslationAPIFacade::getInstance()->__('Your %s', 'pop-userstance-processors'), PoP_UserStance_PostNameUtils::getNameLc())
                    )
                );

                $this->appendProp([[UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_STANCESTATS]], $props, 'class', 'col-sm-4');
                $this->setProp(
                    [
                        [UserStance_Module_Processor_CustomControlGroups::class, UserStance_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_STANCESTATS],
                    ],
                    $props,
                    'title',
                    sprintf(
                        '<small>%s</small>',
                        getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('Stats', 'pop-userstance-processors')
                    )
                );
                break;

            case self::COMPONENT_USERSTANCE_GROUP_HOME_WIDGETAREA:
                $this->appendProp($component, $props, 'class', 'vt-home-widgetarea row');

                $this->appendProp([[self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES]], $props, 'class', 'col-sm-12');
                $this->setProp(
                    [self::COMPONENT_USERSTANCE_GROUP_HOME_STANCESLIDES],
                    $props,
                    'title',
                    sprintf(
                        '<small>%s</small>',
                        getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).PoP_UserStanceProcessors_Utils::getLatestvotesTitle()
                    )
                );

                $this->appendProp([[self::class, self::COMPONENT_USERSTANCE_GROUP_HOME_RIGHTPANE]], $props, 'class', 'col-sm-12');
                break;

            case self::COMPONENT_USERSTANCE_GROUP_AUTHOR_THOUGHTSLIDES:
                $this->appendProp($component, $props, 'class', 'vt-author-thoughtslides');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


