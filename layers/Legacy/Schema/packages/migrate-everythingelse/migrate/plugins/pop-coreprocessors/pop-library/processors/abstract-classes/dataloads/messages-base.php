<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

abstract class PoP_Module_Processor_MessageDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getCustomPostTypes(array $componentVariation)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        $cmsService = CMSServiceFacade::getInstance();
        if ($sticky = $cmsService->getOption('sticky_posts')) {
            $ret['include'] = [$sticky];
            $ret['custompost-types'] = $this->getCustomPostTypes($componentVariation);
        }

        return $ret;
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $componentVariation, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $componentVariation, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsService = CMSServiceFacade::getInstance();
        if (!$cmsService->getOption('sticky_posts')) {
            $ret[DataloadingConstants::SKIPDATALOAD] = true;
        }
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
