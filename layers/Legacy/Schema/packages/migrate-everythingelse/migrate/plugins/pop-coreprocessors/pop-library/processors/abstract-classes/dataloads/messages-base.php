<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

abstract class PoP_Module_Processor_MessageDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getCustomPostTypes(array $component)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        $cmsService = CMSServiceFacade::getInstance();
        if ($sticky = $cmsService->getOption('sticky_posts')) {
            $ret['include'] = [$sticky];
            $ret['custompost-types'] = $this->getCustomPostTypes($component);
        }

        return $ret;
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $component, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $component, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsService = CMSServiceFacade::getInstance();
        if (!$cmsService->getOption('sticky_posts')) {
            $ret[DataloadingConstants::SKIPDATALOAD] = true;
        }
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
