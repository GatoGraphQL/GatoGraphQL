<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ReplyCommentViewComponentHeadersBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($post_module = $this->getPostSubmodule($module)) {
            $ret[] = $post_module;
        }
        if ($comment_module = $this->getCommentSubmodule($module)) {
            $ret[] = $comment_module;
        }

        return $ret;
    }

    public function getPostSubmodule(array $module)
    {
        return null;
    }

    public function getCommentSubmodule(array $module)
    {
        return null;
    }

    public function getInresponsetoTitle(array $module, array &$props)
    {
        return sprintf(
            '<p><em>%s</em></p>',
            TranslationAPIFacade::getInstance()->__('In response to:', 'pop-coreprocessors')
        );
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_TITLES]['inresponseto'] = $this->getInresponsetoTitle($module, $props);

        if ($post_module = $this->getPostSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['post'] = ModuleUtils::getModuleOutputName((array) $post_module);
        }

        if ($comment_module = $this->getCommentSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['comment'] = ModuleUtils::getModuleOutputName((array) $comment_module);
        }

        return $ret;
    }
}
