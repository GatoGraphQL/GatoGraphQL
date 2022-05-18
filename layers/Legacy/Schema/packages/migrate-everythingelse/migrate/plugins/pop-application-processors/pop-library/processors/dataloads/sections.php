<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Module_Processor_CustomSectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS = 'dataload-singleauthors-scroll-details';
    public final const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW = 'dataload-singleauthors-scroll-fullview';
    public final const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL = 'dataload-singleauthors-scroll-thumbnail';
    public final const MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST = 'dataload-singleauthors-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    // // Single Authors has no filter, because the authors are provided using 'include' which can't be filtered
    // function getFilterSubmodule(array $component) {

    //     switch ($component[1]) {

    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:

    //             return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
    //     }

    //     return parent::getFilterSubmodule($component);
    // }

    public function getFormat(array $component): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($component);
    }

    // public function getNature(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($component);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



