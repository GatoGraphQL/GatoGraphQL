<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ReplyCommentViewComponentHeadersBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($post_component = $this->getPostSubcomponent($component)) {
            $ret[] = $post_component;
        }
        if ($comment_component = $this->getCommentSubcomponent($component)) {
            $ret[] = $comment_component;
        }

        return $ret;
    }

    public function getPostSubcomponent(array $component)
    {
        return null;
    }

    public function getCommentSubcomponent(array $component)
    {
        return null;
    }

    public function getInresponsetoTitle(array $component, array &$props)
    {
        return sprintf(
            '<p><em>%s</em></p>',
            TranslationAPIFacade::getInstance()->__('In response to:', 'pop-coreprocessors')
        );
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_TITLES]['inresponseto'] = $this->getInresponsetoTitle($component, $props);

        if ($post_component = $this->getPostSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['post'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName((array) $post_component);
        }

        if ($comment_component = $this->getCommentSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['comment'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName((array) $comment_component);
        }

        return $ret;
    }
}
