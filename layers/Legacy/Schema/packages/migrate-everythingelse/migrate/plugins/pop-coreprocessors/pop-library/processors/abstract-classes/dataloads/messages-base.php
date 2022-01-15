<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

abstract class PoP_Module_Processor_MessageDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getCustomPostTypes(array $module)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        $cmsService = CMSServiceFacade::getInstance();
        if ($sticky = $cmsService->getOption('sticky_posts')) {
            $ret['include'] = [$sticky];
            $ret['custompost-types'] = $this->getCustomPostTypes($module);
        }

        return $ret;
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $module, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $module, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsService = CMSServiceFacade::getInstance();
        if (!$cmsService->getOption('sticky_posts')) {
            $ret[DataloadingConstants::SKIPDATALOAD] = true;
        }
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
