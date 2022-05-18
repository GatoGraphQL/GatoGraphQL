<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_SocialMediaItemsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA_ITEM];
    }

    public function getName(array $componentVariation): string
    {
        return null;
    }
    public function getShortname(array $componentVariation)
    {
        return null;
    }
    public function getFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getProvider(array $componentVariation)
    {
        return null;
    }

    public function getShareurlField(array $componentVariation, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = array(
            $this->getShareurlField($componentVariation, $props),
            'url',
        );

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $title = sprintf(TranslationAPIFacade::getInstance()->__('Share on %s', 'pop-coreprocessors'), $this->getName($componentVariation));
        $ret['name'] = $title;
        $ret['short-name'] = $this->getShortname($componentVariation);
        $ret['targets']['socialmedia'] = GD_URLPARAM_TARGET_SOCIALMEDIA;
        $ret[GD_JS_TITLES]['share'] = $title;
        $ret[GD_JS_FONTAWESOME] = $this->getFontawesome($componentVariation, $props);
        
        $ret['shareurl-field'] = FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
            $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
            $this->getShareurlField($componentVariation, $props)
        );

        if ($provider = $this->getProvider($componentVariation)) {
            $ret['provider'] = $provider;
        }

        return $ret;
    }
}
