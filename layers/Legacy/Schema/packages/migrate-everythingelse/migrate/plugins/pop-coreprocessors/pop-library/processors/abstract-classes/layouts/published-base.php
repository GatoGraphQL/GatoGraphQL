<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_PostStatusDateLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTSTATUSDATE];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('date', 'status');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_TITLES] = array(
            Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Published', 'pop-coreprocessors'),
            Status::PENDING => TranslationAPIFacade::getInstance()->__('Pending', 'pop-coreprocessors'),
            Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft', 'pop-coreprocessors'),
        );

        return $ret;
    }
}
