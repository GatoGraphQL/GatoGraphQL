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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => POP_ROUTE_AUTHORS,
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => POP_ROUTE_AUTHORS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_FULLVIEW],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_DETAILS],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_THUMBNAIL],
            self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_USERS_LIST],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    // // Single Authors has no filter, because the authors are provided using 'include' which can't be filtered
    // function getFilterSubmodule(array $componentVariation) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:

    //             return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_USERS];
    //     }

    //     return parent::getFilterSubmodule($componentVariation);
    // }

    public function getFormat(array $componentVariation): ?string
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
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($componentVariation, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_CAROUSEL;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    // public function getNature(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
    //             return CustomPostRequestNature::CUSTOMPOST;
    //     }

    //     return parent::getNature($componentVariation);
    // }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsSingleauthors($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_SINGLEAUTHORS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('users', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



