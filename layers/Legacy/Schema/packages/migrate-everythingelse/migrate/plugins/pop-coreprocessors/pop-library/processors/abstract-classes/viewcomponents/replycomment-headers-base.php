<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ReplyCommentViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($post_componentVariation = $this->getPostSubmodule($componentVariation)) {
            $ret[] = $post_componentVariation;
        }
        if ($comment_componentVariation = $this->getCommentSubmodule($componentVariation)) {
            $ret[] = $comment_componentVariation;
        }

        return $ret;
    }

    public function getPostSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getCommentSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getInresponsetoTitle(array $componentVariation, array &$props)
    {
        return sprintf(
            '<p><em>%s</em></p>',
            TranslationAPIFacade::getInstance()->__('In response to:', 'pop-coreprocessors')
        );
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret[GD_JS_TITLES]['inresponseto'] = $this->getInresponsetoTitle($componentVariation, $props);

        if ($post_componentVariation = $this->getPostSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['post'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName((array) $post_componentVariation);
        }

        if ($comment_componentVariation = $this->getCommentSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['comment'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName((array) $comment_componentVariation);
        }

        return $ret;
    }
}
