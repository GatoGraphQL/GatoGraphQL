<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;

abstract class PoP_Module_Processor_AppendCommentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCRIPT_APPENDCOMMENT];
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = 'customPostID';
        $ret[] = 'parent';

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var TypeResolverInterface */
        $postTypeResolver = $instanceManager->getInstance(PostTypeResolver::class);
        $ret['post-dbkey'] = $postTypeResolver->getTypeName();
        $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = 'comments';

        return $ret;
    }
}
