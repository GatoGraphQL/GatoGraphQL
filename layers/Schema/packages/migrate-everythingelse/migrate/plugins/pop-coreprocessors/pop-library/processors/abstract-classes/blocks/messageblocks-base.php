<?php
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;

abstract class PoP_Module_Processor_MessageBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getPostTypes(array $module)
    {
        return [];
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        // If no sticky posts, then make sure we're bringing no results
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $sticky = $cmsengineapi->getOption('sticky_posts');
        if (!$sticky) {
            // $sticky = array('-1');
            $ret['load'] = false;
        }

        $ret['custompost-types'] = $this->getPostTypes($module);
        // $ret['limit'] = 1;
        $ret['include'] = [$sticky];

        return $ret;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
    }

    // function initModelProps(array $module, array &$props) {

    //     $layout = $this->getLayoutSubmodule($module);
    //     $this->setProp($layout, $props, 'layout-inner', [self::class, self::MODULE_LAYOUTPOST_SPEECHBUBBLE]);

    //     parent::initModelProps($module, $props);
    // }
}
