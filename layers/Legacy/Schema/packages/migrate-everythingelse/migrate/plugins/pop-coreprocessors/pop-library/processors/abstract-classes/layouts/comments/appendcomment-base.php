<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

abstract class PoP_Module_Processor_AppendCommentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCRIPT_APPENDCOMMENT];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        $ret[] = 'customPostID';
        $ret[] = 'parent';

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var RelationalTypeResolverInterface */
        $postObjectTypeResolver = $instanceManager->getInstance(PostObjectTypeResolver::class);
        $ret['post-dbkey'] = $postObjectTypeResolver->getTypeName();
        $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = 'comments';

        return $ret;
    }
}
