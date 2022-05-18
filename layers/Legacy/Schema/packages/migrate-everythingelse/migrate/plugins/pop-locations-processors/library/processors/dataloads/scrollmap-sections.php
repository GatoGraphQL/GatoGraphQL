<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\ComponentProcessors\PastEventComponentProcessorTrait;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_EM_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    use PastEventComponentProcessorTrait;

    public final const MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP = 'dataload-searchusers-scrollmap';
    public final const MODULE_DATALOAD_USERS_SCROLLMAP = 'dataload-users-scrollmap';
    public final const MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP = 'dataload-users-horizontalscrollmap';
    public final const MODULE_DATALOAD_EVENTS_SCROLLMAP = 'dataload-events-scrollmap';
    public final const MODULE_DATALOAD_PASTEVENTS_SCROLLMAP = 'dataload-pastevents-scrollmap';
    public final const MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP = 'dataload-events-horizontalscrollmap';
    public final const MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP = 'dataload-authorevents-scrollmap';
    public final const MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP = 'dataload-authorpastevents-scrollmap';
    public final const MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP = 'dataload-authorevents-horizontalscrollmap';
    public final const MODULE_DATALOAD_TAGEVENTS_SCROLLMAP = 'dataload-tagevents-scrollmap';
    public final const MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP = 'dataload-tagpastevents-scrollmap';
    public final const MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP = 'dataload-tagevents-horizontalscrollmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP => POP_BLOG_ROUTE_SEARCHUSERS,
            self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP => UsersModuleConfiguration::getUsersRoute(),
            self::MODULE_DATALOAD_USERS_SCROLLMAP => UsersModuleConfiguration::getUsersRoute(),
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_SEARCHUSERS_SCROLLMAP],
            self::MODULE_DATALOAD_USERS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_USERS_SCROLLMAP],
            self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_USERS_HORIZONTALSCROLLMAP],
            self::MODULE_DATALOAD_EVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_EVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_PASTEVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_EVENTS_HORIZONTALSCROLLMAP],
            self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHOREVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHORPASTEVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_AUTHOREVENTS_HORIZONTALSCROLLMAP],
            self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_TAGEVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_TAGPASTEVENTS_SCROLLMAP],
            self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSections::class, GD_EM_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_TAGEVENTS_HORIZONTALSCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];

            case self::MODULE_DATALOAD_EVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTS];

            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_AUTHOREVENTS];

            case self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_TAGEVENTS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_USERS_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],

            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP],
        );
        $horizontalmaps = array(
            [self::class, self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP],
        );
        if (in_array($module, $maps)) {
            $format = POP_FORMAT_MAP;
        } elseif (in_array($module, $horizontalmaps)) {
            $format = POP_FORMAT_HORIZONTALMAP;
        }

        return $format ?? parent::getFormat($module);
    }

    // public function getNature(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
    //         case self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($module);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
                $this->addPastEventImmutableDataloadQueryArgs($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP:
            case self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);

            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_EVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_EVENTS_HORIZONTALSCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP:
            case self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'past events', TranslationAPIFacade::getInstance()->__('organizations', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_SCROLLMAP:
            case self::MODULE_DATALOAD_USERS_HORIZONTALSCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }

        switch ($module[1]) {
            case self::MODULE_DATALOAD_SEARCHUSERS_SCROLLMAP:
                // Search: don't bring anything unless we're filtering (no results initially)
                // if ($filter_module = $this->getFilterSubmodule($module)) {
                //     $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                //     $filter = $moduleprocessor_manager->getProcessor($filter_module)->getFilter($filter_module);
                // }
                // if (!$filter || !\PoP\Engine\FilterUtils::filteringBy($filter)) {
                if (!$this->getActiveDataloadQueryArgsFilteringModules($module)) {
                    $this->setProp($module, $props, 'skip-data-load', true);
                }
                break;
        }

        // Events: choose to only select past/future
        $past = array(
            [self::class, self::MODULE_DATALOAD_PASTEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGPASTEVENTS_SCROLLMAP],
        );
        $future = array(
            [self::class, self::MODULE_DATALOAD_EVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_AUTHOREVENTS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_TAGEVENTS_SCROLLMAP],
        );
        if (in_array($module, $past)) {
            $daterange_class = 'daterange-past opens-right';
        } elseif (in_array($module, $future)) {
            $daterange_class = 'daterange-future opens-right';
        }
        if ($daterange_class) {
            $this->setProp([PoP_Events_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Events_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_EVENTSCOPE], $props, 'daterange-class', $daterange_class);
        }

        parent::initModelProps($module, $props);
    }
}



