<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_URE_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP = 'dataload-organizations-scrollmap';
    public final const MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP = 'dataload-individuals-scrollmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP => POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSections::class, GD_URE_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_ORGANIZATIONS_SCROLLMAP],
            self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP => [GD_URE_Module_Processor_CustomScrollMapSections::class, GD_URE_Module_Processor_CustomScrollMapSections::MODULE_SCROLLMAP_INDIVIDUALS_SCROLLMAP],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_INDIVIDUALS];

            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP:
                return [PoP_CommonUserRoles_Module_Processor_CustomFilters::class, PoP_CommonUserRoles_Module_Processor_CustomFilters::MODULE_FILTER_ORGANIZATIONS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {
        $maps = array(
            [self::class, self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP],
            [self::class, self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP],
        );
        if (in_array($componentVariation, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP:
                $ret['role'] = GD_URE_ROLE_ORGANIZATION;
                break;

            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP:
                $ret['role'] = GD_URE_ROLE_INDIVIDUAL;
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP:
            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_ORGANIZATIONS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('organizations', 'poptheme-wassup'));
                break;

            case self::MODULE_DATALOAD_INDIVIDUALS_SCROLLMAP:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('individuals', 'poptheme-wassup'));
                break;
        }
        parent::initModelProps($componentVariation, $props);
    }
}



