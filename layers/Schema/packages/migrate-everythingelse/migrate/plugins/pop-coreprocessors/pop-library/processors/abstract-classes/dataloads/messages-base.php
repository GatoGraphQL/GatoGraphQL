<?php
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

abstract class PoP_Module_Processor_MessageDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    public function getPostTypes(array $module)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        $cmsService = CMSServiceFacade::getInstance();
        if ($sticky = $cmsService->getOption('sticky_posts')) {
            $ret['include'] = [$sticky];
            $ret['custompost-types'] = $this->getPostTypes($module);
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

    public function getTypeResolverClass(array $module): ?string
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
    }
}
