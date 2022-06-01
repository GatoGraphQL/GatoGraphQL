<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewUserLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWUSER];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = array_merge(
            parent::getLeafComponentFields($component, $props),
            array('displayName', 'isProfile')
        );

        if ($this->showTitle($component)) {
            $ret[] = 'title';
        }
        if ($this->showShortDescription($component)) {
            $ret[] = 'shortDescriptionFormatted';
        }

        return $ret;
    }

    public function showShortDescription(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($belowavatar_components = $this->getBelowavatarLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowavatar_components
            );
        }
        if ($belowexcerpt_templates = $this->getBelowexcerptLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowexcerpt_templates
            );
        }
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubcomponent($component)) {
                $ret[] = $useravatar;
            }
        }

        return $ret;
    }

    protected function showTitle(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    public function getUseravatarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getBelowavatarLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getBelowexcerptLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    // function getExtraClass(\PoP\ComponentModel\Component\Component $component) {

    //     return '';
    // }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowavatar_components = $this->getBelowavatarLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowavatar'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $belowavatar_components
            );
        }
        if ($belowexcerpt_components = $this->getBelowexcerptLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowexcerpt'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...), 
                $belowexcerpt_components
            );
        }
        if ($this->showShortDescription($component)) {
            $ret['show-short-description'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubcomponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($useravatar);
            }
        }

        return $ret;
    }
}
