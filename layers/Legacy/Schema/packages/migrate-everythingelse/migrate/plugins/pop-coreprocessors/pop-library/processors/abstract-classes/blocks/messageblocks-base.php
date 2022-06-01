<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;

abstract class PoP_Module_Processor_MessageBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getCustomPostTypes(\PoP\ComponentModel\Component\Component $component)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsService = CMSServiceFacade::getInstance();
        $sticky = $cmsService->getOption('sticky_posts');
        if (!$sticky) {
            // $sticky = array('-1');
            $ret['load'] = false;
        }

        $ret['custompost-types'] = $this->getCustomPostTypes($component);
        // $ret['limit'] = 1;
        $ret['include'] = [$sticky];

        return $ret;
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }

    // function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     $layout = $this->getLayoutSubcomponent($component);
    //     $this->setProp($layout, $props, 'layout-inner', [self::class, self::COMPONENT_LAYOUTPOST_SPEECHBUBBLE]);

    //     parent::initModelProps($component, $props);
    // }
}
