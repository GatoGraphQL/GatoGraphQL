<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_SocialMediaItemsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA_ITEM];
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        return null;
    }
    public function getShortname(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    public function getProvider(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getShareurlField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = array(
            $this->getShareurlField($component, $props),
            'url',
        );

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $title = sprintf(TranslationAPIFacade::getInstance()->__('Share on %s', 'pop-coreprocessors'), $this->getName($component));
        $ret['name'] = $title;
        $ret['short-name'] = $this->getShortname($component);
        $ret['targets']['socialmedia'] = GD_URLPARAM_TARGET_SOCIALMEDIA;
        $ret[GD_JS_TITLES]['share'] = $title;
        $ret[GD_JS_FONTAWESOME] = $this->getFontawesome($component, $props);
        
        $ret['shareurl-field'] = FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
            $this->getProp($component, $props, 'succeeding-typeResolver'),
            $this->getShareurlField($component, $props)
        );

        if ($provider = $this->getProvider($component)) {
            $ret['provider'] = $provider;
        }

        return $ret;
    }
}
