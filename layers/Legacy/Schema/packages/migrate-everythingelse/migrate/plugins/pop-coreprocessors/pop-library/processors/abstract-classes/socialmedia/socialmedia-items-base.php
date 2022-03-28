<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_SocialMediaItemsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA_ITEM];
    }

    public function getName(array $module): string
    {
        return null;
    }
    public function getShortname(array $module)
    {
        return null;
    }
    public function getFontawesome(array $module, array &$props)
    {
        return null;
    }
    public function getProvider(array $module)
    {
        return null;
    }

    public function getShareurlField(array $module, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = array(
            $this->getShareurlField($module, $props),
            'url',
        );

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $title = sprintf(TranslationAPIFacade::getInstance()->__('Share on %s', 'pop-coreprocessors'), $this->getName($module));
        $ret['name'] = $title;
        $ret['short-name'] = $this->getShortname($module);
        $ret['targets']['socialmedia'] = GD_URLPARAM_TARGET_SOCIALMEDIA;
        $ret[GD_JS_TITLES]['share'] = $title;
        $ret[GD_JS_FONTAWESOME] = $this->getFontawesome($module, $props);
        
        $ret['shareurl-field'] = FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
            $this->getProp($module, $props, 'succeeding-typeResolver'),
            $this->getShareurlField($module, $props)
        );

        if ($provider = $this->getProvider($module)) {
            $ret['provider'] = $provider;
        }

        return $ret;
    }
}
