<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

abstract class PoP_Module_Processor_MessageBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getCustomPostTypes(array $componentVariation)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsService = CMSServiceFacade::getInstance();
        $sticky = $cmsService->getOption('sticky_posts');
        if (!$sticky) {
            // $sticky = array('-1');
            $ret['load'] = false;
        }

        $ret['custompost-types'] = $this->getCustomPostTypes($componentVariation);
        // $ret['limit'] = 1;
        $ret['include'] = [$sticky];

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }

    // function initModelProps(array $componentVariation, array &$props) {

    //     $layout = $this->getLayoutSubmodule($componentVariation);
    //     $this->setProp($layout, $props, 'layout-inner', [self::class, self::MODULE_LAYOUTPOST_SPEECHBUBBLE]);

    //     parent::initModelProps($componentVariation, $props);
    // }
}
